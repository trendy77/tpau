<?php

/*******************************************************************************
** thirstySetupMenu()
** Setup the plugin options menu
** @since 1.0
*******************************************************************************/
function thirstySetupMenu() {
	if (is_admin()) {
		register_setting('thirstyOptions', 'thirstyOptions');
		add_submenu_page('edit.php?post_type=thirstylink', __('Settings', 'thirstyaffiliates'), __('Settings', 'thirstyaffiliates'), 'manage_options', 'thirsty-settings', 'thirstyAdminOptions');
	}
}

/*******************************************************************************
** thirstyAdminOptions
** Present the options page
** @since 1.0
*******************************************************************************/
function thirstyAdminOptions() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have suffifient permissions to access this page.', 'thirstyaffiliates') );
	}

	$thirstyOptions = get_option('thirstyOptions');

	$linksRebuilt = false;
	if (isset($thirstyOptions['rebuildlinks']) && $thirstyOptions['rebuildlinks'] == 'true') {
		$thirstyOptions['rebuildlinks'] = 'false';
		update_option('thirstyOptions', $thirstyOptions);
		$thirstyOptions = get_option('thirstyOptions');
		thirstyResaveAllLinks();
		flush_rewrite_rules();
		$linksRebuilt = true;
	}

	// Sanity check on link prefix
	if (empty($thirstyOptions['linkprefix'])) {
		$thirstyOptions['linkprefix'] = 'recommends';
		update_option('thirstyOptions', $thirstyOptions);
	}

	$redirectTypes = thirstyGetRedirectTypes();

	// Sanity check on link redirect type
	if (empty($thirstyOptions['linkredirecttype'])) {
		$thirstyOptions['linkredirecttype'] = '301';
		update_option('thirstyOptions', $thirstyOptions);
	}

	$thirstyOptions['nofollow'] = isset($thirstyOptions['nofollow']) ? 'checked="checked"' : '';
	$thirstyOptions['newwindow'] = isset($thirstyOptions['newwindow']) ? 'checked="checked"' : '';
	$thirstyOptions['showcatinslug'] = isset($thirstyOptions['showcatinslug']) ? 'checked="checked"' : '';
	$thirstyOptions['disablecatautoselect'] = isset($thirstyOptions['disablecatautoselect']) ? 'checked="checked"' : '';
	$thirstyOptions['legacyuploader'] = isset($thirstyOptions['legacyuploader']) ? 'checked="checked"' : '';
	$thirstyOptions['disabletitleattribute'] = isset($thirstyOptions['disabletitleattribute']) ? 'checked="checked"' : '';
	$thirstyOptions['disablethirstylinkclass'] = isset($thirstyOptions['disablethirstylinkclass']) ? 'checked="checked"' : '';
	$thirstyOptions['disableslugshortening'] = isset($thirstyOptions['disableslugshortening']) ? 'checked="checked"' : '';
	$thirstyOptions['disablevisualeditorbuttons'] = isset($thirstyOptions['disablevisualeditorbuttons']) ? 'checked="checked"' : '';
	$thirstyOptions['disabletexteditorbuttons'] = isset($thirstyOptions['disabletexteditorbuttons']) ? 'checked="checked"' : '';

	echo '<script type="text/javascript">var thirstyPluginDir = "' .
	plugins_url('thirstyaffiliates/') . '";
	var thirstyJSEnable = true;
	</script>';

	echo '<div class="wrap">';

	echo '<img id="thirstylogo" src="' . plugins_url('thirstyaffiliates/images/thirstylogo.png') . '" alt="ThirstyAffiliates" />';

	echo '<form id="thirstySettingsForm" method="post" action="options.php">';

	wp_nonce_field('update-options');
	settings_fields('thirstyOptions');

	if (!empty($_GET['settings-updated'])) {
		echo '<div id="message" class="updated below-h2"><p>'.__('Settings updated.', 'thirstyaffiliates').'</p>' .
		($linksRebuilt ? '<p>'.__('Links rebuilt.', 'thirstyaffiliates').'</p>' : '') . '</div>';
	}

	echo '
	<table class="thirstyTable form-table" cellspacing="0" cellpadding="0">

	<tr><td><h3 style="margin-top: 0;">'.__('General Settings', 'thirstyaffiliates').'</h3></td></tr>

	<tr>
		<th>
			<label for="thirstyOptions[linkprefix]">'.__('Link Prefix:', 'thirstyaffiliates').'</label>
		</th>
		<td>
			<select id="thirstyOptionsLinkPrefix" name="thirstyOptions[linkprefix]">
				<option value="custom"' . (!empty($thirstyOptions['linkprefix']) && $thirstyOptions['linkprefix'] == 'custom' ? ' selected' : '') . '>-- ' . __('Custom', 'thirstyaffiliates') . ' --</option>';

		thirstyGenerateSelectOptions(array("recommends", "link", "go", "review",
			"product", "suggests", "follow", "endorses", "proceed", "fly", "goto",
			"get", "find", "act", "click", "move", "offer", "run"), true);

		echo '</select><br />
			<input type="text" id="thirstyCustomLinkPrefix" value="' . (isset($thirstyOptions['linkprefixcustom']) ? $thirstyOptions['linkprefixcustom'] : '') . '" name="thirstyOptions[linkprefixcustom]" />';

		if (isset($thirstyOptions['linkprefix']) && $thirstyOptions['linkprefix'] == 'custom') {
			echo '<script type="text/javascript">
			jQuery("#thirstyCustomLinkPrefix").css("display", "block");
			jQuery("#thirstyCustomLinkPrefix").show();
			</script>';
		}

		echo '</td>
		<td>
			<span class="description">'.__('The prefix that comes before your cloaked link\'s slug.<br />eg. ', 'thirstyaffiliates') .
			trailingslashit(get_bloginfo('url')) . '<span style="font-weight: bold;">' . thirstyGetCurrentSlug() . '</span>/your-affiliate-link-name</span>
			<br /><span class="description"><b>'.__('Warning:', 'thirstyaffiliates').'</b> '.__('Changing this setting after you\'ve used links in a post could break those links. Be careful!', 'thirstyaffiliates').'</span>
		</td>
	</tr>

	<tr>
		<th>
			<label for="thirstyOptions[showcatinslug]">'.__('Show Link Category in URL?', 'thirstyaffiliates').'</label>
		<td>
			<input type="checkbox" name="thirstyOptions[showcatinslug]" id="thirstyOptionsShowCatInSlug" ' .
			$thirstyOptions['showcatinslug'] . ' />
		</td>
		<td>
			<span class="description">'.__('Show the selected category in the url. eg. ', 'thirstyaffiliates') .
			trailingslashit(get_bloginfo('url')) . '' . thirstyGetCurrentSlug() . '/<span style="font-weight: bold;">link-category</span>/your-affiliate-link-name</span></span>
			<br /><span class="description"><b>'.__('Warning:', 'thirstyaffiliates').'</b> '.__('Changing this setting after you\'ve used links in a post could break those links. Be careful!', 'thirstyaffiliates').'</span>
		</td>
	</tr>

	<tr>
		<th>
			<label for="thirstyOptions[disablecatautoselect]">'.__('Disable "uncategorized" category on save?', 'thirstyaffiliates').'</label>
		<td>
			<input type="checkbox" name="thirstyOptions[disablecatautoselect]" id="thirstyOptionsDisableCatAutoSelect" ' .
			$thirstyOptions['disablecatautoselect'] . ' />
		</td>
		<td>
			<span class="description">'.__('If the "Show the selected category in the url" option above is selected, by default ThirstyAffiliates will add an "uncategorized" category to apply to non-categorised links during save. If you disable this, it allows you to have some links with categories in the URL and some without.', 'thirstyaffiliates').'</span>
		</td>
	</tr>

	<tr>
		<th>
			<label for="thirstyOptions[linkredirecttype]">'.__('Link Redirect Type:', 'thirstyaffiliates').'</label>
		<td>';

	foreach ($redirectTypes as $redirectTypeCode => $redirectTypeDesc) {

		$linkTypeSelected = false;
		if (strcasecmp($thirstyOptions['linkredirecttype'], $redirectTypeCode) == 0)
			$linkTypeSelected = true;

		echo '<input type="radio" name="thirstyOptions[linkredirecttype]" id="thirstyOptionsLinkRedirectType' . $redirectTypeCode .'" ' .
			($linkTypeSelected ? 'checked="checked" ' : '') . 'value="' . $redirectTypeCode . '" /> <label for="thirstyOptionsLinkRedirectType' . $redirectTypeCode .'">' . $redirectTypeDesc . '</label><br />';

	}

	$additionalreltags = isset($thirstyOptions['additionalreltags']) ? $thirstyOptions['additionalreltags'] : "";

	echo '
		</td>
		<td>
			<span class="description">'.__('This is the type of redirect ThirstyAffiliates will use to redirect the user to your affiliate link.', 'thirstyaffiliates').'</span>
		</td>
	</tr>

	<tr>
		<th>
			<label for="thirstyOptions[nofollow]">'.__('Use no follow on links?', 'thirstyaffiliates').'</label>
		<td>
			<input type="checkbox" name="thirstyOptions[nofollow]" id="thirstyOptionsNofollow" ' .
			$thirstyOptions['nofollow'] . ' />
		</td>
		<td>
			<span class="description">'.__('Add the nofollow attribute to links so search engines don\'t index them', 'thirstyaffiliates').'</span>
		</td>
	</tr>

	<tr>
		<th>
			<label for="thirstyOptions[newwindow]">'.__('Open links in new window?', 'thirstyaffiliates').'</label>
		<td>
			<input type="checkbox" name="thirstyOptions[newwindow]" id="thirstyOptionsNewwindow" ' .
			$thirstyOptions['newwindow'] . ' />
		</td>
		<td>
			<span class="description">'.__('Force the user to open links in a new window or tab', 'thirstyaffiliates').'</span>
		</td>
	</tr>

	<tr>
		<th>
			<label for="thirstyOptions[legacyuploader]">'.__('Revert to legacy image uploader?', 'thirstyaffiliates').'</label>
		<td>
			<input type="checkbox" name="thirstyOptions[legacyuploader]" id="thirstyOptionsLegacyUploader" ' .
			$thirstyOptions['legacyuploader'] . ' />
		</td>
		<td>
			<span class="description">'.__('Disable the new media uploader in favour of the old style uploader', 'thirstyaffiliates').'</span>
		</td>
	</tr>

	<tr>
		<th>
			<label for="thirstyOptions[disabletitleattribute]">'.__('Disable title attribute output on link insertion?', 'thirstyaffiliates').'</label>
		<td>
			<input type="checkbox" name="thirstyOptions[disabletitleattribute]" id="thirstyOptionsDisableTitleAttribute" ' .
			$thirstyOptions['disabletitleattribute'] . ' />
		</td>
		<td>
			<span class="description">'.__('Links are automatically output with a title html attribute (by default this shows the text
			that you have linked), this option lets you disable the output of the title attribute on your links.', 'thirstyaffiliates').'</span>
		</td>
	</tr>

	<tr>
		<th>
			<label for="thirstyOptions[disablethirstylinkclass]">'.__('Disable automatic output of ThirstyAffiliates CSS classes?', 'thirstyaffiliates').'</label>
		<td>
			<input type="checkbox" name="thirstyOptions[disablethirstylinkclass]" id="thirstyOptionsDisableThirstylinkClass" ' .
			$thirstyOptions['disablethirstylinkclass'] . ' />
		</td>
		<td>
			<span class="description">'.__('To help with styling your affiliate links a CSS class called "thirstylink" is added
			to the link and a CSS class called "thirstylinkimg" is added to images (when inserting image affiliate links),
			this option disables the addition of both of these CSS classes.', 'thirstyaffiliates').'</span>
		</td>
	</tr>

	<tr>
		<th>
			<label for="thirstyOptions[disableslugshortening]">'.__('Disable slug shortening?', 'thirstyaffiliates').'</label>
		<td>
			<input type="checkbox" name="thirstyOptions[disableslugshortening]" id="thirstyOptionsDisableSlugShortening" ' .
			$thirstyOptions['disableslugshortening'] . ' />
		</td>
		<td>
			<span class="description">'.__('By default, ThirstyAffiliates removes superfluous words from your cloaked link URLs, this option turns that feature off.', 'thirstyaffiliates').'</span>
		</td>
	</tr>

	<tr>
		<th>
			<label for="thirstyOptions[disablevisualeditorbuttons]">'.__('Disable buttons on the Visual editor?', 'thirstyaffiliates').'</label>
		<td>
			<input type="checkbox" name="thirstyOptions[disablevisualeditorbuttons]" id="thirstyOptionsDisableVisualEditorButtons" ' .
			$thirstyOptions['disablevisualeditorbuttons'] . ' />
		</td>
		<td>
			<span class="description">'.__('Hide the ThirstyAffiliates buttons on the Visual editor.', 'thirstyaffiliates').'</span>
		</td>
	</tr>

	<tr>
		<th>
			<label for="thirstyOptions[disabletexteditorbuttons]">'.__('Disable buttons on the Text/Quicktags editor?', 'thirstyaffiliates').'</label>
		<td>
			<input type="checkbox" name="thirstyOptions[disabletexteditorbuttons]" id="thirstyOptionsDisableTextEditorButtons" ' .
			$thirstyOptions['disabletexteditorbuttons'] . ' />
		</td>
		<td>
			<span class="description">'.__('Hide the ThirstyAffiliates buttons on the Text editor.', 'thirstyaffiliates').'</span>
		</td>
	</tr>

	<tr>
		<th>
			<label for="thirstyOptions[additionalreltags]">'.__('Additional rel attribute tags to add during link insertion: ', 'thirstyaffiliates').'</label>
		<td>
			<input type="text" name="thirstyOptions[additionalreltags]" id="thirstyOptionsAdditionalRelTags" value="' .
			$additionalreltags . '" />
		</td>
		<td>
			<span class="description">'.__('Allows you to add extra tags into the rel= attribute when links are inserted.', 'thirstyaffiliates').'</span>
		</td>
	</tr>';

	do_action('thirstyAffiliatesAfterMainSettings');

	echo '
	</table>

	<input type="hidden" name="thirstyOptions[rebuildlinks]" id="thirstyHiddenRebuildFlag" value="false" />

	<input type="hidden" name="page_options" value="thirstyOptions" />

	<p class="submit">
	<input type="submit" class="button-primary" value="'.__('Save All Changes', 'thirstyaffiliates').'" />
	<input type="submit" id="thirstyForceLinkRebuild" class="button-secondary" value="'.__('Save & Force Link Rebuild').'" />
	</p>

	</form>

	<div class="thirstyWhiteBox">

		<h3>'.__('Plugin Information', 'thirstyaffiliates').'</h3>'.

		'ThirstyAffiliates Version: '. THIRSTY_VERSION .'<br />';

		do_action('thirstyAffiliatesPluginInformation');

	echo '</div><!-- /.thirstyWhiteBox -->';

	do_action('thirstyAffiliatesAfterPluginInformation');

	echo '
		<div class="thirstyWhiteBox">
			<h3>Join The Community</h3>
			<ul id="thirstyCommunityLinks"><li><a href="http://thirstyaffiliates.com">'.__('Visit Our Website', 'thirstyaffiliates').'</a></li>
				<li><a href="' . admin_url('edit.php?post_type=thirstylink&page=thirsty-addons') . '">'.__('Browse ThirstyAffiliates Add-ons', 'thirstyaffiliates').'</a></li>
				<li><a href="http://thirstyaffiliates.com/affiliates">'.__('Join Our Affiliate Program', 'thirstyaffiliates').'</a> '.__('(up to 50% commissions)' ,'thirstyaffiliates').'</li>
				<li><a href="http://facebook.com/thirstyaffiliates" style="margin-right: 10px;">'.__('Like us on Facebook', 'thirstyaffiliates').'</a><iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fthirstyaffiliates&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;font=arial&amp;colorscheme=light&amp;action=like&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:21px; vertical-align: bottom;" allowTransparency="true"></iframe></li>
				<li><a href="http://twitter.com/thirstyaff" style="margin-right: 10px;">'.__('Follow us on Twitter', 'thirstyaffiliates').'</a> <a href="https://twitter.com/thirstyaff" class="twitter-follow-button" data-show-count="true" style="vertical-align: bottom;">Follow @thirstyaff</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?"http":"https";if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document, "script", "twitter-wjs");</script></li>
			</ul>
		</div><!-- /.thirstyWhiteBox -->

	</div><!-- /.wrap -->';

	// Provide debug output for diagnostics and support use
    if(isset($_GET['debug'])){
        if ($_GET['debug'] == 'true') {
            $thirstyOptions = get_option('thirstyOptions'); // re-retrieve options in case any of the filters/actions messed with it
            echo '<pre>'.__('DEBUG: ','thirstyaffiliates') . print_r($thirstyOptions, true) . '</pre>';
        }
    }
}

/*******************************************************************************
** thirstyResaveAllLinks
** Resave all ThirstyAffiliates links in the system. Allows us to regenerate the
** slug and permalink after big settings changes.
** @since 2.1
*******************************************************************************/
function thirstyResaveAllLinks() {

	$thirstyLinkQuery = new WP_Query(array(
		'post_type' => 'thirstylink',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'ignore_sticky_posts'=> 1
	));

	if($thirstyLinkQuery->have_posts()) {
		while ($thirstyLinkQuery->have_posts()) {
			$thirstyLinkQuery->the_post();

			$thirstyLink['ID'] = get_the_ID();
			wp_update_post($thirstyLink);
		}
	}
}

/*******************************************************************************
** thirstyGenerateSelectOptions
** Helper function to generate selection boxes for admin page
** @since 1.0
*******************************************************************************/
function thirstyGenerateSelectOptions($selectNames, $echo = false) {
	$thirstyOptions = get_option('thirstyOptions');
	$html = '';

	foreach ($selectNames as $selectName) {
		$html .= '<option value="' . $selectName . '"' . ($thirstyOptions['linkprefix'] == $selectName ? ' selected' : '') . '>' . $selectName . '</option>';
	}

	if ($echo)
		echo $html;
	else
		return $html;
}

add_action('admin_menu', 'thirstySetupMenu', 99);

/*******************************************************************************
** thirstyGlobalAdminNotices
** This should only be added to for really critical configuration problems that
** the admin should know about. In most cases this shows a notice to the admin
** explaining about the config problem and what they have to do to fix it.
** @since 2.4.6
*******************************************************************************/
function thirstyGlobalAdminNotices() {
	// Check for pretty permalinks
	global $wp_rewrite;
	if (empty($wp_rewrite->permalink_structure)) {
		echo '<div class="error">
			<p>'.__('ThirstyAffiliates requires pretty permalinks, please change
			your', 'thirstyaffiliates').' <a href="' . admin_url('options-permalink.php') . '">'.__('Permalink settings', 'thirstyaffiliates').'</a> '.__('to something other than default.', 'thirstyaffiliates').'<a href="#" style="float: right;" id="thirstyDismissPermalinksMessage">'.__('Dismiss', 'thirstyaffiliates').'</a></p>
		</div>';
	}
}

add_action('admin_notices', 'thirstyGlobalAdminNotices');

/**
 * Render export/import controls.
 *
 * @contributor J++
 * @since 2.5
 */
function renderExportImportControls(){
	?>
    <style>
        .export_import_settings_instruction {
            margin-bottom: 30px;
        }
        .export_import_settings_instruction dt {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .export_import_settings_instruction dd {
            margin-bottom: 20px;
        }
        .export_import_settings_instruction dd ul {
            list-style-type: disc;
        }
    </style>
	<div id="export_import_controls_container" class="thirstyWhiteBox">
		<h3><?php _e('Export/Import Global Settings', 'thirstyaffiliates'); ?></h3>

        <dl class="export_import_settings_instruction">
            <dt><?php _e('Exporting Settings', 'thirstyaffiliates'); ?></dt>
            <dd>
                <ul>
                    <li><?php _e('Click export settings button', 'thirstyaffiliates'); ?></li>
                    <li><?php _e('Copy the settings text code', 'thirstyaffiliates'); ?></li>
                    <li><?php _e('Paste in the settings code to the destination site', 'thirstyaffiliates'); ?></li>
                </ul>
            </dd>

            <dt><?php _e('Importing Settings', 'thirstyaffiliates'); ?></dt>
            <dd>
                <ul>
                    <li><?php _e('Click import settings button', 'thirstyaffiliates'); ?></li>
                    <li><?php _e('Paste the settings text code ( From other site )', 'thirstyaffiliates'); ?></li>
                    <li><?php _e('Click import global settings button', 'thirstyaffiliates'); ?></li>
                </ul>
            </dd>
        </dl>

		<input type="button" class="button button-primary" id="export_global_settings" value="<?php _e('Export Settings', 'thirstyaffiliates'); ?>" />
		<input type="button" class="button button-primary" id="import_global_settings" value="<?php _e('Import Settings', 'thirstyaffiliates'); ?>" />

		<div id="textarea_container">
			<textarea id="global_settings_string" cols="40" rows="10"></textarea>
		</div>
		<input type="button" class="button button-primary" id="import_global_settings_action" value="<?php _e('Import Global Settings', 'thirstyaffiliates'); ?>"/>
	</div>
	<?php
}

add_action( 'thirstyAffiliatesAfterPluginInformation' , 'renderExportImportControls' );

/**
 * Export global settings.
 *
 * @param null $dummyArg
 * @param bool $ajaxCall
 *
 * @contributor J++
 * @return bool
 * @since 2.5
 */
function thirstyExportGlobalSettings( $dummyArg = null , $ajaxCall = true ){
	if (!current_user_can(apply_filters('thirstyAjaxOptionsCapability', 'manage_options')))
		die('Cheatin\', Huh?');

	$thirstyOption = base64_encode( serialize( get_option('thirstyOptions') ) );

	if($ajaxCall === true){

		header('Content-Type: application/json'); // specify we return json
		echo json_encode(array(
			'status'        =>  'success',
			'thirstyOption' =>  $thirstyOption
		));
		die();

	}else{

		return true;

	}

}

add_action( "wp_ajax_thirstyExportGlobalSettings" , 'thirstyExportGlobalSettings' );

/**
 * Import global settings.
 *
 * @param null $thirstyOptions
 * @param bool $ajaxCall
 *
 * @contributor J++
 * @return bool
 * @since 2.5
 */
function thirstyImportGlobalSettings( $thirstyOptions = null , $ajaxCall = true ){
	if (!current_user_can(apply_filters('thirstyAjaxOptionsCapability', 'manage_options')))
		die('Cheatin\', Huh?');

	// We do this coz unserialize issues E_NOTICE on failure.
	error_reporting( E_ERROR | E_PARSE );

	if ( $ajaxCall === true )
		$thirstyOptions = $_POST[ 'thirstyOptions' ];

	$err = null;
	$thirstyOptions = base64_decode( $thirstyOptions );

	if ( !$thirstyOptions )
		$err = __("Failed to decode settings string", "thirstyaffiliates");

	if ( is_null( $err ) ) {

		$thirstyOptions = maybe_unserialize( $thirstyOptions );

		if ( !$thirstyOptions )
			$err = __("Failed to unserialize settings string", "thirstyaffiliates");

	}

	if ( is_null( $err ) )
		update_option( 'thirstyOptions' , $thirstyOptions );

	if($ajaxCall === true){

		if ( is_null( $err ) ) {

			header('Content-Type: application/json'); // specify we return json
			echo json_encode(array(
				'status'        =>  'success'
			));
			die();

		} else {

			header('Content-Type: application/json'); // specify we return json
			echo json_encode(array(
				'status'        =>  'fail',
				'error_message' =>  $err
			));
			die();

		}

	}else{

		if ( is_null( $err ) )
			return true;
		else
			return false;

	}

}

add_action( "wp_ajax_thirstyImportGlobalSettings" , 'thirstyImportGlobalSettings' );
