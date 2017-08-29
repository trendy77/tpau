<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class atkp_template_helper 
{    
    public function formatFloat($number, $fallback, $shopid) {
        $currencysign= 5;
        
        if($shopid != '') {
            $shopids = explode('_', $shopid);
            $currencysign = ATKPTools::get_post_setting($shopids[0], ATKP_SHOP_POSTTYPE.'_currencysign');
        }
        
        $currencysymbol = 'EUR ';
        $currencysymbol2 = '';
        
        switch($currencysign) {            
            case 1:
                $currencysymbol = '&euro; ';
                break;
            case 2:
                $currencysymbol = 'EUR ';
                break;
            case 3:
                $currencysymbol = '&#36 ';
                break;
            case 4:
                $currencysymbol = 'USD ';
                break;
            default:
            case 5:                
                return $fallback;
                break;
            case 6:
                $currencysymbol = ATKPTools::get_post_setting($shopids[0], ATKP_SHOP_POSTTYPE.'_currencysign_customprefix');
                $currencysymbol2 = ATKPTools::get_post_setting($shopids[0], ATKP_SHOP_POSTTYPE.'_currencysign_customsuffix');
                break;
        }
            
        if($number == (float)0 && $fallback != '') 
            $number = $this->price_to_float($fallback);
        
        return $currencysymbol. ''.number_format_i18n  ($number, 2 ) . ''.$currencysymbol2;
    }
    
    function startsWith($haystack, $needle) {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
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
	
	private static function create_external_link($url, $title, $productid, $listid, $templateid, $shopid, $linktype) {
	    if(ATKPSettings::$open_window)
 		    $target='target="_blank"';
 		else
 		    $target='';
 		    
 		
	    
	    
	    $tracking = '';
	    
	    switch(ATKPSettings::$linktracking) {
	        case 0:
	            break;
	        case 1:
	       case 2:
	            //universal tracking
	            
	            $linktypetext = '';
 		
         		switch($linktype) {
         		    default:
        	        case 1:
        	            $linktypetext =__('Link', ATKP_PLUGIN_PREFIX);
        	            break;
        	        case 2:
        	            $linktypetext =__('Offer', ATKP_PLUGIN_PREFIX);
        	            break;
        	       case 3:
        	            $linktypetext =__('Cart', ATKP_PLUGIN_PREFIX);
        	            break;
        	       case 4:
        	            $linktypetext =__('Customer review', ATKP_PLUGIN_PREFIX);
        	            break;
        	       case 5:
        	            $linktypetext =__('Image', ATKP_PLUGIN_PREFIX);
        	            break;
        	    }
        	   
        	   $shoptext ='';
        	   
        	   if(is_numeric($shopid)) {
        	       $shoptext .= get_the_title($shopid);
        	   } else {
        	     $shoptext =    $shopid;
        	   }
        	   
        	     if(is_numeric($templateid) && ATKP_PLUGIN_VERSION >= 30) {            
                     $linktypetext .= ' ('. get_the_title($templateid).', '.$shoptext.')';
        	     } else if($templateid != '') {
        	      $templatetext = $templateid;
        	      
        	      switch($templateid) {
        	        case 'bestseller':
        	            $templatetext = __('bestseller', ATKP_PLUGIN_PREFIX);
        	            break;
        	        case 'wide':
        	            $templatetext = __('wide', ATKP_PLUGIN_PREFIX);
        	            break;
        	        case 'secondwide':
        	            $templatetext = __('secondwide', ATKP_PLUGIN_PREFIX);
        	            break;
        	        case 'box':
        	            $templatetext = __('box', ATKP_PLUGIN_PREFIX);
        	            break;
        	        case 'detailoffers':
        	            $templatetext = __('all offers', ATKP_PLUGIN_PREFIX);
        	            break;
        	      }
        	      
        	         $linktypetext .= ' ('. $templateid .', '.$shoptext.')';
        	     } else 
        	        $linktypetext = __('Textlink', ATKP_PLUGIN_PREFIX). ' ('.$shoptext.')';
        
        	    $listcaption = '';
        	  
        	    if(is_numeric($listid)) {            
                     $listcaption = get_the_title($listid);
        	     }
        	     
        	     if($listid != '' && $listcaption =='')
        	         $listcaption = $listid;  
        	    
        	    $productcaption ='';
        	    
        	    if(is_numeric($productid)) {            
                     $productcaption = ATKPTools::get_post_setting( $productid, ATKP_PRODUCT_POSTTYPE.'_title');
        	     } else if($productid !='')
        	         $productcaption = $productid;
        	       else
        	         $productcaption = '-';
        	    
        	    $finalcaption = $productcaption;
        	    
        	    if($listcaption != '')
        	        $finalcaption .= ' ('.$listcaption.')';
	            	            
	            	            
	            if(ATKPSettings::$linktracking == 1)      
	                $tracking='onclick="ga(\'send\', \'event\', \''.$linktypetext.'\', \'click\', \''.$finalcaption.'\');"';
	            else if(ATKPSettings::$linktracking == 2)      
	                $tracking='onclick="_gaq.push([\'_trackEvent\', \''.$linktypetext.'\', \'click\', \''.$finalcaption.'\']);"';
	            break;
	    }
	    
	    $link = 'href="'.$url.'" rel="nofollow" '.$target.' '.$tracking.' title="'.$title.'"';
	    
	    return $link;
	}
    
    public function createImagePlaceholderArray($myproduct,  $image, $itemIdx,$listid='',$templateid='') {
        $placeholders = array();
        
        $placeholders['title'] = $myproduct->title;
        $placeholders['listid'] = $listid;
        $placeholders['templateid'] = $templateid;
        $placeholders['shopid'] = $myproduct->shopid;
        $placeholders['productid'] = $myproduct->productid;
        
        $placeholders['smallimage'] ='<img src="'. $image->smallimageurl.'" alt="'.$myproduct->title.'" />';
        $placeholders['mediumimage'] ='<img src="'. $image->mediumimageurl.'" alt="'.$myproduct->title.'" />';
        $placeholders['image'] ='<img src="'. $image->largeimageurl.'" alt="'.$myproduct->title.'" />';
        
        $placeholders['smallimageurl'] = $image->smallimageurl;
        $placeholders['mediumimageurl'] = $image->mediumimageurl;
        $placeholders['imageurl'] = $image->largeimageurl;
            
        if(ATKPSettings::$access_mark_links == 1)
            $placeholders['mark'] =  '*';
        else 
            $placeholders['mark'] =  '';
            
        $target = '';
 		
 		if(ATKPSettings::$open_window)
 		    $target='target="_blank"';
 		else
 		    $target='';
 		
 		$placeholders['productlink'] = $this->create_external_link($myproduct->producturl, $myproduct->title, $myproduct->title, $listid, $templateid, $myproduct->shopid, 5);
            
        return $placeholders ;
    }
    
    public function createOfferPlaceholderArray($myproduct, $offer, $itemIdx,  $listid='',$templateid='') {
        $placeholders = array();
        
        $placeholders['listid'] = $listid;
        $placeholders['templateid'] = $templateid;
        $placeholders['shopid'] = $offer->shopid;
        $placeholders['productid'] = $myproduct->productid;
    
        if($offer->price == '')
            $placeholders['price'] =__('not available', ATKP_PLUGIN_PREFIX);
        else
            $placeholders['price']= sprintf(__('%s', ATKP_PLUGIN_PREFIX), $this->formatFloat($offer->pricefloat, $offer->price, $offer->shopid)); 
         
        if($offer->price == '')
            $placeholders['price_text'] =__('not available', ATKP_PLUGIN_PREFIX);
        else
            $placeholders['price_text']= sprintf(__('Price: %s', ATKP_PLUGIN_PREFIX), $this->formatFloat($offer->pricefloat, $offer->price, $offer->shopid)); 
            
        if(ATKPSettings::$access_mark_links == 1)
            $placeholders['mark'] =  '*';
        else 
            $placeholders['mark'] =  '';
            
        $target = '';
 		
 		if(ATKPSettings::$open_window)
 		    $target='target="_blank"';
 		else
 		    $target='';
 		

            
        $placeholders['linktext'] = __('Buy now', ATKP_PLUGIN_PREFIX);
        
        if($offer->shopid != '') {
            require_once ATKP_PLUGIN_DIR.'/includes/shopproviders/atkp_shop_provider_base.php';
            
            $shopids = explode('_', $offer->shopid);
            
            $webservice = ATKPTools::get_post_setting($shopids[0], ATKP_SHOP_POSTTYPE.'_access_webservice');
            
            $myprovider = atkp_shop_provider_base::retrieve_provider($webservice);
            
             if($myprovider == null)
                throw new Exception('provider not found: '.$webservice);
                
            $myprovider->load_basicsettings($shopids[0]);
                                        
            $subshops = $myprovider->get_shops($shopids[0]);
                                              
            foreach ($subshops  as $subshop ) 
                if ($offer->shopid == $subshop->shopid) {
                    $shop = $subshop;
                    break;   
                }
            
            if(isset($shop)) {
                $placeholders['linktext'] = isset($myprovider) && $myprovider->buyat != '' ? $myprovider->buyat : sprintf(__('Buy now at %s', ATKP_PLUGIN_PREFIX), $shop->title);
                
                $placeholders['shoptitle'] = $shop->title;
                $placeholders['shoplogo'] = '<img src="'. $shop->logourl.'" alt="'.$shop->title.'" />';
                $placeholders['smallshoplogo'] = '<img src="'. $shop->smalllogourl.'" alt="'.$shop->title.'" />';
                $placeholders['shoplogourl'] = $shop->logourl;
                $placeholders['smallshoplogourl'] =  $shop->smalllogourl;
            } else {
                 return null;
            }
        } else {
            $placeholders['linktext'] = __('Buy now', ATKP_PLUGIN_PREFIX);
            $placeholders['shoplogo'] = '';
            $placeholders['smallshoplogo'] = '';
            $placeholders['shoptitle'] = '';
            $placeholders['shoplogourl'] ='';
            $placeholders['smallshoplogourl'] ='';
        }
                   
        $placeholders['productlink'] = $placeholders['link'] =  $this->create_external_link($offer->link, sprintf(__('Buy now at %s', ATKP_PLUGIN_PREFIX), $shop->title), $myproduct->title, $listid, $templateid, $placeholders['shoptitle'], 2);
        
        $placeholders['producturl'] = $offer->link;
        
        $placeholders['availability'] = $offer->availability;
        $placeholders['shipping'] = $this->formatFloat($offer->shippingfloat, $offer->shipping, $offer->shopid);
        
        if($offer->shipping =='')
            $placeholders['shipping_text'] = __('Shipping: N/A', ATKP_PLUGIN_PREFIX);
        else
            $placeholders['shipping_text'] = sprintf(__('Shipping: %s', ATKP_PLUGIN_PREFIX), $this->formatFloat($offer->shippingfloat, $offer->shipping, $offer->shopid)); 
            
        if($offer->availability =='')
            $placeholders['availability_text'] = __('Availability: N/A', ATKP_PLUGIN_PREFIX);
        else
            $placeholders['availability_text'] = sprintf(__('Availability: %s', ATKP_PLUGIN_PREFIX), $offer->availability); 
 
        
        return $placeholders ;
    }
                    
    public function createPlaceholderArray($myproduct, $itemIdx, $cssContainerClass, $cssElementClass, $content, $addtocart, $listid='',$templateid='') {
        $placeholders = array();
        $shop = null;
        $myprovider = null;
        
        $fallbackimage = $myproduct->smallimageurl;
        if($fallbackimage == '')
            $fallbackimage = $myproduct->mediumimageurl;
        if($fallbackimage == '')
            $fallbackimage = $myproduct->largeimageurl;
        
        if( $myproduct->smallimageurl == '')
             $myproduct->smallimageurl = $fallbackimage;
        if( $myproduct->mediumimageurl == '')
             $myproduct->mediumimageurl = $fallbackimage;
        if( $myproduct->largeimageurl == '')
             $myproduct->largeimageurl = $fallbackimage;
       
        if($myproduct->shopid != '') {
            require_once ATKP_PLUGIN_DIR.'/includes/shopproviders/atkp_shop_provider_base.php';
            
            $shopids = explode('_', $myproduct->shopid);
            
            $webservice = ATKPTools::get_post_setting($shopids[0], ATKP_SHOP_POSTTYPE.'_access_webservice');
            
            $myprovider = atkp_shop_provider_base::retrieve_provider($webservice);
            
             if($myprovider == null)
                throw new Exception('provider not found: '.$webservice);
                
            $myprovider->load_basicsettings($shopids[0]);
                                        
            $subshops = $myprovider->get_shops($shopids[0]);
                                              
            foreach ($subshops  as $subshop ) 
                if ($myproduct->shopid == $subshop->shopid) {
                    $shop = $subshop;
                    break;   
                }
            if($myprovider->displayshoplogo) {
                $placeholders['shoplogo'] = '<img src="'. $shop->logourl.'" alt="'.$shop->title.'" />';
                $placeholders['smallshoplogo'] = '<img src="'. $shop->smalllogourl.'" alt="'.$shop->title.'" />';
            }
            else {
                $placeholders['shoplogo'] = '';
                $placeholders['smallshoplogo'] = '';
            }
            $placeholders['shoptitle'] = $shop->title;
            
            $placeholders['shoplogourl'] = $shop->logourl;
            $placeholders['smallshoplogourl'] =  $shop->smalllogourl;
        } else {
            $placeholders['shoplogo'] = '';
            $placeholders['smallshoplogo'] = '';
            $placeholders['shoptitle'] = '';
            $placeholders['shoplogourl'] ='';
            $placeholders['smallshoplogourl'] ='';
        }
        
         $placeholders['shopid'] = $myproduct->shopid;
         
         $placeholders['listid'] = $listid;
         $placeholders['templateid'] = $templateid;
                
        if(ATKPSettings::$access_mark_links == 1)
            $placeholders['mark'] =  '*';
        else 
            $placeholders['mark'] =  '';
        
        $placeholders['title'] = $myproduct->title;
        
        if( ATKPSettings::$short_title_length > 0) {
            $placeholders['short_title'] = (strlen($myproduct->title) > ATKPSettings::$short_title_length) ? substr($myproduct->title,0,ATKPSettings::$short_title_length) :  $myproduct->title;
        } else {
            $placeholders['short_title'] = $myproduct->title;
        }
        
        
        
        
        $placeholders['productid'] = $myproduct->productid;
        
        $placeholders['asin'] = $myproduct->asin;
        $placeholders['ean'] = $myproduct->ean;
        $placeholders['isbn']= $myproduct->isbn;
        $placeholders['brand']= $myproduct->brand;
        $placeholders['productgroup']= $myproduct->productgroup;
        $placeholders['availability'] = $myproduct->availability;
        $placeholders['shipping'] = $myproduct->shipping;
        
        if($myproduct->smallimageurl == '')
            $myproduct->smallimageurl = plugins_url('../../images/image-not-found.jpg', __FILE__ );
        if($myproduct->mediumimageurl == '')
            $myproduct->mediumimageurl = plugins_url('../../images/image-not-found.jpg', __FILE__ );
        if($myproduct->largeimageurl == '')
            $myproduct->largeimageurl = plugins_url('../../images/image-not-found.jpg', __FILE__ );
        
        $placeholders['smallimageurl'] = $myproduct->smallimageurl;
        $placeholders['mediumimageurl'] = $myproduct->mediumimageurl;
        $placeholders['largeimageurl'] = $myproduct->largeimageurl;
        
        $placeholders['smallimage'] = '<img src="'. $myproduct->smallimageurl.'" alt="'.$myproduct->title.'" />';
        $placeholders['mediumimage'] ='<img src="'.  $myproduct->mediumimageurl.'" alt="'.$myproduct->title.'" />';
        $placeholders['largeimage'] = '<img src="'. $myproduct->largeimageurl.'" alt="'.$myproduct->title.'" />';
        
       
        
        for ($i = 1; $i <= 5; $i++) {
            $placeholders['thumbimages_'.$i] ='';
			$placeholders['mediumimages_'.$i] = '';
            $placeholders['images_'.$i] = '';
        }
        
        $idx = 1;
        if(is_array($myproduct->images))
        foreach ($myproduct->images as $newimage ) {
            
            $placeholders['thumbimages_'.$idx] ='<img src="'. $newimage->smallimageurl.'" alt="'.$myproduct->title.'" />';
            $placeholders['mediumimages_'.$idx] ='<img src="'. $newimage->mediumimageurl.'" alt="'.$myproduct->title.'" />';
            $placeholders['images_'.$idx] ='<img src="'. $newimage->largeimageurl.'" alt="'.$myproduct->title.'" />';
            
            
           $idx += 1; 
        }
        
            
        if($myproduct->manufacturer != '')
            $placeholders['by_text'] = sprintf(__('by %s', ATKP_PLUGIN_PREFIX), $myproduct->manufacturer);
        else if ($myproduct->author != '')
            $placeholders['by_text'] =  sprintf(__('by %s', ATKP_PLUGIN_PREFIX),$myproduct->author);
        else
            $placeholders['by_text'] ='';
 		
 		$target = '';
 		
 		if(ATKPSettings::$open_window)
 		    $target='target="_blank"';
 		else
 		    $target='';
 		
 		$linkttitle = $myproduct->title;
 		
 		if($shop != null) {
 		 $linkttitle = sprintf(__('Buy now at %s', ATKP_PLUGIN_PREFIX), $shop->title);
 		}
 		
 		    if($myproduct->addtocarturl == '')
 		        $placeholders['cartlink'] ='';
 		    else
			    $placeholders['cartlink'] = $this->create_external_link($myproduct->addtocarturl, $linkttitle, $myproduct->title, $listid, $templateid, $placeholders['shoptitle'] , 3);
        
            $placeholders['productlink'] = $this->create_external_link($myproduct->producturl, $linkttitle, $myproduct->title, $listid, $templateid, $placeholders['shoptitle'] , 1);
            
            
            $placeholders['producturl'] = $myproduct->producturl;
            $placeholders['customerreviewsurl'] = $myproduct->customerreviewurl;
            
            $listurl = '';
            
            if($listid != '') {
             $listurl =  ATKPTools::get_post_setting( $listid, ATKP_LIST_POSTTYPE.'_listurl');   
            }
            
            if($listurl != '') {
                $placeholders['hidelistlink'] ='';
                $placeholders['listlink'] = 'href="'.$listurl.'" rel="nofollow" '.$target.' title="'. __('Show me more products', ATKP_PLUGIN_PREFIX).'"';            
                $placeholders['listurl'] = $listurl;        
                $placeholders['listlinktext']       = __('Show me more products', ATKP_PLUGIN_PREFIX); 
            } else {
                $placeholders['hidelistlink'] = 'style="display:none"';
                $placeholders['listurl']  ='';
                $placeholders['listlink'] ='';
                 $placeholders['listlinktext'] ='';
            }
            
            $addittocart = ATKPSettings::$add_to_cart;
            
            if(is_numeric($addtocart)) {
                $addittocart  = $addtocart;
            }
            
            if($addittocart && $placeholders['cartlink'] != '') {
                $placeholders['link'] = $placeholders['cartlink'];
                $placeholders['linktext'] =  isset($myprovider) && $myprovider->addtocart != '' ? $myprovider->addtocart : __('Add to Amazon Cart', ATKP_PLUGIN_PREFIX);
            } else {
                $placeholders['link'] = $placeholders['productlink'];
                
                if(!isset($shop))
                    $placeholders['linktext'] = __('Buy now', ATKP_PLUGIN_PREFIX);
                else {
                 
                    $placeholders['linktext'] = isset($myprovider) && $myprovider->buyat != '' ? $myprovider->buyat : __('Buy now at Amazon', ATKP_PLUGIN_PREFIX);
                }
            }
           
            if(($itemIdx > 3 && ATKPSettings::$bestsellerribbon == 1) || $itemIdx <= 0)
                $placeholders['bestseller_text'] = '';
            else
                $placeholders['bestseller_text'] = sprintf(__('#%s Best Seller', ATKP_PLUGIN_PREFIX), $itemIdx);
                
            if(($itemIdx > 3 && ATKPSettings::$bestsellerribbon == 1) || $itemIdx <= 0)
                $placeholders['bestseller_number'] = '';
            else
                $placeholders['bestseller_number'] = sprintf(__('#%s', ATKP_PLUGIN_PREFIX), $itemIdx);
            
             $placeholders['reviewsurl'] =$myproduct->reviewsurl;
            
            $placeholders['reviewcount2'] = '';
            
            if(($myproduct->rating !='' && $myproduct->reviewcount != '' && ATKPSettings::$showstarrating)) {
                if($myproduct->rating =='')
                    $myproduct->rating = 0;
                
                $class = 'atkp-star-' . number_format($this->roundRate($myproduct->rating), 1, ' atkp-star-0', '');
                    
                $placeholders['rating'] = sprintf(__('%s out of 5 stars', ATKP_PLUGIN_PREFIX), $myproduct->rating);
                $placeholders['star_rating'] = '<span class="atkp-star '.$class.'" title="'.$placeholders['rating'].'"></span>';
                $placeholders['reviewcount2'] = $placeholders['rating'];
            } else {
                $placeholders['rating'] = '';
                $placeholders['star_rating'] = '';
                
            }
            
            if($myproduct->reviewcount == '')
                $myproduct->reviewcount = 0;
            
            if( $myproduct->isownreview) {
                $reviewstext = __('Show review', ATKP_PLUGIN_PREFIX);
                
                if(ATKPSettings::$showrating)
                    $placeholders['reviewcount'] =  $reviewstext;
                
                if($myproduct->reviewsurl != '' && ATKPSettings::$showrating) {                   
                    
                    $placeholders['reviewslink'] = 'href="'.$myproduct->reviewsurl.'" title="'.$reviewstext.'"';
                    $placeholders['markrating'] = '';
                } else {
                    $placeholders['reviewslink'] ='';
//                    $placeholders['reviewcount'] ='';
                    $placeholders['markrating'] = '';   
                }            
            }
            else {
                $reviewstextNull = __('Show customer reviews', ATKP_PLUGIN_PREFIX);
                $reviewstext = __('%s customer reviews', ATKP_PLUGIN_PREFIX);
			    $reviewstext2 = __('1 customer review', ATKP_PLUGIN_PREFIX);
                
                $placeholders['reviewcount'] ='';
                
                if(ATKPSettings::$showrating) {
                    if($myproduct->reviewcount == '' || $myproduct->reviewcount == 0) {
                        $placeholders['reviewcount'] = $reviewstextNull;
                    } else {                    
						$placeholders['reviewcount'] = sprintf(_n($reviewstext2, $reviewstext, $myproduct->reviewcount, ATKP_PLUGIN_PREFIX), $myproduct->reviewcount);
						
					}
                }
                
                if($myproduct->customerreviewurl != '' && ATKPSettings::$showrating) {
                    $placeholders['reviewslink'] = $this->create_external_link($myproduct->customerreviewurl, $placeholders['reviewcount'], $myproduct->title, $listid, $templateid, $placeholders['shoptitle'] , 4);
                    $placeholders['markrating'] = $placeholders['mark'] ;
                } else {         
                    $placeholders['reviewslink'] ='';
                    
                    if($placeholders['reviewcount'] == $reviewstextNull)
                        $placeholders['reviewcount'] ='';
                    $placeholders['markrating'] = '';                    
                }
                
            }
            
            if($myproduct->isprime && ATKPSettings::$showprice) {
                $placeholders['prime_icon'] = '<img src="'.plugins_url('images/prime_amazon.png', ATKP_PLUGIN_FILE).'" alt="'.__('Prime', ATKP_PLUGIN_PREFIX).'"/>';
            } else {
                $placeholders['prime_icon'] = '';   
            }
            
            if($myproduct->percentagesaved == '' || $myproduct->percentagesaved == 0 || !ATKPSettings::$showpricediscount) {
                $placeholders['save_percentage'] = '';
                $placeholders['save_percentage_'] = '';
            } else {
                $placeholders['save_percentage'] = '-'.$myproduct->percentagesaved.'%';
                $placeholders['save_percentage_']  = '('.$placeholders['save_percentage'].')';
            }
            
            if($myproduct->amountsaved == '' || !ATKPSettings::$showpricediscount) {
                $placeholders['save_text'] ='';
                $placeholders['save_amount'] = '';

            }
            else {
                $placeholders['save_amount'] = $myproduct->amountsaved;

                if($myproduct->percentagesaved != '' && $myproduct->percentagesaved != '0')
                    $perc = ' (%s)';
                else
                    $perc ='';
                    
                $placeholders['save_text'] = sprintf(__('You Save: %s', ATKP_PLUGIN_PREFIX).$perc,  $this->formatFloat( $myproduct->amountsavedfloat,  $myproduct->amountsaved, $myproduct->shopid), $myproduct->percentagesaved.'%');
            }
            if($myproduct->listprice == '' || !ATKPSettings::$showprice  || !ATKPSettings::$showpricediscount)
                $placeholders['listprice_text'] ='';
            else
                $placeholders['listprice_text'] = sprintf(__('List Price: %s', ATKP_PLUGIN_PREFIX), $this->formatFloat( $myproduct->listpricefloat,  $myproduct->listprice, $myproduct->shopid)); 
         
            if($myproduct->listprice == '' || !ATKPSettings::$showprice)
                $placeholders['listprice'] ='';
            else
                $placeholders['listprice'] = sprintf(__('%s', ATKP_PLUGIN_PREFIX), $myproduct->listprice); 
                
            if($myproduct->saleprice == '' || !ATKPSettings::$showprice)
                $placeholders['price'] =__('not available', ATKP_PLUGIN_PREFIX);
            else
                $placeholders['price']= sprintf(__('%s', ATKP_PLUGIN_PREFIX),  $this->formatFloat( $myproduct->salepricefloat,  $myproduct->saleprice, $myproduct->shopid)); 
         
         if(!ATKPSettings::$showprice)
         $placeholders['price_text'] ='';
           else if($myproduct->saleprice == '')
                $placeholders['price_text'] =__('not available', ATKP_PLUGIN_PREFIX);
            else
                $placeholders['price_text']= sprintf(__('Price: %s', ATKP_PLUGIN_PREFIX),  $this->formatFloat( $myproduct->salepricefloat,  $myproduct->saleprice, $myproduct->shopid));          
         
            //if($myproduct->totalnew == '' || $myproduct->totalnew == 0)
            //    $placeholders['newfrom_text'] ='';
            //else
            //    $placeholders['newfrom_text'] = sprintf(__('%s new from %s', ATKP_PLUGIN_PREFIX), $myproduct->totalnew, $myproduct->lowestnewprice);          
         
         $desclength = ATKPSettings::$description_length == '0' || ATKPSettings::$description_length == '' ? 400 : ATKPSettings::$description_length;
         
            switch(ATKPSettings::$boxcontent) {
                default:
                case '1':
                    
                    if($myproduct->features == '') {
                        $descclean = strip_tags($myproduct->description);
                        $placeholders['info_text'] = (strlen($descclean) > $desclength) ? substr($descclean,0,$desclength).'...' :  $descclean;
                    } else {
                         $placeholders['info_text'] = $myproduct->features;
                    }
                    
                    break;
                case '2':
                    $placeholders['info_text'] = $myproduct->features;
                    break;
                case '3':
                    $descclean = strip_tags($myproduct->description);
                    $placeholders['info_text'] = (strlen($descclean) > $desclength) ? substr($descclean,0,$desclength).'...' :  $descclean;
                    break;
                
            }
            
           
            
            $placeholders['features_text'] = $myproduct->features;
            $placeholders['description_text'] = $myproduct->description;
            
            $placeholders['priceinfo_text'] = __('Price incl. VAT., Excl. Shipping', ATKP_PLUGIN_PREFIX);
            $placeholders['cssclass'] = $cssElementClass;
            $placeholders['content'] = $content;
            
            if(ATKPSettings::$show_moreoffers) {
                
                $moreoffers = $this->createOutput(array($myproduct), '', ATKPSettings::$moreoffers_template = '' || ATKPSettings::$moreoffers_template == null ? 'moreoffers' : ATKPSettings::$moreoffers_template, 'atkp-moreoffersinfo',  '',  '',  '',  0, 2);
                
                $placeholders['moreoffers'] = $moreoffers;
            } else
                $placeholders['moreoffers'] = '';
            
            
            if(ATKP_PLUGIN_VERSION >= 30) {
                require_once  ATKP_PLUGIN_DIR.'/includes/atkp_udfield.php';
                $newfields = atkp_udfield::load_fields();
               
               foreach ($newfields as $newfield ) {
                    $fieldname = 'customfield_'.$newfield->name; 
                    
                    $placeholders[$fieldname] = '';
                    
                    if($myproduct->productid == '' || $myproduct->productid == '0')
                        continue;
                        
                    $placeholders[$fieldname] = ATKPTools::get_post_setting( $myproduct->productid, ATKP_PRODUCT_POSTTYPE.'_'.$fieldname);
       
                    switch($newfield->type) {
                        case 4:
                            //yesno
                            if($newfield->format == 'text') {
                                if($placeholders[$fieldname] == '1')
                                    $placeholders[$fieldname] = __('Yes', ATKP_PLUGIN_PREFIX);
                                else if($placeholders[$fieldname] == '0')
                                    $placeholders[$fieldname] = __('No', ATKP_PLUGIN_PREFIX);
                                else
                                    $placeholders[$fieldname] ='';
                            } else {                            
                                if($placeholders[$fieldname] == '1')
                                    $placeholders[$fieldname] = '<img src="'.plugins_url('images/yes.png', ATKP_PLUGIN_FILE).'" alt="'.__('Yes', ATKP_PLUGIN_PREFIX).'"/>';
                                else if($placeholders[$fieldname] == '0')
                                    $placeholders[$fieldname] = '<img src="'.plugins_url('images/no.png', ATKP_PLUGIN_FILE).'" alt="'.__('No', ATKP_PLUGIN_PREFIX).'"/>';
                                else
                                    $placeholders[$fieldname] ='';
                            }
                            break;
                    }
                }    
            }
            
        $placeholders['refresh_date'] = date_i18n( get_option( 'date_format' ), $myproduct->updatedon);
        $placeholders['refresh_time'] = date_i18n( get_option( 'time_format' ), $myproduct->updatedon);
		
        $placeholders = apply_filters('atkp_modify_placeholders', $placeholders);
 
        return $placeholders;
    }
    
    public function getPlaceholders() {
        require_once  ATKP_PLUGIN_DIR.'/includes/atkp_product.php';
        require_once  ATKP_PLUGIN_DIR.'/includes/atkp_product_image.php';
        
        $placeholders = $this->createPlaceholderArray(new atkp_product(), 1, '', '', false);
        
        $newfields = array();
         if(ATKP_PLUGIN_VERSION >= 30) {
                require_once  ATKP_PLUGIN_DIR.'/includes/atkp_udfield.php';
                $newfields = atkp_udfield::load_fields();
         }            
        
        $myplaceholders = array();
                                            
        foreach ( array_keys($placeholders) as $placeholder ) { 
            switch($placeholder) {
                case 'bestseller_number':
                case 'bestseller_text':
                case 'cartlink':
                case 'content':
                case 'cssclass': 
                case 'hidelistlink':
                case 'info_text':
                case 'link':
                case 'linktext':
                case 'mark':
                case 'markrating':
                case 'productid':                                                                       
                case 'productlink':
                case 'reviewslink':
                case 'listurl':
                case 'listlink':
                case 'listlinktext':
                case 'reviewcount2':
                case 'priceinfo_text':
                case 'shopid':
                case 'listid':
                case 'templateid':
                case 'moreoffers':
                case 'shoplogourl':
                case 'smallshoplogourl':
                    break;
                case 'refresh_date':
                    $myplaceholders[$placeholder] =  __('Refresh date', ATKP_PLUGIN_PREFIX);  
                    break;
                case 'refresh_time':
                    $myplaceholders[$placeholder] =  __('Refresh time', ATKP_PLUGIN_PREFIX);  
                    break;
                case 'shipping':
                    $myplaceholders[$placeholder] =  __('Shipping', ATKP_PLUGIN_PREFIX);  
                    break;
                case 'shoptitle':
                    $myplaceholders[$placeholder] =  __('Shop Title', ATKP_PLUGIN_PREFIX);  
                    break;
                case 'smallshoplogo':
                    $myplaceholders[$placeholder] =  __('Small Shop Logo', ATKP_PLUGIN_PREFIX);  
                    break;
                case 'shoplogo':
                    $myplaceholders[$placeholder] =  __('Shop Logo', ATKP_PLUGIN_PREFIX);  
                    break;
                case 'title':
                    $myplaceholders[$placeholder] =  __('Title', ATKP_PLUGIN_PREFIX);  
                    break;    
                case 'short_title':
                    $myplaceholders[$placeholder] =  __('Title Short', ATKP_PLUGIN_PREFIX);  
                    break;     
                case 'asin':
                    $myplaceholders[$placeholder] =  __('ASIN', ATKP_PLUGIN_PREFIX);  
                    break;   
                case 'isbn':
                    $myplaceholders[$placeholder] =  __('ISBN', ATKP_PLUGIN_PREFIX);  
                    break;   
                case 'ean':
                    $myplaceholders[$placeholder] =  __('EAN', ATKP_PLUGIN_PREFIX);  
                    break;
                case 'brand':
                    $myplaceholders[$placeholder] =  __('Brand', ATKP_PLUGIN_PREFIX);  
                    break;   
                case 'productgroup':
                    $myplaceholders[$placeholder] =  __('Productgroup', ATKP_PLUGIN_PREFIX);  
                    break;  
                case 'availability':
                    $myplaceholders[$placeholder] =  __('Availability', ATKP_PLUGIN_PREFIX);  
                    break; 
                case 'smallimageurl':
                    $myplaceholders[$placeholder] =  __('Small image URL', ATKP_PLUGIN_PREFIX);  
                    break; 
                case 'mediumimageurl':
                    $myplaceholders[$placeholder] =  __('Medium image URL', ATKP_PLUGIN_PREFIX);  
                    break; 
                case 'largeimageurl':
                    $myplaceholders[$placeholder] =  __('Large image URL', ATKP_PLUGIN_PREFIX);  
                    break; 
                case 'smallimage':
                    $myplaceholders[$placeholder] =  __('Small image', ATKP_PLUGIN_PREFIX);  
                    break; 
                case 'mediumimage':
                    $myplaceholders[$placeholder] =  __('Medium image', ATKP_PLUGIN_PREFIX);  
                    break; 
                case 'largeimage':
                    $myplaceholders[$placeholder] =  __('Large image', ATKP_PLUGIN_PREFIX);  
                    break; 
                     
                case 'thumbimages_1':
                case 'thumbimages_2':
                case 'thumbimages_3':
                case 'thumbimages_4':
                case 'thumbimages_5':
                case 'thumbimages_6':
                    $splitted = explode('_',$placeholder);
                    $myplaceholders[$placeholder] =  sprintf(__('Small image %s', ATKP_PLUGIN_PREFIX),$splitted[1]);  
                    break; 
                case 'mediumimages_1':
                case 'mediumimages_2':
                case 'mediumimages_3':
                case 'mediumimages_4':
                case 'mediumimages_5':
                case 'mediumimages_6':
                    $splitted = explode('_',$placeholder);
                    $myplaceholders[$placeholder] =  sprintf(__('Medium image %s', ATKP_PLUGIN_PREFIX) ,$splitted[1]); 
                    break; 
                case 'images_1':
                case 'images_2':
                case 'images_3':
                case 'images_4':
                case 'images_5':
                case 'images_6':
                    $splitted = explode('_',$placeholder);
                    $myplaceholders[$placeholder] =  sprintf(__('Large image %s', ATKP_PLUGIN_PREFIX),$splitted[1]); 
                    break; 
                case 'by_text':
                    $myplaceholders[$placeholder] =  __('"by"-Text', ATKP_PLUGIN_PREFIX);  
                    break; 
                case 'producturl':
                    $myplaceholders[$placeholder] =  __('Product page URL', ATKP_PLUGIN_PREFIX);  
                    break; 
                case 'customerreviewsurl':
                    $myplaceholders[$placeholder] =  __('Customer Reviews URL', ATKP_PLUGIN_PREFIX);  
                    break; 
                case 'reviewsurl':
                    $myplaceholders[$placeholder] =  __('Review URL', ATKP_PLUGIN_PREFIX);  
                    break; 
                case 'rating':
                    $myplaceholders[$placeholder] =  __('Rating', ATKP_PLUGIN_PREFIX);  
                    break; 
                case 'star_rating':
                    $myplaceholders[$placeholder] =  __('Star Rating', ATKP_PLUGIN_PREFIX);  
                    break; 
                case 'reviewcount':
                    $myplaceholders[$placeholder] =  __('Review count', ATKP_PLUGIN_PREFIX);  
                    break; 
                case 'prime_icon':
                    $myplaceholders[$placeholder] =  __('Is prime', ATKP_PLUGIN_PREFIX);  
                    break; 
                case 'save_percentage':
                    $myplaceholders[$placeholder] =  __('Percentage saved', ATKP_PLUGIN_PREFIX);  
                    break; 
                case 'save_percentage_':
                    $myplaceholders[$placeholder] =  __('(Percentage saved)', ATKP_PLUGIN_PREFIX);  
                    break; 
                case 'save_text':
                    $myplaceholders[$placeholder] =  __('You Save', ATKP_PLUGIN_PREFIX);  
                    break; 
                case 'save_amount':
                    $myplaceholders[$placeholder] =  __('Amount saved', ATKP_PLUGIN_PREFIX);  
                    break; 
                case 'listprice':
                    $myplaceholders[$placeholder] =  __('Listprice', ATKP_PLUGIN_PREFIX);  
                    break; 
                case 'listprice_text':
                    $myplaceholders[$placeholder] =  __('Listprice (Text)', ATKP_PLUGIN_PREFIX);  
                    break; 
                case 'price':
                    $myplaceholders[$placeholder] =  __('Price', ATKP_PLUGIN_PREFIX);  
                    break; 
                case 'price_text':
                    $myplaceholders[$placeholder] =  __('Price (Text)', ATKP_PLUGIN_PREFIX);  
                    break;
                case 'features_text':
                    $myplaceholders[$placeholder] =  __('Features', ATKP_PLUGIN_PREFIX);  
                    break; 
                case 'description_text':
                    $myplaceholders[$placeholder] =  __('Description', ATKP_PLUGIN_PREFIX);  
                    break; 
                default:
                    $myplaceholders[$placeholder] = $placeholder;  
                    
                    if(ATKP_PLUGIN_VERSION >= 30)
                       foreach ($newfields as $newfield ) {
                           if('customfield_'.$newfield->name == $placeholder)
                                $myplaceholders[$placeholder] = $newfield->caption;
                       }
                    break;
            }   
        }
        
        
        return $myplaceholders;        
    }
    
    public function createOutput($products, $content='', $template='', $cssContainerClass = '', $cssElementClass = '', $addtocart = '', $listid= '', $hidedisclaimer = 0, $templatetypedefault = 0) {
            
        $myheader ='';
        $body_header ='';
        $detail_header ='';
        $detail_footer ='';
        
        $mytemplate = '';
        $body_footer ='';
        $myfooter ='';
        $disclaimer = '';
        $disclaimerclass ='';

        $templatetype = 1;
        $includemainoffer = false;
        
        if(ATKPSettings::$access_show_disclaimer) 
            $disclaimer = ATKPSettings::$access_disclaimer_text;

        if(is_numeric($template) && ATKP_PLUGIN_VERSION >= 30) {
            
             $templatefound = get_post($template);

            if(isset($templatefound) && $templatefound != null) {
                $myheader = html_entity_decode(ATKPTools::get_post_setting($templatefound->ID, ATKP_TEMPLATE_POSTTYPE.'_header', true ));
                $detail_header = html_entity_decode(ATKPTools::get_post_setting($templatefound->ID, ATKP_TEMPLATE_POSTTYPE.'_detail_header', true ));
                $detail_footer = html_entity_decode(ATKPTools::get_post_setting($templatefound->ID, ATKP_TEMPLATE_POSTTYPE.'_detail_footer', true ));
                $body_header = html_entity_decode(ATKPTools::get_post_setting($templatefound->ID, ATKP_TEMPLATE_POSTTYPE.'_body_header', true ));
                $mytemplate = html_entity_decode(ATKPTools::get_post_setting($templatefound->ID, ATKP_TEMPLATE_POSTTYPE.'_body', true ));
                
                $body_footer = html_entity_decode(ATKPTools::get_post_setting($templatefound->ID, ATKP_TEMPLATE_POSTTYPE.'_body_footer', true ));
                $myfooter = html_entity_decode(ATKPTools::get_post_setting($templatefound->ID, ATKP_TEMPLATE_POSTTYPE.'_footer', true ));    
                
                
                $disabledisclaimer = ATKPTools::get_post_setting($templatefound->ID, ATKP_TEMPLATE_POSTTYPE.'_disabledisclaimer', true );
                
                $templatetype = ATKPTools::get_post_setting($templatefound->ID, ATKP_TEMPLATE_POSTTYPE.'_template_type', true );
                $includemainoffer = ATKPTools::get_post_setting($templatefound->ID, ATKP_TEMPLATE_POSTTYPE.'_includemainoffer', true );
                
                $disclaimerclass = 'atkp-disclaimer-'.$templatefound->ID;            
                
                if($disabledisclaimer)
                     $hidedisclaimer = true;
            } else
                return 'template not found: ' . $template;        
            
        }else {
            switch($template) {
                default:
                case 'bestseller':
                    $mytemplate = $this->getDefaultBestsellerTemplate();
                    break;
                case 'wide':
                    $mytemplate = $this->getDefaultWideTemplate();
                    break;
                case 'secondwide':
                    $mytemplate = $this->getSecondWideTemplate();
                    break;
                case 'box':
                    $mytemplate = $this->getDefaultBoxTemplate();
                    $disclaimerclass = 'atkp-small-disclaimer';
                    
                    if(strpos($cssElementClass, 'atkp-widget') !== false)
                        $disclaimerclass .= ' atkp-widget';
                    break;
                case 'popup':
                    $mytemplate = $this->getDefaultPopupTemplate();
                    break;
                case 'detailoffers':
                    $body_header = $this->getDefaultOffersDetailTemplateHeader();
                    $mytemplate = $this->getDefaultOffersDetailTemplateDetail();
                    $myfooter = $this->getDefaultOffersDetailTemplateFooter();
                    $includemainoffer = true;
                    $templatetype = 2;
                    break;
                case 'moreoffers':
                    $body_header = $this->getDefaultMoreOffersTemplateHeader();
                    $mytemplate = $this->getDefaultMoreOffersTemplateDetail();
                    $myfooter = $this->getDefaultMoreOffersTemplateFooter();
                    $hidedisclaimer = true;
                    $templatetype = 2;
                    break;
            }  
        }
       
        if($templatetypedefault != 0)
            $templatetype = $templatetypedefault;
        
        //file_put_contents(ATKP_PLUGIN_DIR.'/'.$template.'.html', $myheader. $mytemplate.$myfooter);
        
        $resultValue = '<div class="atkp-container '.$cssContainerClass.'">';
        
        $firstproduct = null;
        $firstplaceholders = null;
        
        if(sizeof($products) > 0)
        {
            $firstproduct  =$products[0];
         
            if($templatetype != 2 && $templatetype != 3)      
                $firstplaceholders = $this->createPlaceholderArray($firstproduct, 1,  $cssContainerClass, $cssElementClass, $content, $addtocart, $listid, $template);  
            else
                $firstplaceholders = array();
            
            $firstplaceholders['refresh_date'] = date_i18n( get_option( 'date_format' ), $firstproduct->updatedon);
            $firstplaceholders['refresh_time'] = date_i18n( get_option( 'time_format' ), $firstproduct->updatedon);
        }
        
    if($myheader != '' && $firstplaceholders != null) {
        foreach(array_keys($firstplaceholders) as $key){
                $myheader = str_replace('%'.strtolower($key).'%', $firstplaceholders[$key], $myheader);
            }    
        
        $resultValue .= $myheader;
    }
        
        $headerrows = explode('{SYS_APPEND}', $detail_header);
        $templaterows = explode('{SYS_APPEND}', $mytemplate);
        $footerrows = explode('{SYS_APPEND}', $detail_footer);
        
        $productplaceholders = array();
        $hideoutput = false;
        
        $count = 1;
        foreach($products as $myproduct) {    
            
            switch($templatetype) {
                default:
                case 1:
                    //product
                    $placeholders = $this->createPlaceholderArray($myproduct, $count, $cssContainerClass, $cssElementClass, $content, $addtocart, $listid, $template);  
                    
                    array_push($productplaceholders, $placeholders);
                    $count = $count+1;
                    break;
                case 2:
                     
                    //offer
                    if(is_array($myproduct->offers) || $includemainoffer) {
                        if(!is_array($myproduct->offers))
                            $offers = array();
                        else
                            $offers = $myproduct->offers;
                        
                        if($includemainoffer) {
                         
                         $offer = new atkp_product_offer();
                            $offer->id =uniqid();
                            $offer->type = 2;
                            $offer->shopid= $myproduct->shopid;
                            $offer->number =  $myproduct->asin;
                            
                            $offer->shipping = $myproduct->shipping;
                            $offer->availability = $myproduct->availability;
                
                            $offer->price = $myproduct->saleprice;
                            $offer->pricefloat =  $myproduct->salepricefloat;
                            $offer->shippingfloat =  $myproduct->shippingfloat;
                            
                            
                            $offer->link = $myproduct->producturl;
                            $offer->title = $myproduct->title;
                                
                            array_push($offers, $offer);
                            
                        }
                        
                        
                        usort($offers, array($this, "sortPrice"));
                        
                        foreach($offers as $offer) {
                            if($offer->pricefloat == (float)0)
                                continue;
                            
                            $placeholders = $this->createOfferPlaceholderArray($myproduct, $offer, $count, $listid, $template);  
                            if($placeholders != null) {
                                array_push($productplaceholders, $placeholders);
                                $count = $count+1;
                            }
                        }
                    }
                    
                    if($count <= 1)
                            $hideoutput = true;
                    break;
                case 3:
                    //image
                    if(is_array($myproduct->images)) {
                        foreach($myproduct->images as $image) {
                            $placeholders = $this->createImagePlaceholderArray($myproduct, $image, $count, $listid, $template);  
                            array_push($productplaceholders, $placeholders);
                            $count = $count+1;
                        }
                    }
                     if($count <= 1)
                            $hideoutput = true;
                    
                    break;                    
            }
            
            
        }
        

        
        
        $idx = 0;
        foreach($templaterows as $templaterow) {    
            $resultrow = '';
            foreach($productplaceholders as $placeholders) {    
                $result = $templaterow;

                // Search & Replace placeholders
                foreach(array_keys($placeholders) as $key){
                    $result = str_replace('%'.strtolower($key).'%', $placeholders[$key], $result);
                }
                $resultrow .= $result;
                
            }
            
            $resultValue .= $body_header. (isset($headerrows[$idx]) ? $headerrows[$idx] : ''). $resultrow .(isset($footerrows[$idx]) ? $footerrows[$idx] : '').$body_footer;
            
            $idx = $idx+1;
        }
        
        if($myfooter != '' && $firstplaceholders != null) {    
            foreach(array_keys($firstplaceholders) as $key){
                    $myfooter = str_replace('%'.strtolower($key).'%', $firstplaceholders[$key], $myfooter);
                }    
            $resultValue .= $myfooter;
        }
        
        if($disclaimer != '' && $firstproduct != null && !$hidedisclaimer) {
        foreach(array_keys($firstplaceholders) as $key){
                    $disclaimer = str_replace('%'.strtolower($key).'%', $firstplaceholders[$key], $disclaimer);
                }   
            $resultValue .= '<span class="atkp-disclaimer '.$disclaimerclass.'">'.$disclaimer.'</span>';
        }
    	
    	if(ATKP_PLUGIN_VERSION < 20 && $firstproduct != null){
    		$resultValue .= '<span class="atkp-credits">'.__('This list was created with <a href="http://www.affiliate-toolkit.com" title="Affiliate Toolkit">Affiliate Toolkit</a>.', ATKP_PLUGIN_PREFIX).'</span>';	
    	} 
    	
    	$resultValue .= '</div>';
    	
    	if($firstproduct == null)
    	    $resultValue ='';
    	
    	//remove empty a tags from output
    	
        //$resultValue=	preg_replace("/<a>(.*?)<\/a>/", "$1", $resultValue);
        	
    	if($hideoutput == true)
    	    return '';
    	else	
            return $resultValue; 
    }
    
    private function sortPrice($a, $b)
    {
        if ($a->pricefloat == $b->pricefloat) {
            return 0;
        }
        return ($a->pricefloat < $b->pricefloat) ? -1 : 1;
    }

    
    private function roundRate($rate)
    {
        $rate = round(($rate * 2), 0) / 2;
        return $rate;
    }
    
    private function getDefaultBestsellerTemplate() {

            $result = '<div class="atkp-box atkp-clearfix %cssclass%">';
        
            //$result .= '    <span class="atkp-bestseller">%bestseller_text%</span>';
            $result .= '        <div class="atkp-ribbon"><span>%bestseller_number%</span></div>';
            $result .= '    <div class="atkp-thumb">';
            
             if(get_option(ATKP_PLUGIN_PREFIX.'_linkimage',0))
                $result .= '        <a %link%><img class="atkp-image" src="%mediumimageurl%" alt="%title%" /></a>';
            else
                $result .= '        <img class="atkp-image" src="%mediumimageurl%" alt="%title%" />';
            
            $result .= '        <div class="atkp-rating">%star_rating%</div>';
            if(get_option(ATKP_PLUGIN_PREFIX.'_linkrating',0))
                $result .= '        <div class="atkp-reviews"><a %reviewslink%>%reviewcount%%markrating%</a></div>';
            else
                $result .= '        <div class="atkp-reviews">%reviewcount%</div>';
            $result .= '        <div class="atkp-shoplogo">%shoplogo%</div>';
            $result .= '    </div>';

            $result .= '    <div class="atkp-content">';
            $result .= '        <a class="atkp-title" %productlink%>%short_title%%mark%</a>';
            $result .= '        <div class="atkp-author">%by_text%</div>';
            $result .= '        <div class="atkp-description">%info_text%</div>';
            $result .= '    </div>'; 
            $result .= '    <div class="atkp-bottom">';
           
            $result .= '        <span class="atkp-price atkp-listprice">%listprice_text%</span>';
            $result .= '        <span class="atkp-price atkp-savedamount">%save_text%</span>';
            $result .= '        <span class="atkp-price atkp-saleprice">%prime_icon% %price_text%</span>';
            
            $result .= '        <a %link% class="atkp-button">%linktext%%mark%</a> ';
            $result .= '        <div class="atkp-moreoffersinfo">%moreoffers%</div> ';
            $result .= '        <div class="atkp-container"></div><span class="atkp-priceinfo">%priceinfo_text%</span>';
            $result .= '    </div>'; 

            
            $result .= '</div>'; 
            

            return $result;
    }
        
    private function getDefaultWideTemplate() {

            $result = '<div class="atkp-box atkp-clearfix %cssclass%">';
            $result .= '    <div class="atkp-thumb">';

            if(get_option(ATKP_PLUGIN_PREFIX.'_linkimage',0))
                $result .= '        <a %link%><img class="atkp-image" src="%mediumimageurl%" alt="%title%" /></a>';
            else
                $result .= '        <img class="atkp-image" src="%mediumimageurl%" alt="%title%" />';
            
            $result .= '        <div class="atkp-rating">%star_rating%</div>';
            if(get_option(ATKP_PLUGIN_PREFIX.'_linkrating',0))
                $result .= '        <div class="atkp-reviews"><a %reviewslink%>%reviewcount%%markrating%</a></div>';
            else
                $result .= '        <div class="atkp-reviews">%reviewcount%</div>';
            $result .= '        <div class="atkp-shoplogo">%shoplogo%</div>';
            $result .= '    </div>';

            $result .= '    <div class="atkp-content">';
            $result .= '        <a class="atkp-title" %productlink%>%short_title%%mark%</a>';
            $result .= '        <div class="atkp-author">%by_text%</div>';
			$result .= '        <div class="atkp-description">%info_text%</div>';
            $result .= '    </div>'; 
            $result .= '    <div class="atkp-bottom">';
           
            $result .= '        <span class="atkp-price atkp-listprice">%listprice_text%</span>';
            $result .= '        <span class="atkp-price atkp-savedamount">%save_text%</span>';
            $result .= '        <span class="atkp-price atkp-saleprice">%prime_icon%&nbsp;%price_text%</span>';
            
            $result .= '        <a %link% class="atkp-button">%linktext%%mark%</a> ';
            $result .= '        <div class="atkp-moreoffersinfo">%moreoffers%</div> ';
            $result .= '        <div class="atkp-container"></div><span class="atkp-priceinfo">%priceinfo_text%</span>';
            $result .= '    </div>'; 

            
            $result .= '</div>'; 
            

            return $result;
    }
    
    private function getDefaultPopupTemplate() {
            $result = '    <div class="atkp-right" style="width:40%">';
            if(get_option(ATKP_PLUGIN_PREFIX.'_linkimage',0))
                $result .= '        <a %link%><img class="atkp-image" src="%smallimageurl%" alt="%title%" /></a>';
             else
                $result .= '        <img class="atkp-image" src="%smallimageurl%" alt="%title%" />';
            
               

            $result .= '        <div class="atkp-rating">%star_rating%</div>';
            $result .= '        <div class="atkp-reviews">%reviewcount2%</div>';
            $result .= '    </div>';
            $result .= '    <div class="atkp-left" style="width: 55%;">';
            $result .= '        <span class="atkp-title"><b>%title%</b></span>';
            $result .= '        <div class="atkp-author">%by_text%</div>';
            $result .= '    </div>'; 
           



            return $result;
    }
    
    private function getDefaultBoxTemplate() {

            $result = '<div class="atkp-box atkp-clearfix atkp-smallbox %cssclass%">';

            $result .= '    <div class="atkp-thumb">';
            if(get_option(ATKP_PLUGIN_PREFIX.'_linkimage',0))
                $result .= '        <a %link%><img class="atkp-image" src="%mediumimageurl%" alt="%title%" /></a>';
            else
                $result .= '        <img class="atkp-image" src="%mediumimageurl%" alt="%title%" />';
            
            $result .= '        <div class="atkp-rating">%star_rating%</div>';
            
            if(get_option(ATKP_PLUGIN_PREFIX.'_linkrating',0))
                $result .= '        <div class="atkp-reviews"><a %reviewslink%>%reviewcount%%markrating%</a></div>';
            else
                $result .= '        <div class="atkp-reviews">%reviewcount%</div>';
            
            $result .= '    </div>';

            $result .= '    <div class="atkp-content">';
            $result .= '        <a class="atkp-title" %productlink%>%short_title%%mark%</a>';
            $result .= '        <div class="atkp-author">%by_text%</div>';
            $result .= '        <div class="atkp-shoplogo">%shoplogo%</div>';
            $result .= '    </div>'; 
            $result .= '    <div class="atkp-bottom">';
           
            $result .= '        <span class="atkp-price atkp-listprice">%listprice_text%</span>';
            $result .= '        <span class="atkp-price atkp-savedamount">%save_text%</span>';
            $result .= '        <span class="atkp-price atkp-saleprice">%prime_icon% %price_text%</span>';
            
            $result .= '        <a %link% class="atkp-button">%linktext%%mark%</a> ';
            $result .= '        <div class="atkp-moreoffersinfo">%moreoffers%</div> ';
            $result .= '        <span class="atkp-priceinfo">%priceinfo_text%</span>';
            //Preis inkl. MwSt., zzgl. Versandkosten
            $result .= '    </div>'; 

            
            $result .= '</div>'; 
            

            return $result;
    }
    
    
    private function getDefaultMoreOffersTemplateHeader() {

            $result = '<div class="atkp-offers-dropdown">';

            $result .= '      <a  class="atkp-offers-dropbtn" style="font-size:12px" >'.__('More offers »', ATKP_PLUGIN_PREFIX).'</a>';
            $result .= '          <div class="atkp-offers-dropdown-content">';
            

            return $result;
    }
    
     private function getDefaultMoreOffersTemplateDetail() {

            $result = '<div class="atkp-container atkp-clearfix">';

            $result .= '<a %productlink%>';
            $result .= '<span class="atkp-more-offers-left" style="width: 25%;">';
            $result .= '%smallshoplogo%';
            $result .= '</span>';
            $result .= '<span class="atkp-more-offers-right" style="width: 65%;">';
            $result .= '<span class="atkp-more-offers-price">%price_text%</span><br />';
            $result .= '<span class="atkp-more-offers-shipping atkp-clearfix">%shipping_text%</span>';
            $result .= '</span>';
            $result .= '</span>';
            $result .= '</a>';
            $result .= '</div>';

            return $result;
    }
    
     private function getDefaultMoreOffersTemplateFooter() {

            $result = '  </div>  </div>';

            return $result;
    }
    
    private function getDefaultOffersDetailTemplateHeader() {

            $result = '<table style="width:100%" class="atkp-pricecompare">';
        

            return $result;
    }
    
     private function getDefaultOffersDetailTemplateDetail() {

            $result = '<tr>';

            $result .= '<td style="vertical-align: middle;">';
            $result .= '%shoplogo%';
            $result .= '</td><td>';
            $result .= '%price_text%<br />%shipping_text%<br />%availability_text%';
            $result .= '</td>';
            $result .= '<td style="vertical-align: middle; text-align:right">';
            $result .= '<a %productlink% class="atkp-button">%linktext%%mark%</a>';
            $result .= '</td>';
            $result .= '</tr>';

            return $result;
    }
    
     private function getDefaultOffersDetailTemplateFooter() {

            $result = '</table>';

            return $result;
    }
    
    private function getSecondWideTemplate() {

           $result = '<div class="atkp-box atkp-secondbox atkp-clearfix %cssclass%">';
           $result .= '        <a class="atkp-title" %productlink%>%short_title%%mark%</a>';
           
           
            $result .= '    <div class="atkp-thumb">';

            if(get_option(ATKP_PLUGIN_PREFIX.'_linkimage',0))
                $result .= '        <a %link%><img class="atkp-image" src="%mediumimageurl%" alt="%title%" /></a>';
            else
                $result .= '        <img class="atkp-image" src="%mediumimageurl%" alt="%title%" />';
            
            
            $result .= '    </div>';

            $result .= '    <div class="atkp-bottom">';
           
            $result .= '        <span class="atkp-price atkp-saleprice">%price_text% %prime_icon% </span>';
            
             $rating = '';
            
            if(ATKPSettings::$showrating) {
                $rating = '(%reviewcount%)';
                
                if(get_option(ATKP_PLUGIN_PREFIX.'_linkrating',0))
                    $rating = '(<a %reviewslink%>%reviewcount%%markrating%</a>)';
            }
            
            $result .= '        <div class="atkp-rating">%star_rating% <span>'.$rating.'</span></div>';
            $result .= '        <div class="atkp-reviews"></div>';
            
            $result .= '        <a %link% class="atkp-button">%linktext%%mark%</a> ';
            $result .= '        <div class="atkp-moreoffersinfo">%moreoffers%</div> ';
            $result .= '        <div class="atkp-container"></div><span class="atkp-priceinfo">%priceinfo_text%</span>';
            
            
            $result .= '        <div class="atkp-shoplogo">%shoplogo%</div>';
            $result .= '    </div>'; 

            
            $result .= '</div>'; 
            

            return $result;
    }
    
}


?>