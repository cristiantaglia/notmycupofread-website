<?php
/**
 * Gutenberg / Block Editor customization.
 *
 * @package NMCOR
 */

defined('ABSPATH') || exit;

/**
 * Register block patterns and categories.
 */
add_action('init', 'nmcor_register_block_patterns');

function nmcor_register_block_patterns(): void {
	// Register pattern categories
	register_block_pattern_category('nmcor-heroes', [
		'label' => __('NMCOR - Heroes', 'nmcor'),
	]);

	register_block_pattern_category('nmcor-sections', [
		'label' => __('NMCOR - Sezioni', 'nmcor'),
	]);

	register_block_pattern_category('nmcor-cta', [
		'label' => __('NMCOR - Call to Action', 'nmcor'),
	]);
}

/**
 * Register block styles.
 */
add_action('init', 'nmcor_register_block_styles');

function nmcor_register_block_styles(): void {
	// Group: editorial card style
	register_block_style('core/group', [
		'name'  => 'nmcor-card',
		'label' => __('Card Editoriale', 'nmcor'),
	]);

	register_block_style('core/group', [
		'name'  => 'nmcor-section',
		'label' => __('Sezione con sfondo', 'nmcor'),
	]);

	// Separator: editorial style
	register_block_style('core/separator', [
		'name'  => 'nmcor-ornament',
		'label' => __('Ornamento editoriale', 'nmcor'),
	]);

	// Heading: section title
	register_block_style('core/heading', [
		'name'  => 'nmcor-section-title',
		'label' => __('Titolo di sezione', 'nmcor'),
	]);

	// Button: outlined
	register_block_style('core/button', [
		'name'  => 'nmcor-outlined',
		'label' => __('Outlined', 'nmcor'),
	]);

	register_block_style('core/button', [
		'name'  => 'nmcor-text-link',
		'label' => __('Link testuale', 'nmcor'),
	]);
}

/**
 * Restrict allowed block types for specific post types.
 */
add_filter('allowed_block_types_all', 'nmcor_allowed_block_types', 10, 2);

function nmcor_allowed_block_types(bool|array $allowed, WP_Block_Editor_Context $context): bool|array {
	// Allow all blocks by default
	return true;
}

/**
 * Add editor styles.
 */
add_action('after_setup_theme', function (): void {
	add_editor_style('assets/css/editor.css');
});

/**
 * Enqueue block editor assets.
 */
add_action('enqueue_block_editor_assets', function (): void {
	wp_enqueue_style(
		'nmcor-editor',
		NMCOR_URI . '/assets/css/editor.css',
		[],
		NMCOR_VERSION
	);
});
