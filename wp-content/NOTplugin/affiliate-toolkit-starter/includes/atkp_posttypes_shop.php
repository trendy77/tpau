<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    class atkp_posttypes_shop
    {   
        /**
         * Construct the plugin object
         */
        public function __construct($pluginbase)
        {
            $this->register_shopPostType();
            
            add_action( 'add_meta_boxes', array(&$this, 'list_boxes' ));
            add_action( 'save_post', array(&$this, 'list_detail_save' ));
            
        ATKPTools::add_column(ATKP_SHOP_POSTTYPE, __('Status', ATKP_PLUGIN_PREFIX), function($post_id){
                
                $error = ATKPTools::get_post_setting($post_id, ATKP_SHOP_POSTTYPE.'_access_message');
                
                if ($error == null || empty($error)) {
                    echo '<span style="color:green">' . __('Connected', ATKP_PLUGIN_PREFIX) . '</span>';
                } else {                   
                    echo '<span style="color:red">' . __('Disconnected', ATKP_PLUGIN_PREFIX) . ' ('.$error . ')</span>';
                }       
            }, 2);
        }
        
        
        function register_shopPostType() {
  $labels = array(
    'name'               => __( 'Shops', ATKP_PLUGIN_PREFIX ),
    'singular_name'      => __( 'Shop', ATKP_PLUGIN_PREFIX ),    
    'add_new_item'       => __( 'Add New Shop', ATKP_PLUGIN_PREFIX ),
    'edit_item'          => __( 'Edit Shop' , ATKP_PLUGIN_PREFIX),
    'new_item'           => __( 'New Shop' , ATKP_PLUGIN_PREFIX),
    'all_items'          => __( 'Shops' , ATKP_PLUGIN_PREFIX),
    'view_item'          => __( 'View Shop' , ATKP_PLUGIN_PREFIX),
    'search_items'       => __( 'Search Shops' , ATKP_PLUGIN_PREFIX),
    'not_found'          => __( 'No lists found' , ATKP_PLUGIN_PREFIX),
    'not_found_in_trash' => __( 'No lists found in the Trash' , ATKP_PLUGIN_PREFIX), 
    'parent_item_colon'  => '',
    'menu_name'          => __( 'Shops' , ATKP_PLUGIN_PREFIX),
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Holds our Shop',
    
    'public' => false,  // it's not public, it shouldn't have it's own permalink, and so on
    'publicly_queriable' => true,  // you should be able to query it
    'show_ui' => true,  // you should be able to edit it in wp-admin
    'exclude_from_search' => true,  // you should exclude it from search results
    'show_in_nav_menus' => false,  // you shouldn't be able to add it to menus
    'has_archive' => false,  // it shouldn't have archive page
    'rewrite' => false,  // it shouldn't have rewrite rules
    
	'capability_type' => 'page',
	
    'menu_position' => 200,
    'supports'      => array( 'title' ),
    'show_in_menu' => ATKP_PLUGIN_PREFIX.'_affiliate_toolkit-plugin',
  );
  register_post_type(ATKP_SHOP_POSTTYPE, $args );         
  }

function list_boxes() {
    
    add_meta_box( 
        ATKP_SHOP_POSTTYPE.'_detail_box',
        __( 'Shop Information', ATKP_PLUGIN_PREFIX),
        array(&$this, 'list_detail_box_content'),
        ATKP_SHOP_POSTTYPE,
        'normal',
        'default'
    );
    
}

function list_detail_box_content( $post ) {
    wp_nonce_field( plugin_basename( __FILE__ ), 'shop_detail_box_content_nonce' ); 

    require_once ATKP_PLUGIN_DIR.'/includes/shopproviders/atkp_shop_provider_base.php';

    $selwebservice = ATKPTools::get_post_setting($post->ID, ATKP_SHOP_POSTTYPE.'_access_webservice');

    $error = ATKPTools::get_post_setting($post->ID, ATKP_SHOP_POSTTYPE.'_access_message');
    
    if($selwebservice == '') {
        $error =     __('credentials empty', ATKP_PLUGIN_PREFIX);
    }
    
    if (($error == null || empty($error))) {
        $access_test = '<span style="color:green">' . __('Connected', ATKP_PLUGIN_PREFIX) . '</span>';
    } else {        
        $access_test = '<span style="color:red">' . __('Disconnected', ATKP_PLUGIN_PREFIX) . ' ('.$error . ')</span>';
    }       
    

  ?>  
   <table class="form-table">
    <tr valign="top">
                            <th scope="row">
                                <label for="">
                                    <?php _e('Data distributor', ATKP_PLUGIN_PREFIX) ?>:
                                </label> 
                            </th>
                            <td>
                                <select name="<?php echo ATKP_SHOP_POSTTYPE.'_access_webservice' ?>" id="<?php echo ATKP_SHOP_POSTTYPE.'_access_webservice' ?>">
                                <?php
         
                                 $locations = atkp_shop_provider_base::retrieve_providers();
                                
                                
                                foreach ($locations as $value => $provider) {
                                    if ($value == $selwebservice) 
                                        $sel = ' selected'; 
                                    else 
                                        $sel = '';
                                    
                                                                
                                    echo '<option value="' . $value . '"' . $sel . '>' . $provider->get_caption() . '</option>';
                                } ?>
                                </select>
                            </td>
                        </tr>                        
   
    <tr valign="top">
                            <th scope="row">
                                <label for="">
                                    <?php _e('Status', ATKP_PLUGIN_PREFIX) ?>:
                                </label> 
                            </th>
                            <td>
                                <?php echo $access_test; ?>
                            </td>
                        </tr>
                        </table>
                        <?php
                        foreach ($locations as $value => $provider) {
                        
                            echo '<div id="api-'.$value.'">';
                            echo '<table class="form-table">';
                            echo $provider->get_configuration($post);
                            echo '</table>';
                            echo '</div>';
                        }
                        
                        ?>                        
                        <table class="form-table">
                                             
                        
                        <tr valign="top">
                            <th scope="row">
                                
                            </th>
                            <td>
                                <input disabled type="checkbox" id="<?php echo ATKP_SHOP_POSTTYPE.'_displayshoplogo' ?>" name="<?php echo ATKP_SHOP_POSTTYPE.'_displayshoplogo' ?>" value="1" <?php echo checked(1, ATKPTools::get_post_setting($post->ID, ATKP_SHOP_POSTTYPE.'_displayshoplogo'), true); ?>>
                                 <label for="<?php echo ATKP_SHOP_POSTTYPE.'_displayshoplogo' ?>">
                                    <?php _e('Display Shop Logo', ATKP_PLUGIN_PREFIX) ?>
                                </label>
                            </td>
                        </tr>
                        
                        <tr valign="top">
                            <th scope="row">
                                
                            </th>
                            <td>
                                <input disabled type="checkbox" id="<?php echo ATKP_SHOP_POSTTYPE.'_enableofferload' ?>" name="<?php echo ATKP_SHOP_POSTTYPE.'_enableofferload' ?>" value="1" <?php echo checked(1, ATKPTools::get_post_setting($post->ID, ATKP_SHOP_POSTTYPE.'_enableofferload'), true); ?>>
                                 <label for="<?php echo ATKP_SHOP_POSTTYPE.'_enableofferload' ?>">
                                    <?php _e('Enabled for Offer Autoload', ATKP_PLUGIN_PREFIX) ?>
                                </label>
                            </td>
                        </tr>
                        
                        
                        <tr valign="top">
                            <th scope="row">
                                <label for="">
                                    <?php _e('Buy at Button', ATKP_PLUGIN_PREFIX) ?>:
                                </label> 
                            </th>
                            <td>
                                <input  style="width:30%" type="text" id="<?php echo ATKP_SHOP_POSTTYPE.'_text_buyat' ?>" name="<?php echo ATKP_SHOP_POSTTYPE.'_text_buyat' ?>" value="<?php echo esc_attr(ATKPTools::get_post_setting($post->ID, ATKP_SHOP_POSTTYPE.'_text_buyat')); ?>">
                            </td>
                        </tr>
                        
                        <tr valign="top">
                            <th scope="row">
                                <label for="">
                                    <?php _e('Add to Cart Button', ATKP_PLUGIN_PREFIX) ?>:
                                </label> 
                            </th>
                            <td>
                                <input  style="width:30%" type="text" id="<?php echo ATKP_SHOP_POSTTYPE.'_text_addtocart' ?>" name="<?php echo ATKP_SHOP_POSTTYPE.'_text_addtocart' ?>" value="<?php echo esc_attr(ATKPTools::get_post_setting($post->ID, ATKP_SHOP_POSTTYPE.'_text_addtocart')); ?>">
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="">
                                    <?php _e('Currency sign', ATKP_PLUGIN_PREFIX) ?>:
                                </label> 
                            </th>
                            <td>
                            <select disabled id="<?php echo ATKP_SHOP_POSTTYPE.'_currencysign' ?>" name="<?php echo ATKP_SHOP_POSTTYPE.'_currencysign' ?>" style="width:300px">
                                <?php
                                $selected = ATKPTools::get_post_setting($post->ID, ATKP_SHOP_POSTTYPE.'_currencysign');
                                                         
                                echo '<option value="1" '.($selected == 1 ? 'selected' : '').' >'.__('&euro; sign', ATKP_PLUGIN_PREFIX).'</option>';
                                 
                                echo '<option value="2" '.($selected == 2 ? 'selected' : '').'>'.__('EUR', ATKP_PLUGIN_PREFIX).'</option>';      
                                
                                echo '<option value="3" '.($selected == 3 ? 'selected' : '').'>'.__('&#36; sign', ATKP_PLUGIN_PREFIX).'</option>';   
                                
                                echo '<option value="4" '.($selected == 4 ? 'selected' : '').'>'.__('USD', ATKP_PLUGIN_PREFIX).'</option>';   
                               
                               echo '<option value="5" '.($selected == '' || $selected == 5 ? 'selected' : '').'>'.__('Default', ATKP_PLUGIN_PREFIX).'</option>';   
                               echo '<option value="6" '.($selected == 6 ? 'selected' : '').'>'.__('Userdefined', ATKP_PLUGIN_PREFIX).'</option>';   
                                 
                             ?>
                             
</select>                   <div id="customcurrencysign">

                            </div>
                             </td>
                        </tr>
                         </table>
                        
                        <table class="form-table">
                        <tr>
                        <td colspan="2" style="text-align:center">
						
						<?php ATKPHomeLinks::echo_banner(); ?>
						
						</td>
                        </tr>
                        </table>
                        
                        
                        <script type="text/javascript">
                        var $j = jQuery.noConflict();
                        /*
                         * Attaches the image uploader to the input field
                         */
                        $j(document).ready(function($){
                         
                         
                         $j('#<?php echo ATKP_SHOP_POSTTYPE.'_currencysign' ?>').change(function () {
                                 
                            if($j('#<?php echo ATKP_SHOP_POSTTYPE.'_currencysign' ?>').val() == '6')
                                 $j('#customcurrencysign').show();
                            else
                                 $j('#customcurrencysign').hide();
                         });
                         
                         $j('#<?php echo ATKP_SHOP_POSTTYPE.'_currencysign' ?>').trigger("change");
                         
                            $j('#<?php echo ATKP_SHOP_POSTTYPE.'_access_webservice' ?>').change(function () {
                                    
                                    switch($j('#<?php echo ATKP_SHOP_POSTTYPE.'_access_webservice' ?>').val()) {
                                        case '1':
                                            $j('#api-2').hide();
                                            $j('#api-1').show();
                                            $j('#api-4').hide();
                                            $j('#api-3').hide();
                                        break;
                                        case '2':
                                            $j('#api-2').show();
                                            $j('#api-1').hide();
                                            $j('#api-4').hide();
                                            $j('#api-3').hide();
                                             break;
                                        case '3':
                                            $j('#api-2').hide();
                                            $j('#api-1').hide(); 
                                            $j('#api-4').hide();
                                            $j('#api-3').show();
                                            break;
                                      case '4':
                                            $j('#api-2').hide();
                                            $j('#api-1').hide(); 
                                            $j('#api-3').hide();
                                            $j('#api-4').show();
                                            break;
                                        
                                    }
                                        
                                         
                                
                                        
                                        
                            });
                            
                             $j('#<?php echo ATKP_SHOP_POSTTYPE.'_access_webservice' ?>').trigger("change");
                         
                          
                        });
                        
                        </script>
  
  <?php 
}

	function list_detail_save( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
				return;
		
		$posttype =  ATKPTools::get_post_parameter('post_type', 'string');

		if (ATKP_SHOP_POSTTYPE != $posttype ) {
			return;
		}
		
		$nounce =  ATKPTools::get_post_parameter('shop_detail_box_content_nonce', 'string');
	  
		if(!wp_verify_nonce($nounce, plugin_basename( __FILE__ ) ) )
			return;
		
	   //speichern der einstellungen
					

		require_once ATKP_PLUGIN_DIR.'/includes/shopproviders/atkp_shop_provider_base.php';
		
		$webservice = ATKPTools::get_post_parameter(ATKP_SHOP_POSTTYPE.'_access_webservice', 'string');
		
		ATKPTools::set_post_setting( $post_id, ATKP_SHOP_POSTTYPE.'_access_webservice', $webservice);

		$myprovider = atkp_shop_provider_base::retrieve_provider($webservice);
									
		if($myprovider == null)
			throw new Exception('provider not found: ' + $webservice);
				
		ATKPTools::set_post_setting( $post_id, ATKP_SHOP_POSTTYPE.'_displayshoplogo', ATKPTools::get_post_parameter(ATKP_SHOP_POSTTYPE.'_displayshoplogo', 'bool'));
		ATKPTools::set_post_setting( $post_id, ATKP_SHOP_POSTTYPE.'_enableofferload', ATKPTools::get_post_parameter(ATKP_SHOP_POSTTYPE.'_enableofferload', 'bool'));
		
		$buyattext = ATKPTools::get_post_parameter(ATKP_SHOP_POSTTYPE.'_text_buyat', 'string');
		$addtocarttext = ATKPTools::get_post_parameter(ATKP_SHOP_POSTTYPE.'_text_addtocart', 'string');
				
		if($buyattext == null || $buyattext == '') 
			$buyattext = $myprovider->get_defaultbtn1_text();
		if($addtocarttext == null || $addtocarttext == '') 
			$addtocarttext = $myprovider->get_defaultbtn2_text();
		
		ATKPTools::set_post_setting( $post_id, ATKP_SHOP_POSTTYPE.'_text_buyat', $buyattext);
		ATKPTools::set_post_setting( $post_id, ATKP_SHOP_POSTTYPE.'_text_addtocart', $addtocarttext);


		$myprovider->set_configuration($post_id);
		
		$message = $myprovider->check_configuration($post_id);
	   
		ATKPTools::set_post_setting( $post_id, ATKP_SHOP_POSTTYPE.'_access_message', $message);    
				  

		
			

		
	}

}
    
?>