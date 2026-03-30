<?php
/**
 * Custom Taxonomies registration.
 *
 * Taxonomies:
 * - genre           → Genere letterario (books, reviews)
 * - literary_theme   → Tema letterario (cross-content)
 * - article_type     → Tipo approfondimento (posts only)
 * - podcast_format   → Format podcast
 * - event_type       → Tipo evento
 * - publisher        → Editore
 *
 * @package NMCOR
 */

defined('ABSPATH') || exit;

add_action('init', 'nmcor_register_taxonomies');

function nmcor_register_taxonomies(): void {

	// ─── Genere Letterario ───────────────────────────────────────
	register_taxonomy('genre', ['book', 'review'], [
		'labels' => [
			'name'          => __('Generi', 'nmcor'),
			'singular_name' => __('Genere', 'nmcor'),
			'search_items'  => __('Cerca Generi', 'nmcor'),
			'all_items'     => __('Tutti i Generi', 'nmcor'),
			'edit_item'     => __('Modifica Genere', 'nmcor'),
			'add_new_item'  => __('Aggiungi Genere', 'nmcor'),
			'menu_name'     => __('Generi', 'nmcor'),
		],
		'hierarchical'      => true,
		'public'            => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => ['slug' => 'genere', 'with_front' => false],
	]);

	// ─── Tema Letterario ─────────────────────────────────────────
	register_taxonomy('literary_theme', ['post', 'review', 'book', 'podcast_episode', 'book_author'], [
		'labels' => [
			'name'          => __('Temi Letterari', 'nmcor'),
			'singular_name' => __('Tema Letterario', 'nmcor'),
			'search_items'  => __('Cerca Temi', 'nmcor'),
			'all_items'     => __('Tutti i Temi', 'nmcor'),
			'edit_item'     => __('Modifica Tema', 'nmcor'),
			'add_new_item'  => __('Aggiungi Tema', 'nmcor'),
			'menu_name'     => __('Temi Letterari', 'nmcor'),
		],
		'hierarchical'      => true,
		'public'            => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => ['slug' => 'tema', 'with_front' => false],
	]);

	// ─── Tipo Approfondimento ────────────────────────────────────
	// Analisi di opere, Confronti, Temi e concetti, Autori, Percorsi di lettura
	register_taxonomy('article_type', ['post'], [
		'labels' => [
			'name'          => __('Tipo Approfondimento', 'nmcor'),
			'singular_name' => __('Tipo', 'nmcor'),
			'search_items'  => __('Cerca Tipi', 'nmcor'),
			'all_items'     => __('Tutti i Tipi', 'nmcor'),
			'edit_item'     => __('Modifica Tipo', 'nmcor'),
			'add_new_item'  => __('Aggiungi Tipo', 'nmcor'),
			'menu_name'     => __('Tipo Approfondimento', 'nmcor'),
		],
		'hierarchical'      => true,
		'public'            => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => ['slug' => 'approfondimenti/tipo', 'with_front' => false],
	]);

	// ─── Format Podcast ──────────────────────────────────────────
	register_taxonomy('podcast_format', ['podcast_episode'], [
		'labels' => [
			'name'          => __('Format Podcast', 'nmcor'),
			'singular_name' => __('Format', 'nmcor'),
			'search_items'  => __('Cerca Format', 'nmcor'),
			'all_items'     => __('Tutti i Format', 'nmcor'),
			'edit_item'     => __('Modifica Format', 'nmcor'),
			'add_new_item'  => __('Aggiungi Format', 'nmcor'),
			'menu_name'     => __('Format', 'nmcor'),
		],
		'hierarchical'      => true,
		'public'            => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => ['slug' => 'podcast/format', 'with_front' => false],
	]);

	// ─── Tipo Evento ─────────────────────────────────────────────
	register_taxonomy('event_type', ['nmcor_event'], [
		'labels' => [
			'name'          => __('Tipo Evento', 'nmcor'),
			'singular_name' => __('Tipo Evento', 'nmcor'),
			'search_items'  => __('Cerca Tipi Evento', 'nmcor'),
			'all_items'     => __('Tutti i Tipi', 'nmcor'),
			'edit_item'     => __('Modifica Tipo', 'nmcor'),
			'add_new_item'  => __('Aggiungi Tipo', 'nmcor'),
			'menu_name'     => __('Tipo Evento', 'nmcor'),
		],
		'hierarchical'      => true,
		'public'            => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => ['slug' => 'eventi/tipo', 'with_front' => false],
	]);

	// ─── Editore ─────────────────────────────────────────────────
	register_taxonomy('publisher', ['book'], [
		'labels' => [
			'name'          => __('Editori', 'nmcor'),
			'singular_name' => __('Editore', 'nmcor'),
			'search_items'  => __('Cerca Editori', 'nmcor'),
			'all_items'     => __('Tutti gli Editori', 'nmcor'),
			'edit_item'     => __('Modifica Editore', 'nmcor'),
			'add_new_item'  => __('Aggiungi Editore', 'nmcor'),
			'menu_name'     => __('Editori', 'nmcor'),
		],
		'hierarchical'      => false,
		'public'            => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => ['slug' => 'editore', 'with_front' => false],
	]);

	// Register literary_theme for standard posts too (Approfondimenti)
	register_taxonomy_for_object_type('literary_theme', 'post');
}

/**
 * Pre-populate default terms on theme activation.
 */
add_action('after_switch_theme', 'nmcor_insert_default_terms');

function nmcor_insert_default_terms(): void {
	// Article types
	$article_types = [
		'analisi'    => 'Analisi di opere',
		'confronti'  => 'Confronti',
		'temi'       => 'Temi e concetti',
		'autori'     => 'Autori',
		'percorsi'   => 'Percorsi di lettura',
	];
	foreach ($article_types as $slug => $name) {
		if (!term_exists($slug, 'article_type')) {
			wp_insert_term($name, 'article_type', ['slug' => $slug]);
		}
	}

	// Event types
	$event_types = [
		'fiera'         => 'Fiera',
		'festival'      => 'Festival',
		'presentazione' => 'Presentazione',
		'incontro'      => 'Incontro',
	];
	foreach ($event_types as $slug => $name) {
		if (!term_exists($slug, 'event_type')) {
			wp_insert_term($name, 'event_type', ['slug' => $slug]);
		}
	}
}
