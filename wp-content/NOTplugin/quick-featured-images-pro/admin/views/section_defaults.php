<?php
/**
 * Options Page For Default Images
 *
 * @package   Quick_Featured_Images_Pro_Defaults
 * @author    Martin Stehle <m.stehle@gmx.de>
 * @license   GPL-2.0+
 * @link      http://quickfeaturedimages.com/
 * @copyright 2014 
 */
#dambedei( $this->selected_rules );
// define some variables
$no_thumb_url = includes_url() . 'images/blank.gif';

// store recurring translations only once for more performance
$matches_label			= __( 'matches', 'quick-featured-images-pro' );
$number_label			= __( 'No.', 'quick-featured-images-pro' );
$search_label			= __( 'Search string in post title', 'quick-featured-images-pro' );
$searchterm_label		= __( 'Search string', 'quick-featured-images-pro' );
$button_multiples_label	= __( 'Choose random images', 'quick-featured-images-pro' );
$randomize_label		= __( 'Set the featured image randomly at each page load. That works only for multiple images and will always overwrite existing featured images!', 'quick-featured-images-pro' );

// WP core strings
$text = 'Save Changes';
$button_label		= __( $text );
$text = 'Format';
$post_format_label	= _x( $text, 'post format' );
$text = 'Choose Image';
$choose_image_label	= __( $text );
$text = 'Taxonomy:';
$taxonomy_label		= __( $text );
$text = 'Action';
$action_label		= __( $text );
$text = 'Description';
$description_label  = __( $text );
$text = 'Image';
$image_label		= __( $text );
$text = 'Value';
$value_label		= __( $text );
$text = 'Author';
$user_label		= __( $text );
$text = '&mdash; Select &mdash;';
$first_option_label = __( $text );
$text = 'Featured Image';
$feat_img_label 	= __( $text );
$text = 'Category';
$category_label 	= _x( $text, 'taxonomy singular name' );
$text = 'Tag';
$tag_label 			= _x( $text, 'taxonomy singular name' );
$text = 'Post';
$post_label		= _x( $text, 'post type singular name' );
$text = 'Page';
$page_label		= _x( $text, 'post type singular name' );

// Descriptions
$search_desc		= sprintf( __( 'works only if &#8220;%s&#8221; is set to &#8220;%s&#8221;', 'quick-featured-images-pro' ), $taxonomy_label, $search_label );

// set parameters for term queries
$args = array( 
	'orderby'       => 'name', 
	'order'         => 'ASC',
	'hide_empty'    => false, 
	'hierarchical'  => true, 
);

// set options fields
$optionfields = array(
	'post_type' => __( 'Post Type', 'quick-featured-images-pro' ),
	'post_format' => $post_format_label,
	'category' => $category_label,
	'post_tag' => $tag_label,
	'user' => $user_label,
);

// get stored tags
$tags = get_tags( $args );

// get stored categories
$categories = get_categories( $args );

// get authors: Return List all blog editors, return limited fields in resulting row objects:
$user_query = new WP_User_Query( array( 
	'who' => 'authors', 
	'fields' => array( 'ID', 'user_nicename', 'display_name' ),
	'order' => 'ASC',
    'orderby' => 'display_name'
) );
$user_data = $user_query->get_results();
// make selection box entries
$users = array();
if ( 0 < count( $user_data ) ) {
	// loop through each author
	foreach ( $user_data as $user ) {
		$users[] = array( 'id' => $user->ID, 'name' => sprintf( '%s (%s)', $user->display_name, $user->user_nicename ) );
	}
}

// get stored post formats
$post_formats = get_post_format_strings();
unset( $post_formats[ 'standard' ] ); // Special case, delete it from list

// get stored post types
$post_types = $this->get_custom_post_types_labels();

// get stored taxonomies
$custom_taxonomies = $this->get_custom_taxonomies_labels();
$custom_taxonomies_terms = array();
if ( $custom_taxonomies ) {
	foreach ( $custom_taxonomies as $key => $label ) {
		$options = array();
		$terms = get_terms( $key, $args );
		if ( is_wp_error( $terms ) ) {
			printf( '<p>%s<p>', $terms->get_error_message() );
			continue;
		}
		if ( 0 < count( $terms ) ) {
			foreach ( $terms as $term ) {
				$custom_taxonomies_terms[ $key ][ $term->term_id ] = $term->name;
			}
			if ( isset( $this->selected_custom_taxonomies[ $key ] ) ) {
				$selected_tax = $this->selected_custom_taxonomies[ $key ];
			} else {
				$selected_tax = '';
			}
		}
	}
}

// print jQuery for pulldowns
?>
<script type="text/javascript">
jQuery( document ).ready( function( $ ){

/*
 * build arrays of options
 */
var options = new Array();
<?php
// build post type options
$key = 'post_type';
printf( 'options[ \'%s\' ] = new Array();', $key );
print "\n";
printf( 'options[ \'%s\' ].push( \'<option value="">%s</option>\' );', $key, $first_option_label );
print "\n";
printf( 'options[ \'%s\' ].push( \'<option value="%s">%s</option>\' );', $key, 'post', $post_label );
print "\n";
printf( 'options[ \'%s\' ].push( \'<option value="%s">%s</option>\' );', $key, 'page', $page_label );
print "\n";
foreach ( $post_types as $name => $label ) {
	printf( 'options[ \'%s\' ].push( \'<option value="%s">%s</option>\' );', $key, esc_attr( $name ), esc_html( $label ) );
	print "\n";
}

// build post format options
$key = 'post_format';
printf( 'options[ \'%s\' ] = new Array();', $key );
print "\n";
printf( 'options[ \'%s\' ].push( \'<option value="">%s</option>\' );', $key, $first_option_label );
print "\n";
foreach ( $post_formats as $name => $label ) {
	printf( 'options[ \'%s\' ].push( \'<option value="%s">%s</option>\' );', $key, esc_attr( $name ), esc_html( $label ) );
	print "\n";
}

// build tag options
$key = 'post_tag';
printf( 'options[ \'%s\' ] = new Array();', $key );
print "\n";
printf( 'options[ \'%s\' ].push( \'<option value="">%s</option>\' );', $key, $first_option_label ); 
print "\n";
foreach ( $tags as $tag ) {
	printf( 'options[ \'%s\' ].push( \'<option value="%d">%s</option>\' );', $key, absint( $tag->term_id ), esc_html( $tag->name ) );
	print "\n";
}

// build category options
$key = 'category';
printf( 'options[ \'%s\' ] = new Array();', $key );
print "\n";
printf( 'options[ \'%s\' ].push( \'<option value="">%s</option>\' );', $key, $first_option_label );
print "\n";
foreach ( $categories as $category ) {
	printf( 'options[ \'%s\' ].push( \'<option value="%d">%s</option>\' );', $key, absint( $category->term_id ), esc_html( $category->name ) );
	print "\n";
}

// build custom taxonomy options
if ( $custom_taxonomies_terms ) {
	foreach ( array_keys( $custom_taxonomies_terms ) as $key ) {
		printf( 'options[ \'%s\' ] = new Array();', $key );
		print "\n";
		printf( 'options[ \'%s\' ].push( \'<option value="">%s</option>\' );', $key, $first_option_label );
		print "\n";
 		foreach ( $custom_taxonomies_terms[ $key ] as $term_id => $term_name ) {
			printf( 'options[ \'%s\' ].push( \'<option value="%d">%s</option>\' );', $key, absint( $term_id ), esc_html( $term_name ) );
			print "\n";
		}
	}
} // if ( custom_taxonomies_terms )

// build user options
$key = 'user';
printf( 'options[ \'%s\' ] = new Array();', $key );
print "\n";
printf( 'options[ \'%s\' ].push( \'<option value="">%s</option>\' );', $key, $first_option_label );
print "\n";
foreach ( $users as $user ) {
	printf( 'options[ \'%s\' ].push( \'<option value="%d">%s</option>\' );', $key, absint( $user[ 'id' ] ), esc_html( $user[ 'name' ] ) );
	print "\n";
}

// build search option
$key = 'searchterm';
printf( 'options[ \'%s\' ] = new Array();', $key );
print "\n";
printf( 'options[ \'%s\' ].push( \'<option value="qfi-defaults-search">%s</option>\' );', $key, $searchterm_label );
print "\n";

?>
	 /*
	 * Options changes
	 */
	 $( '.selection_rules' ).live( 'change', function() {
		// get number of row
		var row_number = this.id.match( /[0-9]+/ );
		// set selector names
		var selector_taxonomy = '#taxonomy_' + row_number;
		var selector_matchterm = '#matchterm_' + row_number;
		// change 'value' selection on change of 'taxonomy' selection
		$( selector_taxonomy + ' option:selected' ).each( function() {
			$( selector_matchterm ).html( options[ $( this ).val() ].join( '' ));
		} );
	} )
} )
</script>

<h2 class="no-bottom"><?php esc_html_e( 'Default featured images for future posts', 'quick-featured-images-pro' ); ?></h2>
<div class="qfi_page_description">
	<p><?php echo $this->get_page_description(); ?>. <?php esc_html_e( 'Define the rules to use images as default featured images automatically every time a post is saved.', 'quick-featured-images-pro' ); ?></p>
	<p><?php esc_html_e( 'To use a rule choose the image and set both the taxonomy and the value. A rule which is defined only partially will be ignored.', 'quick-featured-images-pro' ); ?></p>
</div>

<?php 
if ( ! ( current_theme_supports( 'post-thumbnails' ) and current_theme_supports( 'post-formats' ) ) ) {
?>
<h2 style="margin-bottom:0"><?php $text = 'Notice:'; esc_html_e( $text ); ?></h2>
<div class="qfi-failure">
<?php
	if ( ! current_theme_supports( 'post-thumbnails' ) ) {
?>
	<p><?php esc_html_e( 'The current theme does not support featured images. Anyway you can use this plugin. The rules are stored and will be visible in a theme which supports featured images.', 'quick-featured-images-pro' ); ?></p>
<?php 
	}
	if ( ! current_theme_supports( 'post-formats' ) ) {
?>
	<p><?php esc_html_e( 'The current theme does not support post formats. Anyway you can use this plugin. The rules are stored and will be visible in a theme which supports post formats.', 'quick-featured-images-pro' ); ?></p>
<?php 
	}
?>
</div>
<?php 
}

//printf( "<pre>%s</pre>", var_export( $this->selected_rules, true ) );
?>

<form method="post" action="">
	<p><?php printf( __( 'Define the rules in two steps: First set the rules, second set the order of the rules. After that click on the button %s.', 'quick-featured-images-pro' ), "'" . $button_label . "'" ); ?></p>
<?php
$qfi_rules = get_option( 'quick-featured-images-defaults' );
if ( false !== $qfi_rules and ! isset( $_POST[ 'importrules' ] ) ) {
	// QFI rules are available and no import request was done so ask for import
?>
	<h2><?php esc_html_e( 'Import presets from Quick Featured Images', 'quick-featured-images-pro' ); ?></h2>
	<div id="qfi_import_notice">
		<p><?php esc_html_e( 'Presets from the free plugin Quick Featured Images are available. Click on the button to import and apply them here.', 'quick-featured-images-pro' ); ?></p>
		<h3><?php $text = 'Notice:'; esc_html_e( $text ); ?></h3>
		<ul>
			<li><?php esc_html_e( 'The import will overwrite already existing settings and rules!', 'quick-featured-images-pro' ); ?></li>
			<li><?php esc_html_e( 'If you want to dismiss this section please deinstall Quick Featured Images.', 'quick-featured-images-pro' ); ?></li>
		</ul>
		<?php submit_button( __( 'Import presets', 'quick-featured-images-pro' ), 'primary', 'importrules' ); ?>
	</div>
<?php
}

?>
	<h2><?php esc_html_e( 'Set rules', 'quick-featured-images-pro' ); ?></h2>
	<table class="widefat">
		<thead>
			<tr>
				<th class="num"><?php echo $number_label; ?></th>
				<th><?php echo $image_label; ?></th>
				<th><?php echo $description_label; ?></th>
				<th><?php echo $action_label; ?></th>
			</tr>
		</thead>
		<tbody>
			<tr id="row_1" class="alternate">
				<td class="num">1</td>
				<td>
					<?php printf( '<img src="%s" alt="%s" width="80" height="80" />', plugins_url( 'assets/images/overwrite-image.jpg' , dirname( __FILE__ ) ), __( 'An image overwrites an existing image', 'quick-featured-images-pro' ) ); ?><br />
				</td>
				<td>
					<p>
						<label><input type="checkbox" name="overwrite_automatically" value="1"<?php checked( isset( $this->selected_rules[ 'overwrite_automatically' ] ), '1' ); ?>><?php esc_html_e( 'Activate to automatically overwrite an existing featured image while saving a post', 'quick-featured-images-pro' ); ?></label>
					</p>
					<p class="description"><?php esc_html_e( 'If activated the rule is used automatically while saving a post to overwrite an existing featured image with the new one based on the following rules. Do not use this if you want to keep manually set featured images.', 'quick-featured-images-pro' ); ?></p>
				</td>
				<td></td>
			</tr>
			<tr id="row_2">
				<td class="num">2</td>
				<td>
					<?php printf( '<img src="%s" alt="%s" width="80" height="80" />', plugins_url( 'assets/images/first-content-image.gif' , dirname( __FILE__ ) ), __( 'Text with images in WordPress editor', 'quick-featured-images-pro' ) ); ?><br />
				</td>
				<td>
<?php
foreach ( $this->valid_first_image_actions as $value => $label ) {
?>
					<p><label><input type="radio" name="use_first_image_as_default" value="<?php echo $value; ?>" class="tog"<?php checked( $value, $this->selected_rules[ 'use_first_image_as_default' ] ); ?> /><?php echo $label; ?></label></p>
<?php
}
?>
					<p class="description"><?php esc_html_e( 'If activated the rule is used automatically while saving a post to set the first image - if available in the media library or from external server - as the featured image of the post. If an image was not found the next rules will be applied.', 'quick-featured-images-pro' ); ?></p>
				</td>
				<td></td>
			</tr>
			<tr id="row_3" class="alternate">
				<td class="num">3</td>
				<td>
					<?php $text = 'Advanced Options'; esc_html_e( $text ); ?>
				</td>
				<td>
<?php
foreach ( $this->valid_first_image_options as $key => $label ) {
?>
					<p><label><input type="checkbox" name="<?php echo $key; ?>" value="1"<?php checked( isset( $this->selected_rules[ $key ] ), '1' ); ?> /><?php echo $label; ?></label></p>
<?php
}
?>
					<?php #<p class="description">< ?php esc_html_e( 'If activated the rule is used automatically while saving a post to set the first image - if available in the media library or from external server - as the featured image of the post. If an image was not found the next rules will be applied.', 'quick-featured-images-pro' ); ? ></p> ?>
				</td>
				<td></td>
			</tr>
<?php
$c = 4;
if ( isset( $this->selected_rules[ 'rules' ] ) ) {
	foreach ( $this->selected_rules[ 'rules' ] as $rule ) {
		// only consider valid values
		if ( ! ( $rule[ 'id' ] or $rule[ 'ids' ] ) ) continue;
		if ( '' == $rule[ 'taxonomy' ] ) continue;
		if ( '' == $rule[ 'matchterm' ] ) continue;
		// alternate row color
		if( 0 != $c % 2 ) { // if c is odd
			$row_classes = ' class="alternate"';
		} else {
			$row_classes = '';
		}
		// cast ids to string for hidden field
		if ( ! empty ( $rule[ 'ids' ] ) ) {
			$r_ids = implode( ',', $rule[ 'ids' ] );
		} else {
			$r_ids = '';
		}
?>
			<tr id="row_<?php echo $c; ?>"<?php echo $row_classes; ?>>
				<td class="num"><?php echo $c; ?></td>
				<td>
					<input type="hidden" name="rules[<?php echo $c; ?>][ids]" id="multiple_image_ids_<?php echo $c; ?>" value="<?php echo $r_ids; ?>">
					<input type="hidden" name="rules[<?php echo $c; ?>][id]" id="image_id_<?php echo $c; ?>" value="<?php echo $rule[ 'id' ]; ?>">
<?php
			if ( $rule[ 'id' ] ) {
?>
					<img src="<?php echo wp_get_attachment_thumb_url( $rule[ 'id' ] ); ?>" alt="<?php echo $feat_img_label; ?>" id="selected_image_<?php echo $c; ?>" class="attachment-thumbnail qfi_preset_image">
<?php
			} elseif ( $rule[ 'ids' ] ) {
				foreach ( $rule[ 'ids' ] as $id ) {
?>
					<img src="<?php echo wp_get_attachment_thumb_url( $id ); ?>" alt="<?php echo $feat_img_label; ?>" class="attachment-thumbnail qfi_preset_image qfi_img_rule_<?php echo $c; ?>">
<?php
				}
?>
					<img src="<?php echo $no_thumb_url; ?>" alt="<?php echo $feat_img_label; ?>" id="selected_image_<?php echo $c; ?>">
<?php
			} else {
?>
					<img src="<?php echo $no_thumb_url; ?>" alt="<?php echo $feat_img_label; ?>" id="selected_image_<?php echo $c; ?>">
<?php
			} // if ( rule[id] )
?>
				</td>
				<td>
					<input type="button" name="single_image_<?php echo $c; ?>" value="<?php echo $choose_image_label; ?>" class="button single_image" id="single_image_<?php echo $c; ?>">
					<input type="button" name="multiple_images_<?php echo $c; ?>" value="<?php echo $button_multiples_label; ?>" class="button multiple_images" id="multiple_images_<?php echo $c; ?>" /><br />
					<label for="taxonomy_<?php echo $c; ?>"><?php echo $taxonomy_label; ?></label><br />
					<select name="rules[<?php echo $c; ?>][taxonomy]" id="taxonomy_<?php echo $c; ?>" class="selection_rules">
						<option value=""><?php echo $first_option_label; ?></option>
<?php
		$key = $rule[ 'taxonomy' ];
		foreach ( $optionfields as $value => $label ) {
?>
						<option value="<?php echo $value; ?>"<?php selected( $value == $key, true ); ?>><?php echo $label; ?></option>
<?php
		} // foreach ( $optionfields )
		if ( $custom_taxonomies_terms ) {
			foreach ( $custom_taxonomies as $custom_key => $label ) {
				if ( $custom_key and $label ) { // ommit empty or false values
?>
						<option value="<?php echo esc_attr( $custom_key ); ?>"<?php selected( $custom_key == $rule[ 'taxonomy' ], true ); ?>><?php echo esc_html( $label ); ?></option>
<?php
				}
			}
		} // if ( $custom_taxonomies_terms )
?>
						<option value="searchterm"<?php selected( 'searchterm' == $key, true ); ?>><?php echo $search_label; ?></option>
					</select><br />
					<?php echo $matches_label; ?>:<br />
					<label for="matchterm_<?php echo $c; ?>"><?php echo $value_label; ?></label><br />
					<select name="rules[<?php echo $c; ?>][matchterm]" id="matchterm_<?php echo $c; ?>">
<?php
		switch( $rule[ 'taxonomy' ] ) {
			case 'post_type':
?>
						<option value=""><?php echo $first_option_label; ?></option>
						<option value="post"<?php selected( 'post' == $rule[ 'matchterm' ], true ); ?>><?php echo $post_label; ?></option>
						<option value="page"<?php selected( 'page' == $rule[ 'matchterm' ], true ); ?>><?php echo $page_label; ?></option>
<?php
				foreach ( $post_types as $key => $label ) {
?>
						<option value="<?php echo esc_attr( $key ); ?>"<?php selected( $key == $rule[ 'matchterm' ], true); ?>><?php echo esc_html( $label ); ?></option>
<?php
				}
				break;
			case 'post_format':
?>
						<option value=""><?php echo $first_option_label; ?></option>
<?php
				foreach ( $post_formats as $key => $label ) {
?>
						<option value="<?php echo esc_attr( $key ); ?>"<?php selected( $key == $rule[ 'matchterm' ], true ); ?>><?php echo esc_html( $label ); ?></option>
<?php
				}
				break;
			case 'post_tag':
?>
						<option value=""><?php echo $first_option_label; ?></option>
<?php
				foreach ( $tags as $tag ) {
?>
						<option value="<?php echo absint( $tag->term_id ); ?>"<?php selected( $tag->term_id == $rule[ 'matchterm' ], true ); ?>><?php echo esc_html( $tag->name ); ?></option>
<?php
				}
				break;
			case 'category':
?>
						<option value=""><?php echo $first_option_label; ?></option>
<?php
				foreach ( $categories as $category ) {
?>
						<option value="<?php echo absint( $category->term_id ); ?>"<?php selected( $category->term_id == $rule[ 'matchterm' ], true ); ?>><?php echo esc_html( $category->name ); ?></option>
<?php
				}
				break;
			case 'user':
?>
						<option value=""><?php echo $first_option_label; ?></option>
<?php
				foreach ( $users as $user ) {
?>
						<option value="<?php echo absint( $user[ 'id' ] ); ?>"<?php selected( $user[ 'id' ] == $rule[ 'matchterm' ], true ); ?>><?php echo esc_html( $user[ 'name' ] ); ?></option>
<?php
				}
				break;
			case 'searchterm':
?>
						<option value="qfi-defaults-search"><?php echo $searchterm_label; ?></option>
<?php
				break;
			default: // custom taxonomy
?>
						<option value=""><?php echo $first_option_label; ?></option>
<?php
				if ( $custom_taxonomies_terms ) {
					foreach ( $custom_taxonomies_terms[ $rule[ 'taxonomy' ] ] as $term_id => $term_name ) {
?>
						<option value="<?php echo absint( $term_id ); ?>"<?php selected( $term_id == $rule[ 'matchterm' ] ); ?>><?php echo esc_html( $term_name ); ?></option>
<?php
					}
				}
		} // switch()
?>
					</select><br>
					<label for="searchterm_<?php echo $c; ?>"><?php echo $searchterm_label; ?> (<?php echo $search_desc; ?>)</label>:<br />
					<input type="text" name="rules[<?php echo $c; ?>][searchterm]" id="searchterm_<?php echo $c; ?>" value="<?php if ( 'searchterm' == $rule[ 'taxonomy' ] ) { echo esc_attr( $rule[ 'matchterm' ] ); } ?>"><br />
					<label><input type="checkbox" name="rules[<?php echo $c; ?>][randomize]" id="randomize_<?php echo $c; ?>" value="1"<?php checked( isset( $rule[ 'randomize' ] ), '1' ); ?>><?php echo $randomize_label; ?></label>
				</td>
				<td><input type="button" name="remove_rule_<?php echo $c; ?>" value="X" class="button remove_rule" id="remove_rule_<?php echo $c; ?>"></td>
			</tr>
<?php
		$c = $c + 1;
	} // foreach()
} else {
	// show default taxonomy rule row
?>
			<tr id="row_<?php echo $c; ?>">
				<td class="num"><?php echo $c; ?></td>
				<td>
					<input type="hidden" name="rules[<?php echo $c; ?>][ids]" id="multiple_image_ids_<?php echo $c; ?>" value="">
					<input type="hidden" name="rules[<?php echo $c; ?>][id]" id="image_id_<?php echo $c; ?>" value="0">
					<img src="<?php echo $no_thumb_url; ?>" alt="<?php echo $feat_img_label; ?>" id="selected_image_<?php echo $c; ?>" />
				</td>
				<td>
					<input type="button" name="single_image_<?php echo $c; ?>" value="<?php echo $choose_image_label; ?>" class="button single_image" id="single_image_<?php echo $c; ?>" />
					<input type="button" name="multiple_images_<?php echo $c; ?>" value="<?php echo $button_multiples_label; ?>" class="button multiple_images" id="multiple_images_<?php echo $c; ?>" /><br />
					<label for="taxonomy_<?php echo $c; ?>"><?php echo $taxonomy_label; ?></label><br />
					<select name="rules[<?php echo $c; ?>][taxonomy]" id="taxonomy_<?php echo $c; ?>" class="selection_rules">
						<option value=""><?php echo $first_option_label; ?></option>
<?php
		foreach ( $optionfields as $value => $label ) {
?>
						<option value="<?php echo $value; ?>"><?php echo $label; ?></option>
<?php
		} // foreach ( $optionfields )
		if ( $custom_taxonomies_terms ) {
			foreach ( $custom_taxonomies as $key => $label ) {
				if ( $key and $label ) { // ommit empty or false values
?>
						<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></option>
<?php
				}
			}
		} // if ( $custom_taxonomies_terms )
?>
						<option value="searchterm"><?php echo $search_label; ?></option>
					</select><br />
					<?php echo $matches_label; ?>:<br />
					<label for="matchterm_<?php echo $c; ?>"><?php echo $value_label; ?></label><br />
					<select name="rules[<?php echo $c; ?>][matchterm]" id="matchterm_<?php echo $c; ?>">
						<option value=""><?php echo $first_option_label; ?></option>
					</select>
					<label for="searchterm_<?php echo $c; ?>"><?php echo $searchterm_label; ?> (<?php echo $search_desc; ?>)</label>:<br />
					<input type="text" name="rules[<?php echo $c; ?>][searchterm]" id="searchterm_<?php echo $c; ?>" value=""><br />
					<label><input type="checkbox" name="rules[<?php echo $c; ?>][randomize]" id="randomize_<?php echo $c; ?>" value="1"<?php checked( isset( $rule[ 'randomize' ] ), '1' ); ?>><?php echo $randomize_label; ?></label>

				</td>
				<td><input type="button" name="remove_rule_<?php echo $c; ?>" value="X" class="button remove_rule" id="remove_rule_<?php echo $c; ?>"></td>
			</tr>
<?php
} // if( rules )
?>
			<tr id="template_row">
				<td class="num">XX</td>
				<td>
					<input type="hidden" name="rules[XX][ids]" id="multiple_image_ids_XX" value="">
					<input type="hidden" name="rules[XX][id]" id="image_id_XX" value="0">
					<img src="<?php echo $no_thumb_url; ?>" alt="<?php echo $feat_img_label; ?>" id="selected_image_XX">
				</td>
				<td>
					<input type="button" name="single_image_XX" value="<?php echo $choose_image_label; ?>" class="button single_image" id="single_image_XX">
					<input type="button" name="multiple_images_XX" value="<?php echo $button_multiples_label; ?>" class="button multiple_images" id="multiple_images_XX"><br />
					<label for="taxonomy_XX"><?php echo $taxonomy_label; ?></label><br />
					<select name="rules[XX][taxonomy]" id="taxonomy_XX" class="selection_rules">
						<option value=""><?php echo $first_option_label; ?></option>
<?php
foreach ( $optionfields as $value => $label ) {
?>
						<option value="<?php echo $value; ?>"><?php echo $label; ?></option>
<?php
} // foreach ( $optionfields )

if ( $custom_taxonomies_terms ) {
	foreach ( $custom_taxonomies as $key => $label ) {
		if ( $key and $label ) { // ommit empty or false values
?>
						<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></option>
<?php
		}
	}
} // if ( $custom_taxonomies_terms )
?>
						<option value="searchterm"><?php echo $search_label; ?></option>
					</select><br />
					<?php echo $matches_label; ?>:<br />
					<label for="matchterm_XX"><?php echo $value_label; ?></label><br />
					<select name="rules[XX][matchterm]" id="matchterm_XX">
						<option value=""><?php echo $first_option_label; ?></option>
					</select>
					<label for="searchterm_XX"><?php echo $searchterm_label; ?> (<?php echo $search_desc; ?>)</label>:<br />
					<input type="text" name="rules[XX][searchterm]" id="searchterm_XX" value=""><br />
					<label><input type="checkbox" name="rules[XX][randomize]" id="randomize_XX" value="1"><?php echo $randomize_label; ?></label>
				</td>
				<td><input type="button" name="remove_rule_XX" value="X" class="button remove_rule" id="remove_rule_XX"></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<th class="num"><?php echo $number_label; ?></th>
				<th><?php echo $image_label; ?></th>
				<th><?php echo $description_label; ?></th>
				<th><?php echo $action_label; ?></th>
			</tr>
		</tfoot>
	</table>
	<?php submit_button( __( 'Add rule', 'quick-featured-images-pro' ), 'secondary', 'add_rule_button' ); ?>
	<h2><?php esc_html_e( 'Set rules order', 'quick-featured-images-pro' ); ?></h2>
	<p><?php esc_html_e( 'Set the order of the rules by selecting the number in the selection fields. 1 means in the first place, 2 means in the second place etc. If some rules have the same number it is unforeseeable in which order they will be applied. So set the order uniquely.', 'quick-featured-images-pro' ); ?></p>
	<p><?php esc_html_e( 'Regardless of the order in the list the rules are applied in the following order until a rule and a property of the post fit together:', 'quick-featured-images-pro' ); ?></p>
	<ol class="qfi_styled_numbers">
<?php foreach ( $this->selected_rules[ 'rules_order' ] as $name => $index ) { ?>
		<li><label><select name="rules_order[<?php echo $name; ?>]">
<?php 	foreach ( range( 1, 8 ) as $i ) { ?>
				<option value="<?php echo $i; ?>"<?php selected( $index, $i ); ?>><?php echo $i; ?></option>
<?php 	} ?>
			</select> <?php
	switch ( $name ) {
		case 'first_image':
			_e( 'found first image. If not then...', 'quick-featured-images-pro' );
			break;
		case 'matched_search':
			_e( 'matched search string in post title. If not then...', 'quick-featured-images-pro' );
			break;
		case 'matched_taxonomy':
			_e( 'matched custom taxonomy. If not then...', 'quick-featured-images-pro' );
			break;
		case 'matched_tag':
			_e( 'matched tag. If not then...', 'quick-featured-images-pro' );
			break;
		case 'matched_category':
			_e( 'matched category. If not then...', 'quick-featured-images-pro' );
			break;
		case 'matched_author':
			_e( 'matched author. If not then...', 'quick-featured-images-pro' );
			break;
		case 'matched_postformat':
			_e( 'matched post format. If not then...', 'quick-featured-images-pro' );
			break;
		case 'matched_posttype':
			_e( 'matched post type. If not then...', 'quick-featured-images-pro' );
			break;
	} // switch ( name )
	?></label>
		</li>
<?php } // foreach ( rules_order ) ?>
		<li><span><?php esc_html_e( 'no featured image.', 'quick-featured-images-pro' ); ?></span></li>
	</ol>
	<p><?php esc_html_e( 'Bear in mind that if two or more rules with the same taxonomy would fit to the post it is unforeseeable which image will become the featured image.', 'quick-featured-images-pro' ); ?></p>
<?php 
submit_button( $button_label );
wp_nonce_field( 'save_default_images', 'knlk235rf' );
?>
	<input type="hidden" id="placeholder_url" name="placeholder_url" value="<?php echo $no_thumb_url; ?>" />
	<input type="hidden" id="confirmation_question" name="confirmation_question" value="<?php esc_attr_e( 'Are you sure to remove this rule?', 'quick-featured-images-pro' ); ?>" />
	<input type="hidden" id="selection_advice" name="selection_advice" value="<?php esc_attr_e( 'Use CTRL for multiple choice', 'quick-featured-images-pro' ); ?>" />
</form>
