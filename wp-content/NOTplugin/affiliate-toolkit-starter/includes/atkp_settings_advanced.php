<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    class atkp_settings_advanced
    {
        /**
         * Construct the plugin object
         */
        public function __construct($pluginbase)
        {
            
            
        }
    

        public function advanced_configuration_page()
        {        
            if (ATKPTools::exists_post_parameter('saveadvanced') && check_admin_referer('save', 'save')) {
                if (!current_user_can('manage_options')) {
                    wp_die(__('You do not have sufficient permissions to access this page', ATKP_PLUGIN_PREFIX));
                }
                
                //speichern der einstellungen
                update_option(ATKP_PLUGIN_PREFIX.'_mark_links', ATKPTools::get_post_parameter(ATKP_PLUGIN_PREFIX.'_mark_links', 'bool'));
                update_option(ATKP_PLUGIN_PREFIX.'_show_disclaimer', ATKPTools::get_post_parameter(ATKP_PLUGIN_PREFIX.'_show_disclaimer', 'bool'));
                update_option(ATKP_PLUGIN_PREFIX.'_disclaimer_text', ATKPTools::get_post_parameter(ATKP_PLUGIN_PREFIX.'_disclaimer_text', 'html'));
                update_option(ATKP_PLUGIN_PREFIX.'_add_to_cart', ATKPTools::get_post_parameter(ATKP_PLUGIN_PREFIX.'_add_to_cart', 'bool'));
                update_option(ATKP_PLUGIN_PREFIX.'_open_window', ATKPTools::get_post_parameter(ATKP_PLUGIN_PREFIX.'_open_window', 'bool'));
                update_option(ATKP_PLUGIN_PREFIX.'_loglevel', ATKPTools::get_post_parameter(ATKP_PLUGIN_PREFIX.'_loglevel', 'string'));
                
                
                
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
                                <?php _e('Disclaimer settings', ATKP_PLUGIN_PREFIX) ?>
                            </th>
                        </tr>
                        
                        
                        <tr valign="top">
                            <th scope="row">
                               
                            </th>
                            <td>
                                <input type="checkbox" id="<?php echo ATKP_PLUGIN_PREFIX.'_show_disclaimer' ?>" name="<?php echo ATKP_PLUGIN_PREFIX.'_show_disclaimer' ?>" value="1" <?php echo checked(1, get_option(ATKP_PLUGIN_PREFIX.'_show_disclaimer'), true); ?>>
                             <label for="<?php echo ATKP_PLUGIN_PREFIX.'_show_disclaimer' ?>">
                                    <?php _e('Show disclaimer', ATKP_PLUGIN_PREFIX) ?>
                                </label> <br />
                                <label for="">
                                    <?php _e('Amazon advised to include a disclaimer adjacent to the pricing or availability information.', ATKP_PLUGIN_PREFIX) ?>
                                </label> 
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="">
                                    <?php _e('Disclaimer text', ATKP_PLUGIN_PREFIX) ?>:
                                </label> 
                            </th>
                            <td>
                                
                                <?php
                                wp_editor(get_option(ATKP_PLUGIN_PREFIX.'_disclaimer_text', stripslashes(__('Last updated on %refresh_date% at %refresh_time% - Image source: Amazon Affiliate Program. All statements without guarantee.', ATKP_PLUGIN_PREFIX))), ATKP_PLUGIN_PREFIX.'_dislaimer_text', array(
                                    'media_buttons' => false,
                                    'textarea_name' => ATKP_PLUGIN_PREFIX.'_disclaimer_text',
                                    'textarea_rows' => 7,
                                ));
                                ?>
                            </td>
                        </tr>
                                           <tr>
                        <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr valign="top">
                            <th scope="row" style="background-color:gainsboro; padding:7px" colspan="2">
                                <?php _e('Links & Buttons', ATKP_PLUGIN_PREFIX) ?>
                            </th>
                        </tr>
                        
     
                        
                        <tr valign="top">
                            <th scope="row">
                               
                            </th>
                            <td>
                                <input type="checkbox" id="<?php echo ATKP_PLUGIN_PREFIX.'_add_to_cart' ?>" name="<?php echo ATKP_PLUGIN_PREFIX.'_add_to_cart' ?>" value="1" <?php echo checked(1, get_option(ATKP_PLUGIN_PREFIX.'_add_to_cart'), true); ?>>
                            
                             <label for="<?php echo ATKP_PLUGIN_PREFIX.'_add_to_cart' ?>">
                                    <?php _e('Enable "add to cart"', ATKP_PLUGIN_PREFIX) ?>
                                </label> 
                                </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                
                            </th>
                            <td>
                                <input type="checkbox" id="<?php echo ATKP_PLUGIN_PREFIX.'_mark_links' ?>" name="<?php echo ATKP_PLUGIN_PREFIX.'_mark_links' ?>" value="1" <?php echo checked(1, get_option(ATKP_PLUGIN_PREFIX.'_mark_links', 1), true); ?>>
                                <label for="<?php echo ATKP_PLUGIN_PREFIX.'_mark_links' ?>">
                                    <?php _e('Mark links (*)', ATKP_PLUGIN_PREFIX) ?>
                                </label> 
                                <label for=""><?php echo ATKPHomeLinks::ReplaceLinkType(__('<a href="%link_mark-affiliate-links%" target="_blank">More information</a>', ATKP_PLUGIN_PREFIX)) ?>
                            </td>
                        </tr>
                         <tr valign="top">
                            <th scope="row">
                                
                            </th>
                            <td>
                                <input type="checkbox" id="<?php echo ATKP_PLUGIN_PREFIX.'_open_window' ?>" name="<?php echo ATKP_PLUGIN_PREFIX.'_open_window' ?>" value="1" <?php echo checked(1, get_option(ATKP_PLUGIN_PREFIX.'_open_window', 1), true); ?>>
                                <label for="<?php echo ATKP_PLUGIN_PREFIX.'_open_window' ?>">
                                    <?php _e('Open links in new window/tab', ATKP_PLUGIN_PREFIX) ?>
                                </label> 
                               
                            </td>
                        </tr>                        
                        <tr>
                        <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr valign="top">
                            <th scope="row" style="background-color:gainsboro; padding:7px" colspan="2">
                                <?php _e('Logfile', ATKP_PLUGIN_PREFIX) ?>
                            </th>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="">
                                    <?php _e('Log Level', ATKP_PLUGIN_PREFIX) ?>:
                                </label> 
                            </th>
                            <td>
                            <select  id="<?php echo ATKP_PLUGIN_PREFIX.'_loglevel' ?>" name="<?php echo ATKP_PLUGIN_PREFIX.'_loglevel' ?>" style="width:300px">
                                <?php
                                $selected = get_option(ATKP_PLUGIN_PREFIX.'_loglevel');
                                                         
                                echo '<option value="off" '.($selected == '' || $selected == 'off' ? 'selected' : '').' >'.__('OFF', ATKP_PLUGIN_PREFIX).'</option>';
                                 
                                echo '<option value="debug" '.($selected == 'debug' ? 'selected' : '').'>'.__('DEBUG', ATKP_PLUGIN_PREFIX).'</option>';      
                                
                                echo '<option value="error" '.($selected == 'error' ? 'selected' : '').'>'.__('ERROR', ATKP_PLUGIN_PREFIX).'</option>';   
                                
                                
                             ?>
</select>                   
                             </td>
                        </tr>
                         
                        
                        <tr valign="top">
                            <th scope="row">                      
                            </th>
                            <td>
                                <?php submit_button('', 'primary', 'saveadvanced', false); ?>
                            </td>
                        </tr>
                    </table>
                </form>    
            </div> <?php
        }
}
?>