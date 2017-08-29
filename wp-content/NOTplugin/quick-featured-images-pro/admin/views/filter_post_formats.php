<h3><?php echo $this->valid_filters[ 'filter_post_formats' ]; ?></h3>
<p><?php _e( 'Select post formats', 'quick-featured-images-pro' ); ?>. <?php _e( 'You can select posts formats.', 'quick-featured-images-pro' ); ?></p>
<p>
<?php
#$labeled_custom_post_formats = $this->get_custom_post_formats_labels();

foreach ( $this->valid_post_formats as $key => $label ) {
?>
	<input type="checkbox" id="<?php printf( 'qfi_%s', $key ); ?>" name="post_formats[]" value="<?php echo $key; ?>"  <?php checked( in_array( $key, $this->selected_post_formats ) ); ?> />
	<label for="<?php printf( 'qfi_%s', $key ); ?>"><?php echo $label; ?></label><br />
<?php
}
?>
</p>
