<?php
/**
 * Plugin Name: WP Odds Tracker
 * Description: Live football odds crawler + scoreboard via shortcode.
 * Version: 1.0.0
 * Slug: wp-odds-tracker
 * Author: Rahwee Khum
 */

if (!defined('ABSPATH')) exit;

define('WPOT_DIR', plugin_dir_path(__FILE__));
define('WPOT_URL', plugin_dir_url(__FILE__));
define('WPOT_NAMESPACE', 'wpot_');

// autoload includes
require_once WPOT_DIR . 'includes/helpers.php';
require_once WPOT_DIR . 'includes/class-wpot-loader.php';

add_action('plugins_loaded', 'wpot_boot');

function wpot_boot() {
    $loader = new WPOT_Loader();
    $loader->init();
}



