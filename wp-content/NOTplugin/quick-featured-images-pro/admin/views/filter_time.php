<h3><?php echo $this->valid_filters[ 'filter_time' ]; ?></h3>
<h4><?php _e( 'Date range', 'quick-featured-images-pro' ); ?>:</h4>
<p><?php _e( 'To define a time segment select both the start date and the end date.', 'quick-featured-images-pro' ); ?></p>
<p><?php _e( 'You can also define a time period by selecting only one date as the limiting value of the period.', 'quick-featured-images-pro' ); ?></p>
<p><?php _e( 'The listed dates are the date of the publication of stored posts.', 'quick-featured-images-pro' ); ?></p>
<?php

?>
<div class="qfi_wrapper">
<?php 
$this->valid_post_dates = $this->get_registered_post_dates();

foreach ( $this->valid_date_queries as $key => $label ) { 
	switch ( $key ) {	
		case 'after':
		case 'before':
?>
	<div class="qfi_w50percent">
		<p>
			<label for="<?php printf( 'qfi_%s', $key ); ?>"><?php echo $label; ?></label><br>
			<select id="<?php printf( 'qfi_%s', $key ); ?>" name="date_queries[<?php echo $key; ?>]">
<?php 
			print $this->get_html_date_options( $key );
?>
			</select>
		</p>
	</div><!-- .qfi_w50percent -->
<?php 
			break;
	} // switch()
} // foreach()
?>
</div><!-- .qfi_wrapper -->
<?php
$key = 'inclusive';
$label = $this->valid_date_queries[ $key ];
?>
<p>
	<input type="checkbox" id="<?php printf( 'qfi_%s', $key ); ?>" name="date_queries[<?php echo $key; ?>]" value="1" <?php checked( isset( $this->selected_date_queries[ $key ] ) ); ?> />
	<label for="<?php printf( 'qfi_%s', $key ); ?>"><?php echo $label; ?></label><br>
</p>

