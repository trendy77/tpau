<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class atkp_shop_provider_amazon extends atkp_shop_provider_base
{ 
    //das ist die basis klasse für alle shop provider
    
    public function __construct() 
    { 
        
    } 
    
    public function get_caption() {
        return __('Amazon Product Advertising API', ATKP_PLUGIN_PREFIX);
    }
    
    public function get_defaultlogo() {
        
    }
    
    public function get_defaultbtn1_text() {
        return __('Buy now at Amazon', ATKP_PLUGIN_PREFIX);   
    }
    
    public function get_defaultbtn2_text() { 
        return __('Add to Amazon Cart', ATKP_PLUGIN_PREFIX);  
    }
    
    public function check_configuration($post_id) {
        try {
            $apikey = ATKPTools::get_post_setting($post_id, ATKP_SHOP_POSTTYPE.'_access_key');
            $apisecretkey = ATKPTools::get_post_setting($post_id, ATKP_SHOP_POSTTYPE.'_access_secret_key');
            $website = ATKPTools::get_post_setting($post_id, ATKP_SHOP_POSTTYPE.'_access_website');
            $trackingid = ATKPTools::get_post_setting($post_id, ATKP_SHOP_POSTTYPE.'_access_tracking_id');
            
            $message = '';
            if ($apikey != '' && $apisecretkey != '') {
                
                if (!class_exists('ApaiIO'))
                    require_once ATKP_PLUGIN_DIR.'/lib/apai-io/bootstrap.php';                
                            
                
                $request = new \ApaiIO\Request\Rest\Request(array(\ApaiIO\Request\Rest\Request::FOLLOW_LOCATION => 0));
    
                
                $conf = new \ApaiIO\Configuration\GenericConfiguration();
                $conf
                    ->setCountry($website)
                    ->setAccessKey($apikey)
                    ->setSecretKey($apisecretkey)
                    ->setAssociateTag($trackingid)
                    //->setRequest('\ApaiIO\Request\Rest\Request')
                    //->setResponseTransformer('\ApaiIO\ResponseTransformer\XmlToDomDocument');
                    //->setRequest('\ApaiIO\Request\Soap\Request')
                    //->setResponseTransformer('\ApaiIO\ResponseTransformer\ObjectToArray');
                    ->setRequest($request);
    
                
                $api = new \ApaiIO\ApaiIO($conf);
                
                $search = new ApaiIO\Operations\Search();
                $search->setKeywords('Harry Potter');
                
                $test = $api->runOperation($search);
               
                $test = $this->convert_response($test);

                // Success
                if ( ! empty ( $test->OperationRequest->RequestId ) ) {
                    //alles ok
                    
                    $subshopsold = ATKPTools::get_post_setting( $post_id, ATKP_SHOP_POSTTYPE.'_default_shops');
                    $subshops = array();
                    
                    //add subshop for amazon
                    $subshop = new subshop();
                    $subshop->title = __('Amazon', ATKP_PLUGIN_PREFIX);
                    $subshop->shopid = '1';
                    $subshop->logourl =  plugins_url('images/amazon-logo.png', ATKP_PLUGIN_FILE);
                    $subshop->smalllogourl = plugins_url('images/amazon-logo-small.png', ATKP_PLUGIN_FILE);
                    
                    if($subshopsold == null || count($subshopsold) == 0)
                        $subshop->enabled = true;
                    
                    if(is_array($subshopsold))
                         foreach($subshopsold as $shopold) {
                              if($subshop->shopid == $shopold->shopid && $subshop->programid == $shopold->programid) {
                                  $subshop->enabled = $shopold->enabled;
                              } 
                         }
                         
                     array_push($subshops, $subshop);
                    
                    //add subshop for marketplace
                     $subshop = new subshop();
                    $subshop->title = __('Amazon Marketplace', ATKP_PLUGIN_PREFIX);
                    $subshop->shopid = '2';
                    $subshop->logourl =  plugins_url('images/amazon-logo.png', ATKP_PLUGIN_FILE);
                    $subshop->smalllogourl = plugins_url('images/amazon-logo-small.png', ATKP_PLUGIN_FILE);
                    
                    if(is_array($subshopsold))
                         foreach($subshopsold as $shopold) {
                              if($subshop->shopid == $shopold->shopid && $subshop->programid == $shopold->programid) {
                                  $subshop->enabled = $shopold->enabled;
                              } 
                         }
                         
                     array_push($subshops, $subshop);
                    
                    //add subshop for amazon + marketplace                    
                     $subshop = new subshop();
                    $subshop->title = __('Combine Amazon and Marketplace', ATKP_PLUGIN_PREFIX);
                    $subshop->shopid = '3';
                    //TODO: marketplacelogo
                    $subshop->logourl =  plugins_url('images/amazon-logo.png', ATKP_PLUGIN_FILE);
                    $subshop->smalllogourl = plugins_url('images/amazon-logo-small.png', ATKP_PLUGIN_FILE);
                    
                    if(is_array($subshopsold))
                         foreach($subshopsold as $shopold) {
                              if($subshop->shopid == $shopold->shopid && $subshop->programid == $shopold->programid) {
                                  $subshop->enabled = $shopold->enabled;
                              } 
                         }
                         
                     array_push($subshops, $subshop);
                     
                    
                 ATKPTools::set_post_setting( $post_id, ATKP_SHOP_POSTTYPE.'_default_shops',$subshops);
    
                // Error Code
                } elseif ( ! empty ( $test->Error->Code ) ) {
                    return $test->Error->Message;
                // Fallback for any other issue
                } else {
                    return 'undefined';
                }
                
                   
            } else {
                //wenn zugangscodes gelöscht werden muss message auch geleert werden
                 $message = 'Credientials are empty';  
            }
            
            return $message;
        } catch(Exception $e) {
	        return $e->getMessage();
	    }
    }
    
    private function convert_response($response) {

        //return json_decode(json_encode($response), false);
        return json_decode(json_encode((array)simplexml_load_string($response)),0);
    }
    
    public function set_configuration($post_id) {
        
        
     
        ATKPTools::set_post_setting( $post_id, ATKP_SHOP_POSTTYPE.'_access_website', ATKPTools::get_post_parameter(ATKP_SHOP_POSTTYPE.'_amz_access_website', 'string'));
        ATKPTools::set_post_setting( $post_id, ATKP_SHOP_POSTTYPE.'_access_tracking_id', ATKPTools::get_post_parameter(ATKP_SHOP_POSTTYPE.'_amz_access_tracking_id', 'string'));
        
        ATKPTools::set_post_setting( $post_id, ATKP_SHOP_POSTTYPE.'_load_customer_reviews', ATKPTools::get_post_parameter(ATKP_SHOP_POSTTYPE.'_amz_load_customer_reviews', 'bool'));
        ATKPTools::set_post_setting( $post_id, ATKP_SHOP_POSTTYPE.'_enable_ssl', ATKPTools::get_post_parameter(ATKP_SHOP_POSTTYPE.'_amz_enable_ssl', 'bool'));
       
        ATKPTools::set_post_setting( $post_id, ATKP_SHOP_POSTTYPE.'_access_key', ATKPTools::get_post_parameter(ATKP_SHOP_POSTTYPE.'_amz_access_key', 'string'));
        ATKPTools::set_post_setting( $post_id, ATKP_SHOP_POSTTYPE.'_access_secret_key', ATKPTools::get_post_parameter(ATKP_SHOP_POSTTYPE.'_amz_access_secret_key', 'string'));   
        
        
        $selectedstore = ATKPTools::get_post_parameter(ATKP_PRODUCT_POSTTYPE.'_amz_store', 'int'); 
        
        if($selectedstore == '')
            $selectedstore = 1;
        
        ATKPTools::set_post_setting( $post_id, ATKP_SHOP_POSTTYPE.'_amz_store', $selectedstore);
    }
    
    public function get_configuration($post) {
        $webservice = ATKPTools::get_post_setting($post->ID, ATKP_SHOP_POSTTYPE.'_access_webservice');
		
        $apikey = '';
        $apisecretkey = '';
        $subshops = null;
        
        if($webservice == '1') {		
            $apikey = ATKPTools::get_post_setting($post->ID, ATKP_SHOP_POSTTYPE.'_access_key');
            $apisecretkey = ATKPTools::get_post_setting($post->ID, ATKP_SHOP_POSTTYPE.'_access_secret_key');
            
        }
     ?>
     <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo ATKP_SHOP_POSTTYPE.'_amz_access_key' ?>">
                                    <?php _e('Amazon Access Key ID', ATKP_PLUGIN_PREFIX) ?>:
                                </label> 
                            </th>
                            <td>
                                <input  style="width:40%" type="text" id="<?php echo ATKP_SHOP_POSTTYPE.'_amz_access_key' ?>" name="<?php echo ATKP_SHOP_POSTTYPE.'_amz_access_key' ?>" value="<?php echo esc_attr($apikey); ?>">
                                <label for="">
                
                    <?php echo ATKPHomeLinks::ReplaceLinkType(__('<a href="%link_get-amazon-apikey%" target="_blank">More information</a>', ATKP_PLUGIN_PREFIX)) ?>
                 </label>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo ATKP_SHOP_POSTTYPE.'_amz_access_secret_key' ?>">
                                    <?php _e('Amazon Secret Access Key', ATKP_PLUGIN_PREFIX) ?>:
                                </label> 
                            </th>
                            <td>
                                <input  style="width:40%" type="password" id="<?php echo ATKP_SHOP_POSTTYPE.'_amz_access_secret_key' ?>" name="<?php echo ATKP_SHOP_POSTTYPE.'_amz_access_secret_key' ?>" value="<?php echo esc_attr($apisecretkey); ?>">
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo ATKP_SHOP_POSTTYPE.'_amz_access_website' ?>">
                                    <?php _e('Amazon Website', ATKP_PLUGIN_PREFIX) ?>:
                                </label> 
                            </th>
                            <td>
                                <select name="<?php echo ATKP_SHOP_POSTTYPE.'_amz_access_website' ?>">
                                <?php
                                //        public static $locations = array('de', 'com', 'co.uk', 'ca', 'fr', 'co.jp', 'it', 'cn', 'es', 'in', 'com.br');
                                
                                 $locations = array(
                                                    'de' => __('Amazon Germany', ATKP_PLUGIN_PREFIX),
                                                    'com' => __('Amazon United States', ATKP_PLUGIN_PREFIX),
                                                    'co.uk' => __('Amazon United Kingdom', ATKP_PLUGIN_PREFIX),
                                                    'ca' => __('Amazon Canada', ATKP_PLUGIN_PREFIX),
                                                    'fr' => __('Amazon France', ATKP_PLUGIN_PREFIX),
                                                    'co.jp' => __('Amazon Japan', ATKP_PLUGIN_PREFIX),
                                                    'it' => __('Amazon Italy', ATKP_PLUGIN_PREFIX),
                                                    'cn' => __('Amazon China', ATKP_PLUGIN_PREFIX),
                                                    'es' => __('Amazon Spain', ATKP_PLUGIN_PREFIX),
                                                    'in' => __('Amazon India', ATKP_PLUGIN_PREFIX),
                                                    'com.br' => __('Amazon Brazil', ATKP_PLUGIN_PREFIX),                                                    
                                                  );
                                
                                
                                foreach ($locations as $value => $name) {
                                    if ($value == ATKPTools::get_post_setting($post->ID, ATKP_SHOP_POSTTYPE.'_access_website')) 
                                        $sel = ' selected'; 
                                    else 
                                        $sel = '';
                                    
                                                                
                                    echo '<option value="' . $value . '"' . $sel . '>' . esc_attr($name) . '</option>';
                                } ?>
                                </select>
                            </td>
                        </tr>                        
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo ATKP_SHOP_POSTTYPE.'_amz_access_tracking_id' ?>">
                                    <?php _e('Amazon Tracking ID', ATKP_PLUGIN_PREFIX) ?>:
                                </label> 
                            </th>
                            <td>
                                <input type="text" id="<?php echo ATKP_SHOP_POSTTYPE.'_amz_access_tracking_id' ?>" name="<?php echo ATKP_SHOP_POSTTYPE.'_amz_access_tracking_id' ?>" value="<?php echo  esc_attr(ATKPTools::get_post_setting($post->ID, ATKP_SHOP_POSTTYPE.'_access_tracking_id')); ?>">
                            </td>
                        </tr> 
                        <tr valign="top">
                            <th scope="row">
                                
                            </th>
                            <td>
                                <input type="checkbox" id="<?php echo ATKP_SHOP_POSTTYPE.'_amz_enable_ssl' ?>" name="<?php echo ATKP_SHOP_POSTTYPE.'_amz_enable_ssl' ?>" value="1" <?php echo checked(1, ATKPTools::get_post_setting($post->ID, ATKP_SHOP_POSTTYPE.'_enable_ssl'), true); ?>>
                                 <label for="<?php echo ATKP_SHOP_POSTTYPE.'_amz_enable_ssl' ?>">
                                    <?php _e('Enable SSL for pictures', ATKP_PLUGIN_PREFIX) ?>
                                </label>
                            </td>
                        </tr>
                        
                        <tr valign="top">
                            <th scope="row">
                                
                            </th>
                            <td>
                                <input type="checkbox" id="<?php echo ATKP_SHOP_POSTTYPE.'_amz_load_customer_reviews' ?>" name="<?php echo ATKP_SHOP_POSTTYPE.'_amz_load_customer_reviews' ?>" value="1" <?php echo checked(1, ATKPTools::get_post_setting($post->ID, ATKP_SHOP_POSTTYPE.'_load_customer_reviews'), true); ?>>
                                <!-- ATKPTools::get_post_setting($post->ID, ATKP_SHOP_POSTTYPE.'_load_customer_reviews') -->
                                 <label for="<?php echo ATKP_SHOP_POSTTYPE.'_amz_load_customer_reviews' ?>">
                                    <?php _e('Load customer reviews', ATKP_PLUGIN_PREFIX) ?>
                                </label>
                            </td>
                        </tr>
                      
                        
        <?php
        
    }

    public function get_shops($post_id) {
        $shops = array();
        
        $store = ATKPTools::get_post_setting( $post_id, ATKP_SHOP_POSTTYPE.'_amz_store');
        
        $shops = array();
        
        $subshop = new subshop();
                
        $subshop->title = __('Amazon', ATKP_PLUGIN_PREFIX);
        
        $webseite = ATKPTools::get_post_setting($post_id, ATKP_SHOP_POSTTYPE.'_access_website');
        
        switch($webseite) {
            case 'de':        
                $subshop->logourl =  plugins_url('images/logo-normal-amazon-de.jpg', ATKP_PLUGIN_FILE);
                $subshop->smalllogourl = plugins_url('images/logo-small-amazon-de.jpg', ATKP_PLUGIN_FILE);     
                break;
            case 'com':        
                $subshop->logourl =  plugins_url('images/logo-normal-amazon-com.png', ATKP_PLUGIN_FILE);
                $subshop->smalllogourl = plugins_url('images/logo-small-amazon-com.png', ATKP_PLUGIN_FILE);     
                break;
        }
        
        $subshop->shopid = $post_id;
        $subshop->programid = '';
        
        $subshop->enabled = true;
        
        array_push($shops, $subshop);
        
        return $shops;
    }
    
    private $helper = null;
    private $enable_ssl = false;
    private $storetype = 1;
    private $country ='';
    private $load_customer_reviews = false;
    private $associateTag = '';
    private $accessKey ='';
    private $shopid ='';
    
    public function checklogon($shopid, $subshopid) {
        $this->shopid = $shopid;
        $this->accessKey = $access_key = ATKPTools::get_post_setting( $shopid, ATKP_SHOP_POSTTYPE.'_access_key');
        $access_secret_key =ATKPTools::get_post_setting( $shopid, ATKP_SHOP_POSTTYPE.'_access_secret_key');
        $access_message = ATKPTools::get_post_setting( $shopid, ATKP_SHOP_POSTTYPE.'_access_message');
        $this->country = $access_website =ATKPTools::get_post_setting( $shopid, ATKP_SHOP_POSTTYPE.'_access_website');
        $this->associateTag = $access_tracking_id =ATKPTools::get_post_setting( $shopid, ATKP_SHOP_POSTTYPE.'_access_tracking_id');
        $this->load_customer_reviews = ATKPTools::get_post_setting( $shopid, ATKP_SHOP_POSTTYPE.'_load_customer_reviews');
        $this->enable_ssl =ATKPTools::get_post_setting( $shopid, ATKP_SHOP_POSTTYPE.'_enable_ssl');
        $this->storetype = ATKPTools::get_post_setting( $shopid, ATKP_SHOP_POSTTYPE.'_amz_store');
        
        if($access_tracking_id == '')
            $access_tracking_id = 'empty';
        
        if (!class_exists('ApaiIO'))
            require_once ATKP_PLUGIN_DIR.'/lib/apai-io/bootstrap.php';                
                            
        $request = new \ApaiIO\Request\Rest\Request(array(\ApaiIO\Request\Rest\Request::FOLLOW_LOCATION => 0));

        
        $conf = new \ApaiIO\Configuration\GenericConfiguration();
        $conf
            ->setCountry($access_website)
            ->setAccessKey($access_key)
            ->setSecretKey($access_secret_key)
            ->setAssociateTag($access_tracking_id)
            ->setRequest($request);

        
        $this->helper = new \ApaiIO\ApaiIO($conf);
    }
    
    public function retrieve_browsenodes($keyword) {
        if($this->helper == null)
            throw new Exception('checklogon required');

        $nodes = array();      
        
        $search = new ApaiIO\Operations\Search();
        $search->setPage(1);
        $search->setCategory('All');
        //$search->setCondition('New');
        $search->setKeywords($keyword);
        $search->setResponseGroup(array('BrowseNodes'));
        
        switch($this->storetype) {
            default:
            case 1:
                //amazon
                //$search->setMerchantId('Amazon');
                break;
            case 2:
                //marketplace
                //TODO:
                break;
            case 3:
                //amazon marketplace
                
                break;
        }
        
        $pagination = 0;
        do {				
            $pagination++; //increment 

            $search->setPage($pagination);
     
            $response = $this->helper->runOperation($search);
            $response = $this->convert_response($response);
    
    
    
            $check = $this->checkResponse($response);
            if(!empty($check))
                throw new Exception($check);
    
            if (isset($response->Items->Item) ) {
                foreach ($response->Items->Item as $result) {
                    if(isset($result->BrowseNodes->BrowseNode)) {
                        
                        if(isset($result->BrowseNodes->BrowseNode->Name))
                         $nodes[$result->BrowseNodes->BrowseNode->BrowseNodeId] = $result->BrowseNodes->BrowseNode->Name;
                 
                        foreach ($result->BrowseNodes->BrowseNode as $browsenode) {
                            foreach($this->RecursiveBrowseNodes($browsenode) as $node => $value) 
                                $nodes[$node] = $value;
                        }
                 
                    }
                }
            }
        } while($pagination <= 2);
                
        $newNodes = array();
        
        foreach($nodes as $node => $value) {
            if(!array_key_exists($node, $newNodes))
                $newNodes[$node] = $value;   
        }
      
        return $newNodes;
    }
    
    private function RecursiveBrowseNodes($parentBrowseNode) {
	    $nodes = array();
	    if(isset($parentBrowseNode->Ancestors))
			foreach ($parentBrowseNode->Ancestors as $browsenode) {
			    if(!isset($browsenode->Name) || !is_string ($browsenode->Name))
			        continue;
			    
				$nodes[$browsenode->BrowseNodeId] = $browsenode->Name;
							
				foreach($this->RecursiveBrowseNodes($browsenode) as $node => $value)
					$nodes[$node] = $value;
								
				//array_push($nodes, $this->RecursiveBrowseNodes($browsenode));
			}
        
        return $nodes;
	}
    
    public function quick_search($keyword, $searchType, $pagination = 1) {
        if($this->helper == null)
            throw new Exception('checklogon required');

        $maxCount= 10;

        $products = array();        
        
        if($searchType == 'asin' || $searchType =='ean') {
                        
            $search = new ApaiIO\Operations\Lookup();
       
            $search->setCategory('All');
           
            if($searchType == 'ean')
                $search->setIdType('EAN');
        
            $search->setItemId(explode(',', $keyword));
            
            
        } else {
        
            $search = new ApaiIO\Operations\Search();
    
            $search->setCategory('All');
            //$search->setCondition('New');
            $search->setKeywords($keyword);
            
        }
        $search->setResponseGroup(array('ItemAttributes','Images','OfferFull'));
        
        switch($this->storetype) {
            default:
            case 1:
                //amazon
                //$search->setMerchantId('Amazon');
                break;
            case 2:
                //marketplace
                //TODO:
                break;
            case 3:
                //amazon marketplace
                
                break;
        }
        
        $pagination--;
        do {				
            $pagination++; //increment 

            $search->setPage($pagination);
     
            $response = $this->helper->runOperation($search);
            $response = $this->convert_response($response);
    
            $check = $this->checkResponse($response);
            if(!empty($check))
                throw new Exception($check);
   
            if (isset($response->Items->Item) ) {
                if(!is_array($response->Items->Item)) {
    
                    $response->Items->Item = array($response->Items->Item);
                }
                
                
                foreach ($response->Items->Item as $result) {
                    if (isset($result->ASIN)) {
                        $product = array();
                        
                        if(isset($result->SmallImage->URL))
                            $product['imageurl'] = $this->checkimageurl($result->SmallImage->URL);
                        $product['asin'] = $result->ASIN;
                        if(isset($result->ItemAttributes->EAN))
                            $product['ean'] = $result->ItemAttributes->EAN;
                        $product['producturl'] = $result->DetailPageURL;
                        $product['title']  = $result->ItemAttributes->Title;
                        if(isset( $result->Offers->Offer->OfferListing->Availability))
                          $product['availability'] = $result->Offers->Offer->OfferListing->Availability;
                        
						//TODO: lowest new price liefert völlig sinnfreie Preise aus dem marketplace zurück
                        if (isset($result->OfferSummary->LowestNewPrice))
                    		$product['price'] = $result->OfferSummary->LowestNewPrice->FormattedPrice;
                      
                      
                        //$product['availability'] = $result->Offers->Offer->OfferListing->Availability;
                                                    
                        array_push($products, $product);	
                    }
                     if(count($products) >= $maxCount)
                        break;
                }
            }
            if(count($products) >= $maxCount)
                    break;
                    
        } while(($pagination * 10) < $maxCount);
        
        return $products;
    }
    
    private function checkimageurl($url) {
        
        if($this->enable_ssl)
           $url = str_replace('http://ecx.images-amazon.com', 'https://images-na.ssl-images-amazon.com', $url);
        
     return $url;   
    }
	
    
     private function checkResponse($response) {
        $requestHelp;
        if(isset($response->BrowseNodes->Request)) {
            $requestHelp = $response->BrowseNodes->Request;
        } else if(isset($response->Items->Request)) {
            $requestHelp = $response->Items->Request;
        }
        
        //echo('$response: ' .serialize($response));
        
        $message ='';
        
        if(isset($requestHelp->IsValid) && $requestHelp->IsValid != 'True') { 
                
            $message .=  'Invalid Request. IsValid: '.$requestHelp->IsValid  ; 
            
            //echo('xx '.serialize($requestHelp->Errors->Error));
    
        }
        
        if(isset($requestHelp->Errors->Error)) { 
            
            if($requestHelp->Errors->Error->Code != '') {
                $error = $requestHelp->Errors->Error;
                if($message != '') $message .= '<br />';
                    $message .= 'ErrorCode: '.$error->Code;   
                    if($message != '') $message .= '<br />';
                    $message .= 'Message: '.$error->Message;    
            } else {        
                foreach($requestHelp->Errors->Error as $error) {
                    if($message != '') $message .= '<br />';
                    $message .= 'ErrorCode: '.$error->Code;   
                    if($message != '') $message .= '<br />';
                    $message .= 'Message: '.$error->Message;    
                }
            }
        }     
        
        return $message;;
    }
     
    public function retrieve_departments() {
        if($this->helper == null)
            throw new Exception('checklogon required');
            
        $departments = array();
	    
	    switch($this->country) {
	        case 'de':
	            require_once ATKP_PLUGIN_DIR.'/includes/helper/atkp_amazon_helper_de.php';	
	            
	            $helper = new atkp_amazon_helper_de();
	            $departments = $helper->get_departments();
	            break;
	       default:     
	       case 'en':
	            require_once ATKP_PLUGIN_DIR.'/includes/helper/atkp_amazon_helper_en.php';	
	            
	            $helper = new atkp_amazon_helper_en();
	            $departments = $helper->get_departments();
	           break;
	        
	    }
	    
	    return $departments;
    }
    
    public function retrieve_filters() {
        $durations = array(
            '' => __('Not selected', ATKP_PLUGIN_PREFIX),
            'Keywords' => __('Keywords', ATKP_PLUGIN_PREFIX),
            'SearchIndex' => __('SearchIndex', ATKP_PLUGIN_PREFIX),
            'Sort' => __('Sort', ATKP_PLUGIN_PREFIX),
            'BrowseNode' => __('BrowseNode', ATKP_PLUGIN_PREFIX),
            
            'Availability' => __('Availability', ATKP_PLUGIN_PREFIX),
                                                                
            'Power' => __('Power', ATKP_PLUGIN_PREFIX),
            'RelationshipType' => __('RelationshipType', ATKP_PLUGIN_PREFIX),
            
            'Actor' => __('Actor', ATKP_PLUGIN_PREFIX),
            'Artist' => __('Artist', ATKP_PLUGIN_PREFIX),
            'Author' => __('Author', ATKP_PLUGIN_PREFIX),
            'Brand' => __('Brand', ATKP_PLUGIN_PREFIX),
           
            'Composer' => __('Composer', ATKP_PLUGIN_PREFIX),
            'Conductor' => __('Conductor', ATKP_PLUGIN_PREFIX),
            'Director' => __('Director', ATKP_PLUGIN_PREFIX),
            'Conductor' => __('Conductor', ATKP_PLUGIN_PREFIX),
            
            'Manufacturer' => __('Manufacturer', ATKP_PLUGIN_PREFIX),
            'MaximumPrice' => __('MaximumPrice', ATKP_PLUGIN_PREFIX),
            'MinimumPrice' => __('MinimumPrice', ATKP_PLUGIN_PREFIX),
            'MinPercentageOff' => __('MinPercentageOff', ATKP_PLUGIN_PREFIX),
            
            'Orchestra' => __('Orchestra', ATKP_PLUGIN_PREFIX),
            'Publisher' => __('Publisher', ATKP_PLUGIN_PREFIX),
            
            'Condition' => __('Condition', ATKP_PLUGIN_PREFIX),
            'MerchantId' => __('MerchantId', ATKP_PLUGIN_PREFIX),
          );
          
          return $durations;
    }
    
    public function retrieve_product($asin, $id_type = 'ASIN') {
        if($this->helper == null)
            throw new Exception('checklogon required');        
        
        $products = $this->retrieve_products_internal(array($asin), $id_type);
        
        if(count($products) > 0)
            return $products[0];
        else
            return null;
    }
    
    public function retrieve_products($asins) {
        return $this->retrieve_products_internal($asins, 'ASIN');
    }
    
    public function retrieve_products_internal($asins, $id_type) {
         $products = array();        
        
        $search = new ApaiIO\Operations\Lookup();
        $search->setPage(1);
        $search->setCategory('All');
        $search->setResponseGroup(array('Large', 'Variations'));
        if($id_type == 'EAN')
            $search->setIdType('EAN');
        
        $search->setItemId($asins);
        
        $response = $this->helper->runOperation($search);
        $response = $this->convert_response($response);

        $check = $this->checkResponse($response);
        if(!empty($check))
            throw new Exception($check);

        if (isset($response->Items->Item) ) {
            if(!is_array($response->Items->Item)) {
                $result = $response->Items->Item;
                
                if (isset($result->ASIN)) {
                        $product = array();
                    
                        $product = $this->fill_product($result);
                 
                        array_push($products, $product);	
                    }
                
            } else {
                foreach ($response->Items->Item as $result) {
                    if (isset($result->ASIN)) {
                        $product = array();
                        
                        $product = $this->fill_product($result);
                 
                        array_push($products, $product);	
                    }
                }
            }
        }
        return $products;
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
	
	private function fill_product($result) {
	    if(ATKPLog::$logenabled) {
            ATKPLog::LogDebug('*** fill_product amazon ***');
            ATKPLog::LogDebug(serialize($result));
            ATKPLog::LogDebug('*** fill_product amazon ***');
        }
	        
        $myproduct = new atkp_product();                
	    $myproduct->updatedon = current_time('timestamp' ); 
	    $myproduct->shopid = $this->shopid;
	    
	    //store the ASIN code in case we need it
        $myproduct->asin = $result->ASIN;
        $myproduct->producturl = urldecode($result->DetailPageURL);
        $myproduct->addtocarturl = 'http://www.amazon.'.$this->country.'/gp/aws/cart/add.html?AWSAccessKeyId='.$this->accessKey.'&AssociateTag='.$this->associateTag.'&ASIN.1='.$myproduct->asin.'&Quantity.1=1';
        
        //TODO: ssl untersützung der bilder implementieren: https://images-na.ssl-images-amazon.com statt http://ecx.images-amazon.com
        if(isset($result->SmallImage->URL))         
            $myproduct->smallimageurl= $this->checkimageurl($result->SmallImage->URL);
        if(isset($result->MediumImage->URL))    
            $myproduct->mediumimageurl= $this->checkimageurl($result->MediumImage->URL);
        if(isset($result->LargeImage->URL))    
            $myproduct->largeimageurl= $this->checkimageurl($result->LargeImage->URL);
        
        if($this->load_customer_reviews) {
        if ($result->CustomerReviews->HasReviews) {
            $averageRating = 0;
            $totalReviews = 0;
            $this->getCustomerReviewData (str_replace('http://', 'https://', $result->CustomerReviews->IFrameURL), $averageRating,  $totalReviews);
            
            //if($myproduct->reviewcount == 0) {
            //    $this->getCustomerReviewData ($result->CustomerReviews->IFrameURL, $averageRating,  $totalReviews, 'alt');            
            //}            
            
            $myproduct->rating = $averageRating;
            $myproduct->reviewcount = $totalReviews;
             
        } else {
            $myproduct->rating = 0;
            $myproduct->reviewcount = 0;
        }
        }
                
        $myproduct->customerreviewurl = 'http://www.amazon.'.$this->country.'/product-reviews/'.$myproduct->asin.'/?tag='.$this->associateTag;
                        
        //$myproduct->reviewsurl = $result->CustomerReviews->IFrameURL;
        
        $description='';
        if (isset($result->EditorialReviews->EditorialReview)) {
                if (isset($result->EditorialReviews->EditorialReview->Content)) {
                    $description = $result->EditorialReviews->EditorialReview->Content;
                } elseif (sizeof($result->EditorialReviews->EditorialReview)>0) {
                    foreach ($result->EditorialReviews->EditorialReview as $tmp) {
                        if (isset($description) && strlen($description)<strlen($tmp->Content)) $description = $tmp->Content;
                    }
                }
            }
            
        $description = (isset($description)) ? $description : '';
        $features = '';
        if (isset($result->ItemAttributes->Feature)) {
                    if (is_array($result->ItemAttributes->Feature) && sizeof($result->ItemAttributes->Feature > 0)) {
                        foreach ($result->ItemAttributes->Feature as $feature) {
                            $features.= '<li>' . $feature . '</li>';                            
                        }
                    }
                }
        
        $myproduct->features=$features;        
        $myproduct->description = $description;                
        $myproduct->title = $result->ItemAttributes->Title;
        
        //preise laden
        if(isset($result->ItemAttributes->ListPrice->FormattedPrice))
            $myproduct->listprice = $result->ItemAttributes->ListPrice->FormattedPrice;
            
        if($myproduct->listprice == '' && isset($result->Offers->Offer->OfferListing->Price->FormattedPrice)) 
            $myproduct->listprice = $result->Offers->Offer->OfferListing->Price->FormattedPrice;
            
        if(isset( $result->Offers->Offer->OfferListing->Price->FormattedPrice))
            $myproduct->saleprice = $result->Offers->Offer->OfferListing->Price->FormattedPrice;
        
        $infosset = false;
        if(isset($result->Offers->Offer->OfferListing->SalePrice->FormattedPrice))
            $myproduct->saleprice = $result->Offers->Offer->OfferListing->SalePrice->FormattedPrice;
            
        if(isset($result->Variations->Item ))
            foreach($result->Variations->Item as $variationItem) {
                if(isset($variationItem->Offers))                
                    foreach($variationItem->Offers as $offerVariation) {
                        
                        //if($this->storetype==1)
                        //{
                        //    if($offerVariation->Merchant->Name != 'Amazon')
                        //        continue;
                        //}
                        
                        $myproduct->isprime = $offerVariation->OfferListing->IsEligibleForPrime;
                        $myproduct->availability = $offerVariation->OfferListing->Availability;
                        
                        if(isset($offerVariation->OfferListing->SalePrice->FormattedPrice))
                            $myproduct->saleprice = $offerVariation->OfferListing->SalePrice->FormattedPrice;
                        else
                            $myproduct->saleprice = $offerVariation->OfferListing->Price->FormattedPrice;
      
                        $infosset = true;
                        break;
                    }
                
                
            }            
            
        switch($this->storetype) {
            default:
            case 1:
                //amazon
                
                break;
            case 2:
                //marketplace
                //TODO:
                break;
            case 3:
                //amazon marketplace
                //echo serialize($result);exit;
                break;
        }
        
        if(!$infosset) {
            if(isset($result->Offers->Offer->OfferListing->AmountSaved->FormattedPrice))
                $myproduct->amountsaved = $result->Offers->Offer->OfferListing->AmountSaved->FormattedPrice;
            if(isset($result->Offers->Offer->OfferListing->PercentageSaved))
                $myproduct->percentagesaved = $result->Offers->Offer->OfferListing->PercentageSaved;
            
            if(isset($result->Offers->Offer->OfferListing->Availability))
                $myproduct->availability = $result->Offers->Offer->OfferListing->Availability;
            if(isset($result->Offers->Offer->OfferListing->IsEligibleForPrime))
                $myproduct->isprime = $result->Offers->Offer->OfferListing->IsEligibleForPrime;        
        }
		
		$offers = null;
		if ( isset($result->Offers->Offer) ) {
			$offers = $result->Offers->Offer;
		}

		if ( isset($result->Variations->Item->Offers->Offer) ) {
			$offers = $result->Variations->Item->Offers->Offer;
		}

        if(isset($result->Offers->Offer->OfferListing->SalePrice->FormattedPrice) && $myproduct->saleprice =='') {

		    $myproduct->saleprice = $result->Offers->Offer->OfferListing->SalePrice->FormattedPrice;
		    $myproduct->listprice ='';
            $myproduct->percentagesaved = '';
		}

				
		if(isset($result->Offers->Offer->OfferListing->Price->FormattedPrice) && $myproduct->saleprice =='') {

		    $myproduct->saleprice = $result->Offers->Offer->OfferListing->Price->FormattedPrice;
		    $myproduct->listprice ='';
            $myproduct->percentagesaved = '';
		}
		
		
        //TODO: lowestnew liefert völlig sinnlose preise welche keinerlei gütligtkeit haben
        if(isset($result->OfferSummary->LowestNewPrice) && $myproduct->saleprice =='') {
            
             $myproduct->saleprice = $result->OfferSummary->LowestNewPrice->FormattedPrice;
             $myproduct->listprice ='';
             $myproduct->percentagesaved = '';
        }
        if(isset($result->OfferSummary->LowestNewPrice->FormattedPrice))
            $myproduct->lowestnewprice = $result->OfferSummary->LowestNewPrice->FormattedPrice;
        if(isset($result->OfferSummary->TotalNew))
            $myproduct->totalnew = $result->OfferSummary->TotalNew;       
        
        if($myproduct->listprice  == '') 
                $myproduct->amountsaved ='';
        
        $myproduct->salepricefloat = $this->price_to_float($myproduct->saleprice);
        $myproduct->amountsavedfloat = $this->price_to_float($myproduct->amountsaved);
        $myproduct->listpricefloat = $this->price_to_float($myproduct->listprice);
        $myproduct->shippingfloat = (float)0;
        
        if (isset($result->ItemAttributes->Manufacturer))
		    $myproduct->manufacturer = $result->ItemAttributes->Manufacturer;	
		if (isset($result->ItemAttributes->Brand))
		    $myproduct->brand = $result->ItemAttributes->Brand;  
		    
		if (isset($result->ItemAttributes->ISBN))
		    $myproduct->isbn = $result->ItemAttributes->ISBN;  
		
		if (isset($result->ItemAttributes->ProductGroup)) 
		    $myproduct->productgroup = $result->ItemAttributes->ProductGroup;
		
		
		if (isset($result->ItemAttributes->Author))
		if(is_array($result->ItemAttributes->Author)) {
		    $myproduct->author = implode(', ', $result->ItemAttributes->Author);
		} else 
		    $myproduct->author = $result->ItemAttributes->Author;
		if (isset($result->ItemAttributes->NumberOfPages))
		    $myproduct->numberofpages = $result->ItemAttributes->NumberOfPages;	
	    
	    $myproduct->ean ='';
		if (isset($result->ItemAttributes->EAN))	
		    $myproduct->ean = $result->ItemAttributes->EAN;   
		
		if (isset($result->ItemAttributes->EANList) && isset($result->ItemAttributes->EANList->EANListElement)) {
		   $newean = '';
		   if (is_array($result->ItemAttributes->EANList->EANListElement) || is_object($result->ItemAttributes->EANList->EANListElement))
    		   foreach($result->ItemAttributes->EANList->EANListElement as $eanitem)
                {
                    
                    if( $newean == '')
                        $newean = $eanitem;
                    else
                        $newean .= ','.$eanitem;
                }
		   
		   if($newean != '')
		    $myproduct->ean = $newean;		  
    	}
		    
		$myproduct->imagesurl ='';
		$myproduct->thumbimagesurl ='';
		
		$images = array();
				
		//if(is_array($result->ImageSets->ImageSet))
		
		//echo serialize($result->ImageSets->ImageSet);
		if(isset($result->ImageSets->ImageSet))
    		foreach($result->ImageSets->ImageSet as  $i => $image) {
    		    if(!isset($image->SmallImage->URL) && !isset($image->MediumImage->URL) && !isset($image->LargeImage->URL))
    		        continue;
		        $udf = new atkp_product_image();
               $udf->id =uniqid();
               if(isset($image->SmallImage->URL))
                $udf->smallimageurl = $this->checkimageurl($image->SmallImage->URL);
               if(isset($image->MediumImage->URL))
                $udf->mediumimageurl = $this->checkimageurl($image->MediumImage->URL);
               if(isset($image->LargeImage->URL))
                $udf->largeimageurl = $this->checkimageurl($image->LargeImage->URL);
                                  
               array_push($images, $udf);		    
    		}	
		
		
		
		$myproduct->images = $images;
		//echo serialize($result);exit;
		    //echo serialize($myproduct);exit;
		return $myproduct;
	    
	}
	
	
	private function getCustomerReviewData ($url, &$averageRating,  &$totalReviews,  $findmode = 'html')
    {
        try {
            if (!class_exists('simple_html_dom')) {
                require_once ATKP_PLUGIN_DIR . '/lib/simple_html_dom.php';
            }
            
            $totalReviews = 0;
            $averageRating = 0;
            
            $page = $this->getUrlContent($url, NULL);

            $html = new simple_html_dom();

            $html->load($page);

            if (!$html)
                return null;

            // finding rating star
            $rating_span = $html->find('span.asinReviewsSummary', 0);

            if (!method_exists($rating_span, 'find'))
                return;

            $rating_anchor_alt = $rating_span->find('img', 0)->getAttribute('alt');

            $rating_array = array();
            if (preg_match("|([0-9.]+) (.*) ([0-9.]+) (.*)|i", $rating_anchor_alt, $rating_array)) {
                $averageRating = (float) $rating_array[1];
            }
            if ($averageRating < 0 || $averageRating > 5)
                $averageRating = 0;

            // finding total reviews
            $review_text = $html->find('div.crIFrameHeaderHistogram', 0)->find('div.tiny', 0)->find('b', 0)->plaintext;
            $review = array();

            $review_text = str_replace('.', '', $review_text);

            if (preg_match("|([0-9]+) (.*)|i", $review_text, $review))
                $totalReviews = (float) $review[1];

            if ($totalReviews < 0)
                $totalReviews = 0;



            return;
            
            
            
            
            
            $frameContent = $this->getUrlContent($url, $findmode);
            
            
            
            $patternTag = '~<img[^>]+>~is';
            $patternSrc = '/(src)="([^"]*)"/i';
            $patternAlt = '/(alt)="([^"]*)"/i';
            $patternCnt = '~<a\b[^>]*>(.*?)</a>~is';
            
            $averageRating = 0;
            $totalReviews = 0;
            
            if (preg_match($patternTag, $frameContent, $matchTag) == 1) {
                $imgTag = $matchTag[0];
                               
                if (preg_match($patternSrc, $imgTag, $matchSrc) == 1) {
                    $this->imgSrc = $matchSrc[2];                
                }
                if (preg_match($patternAlt, $imgTag, $matchAlt) == 1) {
                    $alt = explode(' ', $matchAlt[2]);
                    $averageRating = $alt[0];                
                }
            }      
            
            if (preg_match_all($patternCnt, $frameContent, $matchCnt)) {
                $count = explode(' ', $matchCnt[1][1]);
                $count = str_replace(',', '', $count);
                $count = str_replace('.', '', $count);
                $totalReviews = intval($count[0]);
            }
        } catch(Exception $e) {
	        //echo 'Error: ' .$e->getMessage();
	    }
    }
    
    private function getUrlContent($url,$file = NULL, $referer = '', $compressed = false) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_VERBOSE, 0);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:24.0) Gecko/20100101 Firefox/24.0');
        curl_setopt($curl, CURLOPT_AUTOREFERER,$referer);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if ($compressed) curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
        if ($file) {
            $f = fopen($file, 'w+');
            curl_setopt($curl, CURLOPT_FILE , $f);
        }

        $page = curl_exec($curl);
        curl_close($curl);
        if ($file && $f) fclose($f);

        return $page;
    }
	

    
    public function get_supportedlistsources() {
        return '10,11,20,30,40';
    }
    
    public function retrieve_list($requestType, $rootCategory, $keyword, $asin, $maxCount, $sortorder, $filters) {
        if($this->helper == null)
            throw new Exception('checklogon required'); 
        
		if(!isset($maxCount) || $maxCount == 0 || $maxCount == '')
            $maxCount = 10;
		
        $mylist = new atkp_list_resp();                
    	$mylist->updatedon = current_time('timestamp' ); 
    	$mylist->asins = array();
    	$mylist->products = null;
        
        switch($requestType) {
            case 'Similarity':     
                $search = new ApaiIO\Operations\SimilarityLookup();
                $search->setPage(1);
                if($rootCategory == '')
                    $search->setCategory('All');
                 else   
                    $search->setCategory($rootCategory);
                
                $search->setItemId($asin);
                
                $response = $this->helper->runOperation($search);
                $response = $this->convert_response($response);
                
                $check = $this->checkResponse($response);
    		    if(!empty($check))
    		        throw new Exception($check);
    
                if (isset($response->Items->Item) ) {
                    
                    if(isset($response->Items->MoreSearchResultsUrl))
                        $mylist->listurl = $response->Items->MoreSearchResultsUrl;
                    
                    //loop through each item
                    foreach ($response->Items->Item as $result) {
    
                        //check that there is a ASIN code - for some reason, some items are not
                        //correctly listed. Im sure there is a reason for it, need to check.
                        if (isset($result->ASIN)) {
                            array_push($mylist->asins, $result->ASIN);	
                        }
                    }
                }
                break;
            case 'TopSellers':
                $browseNodeLookup = new ApaiIO\Operations\BrowseNodeLookup();
                $browseNodeLookup->setNodeId(intval($rootCategory));
                $browseNodeLookup->setResponseGroup(array('TopSellers'));
                
                $response = $this->helper->runOperation($browseNodeLookup);
                $response = $this->convert_response($response);
                                
                $check = $this->checkResponse($response);
    		    if(!empty($check))
    		        throw new Exception($check);
    			
    		    if (isset($response->BrowseNodes) && isset($response->BrowseNodes->BrowseNode) ) {
    		        
    		        $mylist->browsenodename = $response->BrowseNodes->BrowseNode->Name;
    		    	$response = $response->BrowseNodes->BrowseNode->TopSellers->TopSeller;
    		    		
    		    	if(is_array($response))
    		    	foreach ($response as $item) {
    		    		array_push($mylist->asins, $item->ASIN);	
    		    	}				
    		    }
                break;
            case 'NewReleases':         
                $browseNodeLookup = new ApaiIO\Operations\BrowseNodeLookup();
                $browseNodeLookup->setNodeId(intval($rootCategory));
                $browseNodeLookup->setResponseGroup(array('NewReleases'));
                
                $response = $this->helper->runOperation($browseNodeLookup);
                $response = $this->convert_response($response);
                
    		    $check = $this->checkResponse($response);
    		    if(!empty($check))
    		        throw new Exception($check);
    		    
    		    if (isset($response->BrowseNodes) && isset($response->BrowseNodes->BrowseNode) ) {
    		        
    		        $mylist->browsenodename = $response->BrowseNodes->BrowseNode->Name;
    		        
    		        if(isset($response->BrowseNodes->BrowseNode->NewReleases->NewRelease)) {
        		        $response = $response->BrowseNodes->BrowseNode->NewReleases->NewRelease;
        				
        				if(is_array($response))				
            			    foreach ($response as $item) {
            			    	array_push($mylist->asins, $item->ASIN);	
            			    }				
    		        }
    		    }
                break;
            case 'ExtendedSearch':
            case 'Search':
                $search = new ApaiIO\Operations\Search();
                if($rootCategory == '')
                    $search->setCategory('All');
                 else   
                    $search->setCategory($rootCategory);
                
                $search->setResponseGroup(array('Large', 'Variations'));
                
                switch($this->storetype) {
                    default:
                    case 1:
                        //amazon
                        //$search->setMerchantId('Amazon');
                        break;
                    case 2:
                        //marketplace
                        //TODO:
                        break;
                    case 3:
                        //amazon marketplace
                        
                        break;
                }
                
            
    	        if($rootCategory != '' && $rootCategory != 'All' && $sortorder != '')
    	            $search->setSort($sortorder);
    	             	    
        	    if($filters != null)
        	        foreach ($filters as $field => $value) { 
        	            if($field == 'Keywords')
        	                $keyword = $value;
        	            else if($field == 'SearchIndex') {
        	                 $search->setCategory($value);        	            
        	            }else
        	                $search->setOperationParameter($field, $value);
        	        }
    	            	         
    	         $search->setKeywords($keyword);
    	         
    	        $products = array();
        
                $pagination = 0;
    	        do {				
    	            $pagination++; //increment 
    	
    	            $search->setPage($pagination);
               
                    $response = $this->helper->runOperation($search);
                    $response = $this->convert_response($response);
    	            
    	            $check = $this->checkResponse($response);
    		        if(!empty($check))
    		            throw new Exception($check);
    	     
                    //check that there are items in the response
                    if (isset($response->Items->Item) ) {
                        
                        $mylist->listurl = $response->Items->MoreSearchResultsUrl;
    
                        //loop through each item
                        foreach ($response->Items->Item as $result) {
    
                            //check that there is a ASIN code - for some reason, some items are not
                            //correctly listed. Im sure there is a reason for it, need to check.
                            if (isset($result->ASIN)) {
                                array_push($mylist->asins, $result->ASIN);	
                                
                                $myproduct = $this->fill_product($result);
                    
                                array_push($products, $myproduct);	
                            }
                
                            if(count($products) >= $maxCount)
                                break;
                        }
            
                        if(count($products) >= $maxCount)
                            break;
                    } 
    	        } while(($pagination * 10) < $maxCount);
    	        
    	        $mylist->products = $products;
            break;        
            default:
                throw new Exception('unknown requestType: '.$requestType);
            break;
        }
        
        if($mylist->products == null) {
            //wenn produkte nicht direkt geladen, dann nachladen
            $mylist->products = $this->retrieve_products($mylist->asins, 'ASIN');
                        
        }
        
        return $mylist;
    }
    
    public function check_offer($offer) {
             

        $products = null;
        $found =false;
        if($offer->type == 1)
            $product = $this->retrieve_product($offer->number, 'EAN');
        else
            $product = $this->retrieve_product($offer->number, 'ASIN');
        
        
        
        if($product != null) {
            
            $offer->shipping ='';
            $offer->availability =$product->availability;

            $offer->price = $product->saleprice;
            $offer->pricefloat = $product->salepricefloat;
            $offer->shippingfloat = (float)0;
            
            
            $offer->link =$product->producturl;
            $offer->title =$product->title;
            $found = true;            
        }
        
        if(!$found) 
            $offer->message='product not found';
               
               return $offer;
    }
} 


?>