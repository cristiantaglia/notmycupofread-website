<?php
/**
 * Not My Cup of Read - Functions and definitions
 *
 * @package NMCOR
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

define('NMCOR_VERSION', '1.0.0');
define('NMCOR_DIR', get_template_directory());
define('NMCOR_URI', get_template_directory_uri());

// Core includes
require_once NMCOR_DIR . '/inc/setup.php';
require_once NMCOR_DIR . '/inc/enqueue.php';
require_once NMCOR_DIR . '/inc/custom-post-types.php';
require_once NMCOR_DIR . '/inc/custom-taxonomies.php';
require_once NMCOR_DIR . '/inc/custom-fields.php';
require_once NMCOR_DIR . '/inc/template-tags.php';
require_once NMCOR_DIR . '/inc/helpers.php';
require_once NMCOR_DIR . '/inc/gutenberg.php';
require_once NMCOR_DIR . '/inc/ajax-handlers.php';
