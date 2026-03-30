<?php
/**
 * Helper functions.
 *
 * @package NMCOR
 */

defined('ABSPATH') || exit;

/**
 * Get latest posts of any type.
 */
function nmcor_get_latest(string $post_type = 'post', int $count = 4, array $extra_args = []): array {
	$args = array_merge([
		'post_type'      => $post_type,
		'posts_per_page' => $count,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	], $extra_args);

	return get_posts($args);
}

/**
 * Get upcoming events (ordered by event date).
 */
function nmcor_get_upcoming_events(int $count = 4): array {
	return get_posts([
		'post_type'      => 'nmcor_event',
		'posts_per_page' => $count,
		'post_status'    => 'publish',
		'meta_key'       => 'nmcor_event_date_start',
		'orderby'        => 'meta_value',
		'order'          => 'ASC',
		'meta_query'     => [
			[
				'key'     => 'nmcor_event_date_start',
				'value'   => date('Y-m-d'),
				'compare' => '>=',
				'type'    => 'DATE',
			],
		],
	]);
}

/**
 * Get past events.
 */
function nmcor_get_past_events(int $count = 8): array {
	return get_posts([
		'post_type'      => 'nmcor_event',
		'posts_per_page' => $count,
		'post_status'    => 'publish',
		'meta_key'       => 'nmcor_event_date_start',
		'orderby'        => 'meta_value',
		'order'          => 'DESC',
		'meta_query'     => [
			[
				'key'     => 'nmcor_event_date_start',
				'value'   => date('Y-m-d'),
				'compare' => '<',
				'type'    => 'DATE',
			],
		],
	]);
}

/**
 * Get featured podcast episodes.
 */
function nmcor_get_featured_episodes(int $count = 3): array {
	return get_posts([
		'post_type'      => 'podcast_episode',
		'posts_per_page' => $count,
		'post_status'    => 'publish',
		'meta_query'     => [
			[
				'key'   => 'nmcor_episode_is_featured',
				'value' => '1',
			],
		],
	]);
}

/**
 * Get the latest podcast episode.
 */
function nmcor_get_latest_episode(): ?WP_Post {
	$episodes = get_posts([
		'post_type'      => 'podcast_episode',
		'posts_per_page' => 1,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	]);
	return $episodes[0] ?? null;
}

/**
 * Get "sticky" / featured posts for a section.
 * Uses a simple approach: posts with a specific tag or custom field.
 */
function nmcor_get_featured_posts(string $post_type = 'post', int $count = 1): array {
	if ($post_type === 'post') {
		$sticky = get_option('sticky_posts');
		if ($sticky) {
			return get_posts([
				'post_type'      => 'post',
				'posts_per_page' => $count,
				'post__in'       => $sticky,
				'post_status'    => 'publish',
			]);
		}
	}
	// Fallback: return latest
	return nmcor_get_latest($post_type, $count);
}

/**
 * Get all content linked to a book_author.
 */
function nmcor_get_author_content(int $author_id): array {
	$content = [
		'books'    => [],
		'reviews'  => [],
		'articles' => [],
		'episodes' => [],
	];

	// Books linked via ACF relationship
	$book_ids = get_field('nmcor_author_books', $author_id);
	if ($book_ids && is_array($book_ids)) {
		$content['books'] = array_filter(array_map('get_post', $book_ids));
	}

	return $content;
}

/**
 * Sanitize and truncate text for meta descriptions.
 */
function nmcor_meta_description(int $post_id = 0, int $length = 160): string {
	$post_id = $post_id ?: get_the_ID();
	$excerpt = get_the_excerpt($post_id);

	if (!$excerpt) {
		$excerpt = wp_strip_all_tags(get_post_field('post_content', $post_id));
	}

	if (strlen($excerpt) > $length) {
		$excerpt = substr($excerpt, 0, $length - 3) . '...';
	}

	return $excerpt;
}

/**
 * Check if ACF is available.
 */
function nmcor_has_acf(): bool {
	return function_exists('get_field');
}

/**
 * Safe ACF get_field wrapper.
 */
function nmcor_field(string $field, int $post_id = 0) {
	if (!nmcor_has_acf()) {
		$post_id = $post_id ?: get_the_ID();
		return get_post_meta($post_id, $field, true);
	}
	return get_field($field, $post_id ?: false);
}

/**
 * Generate structured data (JSON-LD) for a review.
 */
function nmcor_review_schema(int $post_id = 0): void {
	$post_id = $post_id ?: get_the_ID();
	$book = nmcor_get_review_book($post_id);
	$rating = get_post_meta($post_id, 'nmcor_review_rating', true);

	if (!$book) {
		return;
	}

	$schema = [
		'@context' => 'https://schema.org',
		'@type'    => 'Review',
		'name'     => get_the_title($post_id),
		'author'   => [
			'@type' => 'Organization',
			'name'  => 'Not My Cup of Read',
		],
		'itemReviewed' => [
			'@type' => 'Book',
			'name'  => get_the_title($book->ID),
		],
		'datePublished' => get_the_date('c', $post_id),
	];

	if ($rating) {
		$schema['reviewRating'] = [
			'@type'       => 'Rating',
			'ratingValue' => (float) $rating,
			'bestRating'  => 5,
		];
	}

	$authors = nmcor_get_book_authors($book->ID);
	if ($authors) {
		$schema['itemReviewed']['author'] = array_map(fn($a) => [
			'@type' => 'Person',
			'name'  => $a->post_title,
		], $authors);
	}

	echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
}

/**
 * Generate podcast episode schema.
 */
function nmcor_episode_schema(int $post_id = 0): void {
	$post_id = $post_id ?: get_the_ID();

	$schema = [
		'@context'    => 'https://schema.org',
		'@type'       => 'PodcastEpisode',
		'name'        => get_the_title($post_id),
		'description' => nmcor_meta_description($post_id),
		'datePublished' => get_the_date('c', $post_id),
		'partOfSeries' => [
			'@type' => 'PodcastSeries',
			'name'  => 'Not My Cup of Read',
		],
	];

	$duration = get_post_meta($post_id, 'nmcor_episode_duration', true);
	if ($duration) {
		$schema['timeRequired'] = $duration;
	}

	$audio_url = get_post_meta($post_id, 'nmcor_episode_audio_url', true);
	if ($audio_url) {
		$schema['associatedMedia'] = [
			'@type'      => 'MediaObject',
			'contentUrl' => $audio_url,
		];
	}

	echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
}
