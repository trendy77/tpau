<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define('ATKP_EVENT', strtolower(ATKP_PLUGIN_PREFIX).'_event');
define('ATKP_CHECK', strtolower(ATKP_PLUGIN_PREFIX).'_check');

define('ATKP_SHOP_POSTTYPE', strtolower(ATKP_PLUGIN_PREFIX).'_shop');
define('ATKP_LIST_POSTTYPE', strtolower(ATKP_PLUGIN_PREFIX).'_list');
define('ATKP_PRODUCT_POSTTYPE', strtolower(ATKP_PLUGIN_PREFIX).'_product');
define('ATKP_TEMPLATE_POSTTYPE', strtolower(ATKP_PLUGIN_PREFIX).'_template');

define('ATKP_SHORTCODE', strtolower(ATKP_PLUGIN_PREFIX).'_shortcode');
define('ATKP_LIST_SHORTCODE', strtolower(ATKP_PLUGIN_PREFIX).'_list');
define('ATKP_PRODUCT_SHORTCODE', strtolower(ATKP_PLUGIN_PREFIX).'_product');
define('ATKP_WIDGET', strtolower(ATKP_PLUGIN_PREFIX).'_widget');


//** Credits im Kopf erzeugen **//
if(ATKP_PLUGIN_VERSION < 40) {
    add_action( 'wp_head', 'my_affiliate_toolkit_tags' );

    function my_affiliate_toolkit_tags() {
        echo "\r\n".'<!-- '.__('This page uses Affiliate Toolkit', ATKP_PLUGIN_PREFIX).' / https://www.affiliate-toolkit.com -->'."\r\n\r\n";
    }
}

class ATKPSettings {
    //Plugin-Prefix: Affiliate Toolkit Plugin (atkp)
  
    /*public static $access_key;
    public static $access_secret_key;
    public static $access_message;
    public static $access_website;
    public static $access_tracking_id;*/
    public static $access_cache_duration;
    public static $access_mark_links;
    public static $access_show_disclaimer;
    public static $access_disclaimer_text;
    /*public static $load_customer_reviews;*/
	public static $add_to_cart;
	public static $open_window;
	
	public static $enable_ssl;
	
	public static $show_linkinfo;
	public static $linkinfo_template;
	
	public static $check_enabled;
	public static $notification_interval;
	public static $email_recipient;
    public static $short_title_length;
    
    public static $show_moreoffers;
    public static $moreoffers_template;
    
    public static $description_length;
    public static $boxcontent;
  
    public static $boxstyle;
    public static $bestsellerribbon;
    public static $showprice;
    public static $showpricediscount;
    public static $showstarrating;
    public static $showrating;
    
    public static $linktracking;
  
    /**
 * Returns current plugin version.
 * 
 * @return string Plugin version
 */
public static function plugin_get_version() {

	$plugin_data = get_plugin_data(ATKP_PLUGIN_FILE);
    $plugin_version = $plugin_data['Version'];
    return $plugin_version;
}
  
    public static function load_settings() {
        ATKPSettings::$linktracking = get_option(ATKP_PLUGIN_PREFIX.'_link_click_tracking', 0);
        
        ATKPSettings::$access_cache_duration = get_option(ATKP_PLUGIN_PREFIX.'_cache_duration', 1440);
        ATKPSettings::$access_mark_links = get_option(ATKP_PLUGIN_PREFIX.'_mark_links', 1);
                     
        ATKPSettings::$access_show_disclaimer = get_option(ATKP_PLUGIN_PREFIX.'_show_disclaimer', 0);
        ATKPSettings::$access_disclaimer_text= get_option(ATKP_PLUGIN_PREFIX.'_disclaimer_text');        
        
        ATKPSettings::$add_to_cart = get_option(ATKP_PLUGIN_PREFIX.'_add_to_cart', 0);
        ATKPSettings::$open_window = get_option(ATKP_PLUGIN_PREFIX.'_open_window', 1);
        
        ATKPSettings::$show_linkinfo = get_option(ATKP_PLUGIN_PREFIX.'_show_linkinfo', 0);
        ATKPSettings::$linkinfo_template = get_option(ATKP_PLUGIN_PREFIX.'_linkinfo_template');
        
        ATKPSettings::$check_enabled = get_option(ATKP_PLUGIN_PREFIX.'_check_enabled');
        ATKPSettings::$notification_interval = get_option(ATKP_PLUGIN_PREFIX.'_notification_interval', 4320);
        ATKPSettings::$email_recipient = get_option(ATKP_PLUGIN_PREFIX.'_email_recipient');
        
        ATKPSettings::$short_title_length = get_option(ATKP_PLUGIN_PREFIX.'_short_title_length',0);
        
        ATKPSettings::$show_moreoffers = get_option(ATKP_PLUGIN_PREFIX.'_show_moreoffers',0);
        ATKPSettings::$moreoffers_template = get_option(ATKP_PLUGIN_PREFIX.'_moreoffers_template','');
     
        ATKPSettings::$description_length = get_option(ATKP_PLUGIN_PREFIX.'_description_length',0);
        ATKPSettings::$boxcontent = get_option(ATKP_PLUGIN_PREFIX.'_boxcontent','');
     
        ATKPSettings::$boxstyle = get_option(ATKP_PLUGIN_PREFIX.'_boxstyle',1);
        ATKPSettings::$bestsellerribbon = get_option(ATKP_PLUGIN_PREFIX.'_bestsellerribbon',1);
        ATKPSettings::$showprice = get_option(ATKP_PLUGIN_PREFIX.'_showprice',1);
        ATKPSettings::$showpricediscount = get_option(ATKP_PLUGIN_PREFIX.'_showpricediscount',1);
        ATKPSettings::$showstarrating = get_option(ATKP_PLUGIN_PREFIX.'_showstarrating',1);
        ATKPSettings::$showrating = get_option(ATKP_PLUGIN_PREFIX.'_showrating',1);
        
        $loglevel = get_option(ATKP_PLUGIN_PREFIX.'_loglevel','off');
        
        ATKPLog::Init(ATKP_PLUGIN_DIR.'/log/log.txt', $loglevel);
    }
  
    public static function connection_ready() {
        return !empty(ATKPSettings::$access_key) && !empty(ATKPSettings::$access_secret_key) && empty(ATKPSettings::$access_message) && extension_loaded('soap');
    }
}

class ATKPTools {
    
    public static function add_global_styles($name) {

            require_once  ATKP_PLUGIN_DIR.'/includes/atkp_output.php';
            $output = new atkp_output();
            $custom_css = $output->get_css_output();
            
            wp_add_inline_style($name, $custom_css );
        }
    
    public static function add_column( $post_types, $label, $callback, $priority = 10 ) {
		if ( !is_array( $post_types ) ) {
			$post_types = array( $post_types );
		}
		foreach ( $post_types as $post_type ) {
			$filter_name = 'manage_'.$post_type.'_posts_columns';
			add_filter( $filter_name , function ( $columns ) use ( $label, $priority ) {
					$key = sanitize_title( $label );
					$col = array( $key => $label );
					if ( $priority < 0 ) {
						return array_merge( $col, $columns );
					} else if ( $priority > count( $columns ) ) {
						return array_merge( $columns, $col );
					} else {
						$offset = $priority;
						$sorted = array_slice( $columns, 0, $offset, true ) + $col + array_slice( $columns, $offset, NULL, true );
						return $sorted;
					}
				}, $priority );
			add_action( 'manage_'.$post_type.'_posts_custom_column', function( $col, $pid ) use ( $label, $callback ) {
					$key = sanitize_title( $label );
					if ( $col == $key ) {
						$callback( $pid );
					}
				}, $priority, 2 );
		}
	}
	
	public static function show_notice( $text, $class = 'updated' ) {
		if ( $class == 'yellow' ) {
			$class = 'updated';
		}
		if ( $class == 'red' ) {
			$class = 'error';
		}
		add_action( 'admin_notices', function() use ( $text, $class ) {
				echo '<div class="'.$class.'"><p>'.$text.'</p></div>';
			}, 1 );
	}
    
    
    public static function get_siteurl() {
        
        $url = 'unknown';
        
        if(is_multisite())
            $url = network_site_url();
        else
            $url = site_url();
        
        return $url;         
     }
     
     public static function get_endpointurl() {
        
        $url = admin_url('admin-ajax.php');
        
        return $url;         
     }
	 
	 public static function exists_get_parameter($key) {
		 return isset($_GET[$key]);
	 }
	 
	 public static function get_get_parameter($key, $type) {
		$parametervalue = null;
                      
        if(isset($_GET[$key])) {
            $parametervalue = $_GET[$key];
        }
		
		return ATKPTools::get_casted_value($parametervalue, $type);
	 }
	 
	 
	 public static function exists_post_parameter($key) {
		 return isset($_POST[$key]);
	 }
	 
	 public static function get_post_parameter($key, $type) {
		$parametervalue = null;
                      
        if(isset($_POST[$key])) {
            $parametervalue = $_POST[$key];
        }
		
		return ATKPTools::get_casted_value($parametervalue, $type);
	 }

     public static function get_casted_value($parametervalue, $type) {
                
        switch($type) {
            case 'bool':
                if($parametervalue == null || $parametervalue == '')
                    return false;
                else {
                    //hack for older versions than 5.5
                    if(function_exists('boolval'))
                        return boolval($parametervalue);   
                    else
                        return (bool)$parametervalue;
                }
                break;
            case 'int':
                if($parametervalue == null || $parametervalue == '')
                    return 0;
                else
                    return intval($parametervalue);
                break;
            case 'double':
                if($parametervalue == null || $parametervalue == '')
                    return 0;
                else
                    return floatval($parametervalue);
                break;
            case 'string':
                if($parametervalue == null || $parametervalue == '')
                    return '';
                else
                    return sanitize_text_field($parametervalue);
                break;
            case 'multistring2':
            case 'multistring':
                if($parametervalue == null || $parametervalue == '')
                    return '';
                else
                    return implode( "\n", array_map( 'sanitize_text_field', (array) explode( "\n", (string)$parametervalue) ));                
                break;            
            case 'allhtml':
                if($parametervalue == null || $parametervalue == '')
                    return '';
                else
                    return ($parametervalue);          
                break;
            case 'html':
                if($parametervalue == null || $parametervalue == '')
                    return '';
                else
                    return wp_kses_post($parametervalue);          
                
                break;
            case 'url':
                if($parametervalue == null || $parametervalue == '')
                    return '';
                else
                    return sanitize_text_field($parametervalue);
                break;
            default:
                throw new exception('type unkown: '.$type);
        }
     }
     
    

    
     public static function get_post_setting($post_id, $key) {
      $value = get_post_meta($post_id, $key);
      
      if(isset($value) && is_array($value) && count($value) > 0) {
        return $value[0];
      }
      else {
        return '';
      }
     }
     public static function set_post_setting($post_id, $key, $value) {
         
        delete_post_meta($post_id, $key);
        if($value != null)
        add_post_meta($post_id, $key, $value);
         
     }
     
    public static function startsWith($haystack, $needle) {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }
    
    public static function price_to_float($s) {
		$s = str_replace(',', '.', $s);

		// remove everything except numbers and dot "."
		$s = preg_replace("/[^0-9\.]/", "", $s);

		// remove all seperators from first part and keep the end
		$s = str_replace('.', '',substr($s, 0, -3)) . substr($s, -3);

		// return float
		return round((float)$s, 2);
	}
}

class ATKPCache {
    public static function get_cache_by_id($postid) {			
	return get_transient( ATKP_PLUGIN_PREFIX.'_' . $postid );		
    }

    public static function set_cache_by_id($postid, $data, $expiration = 0) {
    	if (!isset($data) || empty($data) || sizeof($data)==0) {
    	    delete_transient(ATKP_PLUGIN_PREFIX.'_' . $postid);
    	    return;
    	}

    	set_transient( ATKP_PLUGIN_PREFIX.'_' . $postid, $data, $expiration);
    }
    
   public static function GetCache($category, $keyword, $count) {			
	return get_transient( ATKP_PLUGIN_PREFIX.'_' . md5($category .'-'. $keyword .'-'. $count) );		
    }

    public static function SetCache($category, $keyword, $count, $data) {
    	if (!isset($data) || empty($data) || sizeof($data)==0) {
    	    delete_transient(ATKP_PLUGIN_PREFIX.'_' . md5($category .'-'. $keyword .'-'. $count));
    	    return;
    	}

    	set_transient( ATKP_PLUGIN_PREFIX.'_' . md5($category .'-'. $keyword .'-'. $count) , $data, ATKPSettings::$access_cache_duration * 60 );
    }

    public static function ClearCache() {
        global $wpdb;

        $result = $wpdb->get_results("SELECT * FROM $wpdb->options WHERE option_name LIKE '_transient_".ATKP_PLUGIN_PREFIX."_%'");
    		foreach ( $result as $row ) {
    			delete_transient(str_replace('_transient_', '',$row->option_name), true);
    		}		 
        }    
    }

class ATKPHomeLinks {
    
    public static function echo_banner() {
		
		$bannerurl = plugins_url('images/468x60_buy_starter_%locale%.jpg', ATKP_PLUGIN_FILE );
		
		$str = '<a href="https://www.affiliate-toolkit.com/%locale%/gute-gruende-fuer-ein-upgrade-auf-eine-kaufversion/" target="_blank" title="'. __('Good reasons for a upgrade!', ATKP_PLUGIN_PREFIX).'"><img src="'.$bannerurl.'" alt="Affiliate-Toolkit Banner" /></a>';
		
		echo ATKPHomeLinks::CheckLink($str);
	}
	
    public static function ReplaceLinkType($str) {
        
        $str = str_replace('%link_toolkit-bestseller%', 'https://www.affiliate-toolkit.com/%locale%/toolkit-bestseller/', $str);   
        $str = str_replace('%link_get-amazon-apikey%', 'https://www.affiliate-toolkit.com/%locale%/get-amazon-apikey/', $str); 
        $str = str_replace('%link_load-amazon-customer-reviews%', 'https://www.affiliate-toolkit.com/%locale%/load-amazon-customer-reviews/', $str);
        $str = str_replace('%link_mark-affiliate-links%', 'https://www.affiliate-toolkit.com/%locale%/mark-affiliate-links/', $str);
        $str = str_replace('%link_get-amazon-search-department%', 'https://www.affiliate-toolkit.com/%locale%/get-amazon-search-department/', $str);
        
        $str = str_replace('%link_help%', 'https://www.affiliate-toolkit.com/%locale%/help/', $str);
        
        $str = str_replace('%link_support%', 'https://www.affiliate-toolkit.com/%locale%/support/', $str);
        
        $str = str_replace('%link_affiliate%', 'https://www.affiliate-toolkit.com/%locale%/affiliate/', $str);
        $str = str_replace('%link_contact%', 'https://www.affiliate-toolkit.com/%locale%/contact/', $str);
        $str = str_replace('%link_get-license-key%', 'https://www.affiliate-toolkit.com/%locale%/get-license-key/', $str);
        $str = str_replace('%link_customfields%', 'https://www.affiliate-toolkit.com/%locale%/customfields/', $str);
        

        return ATKPHomeLinks::CheckLink($str);
    }
    
    public static function CheckLink($link) {
  
    if(get_locale() == 'de_DE' || get_locale() == 'de_AT')
        $locale = "de";
    else
        $locale = "en";
    
    return str_replace('%locale%', $locale, $link);   
     
 }
    
}

class ATKPLog {
    private static $log;
    public static $logenabled;
    
    public static function Init($filepath, $priority) {
        ATKPLog::$logenabled = false;        
        
        if($priority != 'off' && $priority != '') {     
			if (!class_exists('KLogger')) 
    			require_once  ATKP_PLUGIN_DIR.'/lib/klogger.php';
			
			$logpriority = KLogger::OFF;
            
			switch($priority) {
				case 'debug':
					ATKPLog::$logenabled = true;
					$logpriority = KLogger::DEBUG;
					break;
				case 'error':
					ATKPLog::$logenabled = true;
					$logpriority = KLogger::ERROR;
					break;
			}
			
            ATKPLog::$log = new KLogger ( $filepath, $logpriority );   
        }
    }
    
    public static function LogInfo($line)
	{
	    if(!ATKPLog::$logenabled)
	        return;	    
	    
		ATKPLog::$log->LogInfo( $line);
	}
	
	public static function LogDebug($line, $context = null)
	{
	    if(!ATKPLog::$logenabled)
	        return;
	        
		ATKPLog::$log->LogDebug( $line );
		
		if($context != null) {
		    ATKPLog::$log->LogDebug( ATKPLog::contextToString($context));
		}
	}
	
	/**
     * Takes the given context and coverts it to a string.
     *
     * @param  array $context The Context
     * @return string
     */
    protected static function contextToString($context)
    {
        $export = '';
        foreach ($context as $key => $value) {
            $export .= "{$key}: ";
            $export .= preg_replace(array(
                '/=>\s+([a-zA-Z])/im',
                '/array\(\s+\)/im',
                '/^  |\G  /m'
            ), array(
                '=> $1',
                'array()',
                '    '
            ), str_replace('array (', 'array(', var_export($value, true)));
            $export .= PHP_EOL;
        }
        return str_replace(array('\\\\', '\\\''), array('\\', '\''), rtrim($export));
    }
	
	public static function LogWarn($line)
	{
	    if(!ATKPLog::$logenabled)
	        return;
	        
		ATKPLog::$log->LogWarn( $line);	
	}
	
	public static function LogError($line)
	{
	    if(!ATKPLog::$logenabled)
	        return;
	        
		ATKPLog::$log->LogError( $line  );		
	}
    
}


?>