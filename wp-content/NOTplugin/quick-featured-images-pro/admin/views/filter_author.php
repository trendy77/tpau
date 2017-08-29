<h3><?php echo $this->valid_filters[ 'filter_author' ]; ?></h3>
<p>
	<label for="selected_author_id"><?php _e( 'Select an author', 'quick-featured-images-pro' ); ?></label><br />
<?php 
$text = '&mdash; Select &mdash;';
$args = array(
	'name'		=> 'author_id',
	'selected'	=> $this->selected_author_id,
	'show_option_none' => __( $text ),
	//'who'		=> 'authors',
);
wp_dropdown_users( $args ); 
?>
</p>
