<h3><?php echo $this->valid_filters[ 'filter_parent_page' ]; ?></h3>
<?php
// translate once
$text = '&mdash; Select &mdash;';
$label_option_none = __( $text );
$label_include_page = __( 'Include parent page of %s', 'quick-featured-images-pro' );
$label_head_post_type = __( 'Select a parent page of %s', 'quick-featured-images-pro' );
$text_no_pages = __( 'There are no pages with child pages.', 'quick-featured-images-pro' );

// print form elements
foreach ( array_merge( $this->valid_post_types, $this->get_custom_post_types_labels() ) as $post_type => $post_type_label ) {
	if ( is_post_type_hierarchical( $post_type ) ) {
?>
<h4><?php echo $post_type_label; ?></h4>
<?php 
		$parent_pages = $this->get_post_ids_of_parent_pages( $post_type );
		if ( $parent_pages ) {
			$selection_name = sprintf( '%s_id', $post_type );
?>
<p>
	<label for="<?php echo $selection_name; ?>"><?php printf( $label_head_post_type, $post_type_label ); ?></label><br />
<?php 
			// set params for pulldown
			$args = array(
				'post_type'			=> $post_type,
				'include'			=> $parent_pages,
				'show_option_none'	=> $label_option_none,
				'option_none_value'	=> '',
				'id'				=> $selection_name,
				'name'				=> sprintf( 'parent_page_id[%s]', $post_type ),
			);
			if ( ! empty( $this->selected_parent_page_ids[ $post_type ] ) ) {
				$args[ 'selected' ] = $this->selected_parent_page_ids[ $post_type ];
			}
			
			// display the pulldown
			wp_dropdown_pages( $args ); 
?>
</p>
<?php
			$key = sprintf( 'parent_included_%s', $post_type );
?>
<p>
	<input type="checkbox" id="<?php echo $key; ?>" name="parent_page_included[]" value="<?php echo $post_type; ?>" <?php checked( in_array( $post_type, $this->selected_parent_pages_included ) ); ?> />
	<label for="<?php echo $key; ?>"><?php printf( $label_include_page, $post_type_label ); ?></label>
</p>
<?php
		} else {
?>
<p>
<?php
			echo $text_no_pages;
?>
</p>
<?php
		} // if ( $parent_pages )
	} // if ( is_post_type_hierarchical() )
} // foreach( valid_post_types )
