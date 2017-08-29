<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    class atkp_settings_toolkit
    {
        private $base = null;
        /**
         * Construct the plugin object
         */
        public function __construct($pluginbase)
        {
            $base  = $pluginbase;
        }

private function display_cron_job($hookParam = ATKP_EVENT)
{
    //kleiner als basic wird nicht unterstützt
    if(ATKP_PLUGIN_VERSION < 20)
	    return;
	    
   $cron = _get_cron_array();

  

    $hook = wp_get_schedule( $hookParam );
    
   
    
    $crons  = _get_cron_array();
		$events = array();

		foreach ( $crons as $time => $cron ) {
			foreach ( $cron as $hook => $dings ) {
				foreach ( $dings as $sig => $data ) {
                if($hook == $hookParam) {
    
				
				if (  $data['schedule'] ) {
					
				$localtime = get_date_from_gmt( date( 'Y-m-d H:i:s', $time ), get_option( 'time_format' ));
				$localdate = get_date_from_gmt( date( 'Y-m-d H:i:s', $time ), get_option( 'date_format' ));
				
				$text = '';
				$text .= ' '.sprintf(__('Cronjob next execution: %s %s', ATKP_PLUGIN_PREFIX), $localdate, $localtime);
				$text .= ' ('.$this->time_since( time(), $time ).')';
				$text .= ' Interval: '. $this->interval(isset( $data['interval'] ) ? $data['interval'] : null );
				
				echo($text);
				} else {
					
					__( 'Non-repeating', ATKP_PLUGIN_PREFIX);
				
				}
}
				}
			}
		}
   
}

	public function time_since( $older_date, $newer_date ) {
		return $this->interval( $newer_date - $older_date );
	}

	public function interval( $since ) {
	    	    
		// array of time period chunks
		$chunks = array(
			array( 60 * 60 * 24 * 365, _n_noop( '%s year', '%s years', ATKP_PLUGIN_PREFIX) ),
			array( 60 * 60 * 24 * 30, _n_noop( '%s month', '%s months', ATKP_PLUGIN_PREFIX ) ),
			array( 60 * 60 * 24 * 7, _n_noop( '%s week', '%s weeks', ATKP_PLUGIN_PREFIX ) ),
			array( 60 * 60 * 24, _n_noop( '%s day', '%s days', ATKP_PLUGIN_PREFIX ) ),
			array( 60 * 60, _n_noop( '%s hour', '%s hours', ATKP_PLUGIN_PREFIX ) ),
			array( 60, _n_noop( '%s minute', '%s minutes', ATKP_PLUGIN_PREFIX ) ),
			array( 1, _n_noop( '%s second', '%s seconds', ATKP_PLUGIN_PREFIX ) ),
		);

		if ( $since <= 0 ) {
			return __( 'now', ATKP_PLUGIN_PREFIX );
		}

		// we only want to output two chunks of time here, eg:
		// x years, xx months
		// x days, xx hours
		// so there's only two bits of calculation below:

		// step one: the first chunk
		for ( $i = 0, $j = count( $chunks ); $i < $j; $i++ ) {
			$seconds = $chunks[ $i ][0];
			$name = $chunks[ $i ][1];

			// finding the biggest chunk (if the chunk fits, break)
			if ( ( $count = floor( $since / $seconds ) ) != 0 ) {
				break;
			}
		}

		// set output var
		$output = sprintf( translate_nooped_plural( $name, $count, ATKP_PLUGIN_PREFIX ), $count );

		// step two: the second chunk
		if ( $i + 1 < $j ) {
			$seconds2 = $chunks[ $i + 1 ][0];
			$name2 = $chunks[ $i + 1 ][1];

			if ( ( $count2 = floor( ( $since - ( $seconds * $count ) ) / $seconds2 ) ) != 0 ) {
				// add to output var
				$output .= ' ' . sprintf( translate_nooped_plural( $name2, $count2, ATKP_PLUGIN_PREFIX ), $count2 );
			}
		}

		return $output;
	}


        public function toolkit_configuration_page()
        {
        
            
            
        if (ATKPTools::exists_post_parameter('saveglobal') && check_admin_referer('save', 'save')) {
                //speichern der einstellungen
                
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page', ATKP_PLUGIN_PREFIX));
            }
                
			$oldduration = get_option(ATKP_PLUGIN_PREFIX.'_cache_duration', 1440);
			$duration = ATKPTools::get_post_parameter(ATKP_PLUGIN_PREFIX.'_cache_duration', 'int');
			
			update_option(ATKP_PLUGIN_PREFIX.'_cache_duration', $duration);
			
		
			if(ATKP_PLUGIN_VERSION >= 20) {
				$cronjob = new atkp_cronjob(array());
				
				if(isset($cronjob)) {
					ATKPSettings::load_settings();
		
					$cronjob->my_update();                        
				}
			}
             
            }
            
            
            ?>
            <div class="wrap">
            <div id="tabs">
            

                <?php if ( !extension_loaded('soap') ) { ?>
                    <div class="error">
                        <p><?php _e('PHP SOAP extension is not loaded.', ATKP_PLUGIN_PREFIX) ?></p>
                    </div>
                <?php }     
                ?>
                
                
                
                <form method="POST" action="?page=<?php echo ATKP_PLUGIN_PREFIX.'_affiliate_toolkit-plugin' ?>">
                <?php wp_nonce_field("save", "save"); ?>
                    <table class="form-table" style="width:1024px">                       
      
 
                        <tr valign="top">
                            <th scope="row" style="background-color:gainsboro; padding:7px" colspan="2">
                                <?php _e('Settings for data cache', ATKP_PLUGIN_PREFIX) ?>
                            </th>
                        </tr>
    
                        <tr valign="top">
                            <th scope="row">
                                <label for="">
                                    <?php _e('Cache duration', ATKP_PLUGIN_PREFIX) ?>:
                                </label> 
                            </th>
                            <td>
                                <select name="<?php echo ATKP_PLUGIN_PREFIX.'_cache_duration' ?>">
                                <?php
                                
                                $durations = array(
                                                    60 => __('1 Hour', ATKP_PLUGIN_PREFIX),
                                                    360 => __('6 Hours', ATKP_PLUGIN_PREFIX),
                                                    720 => __('12 Hours', ATKP_PLUGIN_PREFIX),
                                                    1440 => __('1 Day', ATKP_PLUGIN_PREFIX),
                                                    4320 => __('3 Days', ATKP_PLUGIN_PREFIX),
                                                    10080 => __('1 Week', ATKP_PLUGIN_PREFIX),
                                                  );
                                
                                foreach ($durations as $value => $name) {
                                    if ($value == get_option(ATKP_PLUGIN_PREFIX.'_cache_duration', 1440)) 
                                        $sel = ' selected'; 
                                    else 
                                        $sel = '';
                                    
                                    $item_translated = '';
                                                                
                                    echo '<option value="' . $value . '"' . $sel . '>' . $name . '</option>';
                                } ?>
                                </select><br />
                                <label for=""><?php echo $this->display_cron_job(); ?></label>
                            </td>
                        </tr>
  
                        <tr valign="top">
                            <th scope="row">                      
                            </th>
                            <td>
                                <?php submit_button('', 'primary', 'saveglobal', false); ?>
                            </td>
                        </tr>
                        
                    </table>
                </form>                
       
    </div>
</div>
            
            
                <?php
            }
}
?>