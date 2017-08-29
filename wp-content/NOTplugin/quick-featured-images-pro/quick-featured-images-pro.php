<?php
/**
 * @package   Quick_Featured_Images_Pro_Admin
 * @author    Martin Stehle <m.stehle@gmx.de>
 * @license   GPL-2.0+
 * @link      http://stehle-internet.de/
 * @copyright 2014 Martin Stehle
 *
 * @wordpress-plugin
 * Plugin Name:       Quick Featured Images Pro
 * Plugin URI:        http://quickfeaturedimages.com/
 * Description:       Your time-saving Swiss Army Knife for featured images: Set, replace and delete them in bulk, in posts lists and set default images for future posts.
 * Version:           5.4.1
 * Author:            Martin Stehle
 * Author URI:        http://stehle-internet.de
 * Text Domain:       quick-featured-images-pro
 * Domain Path:       /languages
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*
 * There is no frontend use of this plugin
 * so call it only in the backend
 *
 */
$qfip_root = plugin_dir_path( __FILE__ );

require_once( $qfip_root . 'admin/class-quick-featured-images-pro-admin.php' );
add_action( 'plugins_loaded', array( 'Quick_Featured_Images_Pro_Admin', 'get_instance' ) );

if ( is_admin() ) {

	/*
	 * Register hooks that are fired when the plugin is activated or deactivated.
	 * When the plugin is deleted, the uninstall.php file is loaded.
	 *
	 */
	register_activation_hook( __FILE__, array( 'Quick_Featured_Images_Pro_Admin', 'activate' ) );
	register_deactivation_hook( __FILE__, array( 'Quick_Featured_Images_Pro_Admin', 'deactivate' ) );
	
	/*
	 * Make object instance of bulk tools class
	 *
	 */
	require_once( $qfip_root . 'admin/class-quick-featured-images-pro-tools.php' );
	add_action( 'plugins_loaded', array( 'Quick_Featured_Images_Pro_Tools', 'get_instance' ) );

}

/*
 * since 8.0: Make object instance of default images functions class
 *
 */
require_once( $qfip_root . 'admin/class-quick-featured-images-pro-defaults.php' );
add_action( 'plugins_loaded', array( 'Quick_Featured_Images_Pro_Defaults', 'get_instance' ) );


if ( is_admin() ) {

	/*
	 * since 7.0: Make object instance of options page class
	 *
	 */
	require_once( $qfip_root . 'admin/class-quick-featured-images-pro-settings.php' );
	add_action( 'plugins_loaded', array( 'Quick_Featured_Images_Pro_Settings', 'get_instance' ) );

	/*
	 * since 7.0: Make object instance of column functions class
	 *
	 */
	require_once( $qfip_root . 'admin/class-quick-featured-images-pro-columns.php' );
	add_action( 'plugins_loaded', array( 'Quick_Featured_Images_Pro_Columns', 'get_instance' ) );

	/*
	 * since 2.0 pro: Make object instance of licensing class
	 *
	 */
	require_once( $qfip_root . 'admin/class-quick-featured-images-pro-licensing.php' );
	add_action( 'plugins_loaded', array( 'Quick_Featured_Images_Pro_Licensing', 'get_instance' ) );

}
