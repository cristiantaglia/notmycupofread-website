<?php
/**
 * Custom Fields registration.
 *
 * Uses ACF if available, otherwise registers meta fields via WordPress REST API.
 * ACF is recommended for the best editorial experience.
 *
 * @package NMCOR
 */

defined('ABSPATH') || exit;

/**
 * Register meta fields for REST API access (works with or without ACF).
 */
add_action('init', 'nmcor_register_meta_fields');

function nmcor_register_meta_fields(): void {

	// ─── Review meta ─────────────────────────────────────────────
	$review_meta = [
		'nmcor_review_subtitle'       => 'string',
		'nmcor_review_rating'         => 'number',   // 1-5 or 1-10
		'nmcor_review_verdict'        => 'string',   // Giudizio sintetico
	];
	foreach ($review_meta as $key => $type) {
		register_post_meta('review', $key, [
			'show_in_rest'  => true,
			'single'        => true,
			'type'          => $type,
			'auth_callback' => fn() => current_user_can('edit_posts'),
		]);
	}

	// ─── Podcast Episode meta ────────────────────────────────────
	$episode_meta = [
		'nmcor_episode_number'        => 'integer',
		'nmcor_episode_season'        => 'integer',
		'nmcor_episode_duration'      => 'string',  // "1h 23m"
		'nmcor_episode_spotify_url'   => 'string',
		'nmcor_episode_youtube_url'   => 'string',
		'nmcor_episode_apple_url'     => 'string',
		'nmcor_episode_audio_url'     => 'string',  // Direct audio embed
		'nmcor_episode_embed_url'     => 'string',  // Spotify/other embed iframe URL
		'nmcor_episode_is_featured'   => 'boolean',
	];
	foreach ($episode_meta as $key => $type) {
		register_post_meta('podcast_episode', $key, [
			'show_in_rest'  => true,
			'single'        => true,
			'type'          => $type,
			'auth_callback' => fn() => current_user_can('edit_posts'),
		]);
	}

	// ─── Event meta ──────────────────────────────────────────────
	$event_meta = [
		'nmcor_event_date_start'  => 'string',  // Y-m-d
		'nmcor_event_date_end'    => 'string',
		'nmcor_event_time'        => 'string',
		'nmcor_event_location'    => 'string',
		'nmcor_event_address'     => 'string',
		'nmcor_event_city'        => 'string',
		'nmcor_event_url'         => 'string',
		'nmcor_event_status'      => 'string',  // upcoming, past, cancelled
	];
	foreach ($event_meta as $key => $type) {
		register_post_meta('nmcor_event', $key, [
			'show_in_rest'  => true,
			'single'        => true,
			'type'          => $type,
			'auth_callback' => fn() => current_user_can('edit_posts'),
		]);
	}

	// ─── Book meta ───────────────────────────────────────────────
	$book_meta = [
		'nmcor_book_subtitle'         => 'string',
		'nmcor_book_year'             => 'integer',
		'nmcor_book_pages'            => 'integer',
		'nmcor_book_isbn'             => 'string',
		'nmcor_book_original_title'   => 'string',
		'nmcor_book_original_language'=> 'string',
		'nmcor_book_buy_url'          => 'string',
	];
	foreach ($book_meta as $key => $type) {
		register_post_meta('book', $key, [
			'show_in_rest'  => true,
			'single'        => true,
			'type'          => $type,
			'auth_callback' => fn() => current_user_can('edit_posts'),
		]);
	}

	// ─── Book Author meta ────────────────────────────────────────
	$author_meta = [
		'nmcor_author_birth_year'     => 'integer',
		'nmcor_author_nationality'    => 'string',
		'nmcor_author_website'        => 'string',
		'nmcor_author_wikipedia_url'  => 'string',
	];
	foreach ($author_meta as $key => $type) {
		register_post_meta('book_author', $key, [
			'show_in_rest'  => true,
			'single'        => true,
			'type'          => $type,
			'auth_callback' => fn() => current_user_can('edit_posts'),
		]);
	}
}

/**
 * Register ACF field groups if ACF is active.
 *
 * This provides a rich editorial UI for managing content relationships
 * and custom fields.
 */
add_action('acf/init', 'nmcor_register_acf_fields');

function nmcor_register_acf_fields(): void {
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	// ─── Review Fields ───────────────────────────────────────────
	acf_add_local_field_group([
		'key'      => 'group_review_details',
		'title'    => 'Dettagli Recensione',
		'fields'   => [
			[
				'key'   => 'field_review_subtitle',
				'label' => 'Sottotitolo',
				'name'  => 'nmcor_review_subtitle',
				'type'  => 'text',
			],
			[
				'key'   => 'field_review_book',
				'label' => 'Libro recensito',
				'name'  => 'nmcor_review_book',
				'type'  => 'relationship',
				'post_type' => ['book'],
				'max'   => 1,
				'return_format' => 'id',
			],
			[
				'key'   => 'field_review_rating',
				'label' => 'Valutazione (1-5)',
				'name'  => 'nmcor_review_rating',
				'type'  => 'number',
				'min'   => 1,
				'max'   => 5,
				'step'  => 0.5,
			],
			[
				'key'   => 'field_review_verdict',
				'label' => 'Giudizio sintetico',
				'name'  => 'nmcor_review_verdict',
				'type'  => 'textarea',
				'rows'  => 3,
			],
			[
				'key'   => 'field_review_episode',
				'label' => 'Episodio podcast collegato',
				'name'  => 'nmcor_review_episode',
				'type'  => 'relationship',
				'post_type' => ['podcast_episode'],
				'max'   => 1,
				'return_format' => 'id',
			],
		],
		'location' => [
			[['param' => 'post_type', 'operator' => '==', 'value' => 'review']],
		],
		'position' => 'normal',
		'style'    => 'default',
	]);

	// ─── Podcast Episode Fields ──────────────────────────────────
	acf_add_local_field_group([
		'key'      => 'group_episode_details',
		'title'    => 'Dettagli Episodio',
		'fields'   => [
			[
				'key'   => 'field_episode_number',
				'label' => 'Numero episodio',
				'name'  => 'nmcor_episode_number',
				'type'  => 'number',
			],
			[
				'key'   => 'field_episode_season',
				'label' => 'Stagione',
				'name'  => 'nmcor_episode_season',
				'type'  => 'number',
			],
			[
				'key'   => 'field_episode_duration',
				'label' => 'Durata',
				'name'  => 'nmcor_episode_duration',
				'type'  => 'text',
				'placeholder' => '1h 23m',
			],
			[
				'key'   => 'field_episode_spotify_url',
				'label' => 'URL Spotify',
				'name'  => 'nmcor_episode_spotify_url',
				'type'  => 'url',
			],
			[
				'key'   => 'field_episode_youtube_url',
				'label' => 'URL YouTube',
				'name'  => 'nmcor_episode_youtube_url',
				'type'  => 'url',
			],
			[
				'key'   => 'field_episode_apple_url',
				'label' => 'URL Apple Podcasts',
				'name'  => 'nmcor_episode_apple_url',
				'type'  => 'url',
			],
			[
				'key'   => 'field_episode_embed_url',
				'label' => 'URL Embed (Spotify embed)',
				'name'  => 'nmcor_episode_embed_url',
				'type'  => 'url',
			],
			[
				'key'   => 'field_episode_audio_url',
				'label' => 'URL Audio diretto',
				'name'  => 'nmcor_episode_audio_url',
				'type'  => 'url',
			],
			[
				'key'   => 'field_episode_books',
				'label' => 'Libri trattati',
				'name'  => 'nmcor_episode_books',
				'type'  => 'relationship',
				'post_type' => ['book'],
				'return_format' => 'id',
			],
			[
				'key'   => 'field_episode_authors',
				'label' => 'Autori trattati',
				'name'  => 'nmcor_episode_authors',
				'type'  => 'relationship',
				'post_type' => ['book_author'],
				'return_format' => 'id',
			],
			[
				'key'   => 'field_episode_is_featured',
				'label' => 'In evidenza',
				'name'  => 'nmcor_episode_is_featured',
				'type'  => 'true_false',
				'ui'    => 1,
			],
		],
		'location' => [
			[['param' => 'post_type', 'operator' => '==', 'value' => 'podcast_episode']],
		],
	]);

	// ─── Event Fields ────────────────────────────────────────────
	acf_add_local_field_group([
		'key'      => 'group_event_details',
		'title'    => 'Dettagli Evento',
		'fields'   => [
			[
				'key'   => 'field_event_date_start',
				'label' => 'Data inizio',
				'name'  => 'nmcor_event_date_start',
				'type'  => 'date_picker',
				'return_format' => 'Y-m-d',
				'display_format' => 'd/m/Y',
			],
			[
				'key'   => 'field_event_date_end',
				'label' => 'Data fine',
				'name'  => 'nmcor_event_date_end',
				'type'  => 'date_picker',
				'return_format' => 'Y-m-d',
				'display_format' => 'd/m/Y',
			],
			[
				'key'   => 'field_event_time',
				'label' => 'Orario',
				'name'  => 'nmcor_event_time',
				'type'  => 'text',
				'placeholder' => '18:30',
			],
			[
				'key'   => 'field_event_location',
				'label' => 'Luogo',
				'name'  => 'nmcor_event_location',
				'type'  => 'text',
			],
			[
				'key'   => 'field_event_address',
				'label' => 'Indirizzo',
				'name'  => 'nmcor_event_address',
				'type'  => 'text',
			],
			[
				'key'   => 'field_event_city',
				'label' => 'Città',
				'name'  => 'nmcor_event_city',
				'type'  => 'text',
			],
			[
				'key'   => 'field_event_url',
				'label' => 'URL evento esterno',
				'name'  => 'nmcor_event_url',
				'type'  => 'url',
			],
			[
				'key'   => 'field_event_status',
				'label' => 'Stato',
				'name'  => 'nmcor_event_status',
				'type'  => 'select',
				'choices' => [
					'upcoming'  => 'In programma',
					'past'      => 'Passato',
					'cancelled' => 'Annullato',
				],
				'default_value' => 'upcoming',
			],
			[
				'key'   => 'field_event_articles',
				'label' => 'Articoli collegati',
				'name'  => 'nmcor_event_articles',
				'type'  => 'relationship',
				'post_type' => ['post', 'review'],
				'return_format' => 'id',
			],
			[
				'key'   => 'field_event_episodes',
				'label' => 'Episodi podcast collegati',
				'name'  => 'nmcor_event_episodes',
				'type'  => 'relationship',
				'post_type' => ['podcast_episode'],
				'return_format' => 'id',
			],
		],
		'location' => [
			[['param' => 'post_type', 'operator' => '==', 'value' => 'nmcor_event']],
		],
	]);

	// ─── Book Fields ─────────────────────────────────────────────
	acf_add_local_field_group([
		'key'      => 'group_book_details',
		'title'    => 'Dettagli Libro',
		'fields'   => [
			[
				'key'   => 'field_book_subtitle',
				'label' => 'Sottotitolo',
				'name'  => 'nmcor_book_subtitle',
				'type'  => 'text',
			],
			[
				'key'   => 'field_book_author',
				'label' => 'Autore',
				'name'  => 'nmcor_book_author',
				'type'  => 'relationship',
				'post_type' => ['book_author'],
				'return_format' => 'id',
			],
			[
				'key'   => 'field_book_year',
				'label' => 'Anno di pubblicazione',
				'name'  => 'nmcor_book_year',
				'type'  => 'number',
			],
			[
				'key'   => 'field_book_pages',
				'label' => 'Numero pagine',
				'name'  => 'nmcor_book_pages',
				'type'  => 'number',
			],
			[
				'key'   => 'field_book_isbn',
				'label' => 'ISBN',
				'name'  => 'nmcor_book_isbn',
				'type'  => 'text',
			],
			[
				'key'   => 'field_book_original_title',
				'label' => 'Titolo originale',
				'name'  => 'nmcor_book_original_title',
				'type'  => 'text',
			],
			[
				'key'   => 'field_book_original_language',
				'label' => 'Lingua originale',
				'name'  => 'nmcor_book_original_language',
				'type'  => 'text',
			],
			[
				'key'   => 'field_book_buy_url',
				'label' => 'Link acquisto',
				'name'  => 'nmcor_book_buy_url',
				'type'  => 'url',
			],
		],
		'location' => [
			[['param' => 'post_type', 'operator' => '==', 'value' => 'book']],
		],
	]);

	// ─── Book Author Fields ──────────────────────────────────────
	acf_add_local_field_group([
		'key'      => 'group_author_details',
		'title'    => 'Dettagli Autore',
		'fields'   => [
			[
				'key'   => 'field_author_birth_year',
				'label' => 'Anno di nascita',
				'name'  => 'nmcor_author_birth_year',
				'type'  => 'number',
			],
			[
				'key'   => 'field_author_nationality',
				'label' => 'Nazionalità',
				'name'  => 'nmcor_author_nationality',
				'type'  => 'text',
			],
			[
				'key'   => 'field_author_website',
				'label' => 'Sito web',
				'name'  => 'nmcor_author_website',
				'type'  => 'url',
			],
			[
				'key'   => 'field_author_wikipedia_url',
				'label' => 'Wikipedia',
				'name'  => 'nmcor_author_wikipedia_url',
				'type'  => 'url',
			],
			[
				'key'   => 'field_author_books',
				'label' => 'Libri',
				'name'  => 'nmcor_author_books',
				'type'  => 'relationship',
				'post_type' => ['book'],
				'return_format' => 'id',
			],
		],
		'location' => [
			[['param' => 'post_type', 'operator' => '==', 'value' => 'book_author']],
		],
	]);

	// ─── Approfondimento (post) relationship fields ──────────────
	acf_add_local_field_group([
		'key'      => 'group_post_relationships',
		'title'    => 'Contenuti Collegati',
		'fields'   => [
			[
				'key'   => 'field_post_books',
				'label' => 'Libri collegati',
				'name'  => 'nmcor_post_books',
				'type'  => 'relationship',
				'post_type' => ['book'],
				'return_format' => 'id',
			],
			[
				'key'   => 'field_post_episode',
				'label' => 'Episodio podcast collegato',
				'name'  => 'nmcor_post_episode',
				'type'  => 'relationship',
				'post_type' => ['podcast_episode'],
				'max'   => 1,
				'return_format' => 'id',
			],
		],
		'location' => [
			[['param' => 'post_type', 'operator' => '==', 'value' => 'post']],
		],
		'position' => 'side',
	]);
}

/**
 * Fallback: Add meta boxes when ACF is not available.
 * Provides basic meta box UI for essential fields.
 */
add_action('add_meta_boxes', 'nmcor_fallback_meta_boxes');

function nmcor_fallback_meta_boxes(): void {
	if (function_exists('acf_add_local_field_group')) {
		return; // ACF handles the UI
	}

	// Review meta box
	add_meta_box(
		'nmcor_review_meta',
		__('Dettagli Recensione', 'nmcor'),
		'nmcor_render_review_meta_box',
		'review',
		'normal',
		'high'
	);

	// Episode meta box
	add_meta_box(
		'nmcor_episode_meta',
		__('Dettagli Episodio', 'nmcor'),
		'nmcor_render_episode_meta_box',
		'podcast_episode',
		'normal',
		'high'
	);

	// Event meta box
	add_meta_box(
		'nmcor_event_meta',
		__('Dettagli Evento', 'nmcor'),
		'nmcor_render_event_meta_box',
		'nmcor_event',
		'normal',
		'high'
	);

	// Book meta box
	add_meta_box(
		'nmcor_book_meta',
		__('Dettagli Libro', 'nmcor'),
		'nmcor_render_book_meta_box',
		'book',
		'normal',
		'high'
	);
}

function nmcor_render_review_meta_box(\WP_Post $post): void {
	wp_nonce_field('nmcor_meta', 'nmcor_meta_nonce');
	$fields = [
		'nmcor_review_subtitle' => ['label' => 'Sottotitolo', 'type' => 'text'],
		'nmcor_review_rating'   => ['label' => 'Valutazione (1-5)', 'type' => 'number'],
		'nmcor_review_verdict'  => ['label' => 'Giudizio sintetico', 'type' => 'textarea'],
	];
	nmcor_render_meta_fields($post->ID, $fields);
}

function nmcor_render_episode_meta_box(\WP_Post $post): void {
	wp_nonce_field('nmcor_meta', 'nmcor_meta_nonce');
	$fields = [
		'nmcor_episode_number'      => ['label' => 'Numero episodio', 'type' => 'number'],
		'nmcor_episode_season'      => ['label' => 'Stagione', 'type' => 'number'],
		'nmcor_episode_duration'    => ['label' => 'Durata', 'type' => 'text'],
		'nmcor_episode_spotify_url' => ['label' => 'URL Spotify', 'type' => 'url'],
		'nmcor_episode_youtube_url' => ['label' => 'URL YouTube', 'type' => 'url'],
		'nmcor_episode_apple_url'   => ['label' => 'URL Apple Podcasts', 'type' => 'url'],
		'nmcor_episode_embed_url'   => ['label' => 'URL Embed', 'type' => 'url'],
		'nmcor_episode_audio_url'   => ['label' => 'URL Audio', 'type' => 'url'],
	];
	nmcor_render_meta_fields($post->ID, $fields);
}

function nmcor_render_event_meta_box(\WP_Post $post): void {
	wp_nonce_field('nmcor_meta', 'nmcor_meta_nonce');
	$fields = [
		'nmcor_event_date_start' => ['label' => 'Data inizio', 'type' => 'date'],
		'nmcor_event_date_end'   => ['label' => 'Data fine', 'type' => 'date'],
		'nmcor_event_time'       => ['label' => 'Orario', 'type' => 'text'],
		'nmcor_event_location'   => ['label' => 'Luogo', 'type' => 'text'],
		'nmcor_event_city'       => ['label' => 'Città', 'type' => 'text'],
		'nmcor_event_url'        => ['label' => 'URL evento', 'type' => 'url'],
		'nmcor_event_status'     => ['label' => 'Stato', 'type' => 'select', 'options' => [
			'upcoming' => 'In programma', 'past' => 'Passato', 'cancelled' => 'Annullato',
		]],
	];
	nmcor_render_meta_fields($post->ID, $fields);
}

function nmcor_render_book_meta_box(\WP_Post $post): void {
	wp_nonce_field('nmcor_meta', 'nmcor_meta_nonce');
	$fields = [
		'nmcor_book_subtitle'          => ['label' => 'Sottotitolo', 'type' => 'text'],
		'nmcor_book_year'              => ['label' => 'Anno', 'type' => 'number'],
		'nmcor_book_pages'             => ['label' => 'Pagine', 'type' => 'number'],
		'nmcor_book_isbn'              => ['label' => 'ISBN', 'type' => 'text'],
		'nmcor_book_original_title'    => ['label' => 'Titolo originale', 'type' => 'text'],
		'nmcor_book_original_language' => ['label' => 'Lingua originale', 'type' => 'text'],
		'nmcor_book_buy_url'           => ['label' => 'Link acquisto', 'type' => 'url'],
	];
	nmcor_render_meta_fields($post->ID, $fields);
}

/**
 * Render meta fields in a simple table layout.
 */
function nmcor_render_meta_fields(int $post_id, array $fields): void {
	echo '<table class="form-table nmcor-meta-table">';
	foreach ($fields as $key => $config) {
		$value = get_post_meta($post_id, $key, true);
		echo '<tr><th><label for="' . esc_attr($key) . '">' . esc_html($config['label']) . '</label></th><td>';

		switch ($config['type']) {
			case 'textarea':
				echo '<textarea id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" rows="3" class="large-text">' . esc_textarea($value) . '</textarea>';
				break;
			case 'select':
				echo '<select id="' . esc_attr($key) . '" name="' . esc_attr($key) . '">';
				foreach ($config['options'] as $opt_val => $opt_label) {
					echo '<option value="' . esc_attr($opt_val) . '"' . selected($value, $opt_val, false) . '>' . esc_html($opt_label) . '</option>';
				}
				echo '</select>';
				break;
			default:
				echo '<input type="' . esc_attr($config['type']) . '" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" class="regular-text">';
		}

		echo '</td></tr>';
	}
	echo '</table>';
}

/**
 * Save fallback meta box data.
 */
add_action('save_post', 'nmcor_save_meta_fields');

function nmcor_save_meta_fields(int $post_id): void {
	if (function_exists('acf_add_local_field_group')) {
		return; // ACF handles saving
	}

	if (!isset($_POST['nmcor_meta_nonce']) || !wp_verify_nonce($_POST['nmcor_meta_nonce'], 'nmcor_meta')) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	// Save all nmcor_ prefixed fields
	foreach ($_POST as $key => $value) {
		if (str_starts_with($key, 'nmcor_')) {
			update_post_meta($post_id, $key, sanitize_text_field($value));
		}
	}
}
