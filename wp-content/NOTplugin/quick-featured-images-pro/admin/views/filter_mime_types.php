<h3><?php echo $this->valid_filters[ 'filter_mime_types' ]; ?></h3>
<p><?php _e( 'Select multimedia file types', 'quick-featured-images-pro' ); ?>. <?php _e( 'You can select two multimedia files types: audios and videos. If you check at least one of both all other post types (posts, pages, etc.) will be ignored.', 'quick-featured-images-pro' ); ?></p>
<p>
<?php
foreach ( $this->valid_mime_types as $key => $label ) {
?>
	<input type="checkbox" id="<?php printf( 'qfi_%s', $key ); ?>" name="mime_types[]" value="<?php echo $key; ?>"  <?php checked( in_array( $key, $this->selected_mime_types ) ); ?> />
	<label for="<?php printf( 'qfi_%s', $key ); ?>"><?php echo $label; ?></label><br />
<?php
}
?>
</p>
