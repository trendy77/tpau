<?php
/*
* Plugin Name: ThirstyAffiliates
*
* Description: ThirstyAffiliates is a revolution in affiliate link management. Collect, collate and store your affiliate links for use in your posts and pages.
*
* Author: ThirstyAffiliates
* Author URI: http://thirstyaffiliates.com
* Plugin URI: http://thirstyaffiliates.com
* Version: 2.6.4
* Text Domain: thirstyaffiliates
* Domain Path: /languages
*/

define('THIRSTY_VERSION', '2.6.4', false);

/*******************************************************************************
** thirstyAffiliatesLoadPluginTextdomain
** Load Text Domain on International Translations
** @since 1.0
*******************************************************************************/
function thirstyAffiliatesLoadPluginTextdomain() {
    load_plugin_textdomain( 'thirstyaffiliates', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}

/*******************************************************************************
** thirstyRegisterPostType
** Register the post types required by the plugin
** @since 1.0
*******************************************************************************/
function thirstyRegisterPostType() {
	$thirstyOptions = get_option('thirstyOptions');
	$slug = thirstyGetCurrentSlug();

	/* Register the taxonomy for the affiliate links */
	register_taxonomy(
		'thirstylink-category',
		'thirstylink',
		array(
			'labels' => array(
				'name' => __('Link Categories', 'thirstyaffiliates'),
				'singular_name' => __('Link Category', 'thirstyaffiliates')
			),
			'public' => true,
			'show_ui' => true,
			'hierarchical' => true,
			'show_tagcloud' => false,
			'rewrite' => false
		)
	);

	/* Register the post type */
	register_post_type(
		'thirstylink',
		array(
			'labels' => array(
				'name' => __('Affiliate Links', 'thirstyaffiliates'),
				'singular_name' => __('Affiliate Link', 'thirstyaffiliates'),
				'add_new_item' => __('Add New Affiliate Link', 'thirstyaffiliates'),
				'edit_item' => __('Edit Affiliate Link', 'thirstyaffiliates'),
				'view_item' => __('View Affiliate Link', 'thirstyaffiliates'),
				'search_items' =>  __('Search Affiliate Links', 'thirstyaffiliates'),
				'not_found' => __('No Affiliate Links found!', 'thirstyaffiliates'),
				'not_found_in_trash' => __('No Affiliate Links found in trash', 'thirstyaffiliates'),
				'menu_name' => __('Affiliate Links', 'thirstyaffiliates'),
				'all_items' => __('All Affiliate Links', 'thirstyaffiliates')
			),
			'description' => __('ThirstyAffiliates affiliate links', 'thirstyaffiliates'),
			'public' => true,
			'menu_position' => 20,
			'hierarchical' => true,
			'supports' => array(
				'title' => false,
				'editor' => false,
				'author' => false,
				'thumbnail' => false,
				'excerpt' => false,
				'trackbacks' => false,
				'comments' => false,
				'revisions' => false,
				'page-attributes' => false,
				'post-formats' => false
			),
			'show_in_menu' => true,
			'show_in_nav_menus' => true,
			'can_export' => true,
			'has_archive' => false,
			'rewrite' => array(
				'slug' => $slug,
				'with_front' => false,
				'pages' => false
			),
			'menu_icon' => plugins_url('thirstyaffiliates/images/icon-aff.png'),
			'exclude_from_search' => true
		)
	);

	add_rewrite_tag('%' . $slug . '%', '([^&]+)');

	if (!empty($thirstyOptions['showcatinslug']) && $thirstyOptions['showcatinslug'] == 'on') {
		add_rewrite_tag('%thirstylink-category%', '([^&]+)');
		add_rewrite_rule("$slug/([^/]+)?/?$",'index.php?thirstylink=$matches[1]', 'top'); // 2.4.5: still match links that don't have category in the url
		add_rewrite_rule("$slug/([^/]+)?/?([^/]+)?/?",'index.php?thirstylink=$matches[2]&thirstylink-category=$matches[1]', 'top');
	}

	/* Set the list page columns */
	add_filter('manage_thirstylink_posts_columns', 'thirstyAddDestinationColumnToList');
	add_filter('manage_thirstylink_posts_columns', 'thirstyAddCategoryColumnToList');
	add_action('manage_pages_custom_column', 'thirstyShowCategoryColumnInList');
	add_action('manage_pages_custom_column', 'thirstyShowDestinationColumnInList');

	/* Setup the filter drop down */
	add_action('restrict_manage_posts', 'thirstyRestrictLinksByCategory');
	add_filter('parse_query', 'thirstyConvertLinkCatIdToSlugInQuery');
}

/*******************************************************************************
** thirstyForceSend
** Force showing the "Insert into Post" button on the media handler for links
** @since 2.1
*******************************************************************************/
function thirstyForceSend($args){
	if (!empty($_GET['post_id']))
		$pid = $_GET['post_id'];
	else
		return $args;

	if(get_post_type($pid) == 'thirstylink') {
		$args['send'] = true;
	}
	return $args;
}

/*******************************************************************************
** thirstyGetCurrentSlug
** Get the current link prefix setting
** @since 2.1
*******************************************************************************/
function thirstyGetCurrentSlug() {
	$thirstyOptions = get_option('thirstyOptions');
	$slug = 'recommends';

	if (isset($thirstyOptions['linkprefix'])) {
		if ($thirstyOptions['linkprefix'] == 'custom' && isset($thirstyOptions['linkprefixcustom'])) {
			$slug = $thirstyOptions['linkprefixcustom'];
		} else {
			$slug = $thirstyOptions['linkprefix'];
		}
	}

	return $slug;
}

/*******************************************************************************
** thirstyCatLinks
** Handle how link slugs are created and optionally add in the categories
** @since 2.1
*******************************************************************************/
function thirstyCatLinks($post_link, $post) {
	$thirstyOptions = get_option('thirstyOptions');
	$slug = thirstyGetCurrentSlug();

	if (is_wp_error($post) ||
		empty($post) ||
		(!empty($post) && $post->post_type != 'thirstylink') ||
		empty($post->post_name))
		return $post_link;

	// Get the link category:
	$terms = get_the_terms($post->ID, 'thirstylink-category');

	if (empty($thirstyOptions['showcatinslug']) ||
		$thirstyOptions['showcatinslug'] != 'on' ||
		is_wp_error($terms) || !$terms) {
		$linkCat = '';
	} else {
		$linkCatObj = array_pop($terms);
		$linkCat = $linkCatObj->slug . '/';

	}

	return home_url(user_trailingslashit("$slug/$linkCat$post->post_name"));

}

/*******************************************************************************
** thirstyAddDestinationColumnToList
** Add the destination column to the list page
** @since 1.0
*******************************************************************************/
function thirstyAddDestinationColumnToList($posts_columns) {
    if (!isset($posts_columns['date'])) {
        $new_posts_columns = $posts_columns;
    } else {
        $new_posts_columns = array();
        $index = 0;
        foreach($posts_columns as $key => $posts_column) {
            if ($key=='date')
                $new_posts_columns['thirstylink-destination'] = null;
            $new_posts_columns[$key] = $posts_column;
        }
    }
    $new_posts_columns['thirstylink-destination'] = 'Link Destination';
    return $new_posts_columns;
}

/*******************************************************************************
** thirstyAddCategoryColumnToList
** Add the category column to the list page
** @since 1.0
*******************************************************************************/
function thirstyAddCategoryColumnToList($posts_columns) {
    if (!isset($posts_columns['date'])) {
        $new_posts_columns = $posts_columns;
    } else {
        $new_posts_columns = array();
        $index = 0;
        foreach($posts_columns as $key => $posts_column) {
            if ($key=='date')
                $new_posts_columns['thirstylink-category'] = null;
            $new_posts_columns[$key] = $posts_column;
        }
    }
    $new_posts_columns['thirstylink-category'] = __('Link Categories', 'thirstyaffiliates');
    return $new_posts_columns;
}

/*******************************************************************************
** thirstyShowDestinationColumnInList
** Get the destination details for the list page
** @since 1.0
*******************************************************************************/
function thirstyShowDestinationColumnInList($column) {
	global $typenow;
	global $post;

	if ($typenow == 'thirstylink') {

		switch ($column) {
		case 'thirstylink-destination':
			$linkData = unserialize(get_post_meta($post->ID, 'thirstyData', true));
            $linkData['linkurl'] = str_replace('%7B', '{', $linkData['linkurl']);
        	$linkData['linkurl'] = str_replace('%7D', '}', $linkData['linkurl']);
			echo htmlspecialchars_decode($linkData['linkurl'], ENT_COMPAT);
			break;
		}

	}
}

/*******************************************************************************
** thirstyShowCategoryColumnInList
** Get the category details for the list page
** @since 1.0
*******************************************************************************/
function thirstyShowCategoryColumnInList($column) {
	global $typenow;
	global $post;

	if ($typenow == 'thirstylink') {

		switch ($column) {
		case 'thirstylink-category':
			$taxonomy = 'thirstylink-category';
			$thirstyCats = get_the_terms($post->ID, $taxonomy);
			$thirstyCatsFormatted = array();

			if (is_array($thirstyCats) && !empty($thirstyCats)) {

				// pre-sort array by parent value
				uasort($thirstyCats, 'thirstySortArrayByParent');

				// setup sorted array
				$sortedCats = array();

				// loop through all cats
				while (!empty($thirstyCats)) {
					$term = current($thirstyCats);
					$key = key($thirstyCats);

					$sortedCats[] = $term;
					unset($thirstyCats[$key]); // pop current parent term
					$children = array();
					$children = thirstyGetChildrenOfCat($term, $thirstyCats);

					// add each child to the array
					while ($childterm = current($children)) {
						$sortedCats[] = $childterm;
						unset($thirstyCats[key($children)]);
						next($children);
					}
				}

				foreach ($sortedCats as $key => $term) {
					$editLink = admin_url('edit.php?thirstylink-category=' . $term->slug . '&post_type=thirstylink');
					$is_parent = $term->parent == 0;

					echo ($is_parent ? '<p><b>' : '&nbsp;&nbsp;') .
					'<a href="' . $editLink . '">' . $term->name . '</a>' .
					($is_parent ? '</b><br />' : '');
				}

			}
			break;
		}

	}
}

/*******************************************************************************
** thirstySortArrayByParent
** Convenience function to sort an array by it's parent
** @since 1.0
*******************************************************************************/
function thirstySortArrayByParent($a, $b) {
	if ($a->parent < $b->parent) {
		return -1;
	} else if ($a->parent > $b->parent) {
		return 1;
	} else {
		return 0;
	}
}

/*******************************************************************************
** thirstyGetChildrenOfCat
** Get the children of a thirsty affiliates category and return them
** @since 1.0
*******************************************************************************/
function thirstyGetChildrenOfCat($parent, $cats) {
	$children = array();

	if (!empty($cats)) {
		foreach ($cats as $key => $term) {
			if ($term->parent == $parent->term_id) {
				// is a child of the parent
				$children[$key] = $term;
			}
		}
	}

	return $children;
}

/*******************************************************************************
** thirstyRestrictLinksByCategory
** Setup the filter box for the list page so people can filter their links via
** category
** @since 1.0
*******************************************************************************/
function thirstyRestrictLinksByCategory() {
	global $typenow;
	global $wp_query;

	if ($typenow == 'thirstylink') {

		$taxonomy = 'thirstylink-category';
		$thirstyTax = get_taxonomy($taxonomy);

		wp_dropdown_categories(array(
			'show_option_all' => "Show {$thirstyTax->labels->all_items}",
			'taxonomy' => $taxonomy,
			'name' => $taxonomy,
			'orderby' => 'name',
			'selected' => (isset($wp_query->query[$taxonomy]) ? $wp_query->query[$taxonomy] : ''),
			'hierarchical' => true,
			'depth' => 4,
			'show_count' => true,
			'hide_empty' => true
		));
	}
}

/*******************************************************************************
** thirstyConvertLinkCatIdToSlugInQuery
** Setup the filter box for the list page so people can filter their links via
** category
** @since 1.0
*******************************************************************************/
function thirstyConvertLinkCatIdToSlugInQuery($query) {
	global $pagenow;
	$qv = &$query->query_vars;

	if (isset($qv['thirstylink-category']) &&
		is_numeric($qv['thirstylink-category'])) {

		$term = get_term_by('id', $qv['thirstylink-category'], 'thirstylink-category');
		$qv['thirstylink-category'] = $term->slug;

	}
}

/*******************************************************************************
** thirstySetupPostBoxes
** Setup the input boxes for the link post type
** @since 1.0
*******************************************************************************/
function thirstySetupPostBoxes() {
	add_meta_box(
		'thirstylink-link-name-meta',
		'Affiliate Link Name',
		'thirstyLinkNameMeta',
		'thirstylink',
		'normal',
		'high'
	);

	add_meta_box(
		'thirstylink-link-url-meta',
		'URLs',
		'thirstyLinkUrlMeta',
		'thirstylink',
		'normal',
		'high'
	);

	add_meta_box(
		'thirstylink-link-images-meta',
		'Attach Images',
		'thirstyLinkImagesMeta',
		'thirstylink',
		'normal',
		'high'
	);

	remove_meta_box( 'submitdiv', 'thirstylink', 'side' );

	add_meta_box(
		'thirstylink-save-link-side-meta',
		'Save Affiliate Link',
		'thirstySaveLinkMeta',
		'thirstyLink',
		'side',
		'high'
	);

	add_meta_box(
		'thirstylink-save-link-bottom-meta',
		'Save Affiliate Link',
		'thirstySaveLinkMeta',
		'thirstyLink',
		'normal',
		'low'
	);

	add_meta_box(
		'thirstylink-redirect-type-meta',
		'Redirect Type',
		'thirstyRedirectTypeMeta',
		'thirstyLink',
		'side',
		'low'
	);

	/* 2.4.8: Temporarily remove these notices
	add_meta_box(
		'thirstylink-addon-notices',
		'Add-ons Available',
		'thirstyAddonsAvailable',
		'thirstylink',
		'side',
		'low'
	);*/
}

/*******************************************************************************
** thirstyAddonsAvailable
** Addons available meta box
** @since 2.4.7
*******************************************************************************/
function thirstyAddonsAvailable() {

	// Get the available add-ons list
	$products = thirstyAddonsPageGetProducts();

	if (!empty($products)) {
		// Figure out which product to display
		list($usec, $sec) = explode(' ', microtime());
		mt_srand((float)$sec + ((float)$usec * 100000));

		$productCount = count($products);
		$randNum = mt_rand(0, $productCount);

		echo '<ul>';

		$product = $products[$randNum];
		$productUrl = str_replace('utm_source=rss' , 'utm_source=plugin', $product['url']);
		$productUrl = str_replace('utm_medium=rss' , 'utm_medium=addonpage', $productUrl);
		$productTitle = str_replace('ThirstyAffiliates ', '', $product['title']);
		$productTitle = str_replace(' Add-on', '', $productTitle);

		echo '<li class="thirstyaddonlinkpage">';
		echo '<h3>' . $productTitle . '</h3>';
		echo '<div class="thirstyaddondescription">' . $product['description'] . '</div>';
		echo '<a class="button-primary" href="' . $productUrl . '" target="_blank">'.__('Visit Add-on Page', 'thirstyaffiliates').' &rarr;</a>';
		echo '</li>';

		echo '</ul>';
		echo '<a href="' . admin_url('edit.php?post_type=thirstylink&page=thirsty-addons') . '">'.__('View all available add-ons &rarr;', 'thirstyaffiliates').'</a>';
	}
}

/*******************************************************************************
** thirstyRedirectTypeMeta
** Redirect type override meta box
** @since 2.3
*******************************************************************************/
function thirstyRedirectTypeMeta() {
	wp_nonce_field( plugin_basename(__FILE__), 'thirstyaffiliates_noncename' );

	global $post;
	$linkData = unserialize(get_post_meta($post->ID, 'thirstyData', true));
	$thirstyOptions = get_option('thirstyOptions');

	$redirectTypes = thirstyGetRedirectTypes();

	echo '<p>'.__('Override the default redirection type for this link:', 'thirstyaffiliates').'</p><p>';

	foreach ($redirectTypes as $redirectTypeCode => $redirectTypeDesc) {

		// set default values
		$linkTypeDefault = false;
		$linkTypeSelected = false;

		// check if this is the default link redirect type as per global settings
		if (strcasecmp($thirstyOptions['linkredirecttype'], $redirectTypeCode) == 0)
			$linkTypeDefault = true;

		// check if the link specific redirect type is empty and if this is the default link type
		// mark it as selected
		if (empty($linkData['linkredirecttype'])) {
			if ($linkTypeDefault)
				$linkTypeSelected = true;
		} else {
			if (strcasecmp($linkData['linkredirecttype'], $redirectTypeCode) == 0) {
				$linkTypeSelected = true;
			}
		}

		echo '<input type="radio" name="thirsty[linkredirecttype]" id="thirstyOptionsLinkRedirectType' . $redirectTypeCode .'" ' .
			($linkTypeSelected ? 'checked="checked" ' : '') . 'value="' . $redirectTypeCode .
			'" /> <label for="thirstyOptionsLinkRedirectType' . $redirectTypeCode .'">' .
			$redirectTypeDesc . ($linkTypeDefault ? ' (default)' : '') . '</label><br />';

	}

	//echo '</p><p><span id="thirstyClearRedirectOverride" class="button-secondary">Clear Redirect Override</span></p>';

}

/*******************************************************************************
** thirstySaveLinkMeta
** Save link meta box
** @since 1.0
*******************************************************************************/
function thirstySaveLinkMeta() {
	global $post;

	echo '<p class="thirstySaveMe">'.__('NOTE: Please save your link after adding or removing images', 'thirstyaffiliates').'</p>';
	echo '<input name="post_status" type="hidden" id="post_status" value="'.__('publish', 'thirstyaffiliates').'" />';
	echo '<input name="original_publish" type="hidden" id="original_publish" value="'.__('Save', 'thirstyaffiliates').'" />';
	echo '<input name="save" type="submit" class="button-primary" id="publish" tabindex="5" accesskey="p" value="'.__('Save Link', 'thirstyaffiliates').'">';

	if (current_user_can("delete_post", $post->ID)) {
		if (!EMPTY_TRASH_DAYS)
			$delete_text = __('Delete Permanently', 'thirstyaffiliates');
		else
			$delete_text = __('Move to Trash', 'thirstyaffiliates');

		echo '&nbsp;&nbsp;<a class="submitdelete deletion" href="' . get_delete_post_link($post->ID) . '">' . $delete_text . '</a>';
	}
}

/*******************************************************************************
** thirstyLinkNameMeta
** Link name meta box
** @since 1.0
*******************************************************************************/
function thirstyLinkNameMeta() {
	wp_nonce_field( plugin_basename(__FILE__), 'thirstyaffiliates_noncename' );

	global $post;
	$linkData = unserialize(get_post_meta($post->ID, 'thirstyData', true));

	$thirstyOptions = get_option('thirstyOptions');
	echo '<p><label class="infolabel" for="post_title">'.__('Link Name:', 'thirstyaffiliates').'</label><span id="link_id" style="float:right;">'.__('Link ID:', 'thirstyaffiliates').' <strong>' . $post->ID . '</strong></span></p>';
	echo '<p style="clear:both;"><input id="thirsty_linkname" name="post_title" value="' . htmlspecialchars(!empty($linkData['linkname']) ? $linkData['linkname'] : '') .
	'" size="50" type="text" /></p>';

	if (isset($_GET['debug']) && $_GET['debug'] == true) {
		echo '<pre>'.__('DEBUG: ') . print_r($linkData, true) . '</pre>';
	}

}

/*******************************************************************************
** thirstyLinkUrlMeta
** Link slug meta box
** @since 1.0
*******************************************************************************/
function thirstyLinkUrlMeta() {
	wp_nonce_field( plugin_basename(__FILE__), 'thirstyaffiliates_noncename' );

	global $post;
	$linkData = unserialize(get_post_meta($post->ID, 'thirstyData', true));

	$linkData['nofollow'] = isset($linkData['nofollow']) ? 'checked="checked"' : '';
	$linkData['newwindow'] = isset($linkData['newwindow']) ? 'checked="checked"' : '';

	$linkUrl = isset($linkData['linkurl']) ? $linkData['linkurl'] : "";
    $linkUrl = str_replace('%7B', '{', $linkUrl);
    $linkUrl = str_replace('%7D', '}', $linkUrl);
    $linkUrl = html_entity_decode((!empty($linkUrl) ? $linkUrl : ''));

	$thirstyOptions = get_option('thirstyOptions');

	echo '<style>
	label.infolabel {
		margin-right: 10px;
	}
	</style>';

	echo '<p><label class="infolabel" for="thirsty[linkurl]">'.__('Destination URL:', 'thirstyaffiliates').'</label></p>';
	echo '<p><input id="thirsty_linkurl" name="thirsty[linkurl]" value="' . $linkUrl . '" size="50" type="text" /></p>';

	/* Only show permalink if it's an existing post */
	if (!empty($post->post_title)) {
		echo '<p><label class="infolabel">'.__('Cloaked URL:', 'thirstyaffiliates').'</label></p>';
		echo '<input type="text" readonly="readonly" id="thirsty_cloakedurl" value="' . get_post_permalink($post->ID) . '"> <span class="button-secondary" id="thirstyEditSlug">'.__('Edit Slug', 'thirstyaffiliates').'</span> <a href="' . get_post_permalink($post->ID) . '" target="_blank"><span class="button-secondary" id="thirstyVisitLink">'.__('Visit Link').'</span></a><input id="thirsty_linkslug" name="post_name" value="' . $post->post_name . '" size="50" type="text" /></span> <input id="thirstySaveSlug" type="button" value="'.__('Save').'" class="button-secondary" /></p>';
	}

	/* Only display link nofollow setting if the global nofollow setting is disabled */
	if (!isset($thirstyOptions['nofollow'])) {
		echo '<p><label class="infolabel" for="thirsty_nofollow">'.__('No follow this link?:', 'thirstyaffiliates').'</label>
		<input id="thirsty_nofollow" name="thirsty[nofollow]" ' . $linkData['nofollow'] . ' type="checkbox" />
		<span class="thirsty_description">'.__('Adds the rel="nofollow" tag so search engines don\'t pass link juice', 'thirstyaffiliates').'</span></p>';
	}

	/* Only display link new window setting if the global new window setting is disabled */
	if (!isset($thirstyOptions['newwindow'])) {
		echo '<p><label class="infolabel" for="thirsty_newwindow">'.__('Open this link in new window?', 'thirstyaffiliates').'</label>
		<input id="thirsty_newwindow" name="thirsty[newwindow]" ' . $linkData['newwindow'] . ' type="checkbox" />
		<span class="thirsty_description">'.__('Opens links in a new window when clicked on', 'thirstyaffiliates').'</span></p>';
	}
}

/*******************************************************************************
** thirstyLinkImagesMeta
** Link image control meta box
** @since 1.0
*******************************************************************************/
function thirstyLinkImagesMeta() {
	wp_nonce_field( plugin_basename(__FILE__), 'thirstyaffiliates_noncename' );

	global $post;
	$thirstyOptions = get_option('thirstyOptions');
	$legacyUploader = (isset($thirstyOptions['legacyuploader']) && $thirstyOptions['legacyuploader'] == 'on') ? true : false;

	if (function_exists('wp_enqueue_media') && !$legacyUploader) {
		// New media uploader
		echo '<div id="thirsty_upload_media_manager" data-editor="content" data-uploader-title="'.__('Add Image To Affiliate Link', 'thirstyaffiliates').'" data-uploader-button-text="'.__('Add To Affiliate Link').'" class="button-secondary">'.__('Upload/Insert').'&nbsp;&nbsp;<img id="thirsty_add_images" src="' . plugins_url('thirstyaffiliates/') . 'images/media-button.png" alt="'.__('Upload/Insert images', 'thirstyaffiliates').'" /></div>';
	} else {
		echo '<div id="thirsty_upload_insert_img" class="button-secondary">'.__('Upload/Insert', 'thirstyaffiliates').'&nbsp;&nbsp;<a class="thickbox" href="' . trailingslashit(get_bloginfo('url')) .
				'wp-admin/media-upload.php?post_id=' . $post->ID . '?type=image&TB_iframe=1"><img id="thirsty_add_images" src="' . plugins_url('thirstyaffiliates/') . 'images/media-button.png" alt="'.__('Upload/Insert images').'" /></a></div>';
	}


	echo '<div id="content">&nbsp;</div>
	<script type="text/javascript">';

	global $wp_version;
	if ($wp_version >= 3.3) {
		// JMK: WP 3.3+ fix for insert post bug
		echo 'var wpActiveEditor = \'content\';';
	} else {
		// JMK: Pre WP 3.3 fix for insert post bug
		echo 'edCanvas = document.getElementById("content");';
	}

	echo '</script>';

	$attachment_args = array(
		'post_type' => 'attachment',
		'posts_per_page' => -1,
		'post_status' => null,
		'post_parent' => $post->ID,
		'orderby' => 'menu_order',
		'order' => 'ASC'
	);

	$attachments = get_posts($attachment_args);

	if ($attachments) {
		echo '<div id="thirsty_image_holder">';
		foreach ($attachments as $attachment) {
			$img = wp_get_attachment_image_src($attachment->ID, 'full');
			echo '<div class="thirstyImgHolder"><span class="thirstyRemoveImg" title="'.__('Remove This Image').'" id="' . $attachment->ID . '"></span><a class="thirstyImg thickbox" href="' . $img[0] . '" rel="gallery-linkimgs" title="' . $attachment->post_title . '">';
			echo wp_get_attachment_image($attachment->ID, array(100, 100));
			echo '</a></div>';
		}
		echo '</div>';
	}
}

/*******************************************************************************
** thirstySavePost
** Save the link post data into the post's meta
** @since 1.0
*******************************************************************************/
function thirstySavePost($post_id) {

	/* Make sure we only do this for thirstylinks on regular saves and we have permission */
	if (empty($_POST['post_type']) || $_POST['post_type'] != 'thirstylink') {
		return $post_id;
	}

	if (!wp_verify_nonce( $_POST['thirstyaffiliates_noncename'], plugin_basename(__FILE__) ) ||
		(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
		!current_user_can( 'edit_page', $post_id ) ) {
		return $post_id;
	}

	/* Get ThirstyAffiliates settings */
	$thirstyOptions = get_option('thirstyOptions');

	/* Get link data from post array */
	$linkDataNew = array();
	$linkDataNew = $_POST['thirsty'];

	/* Set the link data to be the new link data */
	$linkData = thirstyFilterData($linkDataNew);
	/* Because we trick wordpress into setting the post title by using our field
	** name as post_title we need to make sure our meta data is updated to reflect
	** that correct name.
	** New in 2.1.2: also need to stripslashes here so we can handle quotes below */
	$linkData['linkname'] = stripslashes($_POST['post_title']);

	/* Manually handle curly brackets { } and quotes */
	$linkData['linkurl'] = str_replace('{', '%7B', $linkData['linkurl']);
	$linkData['linkurl'] = str_replace('}', '%7D', $linkData['linkurl']);
	$linkData['linkname'] = str_replace("'", '', $linkData['linkname']);
	$linkData['linkname'] = str_replace('"', '', $linkData['linkname']);

	/* If we are using categories in slugs force user to select a category */
	if (!empty($thirstyOptions['showcatinslug']) && $thirstyOptions['showcatinslug'] == 'on') {
		$selectedLinkCats = wp_get_post_terms($post_id, 'thirstylink-category');

		if ((!isset($thirstyOptions['disablecatautoselect']) || $thirstyOptions['disablecatautoselect'] != 'on') &&
			empty($selectedLinkCats)) {

			$defaultCat = 'Uncategorized';

			// create the default term if it doesn't exist
			if (!term_exists($defaultCat, 'thirstylink-category')) {
				wp_insert_term($defaultCat, 'thirstylink-category');
			}

			// get the default term and set this post to have it
			$defaultTerm = get_term_by('name', $defaultCat, 'thirstylink-category');
			wp_set_post_terms($post_id, $defaultTerm->term_id, 'thirstylink-category');
		}
	}

    // 2.4.10: Add filter before saving the link
    $linkData = apply_filters('thirstyBeforeDataSave', $linkData);

	/* Update the link data */
	update_post_meta($post_id, 'thirstyData', serialize($linkData));

	if (isset($linkData['linkslug']) && !empty($linkData['linkslug'])) {
		$_POST['post_name'] = $linkData['linkslug'];
	}

	$_POST['post_status'] = 'publish';
}

/*******************************************************************************
** thirstyFilterData
** Filter all the data for nasty surprises in the input forms
** @since 2.1.2
*******************************************************************************/
function thirstyFilterData($data) {
	if (is_array($data)) {
		foreach ($data as $key => $elem) {
			// 2.3 (jkohlbach) - filtered data wasn't being passed on to array elements properly
			$data[$key] = thirstyFilterData($elem);
		}
	} else {
		// 2.3 (jkohlbach) - harden up the filtering so we can use wp_editor in some add-ons
		if (empty($data))
			return $data;

		$data = nl2br(trim(htmlspecialchars(wp_kses_post($data), ENT_QUOTES, 'UTF-8')));
		$breaks = array("\r\n", "\n", "\r");
		$data = str_replace($breaks, "", $data);

		//if (get_magic_quotes_gpc())
        $data = stripslashes($data);// Strip slahes out, make sure to unescape it before passing to esc_sql
		$data = esc_sql($data);
	}
    return $data;
}

/*******************************************************************************
** thirstyDraftToPublish
** Don't let user save drafts, make them go straight to published
** @since 1.0
*******************************************************************************/
function thirstyDraftToPublish($post_id) {
	$update_status_post = array();
	$update_status_post['ID'] = $post_id;
	$update_status_post['post_status'] = 'publish';

	// Update the post into the database
	wp_update_post($update_status_post);
}

/*******************************************************************************
** thirstyEditorButtons
** Add the tinyMCE button
** @since 1.0
*******************************************************************************/
function thirstyEditorButtons() {
	if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
		return;

	$thirstyOptions = get_option('thirstyOptions');

	if (!isset($thirstyOptions['disablevisualeditorbuttons']) || $thirstyOptions['disablevisualeditorbuttons'] != 'on') {
		if (get_user_option('rich_editing') == 'true') {
			add_filter('mce_external_plugins', 'thirstyMCEButton');
			add_filter('mce_buttons', 'thirstyRegisterMCEButton', 5);
		}
	}
}

/*******************************************************************************
** thirstyMCEButton
** Add the tinyMCE button
** @return array - array of plugins for tinyMCE with ThirstyAffiliates plugin
** @since 1.0
*******************************************************************************/
function thirstyMCEButton($plugin_array) {
	//$plugin_array['thirstyaffiliates'] = plugins_url('thirstyaffiliates/thirstymce/editor_plugin.js');
	$plugin_array['thirstyaffiliates'] = plugins_url('thirstyaffiliates/thirstymce/editor_plugin_src.js');
	return $plugin_array;
}

/*******************************************************************************
** thirstyRegisterMCEButton
** Register the tinyMCE button
** @return array - buttons array with thirstyaffiliate button included
** @since 1.0
*******************************************************************************/
function thirstyRegisterMCEButton($buttons) {
	array_push($buttons, 'separator', 'thirstyaffiliates_button');
	array_push($buttons, 'separator', 'thirstyaffiliates_quickaddlink_button');
	return $buttons;
}

/*******************************************************************************
** thirstyRedirectUrl
** Handle redirects to thirstylink link urls
** @since 1.0
*******************************************************************************/
function thirstyRedirectUrl() {
	global $post;

	if (get_post_type($post) == 'thirstylink') {
		// Get link data and set the redirect url
		$linkData = unserialize(get_post_meta($post->ID, 'thirstyData', true));
		$thirstyOptions = get_option('thirstyOptions');

		// Set redirect URL
        $linkData['linkurl'] = html_entity_decode($linkData['linkurl']);
        $redirectUrl = $linkData['linkurl'];

		// Set redirect type
		$redirectType = $linkData['linkredirecttype'];
		if (empty($redirectType))
			$redirectType = $thirstyOptions['linkredirecttype'];

		// Apply any filters to the url before redirecting
		$redirectUrl = apply_filters('thirstyFilterRedirectUrl', $redirectUrl);
		$redirectType = apply_filters('thirstyFilterRedirectType', $redirectType);

		// Perform any actions before redirecting
		do_action('thirstyBeforeLinkRedirect', $post->ID, $redirectUrl, $redirectType);

		if (empty($redirectType))
			$redirectType = 301; // default to 301 redirect

		// Redirect the page
		if (!empty($redirectUrl))
			wp_redirect($redirectUrl, intval($redirectType));
		exit();
	}
}

/*******************************************************************************
** thirstyAdminHeader
** Add some javascript/css to the admin header that is required later
** @since 1.0
*******************************************************************************/
function thirstyAdminHeader() {
	global $post;
	$thirstyOptions = get_option('thirstyOptions');
	$legacyUploader = (isset($thirstyOptions['legacyuploader']) && $thirstyOptions['legacyuploader'] == 'on') ? true : false;

	$thirstyJSEnable = 'false';

	if (!empty($post->post_type) && $post->post_type == 'thirstylink') {
		$thirstyJSEnable = 'true';
	}

	echo "\n<!-- ThirstyAffiliates -->\n" .
	'<script type="text/javascript">' . "\n" .
	'	var thirstyAjaxLink = "' . admin_url('admin-ajax.php') . '";' . "\n" .
	'	var thirstyPluginDir = "' . plugins_url('thirstyaffiliates/') . '";' . "\n" .
	'	var thirstyJSEnable = ' . $thirstyJSEnable . ';' . "\n" .
	"</script>\n\n";


	// always queue thickbox
	wp_enqueue_script('thickbox', true);
	wp_enqueue_style('thickbox');
	if (function_exists('wp_enqueue_media') && !$legacyUploader) {
		wp_enqueue_media();
	} else {
		if ($legacyUploader) // 2.4.9 only load if we're using the legacy uploader
			wp_enqueue_script('media');
	}

	if (!empty($post->post_type) && $post->post_type == 'thirstylink' ||
		(isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] == 'thirsty-addons') ||
		(isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] == 'thirsty-settings')) {
		wp_enqueue_style( 'thirstyStylesheet', plugins_url('thirstyaffiliates/css/thirstystyle.css'));

		wp_dequeue_script('jquery-ui-sortable');
		wp_dequeue_script('admin-scripts');
		wp_enqueue_script(
			'thirstyhelper',
			plugins_url('thirstyaffiliates/js/thirstyhelper.js'),
			array('jquery')
		);
	}

	thirstyQuicktags();
}

/*******************************************************************************
** thirstyHeader
** Add some javascript/css front end header that is required later
** @since 2.2
*******************************************************************************/
function thirstyHeader() {
	// Reserved for future use
}

/*******************************************************************************
** thirstyAddSettingsLinkToPluginPage
** Add a settings link to the plugin on the plugins page
** @since 1.0
*******************************************************************************/
function thirstyAddSettingsLinkToPluginPage($links, $file) {
	if ($file == plugin_basename(__FILE__)){
		$settings_link = '<a href="edit.php?post_type=thirstylink&page=thirsty-settings">' . __('Settings', 'thirstyaffiliates') . '</a>';
		array_unshift($links, $settings_link);
	}
	return $links;
}

/*******************************************************************************
** thirstyTrimSlugs
** Make sure links are nice and short. This functionality was adapted from SEO
** slugs plugin by Andrei Mikrukov.
** @since 2.1.1
*******************************************************************************/
function thirstyTrimSlugs($slug) {
    /* Don't change existing slugs */
	if ($slug)
		return $slug;

	// 2.4.2: If the global disable option is checked we should not do anything
	$thirstyOptions = get_option('thirstyOptions');

	if (isset($thirstyOptions['disableslugshortening']) && $thirstyOptions['disableslugshortening'] == 'on')
		return $slug;

	// 2.4.2: If the post isn't an affiliate link, skip slug shortening
	if (isset($_POST['post_ID']) && get_post_type($_POST['post_ID']) != 'thirstylink')
		return $slug;

    // 2.6.1: check for post_title before using it
    if (!isset($_POST['post_title']))
        return $slug;

    /* Get the slug from the title */
	$shortSlug = strtolower(stripslashes($_POST['post_title']));

	/* Sanitize the slug string */
	$shortSlug = preg_replace('/&.+?;/', '', $shortSlug);
    $shortSlug = preg_replace ("/[^a-zA-Z0-9 \']/", "", $shortSlug);

    /* Strip common words */
    $commonWords = array("a", "able", "about", "above", "abroad", "according", "accordingly", "across", "actually", "adj", "after", "afterwards", "again", "against", "ago", "ahead", "ain't", "all", "allow", "allows", "almost", "alone", "along", "alongside", "already", "also", "although", "always", "am", "amid", "amidst", "among", "amongst", "an", "and", "another", "any", "anybody", "anyhow", "anyone", "anything", "anyway", "anyways", "anywhere", "apart", "appear", "appreciate", "appropriate", "are", "aren't", "around", "as", "a's", "aside", "ask", "asking", "associated", "at", "available", "away", "awfully", "b", "back", "backward", "backwards", "be", "became", "because", "become", "becomes", "becoming", "been", "before", "beforehand", "begin", "behind", "being", "believe", "below", "beside", "besides", "best", "better", "between", "beyond", "both", "brief", "but", "by", "c", "came", "can", "cannot", "cant", "can't", "caption", "cause", "causes", "certain", "certainly", "changes", "clearly", "c'mon", "co", "co.", "com", "come", "comes", "concerning", "consequently", "consider", "considering", "contain", "containing", "contains", "corresponding", "could", "couldn't", "course", "c's", "currently", "d", "dare", "daren't", "definitely", "described", "despite", "did", "didn't", "different", "directly", "do", "does", "doesn't", "doing", "done", "don't", "down", "downwards", "during", "e", "each", "edu", "eg", "eight", "eighty", "either", "else", "elsewhere", "end", "ending", "enough", "entirely", "especially", "et", "etc", "even", "ever", "evermore", "every", "everybody", "everyone", "everything", "everywhere", "ex", "exactly", "example", "except", "f", "fairly", "far", "farther", "few", "fewer", "fifth", "first", "five", "followed", "following", "follows", "for", "forever", "former", "formerly", "forth", "forward", "found", "four", "from", "further", "furthermore", "g", "get", "gets", "getting", "given", "gives", "go", "goes", "going", "gone", "got", "gotten", "greetings", "h", "had", "hadn't", "half", "happens", "hardly", "has", "hasn't", "have", "haven't", "having", "he", "he'd", "he'll", "hello", "help", "hence", "her", "here", "hereafter", "hereby", "herein", "here's", "hereupon", "hers", "herself", "he's", "hi", "him", "himself", "his", "hither", "hopefully", "how", "howbeit", "however", "hundred", "i", "i'd", "ie", "if", "ignored", "i'll", "i'm", "immediate", "in", "inasmuch", "inc", "inc.", "indeed", "indicate", "indicated", "indicates", "inner", "inside", "insofar", "instead", "into", "inward", "is", "isn't", "it", "it'd", "it'll", "its", "it's", "itself", "i've", "j", "just", "k", "keep", "keeps", "kept", "know", "known", "knows", "l", "last", "lately", "later", "latter", "latterly", "least", "less", "lest", "let", "let's", "like", "liked", "likely", "likewise", "little", "look", "looking", "looks", "low", "lower", "ltd", "m", "made", "mainly", "make", "makes", "many", "may", "maybe", "mayn't", "me", "mean", "meantime", "meanwhile", "merely", "might", "mightn't", "mine", "minus", "miss", "more", "moreover", "most", "mostly", "mr", "mrs", "much", "must", "mustn't", "my", "myself", "n", "name", "namely", "nd", "near", "nearly", "necessary", "need", "needn't", "needs", "neither", "never", "neverf", "neverless", "nevertheless", "new", "next", "nine", "ninety", "no", "nobody", "non", "none", "nonetheless", "noone", "no-one", "nor", "normally", "not", "nothing", "notwithstanding", "novel", "now", "nowhere", "o", "obviously", "of", "off", "often", "oh", "ok", "okay", "old", "on", "once", "one", "ones", "one's", "only", "onto", "opposite", "or", "other", "others", "otherwise", "ought", "oughtn't", "our", "ours", "ourselves", "out", "outside", "over", "overall", "own", "p", "particular", "particularly", "past", "per", "perhaps", "placed", "please", "plus", "possible", "presumably", "probably", "provided", "provides", "q", "que", "quite", "qv", "r", "rather", "rd", "re", "really", "reasonably", "recent", "recently", "regarding", "regardless", "regards", "relatively", "respectively", "right", "round", "s", "said", "same", "saw", "say", "saying", "says", "second", "secondly", "see", "seeing", "seem", "seemed", "seeming", "seems", "seen", "self", "selves", "sensible", "sent", "serious", "seriously", "seven", "several", "shall", "shan't", "she", "she'd", "she'll", "she's", "should", "shouldn't", "since", "six", "so", "some", "somebody", "someday", "somehow", "someone", "something", "sometime", "sometimes", "somewhat", "somewhere", "soon", "sorry", "specified", "specify", "specifying", "still", "sub", "such", "sup", "sure", "t", "take", "taken", "taking", "tell", "tends", "th", "than", "thank", "thanks", "thanx", "that", "that'll", "thats", "that's", "that've", "the", "their", "theirs", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "there'd", "therefore", "therein", "there'll", "there're", "theres", "there's", "thereupon", "there've", "these", "they", "they'd", "they'll", "they're", "they've", "thing", "things", "think", "third", "thirty", "this", "thorough", "thoroughly", "those", "though", "three", "through", "throughout", "thru", "thus", "till", "to", "together", "too", "took", "toward", "towards", "tried", "tries", "truly", "try", "trying", "t's", "twice", "two", "u", "un", "under", "underneath", "undoing", "unfortunately", "unless", "unlike", "unlikely", "until", "unto", "up", "upon", "upwards", "us", "use", "used", "useful", "uses", "using", "usually", "v", "value", "various", "versus", "very", "via", "viz", "vs", "w", "want", "wants", "was", "wasn't", "way", "we", "we'd", "welcome", "well", "we'll", "went", "were", "we're", "weren't", "we've", "what", "whatever", "what'll", "what's", "what've", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "where's", "whereupon", "wherever", "whether", "which", "whichever", "while", "whilst", "whither", "who", "who'd", "whoever", "whole", "who'll", "whom", "whomever", "who's", "whose", "why", "will", "willing", "wish", "with", "within", "without", "wonder", "won't", "would", "wouldn't", "x", "y", "yes", "yet", "you", "you'd", "you'll", "your", "you're", "yours", "yourself", "yourselves", "you've", "z", "zero");
    $shortSlugArray = array_diff(preg_split("/ /", $shortSlug), $commonWords);

    /* Turn it back into a string before returning */
    $shortSlug = join("-", $shortSlugArray);
	return $shortSlug;
}

/*******************************************************************************
** thirstyQuicktags
** Setup quicktags for adding the affiliate link button to the HTML editor
** @since 2.2
*******************************************************************************/
function thirstyQuicktags() {
	$thirstyOptions = get_option('thirstyOptions');
	if (isset($thirstyOptions['disabletexteditorbuttons']) && $thirstyOptions['disabletexteditorbuttons'] == 'on') {
		return;
	}

	echo '<script type="text/javascript" charset="utf-8">
	jQuery(document).ready(function() {
		if (typeof QTags != "undefined")
			QTags.addButton("ThirstyAffiliates_Aff_Link", "affiliate link", thirstyQTagsButton, "", "", "'.__('Open the ThirstyAffiliates link picker', 'thirstyaffiliates').'", 30);

		// Quick add quick tag
		if (typeof QTags != "undefined")
			QTags.addButton("ThirstyAffiliates_quick_add_Aff_Link", "quick add affiliate link", thirstyQTagsButtonQuickAdd, "", "", "'.__('Open quick add affiliate link dialog', 'thirstyaffiliates').'", 31);

	});

	function thirstyQTagsButton() {
		if (typeof tinymce != "undefined") {
			thirstyOpenLinkPicker(tinymce.activeEditor);
		} else {
			thirstyOpenLinkPicker();
		}
	}

	// Quick add affiliate link callback
	function thirstyQTagsButtonQuickAdd() {
		if (typeof tinymce != "undefined") {
			thirstyOpenQuickAddLinkPicker(tinymce.activeEditor);
		} else {
			thirstyOpenQuickAddLinkPicker();
		}
	}

	function thirstyGetHTMLEditorSelection() {
		var textComponent;
		textComponent = parent.document.getElementById("replycontent");
		if (typeof textComponent == "undefined" || !jQuery(textComponent).is(":visible")) // is not a comment reply
			textComponent = parent.document.getElementById("content");

		var selectedText = {};

		// IE version
		if (parent.document.selection != undefined) {
			textComponent.focus();
			var sel = parent.document.selection.createRange();
			selectedText.text = sel.text;
			selectedText.start = sel.start;
			selectedText.end = sel.end;
		}

		// Mozilla version
		else if (textComponent.selectionStart != undefined) {
			var startPos = textComponent.selectionStart;
			var endPos = textComponent.selectionEnd;
			selectedText.text = textComponent.value.substring(startPos, endPos)
			selectedText.start = startPos;
			selectedText.end = endPos;
		}

		return selectedText;
	}

	</script>

	<style>
	.quicktags-toolbar input[value="affiliate link"],
	.quicktags-toolbar input[value="quick add affiliate link"] {
		text-decoration: underline;
		font-style: italic;
	}</style>';

}

/*******************************************************************************
** thirstyUnattachImageFromLink
** Remove an image from a link, but don't delete it because it could be attached
** to something else
** @since 2.2
*******************************************************************************/
function thirstyUnattachImageFromLink() {
    if (!current_user_can(apply_filters('thirstyAjaxAccessCapability', 'edit_posts')))
        die('Cheatin\', Huh?');

	$imgId = (!empty($_POST['imgId']) ? $_POST['imgId'] : '');

	if (empty($imgId))
		die();

	$img = array(
		'ID' => $imgId,
		'post_parent' => ''
	);

	wp_update_post($img);

	echo $imgId;
	die();
}

/*******************************************************************************
** thirstyAttachImageToLink
** Attach an image to a link, make a carbon copy of the attachment object to do
** but link it to the existing image
** @since 2.2
*******************************************************************************/
function thirstyAttachImageToLink() {
    if (!current_user_can(apply_filters('thirstyAjaxAccessCapability', 'edit_posts')))
        die('Cheatin\', Huh?');

	$imgId = (!empty($_POST['imgId']) ? $_POST['imgId'] : '');
	$imgName = (!empty($_POST['imgName']) ? $_POST['imgName'] : '');
	$imgMime = (!empty($_POST['imgMime']) ? $_POST['imgMime'] : '');
	$postId = (!empty($_POST['postId']) ? $_POST['postId'] : '');
	$wp_upload_dir = wp_upload_dir();

	if (empty($imgId) || empty($postId))
		die();

	$img = wp_get_attachment_metadata($imgId, true);
	$imgPost = get_post($imgId);

	// If this image is attached to another post already we need to duplicate it
	// so we can attach it to our post
	if (!empty($imgPost->post_parent)) {
		$upload_dir = wp_upload_dir(); // 2.4.10: Need the abs path on metadata creation

		$attachment = array(
			'guid' => $img['file'],
			'post_mime_type' => $imgMime,
			'post_title' => $imgName,
			'post_content' => '',
			'post_status' => 'inherit'
		);

		$attach_id = wp_insert_attachment(
			$attachment,
			$img['file'],
			$postId
		);

		require_once(ABSPATH . 'wp-admin/includes/image.php');
		$attach_data = wp_generate_attachment_metadata( $attach_id,  trailingslashit($upload_dir['basedir']) . $img['file'] );
		wp_update_attachment_metadata( $attach_id, $attach_data );

		$img = wp_get_attachment_metadata($attach_id, true);
	} else {
		$imgPost->post_parent = $postId;
		wp_update_post($imgPost);
	}
	die();
}

/*******************************************************************************
** thirstyUploadImageFromUrl
** Upload an image from a URL into the system (used for legacy Insert from URL)
** @since 2.2
*******************************************************************************/
function thirstyUploadImageFromUrl() {
    if (!current_user_can(apply_filters('thirstyAjaxAccessCapability', 'edit_posts')))
        die('Cheatin\', Huh?');

	$imgUrl = (!empty($_POST['imgUrl']) ? $_POST['imgUrl'] : '');
	$postId = (!empty($_POST['postId']) ? $_POST['postId'] : '');

	if (empty($imgUrl) || empty($postId))
		return;

	$image = media_sideload_image($imgUrl, $postId, '');
	echo $image;
	die();
}

/*******************************************************************************
** thirstyLinkPickerSearch
** Worker function for searching for an affiliate link, this is called via ajax
** @since 2.2
*******************************************************************************/
function thirstyLinkPickerSearch() {
    if (!current_user_can(apply_filters('thirstyAjaxAccessCapability', 'edit_posts')))
        die('Cheatin\', Huh?');

	$search_query = thirstyFilterData((!empty($_POST['search_query']) ? $_POST['search_query'] : ''));
	$search_offset = (!empty($_POST['search_offset']) ? $_POST['search_offset'] : '');
	$cats_query = (!empty($_POST['cats_query']) ? $_POST['cats_query'] : '');

	global $wpdb;
	$querystr = "SELECT * FROM $wpdb->posts	WHERE post_type = 'thirstylink' AND post_status = 'publish' ";

	if (!empty($search_query))
		$querystr .= " AND LOWER(post_title) like '%" . strtolower($search_query) . "%' ";

	$querystr .= " ORDER BY post_date DESC";

	if (empty($search_query)) {
		$querystr .= " LIMIT 10";

		if (!empty($search_offset)) {
			$querystr .= " OFFSET " . $search_offset;
		}
	}

	$linkQuery = $wpdb->get_results($querystr, OBJECT);

	$thirstyOptions = get_option('thirstyOptions');
	$nofollow = (!empty($thirstyOptions['nofollow']) ? 'nofollow="true" ' : ' ');
	$target = (!empty($thirstyOptions['newwindow']) ? 'newwindow="true" ' : ' ');

	if (!empty($linkQuery)) {
		$i = 0;
		foreach ($linkQuery as $link) {
			// if not a search, then only display 10 most recent
			if (empty($search_query) && $i >= 10) break;

			$linkData = unserialize(get_post_meta($link->ID, 'thirstyData', true));
			// Set the link's override for nofollow if applicable
			if (!empty($linkData['nofollow'])) {
				$nofollow = ($linkData['nofollow'] == 'on' ? 'nofollow="true" ' : ' ');
			}

			// Set the link's override for target if applicable
			if (!empty($linkData['newwindow'])) {
				$target = ($linkData['newwindow'] == 'on' ? 'newwindow="true" ' : ' ');
			}

			// get images
			$imageThumbsHTML = '';
			$attachment_args = array(
				'post_type' => 'attachment',
				'numberposts' => null,
				'post_status' => null,
				'post_parent' => $link->ID
			);

			$attachments = get_posts($attachment_args);
			$imageThumbsHTML .= '<img class="insert_img_link' . (count($attachments) > 0 ? '' : ' img_link_disabled') . '" src="' . plugins_url('thirstyaffiliates/') . 'images/icon-images' . (count($attachments) > 0 ? '' : '-disabled') .
				'.png" alt="'.__('Insert Image Link', 'thirstyaffiliates').'" ' .
				'title="'.__('Insert Image Link', 'thirstyaffiliates').'" /><div class="img_choices">';

			if (count($attachments) > 0) {

				foreach ($attachments as $attachment) {
					$img = wp_get_attachment_image_src($attachment->ID, 'full');
					$imageThumbsHTML .= '<p><span class="thirstyImg" linkID="' . $link->ID . '" imageId="' . $attachment->ID . '">';
					$imageThumbsHTML .= wp_get_attachment_image($attachment->ID, array(75, 75));
					$imageThumbsHTML .= '</span></p>';
				}

				$imageThumbsHTML .= '</div>';
			}

			// Output the code
			echo '<tr' . ($i % 2 == 1 ? ' class="alternate"' : '') . '><td>' .
			'<span class="linkname">' . $link->post_title .
			'</span>' .
			'</td><td class="right">
			<img class="insert_link" linkID="' . $link->ID . '" src="' . plugins_url('thirstyaffiliates/') . 'images/icon-link.png" alt="'.__('Insert Plain Link', 'thirstyaffiliates').'" title="'.__('Insert Plain Link', 'thirstyaffiliates').'" />
			<img class="insert_shortcode_link" linkID="' . $link->ID . '" src="' . plugins_url('thirstyaffiliates/') . 'images/icon-shortcode.png" alt="'.__('Insert Shortcode', 'thirstyaffiliates').'" title="'.__('Insert Shortcode', 'thirstyaffiliates').'" />
			' . $imageThumbsHTML . '
			</td></tr>';

			$i++;
		}
	} else {
		if (!empty($search_query)) // make sure it's a search query and not just a request for more links
			echo '<tr><td>'.__('Sorry, no affiliate links found.', 'thirstyaffiliates').'</td></tr>';
	}

	die();
}

/*******************************************************************************
** thirstyGetLinkCode
** Worker function for building the link code ready for insertion. This handles
** creating the code to insert into posts, pages, comments, etc and covers three
** link types: standard, shortcode and images.
** @since 2.2
*******************************************************************************/
function thirstyGetLinkCode($linkType = '', $linkID = '', $copiedText = '', $imageID = '', $echo = true) {
    if (defined( 'DOING_AJAX' ) && DOING_AJAX && !current_user_can(apply_filters('thirstyAjaxAccessCapability', 'edit_posts')))
        die('Cheatin\', Huh?');

	if (empty($linkType))
		$linkType = (!empty($_POST['linkType']) ? $_POST['linkType'] : '');
	if (empty($linkID))
		$linkID = (!empty($_POST['linkID']) ? $_POST['linkID'] : '');
	if (empty($imageID))
		$imageID = (!empty($_POST['imageID']) ? $_POST['imageID'] : '');
	if (empty($copiedText))
		$copiedText = (!empty($_POST['copiedText']) ? $_POST['copiedText'] : '');

	if (empty($linkID))
		return; // not a valid link, so don't bother doing any of this

	if (empty($linkType))
		$linkType = 'link';

	if ($linkType == 'image' && empty($imageID))
		return;

	// Get the link and thirsty options
	$thirstyOptions = get_option('thirstyOptions');
	$link = get_post($linkID);
	$linkData = unserialize(get_post_meta($link->ID, 'thirstyData', true));

	if ($linkType == 'image') {
		if (empty($imageID))
			$imageID = (!empty($_POST['imageID']) ? $_POST['imageID'] : '');
		$image = get_post($imageID);
	}

	$nofollow = (!empty($thirstyOptions['nofollow']) ? 'nofollow' : '');
	$target = (!empty($thirstyOptions['newwindow']) ? '_blank' : '');
	$linkclass = (!empty($thirstyOptions['disablethirstylinkclass']) ? '' : 'thirstylink');
	$disabletitle = (!empty($thirstyOptions['disabletitleattribute']) ? true : false);

	// Set the link's nofollow if global setting is not set
	if (empty($nofollow))
		$nofollow = ( isset( $linkData['nofollow'] ) && $linkData['nofollow'] == 'on' ? 'nofollow' : '' );

	// Set the link's target value if global setting is not set
	if (empty($target))
		$target = ( isset( $linkData['newwindow'] ) && $linkData['newwindow'] == 'on' ? '_blank' : '' );

	// 2.4.9: Add additional rel tags specified globally
	$additionalRelTags = '';
	if (isset($thirstyOptions['additionalreltags']) && !empty($thirstyOptions['additionalreltags']))
		$additionalRelTags = ' ' . $thirstyOptions['additionalreltags'];

	// Check if copied text contains HTML
	$copiedTextContainsHTML = false;
	if($copiedText != strip_tags($copiedText)) {
		$copiedTextContainsHTML = true;
		$disabletitle = true;

		// We don't support using shortcode links or image links on top of copied
		// text that has an image tag in it
		if (($linkType == 'shortcode' || $linkType == 'image') &&
			preg_match('/<img/', $copiedText)) {
			$output = stripslashes($copiedText);
			if ($echo)
				echo $output;
			else
				return $output;
			die();
		}
	}

	$linkAttributes = array(
		'href' => get_post_permalink($link->ID),
		'class' => $linkclass,
		'id' => '',
		'rel' => $nofollow . $additionalRelTags,
		'target' => $target,
		'title' => ((!empty($copiedText) && !$disabletitle) ? $copiedText : (!$disabletitle ? $linkData['linkname'] : ''))
	);

	// filter link attributes
	$linkAttributes = apply_filters('thirstyFilterLinkAttributesBeforeInsert', $linkAttributes, $linkID);

	if ($linkType == 'image') {
		$imageDetails = wp_get_attachment_image_src($image->ID, 'full');
		$imageAttributes = array(
			'src' => $imageDetails[0],
			'width' => $imageDetails[1],
			'height' => $imageDetails[2],
			'alt' => (!empty($copiedText) ? strip_tags($copiedText) : $linkData['linkname']),
			'title' => ((!empty($copiedText) && !$disabletitle) ? $copiedText : (!$disabletitle ? $linkData['linkname'] : '')),
			'class' => (!empty($linkclass) ? 'thirstylinkimg' : ''),
			'id' => ''
		);

		// filter link image attributes
		$imageAttributes = apply_filters('thirstyFilterLinkImageAttributesBeforeInsert', $imageAttributes, $imageID, $linkID);
	}

	$output = '';
	switch ($linkType) {
	case 'shortcode':
		$output .= '[thirstylink linkid="' . $link->ID . '" linktext="' . $copiedText . '"';

		unset($linkAttributes['href']);
		unset($linkAttributes['rel']);
		unset($linkAttributes['target']);

		foreach ($linkAttributes as $name => $value) {
			// Handle square bracket escaping (used for some addons, eg. Google Analytics click tracking)
			$value = str_replace("[", "&#91;", $value);
			$value = str_replace("]", "&#93;", $value);
			$value = htmlentities($value);
			$output .= (!empty($value) ? ' ' . $name . '="' . $value . '"' : '');
		}

		$output .= ']';

		break;
	case 'image':

		$output .= '<a';

		foreach ($linkAttributes as $name => $value) {
			$output .= (!empty($value) ? ' ' . $name . '="' . $value . '"' : '');
		}

		$output .= '>';

		$output .= '<img';

		foreach ($imageAttributes as $name => $value) {
			$output .= (!empty($value) ? ' ' . $name . '="' . $value . '"' : '');
		}

		$output .= ' /></a>';

		break;
	case 'link':
	default:
		$output .= '<a';

		foreach ($linkAttributes as $name => $value) {
			$output .= (!empty($value) ? ' ' . $name . '="' . $value . '"' : '');
		}

		$output .= '>' . stripslashes($copiedText) . '</a>';

		break;
	}

	if ($echo)
		echo $output;
	else
		return $output;

	die();
}

/*******************************************************************************
** thirstyGetThickboxContent
** Get the link picker thickbox content
** @since 2.2
*******************************************************************************/
function thirstyGetThickboxContent() {
    if (!current_user_can(apply_filters('thirstyAjaxAccessCapability', 'edit_posts')))
        die('Cheatin\', Huh?');

	?>

	<html>
	<head>

	<?php
		wp_enqueue_script('editor');
		wp_dequeue_script('jquery-ui-sortable');
		wp_dequeue_script('admin-scripts');
		do_action('admin_print_styles');
		do_action('admin_print_scripts');
		do_action('admin_head');
	?>
	<style>

	body {
		font: 14px/16px sans-serif;
		background: #f5f5f5;
	}

	#picker_container, #picker_content {
		padding: 10px;
		overflow: hidden;
		text-align: center;
	}

	#picker_container h1 {
		font-size: 16px;
		font-weight: bold;
	}

	#picker_container table {
		width: 100%;
		text-align: left;
	}

	#picker_container table tr, #picker_container table tr.alternate {
		background: #e5e5e5;
		height: 40px;
		vertical-align: top;
	}

	#picker_container table tr.alternate {
		background: #eeeeee;
	}

	#picker_container table tr td {
		padding: 15px 20px 15px 20px;
		font-size: 16px;
		text-align: left;
	}

	#picker_container table tr td.right {
		text-align: center;
		vertical-align: middle;
		width: 75px;
		padding-right: 10px;
	}

	#picker_container #heading_title {
		margin: 10px auto 0px auto;
	}

	#picker_container #search_box {
		height: 30px;
		margin-top: 20px;
	}

	#picker_container #search_box label {
		color: #202020;
		padding: 4px;
		margin-right: 5px;
	}

	#picker_container #search_input {
		width: 185px;
	}

	#picker_container .linkname {
		font-weight: normal;
		font-size: 16px;
		color: #21759b;
	}

	#picker_container div.linkcats {
		margin-top: 5px;
	}

	#picker_container .linkcat {
		font-size: 10px;
		background: #e1e1e1;
		padding: 3px;
		margin-right: 3px;
		color: #808080;
		white-space: nowrap;
	}

	#picker_container .insert_link,
	#picker_container .insert_img_link,
	#picker_container .insert_shortcode_link {
		white-space: nowrap;
		float: left;
		margin: 0;
		padding: 0;
		text-decoration: underline;
		cursor: pointer;
		vertical-align: middle;
		margin-right: 5px;
	}

	#picker_container .img_link_disabled {
		cursor: default;
	}

	#picker_container .show_url, #picker_container .hide_url {
		font-size: 10px;
		color: #808080;
		text-decoration: underline;
		cursor: pointer;
		margin-left: 10px;
		white-space: nowrap;
	}

	#picker_container .img_choices {
		display: none;
		float: left;
		clear: both;
	}

	#picker_container .thirstyImg {
		cursor: pointer;
	}

	#picker_container #show_more {
		cursor: pointer;
		display: none;
		right: 30px;
		position: absolute;
		margin-top: 10px;
		padding-bottom: 20px;
	}

	#picker_container #show_more_loader {
		display: none;
		right: 150px;
		margin-top: 15px;
		position: absolute;
	}

	</style>
	</head>
	<body>
	<div id="picker_container">
		<img id="heading_title" src="<?php echo plugins_url('thirstyaffiliates/'); ?>images/thirstylogo.png" alt="<?php _e('Affiliate Link Picker', 'thirstyaffiliates'); ?>" />

		<div id="search_box">
		<label for="search_input"><?php _e('Search ...', 'thirstyaffiliates'); ?></label>
		<input type="text" value="" size="35" id="search_input" name="search_input" />
		</div>
		<table id="picker_content" cellspacing="0" cellpadding="0">
			&nbsp;
		</table>
		<img id="show_more_loader" src="<?php echo plugins_url('thirstyaffiliates/'); ?>images/thirsty-loader.gif" alt="Loading ..." />&nbsp;<img id="show_more" src="<?php echo plugins_url('thirstyaffiliates/'); ?>images/search-load-more.png" alt="'.__('Load more ...', 'thirstyaffiliates').'" />
	</div>

	<?php echo '<script type="text/javascript">var thirstyPluginDir = "' .
			plugins_url('thirstyaffiliates/') . '";
			var thirstyMCE;</script>';?>

	<script type="text/javascript" src="<?php echo plugins_url('thirstyaffiliates/'); ?>js/ThirstyLinkPicker.js"></script>
	</body>
	</html>

	<?php
	die();
}

/*******************************************************************************
** thirstyGetQuickAddLinkThickboxContent
** Get the quick add link thickbox content
** Contributor: J++
** @since 2.3.1
*******************************************************************************/
function thirstyGetQuickAddLinkThickboxContent() {
    if (!current_user_can(apply_filters('thirstyAjaxAccessCapability', 'edit_posts')))
        die('Cheatin\', Huh?');

	?>
	<html>
		<head>
			<?php
				wp_enqueue_script('editor');
				wp_dequeue_script('jquery-ui-sortable');
				wp_dequeue_script('admin-scripts');
				do_action('admin_print_styles');
				do_action('admin_print_scripts');
				do_action('admin_head');
			?>
			<style>
				body {
					font: 14px/16px sans-serif;
					background: #fff;
				}

				#heading_title {
					display: block;
					margin: 10px auto 0px auto;
				}

				#quick-add-link-container {
					padding: 1em 3em;
				}

				#error-bulletin {
					padding: .4em .8em;
					border: 1px solid #BB0E19 !important;
					background-color: #FFEFF0;
					font-size: 1em;
					/*font-family: arial, sans-serif;*/
					font-family: 'Open Sans', sans-serif;
					margin: 1em 0;
					color: #BB0E19;
					display: none;
					border-radius: 3px;
				}

				#quick-add-link-form {
					display: block;
				}

				.field_row {
					padding: .2em;
					margin-bottom: 1em;
				}
				.field_row label,
				.field_row .desc {
					display: block;
					font-size: 14px;
					color: #444444;
					/*font-family: arial, sans-serif;*/
					font-family: 'Open Sans', sans-serif;
					margin-bottom: .4em;
					font-weight: normal;
				}
				.field_row .errmsg {
					display: none;
					color: red;
					font-size: 12px;
					font-weight: bold;
					/*font-family: arial, sans-serif;*/
					font-family: 'Open Sans', sans-serif;
					margin-bottom: .4em;
				}
				.field_row .desc {
					font-size: 12px;
					font-style: italic;
				}
				.field_row input[type="text"] {
					display: block;
					width: 100%;
					border: 1px solid #DDD;
					padding: 5px;
					margin-bottom: .4em;
					height: 31px;
					font-size: 14px;
					box-shadow: inset 0 1px 2px rgba(0,0,0,.07);
					background-color: #fff;
					color: #333;
				}
				.field_row select {
					padding: 2px;
					line-height: 28px;
					height: 28px;
					vertical-align: middle;
					border-color: #ddd;
					box-shadow: inset 0 1px 2px rgba(0,0,0,.07);
					background-color: #fff;
					color: #333;
					font-size: 14px;
				}
				.field_row select option {
					font-weight: normal;
					font: inherit;
					line-height: 28px;
				}
				.field_row .err {
					border: 1px solid #BB0E19 !important;
					background-color: #FFEFF0;
				}
				.field_row .option label {
					cursor: pointer;
				}
				.field_row .button-primary {
					margin-top: 8px;
					margin-bottom: 5px;
					background: #2ea2cc;
					border-color: #0074a2;
					-webkit-box-shadow: inset 0 1px 0 rgba(120,200,230,.5),0 1px 0 rgba(0,0,0,.15);
					box-shadow: inset 0 1px 0 rgba(120,200,230,.5),0 1px 0 rgba(0,0,0,.15);
					color: #fff;
					text-decoration: none;
				}
				.field_row .button-primary:hover {
					background: #1e8cbe;
					-webkit-box-shadow: inset 0 1px 0 rgba(120,200,230,.6);
					box-shadow: inset 0 1px 0 rgba(120,200,230,.6);
				}
				.field_row .button_secondary {
					color: #555;
					border-color: #ccc;
					background: #f7f7f7;
					-webkit-box-shadow: inset 0 1px 0 #fff,0 1px 0 rgba(0,0,0,.08);
					box-shadow: inset 0 1px 0 #fff,0 1px 0 rgba(0,0,0,.08);
					vertical-align: top;
				}
				.field_row .button_secondary:hover {
					background: #fafafa;
					border-color: #999;
					color: #222;
				}
				.field_row .button {
					display: inline-block;
					line-height: 26px;
					height: 28px;
					margin: 0;
					padding: 0 10px 1px;
					cursor: pointer;
					border-width: 1px;
					border-style: solid;
					-webkit-border-radius: 3px;
					-webkit-appearance: none;
					border-radius: 3px;
					white-space: nowrap;
					box-sizing: border-box;
					margin-left: 5px;
					font-size: 14px;
					font-family: Arial, sans-serif;
				}
				.field_row.button-container {
					text-align: right;
				}
			</style>
		</head>
		<body>
			<div id="quick-add-link-container">

				<?php
					// Create Nonce
					wp_nonce_field(plugin_basename(__FILE__), 'quick_add_aff_link_nonce');

					// Get Global Options
					$thirstyOptions = get_option('thirstyOptions');
				?>

				<img id="heading_title" src="<?php echo plugins_url('thirstyaffiliates/'); ?>images/thirstylogo.png" alt="<?php _e('Affiliate Link Picker', 'thirstyaffiliates'); ?>" />

				<div id="error-bulletin"></div>

				<div id="quick-add-link-form">

					<div class="field_row">
						<label for="qal_link_name"><?php _e('Link Name:', 'thirstyaffiliates'); ?></label>
						<input type="text" name="qal_link_name" id="qal_link_name">
						<div class="errmsg"></div>
					</div>

					<div class="field_row">
						<label for="qal_destination_url"><?php _e('Destination URL:', 'thirstyaffiliates'); ?></label>
						<div class="desc"><?php _e('http:// or https:// is required', 'thirstyaffiliates'); ?></div>
						<input type="text" name="qal_destination_url" id="qal_destination_url">
						<div class="errmsg"></div>

						<?php

							/* Only display link nofollow setting if the global nofollow setting is disabled */
							if ($thirstyOptions['nofollow'] != 'on') {
								?>
								<div class="option"><label for="qal_no_follow_link"><input type="checkbox" name="qal_no_follow_link" value="on" id="qal_no_follow_link"><?php _e('No follow this link?', 'thirstyaffiliates'); ?></label></div>
								<?php
							}

							/* Only display link new window setting if the global new window setting is disabled */
							if ($thirstyOptions['newwindow'] != 'on') {
								?>
								<div class="option"><label for="qal_new_window"><input type="checkbox" name="qal_new_window" value="on" id="qal_new_window"><?php _e('Open this link in new window?', 'thirstyaffiliates'); ?></label></div>
								<?php
							}

						?>
					</div>

					<div class="field_row">
						<label><?php _e('Redirect Type', 'thirstyaffiliates'); ?></label>
						<span class="desc"><?php _e('Override the default redirection type for this link:', 'thirstyaffiliates'); ?></span>
						<?php
							foreach (thirstyGetRedirectTypes() as $key => $value) {
								?>
								<div class="option">
									<label>
										<input type="radio" name="qal_redirect_type" value="<?php echo $key; ?>" <?php echo (strcasecmp($key, $thirstyOptions['linkredirecttype']) == 0)?"checked":""; ?>>
										<?php echo $value; ?>
										<?php echo (strcasecmp($key, $thirstyOptions['linkredirecttype']) == 0)? __(" (Default)") : ""; ?>
									</label>
								</div>
								<?php
							}
						?>
					</div>

					<?php
						/* If we are using categories in slugs force user to select a category */
						if (!empty($thirstyOptions['showcatinslug']) && $thirstyOptions['showcatinslug'] == 'on') {
							$selectedLinkCats = wp_get_post_terms($post_id, 'thirstylink-category');

							if(empty($selectedLinkCats)) {
								$defaultCat = 'Uncategorized';

								// create the default term if it doesn't exist
								if (!term_exists($defaultCat, 'thirstylink-category')) {
									wp_insert_term($defaultCat, 'thirstylink-category');
								}

								// get the default term and set this post to have it
								$defaultTerm = get_term_by('name', $defaultCat, 'thirstylink-category');
								wp_set_post_terms($post_id, $defaultTerm->term_id, 'thirstylink-category');
							}
						}
					?>

					<?php
						/* Only show category when the show category in slug setting is turned on */
						if(strcasecmp($thirstyOptions['showcatinslug'], "on") == 0){

							// Retrieve all link categories
							$link_categories = get_terms("thirstylink-category", array('hide_empty' => false));

							if(count($link_categories) > 0){
								// If no category term is present, create one, coz we really need this for the plugin to work properly
								if (!term_exists('Uncategorized', 'thirstylink-category')) {
									wp_insert_term('Uncategorized', 'thirstylink-category');
								}

								// Ok, Retrieve again all link categories
								$link_categories = get_terms("thirstylink-category", array('hide_empty' => false));
							}

							// Only show combo box if there are indeed link categories
							if(count($link_categories) > 0){
								?>
								<div class="field_row">
									<label for=""><?php _e('Link Categories', 'thirstyaffiliates'); ?></label>
									<span class="desc"><?php _e('You must select a link category as you set the general setting to include category on the link', 'thirstyaffiliates'); ?></span>
									<select name="qal_link_categories" id="qal_link_categories" style="width: 300px;" data-placeholder="<?php _e('Select categories...', 'thirstyaffiliates'); ?>" multiple>
									<?php
										foreach (get_terms("thirstylink-category", array('hide_empty' => false)) as $link_category) {
											?>
											<option value="<?php echo $link_category->term_id; ?>"><?php $link_category->name; ?></option>
											<?php
										}
									?>
									</select>
								</div>
								<?php
							}
						}
					?>

					<div class="field_row button-container">
						<input type="button" id="add-link" class="button button_secondary" value="<?php _e('Add Link', 'thirstyaffiliates'); ?>">
						<input type="button" id="quick-add-link" class="button button-primary" value="<?php _e('Add Link &amp; Insert Into Post', 'thirstyaffiliates'); ?>">
					</div>

				</div><!-- quick-add-link-form -->
			</div><!-- quick-add-link-container -->


		<?php echo '<script type="text/javascript">var thirstyPluginDir = "' .
			plugins_url('thirstyaffiliates/') . '";
			var thirstyMCE;</script>';?>

		<link rel="stylesheet" href="<?php echo plugins_url('thirstyaffiliates/'); ?>js/lib/chosen/chosen.min.css"/>
		<script type="text/javascript" src="<?php echo plugins_url('thirstyaffiliates/'); ?>js/lib/chosen/chosen.jquery.min.js"></script>

		<script type="text/javascript" src="<?php echo plugins_url('thirstyaffiliates/'); ?>js/ThirstyQuickAddLinkPicker.js"></script>

		<script type="text/javascript">
			jQuery(document).ready(function($){

				$("#qal_link_categories").chosen();

			});
		</script>
		</body>
	</html>

	<?php
	die();
}

/*******************************************************************************
** quickCreateAffiliateLink
** Quick create affiliate link
** Contributor: J++
** @since 2.3.1
*******************************************************************************/
function quickCreateAffiliateLink($linkname = '', $linkurl = '', $nofollow = '', $newwindow = '', $linkredirecttype = '', $linkCategory ='', $echo = true) {
    if (!current_user_can(apply_filters('thirstyAjaxAccessCapability', 'edit_posts')))
        die('Cheatin\', Huh?');

	// Validate Nonce
	if(!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], plugin_basename(__FILE__))) {
		_e("You don't have appropriate permission to perform this action", "thirstyaffiliates");
		echo $_POST['nonce'];
		die();
	}


	/*==========  Server side validation  ==========*/
	$linkname			=	stripslashes(strip_tags(trim($_POST['linkname'])));
	$linkurl			=	stripslashes(strip_tags(trim($_POST['linkurl'])));
	$nofollow			=	stripslashes(strip_tags(trim($_POST['nofollow'])));
	$newwindow			=	stripslashes(strip_tags(trim($_POST['newwindow'])));
	$linkredirecttype	=	stripslashes(strip_tags(trim($_POST['linkredirecttype'])));
	$allGood			=	true;

	// Link Name
	if(strcasecmp($linkname, "") == 0){
		$allGood = false;
	}

	// Link URL
	if(strcasecmp($linkurl, "") == 0){
		$allGood = false;
	}

	// Checkpoint
	if(!$allGood){

		// Kill the flow
		// TODO: Enhance error message
		_e("Server Error: Some fields dont have appropriate values", "thirstyaffiliates");
		die();

	}else{

		/*==========  Create new affiliate link  ==========*/

		// Insert new post
		$current_user = wp_get_current_user();

		$new_post = array(
					  'post_author'		=>	$current_user->ID,
					  'post_date'		=>	current_time('mysql'),
					  'post_date_gmt'	=>	current_time('mysql', true),
					  'post_title'		=>	$linkname,
					  'post_status'		=>	'publish',
					  'post_type'		=>	'thirstyLink'
					);

		$new_post_id = wp_insert_post($new_post);

		// Check if inserting new post was succeful
		if($new_post_id == 0){

			// Kill the flow
			// TODO: Enhance error message
			_e("Server Error: Failed to dynamically insert new post", "thirstyaffiliates");
			die();

		}else{

			/*==========  Update meta data  ==========*/
			// Update post meta for the newly dynamically created post
			$linkData = array(
							'linkname'			=>	$linkname,
							'linkurl'			=>	$linkurl,
							'linkredirecttype'	=>	$linkredirecttype
						);

			// Check if nofollow meta contains valid data
			if(strcasecmp($nofollow, "on") == 0){
				$linkData['nofollow'] = $nofollow;
			}

			// Check if newwindow meta contains valid data
			if(strcasecmp($newwindow, "on") == 0){
				$linkData['newwindow'] = $newwindow;
			}

			$meta_update_status = update_post_meta($new_post_id, 'thirstyData', serialize($linkData));

			if(!$meta_update_status){

				// Kill the flow
				// TODO: Enhance error message
				_e("Server Error: Failed to update meta data of recently dynamically created post", "thirstyaffiliates");
				die();

			}

			/*==========  Set link category if required  ==========*/
			if ( isset( $_POST[ 'linkCategory' ] ) ) {

				$linkCat = $_POST['linkCategory'];
				wp_set_post_terms( $new_post_id, $linkCat, 'thirstylink-category' );

			}

		}// if($new_post_id == 0) else

	}// if(!$allGood) else

	// Return the newly created post id for use in inserting link to editor
	echo $new_post_id;
	die();

}

/*******************************************************************************
** thirstyGetRedirectTypes
** Return the redirect types in the system, the default plus anything any
** add-on adds to the list.
** @since 2.3
*******************************************************************************/
function thirstyGetRedirectTypes() {
	$redirectTypes = array(
		'301' => '301 Permanent',
		'302' => '302 Temporary',
		'307' => '307 Temporary (alternative)'
	);

	return apply_filters('thirstyFilterRedirectTypeOptions', $redirectTypes);
}

function thirstyConvertSpecialToChars($redirectUrl) {
	return htmlspecialchars_decode($redirectUrl, ENT_COMPAT);
}

/*******************************************************************************
** thirstyAffiliatesActivation
** On activation add flush flag which gets removed after flushing the rules once
** @since 1.3
*******************************************************************************/
function thirstyAffiliatesActivation() {
	flush_rewrite_rules();
}

/*******************************************************************************
** thirstyAffiliatesDeactivation
** On deactivation remove flush flag
** @since 1.3
*******************************************************************************/
function thirstyAffiliatesDeactivation() {
	flush_rewrite_rules();
}

/**
 * Add custom column to thirsty link listings (Link ID).
 *
 * @param $columns
 *
 * @return array
 * @since 4.5
 */
function thirstyCustomAffiliateListingColumn ( $columns ) {

	$arrayKeys = array_keys($columns);
	$firstIndex = $arrayKeys[0];
	$firstValue = $columns[$firstIndex];
	array_shift($columns);

	$columns = array( $firstIndex => $firstValue , 'link_id' => __( 'Link ID') ) + $columns;

	return $columns;

}

/**
 * Add custom column value to thirsty link listings (Link ID).
 *
 * @param $column
 * @param $postId
 *
 * @since 1.0.0
 */
function thirstyCustomAffiliateListingColumnValue ( $column, $postId ) {

	if ( $column == 'link_id' ) {

		echo "<span>" . $postId . "</span>";

	}

}

/*******************************************************************************
** thirstyInit
** Initialize the plugin
** @since 1.0
*******************************************************************************/
function thirstyInit() {
	$thirstyOptions = get_option('thirstyOptions');

	thirstyRegisterPostType();

	// Custom Field
	add_filter( 'manage_edit-thirstylink_columns', 'thirstyCustomAffiliateListingColumn' );

	// Custom Field Value
	add_action( 'manage_thirstylink_posts_custom_column', 'thirstyCustomAffiliateListingColumnValue' , 10 , 2 );

	/* Add filter to create category links */
	add_filter('post_type_link', 'thirstyCatLinks', 10, 2);

	/* Add filter to always show the insert into post button for thirstylinks */
	add_filter('get_media_item_args', 'thirstyForceSend');

	/* Add filter to automatically trim useless words out of slugs */
	add_filter('name_save_pre', 'thirstyTrimSlugs', 0);

	/* Add meta boxes and saving functions */
	add_action('add_meta_boxes', 'thirstySetupPostBoxes');
	add_action('save_post', 'thirstySavePost');
	add_action('draft_thirstylink', 'thirstyDraftToPublish');

	/* Add the shortcode */
	require_once("ThirstyShortcode.php");

	/* Control redirection */
	add_action('template_redirect', 'thirstyRedirectUrl', 1);

	/* Filter to convert html special entities back to chars on redirect */
	add_filter('thirstyFilterRedirectUrl', 'thirstyConvertSpecialToChars', 1, 1);

	if (is_admin()) {
		require_once("ThirstyAdminPage.php");
		require_once("ThirstyAddonPage.php");

		if ((!empty($_GET['post']) && get_post_type($_GET['post']) == 'thirstylink') ||
			(!empty($_GET['post_type']) && $_GET['post_type'] == 'thirstylink')) {
			wp_enqueue_script(
				'thirstyhelper',
				plugins_url('thirstyaffiliates/js/thirstyhelper.js'),
				array('jquery')
			);
		} else {

		}
		wp_enqueue_script(
			'thirstyPickerHelper',
			plugins_url('thirstyaffiliates/js/thirstyPickerHelper.js'),
			array('jquery')
		);
	}

	/* Register ajax calls */
	add_action('wp_ajax_thirstyLinkPickerSearch', 'thirstyLinkPickerSearch');
	add_action('wp_ajax_thirstyUploadImageFromUrl', 'thirstyUploadImageFromUrl');
	add_action('wp_ajax_thirstyAttachImageToLink', 'thirstyAttachImageToLink');
	add_action('wp_ajax_thirstyUnattachImageFromLink', 'thirstyUnattachImageFromLink');
	add_action('wp_ajax_thirstyGetLinkCode', 'thirstyGetLinkCode');
	add_action('wp_ajax_thirstyGetThickboxContent', 'thirstyGetThickboxContent');
	add_action('wp_ajax_thirstyGetQuickAddLinkThickboxContent', 'thirstyGetQuickAddLinkThickboxContent');
	add_action('wp_ajax_quickCreateAffiliateLink', 'quickCreateAffiliateLink');
}

register_activation_hook(__FILE__, 'thirstyAffiliatesActivation');
register_deactivation_hook(__FILE__, 'thirstyAffiliatesDeactivation');

/* Initialize the plugin */
add_action('init', 'thirstyInit');

/* Add settings link to plugin page */
add_filter('plugin_action_links', 'thirstyAddSettingsLinkToPluginPage', 10, 2 );

/* Add the tinyMCE plugin */
add_action('init', 'thirstyEditorButtons');

/* Add necessary javascript for the admin page */
add_action('admin_head', 'thirstyAdminHeader');

/* Output front end header stuff */
add_action('wp_head', 'thirstyHeader', 10);

/* Load Plug-ins Text Domain*/
add_action( 'plugins_loaded', 'thirstyAffiliatesLoadPluginTextdomain' );

?>
