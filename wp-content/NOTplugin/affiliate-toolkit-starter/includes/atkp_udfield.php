<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class atkp_udfield{
        public $data = array();

        function __construct()
        {
            $this->id ='';
            $this->caption ='';
            $this->name ='';
            $this->type =0;
            $this->format ='';
            
            
                    //echo '<option value="1">' . __('Text', ATKP_PLUGIN_PREFIX) . '</option>';
                    //echo '<option value="2">' . __('Multiline', ATKP_PLUGIN_PREFIX) . '</option>';
                    //echo '<option value="3">' . __('Dropdown', ATKP_PLUGIN_PREFIX) . '</option>';
                    //echo '<option value="4">' . __('Yes/No', ATKP_PLUGIN_PREFIX) . '</option>';
                    //echo '<option value="5">' . __('Html', ATKP_PLUGIN_PREFIX) . '</option>';
        }
        
        public static function load_fields() {
         
            $oldfields = get_option(ATKP_PLUGIN_PREFIX.'_customfields_product');
            $newfields = get_option(ATKP_PLUGIN_PREFIX.'_udf_product');
                
                
                        
            if(!isset($newfields) || $newfields== '' ) {
                $newfields = array();
                
                
                $count = 1;
                foreach ( explode("\n", $oldfields ) as $field ) {
                   $fieldtemp = str_replace(array("\n","\r"), '', $field);
                
                   if(!isset($fieldtemp) || empty($fieldtemp))
                            continue;
                   
                   $fieldname = sanitize_title($fieldtemp, $count);   
                   
                   $udf = new atkp_udfield();
                   $udf->id =uniqid();
                   $udf->caption = $field;
                   $udf->name = $fieldname;
                   $udf->type = 1;
                   
                   array_push($newfields, $udf);
              
                   $count = $count +1;           
                }
            
                //atkp_udfield
            }
         
            return $newfields;
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