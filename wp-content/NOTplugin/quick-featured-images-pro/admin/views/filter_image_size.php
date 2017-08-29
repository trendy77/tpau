<h3><?php echo $this->valid_filters[ 'filter_image_size' ]; ?></h3>
<p><?php _e( 'The search will find posts with an already added featured image which its original image file is smaller than one of the given dimensions.', 'quick-featured-images-pro' ); ?></p>
<p><?php _e( 'For example you can search for posts with too small featured images.', 'quick-featured-images-pro' ); ?></p>
<?php
$text = 'Settings';
$label_settings = __( $text );
$text = 'Media';
$label_media = _x( $text, 'post type general name' );
$label = sprintf( '%s &rsaquo; %s', $label_settings, $label_media );
if ( current_user_can( 'manage_options' ) ) {
	$text = sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( admin_url( 'options-media.php' ) ), $label );
} else {
	$text= sprintf( '<strong>%s</strong>', $label );
}
?>
<p><?php printf( __( 'Only positive integers from %d to %d are allowed. By default the thumbnail dimensions as defined in %s are used.', 'quick-featured-images-pro' ), $this->min_image_length, $this->max_image_length, $text ); ?></p>
<?php 
foreach ( $this->valid_image_dimensions as $key => $label ) {
?>
<p>
	<label for="<?php printf( 'qfi_%s', $key ); ?>"><?php echo $label; ?></label>
	<input type="text" 
	name="image_dimensions[<?php echo $key; ?>]" 
	id="<?php printf( 'qfi_%s', $key ); ?>" 
	value="<?php echo $this->selected_image_dimensions[ $key ]; ?>" maxlength="4">
	px
</p>
<?php 
} // foreach()
?>
</p>
