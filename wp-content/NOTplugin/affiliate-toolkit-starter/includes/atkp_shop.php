<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class atkp_shop{
        public $data = array();

        function __construct()
        {
            $this->logourl ='';
            $this->buyattext ='';
            $this->addtocarttext ='';
            
            $this->access_key ='';
            $this->access_secret_key ='';
            $this->access_message ='';
            $this->access_website ='';
            $this->access_tracking_id ='';
            $this->load_customer_reviews =0;
            $this->enable_ssl =0;
            $this->default_asins = '';
            $this->shopid ='';
        }

        public function connection_ready() {
             
            $result =  $this->access_key != '' && $this->access_secret_key != '' && $this->access_message == '' && extension_loaded('soap');
            
            return $result;
        }

        public static function load($post_id) {
         
            $shop = get_post( $post_id ); 
    
            if(!isset($shop) || $shop == null)
                throw new Exception( 'shop not found: '.$post_id);
            if($shop->post_type != ATKP_SHOP_POSTTYPE)
                 throw new Exception('invalid shop post_type: '.$shop->post_type. ', $post_id: '.$post_id);
            
            $shp = new atkp_shop();
                
            $shp->shopid=$post_id; 
            $shp->logourl = ATKPTools::get_post_setting( $post_id, ATKP_SHOP_POSTTYPE.'_logo_url');
            
            $shp->buyattext = ATKPTools::get_post_setting( $post_id, ATKP_SHOP_POSTTYPE.'_text_buyat');
            $shp->addtocarttext =ATKPTools::get_post_setting( $post_id, ATKP_SHOP_POSTTYPE.'_text_addtocart');
            
            $shp->access_key = ATKPTools::get_post_setting( $post_id, ATKP_SHOP_POSTTYPE.'_access_key');
            $shp->access_secret_key =ATKPTools::get_post_setting( $post_id, ATKP_SHOP_POSTTYPE.'_access_secret_key');
            $shp->access_message = ATKPTools::get_post_setting( $post_id, ATKP_SHOP_POSTTYPE.'_access_message');
            $shp->access_website =ATKPTools::get_post_setting( $post_id, ATKP_SHOP_POSTTYPE.'_access_website');
            $shp->access_tracking_id =ATKPTools::get_post_setting( $post_id, ATKP_SHOP_POSTTYPE.'_access_tracking_id');
            $shp->load_customer_reviews = ATKPTools::get_post_setting( $post_id, ATKP_SHOP_POSTTYPE.'_load_customer_reviews');
            $shp->enable_ssl =ATKPTools::get_post_setting( $post_id, ATKP_SHOP_POSTTYPE.'_enable_ssl');
            $shp->default_asins = ATKPTools::get_post_setting( $post_id, ATKP_SHOP_POSTTYPE.'_default_asins');
            
            return $shp;
        }

        public function __get($member) {
            if (isset($this->data[$member])) {
                return $this->data[$member];
            }
        }

        public function __set($member, $value) {            
           // if (isset($this->data[$member])) {
                $this->data[$member] = $value;
            //}
        }
    }
    
    
?>