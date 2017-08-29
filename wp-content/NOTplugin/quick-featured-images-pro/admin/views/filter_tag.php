<h3><?php echo $this->valid_filters[ 'filter_tag' ]; ?></h3>
<p>
<?php 
$tags = get_tags();
if ( $tags ) {
?>
	<label for="qfi_tags"><?php _e( 'Select a tag', 'quick-featured-images-pro' ); ?></label><br />
	<select id="qfi_tags" name="tag_id">
<?php 
	print $this->get_html_empty_option();
	foreach ( $tags as $tag ) {
?>
		<option value="<?php echo $tag->term_id; ?>" <?php selected( $this->selected_tag_id == $tag->term_id ); ?>><?php echo $tag->name; ?></option>
<?php 
	}
?>
	</select>
<?php 
} else {
	_e( 'There are no tags in use.', 'quick-featured-images-pro' );
}
?>
</p>