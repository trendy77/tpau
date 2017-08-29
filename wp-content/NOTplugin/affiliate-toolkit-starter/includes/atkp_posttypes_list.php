<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    class atkp_posttypes_list
    {   
        /**
         * Construct the plugin object
         */
        public function __construct($pluginbase)
        {
            $this->register_listPostType();
            
            add_action( 'add_meta_boxes', array(&$this, 'list_boxes' ));
            add_action( 'save_post', array(&$this, 'list_detail_save' ));
            
             ATKPTools::add_column(ATKP_LIST_POSTTYPE, __('Message', ATKP_PLUGIN_PREFIX), function($post_id){
                
                $message = ATKPTools::get_post_setting($post_id, ATKP_LIST_POSTTYPE.'_message', true );
                
                if(isset($message) && $message != '') {
					//info: message ist html!
                                echo '<span style="color:red">'.$message.'</span>';
                }
            }, 2);
            
            ATKPTools::add_column(ATKP_LIST_POSTTYPE, __('Updated on', ATKP_PLUGIN_PREFIX), function($post_id){
                
                $updatedon = ATKPTools::get_post_setting($post_id, ATKP_LIST_POSTTYPE.'_updatedon', true );
                
                if(isset($updatedon) && $updatedon != '') {
                                    $infotext = __('%refresh_date% at %refresh_time%', ATKP_PLUGIN_PREFIX);
                                    
                                    $infotext = str_replace('%refresh_date%',  date_i18n( get_option( 'date_format' ), $updatedon), $infotext);
                                    $infotext = str_replace('%refresh_time%',  date_i18n( get_option( 'time_format' ), $updatedon), $infotext);
                                    
                                    
                                echo esc_attr($infotext);
                }
            }, 2);

           
        }
        
        function register_listPostType() {
  $labels = array(
    'name'               => __( 'Lists', ATKP_PLUGIN_PREFIX ),
    'singular_name'      => __( 'List', ATKP_PLUGIN_PREFIX ),
    'add_new_item'       => __( 'Add New List', ATKP_PLUGIN_PREFIX ),
    'edit_item'          => __( 'Edit List' , ATKP_PLUGIN_PREFIX),
    'new_item'           => __( 'New List' , ATKP_PLUGIN_PREFIX),
    'all_items'          => __( 'Lists' , ATKP_PLUGIN_PREFIX),
    'view_item'          => __( 'View List' , ATKP_PLUGIN_PREFIX),
    'search_items'       => __( 'Search Lists' , ATKP_PLUGIN_PREFIX),
    'not_found'          => __( 'No lists found' , ATKP_PLUGIN_PREFIX),
    'not_found_in_trash' => __( 'No lists found in the Trash' , ATKP_PLUGIN_PREFIX), 
    'parent_item_colon'  => '',
    'menu_name'          => __( 'Lists' , ATKP_PLUGIN_PREFIX),
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Holds our lists',
   
    'public' => false,  // it's not public, it shouldn't have it's own permalink, and so on
    'publicly_queriable' => true,  // you should be able to query it
    'show_ui' => true,  // you should be able to edit it in wp-admin
    'exclude_from_search' => true,  // you should exclude it from search results
    'show_in_nav_menus' => false,  // you shouldn't be able to add it to menus
    'has_archive' => false,  // it shouldn't have archive page
    'rewrite' => false,  // it shouldn't have rewrite rules
   
    'supports'      => array( 'title' ),
   
    'capability_type' => 'post',
    'menu_position' => 20,
  );
  register_post_type(ATKP_LIST_POSTTYPE, $args );         
  }

function list_boxes() {
    add_meta_box( 
        ATKP_LIST_POSTTYPE.'_shop_box',
        __( 'Shop Information', ATKP_PLUGIN_PREFIX),
        array(&$this, 'list_shop_box_content'),
        ATKP_LIST_POSTTYPE,
        'normal',
        'default'
    );
        
    add_meta_box( 
        ATKP_LIST_POSTTYPE.'_detail_box',
        __( 'List Information', ATKP_PLUGIN_PREFIX),
        array(&$this, 'list_detail_box_content'),
        ATKP_LIST_POSTTYPE,
        'normal',
        'default'
    );
    
    add_meta_box( 
        ATKP_LIST_POSTTYPE.'_preview_box',
        __( 'List Preview', ATKP_PLUGIN_PREFIX),
        array(&$this, 'list_preview_box_content'),
        ATKP_LIST_POSTTYPE,
        'normal',
        'default'
    );

}

function list_shop_box_content( $post ) {
  wp_nonce_field( plugin_basename( __FILE__ ), 'list_shop_box_content_nonce' ); 
  
  require_once ATKP_PLUGIN_DIR.'/includes/shopproviders/atkp_shop_provider_base.php';
  
  ?>  
   <table class="form-table">
   <tr valign="top">
                            <th scope="row">
                                <label for="">
                                    <?php _e('Shop', ATKP_PLUGIN_PREFIX) ?>:
                                </label> 
                            </th>
                            <td>
                            <select id="<?php echo ATKP_LIST_POSTTYPE.'_shopid' ?>" name="<?php echo ATKP_LIST_POSTTYPE.'_shopid' ?>" style="width:300px">                            
                                <?php
                                $selectedshopid = ATKPTools::get_post_setting($post->ID, ATKP_LIST_POSTTYPE.'_shopid', 0); 
                                
                                
                                global $post;
$args = array( 'post_type' => ATKP_SHOP_POSTTYPE, 'posts_per_page'   => 300, 'post_status'      => 'publish');
$posts_array = get_posts($args);
foreach ( $posts_array as $prd ) { 

                                    
                                 $webservice = ATKPTools::get_post_setting( $prd->ID, ATKP_SHOP_POSTTYPE.'_access_webservice');

                           $myprovider = atkp_shop_provider_base::retrieve_provider($webservice);
                                    
                                    if(isset($myprovider)) {       
                                        
                                        $subshops = $myprovider->get_shops($prd->ID);
                                                                          
                                        foreach ($subshops  as $subshop ) { 
                                        if ($selectedshopid == $subshop->shopid) 
                                            $sel = ' selected'; 
                                        else 
                                            $sel = '';
                                                                                
                                   
                                                    //10 => __('Category - Best Seller', ATKP_PLUGIN_PREFIX),
                                                    //11 => __('Category - New Releases (US locale only)', ATKP_PLUGIN_PREFIX),
                                                    //20 => __('Search', ATKP_PLUGIN_PREFIX),                                                   
                                                    //30 => __('Extended Search', ATKP_PLUGIN_PREFIX),
                                                    //40 => __('Similarity - Find similar products', ATKP_PLUGIN_PREFIX),
                                   
                                            $datasources = $myprovider->get_supportedlistsources();
                                   
                                        if($datasources != '')
                                            echo '<option data-sources="'.$datasources.'" value="' .$subshop->shopid . '"' . $sel . ' > ' . esc_attr(  $prd->post_title .' > '.$subshop->title.' ('.$prd->ID.')') . '</option>';
                                            
                                        }
                                    }
 }; ?>
</select>
                                
                               
                            
                             </td>
                        </tr>
                       
                        <?php
                        
                       
     $updatedon = ATKPTools::get_post_setting($post->ID, ATKP_LIST_POSTTYPE.'_updatedon', true );
    $message = ATKPTools::get_post_setting($post->ID, ATKP_LIST_POSTTYPE.'_message', true );

                        ?>
                        
                            <tr valign="top">
                            
                            <td colspan="2"><i>
                                <?php 
                                if(isset($updatedon) && $updatedon != '') {
                                    $infotext = __('List updated on %refresh_date% at %refresh_time%', ATKP_PLUGIN_PREFIX);
                                    
                                    $infotext = str_replace('%refresh_date%',  date_i18n( get_option( 'date_format' ), $updatedon), $infotext);
                                    $infotext = str_replace('%refresh_time%',  date_i18n( get_option( 'time_format' ), $updatedon), $infotext);
                                    
                                    
                                echo esc_attr($infotext); ?><br /><?php } //info: message ist html! ?>
								
                                <?php echo  '<span style="color:red;">'.substr($message,0, 300).'</span>'; ?>
                            </i></td>
                        </tr>
   </table>
   
   
   <div id="modal-browsenode-lookup" style="display:none;">
   
   <div class="atkp-lookupbox"> 
    <p><label for=""><?php _e('Keyword:', ATKP_PLUGIN_PREFIX) ?></label> <input type="text" id="<?php echo ATKP_LIST_POSTTYPE.'_nodelookupsearch' ?>" name="<?php echo ATKP_LIST_POSTTYPE.'_nodelookupsearch' ?>" value="">  <input type="submit" class="button" id="<?php echo ATKP_LIST_POSTTYPE.'_nodelookupbtnsearch' ?>" value="<?php _e('Search', ATKP_PLUGIN_PREFIX) ?>" >
    <div id="LoadingImageLookup" style="display: none;text-align:center"><img src="<?php echo plugin_dir_url( ATKP_PLUGIN_FILE ) ?>/images/spin.gif" style="width:32px" alt="loading" /></div>
    </p>
    
    <div id="<?php echo ATKP_LIST_POSTTYPE.'_nodelookupresult' ?>">
    
    </div>
    

    
		</div>
</div>
   
   <script type="text/javascript">
   var $j = jQuery.noConflict();
      $j(document).ready(function() {
          
           $j(<?php echo ATKP_LIST_POSTTYPE.'_nodelookupbtnsearch' ?>).click(function(e) {
          
          $j("#<?php echo ATKP_LIST_POSTTYPE.'_nodelookupresult' ?>").html('');
          $j("#LoadingImageLookup").show();
          
          $j.ajax({
              type: "POST",
              url: "<?php echo ATKPTools::get_endpointurl(); ?>",
              data: { action: "atkp_search_browsenodes", shop: $j('#<?php echo ATKP_LIST_POSTTYPE.'_shopid' ?>').val(), keyword: $j('#<?php echo ATKP_LIST_POSTTYPE.'_nodelookupsearch' ?>').val(), request_nonce:"<?php echo wp_create_nonce('atkp-search-nonce') ?>" },
              
              dataType: "json",
              success : function(data) {
                  
                   if(data.length > 0) {                              
                          if(typeof data[0].error != 'undefined')
                          {
                              $j("#<?php echo ATKP_PRODUCT_POSTTYPE.'_nodelookupresult' ?>").html('<span style="color:red">' + data[0].error + '<br /> '+ data[0].message+'</span>');
                        
                          }      
                      } else {
                                  var outputresult = '<ul class="node-link">';     
               
                        $j.each(data, function(key, value) {
                            outputresult +='<li>';
                               outputresult += '<h3 data-id='+key+'>'+value+'</h3>';         
                                outputresult += '<p>BrowseNode: '+key+' </p>';
                                 outputresult +='</li>';
                        });   
                        outputresult += '</ul>';  
                         
                            $j("#<?php echo ATKP_LIST_POSTTYPE.'_nodelookupresult' ?>").html(outputresult);
                       
                        
                  
                      $j('ul.node-link li h3').click(function(e) 
                        { 
                            var id = $j(this).attr("data-id");
                            $j("#<?php echo ATKP_LIST_POSTTYPE.'_node_id' ?>").val(id);
                            $j("#<?php echo ATKP_LIST_POSTTYPE.'_node_id' ?>").trigger('change');
                        tb_remove();
                        });
                  }
                    $j("#LoadingImageLookup").hide();
                },
                  error: function (xhr, status) {  
                    $j("#<?php echo ATKP_LIST_POSTTYPE.'_nodelookupresult' ?>").html('<span style="color:red">' + xhr.responseText + '</span>');
                    $j("#LoadingImageLookup").hide();
                  }    
            });
      });
        $j('#<?php echo ATKP_LIST_POSTTYPE.'_node_id' ?>').change(function(){
      
      $j('#<?php echo ATKP_LIST_POSTTYPE.'_node_caption' ?>').empty();
        });
          
          
            var loadeddepartments;
            var loadedfilters;
          
            $j('#<?php echo ATKP_LIST_POSTTYPE.'_shopid' ?>').change(function(){
            
            if($j('#<?php echo ATKP_LIST_POSTTYPE.'_shopid' ?>').val() == '') {
                $j('#settings-1').hide();
                $j('#settings-2').show();
                
                $j('#<?php echo ATKP_LIST_POSTTYPE.'_listurl' ?>').prop('disabled', false);
            } else {
                $j('#settings-2').hide();
                $j('#settings-1').show();
                
                //
                
                var option = $j('option:selected', $j('#<?php echo ATKP_LIST_POSTTYPE.'_shopid' ?>')).attr('data-sources');
                var supportedsources = option.split(",");
                
                $j('#<?php echo ATKP_LIST_POSTTYPE.'_source' ?> option[value=10]').hide();
                $j('#<?php echo ATKP_LIST_POSTTYPE.'_source' ?> option[value=11]').hide();
                $j('#<?php echo ATKP_LIST_POSTTYPE.'_source' ?> option[value=20]').hide();
                $j('#<?php echo ATKP_LIST_POSTTYPE.'_source' ?> option[value=30]').hide();
                $j('#<?php echo ATKP_LIST_POSTTYPE.'_source' ?> option[value=40]').hide();
                
                var selectedval =  $j('#<?php echo ATKP_LIST_POSTTYPE.'_source' ?>').attr('selected-id');
                
                
                var isset = false;
                $j.each(supportedsources, function( index, value ) {
                  $j('#<?php echo ATKP_LIST_POSTTYPE.'_source' ?> option[value='+value+']').show();
                  
                  if(selectedval =='') {          
                      if(!isset) {
                        $j('#<?php echo ATKP_LIST_POSTTYPE.'_source' ?>').val(value).change();                    
                        
                        isset = true;   
                      }
                  }
                });
              
               if(selectedval !='')
                $j('#<?php echo ATKP_LIST_POSTTYPE.'_source' ?>').val(selectedval).change();   
                
                $j('#<?php echo ATKP_LIST_POSTTYPE.'_listurl' ?>').prop('disabled', true);
                            
                //load shop departments
                $j("#LoadingImage").show();
                $j("#LoadingImage2").show();
                loadeddepartments = null;
                loadedfilters = null;
                
                var searchdepbox = $j("#<?php echo ATKP_LIST_POSTTYPE.'_search_department' ?>");
                var searchorderbox = $j("#<?php echo ATKP_LIST_POSTTYPE.'_search_orderby' ?>");
                
            
                var selectedvalue = searchdepbox.val();
                
                
                  if(selectedvalue == null)
                      selectedvalue = searchdepbox.attr('data-value');
                      
                  searchdepbox.empty();
                  searchorderbox.empty();
                  
                  
      
                
                $j.ajax({
                      type: "POST",
                      url: "<?php echo ATKPTools::get_endpointurl(); ?>",
                       data: { action: "atkp_search_departments", shop: $j('#<?php echo ATKP_LIST_POSTTYPE.'_shopid' ?>').val(), request_nonce:"<?php echo wp_create_nonce('atkp-search-nonce') ?>" },
                      
                      dataType: "json",
                      success : function(data) {
                        if(data.length > 0) {                              
                              if(typeof data[0].error != 'undefined')
                              {
                                  alert(data[0].error+': '+data[0].message);
                              }      
                          } else {
                                             
                            $j.each(data, function(key, value) {
                                  searchdepbox.append($j('<option>', { 
                                        value: key,
                                        text : value.caption 
                                    }));                            
                            });   
                            
                            
                            loadeddepartments = data;
                                                                
                            searchdepbox.val(selectedvalue);
                            searchdepbox.trigger("change");
                          }
                          
                          $j("#LoadingImage").hide();
                        },
                          error: function (xhr, status) { 
                            $j("#LoadingImage").hide();
                          }    
                });
                
      
                
               
                
                $j.ajax({
                      type: "POST",
                      url: "<?php echo ATKPTools::get_endpointurl(); ?>",
                       data: { action: "atkp_search_filters", shop: $j('#<?php echo ATKP_LIST_POSTTYPE.'_shopid' ?>').val(), request_nonce:"<?php echo wp_create_nonce('atkp-search-nonce') ?>" },
                      
                      dataType: "json",
                      success : function(data) {
                        if(data.length > 0) {                              
                              if(typeof data[0].error != 'undefined')
                              {
                                  alert(data[0].error+': '+data[0].message);
                              }      
                          } else {
                             
                                 
                            var idx = 1;
                            while (idx <= 5) {
                            
                                var searchfilterfield  = $j("#<?php echo ATKP_LIST_POSTTYPE.'_filterfield' ?>"+idx);                                                
                                var selectedfiltervalue = searchfilterfield.attr('data-value');
                                  
                                searchfilterfield.empty();
                            
                                $j.each(data, function(key, value) {
                                     
                                    
                                      searchfilterfield.append($j('<option>', { 
                                            value: key,
                                            text : value 
                                        }));                            
                                });
                            
                                searchfilterfield.val(selectedfiltervalue);
                            
                                idx++;
                            }

                               
                            
                            
                            loadedfilters = data;
                                                                
                            
                          }
                          
                          $j("#LoadingImage").hide();
                           $j("#LoadingImage2").hide();
                        },
                          error: function (xhr, status) { 
                            $j("#LoadingImage").hide();
                             $j("#LoadingImage2").hide();
                          }    
                });
            }
        
        });
        
        $j('#<?php echo ATKP_LIST_POSTTYPE.'_shopid' ?>').trigger("change");
   
        $j('#<?php echo ATKP_LIST_POSTTYPE.'_search_department' ?>').change(function(){
            if(loadeddepartments == null)
                return;
            
            var searchdepbox = $j("#<?php echo ATKP_LIST_POSTTYPE.'_search_department' ?>");
            var searchorderbox = $j("#<?php echo ATKP_LIST_POSTTYPE.'_search_orderby' ?>");
            
            var selectedvalue = searchorderbox.val();
                          
              if(selectedvalue == null)
                  selectedvalue = searchorderbox.attr('data-value');
            
            searchorderbox.empty();
            
           searchorderbox.append($j('<option>', { 
                                        value: '',
                                        text : '<?php _e('no sort', ATKP_PLUGIN_PREFIX) ?>'
                                    }));        
            
                        
            $j.each(loadeddepartments, function(key, value) {
                  if(key == searchdepbox.val()) {
                      
                       //alert(JSON.stringify(value), null, 2);
                        
                        if(typeof value.sortvalues !== 'undefined') {
                            $j.each(value.sortvalues, function(key2, value2) {
                            searchorderbox.append($j('<option>', { 
                                            value: key2,
                                            text : value2
                                        }));        
                                        
                            });
                        }
                        
                        
                  }
            });   
            
            searchorderbox.val(selectedvalue);
            
            //alert(JSON.stringify(loadeddepartments, null, 2));
        });
        
   
   
        $j('.drop-down-show-hide').hide();
        $j('#div' + $j('#<?php echo ATKP_LIST_POSTTYPE.'_source' ?>').val().substring(0,1)).show();
                
        
        $j('#<?php echo ATKP_LIST_POSTTYPE.'_source' ?>').change(function () {
                 
            if($j('#<?php echo ATKP_LIST_POSTTYPE.'_source' ?>').val() == 20)
            {
                if($j('#<?php echo ATKP_LIST_POSTTYPE.'_search_department' ?>').val() == '')
                    $j('#<?php echo ATKP_LIST_POSTTYPE.'_search_department' ?>').val('All');
                
            } else if($j('#<?php echo ATKP_LIST_POSTTYPE.'_source' ?>').val().substring(0,1) == 2) {
                if($j('#<?php echo ATKP_LIST_POSTTYPE.'_search_department' ?>').val() == 'All')
                    $j('#<?php echo ATKP_LIST_POSTTYPE.'_search_department' ?>').val('');
            } 
            
            $j('.drop-down-show-hide').hide()
            $j('#div' + this.value.substring(0,1)).show();
        
        });
       
       
       
            $j('#btn-add').click(function(){
            $j('#select-from option:selected').each( function() {
                    $j('#<?php echo ATKP_LIST_POSTTYPE.'_products' ?>').append("<option value='"+$j(this).val()+"'>"+$j(this).text()+"</option>");
                $j(this).remove();
            });
        });
        $j('#btn-remove').click(function(){
            $j('#<?php echo ATKP_LIST_POSTTYPE.'_products' ?> option:selected').each( function() {
                $j('#select-from').append("<option value='"+$j(this).val()+"'>"+$j(this).text()+"</option>");
                $j(this).remove();
            });
        });
        $j('#btn-up').bind('click', function() {
            $j('#<?php echo ATKP_LIST_POSTTYPE.'_products' ?> option:selected').each( function() {
                var newPos = $j('#<?php echo ATKP_LIST_POSTTYPE.'_products' ?> option').index(this) - 1;
                if (newPos > -1) {
                    $j('#<?php echo ATKP_LIST_POSTTYPE.'_products' ?> option').eq(newPos).before("<option value='"+$j(this).val()+"' selected='selected'>"+$j(this).text()+"</option>");
                    $j(this).remove();
                }
            });
        });
        
        jQuery.fn.reverse = function() {
            return this.pushStack(this.get().reverse(), arguments);
        };
        
        $j('#btn-down').bind('click', function() {
            var countOptions = $j('#<?php echo ATKP_LIST_POSTTYPE.'_products' ?> option').size();
            $j('#<?php echo ATKP_LIST_POSTTYPE.'_products' ?> option:selected').reverse().each( function() {
                var newPos = $j('#<?php echo ATKP_LIST_POSTTYPE.'_products' ?> option').index(this) + 1;
                if (newPos < countOptions) {
                    $j('#<?php echo ATKP_LIST_POSTTYPE.'_products' ?> option').eq(newPos).after("<option value='"+$j(this).val()+"' selected='selected'>"+$j(this).text()+"</option>");
                    $j(this).remove();
                }
            });
        });
        
        $j( "#post" ).submit(function( event ) {
            
                 $j('#<?php echo ATKP_LIST_POSTTYPE.'_products' ?> option').each( function() {                 
                    $j(this).attr('selected', true);
                });
            });
      });
    </script>
   
   <?php
   
}

function list_detail_box_content( $post ) {
  wp_nonce_field( plugin_basename( __FILE__ ), 'list_detail_box_content_nonce' ); 
  

  
  ?>  

                        <table class="form-table" id="settings-1">                         
                        <tr valign="top">
                            <th scope="row">
                                <label for="">
                                    <?php _e('Source', ATKP_PLUGIN_PREFIX) ?>:
                                </label> 
                            </th>
                            <td>
                            <?php $selectedsourceval = ATKPTools::get_post_setting($post->ID, ATKP_LIST_POSTTYPE.'_source', 10); ?>
                            
                                <select selected-id="<?php echo $selectedsourceval ?>" name="<?php echo ATKP_LIST_POSTTYPE.'_source' ?>" id="<?php echo ATKP_LIST_POSTTYPE.'_source' ?>">
                                <?php
                                
                                $durations = array(
                                                    10 => __('Category - Best Seller', ATKP_PLUGIN_PREFIX),
                                                    20 => __('Search', ATKP_PLUGIN_PREFIX),                                                   
                                                 
                                                  );
                                
                                foreach ($durations as $value => $name) {
                                    if ($value == $selectedsourceval) 
                                        $sel = ' selected'; 
                                    else 
                                        $sel = '';
                                    
                                    $item_translated = '';
                                                                
                                    echo '<option value="' . $value . '"' . $sel . '>' . esc_attr($name) . '</option>';
                                } ?>
                                </select>
                            </td>
                        </tr>
                        
                        <tr valign="top">
                        <td></td>
                            <td  >
                                <table id="div1" class="drop-down-show-hide form-table"  style="display: none;">
                                <tr valign="top">
                            <th scope="row">
                                <label for="">
                                    <?php _e('BrowseNode', ATKP_PLUGIN_PREFIX) ?>:
                                </label> 
                            </th>
                            <td>
                                <input type="number" id="<?php echo ATKP_LIST_POSTTYPE.'_node_id' ?>" name="<?php echo ATKP_LIST_POSTTYPE.'_node_id' ?>" value="<?php echo  ATKPTools::get_post_setting($post->ID, ATKP_LIST_POSTTYPE.'_node_id', true ); ?>">                              
                                 <label id="<?php echo ATKP_LIST_POSTTYPE.'_node_caption' ?>"><?php echo  ATKPTools::get_post_setting($post->ID, ATKP_LIST_POSTTYPE.'_node_caption', true ); ?></label>    <br />                         
                                <input type="button" id="searchbrowsenode-button" class="button browsenode-lookup thickbox" title="<?php _e( 'Search BrowseNode', ATKP_PLUGIN_PREFIX)?>" alt="#TB_inline?height=400&amp;width=500&amp;inlineId=modal-browsenode-lookup" value="<?php _e( 'Search BrowseNode', ATKP_PLUGIN_PREFIX)?>" />
                            </td>
                        </tr>
                                
                                </table>
                                <table id="div2" class="drop-down-show-hide form-table"  style="display: none;">
 <tr valign="top">
                            <th scope="row">
                                <label for="">
                                     <?php _e('Department', ATKP_PLUGIN_PREFIX) ?>:
                                </label> 
                            </th>
                            <td>
                            <div id="LoadingImage" style="display: none"><img src="<?php echo plugin_dir_url( ATKP_PLUGIN_FILE ) ?>/images/spin.gif" style="width:32px" alt="loading" /></div>
                                <select style="width: 600px;" id="<?php echo ATKP_LIST_POSTTYPE.'_search_department' ?>" name="<?php echo ATKP_LIST_POSTTYPE.'_search_department' ?>" data-value="<?php echo  ATKPTools::get_post_setting($post->ID, ATKP_LIST_POSTTYPE.'_search_department', true); ?>" />                              
                            <?php echo ATKPHomeLinks::ReplaceLinkType(__('<a href="%link_get-amazon-search-department%" target="_blank">More information</a>', ATKP_PLUGIN_PREFIX)) ?>
                               </td>
                        </tr>
                        <th scope="row">
                                <label for="">
                                     <?php _e('Order by ', ATKP_PLUGIN_PREFIX) ?>:
                                </label> 
                            </th>
                            <td>
                            
                                <select disabled id="<?php echo ATKP_LIST_POSTTYPE.'_search_orderby' ?>" name="<?php echo ATKP_LIST_POSTTYPE.'_search_orderby' ?>" data-value="<?php echo  ATKPTools::get_post_setting($post->ID, ATKP_LIST_POSTTYPE.'_search_orderby', true); ?>" />                              
                            
                               </td>
                        </tr>
                                <tr valign="top">
                            <th scope="row">
                                <label for="">
                                    <?php _e('Keyword', ATKP_PLUGIN_PREFIX) ?>:<br />
                                   
                                </label> 
                            </th>
                            <td>
                                <input type="text" id="<?php echo ATKP_LIST_POSTTYPE.'_search_keyword' ?>" name="<?php echo ATKP_LIST_POSTTYPE.'_search_keyword' ?>" value="<?php echo  esc_attr(ATKPTools::get_post_setting($post->ID, ATKP_LIST_POSTTYPE.'_search_keyword', true )); ?>">                              
                              
                            </td>
                        </tr>
                             <tr valign="top">
                            <th scope="row">
                                <label for="">
                                    <?php _e('Limit', ATKP_PLUGIN_PREFIX) ?>:<br />
                                   
                                </label> 
                            </th>
                            <td>
                            <?php
                            
                            $searchlimit = ATKPTools::get_post_setting($post->ID, ATKP_LIST_POSTTYPE.'_search_limit', true );
                            if($searchlimit == null || $searchlimit == '')
                                $searchlimit = 10;
                            
                            ?>                            
                            
                                <input disabled type="number" min="1" max="30" id="<?php echo ATKP_LIST_POSTTYPE.'_search_limit' ?>" name="<?php echo ATKP_LIST_POSTTYPE.'_search_limit' ?>" value="<?php echo $searchlimit; ?>">                              
                              
                            </td>
                        </tr>
                        
                        </table>

                            </td>
                        </tr>
                        
                        

                        
                        <tr valign="top">
                            <th scope="row">
                                
                            </th>
                            <td>
                                <input disabled type="checkbox" id="<?php echo ATKP_LIST_POSTTYPE.'_preferlocalproduct' ?>" name="<?php echo ATKP_LIST_POSTTYPE.'_preferlocalproduct' ?>" value="1" <?php echo checked(1, ATKPTools::get_post_setting($post->ID, ATKP_LIST_POSTTYPE.'_source') == '' ? false : ATKPTools::get_post_setting($post->ID, ATKP_LIST_POSTTYPE.'_preferlocalproduct'), true); ?>>
                            <label for="<?php echo ATKP_LIST_POSTTYPE.'_preferlocalproduct' ?>">
                                    <?php _e('Prefer local product information', ATKP_PLUGIN_PREFIX) ?>
                                </label> 
                            </td>
                         </tr>
                         <tr valign="top">
                            <th scope="row">
                                
                            </th>
                            <td>
                                <input disabled type="checkbox" id="<?php echo ATKP_LIST_POSTTYPE.'_loadmoreoffers' ?>" name="<?php echo ATKP_LIST_POSTTYPE.'_loadmoreoffers' ?>" value="1" <?php echo  checked(1, ATKPTools::get_post_setting($post->ID, ATKP_LIST_POSTTYPE.'_loadmoreoffers')); ?>>
                            <label for="<?php echo ATKP_LIST_POSTTYPE.'_loadmoreoffers' ?>">
                                    <?php _e('Load offers from other shops (slower)', ATKP_PLUGIN_PREFIX) ?>
                                </label> 
                            </td>
                         </tr>
                        <tr valign="top">
                            <th scope="row">
                                
                            </th>
                            <td>
                            <label for="">
                            <strong>
                                    <?php _e('Title filter (one keyword per line)', ATKP_PLUGIN_PREFIX) ?>:<br />
                                   </strong>
                                </label> <br />
                            <?php
                            
                            $searchtitelfilter = ATKPTools::get_post_setting($post->ID, ATKP_LIST_POSTTYPE.'_search_titelfilter', true );
                            
                            ?>                            
                             <textarea disabled style="width:100%;height:100px" id="<?php echo ATKP_LIST_POSTTYPE.'_search_titelfilter' ?>" name="<?php echo ATKP_LIST_POSTTYPE.'_search_titelfilter' ?>"><?php echo esc_textarea($searchtitelfilter); ?></textarea>
    		
                            </td>
                        </tr>
                        
                        </table>
                       
                         </td>
                         </tr>
                         
                         <tr valign="top">
                            <th scope="row">
                                <label for="">
                                    <?php _e('List URL', ATKP_PLUGIN_PREFIX) ?>:
                                </label> 
                            </th>
                            <td>
                                <input disabled type="url" style="width:100%" id="<?php echo ATKP_LIST_POSTTYPE.'_listurl' ?>" name="<?php echo ATKP_LIST_POSTTYPE.'_listurl' ?>" value="<?php echo  esc_attr(ATKPTools::get_post_setting($post->ID, ATKP_LIST_POSTTYPE.'_listurl', true )); ?>">                              
                                
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

  
  <?php 
}

function list_preview_box_content( $post ) {
    require_once  ATKP_PLUGIN_DIR.'/includes/atkp_product.php';
    
    $productlist = ATKPCache::get_cache_by_id($post->ID);
   // echo("$productlist: ".serialize($productlist));
    
    $preferlocalproductinfo = ATKPTools::get_post_setting( $post->ID, ATKP_LIST_POSTTYPE.'_preferlocalproduct');

    if($productlist != null) {
         $posts_found = get_posts(array(
	'posts_per_page'	=> -1,
	'post_status'      => 'publish',
	'post_type'			=> ATKP_PRODUCT_POSTTYPE	
));
   $counter = 1;     
        foreach ($productlist as $product) {
            try {
                $type = $product['type'];
                $value = $product['value'];
                
                if($value == '')
                    continue;
                
                switch($type) {
                    case 'product':
                    if($preferlocalproductinfo)
                        foreach ( $posts_found as $mypost ) 
                        {
                            if(ATKPTools::get_post_setting( $mypost->ID, ATKP_PRODUCT_POSTTYPE.'_asin') == $value->asin)
                                $value = atkp_product::load($mypost->ID);
                            break;
                        }
                        
                        break;
                    case 'productid':
                        $value = atkp_product::load($value);
                        break;
                }
                
                $prdid = $value->productid;
                if($prdid=='')
                    $prdid='-';
                
                if($value->producturl !='')
                    echo( $counter.'. <a href="'.$value->producturl.'" target="_blank">'.esc_attr(substr($value->title,0, 180)).'</a> ('.esc_attr($value->asin).', '.$prdid.')<br />');
                else
                     echo( $counter.'. '. esc_attr(substr($value->title,0, 180)).' ('.esc_attr($value->asin).', '.$prdid.')<br />');
                     
                      $counter =  $counter +1;
            }catch(Exception $e) {
                //TODO: 'Exception: ',  $e->getMessage(), "\n";
            }
        }    
    }
}

function list_detail_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return;
	
	$posttype =  ATKPTools::get_post_parameter('post_type', 'string');
	
	if (ATKP_LIST_POSTTYPE != $posttype ) {
		return;
	}
	$nounce =  ATKPTools::get_post_parameter('list_detail_box_content_nonce', 'string');
	  
	if(!wp_verify_nonce($nounce, plugin_basename( __FILE__ ) ) )
		return;
 
    $shopid = ATKPTools::get_post_parameter(ATKP_LIST_POSTTYPE.'_shopid', 'string'); 
    
    $source = ATKPTools::get_post_parameter(ATKP_LIST_POSTTYPE.'_source', 'string'); 
    $nodeid = ATKPTools::get_post_parameter(ATKP_LIST_POSTTYPE.'_node_id', 'string'); 
    
    $searchdepartment =  ATKPTools::get_post_parameter(ATKP_LIST_POSTTYPE.'_search_department', 'string'); 
    $searchkeyword = ATKPTools::get_post_parameter(ATKP_LIST_POSTTYPE.'_search_keyword', 'string'); 
    $searchorderby = ATKPTools::get_post_parameter(ATKP_LIST_POSTTYPE.'_search_orderby', 'string'); 
    
    $preferlocalproduct= ATKPTools::get_post_parameter(ATKP_LIST_POSTTYPE.'_preferlocalproduct', 'bool'); 
    $loadmoreoffers= ATKPTools::get_post_parameter(ATKP_LIST_POSTTYPE.'_loadmoreoffers', 'bool'); 
        
    $productid = ATKPTools::get_post_parameter(ATKP_LIST_POSTTYPE.'_productid', 'int'); 
	$listurl = ATKPTools::get_post_parameter(ATKP_LIST_POSTTYPE.'_listurl', 'url'); 
	
	$extsearchlimit = ATKPTools::get_post_parameter(ATKP_LIST_POSTTYPE.'_extendedsearch_limit', 'int'); 
	$searchlimit = ATKPTools::get_post_parameter(ATKP_LIST_POSTTYPE.'_search_limit', 'int'); 
	
	
	 $searchtitelfilter = implode( "\n", array_map( 'sanitize_text_field', explode( "\n", ATKPTools::get_post_parameter(ATKP_LIST_POSTTYPE.'_search_titelfilter', 'multistring') ) ) );
	
	ATKPTools::set_post_setting( $post_id, ATKP_LIST_POSTTYPE.'_shopid', $shopid);
    
    if($shopid == '') {
    } else {
    
        ATKPTools::set_post_setting( $post_id, ATKP_LIST_POSTTYPE.'_source', $source);
    
        ATKPTools::set_post_setting( $post_id, ATKP_LIST_POSTTYPE.'_preferlocalproduct', $preferlocalproduct);
        
        ATKPTools::set_post_setting( $post_id, ATKP_LIST_POSTTYPE.'_search_department', $searchdepartment);
        ATKPTools::set_post_setting( $post_id, ATKP_LIST_POSTTYPE.'_search_keyword', $searchkeyword);
        ATKPTools::set_post_setting( $post_id, ATKP_LIST_POSTTYPE.'_search_orderby', $searchorderby);
        
        ATKPTools::set_post_setting( $post_id, ATKP_LIST_POSTTYPE.'_node_id', $nodeid);
        
        ATKPTools::set_post_setting( $post_id, ATKP_LIST_POSTTYPE.'_search_limit', $searchlimit);
        
    }
    

    
   
       //wenn die Extension nicht geladen ist, kann das Plugin nicht arbeiten
    //Wenn keine Einstellungen definiert wurden um Daten zu laden, keine Liste generieren
    
    $cronjob = new atkp_cronjob(array());
    $cronjob->update_list($post_id);
    
    
}

    }
    
?>