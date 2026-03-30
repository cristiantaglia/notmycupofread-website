<?php
/**
 * Template tags for use in theme templates.
 *
 * @package NMCOR
 */

defined('ABSPATH') || exit;

/**
 * Get review's associated book data.
 */
function nmcor_get_review_book(int $review_id = 0): ?WP_Post {
	$review_id = $review_id ?: get_the_ID();
	$book_ids = get_field('nmcor_review_book', $review_id);

	if (!$book_ids) {
		return null;
	}

	$book_id = is_array($book_ids) ? $book_ids[0] : $book_ids;
	return get_post($book_id);
}

/**
 * Get book's author(s).
 */
function nmcor_get_book_authors(int $book_id = 0): array {
	$book_id = $book_id ?: get_the_ID();
	$author_ids = get_field('nmcor_book_author', $book_id);

	if (!$author_ids) {
		return [];
	}

	if (!is_array($author_ids)) {
		$author_ids = [$author_ids];
	}

	return array_filter(array_map('get_post', $author_ids));
}

/**
 * Get linked podcast episode for a post/review.
 */
function nmcor_get_linked_episode(int $post_id = 0): ?WP_Post {
	$post_id = $post_id ?: get_the_ID();
	$field_key = get_post_type($post_id) === 'review' ? 'nmcor_review_episode' : 'nmcor_post_episode';
	$episode_ids = get_field($field_key, $post_id);

	if (!$episode_ids) {
		return null;
	}

	$episode_id = is_array($episode_ids) ? $episode_ids[0] : $episode_ids;
	return get_post($episode_id);
}

/**
 * Get episode's books.
 */
function nmcor_get_episode_books(int $episode_id = 0): array {
	$episode_id = $episode_id ?: get_the_ID();
	$book_ids = get_field('nmcor_episode_books', $episode_id);

	if (!$book_ids || !is_array($book_ids)) {
		return [];
	}

	return array_filter(array_map('get_post', $book_ids));
}

/**
 * Get content related to a book (reviews, approfondimenti, episodes).
 */
function nmcor_get_book_related_content(int $book_id): array {
	$related = ['reviews' => [], 'articles' => [], 'episodes' => []];

	// Reviews that reference this book
	if (function_exists('get_field')) {
		$reviews = get_posts([
			'post_type'      => 'review',
			'posts_per_page' => -1,
			'meta_query'     => [
				[
					'key'     => 'nmcor_review_book',
					'value'   => sprintf(':"%d";', $book_id),
					'compare' => 'LIKE',
				],
			],
		]);
		$related['reviews'] = $reviews;
	}

	return $related;
}

/**
 * Output star rating HTML.
 */
function nmcor_rating_stars(float $rating, int $max = 5): string {
	$html = '<div class="nmcor-rating" aria-label="' . sprintf(__('Valutazione: %s su %d', 'nmcor'), $rating, $max) . '">';
	for ($i = 1; $i <= $max; $i++) {
		if ($i <= floor($rating)) {
			$html .= '<span class="star star--full" aria-hidden="true">&#9733;</span>';
		} elseif ($i - 0.5 <= $rating) {
			$html .= '<span class="star star--half" aria-hidden="true">&#9733;</span>';
		} else {
			$html .= '<span class="star star--empty" aria-hidden="true">&#9734;</span>';
		}
	}
	$html .= '</div>';
	return $html;
}

/**
 * Format event date range.
 */
function nmcor_format_event_dates(int $event_id = 0): string {
	$event_id = $event_id ?: get_the_ID();
	$start = get_post_meta($event_id, 'nmcor_event_date_start', true);
	$end   = get_post_meta($event_id, 'nmcor_event_date_end', true);

	if (!$start) {
		return '';
	}

	$start_ts = strtotime($start);
	$months_it = [
		1 => 'Gennaio', 2 => 'Febbraio', 3 => 'Marzo', 4 => 'Aprile',
		5 => 'Maggio', 6 => 'Giugno', 7 => 'Luglio', 8 => 'Agosto',
		9 => 'Settembre', 10 => 'Ottobre', 11 => 'Novembre', 12 => 'Dicembre',
	];

	$start_str = date('j', $start_ts) . ' ' . $months_it[(int) date('n', $start_ts)] . ' ' . date('Y', $start_ts);

	if ($end && $end !== $start) {
		$end_ts = strtotime($end);
		if (date('n Y', $start_ts) === date('n Y', $end_ts)) {
			return date('j', $start_ts) . ' - ' . date('j', $end_ts) . ' ' . $months_it[(int) date('n', $end_ts)] . ' ' . date('Y', $end_ts);
		}
		$end_str = date('j', $end_ts) . ' ' . $months_it[(int) date('n', $end_ts)] . ' ' . date('Y', $end_ts);
		return $start_str . ' — ' . $end_str;
	}

	return $start_str;
}

/**
 * Get event status badge.
 */
function nmcor_event_status_badge(int $event_id = 0): string {
	$event_id = $event_id ?: get_the_ID();
	$status = get_post_meta($event_id, 'nmcor_event_status', true) ?: 'upcoming';

	// Auto-detect past events
	$start_date = get_post_meta($event_id, 'nmcor_event_date_start', true);
	if ($start_date && strtotime($start_date) < time() && $status === 'upcoming') {
		$status = 'past';
	}

	$labels = [
		'upcoming'  => __('In programma', 'nmcor'),
		'past'      => __('Passato', 'nmcor'),
		'cancelled' => __('Annullato', 'nmcor'),
	];

	return '<span class="event-badge event-badge--' . esc_attr($status) . '">' . esc_html($labels[$status] ?? $status) . '</span>';
}

/**
 * Get reading time estimate.
 */
function nmcor_reading_time(int $post_id = 0): string {
	$post_id = $post_id ?: get_the_ID();
	$content = get_post_field('post_content', $post_id);
	$word_count = str_word_count(wp_strip_all_tags($content));
	$minutes = max(1, (int) ceil($word_count / 200));
	return sprintf(__('%d min di lettura', 'nmcor'), $minutes);
}

/**
 * Output breadcrumbs.
 */
function nmcor_breadcrumbs(): void {
	if (is_front_page()) {
		return;
	}

	echo '<nav class="breadcrumbs" aria-label="' . esc_attr__('Breadcrumb', 'nmcor') . '">';
	echo '<a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'nmcor') . '</a>';

	if (is_singular('review')) {
		echo ' <span class="sep">/</span> <a href="' . esc_url(get_post_type_archive_link('review')) . '">' . esc_html__('Recensioni', 'nmcor') . '</a>';
		echo ' <span class="sep">/</span> <span aria-current="page">' . get_the_title() . '</span>';
	} elseif (is_singular('podcast_episode')) {
		echo ' <span class="sep">/</span> <a href="' . esc_url(get_post_type_archive_link('podcast_episode')) . '">' . esc_html__('Podcast', 'nmcor') . '</a>';
		echo ' <span class="sep">/</span> <span aria-current="page">' . get_the_title() . '</span>';
	} elseif (is_singular('nmcor_event')) {
		echo ' <span class="sep">/</span> <a href="' . esc_url(get_post_type_archive_link('nmcor_event')) . '">' . esc_html__('Eventi', 'nmcor') . '</a>';
		echo ' <span class="sep">/</span> <span aria-current="page">' . get_the_title() . '</span>';
	} elseif (is_singular('book')) {
		echo ' <span class="sep">/</span> <a href="' . esc_url(get_post_type_archive_link('book')) . '">' . esc_html__('Libri', 'nmcor') . '</a>';
		echo ' <span class="sep">/</span> <span aria-current="page">' . get_the_title() . '</span>';
	} elseif (is_singular('book_author')) {
		echo ' <span class="sep">/</span> <a href="' . esc_url(get_post_type_archive_link('book_author')) . '">' . esc_html__('Autori', 'nmcor') . '</a>';
		echo ' <span class="sep">/</span> <span aria-current="page">' . get_the_title() . '</span>';
	} elseif (is_singular('post')) {
		echo ' <span class="sep">/</span> <a href="' . esc_url(home_url('/approfondimenti/')) . '">' . esc_html__('Approfondimenti', 'nmcor') . '</a>';
		echo ' <span class="sep">/</span> <span aria-current="page">' . get_the_title() . '</span>';
	} elseif (is_archive()) {
		echo ' <span class="sep">/</span> <span aria-current="page">' . get_the_archive_title() . '</span>';
	} elseif (is_search()) {
		echo ' <span class="sep">/</span> <span aria-current="page">' . esc_html__('Risultati ricerca', 'nmcor') . '</span>';
	} elseif (is_page()) {
		echo ' <span class="sep">/</span> <span aria-current="page">' . get_the_title() . '</span>';
	}

	echo '</nav>';
}

/**
 * Get taxonomy terms as linked list.
 */
function nmcor_term_links(string $taxonomy, int $post_id = 0, string $separator = ', '): string {
	$post_id = $post_id ?: get_the_ID();
	$terms = get_the_terms($post_id, $taxonomy);

	if (!$terms || is_wp_error($terms)) {
		return '';
	}

	$links = array_map(function (WP_Term $term): string {
		return '<a href="' . esc_url(get_term_link($term)) . '" class="term-link term-link--' . esc_attr($term->taxonomy) . '">' . esc_html($term->name) . '</a>';
	}, $terms);

	return implode($separator, $links);
}

/**
 * Output podcast listen-on links.
 */
function nmcor_listen_on_links(int $episode_id = 0): void {
	$episode_id = $episode_id ?: get_the_ID();

	$platforms = [
		'spotify' => [
			'url'   => get_post_meta($episode_id, 'nmcor_episode_spotify_url', true),
			'label' => 'Spotify',
			'icon'  => 'spotify',
		],
		'youtube' => [
			'url'   => get_post_meta($episode_id, 'nmcor_episode_youtube_url', true),
			'label' => 'YouTube',
			'icon'  => 'youtube',
		],
		'apple' => [
			'url'   => get_post_meta($episode_id, 'nmcor_episode_apple_url', true),
			'label' => 'Apple Podcasts',
			'icon'  => 'apple',
		],
	];

	echo '<div class="listen-on">';
	foreach ($platforms as $key => $platform) {
		if (!empty($platform['url'])) {
			echo '<a href="' . esc_url($platform['url']) . '" class="listen-on__link listen-on__link--' . esc_attr($key) . '" target="_blank" rel="noopener noreferrer">';
			echo '<span class="listen-on__icon" aria-hidden="true"></span>';
			echo '<span class="listen-on__label">' . esc_html($platform['label']) . '</span>';
			echo '</a>';
		}
	}
	echo '</div>';
}
