<?php
/**
 * AJAX handlers for content filtering.
 *
 * @package NMCOR
 */

defined('ABSPATH') || exit;

add_action('wp_ajax_nmcor_filter_posts', 'nmcor_ajax_filter_posts');
add_action('wp_ajax_nopriv_nmcor_filter_posts', 'nmcor_ajax_filter_posts');

/**
 * Filter posts via AJAX.
 * Supports filtering by taxonomy, post type, and search.
 */
function nmcor_ajax_filter_posts(): void {
	check_ajax_referer('nmcor_nonce', 'nonce');

	$post_type = sanitize_text_field($_POST['post_type'] ?? 'post');
	$taxonomy  = sanitize_text_field($_POST['taxonomy'] ?? '');
	$term_slug = sanitize_text_field($_POST['term'] ?? '');
	$paged     = absint($_POST['paged'] ?? 1);
	$per_page  = absint($_POST['per_page'] ?? 12);
	$search    = sanitize_text_field($_POST['search'] ?? '');

	$args = [
		'post_type'      => $post_type,
		'posts_per_page' => $per_page,
		'paged'          => $paged,
		'post_status'    => 'publish',
	];

	// Taxonomy filter
	if ($taxonomy && $term_slug) {
		$args['tax_query'] = [
			[
				'taxonomy' => $taxonomy,
				'field'    => 'slug',
				'terms'    => $term_slug,
			],
		];
	}

	// Search
	if ($search) {
		$args['s'] = $search;
	}

	// Special ordering for events
	if ($post_type === 'nmcor_event') {
		$args['meta_key'] = 'nmcor_event_date_start';
		$args['orderby']  = 'meta_value';
		$args['order']    = 'DESC';
	}

	$query = new WP_Query($args);

	ob_start();

	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();

			$template_map = [
				'review'          => 'cards/card-review',
				'podcast_episode' => 'cards/card-podcast',
				'nmcor_event'     => 'cards/card-event',
				'book'            => 'cards/card-book',
				'book_author'     => 'cards/card-author',
				'post'            => 'cards/card-article',
			];

			$template = $template_map[$post_type] ?? 'cards/card-article';
			get_template_part('template-parts/' . $template);
		}
	} else {
		echo '<div class="no-results"><p>' . esc_html__('Nessun contenuto trovato.', 'nmcor') . '</p></div>';
	}

	$html = ob_get_clean();

	wp_send_json_success([
		'html'       => $html,
		'found'      => $query->found_posts,
		'max_pages'  => $query->max_num_pages,
		'current'    => $paged,
	]);
}

/**
 * Load more posts (infinite scroll / load more button).
 */
add_action('wp_ajax_nmcor_load_more', 'nmcor_ajax_load_more');
add_action('wp_ajax_nopriv_nmcor_load_more', 'nmcor_ajax_load_more');

function nmcor_ajax_load_more(): void {
	check_ajax_referer('nmcor_nonce', 'nonce');

	$post_type = sanitize_text_field($_POST['post_type'] ?? 'post');
	$paged     = absint($_POST['paged'] ?? 2);
	$per_page  = absint($_POST['per_page'] ?? 12);

	$args = [
		'post_type'      => $post_type,
		'posts_per_page' => $per_page,
		'paged'          => $paged,
		'post_status'    => 'publish',
	];

	$query = new WP_Query($args);

	ob_start();

	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();

			$template_map = [
				'review'          => 'cards/card-review',
				'podcast_episode' => 'cards/card-podcast',
				'nmcor_event'     => 'cards/card-event',
				'book'            => 'cards/card-book',
				'post'            => 'cards/card-article',
			];

			$template = $template_map[$post_type] ?? 'cards/card-article';
			get_template_part('template-parts/' . $template);
		}
	}

	$html = ob_get_clean();

	wp_send_json_success([
		'html'      => $html,
		'has_more'  => $paged < $query->max_num_pages,
	]);
}
