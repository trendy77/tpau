<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    class atkp_output
    {   
        public function __construct()
        {
            
        }
        
        /**
        * Erstellt die Ausgabe einer konfigurierten Liste und aufgrund der Parameter.
        *
        * A *description*, that can span multiple lines, to go _in-depth_ into the details of this element
        * and to provide some background information or textual references.
        *
        * @id int Die eindeutige ID der Liste im Wordpress-Custom-Posttype.
        *
        * @template string Entweder ein Standardtemplate (wide, box,...) oder die ID der Vorlage (Customposttype).
        * 
        * @content string Ein benutzerdefinierter Text welcher bis zur Vorlage durchgeschleift wird.  
        *
        * @return string Gibt das vollständige HTML zurück.
        */
        public function get_list_output($id, $template = 'wide', $content='', $buttontype = 'notset', $elementcss = '', $containercss = '', $limit = 10, $randomsort = false, $hidedisclaimer = false) {
            
            
            require_once ATKP_PLUGIN_DIR.'/includes/atkp_product.php';
            require_once ATKP_PLUGIN_DIR.'/includes/atkp_product_image.php'; 
            require_once ATKP_PLUGIN_DIR.'/includes/atkp_product_offer.php';
            require_once ATKP_PLUGIN_DIR.'/includes/helper/atkp_template_helper.php';
            
            $templatehelper = new atkp_template_helper();
            
            $productlist = ATKPCache::get_cache_by_id($id);
            
            if($productlist == null) {
                $cronjob = new atkp_cronjob(array());
                $cronjob->update_list($id);   
                $productlist = ATKPCache::get_cache_by_id($id);
            }
            
            $preferlocalproductinfo = ATKPTools::get_post_setting( $id, ATKP_LIST_POSTTYPE.'_preferlocalproduct');
            
            $outputprds = array();
            if($productlist != null) {
                $posts_found = get_posts(array(
                	'posts_per_page'	=> -1,
                	'post_status'      => 'publish',
                	'post_type'			=> ATKP_PRODUCT_POSTTYPE	
                ));
                
                if($randomsort)
                    shuffle($productlist);
                    
                $counter = 0;
                foreach ($productlist as $product) {
                    try {
                        $type = $product['type'];
                        $value = $product['value'];
                        
                        if($value == '')
                            continue;
                        
                        if($counter >= $limit)
                            break;
                        
                        $counter = $counter +1;
                        
                        switch($type) {
                            case 'product':
                            if($preferlocalproductinfo)
                                foreach ( $posts_found as $myprd ) 
                                {
                                    if(ATKPTools::get_post_setting( $myprd->ID, ATKP_PRODUCT_POSTTYPE.'_asin') == $value->asin)
                                        $value = atkp_product::load($myprd->ID);
                                    break;
                                }
                                
                                break;
                            case 'productid':
                                $value = atkp_product::load($value);
                                break;
                        }        
                        array_push($outputprds, $value);
                    } catch(Exception $e) {
                        //TODO: 'Exception: ',  $e->getMessage(), "\n";
                    }
                }  
            }
            
            $addintocart = false;
            
            switch($buttontype) {
                case 'addtocart':
                    $addintocart =true;
                    break;
                default:
                case 'link':
                    $addintocart = false;
                    break;
            }
            
            $resultValue =  $templatehelper->createOutput($outputprds, $content, $template, $containercss, $elementcss, $addintocart, $id, $hidedisclaimer);
            return $resultValue;
        }
    
    
        public function get_product_output($id, $template = 'box', $content='', $buttontype = 'notset', $field, $link = false, $elementcss = '', $containercss = '', $hidedisclaimer = false) {
         
            $addintocart = false;
            $resultValue ='';
            
            switch($buttontype) {
                case 'addtocart':
                    $addintocart =true;
                    break;
                default:
                case 'link':
                    $addintocart = false;
                    break;
            }
         
            require_once ATKP_PLUGIN_DIR.'/includes/atkp_product.php';
            require_once ATKP_PLUGIN_DIR.'/includes/atkp_product_image.php'; 
            require_once ATKP_PLUGIN_DIR.'/includes/atkp_product_offer.php';
                
            $prd = atkp_product::load($id);
          
            require_once  ATKP_PLUGIN_DIR.'/includes/helper/atkp_template_helper.php';
            $templatehelper = new atkp_template_helper();
            
            if($field != '') {
                $placeholders = ATKPCache::get_cache_by_id('placeholders_'. $id);
                
                if($placeholders == null) {        
                    $placeholders = $templatehelper->createPlaceholderArray($prd, 1, $containercss, $elementcss, $content, $addintocart);
                    ATKPCache::set_cache_by_id('placeholders_'. $id, $placeholders, 120);
                }
                
                foreach(array_keys($placeholders) as $key)
                    if($key == $field){
                        $resultValue = $placeholders[$key];
                        break;
                    }
                    
                    
                if($containercss != '') {
                    $resultValue = '<div class="'.$containercss.'">'.$resultValue.'</div>';   
                }
             } else                                                
                $resultValue =  $templatehelper->createOutput(array($prd), $content, $template, $containercss, $elementcss, $addintocart, '', $hidedisclaimer);
            
            if($link == true) {
                $placeholders = $templatehelper->createPlaceholderArray($prd, 1, $containercss, $elementcss, $content, $addintocart);
                        
                $link = $placeholders['link'];
           
                if($field != '') 
                    $content = $resultValue;
                else if($content == '')
                    $content = $prd->title;
                    
                if(ATKPSettings::$access_mark_links == 1 && strpos($content,'img src') == false)
                        $content .= '*';
        
                
                if(ATKPSettings::$show_linkinfo && !$prd->disablehoverlink) {
                    $link = str_replace('title="', 'alt="', $link);
                    
                    if(ATKPSettings::$linkinfo_template == '')
                        $template = 'popup';
                    else
                        $template = ATKPSettings::$linkinfo_template;
                    //createOutput($products, $content='', $template='', $cssContainerClass = '', $cssElementClass = '', $addtocart = '', $listurl= '', $hidedisclaimer = 0) 
                    $infobox = $templatehelper->createOutput(array($prd), '', $template, 'atkp-clearfix', '', '', '', 1);
                    
                    $name = 'infobox-'.uniqid();
                    
                    echo '<div id="'.$name.'" style="display:none;"><div class="BoxInnen"><span class="BoxInhalte">'. $infobox.'</span></div></div>';
                    
                    
                    $link .= 'onMouseover=\'showAtkpBox(event,"'.$name.'",20,-40);\' onMouseout=\'hideAtkpBox(event,"'.$name.'");\'';
                    
                }
                
                if(ATKPSettings::$access_mark_links == 1 && strpos($content,'img src') == true) {
                    $capt =  __('Advertising', ATKP_PLUGIN_PREFIX);
                    
                    $resultValue = '<div class="'.$containercss.'"><div class="atkp-affiliateimage atkp-clearfix"><a '. $link . ' >'.$content.'</a><div style="margin-top:3px">'. $capt .'</div></div></div>';
                } else
                    $resultValue = '<a '. $link . ' >'.$content.'</a>'; 
                
            }
            
                    
            return $resultValue;
            
        }
    
        public function get_css_url() {
            return plugins_url('/css/style.css', ATKP_PLUGIN_FILE);   
        }
        
        public function get_js_url() {
            return plugins_url('/js/library.js', ATKP_PLUGIN_FILE);   
        }
    
        public function get_css_output() {
         
            $selectedbutton = get_option(ATKP_PLUGIN_PREFIX.'_buttonstyle');
            
            $custom_css = '';                
            
            switch($selectedbutton) {
                default:
                case 1:
                    $custom_css = file_get_contents(ATKP_PLUGIN_DIR.'/css/button_classic.css');
                    break;
                case 2:
                    $btn_background_top =  get_option(ATKP_PLUGIN_PREFIX.'_btn_color_background_top', '#FFB22A');
                    $btn_background_bottom =  get_option(ATKP_PLUGIN_PREFIX.'_btn_color_background_bottom', '#ffab23');
                    $btn_foreground =  get_option(ATKP_PLUGIN_PREFIX.'_btn_color_foreground', '#333333');
                    $btn_border =  get_option(ATKP_PLUGIN_PREFIX.'_btn_color_border', '#ffaa22');
                    
                    
                    $custom_css = file_get_contents(ATKP_PLUGIN_DIR.'/css/button_classic_custom.css');
                     
                    $custom_css = str_replace('%background_color%', $btn_background_top, $custom_css);
                    $custom_css = str_replace('%background2_color%', $btn_background_bottom, $custom_css);
                    $custom_css = str_replace('%foreground_color%', $btn_foreground, $custom_css);
                    $custom_css = str_replace('%border_color%', $btn_border, $custom_css);
                    
                    break;
                case 10:
                    $custom_css = file_get_contents(ATKP_PLUGIN_DIR.'/css/button_flat.css');
                    break;
                case 11:
                    $btn_background_top =  get_option(ATKP_PLUGIN_PREFIX.'_btn_color_background_top', '#FFB22A');
                    $btn_foreground =  get_option(ATKP_PLUGIN_PREFIX.'_btn_color_foreground', '#fff');
                    
                    $custom_css = file_get_contents(ATKP_PLUGIN_DIR.'/css/button_flat_custom.css');
                    $custom_css = str_replace('%background_color%', $btn_background_top, $custom_css);
                    $custom_css = str_replace('%foreground_color%', $btn_foreground, $custom_css);
                    break;
                case 20:
                    break;
            }
            
            $custom_css2 = '';
            
            $selectedbox = get_option(ATKP_PLUGIN_PREFIX.'_boxstyle');
            
            switch($selectedbox) {
                default:
                case 1:
                    $custom_css2 = file_get_contents(ATKP_PLUGIN_DIR.'/css/box_classic.css');
                    break;
                case 2:
                    $custom_css2 = file_get_contents(ATKP_PLUGIN_DIR.'/css/box_flat.css');
                    break;
                case 3:
                    $custom_css2 = file_get_contents(ATKP_PLUGIN_DIR.'/css/box_flat_withoutborder.css');
                    break;
               
            }
            
            $custom_css3 = '';
            
            if(ATKP_PLUGIN_VERSION >= 30) {
                global $post;
                $args = array( 'post_type' => ATKP_TEMPLATE_POSTTYPE, 'posts_per_page'   => 300, 'post_status'      => 'publish');
                $posts_array = get_posts($args);
                
                foreach ( $posts_array as $prd ) { 
                  $css =  ATKPTools::get_post_setting($prd->ID, ATKP_TEMPLATE_POSTTYPE.'_css', true );
                    
                    if($css != '')
                        $custom_css3 .=  $css ."\r\n" ;
                 };
            }
            
            return $custom_css."\r\n". $custom_css2."\r\n".$custom_css3;          
        }
    
    }
    
    
?>