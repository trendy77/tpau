<?php
/**
 * Plugin Name: Affiliate Toolkit Starter
 * Plugin URI: http://www.affiliate-toolkit.com
 * Description: Import products and bestseller lists from Amazon and include them in your posts easily by use of shortcodes and more.
 * Version: 1.2
 * Author: Christof Servit
 * Author URI: http://www.servit.biz
 * License: GPL2
 * Text Domain: ATKP
 * Domain Path: /lang
 */
   
 // If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define('ATKP_PLUGIN_PREFIX', 'ATKP');
define('ATKP_PLUGIN_DIR', dirname(__FILE__));
define('ATKP_PLUGIN_FILE', __FILE__);

define('ATKP_PLUGIN_VERSION', 20);


add_action('plugins_loaded', 'my_affiliate_toolkit_lang');
function my_affiliate_toolkit_lang() {
	load_plugin_textdomain(ATKP_PLUGIN_PREFIX , false, dirname(plugin_basename(__FILE__)) .'/lang' );
}

require_once  ATKP_PLUGIN_DIR.'/includes/atkp_basics.php';

//** Plugin initialisieren **//

add_action('init', 'my_affiliate_toolkit_init');

function my_affiliate_toolkit_init() {
    if ( version_compare( get_bloginfo( 'version' ), '4.0', '<' ) )
    {
        wp_die( "You must update WordPress to use this plugin!" );
    }
    
    ATKPSettings::load_settings();
    
    if(is_admin()) {
        require_once  ATKP_PLUGIN_DIR.'/affiliate-toolkit-settings.php';
        $atkp_settings = new atkp_settings(array());
        
        require_once  ATKP_PLUGIN_DIR.'/includes/atkp_settings_toolkit.php';     
		require_once  ATKP_PLUGIN_DIR.'/includes/atkp_settings_advanced.php';  
		require_once  ATKP_PLUGIN_DIR.'/includes/atkp_settings_display.php';		
		require_once  ATKP_PLUGIN_DIR.'/includes/atkp_settings_compatibilitymode.php';
        
        $atkp_settings::$settings = array(
        __('Basic Settings', ATKP_PLUGIN_PREFIX) => array(new atkp_settings_toolkit(array()), 'toolkit_configuration_page'),
        __('Advanced Settings', ATKP_PLUGIN_PREFIX) => array(new atkp_settings_advanced(array()), 'advanced_configuration_page'),
		__('Display Settings', ATKP_PLUGIN_PREFIX) => array(new atkp_settings_display(array()), 'display_configuration_page'),
   		__('Compatibility mode', ATKP_PLUGIN_PREFIX) => array(new atkp_settings_compatibilitymode(array()), 'compatibilitymode_configuration_page'),		
        );
        
            
        require_once  ATKP_PLUGIN_DIR.'/includes/atkp_posttypes_shop.php';
        new atkp_posttypes_shop(array());
        
        require_once  ATKP_PLUGIN_DIR.'/includes/atkp_posttypes_product.php';
        new atkp_posttypes_product(array());
        
        require_once  ATKP_PLUGIN_DIR.'/includes/atkp_posttypes_list.php';
        new atkp_posttypes_list(array());
        
        require_once  ATKP_PLUGIN_DIR.'/includes/atkp_shortcode_generator.php';
        new atkp_shortcode_generator(array());
        
        add_action( 'admin_enqueue_scripts', 'my_affiliate_toolkit_admin_styles' );
        
        
        
    } else {    
        require_once  ATKP_PLUGIN_DIR.'/includes/atkp_shortcodes_product.php';
        new atkp_shortcodes_product(array());
        
        require_once  ATKP_PLUGIN_DIR.'/includes/atkp_shortcodes_list.php';
        new atkp_shortcodes_list(array());
		
		require_once  ATKP_PLUGIN_DIR.'/includes/atkp_shortcodes_asa1.php';
        new atkp_shortcodes_asa1(array());
               
        add_action('wp_enqueue_scripts', 'my_affiliate_toolkit_styles');
        
        //enable shortcodes at widget area
        //add_filter('widget_text', 'do_shortcode');
    }
    
    require_once  ATKP_PLUGIN_DIR.'/includes/atkp_cronjob.php';
    new atkp_cronjob(array());
    
  
}

    require_once  ATKP_PLUGIN_DIR.'/includes/atkp_endpoints.php';
    new atkp_endpoints(array());  

function my_affiliate_toolkit_admin_styles($hook) {

    if ( 'toplevel_page_ATKP_affiliate_toolkit-plugin' == $hook ) {
        wp_register_style( 'atkp-styles', plugins_url('/css/admin-style.css', __FILE__));
        wp_enqueue_style( 'atkp-styles' );
    } else if('post.php' == $hook || 'post-new.php' == $hook) {
        wp_register_style( 'atkp-styles', plugins_url('/css/style.css', __FILE__));
        wp_enqueue_style( 'atkp-styles' );
    }
}

function my_affiliate_toolkit_styles() {
    wp_register_style( 'atkp-styles', plugins_url('/css/style.css', __FILE__));
    
	ATKPTools::add_global_styles('atkp-styles');
	
	wp_register_script( 'atkp-scripts', plugins_url('/js/library.js', __FILE__));
	wp_enqueue_style( 'atkp-styles' );
    wp_enqueue_script( 'atkp-scripts' );
}

define('ATKP_INIT', '1');


 
 
 
 
 
 ?>