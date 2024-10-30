<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that
 * also follow WordPress Coding Standards and PHP best practices.
 *
 * @package   lxt_jast
 * @author    isurgeli@gmail.com
 * @license   GPL-2.0+
 * @link      http://isurge.wordpress.com
 * @copyright 2013 Li xintao
 *
 * @wordpress-plugin
 * Plugin Name:       Just another survey tool
 * Plugin URI:        http://http://isurge.wordpress.com/
 * Description:       Just another survey tool for Wordpress.
 * Version:           1.0.0
 * Author:            Li XIntao
 * Author URI:        http://http://isurge.wordpress.com/
 * Text Domain:       plugin-name-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/plugin-lxt_jast.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'lxt_jast_plugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'lxt_jast_plugin', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'lxt_jast_plugin', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/plugin-lxt_jast-admin.php' );
	add_action( 'plugins_loaded', array( 'lxt_jast_plugin_Admin', 'get_instance' ) );
}

