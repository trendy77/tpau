<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class atkp_shop_provider_base 
{ 
    //das ist die basis klasse für alle shop provider
    
    public function __construct() 
    { 
        
    } 
    
    public function check_offer($offer) {
                
         return $offer;   
    }
    
    public function get_defaultlogo() {
        return '';   
    }
    
    protected static function price_to_float($s) {
		$s = str_replace(',', '.', $s);

		// remove everything except numbers and dot "."
		$s = preg_replace("/[^0-9\.]/", "", $s);

		// remove all seperators from first part and keep the end
		$s = str_replace('.', '',substr($s, 0, -3)) . substr($s, -3);

		// return float
		return round((float)$s, 2);
	}
    
    public function get_defaultbtn1_text() {
        return __('Buy now!', ATKP_PLUGIN_PREFIX);   
    }
    
    public function get_defaultbtn2_text() {
        return __('Buy now!', ATKP_PLUGIN_PREFIX);   
    }
    
    public $displayshoplogo;
    public $enablepricecomparison;
    public $buyat;
    public $addtocart;
    
    public function load_basicsettings( $shopid) {
        $this->displayshoplogo = ATKPTools::get_post_setting( $shopid, ATKP_SHOP_POSTTYPE.'_displayshoplogo');  
        $this->enablepricecomparison = ATKPTools::get_post_setting( $shopid, ATKP_SHOP_POSTTYPE.'_enablepricecomparison');   
        $this->buyat = ATKPTools::get_post_setting( $shopid, ATKP_SHOP_POSTTYPE.'_text_buyat');   
        $this->addtocart = ATKPTools::get_post_setting( $shopid, ATKP_SHOP_POSTTYPE.'_text_addtocart');   
    }
    
    
    public static function retrieve_provider($id) {
     
     switch($id) {
          case '1':
              require_once ATKP_PLUGIN_DIR.'/includes/shopproviders/atkp_shop_provider_amazon.php';
			return new atkp_shop_provider_amazon();
              break;       
     }
     }
    
    public static function retrieve_providers() {
     
         $providers = array();
         
         require_once ATKP_PLUGIN_DIR.'/includes/shopproviders/atkp_shop_provider_amazon.php';
         
         
        $providers['1'] = new atkp_shop_provider_amazon();
       
     
        return $providers;
    }
    
    public function get_caption() {
        return 'base';
    }
    
    public function check_configuration($post_id) {
        return '';
    }    
    
    public function set_configuration($post_id) {
        
        
    }
    
    public function get_configuration($post) {
        
        
    }
    
    public function get_shops($post_id) {
        return array();
    }
    
    public function checklogon($shopid, $subshopid) {
        
    }
    
    public function quick_search($keyword, $searchType) {
        
    }
    
    public function retrieve_browsenodes($keyword) {
        
    }
    
    public function retrieve_departments() {
        
    }
    
    public function retrieve_filters() {
        
    }

    public function retrieve_product($asin, $id_type = 'ASIN') {
        
    }
    
    public function retrieve_products($asins) {
        
    }
    
    public function get_supportedlistsources() {
        
    }
    
    public function retrieve_list($requestType, $nodeid, $keyword, $asin, $maxCount, $sortOrder, $filter) {
        
    }
    
} 

class subshop
{
    public $data = array();

        function __construct()
        {
            $this->logourl ='';
            $this->shopid ='';
            $this->programid ='';
            $this->title ='';
            $this->enabled = false;
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