<?php
/**
 * Plugin Name: Autotelex Inventory
 * Description: A utility to add Autotelex inventory to Wordpress pages.
 * Plugin URI: https://github.com/KiOui/autotelex-wordpress
 * Version: 0.0.1
 * Author: Lars van Rhijn
 * Author URI: https://larsvanrhijn.nl/
 * Text Domain: autotelex-inventory
 * Domain Path: /languages/
 *
 * @package autotelex-inventory
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'AI_PLUGIN_FILE' ) ) {
	define( 'AI_PLUGIN_FILE', __FILE__ );
}
if ( ! defined( 'AI_PLUGIN_URI' ) ) {
	define( 'AI_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

include_once dirname( __FILE__ ) . '/includes/class-aicore.php';

$GLOBALS['AICore'] = AICore::instance();
