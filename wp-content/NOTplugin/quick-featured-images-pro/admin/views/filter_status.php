<h3><?php echo $this->valid_filters[ 'filter_status' ]; ?></h3>
<p><?php _e( 'Select the statuses of the posts/pages:', 'quick-featured-images-pro' ); ?></p>
<p>
<?php 
foreach ( $this->valid_statuses as $key => $label ) { 
?>
	<input type="checkbox" id="<?php printf( 'qfi_%s', $key ); ?>" name="statuses[]" value="<?php echo $key; ?>" <?php checked( in_array( $key, $this->selected_statuses ) ); ?> />
	<label for="<?php printf( 'qfi_%s', $key ); ?>"><?php echo $label; ?></label><br>
<?php 
}
?>
</p>
