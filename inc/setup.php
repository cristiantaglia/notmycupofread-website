<?php
/**
 * Theme setup and configuration.
 *
 * @package NMCOR
 */

defined('ABSPATH') || exit;

add_action('after_setup_theme', 'nmcor_setup');

function nmcor_setup(): void {
	// Translations
	load_theme_textdomain('nmcor', NMCOR_DIR . '/languages');

	// Theme support
	add_theme_support('automatic-feed-links');
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('html5', [
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	]);
	add_theme_support('custom-logo', [
		'height'      => 80,
		'width'       => 300,
		'flex-height' => true,
		'flex-width'  => true,
	]);
	add_theme_support('editor-styles');
	add_theme_support('responsive-embeds');
	add_theme_support('wp-block-styles');

	// Custom image sizes
	add_image_size('nmcor-hero', 1920, 800, true);
	add_image_size('nmcor-card-large', 800, 500, true);
	add_image_size('nmcor-card-medium', 600, 375, true);
	add_image_size('nmcor-card-small', 400, 250, true);
	add_image_size('nmcor-book-cover', 300, 450, true);
	add_image_size('nmcor-avatar', 200, 200, true);

	// Navigation menus
	register_nav_menus([
		'primary'   => __('Menu Principale', 'nmcor'),
		'footer'    => __('Menu Footer', 'nmcor'),
		'social'    => __('Social Links', 'nmcor'),
	]);
}

/**
 * Register widget areas.
 */
add_action('widgets_init', 'nmcor_widgets_init');

function nmcor_widgets_init(): void {
	register_sidebar([
		'name'          => __('Sidebar', 'nmcor'),
		'id'            => 'sidebar-1',
		'description'   => __('Sidebar principale', 'nmcor'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	]);

	register_sidebar([
		'name'          => __('Footer Col 1', 'nmcor'),
		'id'            => 'footer-1',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	]);

	register_sidebar([
		'name'          => __('Footer Col 2', 'nmcor'),
		'id'            => 'footer-2',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	]);

	register_sidebar([
		'name'          => __('Footer Col 3', 'nmcor'),
		'id'            => 'footer-3',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	]);
}

/**
 * Customize excerpt length.
 */
add_filter('excerpt_length', function (): int {
	return 30;
});

add_filter('excerpt_more', function (): string {
	return '&hellip;';
});

/**
 * Add custom body classes.
 */
add_filter('body_class', function (array $classes): array {
	if (is_singular()) {
		$classes[] = 'singular-' . get_post_type();
	}
	return $classes;
});
