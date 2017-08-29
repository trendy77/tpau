<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    class atkp_endpoints
    {   
        /**
         * Construct the plugin object
         */
        public function __construct($pluginbase)
        {            
            add_action( 'wp_ajax_atkp_export_template',  array(&$this, 'atkp_export_template') );
            add_action( 'wp_ajax_atkp_search_departments',  array(&$this, 'atkp_search_departments') );
            add_action( 'wp_ajax_atkp_search_products',  array(&$this, 'atkp_search_products') );
            add_action( 'wp_ajax_atkp_search_browsenodes',  array(&$this, 'atkp_search_browsenodes') );
            add_action( 'wp_ajax_atkp_search_filters',  array(&$this, 'atkp_search_filters') );
        }
        
        function atkp_export_template() {
            $templateid = ATKPTools::get_get_parameter( 'templateid', 'int');
            
            require_once  ATKP_PLUGIN_DIR.'/includes/atkp_template.php';
            
            $atkp_template = atkp_template::load($templateid);
            
            $string =  serialize($atkp_template);
            $name = sanitize_title($atkp_template->title);
            
            # send the file to the browser as a download
            
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=\"".$name.".txt\"");
            header("Content-Transfer-Encoding: utf-8");
            header("Content-Length: ".strlen($string));
            
            echo $string;
            
            exit;
        }
        
        function atkp_search_products() {
            try {
                $azproducts = $this->quickSearch('product');

                if($azproducts != null) {
                    wp_send_json( $azproducts);
                }
            } catch(Exception $e) {
                $gif_data[] = array(
                        'error'  => 'An error has occurred.',
                        'message' => $e->getMessage(),
                    );
                    
                wp_send_json( $gif_data );
            }
        }
        
        function atkp_search_filters() {
            try {
                $azproducts = $this->quickSearch('filter');
                
                if($azproducts != null) {
                    wp_send_json( $azproducts);
                }
            } catch(Exception $e) {
                $gif_data[] = array(
                        'error'  => 'An error has occurred.',
                        'message' => $e->getMessage(),
                    );
                    
                wp_send_json( $gif_data );
            }
        }
        
        function atkp_search_departments() {
            try {
                $azproducts = $this->quickSearch('department');
                
                if($azproducts != null) {
                    wp_send_json( $azproducts);
                }
            } catch(Exception $e) {
                $gif_data[] = array(
                        'error'  => 'An error has occurred.',
                        'message' => $e->getMessage(),
                    );
                    
                wp_send_json( $gif_data );
            }
        }
        
        function atkp_search_browsenodes() {
            try {
                $aznodes = $this->quickSearch('browsenode');
                
                if($aznodes != null) {
                    wp_send_json( $aznodes);
                }
            } catch(Exception $e) {
                $gif_data[] = array(
                        'error'  => 'An error has occurred.',
                        'message' => $e->getMessage(),
                    );
                    
                wp_send_json( $gif_data );
            }
        }
                
        function quickSearch($searchType) {
            
            $shopid =  ATKPTools::get_post_parameter('shop', 'string');
            $keyword =  ATKPTools::get_post_parameter('keyword', 'string');
            
			$nounce =  ATKPTools::get_post_parameter('request_nonce', 'string');
            			
            if ( !wp_verify_nonce( $nounce, 'atkp-search-nonce' ) )
                throw new Exception('Nonce invalid');                
            
            if($shopid == '')       
                throw new Exception('shop required');
            if($keyword == '' && $searchType != 'department' && $searchType != 'filter')       
                throw new Exception('keyword required');
            
            require_once ATKP_PLUGIN_DIR.'/includes/shopproviders/atkp_shop_provider_base.php';
                        
            $shopids = explode('_', $shopid);
            
            $webservice = ATKPTools::get_post_setting($shopids[0], ATKP_SHOP_POSTTYPE.'_access_webservice');

            $myprovider = atkp_shop_provider_base::retrieve_provider($webservice);
            
            if(isset($myprovider)) {   
                $myprovider->checklogon($shopids[0], $shopid);
                
                if($searchType == 'department')
                    return $myprovider->retrieve_departments();
                else if($searchType == 'filter')
                    return $myprovider->retrieve_filters();
                else if($searchType == 'browsenode')
                    return $myprovider->retrieve_browsenodes($keyword);
                else
                    return $myprovider->quick_search($keyword, $searchType);
                
            }
        
        }
            
    }
    
?>