<?php
/**
 * Plugin Name: Cyrillic To Latin
 * Plugin URI:
 * Description: Convert Serbian cyrillic to Serbian latin translation files.
 * Version:     1.0
 * Author:      Darko Lesendric
 * Author URI:  darko.lesendric@gmail.com
 * License:     GPL-2.0+
 * Copyright:   2017 Darko Lesendric
 * Domain Path: src/Resources/languages
 * Text Domain: cyrillic-to-latin
 */

namespace DLS;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}
require_once __DIR__.'/vendor/autoload.php';

add_action( 'plugins_loaded', __NAMESPACE__ . '\loaded' );

/**
 * Instantiate and run the plugin
 *
 * @return void
 */
$ctl_plugin = null;
function loaded() {
	global $ctl_plugin;
	define('CTL_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR);
	load_plugin_textdomain( 'cyrillic-to-latin', false, basename( dirname( __FILE__ ) ) . '/src/Resources/languages' );
	$ctl_plugin = new WP_CyrillicToLatin();

}


add_action( 'admin_menu', __NAMESPACE__.'\\load_ctl_menu' );

/**
 * Load this into menu
 */
function load_ctl_menu() {
	add_options_page( 'Konvertovanje ćirilice u latinicu', __('convert cyrillic', 'cyrillic-to-latin'), 'manage_options', 'cyrillic-to-latin', __NAMESPACE__.'\\ctl_option' );
}

/** Step 3. */
function ctl_option() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	//container
	echo '<div class="wrap">';
	global $ctl_plugin;
	/**
	 * @var WP_CyrillicToLatin $ctl_plugin
	 */
	$ctl_plugin->run();
	echo '</div>';
}

register_activation_hook( __FILE__, __NAMESPACE__ . '\flush_rewrites' );

/**
 * Plugin activation callback function.
 *
 * @return void
 */
function flush_rewrites() {
	flush_rewrite_rules();
}

