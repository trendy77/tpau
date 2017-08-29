<?php
// display used featured images if user selected replacement with the selected image
if ( 'replace' == $this->selected_action ) {
	$thumb_ids_in_use = $this->get_featured_image_ids();
	if ( $thumb_ids_in_use ) {
?>
<form method="post" action="<?php echo esc_url( admin_url( sprintf( 'admin.php?page=%s&amp;step=confirm', $this->page_slug ) ) ); ?>">
<?php 
		if ( $this->is_error_no_old_image ) {
?>
	<h2><?php _e( 'Notice', 'quick-featured-images-pro' ); ?></h2>
	<div class="qfi_content_inside">
		<p class="failure"><?php _e( 'You did not have selected an image from the list below. To go on select at least one image you want to replace by the selected image.', 'quick-featured-images-pro' ); ?></p>
	</div>
<?php 
		} // if( is_error_no_old_image )
?>
	<h2><?php _e( 'Select the featured images you want to replace by the selected image.', 'quick-featured-images-pro' ); ?></h2>
	<p><?php _e( 'You can select multiple images. Select at least one image.', 'quick-featured-images-pro' ); ?></p>
	<p id="qfi_replace">
<?php
		$this->selected_old_image_ids = $this->get_sanitized_array( 'replacement_image_ids', $thumb_ids_in_use ); #array();

		foreach ( $thumb_ids_in_use as $thumb_id ) {
?>
		<label for="<?php printf( 'qfi_%d', $thumb_id ); ?>" style="width: <?php echo $this->used_thumbnail_width; ?>px;">
			<input type="checkbox" id="<?php printf( 'qfi_%d', $thumb_id ); ?>" name="replacement_image_ids[]" value="<?php echo $thumb_id; ?>" <?php checked( in_array( $thumb_id, $this->selected_old_image_ids ) ); ?>>
<?php 
			echo wp_get_attachment_image( $thumb_id, 'thumbnail' );
?>
		</label>
<?php 
		} // foreach()
?>
	</p>
	<p>
		<input type="hidden" name="image_id" value="<?php echo $this->selected_image_id; ?>" />
		<input type="hidden" name="action" value="<?php echo $this->selected_action; ?>" />
		<?php wp_nonce_field( 'quickfi_refine', $this->plugin_slug . '_nonce' ); ?>
		<input type="submit" class="button" value="<?php _e( 'Preview filtering', 'quick-featured-images-pro' ); ?>" />
	</p>
</form>
<?php 
	} else {
?>
<p><?php _e( 'There are no featured images in use.', 'quick-featured-images-pro' ); ?></p>
<p><a class="button" href="<?php echo esc_url( admin_url( sprintf( 'admin.php?page=%s', $this->page_slug ) ) );?>"><?php _e( 'Start again', 'quick-featured-images-pro' );?></a></p>
<?php 
	} // if( thumb_ids_in_use )
?>
<?php 
} else {
// else display filter selection
?>
<h2><?php _e( 'Refine your selections', 'quick-featured-images-pro' ); ?></h2>
<p><?php _e( 'You can control the process with the following options.', 'quick-featured-images-pro' ); ?></p>
<form method="post" action="<?php echo esc_url( admin_url( sprintf( 'admin.php?page=%s&amp;step=refine', $this->page_slug ) ) ); ?>">
<?php
	switch ( $this->selected_action ) {
		case 'assign':
		case 'assign_first_img':
		case 'assign_randomly':
?>
	<h3><?php _e( 'Optional: Select options', 'quick-featured-images-pro' ); ?></h3>
<?php
			if ( 'assign_first_img' == $this->selected_action ) {
?>
	<fieldset>
		<legend><span><?php _e( 'What sort of first image?', 'quick-featured-images-pro' ); ?></span></legend>
<?php
				foreach ( $this->valid_approaches_first_image as $name => $label ) {
					if ( 'first_wp_nextgen_image' == $name and ! $this->is_ngg2 ) continue; // skip Nextgen option if Nextgen is not active
?>
		<p>
			<input type="radio" id="<?php echo $name; ?>" name="approach" value="<?php echo $name; ?>" <?php checked( $name, $this->selected_approach ); ?> />
			<label for="<?php echo $name; ?>"><strong><?php echo $label; ?>.</strong></label>
		</p>
<?php
				} // foreach()
?>
	</fieldset>
<?php
			} // if(assign_first_img)
?>
	<fieldset>
		<legend><span><?php _e( 'Process Options', 'quick-featured-images-pro' ); ?></span></legend>
<?php 
			// option for overwriting existing featured images
			$key = 'overwrite';
			$label = $this->valid_options[ $key ];
			$desc = __( 'Overwrite existing featured images with new ones', 'quick-featured-images-pro' );
?>
		<p>
			<input type="checkbox" id="<?php printf( 'qfi_%s', $key ); ?>" name="options[]" value="<?php echo $key; ?>" <?php checked( in_array( $key, $this->selected_options ) ); ?>>
			<label for="<?php printf( 'qfi_%s', $key ); ?>"><strong><?php echo $label; ?>:</strong> <?php echo $desc; ?></label>
		</p>
<?php 
			// option for posts without featured image
			$key = 'orphans_only';
			$label = $this->valid_options[ $key ];
			$desc = __( 'Posts with featured images will be ignored, even if the Overwrite option is checked ', 'quick-featured-images-pro' );
?>
		<p>
			<input type="checkbox" id="<?php printf( 'qfi_%s', $key ); ?>" name="options[]" value="<?php echo $key; ?>" <?php checked( in_array( $key, $this->selected_options ) ); ?>>
			<label for="<?php printf( 'qfi_%s', $key ); ?>"><strong><?php echo $label; ?>:</strong> <?php echo $desc; ?></label>
		</p>
<?php
			if ( 'assign_first_img' == $this->selected_action ) {
				// option for removing images
				$key = 'remove_first_img';
				$label = $this->valid_options[ $key ];
				$desc = __( 'Remove the first embedded image from the post content after this image was set as featured image. This does not affect galleries or attached images. <strong>There is no undo function. Be careful with this option and please create a backup of the database of this website before you use this!</strong>', 'quick-featured-images-pro' );
?>
		<p>
			<input type="checkbox" id="<?php printf( 'qfi_%s', $key ); ?>" name="options[]" value="<?php echo $key; ?>" <?php checked( in_array( $key, $this->selected_options ) ); ?>>
			<label for="<?php printf( 'qfi_%s', $key ); ?>"><strong><?php echo $label; ?>:</strong> <?php echo $desc; ?></label>
<?php
				// option for removing images
				$key = 'forced_removal';
				$label = $this->valid_options[ $key ];
				$desc = __( "Remove first content image even if the featured image was not changed successfully. Works only if 'Remove first embedded image' is activated.", 'quick-featured-images-pro' );
?>
		</p>
		<p>
			<input type="checkbox" id="<?php printf( 'qfi_%s', $key ); ?>" name="options[]" value="<?php echo $key; ?>" <?php checked( in_array( $key, $this->selected_options ) ); ?>>
			<label for="<?php printf( 'qfi_%s', $key ); ?>"><strong><?php echo $label; ?>:</strong> <?php echo $desc; ?></label><br>
		</p>
<?php
			} // if(assign_first_img)

			if ( 'assign_randomly' == $this->selected_action ) {
				// option for using each image only once
				$key = 'use_img_only_once';
				$label = $this->valid_options[ $key ];
				$desc = __( 'By default the selected images will be used multiple times in random order. If you check this option each selected image will be used only once. If there are more posts than images the remaining posts will not be changed. If you want to remove the featured images of remaining posts check the next option additionally.', 'quick-featured-images-pro' );
?>
		<p>
			<input type="checkbox" id="<?php printf( 'qfi_%s', $key ); ?>" name="options[]" value="<?php echo $key; ?>" <?php checked( in_array( $key, $this->selected_options ) ); ?>>
			<label for="<?php printf( 'qfi_%s', $key ); ?>"><strong><?php echo $label; ?>:</strong> <?php echo $desc; ?></label>
		</p>
<?php
				// option for removing featured images if all selected images are used
				$key = 'remove_excess_imgs';
				$label = $this->valid_options[ $key ];
				$desc = __( 'Remove existing featured images of remaining posts if all selected images are used. This option works only with the previous option.', 'quick-featured-images-pro' );
?>
		<p>
			<input type="checkbox" id="<?php printf( 'qfi_%s', $key ); ?>" name="options[]" value="<?php echo $key; ?>" <?php checked( in_array( $key, $this->selected_options ) ); ?>>
			<label for="<?php printf( 'qfi_%s', $key ); ?>"><strong><?php echo $label; ?>:</strong> <?php echo $desc; ?></label>
		</p>
<?php
			} // if(assign_randomly)
?>
<?php 
			// option for overwriting existing featured images
			$key = 'attach_image_to_post';
			$label = $this->valid_options[ $key ];
			$text = 'Uploaded to';
			$desc = sprintf( __( 'This will overwrite existing entries in the media library column "%s". If an image will be set as featured image for multiple posts it will be attached to the last of these posts.', 'quick-featured-images-pro' ), _x( $text, "column name" ) );
?>
		<p>
			<input type="checkbox" id="<?php printf( 'qfi_%s', $key ); ?>" name="options[]" value="<?php echo $key; ?>" <?php checked( in_array( $key, $this->selected_options ) ); ?>>
			<label for="<?php printf( 'qfi_%s', $key ); ?>"><strong><?php echo $label; ?>:</strong> <?php echo $desc; ?></label>
		</p>
	</fieldset>
<?php
			break;
		case 'remove':
		case 'remove_any_img':
?>
	<fieldset>
		<legend><span><?php _e( 'Process Options', 'quick-featured-images-pro' ); ?></span></legend>
<?php 
			// option for overwriting existing featured images
			$key = 'detach_image_from_post';
			$label = $this->valid_options[ $key ];
			$desc = ''; // __( 'Uncouple post and image', 'quick-featured-images-pro' );
?>
		<p>
			<input type="checkbox" id="<?php printf( 'qfi_%s', $key ); ?>" name="options[]" value="<?php echo $key; ?>" <?php checked( in_array( $key, $this->selected_options ) ); ?>>
			<label for="<?php printf( 'qfi_%s', $key ); ?>"><strong><?php echo $label; ?>:</strong> <?php echo $desc; ?></label>
		</p>
	</fieldset>
<?php
			break;
	} // switch( selected_action )

	// notices
	if ( 'assign_first_img' == $this->selected_action ) {
?>
	<h3><?php _e( 'Some advices from experience', 'quick-featured-images-pro' ); ?></h3>
	<p><?php _e( 'Looking for external images can take a long time. Please be patient for at least one minute and do not reload the page! If you would see a timeout message please start again with a filter that limits the number of posts.', 'quick-featured-images-pro' ); ?></p>
	<p><?php _e( 'If you would be unsatisfied with the next results of the search for first embedded images try the option for external images. Maybe that result could meet your expectations better.', 'quick-featured-images-pro' ); ?></p>
<?php
	} // if(assign_first_img)
?>
	<h3><?php _e( 'Optional: Add a filter', 'quick-featured-images-pro' ); ?></h3>
	<fieldset>
		<legend><span><?php _e( 'Select filters', 'quick-featured-images-pro' ); ?></span></legend>
		<p><?php _e( 'If you want select one of the following filters to narrow down the set of concerned posts and pages.', 'quick-featured-images-pro' ); ?></p>
		<p><?php _e( 'You can select multiple filters. They will return an intersection of their results.', 'quick-featured-images-pro' ); ?></p>
<?php 
	foreach ( $this->valid_filters as $key => $label ) {
		switch ( $key ) {
			case 'filter_post_types':
				$desc = __( 'Search by post type. By default all posts, pages and custom post types will be affected.', 'quick-featured-images-pro' );
				break;
			case 'filter_post_formats':
				$desc = __( 'Search by post format. By default the standard post format is affected.', 'quick-featured-images-pro' );
				break;
			case 'filter_mime_types':
				$desc = __( 'Search for audios and videos. This filter will ignore all other post types automatically.', 'quick-featured-images-pro' );
				break;
			case 'filter_status':
				$desc = __( 'Search by several statuses (published, draft, private etc.). By default all statuses will be affected.', 'quick-featured-images-pro' );
				break;
			case 'filter_search':
				$desc = __( 'Search by search term', 'quick-featured-images-pro' );
				break;
			case 'filter_time':
				$desc = __( 'Search by time specifications', 'quick-featured-images-pro' );
				break;
			case 'filter_author':
				$desc = __( 'Search by author', 'quick-featured-images-pro' );
				break;
			/* case 'filter_custom_field':
				$desc = __( 'Search by custom field', 'quick-featured-images-pro' );
				break; */
			case 'filter_custom_taxonomies':
				$desc = __( 'Search by other taxonomies like plugin categories etc.', 'quick-featured-images-pro' );
				break;
			case 'filter_image_size':
				$desc = __( 'Search by original dimensions of added featured image', 'quick-featured-images-pro' );
				break;
			case 'filter_category':
				$desc = __( 'Search posts by category', 'quick-featured-images-pro' );
				break;
			case 'filter_tag':
				$desc = __( 'Search posts by tag', 'quick-featured-images-pro' );
				break;
			case 'filter_parent_page':
				$desc = __( 'Search child pages by parent page', 'quick-featured-images-pro' );
				break;
			default:
				$desc = '';
		}
?>
		<p>
			<input type="checkbox" id="<?php printf( 'qfi_%s', $key ); ?>" name="filters[]" value="<?php echo $key; ?>" <?php checked( in_array( $key, $this->selected_filters ) ); ?>>
			<label for="<?php printf( 'qfi_%s', $key ); ?>"><strong><?php echo $label; ?>:</strong> <?php echo $desc; ?></label>
		</p>
<?php
	} // foreach()
?>
	</fieldset>
	<p><?php _e( 'On the next page you can refine the filters. If you did not select any filter you will go to the preview list directly.', 'quick-featured-images-pro' ); ?></p>
	<p>
<?php
// remember selected multiple images if there are some
if ( $this->selected_multiple_image_ids ) {
	$v = implode( ',', $this->selected_multiple_image_ids );
?>
		<input type="hidden" name="multiple_image_ids" value="<?php echo $v; ?>" />
<?php
}
	$text = 'Next &raquo;';
?>
		<input type="hidden" name="image_id" value="<?php echo $this->selected_image_id; ?>" />
		<input type="hidden" name="action" value="<?php echo $this->selected_action; ?>" />
		<?php wp_nonce_field( 'quickfi_select', $this->plugin_slug . '_nonce' ); ?>
		<input type="submit" class="button" value="<?php _e( $text ); ?>" />
	</p>
</form>
<h3><?php _e( 'If you encounter a white, blank page, read this', 'quick-featured-images-pro' ); ?></h3>
<p><?php _e( 'Facing a white blank page while trying to treat thousands of posts is the effect of limited memory capacities on the website server. Instead of treating a huge amount of posts in one single go try to treat small amounts of posts multiple times successively. To achieve that do:', 'quick-featured-images-pro' ); ?></p>
<ol>
<li><?php _e( 'add the time filter,', 'quick-featured-images-pro' ); ?></li>
<li><?php _e( 'set a small time range,', 'quick-featured-images-pro' ); ?></li>
<li><?php _e( 'do the process', 'quick-featured-images-pro' ); ?></li>
<li><?php _e( 'and repeat it with the next time range as often as needed.', 'quick-featured-images-pro' ); ?></li>
</ol>
<p><?php _e( 'This way is not as fast as one single run, but still much faster than setting the images for each post manually.', 'quick-featured-images-pro' ); ?></p>
<?php
} // if( 'replace' == action )
