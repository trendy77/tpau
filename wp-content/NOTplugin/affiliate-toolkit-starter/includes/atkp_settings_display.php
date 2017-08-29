<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    class atkp_settings_display
    {
        /**
         * Construct the plugin object
         */
        public function __construct($pluginbase)
        {
            add_action( 'admin_enqueue_scripts', array(&$this, 'add_color_picker') );

            
            
        }
        
        function add_color_picker( $hook ) {
         
            if( is_admin() ) {
         
                // Add the color picker css file
                wp_enqueue_style( 'wp-color-picker' );
         
                // Include our custom jQuery file with WordPress Color Picker dependency
                wp_enqueue_script( 'custom-script-handle', plugins_url( 'custom-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
            }
        }

        public function display_configuration_page() {
		
            if (ATKPTools::exists_post_parameter('savedisplay') && check_admin_referer('save', 'save')) {
                if (!current_user_can('manage_options')) {
                    wp_die(__('You do not have sufficient permissions to access this page', ATKP_PLUGIN_PREFIX));
                }
                
                //speichern der einstellungen
               
                update_option(ATKP_PLUGIN_PREFIX.'_show_linkinfo', ATKPTools::get_post_parameter(ATKP_PLUGIN_PREFIX.'_show_linkinfo', 'bool'));
                
                update_option(ATKP_PLUGIN_PREFIX.'_showprice', ATKPTools::get_post_parameter(ATKP_PLUGIN_PREFIX.'_showprice', 'bool'));
                update_option(ATKP_PLUGIN_PREFIX.'_showpricediscount', ATKPTools::get_post_parameter(ATKP_PLUGIN_PREFIX.'_showpricediscount', 'bool'));
                update_option(ATKP_PLUGIN_PREFIX.'_showstarrating', ATKPTools::get_post_parameter(ATKP_PLUGIN_PREFIX.'_showstarrating', 'bool'));
                update_option(ATKP_PLUGIN_PREFIX.'_showrating', ATKPTools::get_post_parameter(ATKP_PLUGIN_PREFIX.'_showrating', 'bool'));
                update_option(ATKP_PLUGIN_PREFIX.'_hideemptyrating', ATKPTools::get_post_parameter(ATKP_PLUGIN_PREFIX.'_hideemptyrating', 'bool'));
                update_option(ATKP_PLUGIN_PREFIX.'_hideemptystars', ATKPTools::get_post_parameter(ATKP_PLUGIN_PREFIX.'_hideemptystars', 'bool'));
                                
                update_option(ATKP_PLUGIN_PREFIX.'_linkrating', ATKPTools::get_post_parameter(ATKP_PLUGIN_PREFIX.'_linkrating', 'bool'));
                update_option(ATKP_PLUGIN_PREFIX.'_linkimage', ATKPTools::get_post_parameter(ATKP_PLUGIN_PREFIX.'_linkimage', 'bool'));
                
                update_option(ATKP_PLUGIN_PREFIX.'_buttonstyle', ATKPTools::get_post_parameter(ATKP_PLUGIN_PREFIX.'_buttonstyle', 'int'));
              
            }
			
            $mytab = ATKPTools::get_get_parameter( 'tab', 'int');
			
            if ($mytab != 0 ) $tab = $mytab; else $tab = 1;
                        ?>
            <div class="wrap">
               <!-- <h2><?php _e('Affiliate Toolkit - Advanced Settings', ATKP_PLUGIN_PREFIX) ?></h2>      -->      
                
                <form method="POST" action="?page=<?php echo ATKP_PLUGIN_PREFIX.'_affiliate_toolkit-plugin&tab='.$tab ?>"><!--_affiliate_toolkit-bestseller-->
                    <?php wp_nonce_field("save", "save"); ?>
                    <table class="form-table" style="width:1024px">
                    

                        <tr valign="top">
                            <th scope="row" style="background-color:gainsboro; padding:7px" colspan="2">
                                <?php _e('Display', ATKP_PLUGIN_PREFIX) ?>
                            </th>
                        </tr>
                        
                        <tr valign="top">
                            <th scope="row">
                               
                            </th>
                            <td>
                                <input  type="checkbox" id="<?php echo ATKP_PLUGIN_PREFIX.'_showprice' ?>" name="<?php echo ATKP_PLUGIN_PREFIX.'_showprice' ?>" value="1" <?php echo checked(1, get_option(ATKP_PLUGIN_PREFIX.'_showprice',1), true); ?>>
                             <label for="<?php echo ATKP_PLUGIN_PREFIX.'_showprice' ?>">
                                    <?php _e('Show price', ATKP_PLUGIN_PREFIX) ?>
                                </label>
                            </td>
                        </tr>
                        
                        <tr valign="top">
                            <th scope="row">
                               
                            </th>
                            <td>
                                <input  type="checkbox" id="<?php echo ATKP_PLUGIN_PREFIX.'_showpricediscount' ?>" name="<?php echo ATKP_PLUGIN_PREFIX.'_showpricediscount' ?>" value="1" <?php echo checked(1, get_option(ATKP_PLUGIN_PREFIX.'_showpricediscount',1), true); ?>>
                             <label for="<?php echo ATKP_PLUGIN_PREFIX.'_showpricediscount' ?>">
                                    <?php _e('Show price discount', ATKP_PLUGIN_PREFIX) ?>
                                </label>
                            </td>
                        </tr>
                        
                        <tr valign="top">
                            <th scope="row">
                               
                            </th>
                            <td>
                                <input  type="checkbox" id="<?php echo ATKP_PLUGIN_PREFIX.'_showstarrating' ?>" name="<?php echo ATKP_PLUGIN_PREFIX.'_showstarrating' ?>" value="1" <?php echo checked(1, get_option(ATKP_PLUGIN_PREFIX.'_showstarrating',1), true); ?>>
                             <label for="<?php echo ATKP_PLUGIN_PREFIX.'_showstarrating' ?>">
                                    <?php _e('Show star rating', ATKP_PLUGIN_PREFIX) ?>
                                </label>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                               
                            </th>
                            <td>
                                <input  type="checkbox" id="<?php echo ATKP_PLUGIN_PREFIX.'_showrating' ?>" name="<?php echo ATKP_PLUGIN_PREFIX.'_showrating' ?>" value="1" <?php echo checked(1, get_option(ATKP_PLUGIN_PREFIX.'_showrating',1), true); ?>>
                             <label for="<?php echo ATKP_PLUGIN_PREFIX.'_showrating' ?>">
                                    <?php _e('Show rating', ATKP_PLUGIN_PREFIX) ?>
                                </label>
                            </td>
                        </tr>
                        
                        <tr valign="top">
                            <th scope="row">
                               
                            </th>
                            <td>
                                <input  type="checkbox" id="<?php echo ATKP_PLUGIN_PREFIX.'_linkrating' ?>" name="<?php echo ATKP_PLUGIN_PREFIX.'_linkrating' ?>" value="1" <?php echo checked(1, get_option(ATKP_PLUGIN_PREFIX.'_linkrating',1), true); ?>>
                             <label for="<?php echo ATKP_PLUGIN_PREFIX.'_linkrating' ?>">
                                    <?php _e('Link rating', ATKP_PLUGIN_PREFIX) ?>
                                </label>
                            </td>
                        </tr>
                        
                        <tr valign="top">
                            <th scope="row">
                               
                            </th>
                            <td>
                                <input  type="checkbox" id="<?php echo ATKP_PLUGIN_PREFIX.'_linkimage' ?>" name="<?php echo ATKP_PLUGIN_PREFIX.'_linkimage' ?>" value="1" <?php echo checked(1, get_option(ATKP_PLUGIN_PREFIX.'_linkimage',1), true); ?>>
                             <label for="<?php echo ATKP_PLUGIN_PREFIX.'_linkimage' ?>">
                                    <?php _e('Link image', ATKP_PLUGIN_PREFIX) ?>
                                </label>
                            </td>
                        </tr>
                                                     
                         <tr valign="top">
                            <th scope="row">
                                <label for="">
                                    <?php _e('Button style', ATKP_PLUGIN_PREFIX) ?>:
                                </label> 
                            </th>
                            <td>
                            <select   id="<?php echo ATKP_PLUGIN_PREFIX.'_buttonstyle' ?>" name="<?php echo ATKP_PLUGIN_PREFIX.'_buttonstyle' ?>" style="width:300px">
                                <?php
                                $selected = get_option(ATKP_PLUGIN_PREFIX.'_buttonstyle');
                                                         
                                echo '<option value="1" '.($selected == '' || $selected == 1 ? 'selected' : '').' >'.__('Classic Button', ATKP_PLUGIN_PREFIX).'</option>';
                                 
                                echo '<option value="10" '.($selected == 10 ? 'selected' : '').'>'.__('Flat Button', ATKP_PLUGIN_PREFIX).'</option>';              
                                
                                echo '<option value="20" '.($selected == 20 ? 'selected' : '').'>'.__('No style', ATKP_PLUGIN_PREFIX).'</option>';  
                             ?>
</select>                   
                             </td>
                        </tr>
                        
                         <tr id="colorrow1">
                            <th scope="row">
                                <label for="">
                                    <?php _e('Button Background Top', ATKP_PLUGIN_PREFIX) ?>:
                                </label> 
                            </th>
                            <td>
                                
                                <input disabled type="text" class="color-field" id="<?php echo ATKP_PLUGIN_PREFIX.'_btn_color_background_top' ?>" name="<?php echo ATKP_PLUGIN_PREFIX.'_btn_color_background_top' ?>" value=" <?php echo  get_option(ATKP_PLUGIN_PREFIX.'_btn_color_background_top', '#ffec64') ?>">
                            </td>
                        </tr>
                        
                        <tr id="colorrow2">
                            <th scope="row">
                                <label for="">
                                    <?php _e('Button Background Bottom', ATKP_PLUGIN_PREFIX) ?>:
                                </label> 
                            </th>
                            <td>
                                
                                <input disabled type="text" class="color-field" id="<?php echo ATKP_PLUGIN_PREFIX.'_btn_color_background_bottom' ?>" name="<?php echo ATKP_PLUGIN_PREFIX.'_btn_color_background_bottom' ?>" value=" <?php echo  get_option(ATKP_PLUGIN_PREFIX.'_btn_color_background_bottom', '#ffab23') ?>">
                            </td>
                        </tr>
                        
                        <tr id="colorrow3">
                            <th scope="row">
                                <label for="">
                                    <?php _e('Button Foreground', ATKP_PLUGIN_PREFIX) ?>:
                                </label> 
                            </th>
                            <td>
                                
                                <input disabled type="text" class="color-field" id="<?php echo ATKP_PLUGIN_PREFIX.'_btn_color_foreground' ?>" name="<?php echo ATKP_PLUGIN_PREFIX.'_btn_color_foreground' ?>" value=" <?php echo  get_option(ATKP_PLUGIN_PREFIX.'_btn_color_foreground', '#333333') ?>">
                            </td>
                        </tr>
                        
                        <tr id="colorrow4">
                            <th scope="row">
                                <label for="">
                                    <?php _e('Button Border', ATKP_PLUGIN_PREFIX) ?>:
                                </label> 
                            </th>
                            <td>
                                
                                <input disabled type="text" class="color-field" id="<?php echo ATKP_PLUGIN_PREFIX.'_btn_color_border' ?>" name="<?php echo ATKP_PLUGIN_PREFIX.'_btn_color_border' ?>" value=" <?php echo  get_option(ATKP_PLUGIN_PREFIX.'_btn_color_border', '#ffaa22') ?>">
                            </td>
                        </tr>
                        
                        
                        
                        
                        <script type="text/javascript">
     
                          var $j = jQuery.noConflict();
                          $j(document).ready(function() {
                              $j('.color-field').wpColorPicker();
                              
                              
                               $j('#<?php echo ATKP_PLUGIN_PREFIX.'_buttonstyle' ?>').change(function () {
                                    $j('#<?php echo 'colorrow1' ?>').hide();
                                    $j('#<?php echo 'colorrow2' ?>').hide();
                                    $j('#<?php echo 'colorrow3' ?>').hide();
                                    $j('#<?php echo 'colorrow4' ?>').hide();
                                   
                                    switch($j('#<?php echo ATKP_PLUGIN_PREFIX.'_buttonstyle' ?>').val()) {
                                         case '2':
                                             $j('#<?php echo 'colorrow1' ?>').show();
                                            $j('#<?php echo 'colorrow2' ?>').show();
                                            $j('#<?php echo 'colorrow3' ?>').show();
                                            $j('#<?php echo 'colorrow4' ?>').show();
                                             break;
                                         case '11':
                                             $j('#<?php echo 'colorrow1' ?>').show();   
                                              $j('#<?php echo 'colorrow3' ?>').show();
                                             break;
                                        
                                        
                                        
                                    }
                                    
                                    
                               });
                              
                             $j('#<?php echo ATKP_PLUGIN_PREFIX.'_buttonstyle' ?>').trigger("change");  
                          });
                          
                          
                          
                          
                          </script>
                        
                        
                      
                        
                        <tr valign="top">
                            <th scope="row">                      
                            </th>
                            <td>
                                <?php submit_button('', 'primary', 'savedisplay', false); ?>
                            </td>
                        </tr>
                    </table>
                </form>    
            </div> <?php
        }
	}
?>