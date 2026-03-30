<?php
/**
 * Custom Post Types registration.
 *
 * Post types:
 * - review          → Recensioni
 * - podcast_episode → Episodi Podcast
 * - nmcor_event     → Eventi
 * - book            → Libri
 * - book_author     → Autori letterari
 *
 * Standard 'post' is used for Approfondimenti.
 *
 * @package NMCOR
 */

defined('ABSPATH') || exit;

add_action('init', 'nmcor_register_post_types');

function nmcor_register_post_types(): void {

	// ─── Recensioni ──────────────────────────────────────────────
	register_post_type('review', [
		'labels' => [
			'name'               => __('Recensioni', 'nmcor'),
			'singular_name'      => __('Recensione', 'nmcor'),
			'add_new'            => __('Nuova Recensione', 'nmcor'),
			'add_new_item'       => __('Aggiungi Recensione', 'nmcor'),
			'edit_item'          => __('Modifica Recensione', 'nmcor'),
			'view_item'          => __('Visualizza Recensione', 'nmcor'),
			'search_items'       => __('Cerca Recensioni', 'nmcor'),
			'not_found'          => __('Nessuna recensione trovata', 'nmcor'),
			'not_found_in_trash' => __('Nessuna recensione nel cestino', 'nmcor'),
			'all_items'          => __('Tutte le Recensioni', 'nmcor'),
			'menu_name'          => __('Recensioni', 'nmcor'),
		],
		'public'             => true,
		'has_archive'        => true,
		'rewrite'            => ['slug' => 'recensioni', 'with_front' => false],
		'menu_icon'          => 'dashicons-book-alt',
		'menu_position'      => 5,
		'supports'           => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'],
		'show_in_rest'       => true,
		'taxonomies'         => ['genre', 'literary_theme', 'post_tag'],
		'template'           => [
			['core/paragraph', ['placeholder' => 'Scrivi la tua recensione...']],
		],
	]);

	// ─── Episodi Podcast ─────────────────────────────────────────
	register_post_type('podcast_episode', [
		'labels' => [
			'name'               => __('Episodi Podcast', 'nmcor'),
			'singular_name'      => __('Episodio', 'nmcor'),
			'add_new'            => __('Nuovo Episodio', 'nmcor'),
			'add_new_item'       => __('Aggiungi Episodio', 'nmcor'),
			'edit_item'          => __('Modifica Episodio', 'nmcor'),
			'view_item'          => __('Visualizza Episodio', 'nmcor'),
			'search_items'       => __('Cerca Episodi', 'nmcor'),
			'not_found'          => __('Nessun episodio trovato', 'nmcor'),
			'not_found_in_trash' => __('Nessun episodio nel cestino', 'nmcor'),
			'all_items'          => __('Tutti gli Episodi', 'nmcor'),
			'menu_name'          => __('Podcast', 'nmcor'),
		],
		'public'             => true,
		'has_archive'        => true,
		'rewrite'            => ['slug' => 'podcast/episodi', 'with_front' => false],
		'menu_icon'          => 'dashicons-microphone',
		'menu_position'      => 6,
		'supports'           => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'],
		'show_in_rest'       => true,
		'taxonomies'         => ['podcast_format', 'literary_theme', 'post_tag'],
	]);

	// ─── Eventi ──────────────────────────────────────────────────
	register_post_type('nmcor_event', [
		'labels' => [
			'name'               => __('Eventi', 'nmcor'),
			'singular_name'      => __('Evento', 'nmcor'),
			'add_new'            => __('Nuovo Evento', 'nmcor'),
			'add_new_item'       => __('Aggiungi Evento', 'nmcor'),
			'edit_item'          => __('Modifica Evento', 'nmcor'),
			'view_item'          => __('Visualizza Evento', 'nmcor'),
			'search_items'       => __('Cerca Eventi', 'nmcor'),
			'not_found'          => __('Nessun evento trovato', 'nmcor'),
			'not_found_in_trash' => __('Nessun evento nel cestino', 'nmcor'),
			'all_items'          => __('Tutti gli Eventi', 'nmcor'),
			'menu_name'          => __('Eventi', 'nmcor'),
		],
		'public'             => true,
		'has_archive'        => true,
		'rewrite'            => ['slug' => 'eventi', 'with_front' => false],
		'menu_icon'          => 'dashicons-calendar-alt',
		'menu_position'      => 7,
		'supports'           => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'],
		'show_in_rest'       => true,
		'taxonomies'         => ['event_type', 'post_tag'],
	]);

	// ─── Libri ───────────────────────────────────────────────────
	register_post_type('book', [
		'labels' => [
			'name'               => __('Libri', 'nmcor'),
			'singular_name'      => __('Libro', 'nmcor'),
			'add_new'            => __('Nuovo Libro', 'nmcor'),
			'add_new_item'       => __('Aggiungi Libro', 'nmcor'),
			'edit_item'          => __('Modifica Libro', 'nmcor'),
			'view_item'          => __('Visualizza Libro', 'nmcor'),
			'search_items'       => __('Cerca Libri', 'nmcor'),
			'not_found'          => __('Nessun libro trovato', 'nmcor'),
			'not_found_in_trash' => __('Nessun libro nel cestino', 'nmcor'),
			'all_items'          => __('Tutti i Libri', 'nmcor'),
			'menu_name'          => __('Libri', 'nmcor'),
		],
		'public'             => true,
		'has_archive'        => true,
		'rewrite'            => ['slug' => 'libri', 'with_front' => false],
		'menu_icon'          => 'dashicons-book',
		'menu_position'      => 8,
		'supports'           => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'],
		'show_in_rest'       => true,
		'taxonomies'         => ['genre', 'literary_theme', 'publisher'],
	]);

	// ─── Autori letterari ────────────────────────────────────────
	register_post_type('book_author', [
		'labels' => [
			'name'               => __('Autori', 'nmcor'),
			'singular_name'      => __('Autore', 'nmcor'),
			'add_new'            => __('Nuovo Autore', 'nmcor'),
			'add_new_item'       => __('Aggiungi Autore', 'nmcor'),
			'edit_item'          => __('Modifica Autore', 'nmcor'),
			'view_item'          => __('Visualizza Autore', 'nmcor'),
			'search_items'       => __('Cerca Autori', 'nmcor'),
			'not_found'          => __('Nessun autore trovato', 'nmcor'),
			'not_found_in_trash' => __('Nessun autore nel cestino', 'nmcor'),
			'all_items'          => __('Tutti gli Autori', 'nmcor'),
			'menu_name'          => __('Autori', 'nmcor'),
		],
		'public'             => true,
		'has_archive'        => true,
		'rewrite'            => ['slug' => 'autori', 'with_front' => false],
		'menu_icon'          => 'dashicons-admin-users',
		'menu_position'      => 9,
		'supports'           => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'],
		'show_in_rest'       => true,
		'taxonomies'         => ['literary_theme'],
	]);

	// Rename standard "Posts" to "Approfondimenti" in admin
	nmcor_rename_posts_labels();
}

/**
 * Rename default "Articoli" to "Approfondimenti" in the admin menu.
 */
function nmcor_rename_posts_labels(): void {
	global $wp_post_types;

	if (!isset($wp_post_types['post'])) {
		return;
	}

	$labels = &$wp_post_types['post']->labels;
	$labels->name               = __('Approfondimenti', 'nmcor');
	$labels->singular_name      = __('Approfondimento', 'nmcor');
	$labels->add_new            = __('Nuovo Approfondimento', 'nmcor');
	$labels->add_new_item       = __('Aggiungi Approfondimento', 'nmcor');
	$labels->edit_item          = __('Modifica Approfondimento', 'nmcor');
	$labels->view_item          = __('Visualizza Approfondimento', 'nmcor');
	$labels->search_items       = __('Cerca Approfondimenti', 'nmcor');
	$labels->not_found          = __('Nessun approfondimento trovato', 'nmcor');
	$labels->not_found_in_trash = __('Nessun approfondimento nel cestino', 'nmcor');
	$labels->all_items          = __('Tutti gli Approfondimenti', 'nmcor');
	$labels->menu_name          = __('Approfondimenti', 'nmcor');

	$wp_post_types['post']->menu_icon = 'dashicons-media-text';
}

/**
 * Customize admin menu order.
 */
add_filter('custom_menu_order', '__return_true');
add_filter('menu_order', function (array $menu_order): array {
	return [
		'index.php',
		'separator1',
		'edit.php',            // Approfondimenti
		'edit.php?post_type=review',
		'edit.php?post_type=podcast_episode',
		'edit.php?post_type=nmcor_event',
		'edit.php?post_type=book',
		'edit.php?post_type=book_author',
		'separator2',
		'upload.php',
		'edit.php?post_type=page',
	];
});

/**
 * Flush rewrite rules on theme activation.
 */
add_action('after_switch_theme', function (): void {
	nmcor_register_post_types();
	flush_rewrite_rules();
});
