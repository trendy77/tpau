<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    class atkp_shortcodes_product
    {   
        /**
         * Construct the plugin object
         */
        public function __construct($pluginbase)
        {
            add_shortcode(ATKP_PRODUCT_SHORTCODE, array(&$this, 'shortcode'));
            add_action( 'wp_footer', array($this, 'infobox_div' ));
            


        }
        function infobox_div() {
            echo('<!-- Anfang DIV für die InfoBox -->');
            echo('<div id="atkp-infobox" class="atkp-popup" style="z-index:1; visibility:hidden;">');
            echo('<div id="BoxInnen"><span id="BoxInhalte">&nbsp;</span></div>');
            echo('</div>');
            echo('<!-- Ende DIV für die InfoBox -->');
            
        }
        
        function shortcode($atts, $content = "") {
            try {
            
                $a = shortcode_atts( array(
                    'id' => 0,
                    'template' => '',
                    'elementcss' => '',
                    'containercss'=>'',
                    'buttontype' => '',
                    'field' =>'',
                    'link' =>'',
                    'hidedisclaimer' => 'no',
                ), $atts );
                
                $id = 0;
                $template = 'box';
                $buttontype = 'notset';
                $field ='';
                $link = false;
                $elementcss = '';
                $containercss = '';
                                
                if (isset($a['id'])) 
                    $id = intval($a['id']);
                    
                if (isset($a['template']) && !empty($a['template'])) 
                    $template = $a['template'];
                    
                if (isset($a['elementcss']) && !empty($a['elementcss'])) 
                    $elementcss = $a['elementcss'];
                if (isset($a['containercss']) && !empty($a['containercss'])) 
                    $containercss = $a['containercss'];
                    
                if (isset($a['field']) && !empty($a['field'])) 
                    $field = $a['field'];
                    
                if (isset($a['buttontype']) && !empty($a['buttontype'])) 
                    $buttontype = $a['buttontype'];
                        
                if (isset($a['hidedisclaimer']) && !empty($a['hidedisclaimer'])) 
                    if($a['hidedisclaimer'] == 'yes')
                        $hidedisclaimer =true;
                    else if($a['hidedisclaimer'] == 'no')
                        $hidedisclaimer =false;
                        
                if (isset($a['link']) && $a['link'] == 'yes')
                    $link = true;
                  
                require_once  ATKP_PLUGIN_DIR.'/includes/atkp_output.php';
                
                $output = new atkp_output();
                    
                return $output->get_product_output($id, $template, $content, $buttontype, $field, $link, $elementcss, $containercss, $hidedisclaimer);
            
            } catch(Exception $e) { 
                return 'Exception: '. $e->getMessage();
            }
         }
    }
    
    
?>