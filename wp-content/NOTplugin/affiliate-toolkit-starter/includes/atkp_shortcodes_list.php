<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    class atkp_shortcodes_list
    {   
        /**
         * Construct the plugin object
         */
        public function __construct($pluginbase)
        {
            add_shortcode(ATKP_LIST_SHORTCODE, array(&$this, 'shortcode'));
        }
        
        
        function shortcode($atts, $content = "") {
            try {
             
                $a = shortcode_atts( array(
                    'id' => 0,
                    'template' => '',
                    'elementcss' => '',
                    'containercss'=>'',
                    'buttontype' => '',
                    'limit' => 0,
                    'randomsort' =>'no',
                    'hidedisclaimer' => 'no',
                ), $atts );
                
                $id = 0;
                $template = 'wide';
                $buttontype = 'notset';
                $elementcss = '';
                $containercss = '';
                $field ='';
                $limit = 10;
                $randomsort = false;
                $hidedisclaimer = false;
                
                if (isset($a['id'])) 
                    $id = intval($a['id']);
                if (isset($a['template']) && !empty($a['template'])) 
                    $template = $a['template'];
                    
                if (isset($a['elementcss']) && !empty($a['elementcss'])) 
                    $elementcss = $a['elementcss'];
                if (isset($a['containercss']) && !empty($a['containercss'])) 
                    $containercss = $a['containercss'];
                
                 if (isset($a['buttontype']) && !empty($a['buttontype'])) 
                    $buttontype = $a['buttontype'];
                        
                if (isset($a['randomsort']) && !empty($a['randomsort'])) 
                    if($a['randomsort'] == 'yes')
                        $randomsort = true;
                    else if($a['randomsort'] == 'no')
                        $randomsort =false;
                        
                if (isset($a['hidedisclaimer']) && !empty($a['hidedisclaimer'])) 
                    if($a['hidedisclaimer'] == 'yes')
                        $hidedisclaimer =true;
                    else if($a['hidedisclaimer'] == 'no')
                        $hidedisclaimer =false;
                        
                if (isset($a['limit']) && $a['limit'] > 0) 
                    $limit = intval($a['limit']);
                                        
                require_once  ATKP_PLUGIN_DIR.'/includes/atkp_output.php';
                
                $output = new atkp_output();
                return $output->get_list_output($id, $template, $content, $buttontype, $elementcss, $containercss, $limit, $randomsort, $hidedisclaimer);
            
            } catch(Exception $e) {
                return 'Exception: '. $e->getMessage();
            }
        }
    }
    
    
?>