<?php
/**
 * Enqueue scripts and styles.
 *
 * @package NMCOR
 */

defined('ABSPATH') || exit;

add_action('wp_enqueue_scripts', 'nmcor_enqueue_assets');

function nmcor_enqueue_assets(): void {
	// Main stylesheet
	wp_enqueue_style(
		'nmcor-style',
		NMCOR_URI . '/assets/css/main.css',
		[],
		NMCOR_VERSION
	);

	// Navigation JS
	wp_enqueue_script(
		'nmcor-navigation',
		NMCOR_URI . '/assets/js/navigation.js',
		[],
		NMCOR_VERSION,
		true
	);

	// Main JS
	wp_enqueue_script(
		'nmcor-main',
		NMCOR_URI . '/assets/js/main.js',
		[],
		NMCOR_VERSION,
		true
	);

	// Localize script for AJAX
	wp_localize_script('nmcor-main', 'nmcorAjax', [
		'ajaxUrl' => admin_url('admin-ajax.php'),
		'nonce'   => wp_create_nonce('nmcor_nonce'),
	]);

	// Archive filters (only on archive pages)
	if (is_archive() || is_home() || is_page_template()) {
		wp_enqueue_script(
			'nmcor-filters',
			NMCOR_URI . '/assets/js/filters.js',
			['nmcor-main'],
			NMCOR_VERSION,
			true
		);
	}
}

/**
 * Editor styles.
 */
add_action('admin_init', function (): void {
	add_editor_style('assets/css/editor.css');
});

/**
 * Preload key fonts for performance.
 */
add_action('wp_head', function (): void {
	?>
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<?php
}, 1);
