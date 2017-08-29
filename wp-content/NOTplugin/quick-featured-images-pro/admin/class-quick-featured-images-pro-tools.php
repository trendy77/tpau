<?php
/**
 * @package   Quick_Featured_Images_Pro_Tools
 * @author    Martin Stehle <m.stehle@gmx.de>
 * @license   GPL-2.0+
 * @link      http://stehle-internet.de
 * @copyright 2014 Martin Stehle
 */

class Quick_Featured_Images_Pro_Tools { // only for debugging: extends Quick_Featured_Images_Pro_Base {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Required user capability to use this plugin
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $required_user_cap = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Name of this plugin.
	 *
	 * @since    7.0
	 *
	 * @var      string
	 */
	protected $plugin_name = null;

	/**
	 * Unique identifier for this plugin.
	 *
	 * It is the same as in class Quick_Featured_Images_Pro_Admin
	 * Has to be set here to be used in non-object context, e.g. callback functions
	 *
	 * @since    7.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = null;

	/**
	 * Unique identifier for the admin page of this class.
	 *
	 * @since    7.0
	 *
	 * @var      string
	 */
	protected $page_slug = null;

	/**
	 * Unique identifier for the admin parent page of this class.
	 *
	 * @since    7.0
	 *
	 * @var      string
	 */
	protected $parent_page_slug = null;

	/**
	 * Unique identifier in the WP options table
	 *
	 * @since    5.0 pro
	 *
	 * @var      string
	 */
	private $settings_db_slug = null;

	/**
	 * Valid progress steps
	 *
	 * @since    1.0.0
	 *
	 * @var      array
	 */
	protected $valid_steps = null;

	/**
	 * User selected progress step
	 *
	 * @since    1.0.0
	 *
	 * @var      array
	 */
	protected $selected_step = null;

	/**
	 * Whether an image id is required or not (depends on the selected action)
	 *
	 * @since    2.0
	 *
	 * @var      bool
	 */
	protected $is_image_required = null;

	/**
	 * User selected ID of the new featured image
	 *
	 * @since    1.0.0
	 *
	 * @var      integer
	 */
	protected $selected_image_id = null;

	/**
	 * User selected IDs of the new featured images
	 *
	 * @since    6.0
	 *
	 * @var      array
	 */
	protected $selected_multiple_image_ids = null;

	/**
	 * User selected ID of the featured image to replace
	 *
	 * @since    1.0.0
	 *
	 * @var      integer
	 */
	protected $selected_old_image_ids = null;

	/**
	 * Whether the id of a to be replaced image is set or not
	 *
	 * @since    2.0
	 *
	 * @var      bool
	 */
	protected $is_error_no_old_image = null;

	/**
	 * Whether the user jumps from 'select' directly to 'confirm' omitting 'refine'
	 *
	 * @since    5.1
	 *
	 * @var      bool
	 */
	protected $is_skip_refine = null;

	/**
	 * Width of thumbnail images in the current WordPress settings
	 *
	 * @since    2.0
	 *
	 * @var      integer
	 */
	protected $used_thumbnail_width = null;
	
	/**
	 * Height of thumbnail images in the current WordPress settings
	 *
	 * @since    2.0
	 *
	 * @var      integer
	 */
	protected $used_thumbnail_height = null;
	
	/**
	 * Minimum length of image dimensions to search for
	 *
	 * @since    2.0
	 *
	 * @var      integer
	 */
	protected $min_image_length = null;
	
	/**
	 * Maximum length of image dimensions to search for
	 *
	 * @since    2.0
	 *
	 * @var      integer
	 */
	protected $max_image_length = null;
	
	/**
	 * User selected action the plugin should perform
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $selected_action = null;

	/**
	 * Valid names and descriptions of the actions
	 *
	 * @since    1.0.0
	 *
	 * @var      array
	 */
	protected $valid_actions = null;

	/**
	 * Valid names and descriptions of the actions without a user selected image
	 *
	 * @since    5.0
	 *
	 * @var      array
	 */
	protected $valid_actions_without_image = null;

	/**
	 * Valid names and descriptions of the actions with multiple user selected images
	 *
	 * @since    6.0
	 *
	 * @var      array
	 */
	protected $valid_actions_multiple_images = null;

	/**
	 * User selected filters the plugin should perform
	 *
	 * @since    1.0.0
	 *
	 * @var      array
	 */
	protected $selected_filters = null;

	/**
	 * Valid names and descriptions of the filters
	 *
	 * @since    1.0.0
	 *
	 * @var      array
	 */
	protected $valid_filters = null;

	/**
	 * User selected options the plugin should consider
	 *
	 * @since    5.1
	 *
	 * @var      array
	 */
	protected $selected_options = null;

	/**
	 * Valid names and descriptions of the options
	 *
	 * @since    5.1
	 *
	 * @var      array
	 */
	protected $valid_options = null;

	/**
	 * User selected statuses the plugin should perform
	 *
	 * @since    1.0.0
	 *
	 * @var      array
	 */
	protected $selected_statuses = null;

	/**
	 * Valid names and descriptions of the post statuses
	 *
	 * @since    1.0.0
	 *
	 * @var      array
	 */
	protected $valid_statuses = null;
	
	/**
	 * User selected search term
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $selected_search_term = null;
	
	/**
	 * User selected category
	 *
	 * @since    1.0.0
	 *
	 * @var      integer
	 */
	protected $selected_category_id = null;

	/**
	 * User selected author
	 *
	 * @since    1.0.0
	 *
	 * @var      integer
	 */
	protected $selected_author_id = null;
	
	/**
	 * User selected tag
	 *
	 * @since    1.0.0
	 *
	 * @var      integer
	 */
	protected $selected_tag_id = null;
	
	/**
	 * User selected names and descriptions of post types supporting featured images by default
	 *
	 * @since    1.0.0
	 *
	 * @var      array
	 */
	protected $selected_post_types = null;
	
	/**
	 * Valid names and descriptions of the post types supporting featured images by default
	 *
	 * @since    1.0.0
	 *
	 * @var      array
	 */
	protected $valid_post_types = null;
	
	/**
	 * User selected names and descriptions of post formats supporting featured images by default
	 *
	 * @since    3.6.0
	 *
	 * @var      array
	 */
	protected $selected_post_formats = null;
	
	/**
	 * Valid names and descriptions of the post formats supporting featured images by default
	 *
	 * @since    3.6.0
	 *
	 * @var      array
	 */
	protected $valid_post_formats = null;
	
	/**
	 * User selected names and descriptions of mime types supporting featured images by default
	 *
	 * @since    9.0
	 *
	 * @var      array
	 */
	protected $selected_mime_types = null;
	
	/**
	 * Valid names and descriptions of the mime types supporting featured images by default
	 *
	 * @since    9.0
	 *
	 * @var      array
	 */
	protected $valid_mime_types = null;
	
	/**
	 * User selected names and descriptions of custom post types
	 *
	 * @since    1.0.0
	 *
	 * @var      array
	 */
	protected $selected_custom_post_types = null;
	
	/**
	 * Valid names and descriptions of the custom post types
	 *
	 * @since    3.0
	 *
	 * @var      array
	 */
	protected $valid_custom_post_types = null;
	
	/**
	 * User selected names and descriptions of custom taxonomies
	 *
	 * @since    3.0
	 *
	 * @var      array
	 */
	protected $selected_custom_taxonomies = null;
	
	/**
	 * Valid names and descriptions of the custom taxonomies
	 *
	 * @since    3.0
	 *
	 * @var      array
	 */
	protected $valid_custom_taxonomies = null;
	
	/**
	 * User selected date queries the plugin should perform
	 *
	 * @since    4.0
	 *
	 * @var      array
	 */
	protected $selected_date_queries = null;

	/**
	 * Valid names and descriptions of the date queries
	 *
	 * @since    1.0.0
	 *
	 * @var      array
	 */
	protected $valid_date_queries = null;
	
	/**
	 * Post dates as stored in the db
	 *
	 * @since    4.0
	 *
	 * @var      array
	 */
	protected $valid_post_dates = null;

	/**
	 * User selected custom fields the plugin should perform
	 *
	 * @since    1.0.0
	 *
	 * @var      array
	 */
	protected $selected_custom_field = null;

	/**
	 * Valid names and descriptions of the custom fields
	 *
	 * @since    1.0.0
	 *
	 * @var      array
	 */
	protected $valid_custom_field = null;

	/**
	 * User selected post ids
	 *
	 * @since    1.0.0
	 *
	 * @var      array
	 */
	protected $selected_post_ids = null;
	
	/**
	 * Valid names and descriptions of image sizes
	 *
	 * @since    2.0
	 *
	 * @var      array
	 */
	protected $valid_image_dimensions = null;

	/**
	 * User given image sizes
	 *
	 * @since    2.0
	 *
	 * @var      array
	 */
	protected $selected_image_dimensions = null;
	
	/**
	 * User selected approach the plugin should perform
	 *
	 * @since     2.0.0 pro
	 *
	 * @var      string
	 */
	protected $selected_approach = null;

	/**
	 * Valid names and descriptions of the approaches of finding the first image
	 *
	 * @since     2.0.0 pro
	 *
	 * @var      array
	 */
	protected $valid_approaches_first_image = null;

	/**
	 * User selected search options the plugin should perform
	 *
	 * @since    3.5.0 pro
	 *
	 * @var      array
	 */
	protected $selected_search_options = null;

	/**
	 * Valid names and descriptions of the search options
	 *
	 * @since    3.5.0 pro
	 *
	 * @var      array
	 */
	protected $valid_search_options = null;

	/**
	 * User selected parent pages
	 *
	 * @since    5.2 pro
	 *
	 * @var      array
	 */
	protected $selected_parent_page_ids = null;
	
	/**
	 * User selected parent page to include in the query
	 *
	 * @since    5.2 pro
	 *
	 * @var      array
	 */
	protected $selected_parent_pages_included = null;

	/**
	 * Status about available NextGen Gallery plugin version < 2.0
	 *
	 * @since     2.0.0 pro
	 *
	 * @var      boolean
	 */
	protected $is_ngg1 = null;
	
	/**
	 * Status about available NextGen Gallery plugin version 2+
	 *
	 * @since     2.0.0 pro
	 *
	 * @var      boolean
	 */
	protected $is_ngg2 = null;
	
	/**
	 * Array of valid NextGen shortcode names
	 *
	 * @since     2.0.0 pro
	 *
	 * @var      array
	 */
	protected $ngg_shortcode_names = null;

	/**
	 * Regex pattern for NextGen shortcode
	 *
	 * @since     2.0.0 pro
	 *
	 * @var      string
	 */
	protected $ngg_shortcode_pattern = null;

	/**
	 * Object for NextGen displayed gallery
	 *
	 * @since     2.0.0 pro
	 *
	 * @var      object
	 */
	protected $ngg_display_mapper = null;

	/**
	 * Object for NextGen settings
	 *
	 * @since     2.0.0 pro
	 *
	 * @var      object
	 */
	protected $ngg_settings = null;

	/**
	 * Object for NextGen gallery creator
	 *
	 * @since     2.0.0 pro
	 *
	 * @var      object
	 */
	protected $ngg_factory = null;

	/**
	 * Object for NextGen single gallery
	 *
	 * @since     2.0.0 pro
	 *
	 * @var      object
	 */
	protected $ngg_gallery_mapper = null;

	/**
	 * Object for NextGen single image
	 *
	 * @since     2.0.0 pro
	 *
	 * @var      object
	 */
	protected $ngg_image_mapper = null;
	
	/**
	 * Existence of the function exif_imagetype() 
	 * stored once to improve performance in loops
	 *
	 * @since     2.0.0 pro
	 *
	 * @var      bool
	 */
	protected $is_exif_imagetype = null;
	
	/**
	 * Transient reference for temporary storaging of data
	 *
	 * @since     2.0.0 pro
	 *
	 * @var      string
	 */
	protected $transient_name = null;
		
	/**
	 * Host name of the current site
	 *
	 * @since     3.7.0 pro
	 *
	 * @var      string
	 */
	protected $site_domain = null;
	
	 /**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @access   private
	 * @since     1.0.0
	 */
	private function __construct() {

		// Call some values from public plugin class.
		$plugin = Quick_Featured_Images_Pro_Admin::get_instance();
		$this->plugin_name = $plugin->get_plugin_name();
		$this->plugin_slug = $plugin->get_plugin_slug();
		$this->page_slug = $this->plugin_slug . '-tools';
		$this->parent_page_slug = $plugin->get_page_slug();
		$this->plugin_version = $plugin->get_plugin_version();
		$this->settings_db_slug = $plugin->get_settings_db_slug();

		// get settings
		$settings = get_option( $this->settings_db_slug, array() );
		if ( isset( $settings[ 'minimum_role_all_pages' ] ) ) {
			switch ( $settings[ 'minimum_role_all_pages' ] ) {
				case 'administrator':
					$this->required_user_cap = 'manage_options';
					break;
				default:
					$this->required_user_cap = 'edit_others_posts';
			}
		} else {
			$this->required_user_cap = 'edit_others_posts';
		}
		
		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the admin page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
		
		// Add 'Bulk set' link in rows of media library list
		add_filter( 'media_row_actions', array( &$this, 'add_media_row_action' ), 10, 2 );

	}
	
	/**
	 * Do the admin main function 
	 *
	 * @since     1.0.0
	 *
	 */
	public function main() {
		// set variables
		$this->set_server_config();
		$this->set_default_values();
		// get current step
		$this->selected_step = $this->get_sanitized_step();
		#$this->dambedei( $_REQUEST );
		#$this->dambedei( $_SERVER );
		/*
		 * print content
		 */
		// no action and image required, just the start page
		if ( 'start' == $this->selected_step ) {
			// print header
			$this->display_header();
			include_once( 'views/form_start.php' );
		} else {
			// get user selected action
			$this->selected_action = $this->get_sanitized_action();
			// check if action is defined, else print error page
			if ( ! $this->selected_action ) {
				$this->display_error( 'wrong_action', false );
			} else {
				// check whether thumb id is not required due to selected action
				if ( in_array( $this->selected_action, array_merge( array_keys( $this->valid_actions_without_image ), array_keys( $this->valid_actions_multiple_images ) ) ) ) {
					$this->is_image_required = false;
				}
				// get selected image id, else 0
				$this->selected_image_id = $this->get_sanitized_image_id();
				// get selected image ids, else empty array
				$this->selected_multiple_image_ids = $this->get_sanitized_multiple_image_ids();
				// check whether an image id is available if required
				if ( $this->is_image_required && ! $this->selected_image_id ) {
					$this->display_error( 'no_image', false );
				// check whether selected attachment is an image if required
				} elseif ( $this->is_image_required && ! wp_get_attachment_image_src( $this->selected_image_id ) ) {
					$this->display_error( 'no_result', sprintf( __( 'Wrong image ID %d', 'quick-featured-images-pro' ), $this->selected_image_id ) );
				// check whether there are selected images if required
				} elseif ( 'assign_randomly' == $this->selected_action && ! $this->selected_multiple_image_ids ) {
					$this->display_error( 'no_image', false );
				} else {
					// get user selected filters
					$this->selected_filters = $this->get_sanitized_filter_names();
					// get user selected options
					$this->selected_options = $this->get_sanitized_option_names();
					// get user selected approach
					$this->selected_approach = $this->get_sanitized_approach();
					// get user selected search options
					$this->selected_search_options = $this->get_sanitized_search_options();
					// get user selected parent page options
					$this->selected_parent_pages_included = $this->get_sanitized_parent_pages_included();
					// after the old image selection page (filter_replace.php) and if no old image was selected
					if ( 'replace' == $this->selected_action && 'confirm' == $this->selected_step && ! isset( $_POST[ 'replacement_image_ids' ] ) ) {
						// stay on the selection page with a warning
						$this->selected_step = 'select';
						$this->is_error_no_old_image = true;
					// check if user comes from direct link in media page
					} elseif ( 'assign' == $this->selected_action && 'select' == $this->selected_step && $this->selected_image_id && isset( $_REQUEST[ '_wpnonce' ] ) ) {
						// go to the filter selection page directly
						$this->is_direct_access = true;
					// check if user comes from the selection page and has not select any filter
					} elseif ( 'refine' == $this->selected_step	&& empty( $this->selected_filters ) ) {
						// skip refine page, go to the confirm page directly
						$this->selected_step = 'confirm';
						$this->is_skip_refine = true;
					}

					// print header
					$this->display_header();
					// print content based of process
					switch ( $this->selected_step ) {
						case 'select':
							if ( $this->is_error_no_old_image ) {
								check_admin_referer( 'quickfi_refine', $this->plugin_slug . '_nonce' );
							} elseif ( $this->is_direct_access ) {
								// no referer check
								check_admin_referer( 'bulk-assign' );
							} else {
								check_admin_referer( 'quickfi_start', $this->plugin_slug . '_nonce' );
							}
							// print selected thumbnail if required
							include_once( 'views/section_image.php' );	
							// print form to select the posts to apply the action to
							include_once( 'views/form_select.php' );	
							break;
						case 'refine':
							check_admin_referer( 'quickfi_select', $this->plugin_slug . '_nonce' );
							// print selected thumbnail if required
							include_once( 'views/section_image.php' );	
							// print form to refine choice
							include_once( 'views/form_refine.php' );	
							// print form for going back to the filter selection without loosing input data
							include_once( 'views/form_back_to_selection.php' );	
							break;
						case 'confirm':
							if ( $this->is_skip_refine ) {
								check_admin_referer( 'quickfi_select', $this->plugin_slug . '_nonce' );
							} else {
								check_admin_referer( 'quickfi_refine', $this->plugin_slug . '_nonce' );
							}
							// filter posts
							$results = $this->find_posts();
							// print selected thumbnail if required
							include_once( 'views/section_image.php' );	
							// print refine form again if there are no results
							include_once( 'views/form_confirm.php' );	
							// print form to refine choice if filters were selected
							if ( $this->selected_filters ) {
								include_once( 'views/form_refine.php' );	
							}
							// print form for going back to the filter selection without loosing input data
							include_once( 'views/form_back_to_selection.php' );	
							break;
						case 'perform':
							check_admin_referer( 'quickfi_confirm', $this->plugin_slug . '_nonce' );
							// filter posts and apply action to found posts
							$results = $this->perform_action();
							// print results
							include_once( 'views/section_results.php' );	
							// print form for going back to the filter selection without loosing input data
							include_once( 'views/form_back_to_selection.php' );	
							break;
					} // switch( selected step )
				} // if( image available )
			} // if( action available )
		} // if( is start )
		// print footer
		$this->display_footer();
	}
	
	/**
	 * Set variables
	 *
	 * @access   private
	 * @since     1.0.0
	 */
	private function set_default_values() {
		/*
		 * Note: The order of the entries affects the order in the frontend page
		 *
		 */
		// process steps
		$this->valid_steps = array(
			'start'		=> __( 'Select', 'quick-featured-images-pro' ),
			'select'	=> __( 'Add filter', 'quick-featured-images-pro' ),
			'refine' 	=> __( 'Refine', 'quick-featured-images-pro' ),
			'confirm'	=> __( 'Confirm', 'quick-featured-images-pro' ),
			'perform'	=> __( 'Perform', 'quick-featured-images-pro' ),
		);
		// actions
		$this->valid_actions = array(
			'assign'			=> __( 'Set the selected image as new featured image', 'quick-featured-images-pro' ),
			'replace'			=> __( 'Replace featured images by the selected image', 'quick-featured-images-pro' ),
			'remove'			=> __( 'Remove the selected image as featured image', 'quick-featured-images-pro' ),
		);
		$this->valid_actions_without_image = array(
			'assign_first_img'	=> __( 'Set the first image as featured image', 'quick-featured-images-pro' ),
			'remove_any_img'	=> __( 'Remove any image as featured image', 'quick-featured-images-pro' ),
		);
		$this->valid_actions_multiple_images = array(
			'assign_randomly'	=> __( 'Set multiple images randomly as featured images', 'quick-featured-images-pro' ),
		);
		$this->valid_approaches_first_image = array(
			'first_embedded_image'		=> __( 'Take the first post image if available in the media library', 'quick-featured-images-pro' ),
			'first_internal_image'		=> __( 'Take the first post image from current site domain, copy and add it to the media library if not available there', 'quick-featured-images-pro' ),
			'first_external_image'		=> __( 'Take the first external post image, download it and add it to the media library', 'quick-featured-images-pro' ),
			'first_attached_image'		=> __( 'Take the first attached image of a post', 'quick-featured-images-pro' ),
			'first_wp_gallery_image'	=> __( 'Take the first image of a WordPress standard gallery', 'quick-featured-images-pro' ),
			'first_wp_nextgen_image'	=> __( 'Take the first image of a NextGen Gallery', 'quick-featured-images-pro' ),
		);
		// process options
		$this->valid_options = array(
			'overwrite'				=> __( 'Overwrite featured images', 'quick-featured-images-pro' ),
			'orphans_only'			=> __( 'Consider only posts without any featured image', 'quick-featured-images-pro' ),
			'remove_first_img'		=> __( 'Remove first embedded image', 'quick-featured-images-pro' ),
			'use_img_only_once'		=> __( 'Use each selected image only once', 'quick-featured-images-pro' ),
			'remove_excess_imgs'	=> __( 'Remove excess featured images', 'quick-featured-images-pro' ),
			'forced_removal'		=> __( 'Forced removal', 'quick-featured-images-pro' ),
			'attach_image_to_post'	=> __( 'Attach image to post after set as featured', 'quick-featured-images-pro' ),
			'detach_image_from_post'=> __( 'Detach image from post after removed as featured', 'quick-featured-images-pro' ),
		);
		// filters
		$this->valid_filters = array(
			'filter_post_types' 		=> __( 'Post Type Filter', 'quick-featured-images-pro' ),
			'filter_post_formats' 		=> __( 'Post Format Filter', 'quick-featured-images-pro' ),
			'filter_category' 			=> __( 'Category Filter', 'quick-featured-images-pro' ),
			'filter_tag' 				=> __( 'Tag Filter', 'quick-featured-images-pro' ),
			'filter_mime_types' 		=> __( 'Multimedia File Filter', 'quick-featured-images-pro' ),
			'filter_status' 			=> __( 'Status Filter', 'quick-featured-images-pro' ),
			'filter_search' 			=> __( 'Search Filter', 'quick-featured-images-pro' ),
			'filter_time' 				=> __( 'Time Filter', 'quick-featured-images-pro' ),
			'filter_author' 			=> __( 'Author Filter', 'quick-featured-images-pro' ),
			//'filter_custom_field' 		=> __( 'Custom Field Filter', 'quick-featured-images-pro' ),
			'filter_custom_taxonomies'	=> __( 'Custom Taxonomy Filter', 'quick-featured-images-pro' ),
			'filter_image_size' 		=> __( 'Featured Image Size Filter', 'quick-featured-images-pro' ),
			'filter_parent_page' 		=> __( 'Parent Page Filter', 'quick-featured-images-pro' ),
		);
		// post types (generic and custom)
		$label_posts = 'Posts';
		$label_pages = 'Pages';
		$this->valid_post_types = array(
			'post' => _x( $label_posts, 'post type general name' ),
			'page' => _x( $label_pages, 'post type general name' ),
		);
		$this->valid_custom_post_types = $this->get_registered_custom_post_types();
		// post formats (generic) except 'standard' post format
		$this->valid_post_formats = get_post_format_strings();
		unset( $this->valid_post_formats[ 'standard' ] ); // Special case, delete it from list
		// mime types (supporting feat. images by default)
		$this->valid_mime_types = array(
			'audio' => __( 'Audio files', 'quick-featured-images-pro' ),
			'video' => __( 'Video files', 'quick-featured-images-pro' ),
		);
		// statuses
		$text             = 'Private';
		$label_private    = _x( $text, 'post status' );
		$text             = 'Published';
		$label_publish    = _x( $text, 'post status' );
		$text             = 'Scheduled';
		$label_future     = _x( $text, 'post status' );
		$text             = 'Pending';
		$label_pending    = _x( $text, 'post status' );
		$text             = 'Draft';
		$label_draft      = _x( $text, 'post status' );
		$this->valid_statuses = array(
			'publish' => $label_publish,
			'pending' => $label_pending,
			'draft'   => $label_draft,
			'future'  => $label_future,
			'private' => $label_private
		);
		// time and dates
		$this->valid_date_queries = array(
			'after' 	=> __( 'Start Date', 'quick-featured-images-pro' ),
			'before' 	=> __( 'End Date', 'quick-featured-images-pro' ),
			'inclusive'	=> __( 'Include the posts of the selected dates', 'quick-featured-images-pro' )
		);
		// custom fields
		$this->valid_custom_field = array(
			'key' 		=> __( 'Custom field name', 'quick-featured-images-pro' ),
			'compare' 	=> __( 'Operator to test with the value in the custom field', 'quick-featured-images-pro' ),
			'value' 	=> __( 'Custom field value to compare with', 'quick-featured-images-pro' ),
			'type' 		=> __( 'Custom field type', 'quick-featured-images-pro' )
		);
		// custom taxonomies
		$this->valid_custom_taxonomies = $this->get_registered_custom_taxonomies();
		// image dimensions
		$this->valid_image_dimensions = array(
			'max_width' 	=> __( 'Image width in pixels lower than', 'quick-featured-images-pro' ),
			'max_height' 	=> __( 'Image height in pixels lower than', 'quick-featured-images-pro' ),
		);
		// text search options
		$this->valid_search_options = array(
			'title_only' 	=> __( 'Search in post titles only', 'quick-featured-images-pro' ),
		);
		// default: user selected image is required
		$this->is_image_required = true;
		// default: start form
		$this->selected_step = 'start';
		// default: no images
		$this->selected_old_image_ids = array();
		$this->selected_image_id = 0;
		$this->selected_multiple_image_ids = array();
		$this->is_error_no_old_image = false;
		$this->is_direct_access = false;
		$this->is_skip_refine = false;
		// default: no category
		$this->selected_category_id = 0;
		// default: no parent page
		$this->selected_parent_page_ids = array();
		// default: no author
		$this->selected_author_id = 0;
		// default: no tag
		$this->selected_tag_id = 0;
		// default: no date query
		$this->selected_date_queries = array( 'inclusive' => "1" ); // default: include posts matching selected time specifications
		// default: all post statuses
		$this->selected_statuses = array_keys( $this->valid_statuses ); // default: all statuses
		// default: all post types
		$this->selected_post_types = array_keys( $this->valid_post_types ); // default: all posts, pages and custom post types. old: posts only. No pages, no custom post types. old string: array_keys( $this->valid_post_types );
		// default: all custom post types
		$this->selected_custom_post_types = $this->valid_custom_post_types;
		// default: no post formats, thus standard post format
		$this->selected_post_formats = array();
		// default: no mime types
		$this->selected_mime_types = array();
		// default: no custom taxonomies
		$this->selected_custom_taxonomies = array();
		// default: no custom field
		$this->selected_custom_field = array();
		// default: no selected posts
		$this->selected_post_ids = array();
		// get user defined dimensions for thumbnails, else take 150 px and set maximum value if necessary
		$max_dimension = 160; // width of thumbnail column in px at 1024 px window width
		$this->used_thumbnail_width  = get_option( 'thumbnail_size_w', $max_dimension );
		$this->used_thumbnail_height = get_option( 'thumbnail_size_h', $max_dimension );
		$this->used_thumbnail_width = $this->used_thumbnail_width > $max_dimension ? $max_dimension : $this->used_thumbnail_width;
		$this->used_thumbnail_height = $this->used_thumbnail_height > $max_dimension ? $max_dimension : $this->used_thumbnail_height;
		 // default:  stored sizes for thumbnails
		$this->selected_image_dimensions = array(
			'max_width' 	=> $this->used_thumbnail_width,
			'max_height' 	=> $this->used_thumbnail_height,
		);
		// default: min 1 x 1 px, max 9999 x 9999 px images
		$this->min_image_length = 1;
		$this->max_image_length = 9999;
		// available NextGen Gallery plugin; true or false
		$this->is_ngg1 = class_exists( 'nggOptions' );
		$this->is_ngg2 = ( 
			defined( 'NGG_ATTACH_TO_POST_SLUG' ) 
			and class_exists( 'C_Displayed_Gallery_Mapper' )
			and class_exists( 'C_NextGen_Settings' )
			and class_exists( 'C_Component_Factory' )
			and class_exists( 'C_Gallery_Mapper' )
			and class_exists( 'C_Image_Mapper' )
		);
		// Domain name of WP site
		$parsed_url = parse_url( home_url() );
		$this->site_domain = $parsed_url[ 'host' ];
		// existence of the exif_imagetype()
		$this->is_exif_imagetype = function_exists( 'exif_imagetype' );
		// slug for cached results
		$this->transient_name = 'quick_featured_images_pro_results';
	}
	
	/**
	 * Set server timeout for PHP scripts in seconds
	 *
	 * @access   private
	 * @since    5.4
	 */
	private function set_server_config() {
		// to prevent blank pages for this script:
		// set server timeout to 3000 seconds if lower
		$value = (int) ini_get( 'max_execution_time' );
		if ( 2999 > $value ) {
			ini_set( 'max_execution_time', '3000' );
		}
		// and set allowed memory space to 512 MB if lower
		preg_match( '/(\d+)(\w+)/', ini_get( 'memory_limit' ), $matches );
		if ( $matches ) {
			$value = (int) $matches[ 1 ];
			switch ( strtolower( $matches[ 2 ] ) ) {
				case 'g':
				case 'gb':
					$value *= 1024;
				case 'm':
				case 'mb':
					$value *= 1024;
				case 'k':
				case 'kb':
					$value *= 1024;
			}
			
			if ( 500000000 > $value ) {
				ini_set( 'memory_limit', '512M' );
			}
		}
	}
	
	/**
	 *
	 * Render the header of the admin page
	 *
	 * @access   private
	 * @since    1.0.0
	 */
	private function display_header() {
		include_once( 'views/section_header_progress.php' );
	}
	
	/**
	 *
	 * Render the footer of the admin page
	 *
	 * @access   private
	 * @since    1.0.0
	 */
	private function display_footer() {
		include_once( 'views/section_footer.php' );
	}
	
	/**
	 *
	 * Render the error page
	 *
	 * @access   private
	 * @since    1.0.0
	 */
	private function display_error( $reason, $value_name ) {	
		// print header
		$this->display_header();
		// print error message
		switch ( $reason ) {
			case 'missing_input_value':
				$msg = sprintf( __( 'The input field %s is empty.', 'quick-featured-images-pro' ), $value_name );
				$solution = __( 'Type in a value into the input field.', 'quick-featured-images-pro' );
				break;
			case 'missing_variable':
				$msg = sprintf( __( '%s is not defined.', 'quick-featured-images-pro' ), $value_name );
				$solution = __ ('Check how to define the value.', 'quick-featured-images-pro' );
				break;
			case 'no_image':
				$msg = __( 'There is no selected image.', 'quick-featured-images-pro' );
				$solution = __( 'Select an image from the media library.', 'quick-featured-images-pro' );
				break;
			case 'wrong_action':
				$msg = __( 'You have not selected an action.', 'quick-featured-images-pro' );
				$solution = __( 'Start again and select which action you want to apply.', 'quick-featured-images-pro' );
				break;
			case 'wrong_value':
				$msg = sprintf( __( 'The input field %s has an invalid value.', 'quick-featured-images-pro' ), $value_name );
				$solution = __( 'Type in valid values in the input field.', 'quick-featured-images-pro' );
				break;
			case 'no_result':
				$msg = $value_name;
				$solution = __( 'Type in values stored by WordPress.', 'quick-featured-images-pro' );
				break;
		} // switch ( $reason )
		include_once( 'views/section_errormsg.php' );
		//die();
	} // display_error()

	/**
	 * Call the WP Query and apply the selected action to found posts
	 * 
	 * Is an alias to 'find_posts( true )' for more readability
	 *
	 * @access   private
	 * @since     1.0.0
	 *
	 */
	private function perform_action() {
		return $this->find_posts( true );
	}

	/**
	 * Do the loop to find posts, change the thumbnail if param is true
	 *
	 * @access   private
	 * @since     1.0.0
	 *
	 * @return    array    affected posts
	 */
	private function find_posts( $perform = false ) {
		// initialise result array 
		$results = array();
		// define thumbnail properties
		$size = array( 
			absint( $this->used_thumbnail_width / 2 ), 
			absint( $this->used_thumbnail_height / 2 ) 
		);
		$attr = array( 'class' => 'attachment-thumbnail' );
		// define caching arrays for better performance while calculating attachment images
		$false_id = 'false_id'; // something to use as an array key
		$current_featured_images = array();
		$future_featured_images = array();
		$current_featured_images[ $false_id ] = false;
		$future_featured_images[ $false_id ] = false;
		// get selected options once
		$is_option = array();
		foreach ( array_keys( $this->valid_options ) as $key ) {
			$is_option[ $key ] = in_array( $key, $this->selected_options );
		}
		// define approach functions for 'first image' action
		switch ( $this->selected_approach ) {
			
			case 'first_attached_image':
				$function = 'get_first_attached_image_id';
				break;
			case 'first_wp_gallery_image':
				$function = 'get_first_wp_gallery_image_id';
				break;
			case 'first_wp_nextgen_image':
				$function = 'get_ngg2_thumb_id';
				// set variables for Nextgen operations if Nextgen is activated
				if ( $this->is_ngg2 ) {
					// set Nextgen shortcode names
					$this->ngg_shortcode_names = array( 'ngg_images', 'nggallery', 'nggalbum', 'album', 'nggtags', 'nggimagebrowser', 'imagebrowser', 'nggrandom', 'random', 'nggrecent', 'recent', 'nggsinglepic', 'singlepic', 'nggslideshow', 'slideshow', 'nggtagcloud', 'tagcloud', 'nggthumb', 'thumb',  );
					// set regular expression used to search for shortcodes inside post content
					$this->ngg_shortcode_pattern = "\[(\[?)(" . implode( '|', $this->ngg_shortcode_names ) . ")(?![\w-] )([^\]\/]*(?:\/(?!\] )[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\] )[^\[]*+)*+)\[\/\2\] )?)(\]?)";
					// set the NextGEN objects to provide some methods and defaults
					$this->ngg_settings = C_NextGen_Settings::get_instance();
					$this->ngg_factory = C_Component_Factory::get_instance();
					$this->ngg_gallery_mapper = C_Gallery_Mapper::get_instance();
					$this->ngg_display_mapper = C_Displayed_Gallery_Mapper::get_instance();
					$this->ngg_image_mapper = C_Image_Mapper::get_instance();
				}
				break;
			case 'first_internal_image':
				$function = 'get_first_internal_image_id';
				break;
			case 'first_external_image':
				$function = 'get_first_external_image_id';
				break;
			default:
				// case 'first_embedded_image'
				$function = 'get_first_content_image_id';
		}

		/* three types of tasks:
			if perform:
				if no transient:
					1: set thumbs via query
				else:
					2: set thumbs via transient
			else:
				3: get preview via query
		*/
		if ( $perform ) { // really make changes
			// check for cached data; use them for fast processing, else use query
			// if removal was selected use query, too
			if ( false === ( $query_results = get_transient( $this->transient_name ) ) or $is_option[ 'remove_first_img' ] ) {
				// they weren't there, so use the query
				$the_query = new WP_Query( $this->get_query_args() );
				//printf( '<p>%s</p>', $the_query->request ); // just for debugging
				// The Loop
				if ( $the_query->have_posts() ) {
					// do task dependent on selected action
					switch ( $this->selected_action ) {
						case 'assign':
							while ( $the_query->have_posts() ) {
								$the_query->the_post();
								// get the post id once
								$post_id = get_the_ID();
								// check if there is an existing featured image
								$thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
								// if post with featured images should be ignored, jump to next loop
								if ( $thumb_id and $is_option[ 'orphans_only' ] ) {
									continue;
								}
								$success = false;
								// if no existing featured image or if permission to overwrite it
								if ( ! $thumb_id or $is_option[ 'overwrite' ] ) {
									// set featured image id
									$thumb_id = $this->selected_image_id;
									// do the task
									$success = set_post_thumbnail( $post_id, $thumb_id );
								}
								// get html for featured image for check
								$thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
								// if existing featured image
								if ( $thumb_id ) {
									// get thumbnail html if not yet got
									if ( ! isset( $current_featured_images[ $thumb_id ] ) ) {
										$current_featured_images[ $thumb_id ] = wp_get_attachment_image( $thumb_id, $size, false, $attr );
									}
									// attach featured image to post if the featured image was set successfully
									if ( $is_option[ 'attach_image_to_post' ] and $success ) {
										wp_update_post( array( 'ID' => $thumb_id, 'post_parent' => $post_id ) );
									} // if(success and attach_image_to_post)
								} else {
									// nothing changed
									$thumb_id = $false_id; // cast from '' or 'false' to a value to use as an array key
								}
								// store edit link, post title, image html, success of action (true or false)
								$results[] = array( 
									get_edit_post_link(), 
									get_the_title(),
									$current_featured_images[ $thumb_id ],
									$success
								);
							} // while(have_posts)
							break;
						case 'assign_randomly':
							$last_index = count( $this->selected_multiple_image_ids ) - 1;
							/* if 
							 * 1. only use each selected image once
							 */
							if ( $is_option[ 'use_img_only_once' ] ) {
								$current_index = 0;
								/* if 
								 * 1. only use each selected image once and
								 * 2. remove excess / odd featured images
								 */
								if ( $is_option[ 'remove_excess_imgs' ] ) {
									/* if 
									 * 1. only use each selected image once and
									 * 2. remove excess / odd featured images and
									 * 3. overwrite existing featured images
									 */
									if ( $is_option[ 'overwrite' ] ) {
										while ( $the_query->have_posts() ) {
											$the_query->the_post();
											// get the post id once
											$post_id = get_the_ID();
											// check if there is an existing featured image
											$thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
											// if post with featured images should be ignored, jump to next loop
											if ( $thumb_id and $is_option[ 'orphans_only' ] ) {
												continue;
											}
											$success = false;
											// get thumbnail html if not yet got
											if ( $thumb_id ) {
												if ( ! isset( $current_featured_images[ $thumb_id ] ) ) {
													$current_featured_images[ $thumb_id ] = wp_get_attachment_image( $thumb_id, $size, false, $attr );
												}
											} else {
												$thumb_id = $false_id; // cast from '' or 'false' to a value to use as an array key
											}
											// if index in valid range
											if ( $current_index <= $last_index ) {
												// set featured image : future image = next image
												$thumb_id = $this->selected_multiple_image_ids[ $current_index ];
												// do the task
												$success = set_post_thumbnail( $post_id, $thumb_id );
												// get thumbnail html if not yet got
												if ( ! isset( $current_featured_images[ $thumb_id ] ) ) {
													$current_featured_images[ $thumb_id ] = wp_get_attachment_image( $thumb_id, $size, false, $attr );
												}
												// set next index
												$current_index = $current_index + 1;
												// attach featured image to post if the featured image was set successfully
												if ( $is_option[ 'attach_image_to_post' ] and $success ) {
													wp_update_post( array( 'ID' => $thumb_id, 'post_parent' => $post_id ) );
												} // if(success and attach_image_to_post)
											} else {
												// remove image : future image = no image
												// do the task
												$success = delete_post_thumbnail( $post_id );
												// detach featured image from post
												if ( $success and $is_option[ 'detach_image_from_post' ] and $post_id == wp_get_post_parent_id( $thumb_id ) ) {
													wp_update_post( array( 'ID' => $thumb_id, 'post_parent' => 0 ) );
												} // if(success and post is parent of thumb)
												$thumb_id = $false_id;
											} // is ( valid index )
											// store edit link, post title, image html, success of action (true or false)
											$results[] = array( 
												get_edit_post_link(), 
												get_the_title(),
												$current_featured_images[ $thumb_id ],
												$success
											);
										} // while(have_posts)
									/* if 
									 * 1. only use each selected image once and
									 * 2. remove excess / odd featured images and
									 * 3. do not overwrite existing featured images
									*/
									} else {
										while ( $the_query->have_posts() ) {
											$the_query->the_post();
											// get the post id once
											$post_id = get_the_ID();
											// check if there is an existing featured image
											$thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
											// if post with featured images should be ignored, jump to next loop
											if ( $thumb_id and $is_option[ 'orphans_only' ] ) {
												continue;
											}
											$success = false;
											// get thumbnail html if not yet got
											if ( $thumb_id ) {
												if ( ! isset( $current_featured_images[ $thumb_id ] ) ) {
													$current_featured_images[ $thumb_id ] = wp_get_attachment_image( $thumb_id, $size, false, $attr );
												}
											} else {
												$thumb_id = $false_id; // cast from '' or 'false' to a value to use as an array key
											}
											// if index in valid range
											if ( $current_index <= $last_index ) {
												// if no current featured image
												if ( $thumb_id == $false_id ) {
													// set featured image : future image = next image
													$thumb_id = $this->selected_multiple_image_ids[ $current_index ];
													// do the task
													$success = set_post_thumbnail( $post_id, $thumb_id );
													// get thumbnail html if not yet got
													if ( ! isset( $current_featured_images[ $thumb_id ] ) ) {
														$current_featured_images[ $thumb_id ] = wp_get_attachment_image( $thumb_id, $size, false, $attr );
													}
													// set next index
													$current_index = $current_index + 1;
													// attach featured image to post if the featured image was set successfully
													if ( $is_option[ 'attach_image_to_post' ] and $success ) {
														wp_update_post( array( 'ID' => $thumb_id, 'post_parent' => $post_id ) );
													} // if(success and attach_image_to_post)
												} else {
													// do nothing : future image = current image...
												} // if ( no current image )
											} else { 
												if ( $thumb_id != $false_id ) {
													// remove image : future image = no image
													// do the task
													$success = delete_post_thumbnail( $post_id );
													// detach featured image from post
													if ( $success and $is_option[ 'detach_image_from_post' ] and $post_id == wp_get_post_parent_id( $thumb_id ) ) {
														wp_update_post( array( 'ID' => $thumb_id, 'post_parent' => 0 ) );
													} // if(success and post is parent of thumb)
												} else { 
													// do nothing : future image = no image...
												} // if ( is current image )
												$thumb_id = $false_id;
											} // is ( valid index )
											// store edit link, post title, image html, success of action (true or false)
											$results[] = array( 
												get_edit_post_link(), 
												get_the_title(),
												$current_featured_images[ $thumb_id ],
												$success
											);
										} // while(have_posts)
									} // if ( overwrite )
								/* if 
								 * 1. only use each selected image once and
								 * 2. do not remove excess / odd featured images
								 */
								} else {
									/* if 
									 * 1. only use each selected image once and
									 * 2. do not remove excess / odd featured images and
									 * 3. overwrite existing featured images
									 */
									if ( $is_option[ 'overwrite' ] ) {
										while ( $the_query->have_posts() ) {
											$the_query->the_post();
											// get the post id once
											$post_id = get_the_ID();
											// check if there is an existing featured image
											$thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
											// if post with featured images should be ignored, jump to next loop
											if ( $thumb_id and $is_option[ 'orphans_only' ] ) {
												continue;
											}
											$success = false;
											// get thumbnail html if not yet got
											if ( $thumb_id ) {
												if ( ! isset( $current_featured_images[ $thumb_id ] ) ) {
													$current_featured_images[ $thumb_id ] = wp_get_attachment_image( $thumb_id, $size, false, $attr );
												}
											} else {
												$thumb_id = $false_id; // cast from '' or 'false' to a value to use as an array key
											}
											// if index in valid range
											if ( $current_index <= $last_index ) {
												// set featured image : future image = next image
												$thumb_id = $this->selected_multiple_image_ids[ $current_index ];
												// do the task
												$success = set_post_thumbnail( $post_id, $thumb_id );
												// get thumbnail html if not yet got
												if ( ! isset( $current_featured_images[ $thumb_id ] ) ) {
													$current_featured_images[ $thumb_id ] = wp_get_attachment_image( $thumb_id, $size, false, $attr );
												}
												// set next index
												$current_index = $current_index + 1;
												// attach featured image to post if the featured image was set successfully
												if ( $is_option[ 'attach_image_to_post' ] and $success ) {
													wp_update_post( array( 'ID' => $thumb_id, 'post_parent' => $post_id ) );
												} // if(success and attach_image_to_post)
											} else {
												// do nothing : future image = current image...
											} // is ( valid index )
											// store edit link, post title, image html, success of action (true or false)
											$results[] = array( 
												get_edit_post_link(), 
												get_the_title(),
												$current_featured_images[ $thumb_id ],
												$success
											);
										} // while(have_posts)
									/* if 
									 * 1. only use each selected image once and
									 * 2. do not remove excess / odd featured images and
									 * 3. do not overwrite existing featured images
									 */
									} else {
										while ( $the_query->have_posts() ) {
											$the_query->the_post();
											// get the post id once
											$post_id = get_the_ID();
											// check if there is an existing featured image
											$thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
											// if post with featured images should be ignored, jump to next loop
											if ( $thumb_id and $is_option[ 'orphans_only' ] ) {
												continue;
											}
											$success = false;
											// get thumbnail html if not yet got
											if ( $thumb_id ) {
												if ( ! isset( $current_featured_images[ $thumb_id ] ) ) {
													$current_featured_images[ $thumb_id ] = wp_get_attachment_image( $thumb_id, $size, false, $attr );
												}
											} else {
												$thumb_id = $false_id; // cast from '' or 'false' to a value to use as an array key
											}
											// if index in valid range
											if ( $current_index <= $last_index ) {
												// if no current featured image
												if ( $thumb_id == $false_id ) {
													// set featured image : future image = next image
													$thumb_id = $this->selected_multiple_image_ids[ $current_index ];
													// do the task
													$success = set_post_thumbnail( $post_id, $thumb_id );
													// get thumbnail html if not yet got
													if ( ! isset( $current_featured_images[ $thumb_id ] ) ) {
														$current_featured_images[ $thumb_id ] = wp_get_attachment_image( $thumb_id, $size, false, $attr );
													}
													// set next index
													$current_index = $current_index + 1;
													// attach featured image to post if the featured image was set successfully
													if ( $is_option[ 'attach_image_to_post' ] and $success ) {
														wp_update_post( array( 'ID' => $thumb_id, 'post_parent' => $post_id ) );
													} // if(success and attach_image_to_post)
												} else {
													// do nothing : future image = current image...
												} // if ( no current image )
											} else {
												// do nothing : future image = current image...
											} // is ( valid index )
											// store edit link, post title, image html, success of action (true or false)
											$results[] = array( 
												get_edit_post_link(), 
												get_the_title(),
												$current_featured_images[ $thumb_id ],
												$success
											);
										} // while(have_posts)
									} // if ( overwrite )
								} // if ( remove_excess_imgs )
							/* else
							 * 1. use selected images multiple times randomly
							 */
							} else { 
								/* else 
								 * 1. use selected images multiple times randomly and
								 * 2. overwrite existing featured images
								 */
								if ( $is_option[ 'overwrite' ] ) {
									while ( $the_query->have_posts() ) {
										$the_query->the_post();
										// get the post id once
										$post_id = get_the_ID();
										// check if there is an existing featured image
										$thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
										// if post with featured images should be ignored, jump to next loop
										if ( $thumb_id and $is_option[ 'orphans_only' ] ) {
											continue;
										}
										$success = false;
										// set image randomly : future image = new image
										$thumb_id = $this->selected_multiple_image_ids[ rand( 0, $last_index ) ]; // get thumb id randomly
										// do the task
										$success = set_post_thumbnail( $post_id, $thumb_id );
										// get thumbnail html if not yet got
										if ( ! isset( $current_featured_images[ $thumb_id ] ) ) {
											$current_featured_images[ $thumb_id ] = wp_get_attachment_image( $thumb_id, $size, false, $attr );
										}
										// attach featured image to post if the featured image was set successfully
										if ( $is_option[ 'attach_image_to_post' ] and $success ) {
											wp_update_post( array( 'ID' => $thumb_id, 'post_parent' => $post_id ) );
										} // if(success and attach_image_to_post)
										// store edit link, post title, image html, success of action (true or false)
										$results[] = array( 
											get_edit_post_link(), 
											get_the_title(),
											$current_featured_images[ $thumb_id ],
											$success
										);
									} // while(have_posts)
								/* else 
								 * 1. use selected images multiple times randomly and
								 * 2. do not overwrite existing featured images
								 */
								} else {
									while ( $the_query->have_posts() ) {
										$the_query->the_post();
										// get the post id once
										$post_id = get_the_ID();
										// check if there is an existing featured image
										$thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
										// if post with featured images should be ignored, jump to next loop
										if ( $thumb_id and $is_option[ 'orphans_only' ] ) {
											continue;
										}
										$success = false;
										// if existing featured image
										if ( $thumb_id ) {
											// do nothing : future image = current image...
											// get thumbnail html if not yet got
											if ( ! isset( $current_featured_images[ $thumb_id ] ) ) {
												$current_featured_images[ $thumb_id ] = wp_get_attachment_image( $thumb_id, $size, false, $attr );
											}
										} else {
											// set image randomly : future image = new image
											$thumb_id = $this->selected_multiple_image_ids[ rand( 0, $last_index ) ]; // get thumb id randomly
											// do the task
											$success = set_post_thumbnail( $post_id, $thumb_id );
											// get thumbnail html if not yet got
											if ( ! isset( $current_featured_images[ $thumb_id ] ) ) {
												$current_featured_images[ $thumb_id ] = wp_get_attachment_image( $thumb_id, $size, false, $attr );
											}
											// attach featured image to post if the featured image was set successfully
											if ( $is_option[ 'attach_image_to_post' ] and $success ) {
												wp_update_post( array( 'ID' => $thumb_id, 'post_parent' => $post_id ) );
											} // if(success and attach_image_to_post)
										}
										// store edit link, post title, image html, success of action (true or false)
										$results[] = array( 
											get_edit_post_link(), 
											get_the_title(),
											$current_featured_images[ $thumb_id ],
											$success
										);
									} // while(have_posts)
								} // if ( overwrite )
							} // if ( use_img_only_once )
							break;
						case 'replace':
							while ( $the_query->have_posts() ) {
								$the_query->the_post();
								// get the post id once
								$post_id = get_the_ID();
								// do the task
								$success = set_post_thumbnail( $post_id, $this->selected_image_id );
								// get html for featured image for check
								$thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
								// if existing featured image
								if ( $thumb_id ) {
									// get thumbnail html if not yet got
									if ( ! isset( $current_featured_images[ $thumb_id ] ) ) {
										$current_featured_images[ $thumb_id ] = wp_get_attachment_image( $thumb_id, $size, false, $attr );
									}
									// attach featured image to post if the featured image was set successfully
									if ( $is_option[ 'attach_image_to_post' ] and $success ) {
										wp_update_post( array( 'ID' => $thumb_id, 'post_parent' => $post_id ) );
									} // if(success and attach_image_to_post)
								} else {
									// nothing changed
									$thumb_id = $false_id; // cast from '' or 'false' to a value to use as an array key
								}
								// store edit link, post title, image html, success of action (true or false)
								$results[] = array( 
									get_edit_post_link(), 
									get_the_title(),
									$current_featured_images[ $thumb_id ],
									$success
								);
							} // while(have_posts)
							break;
						case 'remove':
						case 'remove_any_img':
							while ( $the_query->have_posts() ) {
								$the_query->the_post();
								// get the post id once
								$post_id = get_the_ID();
								// get current featured image
								$old_thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
								// do the task
								$success = delete_post_thumbnail( $post_id );
								// get html for featured image for check
								$thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
								if ( $thumb_id ) {
									// get thumbnail html if not yet got
									if ( ! isset( $current_featured_images[ $thumb_id ] ) ) {
										$current_featured_images[ $thumb_id ] = wp_get_attachment_image( $thumb_id, $size, false, $attr );
									}
								} else {
									// nothing changed
									$thumb_id = $false_id; // cast from '' or 'false' to a value to use as an array key
									// detach featured image from post
									if ( $success and $is_option[ 'detach_image_from_post' ] and $post_id == wp_get_post_parent_id( $old_thumb_id ) ) {
										wp_update_post( array( 'ID' => $old_thumb_id, 'post_parent' => 0 ) );
									} // if(success and post is parent of thumb)
								}
								// store edit link, post title, image html, success of action (true or false)
								$results[] = array( 
									get_edit_post_link(), 
									get_the_title(),
									$current_featured_images[ $thumb_id ],
									$success
								);
							} // while(have_posts)
							break;
						case 'assign_first_img':
							// do the loop
							while ( $the_query->have_posts() ) {
								$the_query->the_post();
								// get the post id once
								$post_id = get_the_ID();
								// check if there is an existing featured image
								$thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
								// if post with featured images should be ignored, jump to next loop
								if ( $thumb_id and $is_option[ 'orphans_only' ] ) {
									continue;
								}
								$success = false;
								$success_update = false;
								// if no existing featured image or if permission to overwrite it
								if ( ! $thumb_id or $is_option[ 'overwrite' ] ) {
									// get the post once (for $post->post_content)
									$post = get_post();
									$post_content = $post->post_content;
									// get the id of the first content image, else 0
									$thumb_id = call_user_func( array( $this, $function ), $post_id, $post_content );
									// if first image found
									if ( $thumb_id ) {
										// do the task
										$success = set_post_thumbnail( $post_id, $thumb_id );
										// remove first image from post content if desired and if the featured image was set successfully
										if ( $is_option[ 'remove_first_img' ] and ( $success or $is_option[ 'forced_removal' ] ) ) {
											// get the new content without first image	#$post_content = $this->remove_first_post_image( $post_content );
											// delete the first img element with its caption and link if existing
											$post_content =  preg_replace( '/(\[caption[^\]]*\]\s*)?(<a[^>]*>)?<img[^>]+>(<\/a>)?(.+?\[\/caption\]\s*)?(\r)?(\n)?/i', '', $post_content, 1 );
											// store new post content, in case of failure return 0
											$returned_id = wp_update_post( array( 'ID' => $post_id, 'post_content' => $post_content ) );
											if ( $returned_id == $post_id ) {
												$success_update = true;
											}
										} // if(success and removal)
										// attach featured image to post if the featured image was set successfully
										if ( $is_option[ 'attach_image_to_post' ] and $success ) {
											wp_update_post( array( 'ID' => $thumb_id, 'post_parent' => $post_id ) );
										} // if(success and attach_image_to_post)
									} // if(thumb_id)
								} // if ( ! $thumb_id or $is_option[ 'overwrite' ] )
								// get html for featured image for check
								$thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
								// if existing featured image
								if ( $thumb_id ) {
									// get thumbnail html if not yet got
									if ( ! isset( $current_featured_images[ $thumb_id ] ) ) {
										$current_featured_images[ $thumb_id ] = wp_get_attachment_image( $thumb_id, $size, false, $attr );
									}
								} else {
									// nothing changed
									$thumb_id = $false_id; // cast from '' or 'false' to a value to use as an array key
								}
								// store edit link, post title, image html, success of action (true or false)
								$results[] = array( 
									get_edit_post_link(), 
									get_the_title(),
									$current_featured_images[ $thumb_id ],
									$success,
									$success_update
								);
							} // while(have_posts)
							break;
					} // switch(selected_action)
				} // if( have_posts )
				// Restore original post data after the query
				wp_reset_postdata();
			} else {
				// else if there are cached results
				// do task dependent on selected action
				switch ( $this->selected_action ) {
					case 'assign':
						foreach ( $query_results as $post_id => $post_data ) {
							$thumb_id = $post_data[ 0 ];
							// cast "false" value to boolean false
							if ( $thumb_id == $false_id ) {
								$thumb_id = false;
							}
							// check if there is an existing featured image
							$current_thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
							$success = false;
							// if no existing featured image or if permission to overwrite it
							if ( ! $current_thumb_id or $is_option[ 'overwrite' ] ) {
								// do the task
								$success = set_post_thumbnail( $post_id, $thumb_id );
							}
							// get html for featured image for check
							$thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
							// if existing featured image
							if ( $thumb_id ) {
								// get thumbnail html if not yet got
								if ( ! isset( $current_featured_images[ $thumb_id ] ) ) {
									$current_featured_images[ $thumb_id ] = wp_get_attachment_image( $thumb_id, $size, false, $attr );
								}
								// attach featured image to post if the featured image was set successfully
								if ( $is_option[ 'attach_image_to_post' ] and $success ) {
									wp_update_post( array( 'ID' => $thumb_id, 'post_parent' => $post_id ) );
								} // if(success and attach_image_to_post)
							} else {
								// nothing changed
								$thumb_id = $false_id; // cast from '' or 'false' to a value to use as an array key
							}
							// store edit link, post title, image html, success of action (true or false)
							$results[] = array( 
								$post_data[ 1 ], // get_edit_post_link()
								$post_data[ 2 ], // get_the_title()
								$current_featured_images[ $thumb_id ],
								$success
							);
						} // foreach()
						break;
					case 'assign_randomly':
						foreach ( $query_results as $post_id => $post_data ) {
							$thumb_id = $post_data[ 0 ];
							// cast "false" value to boolean false
							if ( $thumb_id == $false_id ) {
								$thumb_id = false;
							}
							$success = false;
							// check if there is an existing featured image
							$current_thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
							// if existing featured image
							if ( $current_thumb_id ) {
								// if new image
								if ( $thumb_id ) {
									// if permission to overwrite existing image
									if ( $is_option[ 'overwrite' ] ) {
										// do the task
										$success = set_post_thumbnail( $post_id, $thumb_id );
									} else {
										// do nothing : keep existing image
									} // if ( overwrite )
								// if no new image
								} else {
									// if permisson to delete existing image
									if ( $is_option[ 'remove_excess_imgs' ] ) {
										// do the task
										$success = delete_post_thumbnail( $post_id );
										// detach featured image from post
										if ( $success and $is_option[ 'detach_image_from_post' ] and $post_id == wp_get_post_parent_id( $current_thumb_id ) ) {
											wp_update_post( array( 'ID' => $current_thumb_id, 'post_parent' => 0 ) );
										} // if(success and post is parent of thumb)
									} else {
										// do nothing : no image
									} // if ( delete image )
								} // if ( new image )
							// if no existing featured image
							} else {
								// if new image
								if ( $thumb_id ) {
									// do the task
									$success = set_post_thumbnail( $post_id, $thumb_id );
								} else {
									// do nothing : no image
								} // if ( new image )
							} // if ( existing image )
							// get html for featured image for check
							$thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
							// if existing featured image
							if ( $thumb_id ) {
								// get thumbnail html if not yet got
								if ( ! isset( $current_featured_images[ $thumb_id ] ) ) {
									$current_featured_images[ $thumb_id ] = wp_get_attachment_image( $thumb_id, $size, false, $attr );
								}
								// attach featured image to post if the featured image was set successfully
								if ( $is_option[ 'attach_image_to_post' ] and $success ) {
									wp_update_post( array( 'ID' => $thumb_id, 'post_parent' => $post_id ) );
								} // if(success and attach_image_to_post)
							} else {
								// nothing changed
								$thumb_id = $false_id; // cast from '' or 'false' to a value to use as an array key
							}
							// store edit link, post title, image html, success of action (true or false)
							$results[] = array( 
								$post_data[ 1 ], // get_edit_post_link()
								$post_data[ 2 ], // get_the_title()
								$current_featured_images[ $thumb_id ],
								$success
							);
						} // foreach()
						break;
					case 'replace':
						foreach ( $query_results as $post_id => $post_data ) {
							//??$thumb_id = $post_data[ 0 ];
							// do the task
							$success = set_post_thumbnail( $post_id, $thumb_id );
							// get html for featured image for check
							$thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
							if ( $thumb_id ) {
								// get thumbnail html if not yet got
								if ( ! isset( $current_featured_images[ $thumb_id ] ) ) {
									$current_featured_images[ $thumb_id ] = wp_get_attachment_image( $thumb_id, $size, false, $attr );
								}
								// attach featured image to post if the featured image was set successfully
								if ( $is_option[ 'attach_image_to_post' ] and $success ) {
									wp_update_post( array( 'ID' => $thumb_id, 'post_parent' => $post_id ) );
								} // if(success and attach_image_to_post)
							} else {
								// nothing changed
								$thumb_id = $false_id; // cast from '' or 'false' to a value to use as an array key
							}
							// store edit link, post title, image html, success of action (true or false)
							$results[] = array( 
								$post_data[ 1 ], // get_edit_post_link()
								$post_data[ 2 ], // get_the_title()
								$current_featured_images[ $thumb_id ],
								$success
							);
						} // foreach()
						break;
					case 'remove':
					case 'remove_any_img':
						foreach ( $query_results as $post_id => $post_data ) {
							// ?? $thumb_id = $post_data[ 0 ];
							// get current featured image
							$old_thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
							// do the task
							$success = delete_post_thumbnail( $post_id );
							// get html for featured image for check
							$thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
							if ( $thumb_id ) {
								// get thumbnail html if not yet got
								if ( ! isset( $current_featured_images[ $thumb_id ] ) ) {
									$current_featured_images[ $thumb_id ] = wp_get_attachment_image( $thumb_id, $size, false, $attr );
								}
							} else {
								// detach featured image from post
								if ( $success and $is_option[ 'detach_image_from_post' ] and $post_id == wp_get_post_parent_id( $old_thumb_id ) ) {
									wp_update_post( array( 'ID' => $old_thumb_id, 'post_parent' => 0 ) );
								} // if(success and post is parent of thumb)
								// no featured image
								$thumb_id = $false_id; // cast from '' or 'false' to a value to use as an array key
							}
							// store edit link, post title, image html, success of action (true or false)
							$results[] = array( 
								$post_data[ 1 ], // get_edit_post_link()
								$post_data[ 2 ], // get_the_title()
								$current_featured_images[ $thumb_id ],
								$success
							);
						} // foreach()
						break;
					case 'assign_first_img':
						foreach ( $query_results as $post_id => $post_data ) {
							$thumb_id = $post_data[ 0 ];
							// cast "false" value to boolean false
							if ( $thumb_id == $false_id ) {
								$thumb_id = false;
							}
							// check if there is an existing featured image
							$current_thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
							// if post with featured images should be ignored, jump to next loop
							if ( $current_thumb_id and $is_option[ 'orphans_only' ] ) {
								continue;
							}
							$success = false;
							$success_update = false;
							// if no existing featured image or if permission to overwrite it and if new image
							if ( ( ! $current_thumb_id or $is_option[ 'overwrite' ] ) and $thumb_id ) {
								// do the task
								$success = set_post_thumbnail( $post_id, $thumb_id );
							} // if ( ! $thumb_id or $is_option[ 'overwrite' ] )
							// get html for featured image for check
							$thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
							// if existing featured image
							if ( $thumb_id ) {
								// get thumbnail html if not yet got
								if ( ! isset( $current_featured_images[ $thumb_id ] ) ) {
									$current_featured_images[ $thumb_id ] = wp_get_attachment_image( $thumb_id, $size, false, $attr );
								}
								// attach featured image to post if the featured image was set successfully
								if ( $is_option[ 'attach_image_to_post' ] and $success ) {
									wp_update_post( array( 'ID' => $thumb_id, 'post_parent' => $post_id ) );
								} // if(success and attach_image_to_post)
							} else {
								// nothing changed
								$thumb_id = $false_id; // cast from '' or 'false' to a value to use as an array key
							}
							// store edit link, post title, image html, success of action (true or false)
							$results[] = array( 
								$post_data[ 1 ], // get_edit_post_link()
								$post_data[ 2 ], // get_the_title()
								$current_featured_images[ $thumb_id ],
								$success,
								$success_update
							);
						} // foreach()
						break;
				} // switch(selected_action)
				// delete cached results manually
				delete_transient( $this->transient_name );
			} // if transient
		} else {
			$query_results = array();
			$the_query = new WP_Query( $this->get_query_args() );
			// include parent page(s) if desired
			if ( in_array( 'filter_parent_page', $this->selected_filters ) and $this->selected_parent_pages_included ) {
				foreach ( $this->selected_parent_pages_included as $post_type ) {
					$post = get_post( $this->get_sanitized_parent_page_id( $post_type ) );
					if ( $post ) {
						$the_query->posts[] = $post;
						$the_query->post_count++;
					}
				}
			}
			#printf( '<p>%s</p>', var_export( $the_query->result, true ) ); // just for debugging
			// The Loop
			if ( $the_query->have_posts() ) {
				// do task dependent on selected action
				switch ( $this->selected_action ) {
					case 'assign':
						while ( $the_query->have_posts() ) {
							$the_query->the_post();
							// get the post id once
							$post_id = get_the_ID();
							// check if there is an existing featured image
							$current_thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
							// if post with featured images should be ignored, jump to next loop
							if ( $current_thumb_id and $is_option[ 'orphans_only' ] ) {
								continue;
							}
							if ( $current_thumb_id ) {
								// get thumbnail html if not yet got
								if ( ! isset( $current_featured_images[ $current_thumb_id ] ) ) {
									$current_featured_images[ $current_thumb_id ] = wp_get_attachment_image( $current_thumb_id, $size, false, $attr );
								}
								// get html of future thumbnail
								if ( $is_option[ 'overwrite' ] ) {
									// preview old thumb + new thumb
									$future_thumb_id = $this->selected_image_id;
									// get thumbnail html if not yet got
									if ( ! isset( $future_featured_images[ $future_thumb_id ] ) ) {
										$future_featured_images[ $future_thumb_id ] = wp_get_attachment_image( $future_thumb_id, $size, false, $attr );
									}
								} else {
									// preview old thumb + old thumb
									$future_thumb_id = $current_thumb_id;
									$future_featured_images[ $future_thumb_id ] = $current_featured_images[ $current_thumb_id ];
								}
							} else {
								// preview no old thumb + new thumb
								$current_thumb_id = $false_id; // cast from '' or 'false' to a value to use as an array key
								// get html of future thumbnail
								$future_thumb_id = $this->selected_image_id;
								// get thumbnail html if not yet got
								if ( ! isset( $future_featured_images[ $future_thumb_id ] ) ) {
									$future_featured_images[ $future_thumb_id ] = wp_get_attachment_image( $future_thumb_id, $size, false, $attr );
								}
							}
							// store edit link, post title, post date, post author, current image html, future image html
							$post_link = get_edit_post_link();
							$post_title = get_the_title();
							$results[] = array( 
								$post_link, 
								$post_title,
								get_the_date(),
								get_the_author(),
								$current_featured_images[ $current_thumb_id ],
								$future_featured_images[ $future_thumb_id ],
								get_post_status(),
								get_post_type(),
							);
							// notice result for cache
							$query_results[ $post_id ] = array( $future_thumb_id, $post_link, $post_title );
						} // while(have_posts)
						break;
					case 'assign_randomly':
						$last_index = count( $this->selected_multiple_image_ids ) - 1;
						/* if 
						 * 1. only use each selected image once
						 */
						if ( $is_option[ 'use_img_only_once' ] ) {
							$current_index = 0;
							/* if 
							 * 1. only use each selected image once and
							 * 2. remove excess / odd featured images
							 */
							if ( $is_option[ 'remove_excess_imgs' ] ) {
								/* if 
								 * 1. only use each selected image once and
								 * 2. remove excess / odd featured images and
								 * 3. overwrite existing featured images
								 */
								if ( $is_option[ 'overwrite' ] ) {
									while ( $the_query->have_posts() ) {
										$the_query->the_post();
										// get the post id once
										$post_id = get_the_ID();
										// check if there is an existing featured image
										$current_thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
										// if post with featured images should be ignored, jump to next loop
										if ( $current_thumb_id and $is_option[ 'orphans_only' ] ) {
											continue;
										}
										// get thumbnail html if not yet got
										if ( $current_thumb_id ) {
											if ( ! isset( $current_featured_images[ $current_thumb_id ] ) ) {
												$current_featured_images[ $current_thumb_id ] = wp_get_attachment_image( $current_thumb_id, $size, false, $attr );
											}
										} else {
											$current_thumb_id = $false_id; // cast from '' or 'false' to a value to use as an array key
										}
										// if index in valid range
										if ( $current_index <= $last_index ) {
											// set featured image : future image = next image
											$future_thumb_id = $this->selected_multiple_image_ids[ $current_index ];
											// get thumbnail html if not yet got
											if ( ! isset( $future_featured_images[ $future_thumb_id ] ) ) {
												$future_featured_images[ $future_thumb_id ] = wp_get_attachment_image( $future_thumb_id, $size, false, $attr );
											}
											// set next index
											$current_index = $current_index + 1;
										} else {
											// future image = no image
											$future_thumb_id = $false_id;
										} // is ( valid index )
										// store edit link, post title, post date, post author, current image html, future image html
										$post_link = get_edit_post_link();
										$post_title = get_the_title();
										$results[] = array( 
											$post_link, 
											$post_title,
											get_the_date(),
											get_the_author(),
											$current_featured_images[ $current_thumb_id ],
											$future_featured_images[ $future_thumb_id ],
											get_post_status(),
											get_post_type(),
										);
										// notice result for cache
										$query_results[ $post_id ] = array( $future_thumb_id, $post_link, $post_title );
									} // while(have_posts)
								/* if 
								 * 1. only use each selected image once and
								 * 2. remove excess / odd featured images and
								 * 3. do not overwrite existing featured images
								*/
								} else {
									while ( $the_query->have_posts() ) {
										$the_query->the_post();
										// get the post id once
										$post_id = get_the_ID();
										// check if there is an existing featured image
										$current_thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
										// if post with featured images should be ignored, jump to next loop
										if ( $current_thumb_id and $is_option[ 'orphans_only' ] ) {
											continue;
										}
										// get thumbnail html if not yet got
										if ( $current_thumb_id ) {
											if ( ! isset( $current_featured_images[ $current_thumb_id ] ) ) {
												$current_featured_images[ $current_thumb_id ] = wp_get_attachment_image( $current_thumb_id, $size, false, $attr );
											}
										} else {
											$current_thumb_id = $false_id; // cast from '' or 'false' to a value to use as an array key
										}
										// if index in valid range
										if ( $current_index <= $last_index ) {
											// if no current featured image
											if ( $current_thumb_id == $false_id ) {
												// set featured image : future image = next image
												$future_thumb_id = $this->selected_multiple_image_ids[ $current_index ];
												// get thumbnail html if not yet got
												if ( ! isset( $future_featured_images[ $future_thumb_id ] ) ) {
													$future_featured_images[ $future_thumb_id ] = wp_get_attachment_image( $future_thumb_id, $size, false, $attr );
												}
												// set next index
												$current_index = $current_index + 1;
											} else {
												// do nothing : future image = current image
												$future_thumb_id = $current_thumb_id;
												$future_featured_images[ $future_thumb_id ] = $current_featured_images[ $current_thumb_id ];
											} // if ( no current image )
										} else { 
											if ( $current_thumb_id != $false_id ) {
												// remove image : future image = no image
												#$future_thumb_id = $false_id;
											} else { 
												// do nothing : future image = no image
												#$future_thumb_id = $false_id;
											} // if ( is current image )
											$future_thumb_id = $false_id;
										} // is ( valid index )
										// store edit link, post title, post date, post author, current image html, future image html
										$post_link = get_edit_post_link();
										$post_title = get_the_title();
										$results[] = array( 
											$post_link, 
											$post_title,
											get_the_date(),
											get_the_author(),
											$current_featured_images[ $current_thumb_id ],
											$future_featured_images[ $future_thumb_id ],
											get_post_status(),
											get_post_type(),
										);
										// notice result for cache
										$query_results[ $post_id ] = array( $future_thumb_id, $post_link, $post_title );
									} // while(have_posts)
								} // if ( overwrite )
							/* if 
							 * 1. only use each selected image once and
							 * 2. do not remove excess / odd featured images
							 */
							} else {
								/* if 
								 * 1. only use each selected image once and
								 * 2. do not remove excess / odd featured images and
								 * 3. overwrite existing featured images
								 */
								if ( $is_option[ 'overwrite' ] ) {
									while ( $the_query->have_posts() ) {
										$the_query->the_post();
										// get the post id once
										$post_id = get_the_ID();
										// check if there is an existing featured image
										$current_thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
										// if post with featured images should be ignored, jump to next loop
										if ( $current_thumb_id and $is_option[ 'orphans_only' ] ) {
											continue;
										}
										// get thumbnail html if not yet got
										if ( $current_thumb_id ) {
											if ( ! isset( $current_featured_images[ $current_thumb_id ] ) ) {
												$current_featured_images[ $current_thumb_id ] = wp_get_attachment_image( $current_thumb_id, $size, false, $attr );
											}
										} else {
											$current_thumb_id = $false_id; // cast from '' or 'false' to a value to use as an array key
										}
										// if index in valid range
										if ( $current_index <= $last_index ) {
											// set featured image : future image = next image
											$future_thumb_id = $this->selected_multiple_image_ids[ $current_index ];
											// get thumbnail html if not yet got
											if ( ! isset( $future_featured_images[ $future_thumb_id ] ) ) {
												$future_featured_images[ $future_thumb_id ] = wp_get_attachment_image( $future_thumb_id, $size, false, $attr );
											}
											// set next index
											$current_index = $current_index + 1;
										} else {
											// do nothing : future image = current image
											$future_thumb_id = $current_thumb_id;
											$future_featured_images[ $future_thumb_id ] = $current_featured_images[ $current_thumb_id ];
										} // is ( valid index )
										// store edit link, post title, post date, post author, current image html, future image html
										$post_link = get_edit_post_link();
										$post_title = get_the_title();
										$results[] = array( 
											$post_link, 
											$post_title,
											get_the_date(),
											get_the_author(),
											$current_featured_images[ $current_thumb_id ],
											$future_featured_images[ $future_thumb_id ],
											get_post_status(),
											get_post_type(),
										);
										// notice result for cache
										$query_results[ $post_id ] = array( $future_thumb_id, $post_link, $post_title );
									} // while()
								/* if 
								 * 1. only use each selected image once and
								 * 2. do not remove excess / odd featured images and
								 * 3. do not overwrite existing featured images
								 */
								} else {
									while ( $the_query->have_posts() ) {
										$the_query->the_post();
										// get the post id once
										$post_id = get_the_ID();
										// check if there is an existing featured image
										$current_thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
										// if post with featured images should be ignored, jump to next loop
										if ( $current_thumb_id and $is_option[ 'orphans_only' ] ) {
											continue;
										}
										// get thumbnail html if not yet got
										if ( $current_thumb_id ) {
											if ( ! isset( $current_featured_images[ $current_thumb_id ] ) ) {
												$current_featured_images[ $current_thumb_id ] = wp_get_attachment_image( $current_thumb_id, $size, false, $attr );
											}
										} else {
											$current_thumb_id = $false_id; // cast from '' or 'false' to a value to use as an array key
										}
										// if index in valid range
										if ( $current_index <= $last_index ) {
											// if no current featured image
											if ( $current_thumb_id == $false_id ) {
												// set featured image : future image = next image
												$future_thumb_id = $this->selected_multiple_image_ids[ $current_index ];
												// get thumbnail html if not yet got
												if ( ! isset( $future_featured_images[ $future_thumb_id ] ) ) {
													$future_featured_images[ $future_thumb_id ] = wp_get_attachment_image( $future_thumb_id, $size, false, $attr );
												}
												// set next index
												$current_index = $current_index + 1;
											} else {
												// do nothing : future image = current image
												$future_thumb_id = $current_thumb_id;
												$future_featured_images[ $future_thumb_id ] = $current_featured_images[ $current_thumb_id ];
											} // if ( no current image )
										} else {
											// do nothing : future image = current image
											$future_thumb_id = $current_thumb_id;
											$future_featured_images[ $future_thumb_id ] = $current_featured_images[ $current_thumb_id ];
										} // is ( valid index )
										// store edit link, post title, post date, post author, current image html, future image html
										$post_link = get_edit_post_link();
										$post_title = get_the_title();
										$results[] = array( 
											$post_link, 
											$post_title,
											get_the_date(),
											get_the_author(),
											$current_featured_images[ $current_thumb_id ],
											$future_featured_images[ $future_thumb_id ],
											get_post_status(),
											get_post_type(),
										);
										// notice result for cache
										$query_results[ $post_id ] = array( $future_thumb_id, $post_link, $post_title );
									} // while(have_posts)
								} // if ( overwrite )
							} // if ( remove_excess_imgs )
						/* else
						 * 1. use selected images multiple times randomly
						 */
						} else { 
							/* else 
							 * 1. use selected images multiple times randomly and
							 * 2. overwrite existing featured images
							 */
							if ( $is_option[ 'overwrite' ] ) {
								while ( $the_query->have_posts() ) {
									$the_query->the_post();
									// get the post id once
									$post_id = get_the_ID();
									// check if there is an existing featured image
									$current_thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
									// if post with featured images should be ignored, jump to next loop
									if ( $current_thumb_id and $is_option[ 'orphans_only' ] ) {
										continue;
									}
									// if existing featured image
									if ( $current_thumb_id ) {
										// get thumbnail html if not yet got
										if ( ! isset( $current_featured_images[ $current_thumb_id ] ) ) {
											$current_featured_images[ $current_thumb_id ] = wp_get_attachment_image( $current_thumb_id, $size, false, $attr );
										}
									} else {
										$current_thumb_id = $false_id; // cast from '' or 'false' to a value to use as an array key
									}
									// set image randomly : future image = new image
									$future_thumb_id = $this->selected_multiple_image_ids[ rand( 0, $last_index ) ]; // get thumb id randomly
									// get thumbnail html if not yet got
									if ( ! isset( $future_featured_images[ $future_thumb_id ] ) ) {
										$future_featured_images[ $future_thumb_id ] = wp_get_attachment_image( $future_thumb_id, $size, false, $attr );
									}
									// store edit link, post title, post date, post author, current image html, future image html
									$post_link = get_edit_post_link();
									$post_title = get_the_title();
									$results[] = array( 
										$post_link, 
										$post_title,
										get_the_date(),
										get_the_author(),
										$current_featured_images[ $current_thumb_id ],
										$future_featured_images[ $future_thumb_id ],
										get_post_status(),
										get_post_type(),
									);
									// notice result for cache
									$query_results[ $post_id ] = array( $future_thumb_id, $post_link, $post_title );
								} // while(have_posts)
							/* else 
							 * 1. use selected images multiple times randomly and
							 * 2. do not overwrite existing featured images
							 */
							} else {
								while ( $the_query->have_posts() ) {
									$the_query->the_post();
									// get the post id once
									$post_id = get_the_ID();
									// check if there is an existing featured image
									$current_thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
									// if post with featured images should be ignored, jump to next loop
									if ( $current_thumb_id and $is_option[ 'orphans_only' ] ) {
										continue;
									}
									// if existing featured image
									if ( $current_thumb_id ) {
										// get thumbnail html if not yet got
										if ( ! isset( $current_featured_images[ $current_thumb_id ] ) ) {
											$current_featured_images[ $current_thumb_id ] = wp_get_attachment_image( $current_thumb_id, $size, false, $attr );
										}
										// do nothing : future image = current image
										$future_thumb_id = $current_thumb_id;
										$future_featured_images[ $future_thumb_id ] = $current_featured_images[ $current_thumb_id ];
									} else {
										$current_thumb_id = $false_id; // cast from '' or 'false' to a value to use as an array key
										// set image randomly : future image = new image
										$future_thumb_id = $this->selected_multiple_image_ids[ rand( 0, $last_index ) ]; // get thumb id randomly
										// get thumbnail html if not yet got
										if ( ! isset( $future_featured_images[ $future_thumb_id ] ) ) {
											$future_featured_images[ $future_thumb_id ] = wp_get_attachment_image( $future_thumb_id, $size, false, $attr );
										}
									}
									// store edit link, post title, post date, post author, current image html, future image html
									$post_link = get_edit_post_link();
									$post_title = get_the_title();
									$results[] = array( 
										$post_link, 
										$post_title,
										get_the_date(),
										get_the_author(),
										$current_featured_images[ $current_thumb_id ],
										$future_featured_images[ $future_thumb_id ],
										get_post_status(),
										get_post_type(),
									);
									// notice result for cache
									$query_results[ $post_id ] = array( $future_thumb_id, $post_link, $post_title );
								} // while(have_posts)
							} // if ( overwrite )
						} // if ( use_img_only_once )
						break;
					case 'replace':
						while ( $the_query->have_posts() ) {
							$the_query->the_post();
							// get the post id once
							$post_id = get_the_ID();
							// check if there is an existing featured image
							$current_thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
							if ( $current_thumb_id ) {
								// get thumbnail html if not yet got
								if ( ! isset( $current_featured_images[ $current_thumb_id ] ) ) {
									$current_featured_images[ $current_thumb_id ] = wp_get_attachment_image( $current_thumb_id, $size, false, $attr );
								}
							} else {
								$current_thumb_id = $false_id; // cast from '' or 'false' to a value to use as an array key
							}
							// get html of future thumbnail
							$future_thumb_id = $this->selected_image_id;
							// get thumbnail html if not yet got
							if ( ! isset( $future_featured_images[ $future_thumb_id ] ) ) {
								$future_featured_images[ $future_thumb_id ] = wp_get_attachment_image( $future_thumb_id, $size, false, $attr );
							}
							// store edit link, post title, post date, post author, current image html, future image html
							$post_link = get_edit_post_link();
							$post_title = get_the_title();
							$results[] = array( 
								$post_link, 
								$post_title,
								get_the_date(),
								get_the_author(),
								$current_featured_images[ $current_thumb_id ],
								$future_featured_images[ $future_thumb_id ],
								get_post_status(),
								get_post_type(),
							);
							// notice result for cache
							$query_results[ $post_id ] = array( $future_thumb_id, $post_link, $post_title );
						} // while(have_posts)
						break;
					case 'remove':
					case 'remove_any_img':
						$future_thumb_id = false;
						while ( $the_query->have_posts() ) {
							$the_query->the_post();
							// get the post id once
							$post_id = get_the_ID();
							// get html for featured image
							$current_thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
							if ( $current_thumb_id ) {
								// get thumbnail html if not yet got
								if ( ! isset( $current_featured_images[ $current_thumb_id ] ) ) {
									$current_featured_images[ $current_thumb_id ] = wp_get_attachment_image( $current_thumb_id, $size, false, $attr );
								}
							} else {
								$current_thumb_id = $false_id; // cast from '' or 'false' to a value to use as an array key
							}
							// store edit link, post title, post date, post author, current image html, future image html
							$post_link = get_edit_post_link();
							$post_title = get_the_title();
							$results[] = array( 
								$post_link, 
								$post_title,
								get_the_date(),
								get_the_author(),
								$current_featured_images[ $current_thumb_id ],
								$future_thumb_id,
								get_post_status(),
								get_post_type(),
							);
							// notice result for cache
							$query_results[ $post_id ] = array( $future_thumb_id, $post_link, $post_title );
						} // while(have_posts)
						break;
					case 'assign_first_img':
						// do the loop
						while ( $the_query->have_posts() ) {
							$the_query->the_post();
							// get the post id once
							$post_id = get_the_ID();
							// check if there is an existing featured image
							$current_thumb_id = $this->get_sanitized_post_thumbnail_id( $post_id );
							// if post with featured images should be ignored, jump to next loop
							if ( $current_thumb_id and $is_option[ 'orphans_only' ] ) {
								continue;
							}
							// get the post once (for $post->post_content)
							$post = get_post();
							// if existing featured image
							if ( $current_thumb_id ) {
								if ( ! isset( $current_featured_images[ $current_thumb_id ] ) ) {
									// get the html code for featured image once
									$current_featured_images[ $current_thumb_id ] = wp_get_attachment_image( $current_thumb_id, $size, false, $attr );
								}
								if ( $is_option[ 'overwrite' ] ) {
									$post_content = $post->post_content;
									// preview old thumb + new thumb
									$future_thumb_id = call_user_func( array( $this, $function ), $post_id, $post_content );
									if ( $future_thumb_id ) {
										// get thumbnail html if not yet got
										if ( ! isset( $future_featured_images[ $future_thumb_id ] ) ) {
											$future_featured_images[ $future_thumb_id ] = wp_get_attachment_image( $future_thumb_id, $size, false, $attr );
										}
									} else {
										// preview old thumb + old thumb
										$future_thumb_id = $current_thumb_id;
										$future_featured_images[ $future_thumb_id ] = $current_featured_images[ $current_thumb_id ];
									}
								} else {
									// preview old thumb + old thumb
									$future_thumb_id = $current_thumb_id;
									$future_featured_images[ $future_thumb_id ] = $current_featured_images[ $current_thumb_id ];
								} // if 'overwrite' option
							} else {
								$post_content = $post->post_content;
								// preview no old thumb + new thumb
								$current_thumb_id = $false_id; // cast from '' or 'false' to a value to use as an array key
								// try to get html of future thumbnail
								$future_thumb_id = call_user_func( array( $this, $function ), $post_id, $post_content );

								if ( $future_thumb_id ) {
									// get thumbnail html if not yet got
									if ( ! isset( $future_featured_images[ $future_thumb_id ] ) ) {
										$future_featured_images[ $future_thumb_id ] = wp_get_attachment_image( $future_thumb_id, $size, false, $attr );
									}
								} else {
									// preview no old thumb + no old thumb
									$future_thumb_id = $current_thumb_id;
									$future_featured_images[ $future_thumb_id ] = $current_featured_images[ $current_thumb_id ];
								} // if $future_thumb_id
							}
							// store edit link, post title, post date, post author, current image html, future image html
							$post_link = get_edit_post_link();
							$post_title = get_the_title();
							$results[] = array( 
								$post_link, 
								$post_title,
								get_the_date(),
								get_the_author(),
								$current_featured_images[ $current_thumb_id ],
								$future_featured_images[ $future_thumb_id ],
								get_post_status(),
								get_post_type(),
							);
							// notice result for cache
							$query_results[ $post_id ] = array( $future_thumb_id, $post_link, $post_title );
						} // while(have_posts)
						break;
				} // switch(selected_action)
			} // if( have_posts )
			// Restore original post data after the query
			wp_reset_postdata();
			// store results as transient for 1 day at the longest
			set_transient( $this->transient_name, $query_results, DAY_IN_SECONDS );
		} // if perform

		// return results
		return $results;
	}
	
	/**
	 * Check the arguments for WP_Query depended on users selection
	 *
	 * @access   private
	 * @since     1.0.0
	 *
	 * @return    array    the args
	 */
	private function get_query_args() {
		// define default params
		$args[ 'posts_per_page' ] =  -1; // do not use pagination, return whole result at once
		$args[ 'no_found_rows' ] = true; // since no pagination: tell WordPress not to run SQL_CALC_FOUND_ROWS on the SQL query; drastically speeding up the query
		$args[ 'orderby' ] = 'title';
		$args[ 'order' ] = 'ASC';
		$args[ 'ignore_sticky_posts' ] = true;
		$args[ 'post_type' ] = array_merge ( $this->selected_post_types, $this->selected_custom_post_types );
		switch ( $this->selected_action ) {
			case 'replace':
				$this->selected_post_ids = $this->get_post_ids_of_old_thumbnails();
				$args[ 'post__in' ] = $this->get_id_array_for_query( $this->selected_post_ids );
				break;
			case 'remove':
				$this->selected_post_ids = $this->get_post_ids_of_thumbnail();
				$args[ 'post__in' ] = $this->get_id_array_for_query( $this->selected_post_ids );
				break;
		} // switch(selected_action)

		if ( $this->selected_filters ) {
			// if search by mime types: ignore post types
			if ( in_array( 'filter_mime_types', $this->selected_filters ) ) {
				$this->selected_mime_types = $this->get_sanitized_mime_types();
				if ( $this->selected_mime_types ) {
					// add selected mime types to get a desired result
					$args[ 'post_mime_type' ] = $this->selected_mime_types;
				} else {
					// add fictitious mime type to get no result (and not to get a list of all media files)
					$args[ 'post_mime_type' ] = 'abcdefghi'; // assume there is not and will be never a mime type with this name
				}
				// override defaults
				$args[ 'post_type' ] = 'attachment';
				$args[ 'post_status' ] = 'inherit';
			} else {
				// set desired post types and statusses
				if ( in_array( 'filter_post_types', $this->selected_filters ) ) {
					$this->selected_post_types = $this->get_sanitized_post_types();
					$this->selected_custom_post_types = $this->get_sanitized_custom_post_types();
					if ( $this->selected_post_types or $this->selected_custom_post_types ) {
						// add selected post types to get a desired result
						$args[ 'post_type' ] = array_merge ( $this->selected_post_types, $this->selected_custom_post_types );
					} else {
						// add a fictitious post type to get no result (and not to get a list of all posts)
						$args[ 'post_type' ] = 'abcdefghi'; // assume there is not and will be never a post type with this name
					}
				}
				if ( in_array( 'filter_status', $this->selected_filters ) ) {
					$this->selected_statuses = $this->get_sanitized_post_statuses();
					// fictitious post status does not work because the names of post statussses are fixed, so just do:
					$args[ 'post_status' ] = $this->selected_statuses;
				}
			} // if(mime type filter)
			// add other user inputs
			foreach ( $this->selected_filters as $filter ) {
				switch ( $filter ) {
					case 'filter_post_formats':
						$this->selected_post_formats = $this->get_sanitized_post_formats();
						// if there is a selected post format
						if ( 0 < $this->selected_post_formats ) {
							// make selection understandable for WP
							$mapped_post_formats = array();
							foreach ( $this->selected_post_formats as $post_format ) {
								if ( 'standard' == $post_format ) {
									continue; // skip standard post format, there is no entry in the db for it								}
								}
								$mapped_post_formats[] = sprintf( 'post-format-%s', $post_format );
							}
							// assign selection to query if entries
							if ( $mapped_post_formats ) {
								$args[ 'tax_query' ][] = array(
									'taxonomy' => 'post_format',
									'field' => 'slug',
									'terms' => $mapped_post_formats
								);
							}
						}
						break;
					case 'filter_category':
						$this->selected_category_id = $this->get_sanitized_category_id();
						// if there is a selected category assign it to the query
						if ( 0 < $this->selected_category_id ) {
							$args[ 'cat' ] = $this->selected_category_id; // todo: user selects more than 1 category, 'category__in'
						}
						break;
					case 'filter_tag':
						$this->selected_tag_id = $this->get_sanitized_tag_id();
						// if there is a selected tag assign it to the query
						if ( 0 < $this->selected_tag_id ) {
							$args[ 'tag_id' ] = $this->selected_tag_id; // todo: user selects more than 1 tag, 'tag__in'
						}
						break;
					case 'filter_search':
						$this->selected_search_term = $this->get_search_term();
						// if there is a search term assign it to the query
						if ( '' != $this->selected_search_term ) {
							$args[ 's' ] = $this->selected_search_term;
							// Consider user search options at search filter
							if ( $this->selected_search_options ) {
								add_filter( 'posts_search', array( $this, 'get_modified_search_string'), 500, 2 );	
							}
						}
						break;
					case 'filter_author':
						$this->selected_author_id = $this->get_sanitized_author_id();
						// if there is a selected author assign him/her to the query
						if ( 0 < $this->selected_author_id ) {
							$args[ 'author' ] = $this->selected_author_id;
						}
						break;
					case 'filter_custom_field':
						$this->selected_custom_field = $this->get_sanitized_custom_field();
						$args[ 'meta_query' ] = array( $this->selected_custom_field );
						break;
					case 'filter_time':
						$this->selected_date_queries = $this->get_sanitized_date_queries();
						// format the input for the query
						$date_query = array();
						foreach ( $this->selected_date_queries as $key => $value ) {
							switch ( $key ) {
								case 'after':
								case 'before':
									// start and end dates
									$time_data = array();
									if ( isset( $this->selected_date_queries[ $key ] ) AND  "" != $this->selected_date_queries[ $key ] ) {
										$time_data[ $key ] = date( 'Y-m', strtotime( $this->selected_date_queries[ $key ] ) );
										if ( isset( $this->selected_date_queries[ 'inclusive' ] ) AND  "1" === $this->selected_date_queries[ 'inclusive' ] ) {
											if ( 'before' == $key ) {
												$time_data[ $key ] .= '+1 month'; // correction because of $default_to_max in WP_Date_Query
											}
											$time_data[ 'inclusive' ] = true;
										} else {
											if ( 'after' == $key ) {
												$time_data[ $key ] .= '+1 month'; // correction because of $default_to_max in WP_Date_Query
											}
											$time_data[ 'inclusive' ] = false;
										}
									}
									if ( ! empty( $time_data ) ) {
										$date_query[] = $time_data;
									}
									break;
							} // switch(selected_date_queries)
						} // foreach()
						$args[ 'date_query' ] = $date_query;
						break;
					case 'filter_image_size':
						$this->selected_image_dimensions = $this->get_sanitized_image_dimensions();
						$post_ids = $this->get_post_ids_of_to_small_thumbnails();
						// if there are post ids get the intersection with posts ids of to small images, else zero results
						if ( $post_ids && $this->selected_post_ids ) {
							$this->selected_post_ids = $this->get_array_intersect( $this->selected_post_ids, $post_ids );
						} elseif ( $post_ids ) {
							$this->selected_post_ids = $post_ids;
						} else {
							$this->selected_post_ids = array();
						}
						$args[ 'post__in' ] = $this->get_id_array_for_query( $this->selected_post_ids );
						break;
					case 'filter_custom_taxonomies':
						$this->selected_custom_taxonomies = $this->get_sanitized_custom_taxonomies();
						#$tax_query = array();
						// format the input for the query
						foreach ( $this->selected_custom_taxonomies as $sel_cus_tax => $id ) {
							if ( "" == $id ) continue; // next loop cycle if not selected
							$args[ 'tax_query' ][] = array(
								'field' => 'id',
								'taxonomy' => $sel_cus_tax,
								'terms' => $id
							);
						}
						// logical relationship between each inner taxonomy array is intersection when there is more than 1 array
						if ( isset( $args[ 'tax_query' ] ) and 1 < sizeof( $args[ 'tax_query' ] ) ) {
							$args[ 'tax_query' ][ 'relation' ] = 'AND';
						}
						break;
					case 'filter_parent_page':
						//$this->selected_parent_page_id = $this->get_sanitized_page_id();
						$this->selected_parent_page_ids = $this->get_sanitized_parent_page_ids();
						// if there is a selected parent page assign it to the query
						if ( $this->selected_parent_page_ids ) {
							foreach ( $this->selected_parent_page_ids as $post_type => $id ) {
								$sanitized_id = absint( $id );
								if ( $sanitized_id ) {
									$args[ 'post_parent__in' ][] = $id;
								}
							}
						}
						break;
				} // switch(filter)
			} // foreach(filter)
		} // if(filters)
		#$this->dambedei($args);
		return $args;
	}

	/**
	 *
	 * Render options of HTML selection lists with strings as values
	 *
	 * @access   private
	 * @since     1.0.0
	 */
	private function get_html_options_strings( $arr, $key, $options, $first_empty = true ) {
		$output = $first_empty ? $this->get_html_empty_option() : '';
		$is_key = isset( $arr[ $key ] );
		if ( $is_key ) { 
			foreach ( $options as $key => $label ) {
				$output .= sprintf( '<option value="%s" %s>%s</option>', $key, selected( $is_key , true, false ), $label );
			}
		} else {
			foreach ( $options as $key => $label ) {
				$output .= sprintf( '<option value="%s">%s</option>', $key, $label );
			}
		}
		return $output;
	}
	
	/**
	 *
	 * Return empty option for selection field
	 *
	 * @access   private
	 * @since    3.0
	 */
	private function get_html_empty_option() {
		$text = '&mdash; Select &mdash;';
		return sprintf( '<option value="">%s</option>', __( $text ) );
	}

	/**
	 * Returns the post ids which are assigned with the featured images which should be replaced
	 *
	 * @access   private
	 * @since     1.0.0
	 *
	 * @return    array    the post ids assigned with the thumbnails
	 */
	private function get_post_ids_of_old_thumbnails() {
		$this->selected_old_image_ids = $this->get_sanitized_array( 'replacement_image_ids', $this->get_featured_image_ids() );
		return $this->get_post_ids_of_featured_image_ids( $this->selected_old_image_ids );
	}

	/**
	 * Returns the post ids which are assigned with the featured image which should be removed
	 *
	 * @access   private
	 * @since     1.0.0
	 *
	 * @return    array    the post ids assigned with the thumbnail
	 */
	private function get_post_ids_of_thumbnail() {
		$post_ids = array();
		global $wpdb;
		// get a normal array all names of meta keys except the WP builtins meta keys beginning with an underscore '_'
		$results = $wpdb->get_results( $wpdb->prepare( "SELECT `post_id` FROM $wpdb->postmeta WHERE `meta_key` = '_thumbnail_id' AND `meta_value` = %d", $this->selected_image_id ), ARRAY_N );
		// flatten and sanitize results
		if ( $results ) {
			foreach ( $results as $r ) {
				$post_ids[] = absint( $r[ 0 ] );
			}
		}
		if ( empty( $post_ids ) ) {
			$post_ids[] = 0; // enter at least one element with no sense to yield 0 results with WP_QUERY()
		}
		return $post_ids;
	}
	
	/**
	 * Returns the posts ids which are assigned to given featured image ids
	 *
	 * @access   private
	 * @since     2.0
	 *
	 * @return    array    the post ids assigned to given featured images
	 */
	private function get_post_ids_of_featured_image_ids( $image_ids = array() ) {
		$post_ids = array();
		global $wpdb;
		// get a normal array with all IDs of posts assigned with the image ids
		foreach ( $image_ids as $id ) {
			$results = $wpdb->get_results( $wpdb->prepare( "SELECT `post_id` FROM $wpdb->postmeta WHERE `meta_key` = '_thumbnail_id' AND `meta_value` = %d", $id ), ARRAY_N );
			// flatten and sanitize results
			if ( $results ) {
				foreach ( $results as $r ) {
					$post_ids[] = absint( $r[ 0 ] );
				}
			}
		} // foreach()
		return $post_ids;
	}

	/**
	 * Returns the thumbnails ids which are assigned with a post
	 *
	 * @access   private
	 * @since     1.0.0
 	 *
	 * @return    array    the image ids assigned to posts as featured images
	 */
	private function get_featured_image_ids() {
		$image_ids = array();
		global $wpdb;
		// get a normal array all names of meta keys except the WP builtins meta keys beginning with an underscore '_'
		$results = $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT `meta_value` FROM $wpdb->postmeta WHERE `meta_key` LIKE '_thumbnail_id' AND `meta_value` != %d", $this->selected_image_id ), ARRAY_N );
		// flatten and sanitize results
		if ( $results ) {
			foreach ( $results as $r ) {
				$image_ids[] = absint( $r[ 0 ] );
			}
		}
		return $image_ids;
	}

	/**
	 * Check the step parameter and return safe values
	 *
	 * @access   private
	 * @since     1.0.0
	 *
	 * @return    string    the name of the step the plugin should take
	 */
	private function get_sanitized_step() {
		return $this->get_sanitized_value(
			'step',
			array_keys( $this->valid_steps ),
			'start'
		);
	}

	/**
	 * Check the action parameter and return safe values 
	 *
	 * @access   private
	 * @since     1.0.0
	 *
	 * @return    string    the name of the action the plugin should perform, else empty string
	 */
	private function get_sanitized_action() {
		return $this->get_sanitized_value(
			'action',
			array_keys( array_merge( $this->valid_actions, $this->valid_actions_without_image, $this->valid_actions_multiple_images ) )
		);
	}

	/**
	 * Check the requested statuses and return safe values 
	 *
	 * @access   private
	 * @since     1.0.0
	 *
	 * @return    array    the names of the statuses of posts and pages
	 */
	private function get_sanitized_post_statuses() {
		return $this->get_sanitized_array(
			'statuses',
			array_keys( $this->valid_statuses )
		);
	}

	/**
	 * Check the requested filters and return safe values 
	 *
	 * @access   private
	 * @since     1.0.0
	 *
	 * @return    array    the names of the filters
	 */
	private function get_sanitized_filter_names() {
		return $this->get_sanitized_array(
			'filters',
			array_keys( $this->valid_filters )
		);
	}

	/**
	 * Check the requested options and return safe values 
	 *
	 * @access   private
	 * @since     5.1
	 *
	 * @return    array    the names of the options
	 */
	private function get_sanitized_option_names() {
		return $this->get_sanitized_array(
			'options',
			array_keys( $this->valid_options )
		);
	}

	/**
	 * Check the requested post types and return safe values 
	 *
	 * @access   private
	 * @since     1.0.0
	 *
	 * @return    array    the names of the selected post types
	 */
	private function get_sanitized_post_types() {
		return $this->get_sanitized_array(
			'post_types',
			array_keys( $this->valid_post_types )
		);
	}

	/**
	 * Check the requested post formats and return safe values 
	 *
	 * @access   private
	 * @since     3.6.0
	 *
	 * @return    array    the names of the selected post formats
	 */
	private function get_sanitized_post_formats() {
		return $this->get_sanitized_array(
			'post_formats',
			array_keys( $this->valid_post_formats )
		);
	}

	/**
	 * Check the requested mime types and return safe values 
	 *
	 * @access   private
	 * @since     9.0
	 *
	 * @return    array    the names of the selected mime types
	 */
	private function get_sanitized_mime_types() {
		return $this->get_sanitized_array(
			'mime_types',
			array_keys( $this->valid_mime_types )
		);
	}

	/**
	 * Check the requested custom post types and return safe values 
	 *
	 * @access   private
	 * @since     1.0.0
	 *
	 * @return    array    the names of the selected custom post types
	 */
	private function get_sanitized_custom_post_types() {
		return $this->get_sanitized_array(
			'custom_post_types',
			$this->valid_custom_post_types
		);
	}

	/**
	 * Check the requested custom taxonomies and return safe values 
	 *
	 * @access   private
	 * @since    3.0
	 *
	 * @return    array    the names of the selected custom taxonomies
	 */
	private function get_sanitized_custom_taxonomies() {
		return $this->get_sanitized_associated_array(
			'custom_taxonomies',
			$this->valid_custom_taxonomies
		);
	}

	/**
	 * Check the requested time or date period and return safe values 
	 *
	 * @access   private
	 * @since     4.0
	 *
	 * @return    array    the names of the user selected date queries
	 */
	private function get_sanitized_date_queries() {
		return $this->get_sanitized_associated_array( 
			'date_queries', 
			$this->valid_date_queries 
		);
	}

	/**
	 * Check the requested custom field operation and return safe values 
	 *
	 * @access   private
	 * @since     1.0.0
	 *
	 * @return    array    the name of the user selected custom field, the operation and the comparison value
	 */
	private function get_sanitized_custom_field() {
		return $this->get_sanitized_associated_array(
			'custom_field',
			$this->valid_custom_field
		);
	}

	/**
	 * Check the requested custom field operation and return safe values 
	 *
	 * @access   private
	 * @since     2.0
	 *
	 * @return    array    the the user given dimensions of an image
	 */
	private function get_sanitized_image_dimensions() {
		$img_dims = $this->get_sanitized_associated_array(
			'image_dimensions',
			$this->valid_image_dimensions,
			$this->selected_image_dimensions
		);
		// cast to positive integers
		foreach ( array_keys( $this->valid_image_dimensions ) as $key ) {
			if ( isset( $img_dims[ $key ] ) ) {
				$img_dims[ $key ] = absint( $img_dims[ $key ]  ); // no 'else' block necessary because of default values setting
			}
		}
		// correct too high or too low values
		foreach ( $img_dims as $key => $value ) {
			if ( $img_dims[ $key ] > $this->max_image_length ) {
				$img_dims[ $key ] = $this->max_image_length;
			} elseif ( $img_dims[ $key ] < $this->min_image_length ) {
				$img_dims[ $key ] = $this->min_image_length;
			}
		}
		return $img_dims;
	}

	/**
	 * Check the requested search options and return safe values 
	 *
	 * @access   private
	 * @since    3.5.0 pro
	 *
	 * @return    array    the names of the user selected search options
	 */
	private function get_sanitized_search_options() {
		return $this->get_sanitized_associated_array( 
			'search_options', 
			$this->valid_search_options 
		);
	}

	/**
	 * Check the requested parent page to include and return safe values 
	 *
	 * @access   private
	 * @since    5.2 pro
	 *
	 * @return    array    the names of the user selected parent page options
	 */
	private function get_sanitized_parent_pages_included() {
		return $this->get_sanitized_array( 
			'parent_page_included', 
			array_merge( array_keys( $this->valid_post_types ), $this->valid_custom_post_types )
		);
	}
	
	/**
	 * Check the parameter defined by key and return safe value
	 * Written to return a single value, e.g. for radio buttons
	 *
	 * @access   private
	 * @since     1.0.0
	 *
	 * @return    mixed    the user selected valid value or the default value
	 */
	private function get_sanitized_value( $key, $valid_values, $default_value = null ) {
		$value = isset( $_REQUEST[ $key ] ) ? $_REQUEST[ $key ] : $default_value;
		if ( in_array( $value, $valid_values ) ) {
			return $value;            
		} else {                       
			return $default_value;          
		}                             
	}

	/**
	 * Check the parameter and return safe values 
	 * Written to return multiple values, e.g. for checkboxes
	 *
	 * @access   private
	 * @since     1.0.0
	 *
	 * @return    array    the user selected valid values or the default values
	 */
	private function get_sanitized_array( $key, $valid_array, $default_array = array() ) {
		if ( isset( $_POST[ $key ] ) and is_array( $_POST[ $key ] ) ) {
			return $this->get_array_intersect( $_POST[ $key ], $valid_array );
		} else {
			return $default_array;
		}
	}

	/**
	 * Check the parameters and return safe values 
	 * Written to return multiple values associated with key names, e.g. for WP Query
	 * The function filters out empty strings
	 *
	 * @access   private
	 * @since     1.0.0
	 *
	 * @return    array    the user selected valid values or the default values
	 */
	private function get_sanitized_associated_array( $key, $valid_array, $default_array = array() ) {
		$queries = array();
		$arr = isset( $_POST[ $key ] ) ? $_POST[ $key ] : $default_array;
		if ( ! empty( $arr ) && is_array( $arr ) ) {
			foreach ( array_keys( $valid_array ) as $key ) {
				if ( array_key_exists( $key, $arr ) and isset( $arr[ $key ] ) ) {
					$queries[ $key ] = $arr[ $key ];
				}
			}
		}
		return $queries;
	}

	/**
	 * Define parameters and return registered custom post types
	 *
	 * @access   private
	 * @since     1.0.0
	 *
	 * @return    array    the names of the registered and thumbnail supporting custom post types
	 */
	private function get_registered_custom_post_types() {
		$args = array(
			   '_builtin' => false # only custom post types
		);
        $thumbnail_supporting_custom_post_types = array();
		// get the registered custom post types as objects
        $cpts = get_post_types( $args, 'names' );
		// consider only thumbnail supporting types
        foreach ( $cpts as $name => $cpt ) {
            if ( post_type_supports( $name, 'thumbnail' ) ) {
                $thumbnail_supporting_custom_post_types[] = $name;
            }
        }
		// return the result
		return $thumbnail_supporting_custom_post_types;
	}

	/**
	 * Define parameters and return thumbnail supporting custom post types
	 *
	 * @access   private
	 * @since     3.1
	 *
	 * @return    array    the names and labels of the registered and thumbnail supporting custom post types
	 */
	private function get_custom_post_types_labels() {
		$args = array(
			   '_builtin' => false # only custom post types
		);
        $name_labels = array();
		// get the registered custom post types as objects
        $objects = get_post_types( $args, 'objects' );
		// store their names and labels
        foreach ( $objects as $name => $object ) {
            if ( post_type_supports( $name, 'thumbnail' ) ) {
                $name_labels[ $name ] = $object->label;
            }
        }
		// return the result
		return $name_labels;
	}

	/**
	 * Return registered custom taxonomies
	 *
	 * @access   private
	 * @since    3.0
	 *
	 * @return    array    the names of the registered custom taxonomies
	 */
	private function get_registered_custom_taxonomies() {
		$args = array(
			   '_builtin' => false # only custon post types
		);
		return get_taxonomies( $args, 'names' );
	}

	/**
	 * Return registered custom taxonomies with their labels
	 *
	 * @access   private
	 * @since    3.0
	 *
	 * @return    array    the names of the registered custom taxonomies
	 */
	private function get_custom_taxonomies_labels() {
		$args = array(
			   '_builtin' => false # only custon post types
		);
        $name_labels = array();
		// get the registered custom post types as objects
        $objects = get_taxonomies( $args, 'objects' );
		// store their names and labels
        foreach ( $objects as $name => $object ) {
            $name_labels[ $name ] = $object->label;
        }
		// return the result
		return $name_labels;
	}

	/**
	 * Return the intersection of two given arrays
	 * Runs 5 times faster than PHP's array_intersect()
	 *
	 * @access   private
	 * @since     1.0.0
	 *
	 * @return    array    the intersection of two arrays
	 */
	private function get_array_intersect( $a, $b ) { 
		$m = array(); 
		$intersection = array(); 
		// copy first array to array
		$len = sizeof( $a );
		for( $i = 0; $i < $len; $i++ ) { 
			$m[] = $a[ $i ]; 
		} 
		// append second array to array
		$len = sizeof( $b );
		for( $i = 0; $i < $len; $i++ ) { 
			$m[] = $b[ $i ]; 
		} 
		// make values sorted
		sort( $m ); 
		// compare value with the next one and append to intersection array if equal
		$len = sizeof( $m ) - 1;
		for( $i = 0; $i < $len; $i++ ) { 
			if ( $m[ $i ] == $m[ $i + 1 ] ) $intersection[] = $m[ $i ]; 
		} 
		// return intersection
		return $intersection; 
	}
	
	/**
	 * Check the integer value of a user selected value else default value
	 *
	 * @access   private
	 * @since     1.0.0
	 *
	 * @return    integer    the id or 0
	 */
	private function get_sanitized_id( $key, $default = 0 ) {
		if ( empty( $_REQUEST[ $key ] ) ) {
			return $default;
		}
		$given_id = absint( sanitize_text_field( $_REQUEST[ $key ] ) );
		if ( 0 < $given_id ) {
			return $given_id;
		} else {
			return $default;
		}
	}
	
	/**
	 * Check the id of selected featured image and return safe value
	 *
	 * @access   private
	 * @since     1.0.0
	 *
	 * @return    integer    the id or 0
	 */
	private function get_sanitized_image_id() {
		return $this->get_sanitized_id( 'image_id' );
	}
	
	/**
	 * Check the ids of selected featured images and return safe value
	 *
	 * @access   private
	 * @since     6.0
	 *
	 * @return    array    the ids or empty
	 */
	private function get_sanitized_multiple_image_ids() {
		if ( empty( $_POST[ 'multiple_image_ids' ] ) ) {
			return array();
		} else {
			// read: sanatize string, make array out of string, convert each array value to integer, return result array
			return array_map( 'absint', explode( ',', sanitize_text_field( $_POST[ 'multiple_image_ids' ] ) ) );
		}
	}
	
	/**
	 * Check the id of selected tag and return safe value
	 *
	 * @access   private
	 * @since     1.0.0
	 *
	 * @return    integer    the id or 0
	 */
	private function get_sanitized_tag_id() {
		return $this->get_sanitized_id( 'tag_id' );
	}
	
	/**
	 * Check the id of selected category and return safe value
	 *
	 * @access   private
	 * @since     1.0.0
	 *
	 * @return    integer    the id or 0
	 */
	private function get_sanitized_category_id() {
		return $this->get_sanitized_id( 'category_id' );
	}

	/**
	 * Get the ID of a post's featured image, else 0
	 *
	 * @access   private
	 * @since     3.2
	 *
	 * @return    integer    the id or 0
	 */
	private function get_sanitized_post_thumbnail_id( $post_id ) {
		// check if an image with the given ID exists in the media library, else set id to 0
		$current_thumb_id = (int) get_post_thumbnail_id( $post_id );
		if ( $current_thumb_id and wp_attachment_is_image( $current_thumb_id ) ) {
			return $current_thumb_id;
		} else {
			return 0;
		}
	}

	/**
	 * If results in array, return them, else say query something like "no results in array"
	 *
	 * @access   private
	 * @since     2.0
	 *
	 * @return    array    Array with content or 0
	 */
	private function get_id_array_for_query( $arr ) {
		if ( empty( $arr ) ) {
			return array( 0 );
		} else {
			return $arr;
		}
	}
	
	/**
	 * Returns the post ids of pages which have child pages
	 *
	 * @access   private
	 * @since     1.0.0 pro
	 *
	 * @return    array    the post ids of parent pages
	 */
	private function get_post_ids_of_parent_pages( $post_type ) {
		$post_ids = array();
		global $wpdb;
		// get a normal array all names of meta keys except the WP builtins meta keys beginning with an underscore '_'
		$results = $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT `post_parent` FROM $wpdb->posts WHERE `post_parent` != 0 AND `post_type` = %s", $post_type ), ARRAY_N );
		// flatten and sanitize results
		if ( $results ) {
			foreach ( $results as $r ) {
				$post_ids[] = absint( $r[ 0 ] );
			}
		}
		return $post_ids;
	}
	
	/**
	 * Check the approach parameter and return safe values 
	 *
	 * @access   private
	 * @since     2.0.0 pro
	 *
	 * @return    string    the name of the approach the plugin should perform, else empty string
	 */
	private function get_sanitized_approach() {
		return $this->get_sanitized_value(
			'approach',
			array_keys( $this->valid_approaches_first_image )
		);
	}

	/**
	 * Return registered post dates
	 *
	 * @access   private
	 * @since     1.0.0 pro
	 *
	 * @return    array    the values of the registered post dates
	 */
	private function get_registered_post_dates() {
		global $wpdb;
		$post_types = implode( "', '", array_merge( array_keys( $this->valid_post_types ), $this->valid_custom_post_types ) );
		$post_statuses = implode( "', '" , array_keys( $this->valid_statuses ) );
		$mime_types = array_keys( $this->valid_mime_types );
		$mime_where_clause = '';
		foreach ( $mime_types as $type ) {
			$mime_where_clause .= sprintf( " OR post_mime_type LIKE '%s%%'", $type );
		}
		// build query
		$query = "SELECT DISTINCT YEAR( post_date ) AS year, MONTH( post_date ) AS month FROM $wpdb->posts WHERE ( post_type IN ( '" . $post_types . "' ) AND post_status IN ( '" . $post_statuses . "' ) ) " . $mime_where_clause . " ORDER BY post_date DESC";
		// return result
		return $wpdb->get_results( $query );
	}

	/**
	 * Check the id of selected parent page and return safe value
	 *
	 * @access   private
	 * @since     5.2 pro
	 *
	 * @return    integer    the id or 0
	 */
	private function get_sanitized_parent_page_id( $key, $default = 0 ) {
		if ( empty( $_REQUEST[ 'parent_page_id' ][ $key ] ) ) {
			return $default;
		}
		$given_id = absint( $_REQUEST[ 'parent_page_id' ][ $key ] );
		if ( 0 < $given_id ) {
			return $given_id;
		} else {
			return $default;
		}
	}
	
	/**
	 * Check the id of selected parent page and return safe value
	 *
	 * @access   private
	 * @since     5.2 pro
	 *
	 * @return    integer    the id or 0
	 */
	private function get_sanitized_parent_page_ids() {
		return $this->get_sanitized_associated_array(
			'parent_page_id',
			array_merge( $this->valid_post_types, $this->get_custom_post_types_labels() )
		);
	}
	
	/**
	 * Check the id of selected author and return safe value
	 *
	 * @access   private
	 * @since     1.0.0 pro
	 *
	 * @return    integer    the id or 0
	 */
	private function get_sanitized_author_id() {
		return $this->get_sanitized_id( 'author_id' );
	}
	
	/**
	 * Check the user selected search term and return safe value
	 *
	 * @access   private
	 * @since     1.0.0 pro
	 *
	 * @return    string    the search term
	 */
	private function get_search_term() {
		if ( empty( $_POST[ 'search_term' ] ) ) {
			return '';
		} else {
			return sanitize_text_field( $_POST[ 'search_term' ] );
		}
	}
	
	/**
	 * Alter the query string to user search option
	 *
	 * @since     3.5.0 pro
	 *
	 */
	public function get_modified_search_string( $search, &$wp_query ) {
		// quit if no search term in query
		if ( empty( $search ) ) {
			return $search;
		}
		// else go on
		global $wpdb;
		// if search only in post titles
		if ( isset( $this->selected_search_options[ 'title_only' ] ) ) {
			$q = $wp_query->query_vars;
			$n = ! empty( $q[ 'exact' ] ) ? '' : '%';
			$search = $searchand = '';
			foreach ( (array) $q[ 'search_terms' ] as $term ) {
				$term = esc_sql( $wpdb->esc_like( $term ) );
				$search .= "{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
				$searchand = ' AND ';
			}
			if ( ! empty( $search ) ) {
				$search = " AND ({$search}) ";
				if ( ! is_user_logged_in() )
					$search .= " AND ($wpdb->posts.post_password = '') ";
			}
		}
		return $search;
	}
	
	/**
	 * Look in the DB for custom field names and return them
	 *
	 * @access   private
	 * @since     1.0.0 pro
	 *
	 * @return    array    the custom field names
	 */
	private function get_custom_field_keys() {
		global $wpdb;
		$key = 'meta_key';
		$custom_fields = array();
		// get a normal array all names of meta keys except the WP builtins meta keys beginning with an underscore '_'
		$results = $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT %s FROM $wpdb->postmeta WHERE %s NOT REGEXP '^_' ORDER BY %s", $key, $key, $key ), ARRAY_N );
		// flatten and sanitize results
		if ( $results ) {
			foreach ( $results as $r ) {
				$custom_fields[] = $r[ 0 ];
			}
		}
		// return array
		return $custom_fields;
	}
	
	/**
	 *
	 * Render options of HTML selection lists with dates
	 *
	 * @access   private
	 * @since     1.0.0 pro
	 */
	private function get_html_date_options( $key, $first_empty = true ) {
		global $wp_locale;
		$output = $first_empty ? $this->get_html_empty_option() : '';

		if ( count( $this->valid_post_dates ) ) {
			if ( isset( $this->selected_date_queries[ $key ] ) ) { 
				foreach ( $this->valid_post_dates as $date ) {
					if ( 0 == $date->year ) {
						continue;
					}
					$month = zeroise( $date->month, 2 );
					$option_value = sprintf( '%s-%s', $date->year, $month );
					$output .= sprintf( '<option value="%s" %s>%s %s</option>', $option_value, selected( $option_value == $this->selected_date_queries[ $key ], true, false ), $date->year, $wp_locale->get_month( $month ) );
				}
			} else {
				foreach ( $this->valid_post_dates as $date ) {
					if ( 0 == $date->year ) {
						continue;
					}
					$month = zeroise( $date->month, 2 );
					$output .= sprintf( '<option value="%s-%s">%s %s</option>', $date->year, $month, $date->year, $wp_locale->get_month( $month ) );
				}
			}
		}
		return $output;
	}

	/**
	 * Inserts given image into the media library, attachs it to given post and returns image ID
	 *
	 * @access   private
	 * @since     1.0.0 pro
	 *
	 * @return    object|integer    WP Error|the ID of inserted image
	 */
	private function get_inserted_image_id( $source_file, $parent_post_id = 0, $img_data = array(), $keep_file = false )  {

		// check for valid file path
		if ( ! @file_exists( $source_file ) ) {
			#return new WP_Error( 'nosourcefile', __( 'Image could not inserted because there is no source file.', 'quick-featured-images-pro' ) );
			return 0;
		}

		// check for WP upload directory
		$uploads_dir = wp_upload_dir();

		if ( $uploads_dir[ 'error' ] ) {
			// do error handling, quit
			#return new WP_Error( 'updirserror', sprintf( '%s %s', __( 'Image could not inserted. Reason:', 'quick-featured-images-pro' ), $uploads_dir[ 'error' ] ) );
			return 0;
		}
		
		// get filename only
		$file_basename = basename( $source_file );

		$file_type = wp_check_filetype( $file_basename );
		
		// if image filename has no extension create new filename with extension
		if ( false === $file_type[ 'ext' ] ) {
			$ext = $this->get_image_filename_extension( $source_file );
			// delete '-[password].tmp' extension from filename if available
			$file_basename = preg_replace( '/(-[a-zA-Z0-9]+)?\.tmp$/', '', $file_basename );
			// append new extension to filename
			$target_filename = $file_basename . '.' . $ext;
		} else {
			$target_filename = $file_basename;
		}
		// look for identical image file in the media library, quit if equal in name and size
		global $wpdb;
		$thumb_id = $wpdb->get_var( $wpdb->prepare( "SELECT `ID` FROM $wpdb->posts WHERE `guid` LIKE '%%%s'", '/' . $target_filename ) );
		if ( $thumb_id ) {
			$thumb_meta = wp_get_attachment_metadata( $thumb_id );
			$thumb_file = join( DIRECTORY_SEPARATOR, array( $uploads_dir[ 'basedir' ], $thumb_meta[ 'file' ] ) );
			if ( filesize( $source_file ) == filesize( $thumb_file ) ) {
				// delete source file if desired
				if ( ! $keep_file ) {
					@unlink( $source_file );
				}
				// finally quit
				return (int) $thumb_id;
			}
		}
		
		// image file not yet uploaded, so go on

		// because media_handle_sideload() will delete the source file use a copy instead if desired
		// The copy is saved in the directory of this file
		if ( $keep_file ) {
			$copy_file = join( DIRECTORY_SEPARATOR, array( dirname( __FILE__ ), $target_filename ) );	
			
			// try to copy, go on if successful
			if ( ! @copy( $source_file, $copy_file ) ) {
				#return new WP_Error( 'nosourcefile', __( 'Image could not inserted because copying the source file failed.', 'quick-featured-images-pro' ) );
				return 0;
			}
			// copied file is the new source
			$source_file = $copy_file;
		} // if keep_file

		// array based on $_FILE as seen in PHP file uploads
		$file_data = array(
			'name' => $target_filename,
			'type' => $file_type[ 'type' ],
			'tmp_name' => $source_file,
			'error' => 0,
			'size' => filesize( $source_file ),
		);
		
		// add image data if given
		$post_values = array();
		$defaults = array (
			'alt' => '',
			'desc' => '',
		);
		$img_alt = $target_filename;
		if ( ! empty( $img_data ) ) {
			
			// merge defaults with given values
			$img_data = wp_parse_args( $img_data, $defaults );

			// image alt text
			if ( $img_data[ 'alt' ] ) {
				$img_alt = sanitize_text_field( $img_data[ 'alt' ] );
			}

			// image description
			if ( $img_data[ 'desc' ] ) {
				$post_values[ 'post_content' ] = sanitize_text_field( $img_data[ 'desc' ] );
			}
		}
		
		// insert image in media library and return its id, else return error
		$thumb_id = media_handle_sideload( $file_data, $parent_post_id, $img_alt, $post_values );
		
		if ( is_wp_error( $thumb_id ) ) {
			#return $thumb_id;
			return 0;
		} // if is_wp_error()

		// add image alt text
		add_post_meta( $thumb_id, '_wp_attachment_image_alt', $img_alt );
		
		// return image id
		return $thumb_id;

	}
	
	/**
	 * Returns the absolute path of the preview image of a NextGen Gallery gallery
	 *
	 * @access   private
	 * @since     1.0.0 pro
	 *
	 * @return    string    the path of the image, else empty string
	 */
	private function get_ngg2_thumb_id ( $post_id, $content ) {
		// quit if NextGen Gallery plugin version 2+ is not available
		if ( ! $this->is_ngg2 ) return 0;

		// has post content a Nextgen shortcode?
		if ( preg_match_all( '/'. $this->ngg_shortcode_pattern .'/s', $content, $matches ) ) {

			// look for NextGen gallery shortcode and name; if not there: quit with 0
			if ( ! array_key_exists( 2, $matches ) ) return 0;
			if ( ! array_key_exists( 3, $matches ) ) return 0;

			// take the first nggallery shortcode in the post content
			$shortcode_name = $matches[ 2 ][ 0 ];
			$shortcode_atts = $matches[ 3 ][ 0 ];
			
			// set default args
			$ngg_args = null;
			$defaults = array(
				'id' => null,
				'source' => '',
				'container_ids' => array(),
				'gallery_ids' => array(),
				'album_ids' => array(),
				'tag_ids' => array(),
				'display_type' => '',
				'exclusions' => array(),
				'order_by' => $this->ngg_settings->galSort,
				'order_direction' => $this->ngg_settings->galSortOrder,
				'image_ids' => array(),
				'entity_ids' => array(),
				'tagcloud' => false,
				'inner_content' => null,
				'returns' => 'included',
				'slug' => null
			);
			
			// get available attributes as key/value pairs
			$params = shortcode_parse_atts( $shortcode_atts );
			
			// some conversions for compatibility with Nextgen < 2.0
			if ( 'ngg_images' != $shortcode_name ) {
				$params = $this->ngg_recast_shortcode_params( $shortcode_name, $params );
			}
			
			// merge user args with default args
			$ngg_args = shortcode_atts( $defaults, $params );

			// if NextGen gallery: parse the arguments and initiate a gallery object
			// Are we loading a specific displayed gallery that's persisted?
			$ngg_displayed_gallery = null;
			if ( ! is_null( $ngg_args[ 'id' ] ) ) {
				$ngg_displayed_gallery = $this->ngg_display_mapper->find( $ngg_args[ 'id' ] );
			} else {
				// perform some conversions...
				// galleries?
				if ( $ngg_args[ 'gallery_ids' ] ) {
					if ( $ngg_args[ 'source' ] != 'albums' and $ngg_args[ 'source' ] != 'album' ) {
						$ngg_args[ 'source' ] = 'galleries';
						$ngg_args[ 'container_ids' ] = $ngg_args[ 'gallery_ids' ];
						if ( $ngg_args[ 'image_ids' ] ) {
							$ngg_args[ 'entity_ids' ] = $ngg_args[ 'image_ids' ];
						}
					} elseif ( $ngg_args[ 'source' ] == 'albums' ) {
						$ngg_args[ 'entity_ids' ] = $ngg_args[ 'gallery_ids' ];
					}
					unset( $ngg_args[ 'gallery_ids' ] );
				// albums?
				} elseif ( $ngg_args[ 'album_ids' ] || $ngg_args[ 'album_ids' ] === '0' ) {
					$ngg_args[ 'source' ] = 'albums';
					$ngg_args[ 'container_ids' ] = $ngg_args[ 'album_ids' ];
					unset( $ngg_args[ 'albums_ids' ] );
				// galleries based on tags?
				} elseif ( $ngg_args[ 'tag_ids' ] ) {
					$ngg_args[ 'source' ] = 'tags';
					$ngg_args[ 'container_ids' ] = $ngg_args[ 'tag_ids' ];
					unset( $ngg_args[ 'tag_ids' ] );
				// single images?
				} elseif ( $ngg_args[ 'image_ids' ] ) {
					$ngg_args[ 'source' ] = 'galleries';
					$ngg_args[ 'entity_ids' ] = $ngg_args[ 'image_ids' ];
					unset( $ngg_args[ 'image_ids' ] );
				// tag clouds?
				} elseif ( $ngg_args[ 'tagcloud' ] ) {
					$ngg_args[ 'source' ] = 'tags';
				} // if gallery_ids
				
				// Convert strings to arrays
				if ( ! is_array( $ngg_args[ 'container_ids' ] ) ) {
					$ngg_args[ 'container_ids' ] = preg_split( '/,|\\|/', $ngg_args[ 'container_ids' ] );
				}
				if ( ! is_array( $ngg_args[ 'exclusions' ] ) ) {
					$ngg_args[ 'exclusions' ] = preg_split( '/,|\\|/', $ngg_args[ 'exclusions' ] );
				}
				if ( ! is_array( $ngg_args[ 'entity_ids' ] ) ) {
					$ngg_args[ 'entity_ids' ] = preg_split( '/,|\\|/', $ngg_args[ 'entity_ids' ] );
				}
				// Get the display settings
				foreach ( array_keys( $defaults ) as $key ) {
					unset( $params[ $key ] );
				}
				$ngg_args[ 'display_settings' ] = $params;
				
				// Create the displayed gallery
				$ngg_displayed_gallery = $this->ngg_factory->create( 'displayed_gallery', $ngg_args, $this->ngg_display_mapper );
			} // if not null(id)
		} elseif ( preg_match_all( "#<img.*http(s)?://(.*)?" . NGG_ATTACH_TO_POST_SLUG . "(=|/)preview(/|&|&amp;)id(=|--)(\\d+)[^>]*>#mi", $content, $matches, PREG_SET_ORDER ) ) {
			// catch the first found gallery ID and initiate gallery object
			$ngg_displayed_gallery = $this->ngg_display_mapper->find( absint( $matches[ 0 ][ 6 ] ), true );
		} else {
			// quit if no result
			return 0;
		}
		
		// validate the result and gallery object
		if ( ! $ngg_displayed_gallery ) return 0; 
		if ( ! $ngg_displayed_gallery->validate() ) return 0; 
		
		// get the first entity to get the preview image
		$entity = array_pop( $ngg_displayed_gallery->get_included_entities( 1 ) );

		if ( ! $entity ) return 0; 

		// is album or gallery?
		if ( ! ( empty( $entity->is_album ) and empty( $entity->is_gallery ) ) ) {
			if ( 0 == $entity->previewpic ) return 0; // quit if album has no preview image
			$previewpic = $this->ngg_image_mapper->find( absint( $entity->previewpic ) );
		// is image?
		} elseif ( ! empty( $entity->pid ) ) {
			$previewpic = $entity;
		// invalid entity
		} else {
			return 0;
		}

		if ( ! $previewpic ) return 0; // quit if image object not found
		
		// get gallery directory
		$gallery_path = $this->ngg_gallery_mapper->find( absint( $previewpic->galleryid ) )->path;
		
		// build full image file path
		$path = ABSPATH . $gallery_path . DIRECTORY_SEPARATOR . $previewpic->filename;
		
		// upload image into the media library and return its id
		return $this->get_inserted_image_id( $path, $post_id, array( 'alt' => $previewpic->alttext, 'desc' => $previewpic->description ), true );

	}

	/**
	 * Returns the converted shortcode parameters from older Nextgen version for Nextgen 2.0+
	 *
	 * @access   private
	 * @since     2.0.0 pro
	 *
	 * @return    array    the parameters for Nextgen 2.0+
	 */
	private function ngg_recast_shortcode_params ( $shortcode_name, $params ) {
		switch ( $shortcode_name ) {
			case 'nggallery':
				$params[ 'gallery_ids' ]     = ( isset( $params[ 'id' ] ) ) ? $params[ 'id' ] : null;
				$params[ 'display_type' ]    = ( isset( $params[ 'display_type' ] ) ) ? $params[ 'display_type' ] : NGG_BASIC_THUMBNAILS;
				$params[ 'images_per_page' ] = ( isset( $params[ 'images' ] ) ) ? $params[ 'images' ] : null;
				unset( $params[ 'id' ], $params[ 'images' ] );
				break;
			case 'imagebrowser':
			case 'nggimagebrowser':
				$params[ 'gallery_ids' ]  = ( isset( $params[ 'id' ] ) ) ? $params[ 'id' ] : null;
				$params[ 'source' ]       = ( isset( $params[ 'source' ] ) ) ? $params[ 'source' ] : 'galleries';
				$params[ 'display_type' ] = ( isset( $params[ 'display_type' ] ) ) ? $params[ 'display_type' ] : NGG_BASIC_IMAGEBROWSER;
				unset( $params[ 'id' ] );
				break;
			case 'random':
			case 'nggrandom':
				$params[ 'source' ]             = ( isset( $params[ 'source' ] ) ) ? $params[ 'source' ] : 'random';
				$params[ 'images_per_page' ]    = ( isset( $params[ 'max' ] ) ) ? $params[ 'max' ] : null;
				$params[ 'disable_pagination' ] = ( isset( $params[ 'disable_pagination' ] ) ) ? $params[ 'disable_pagination' ] : true;
				$params[ 'display_type' ]       = ( isset( $params[ 'display_type' ] ) ) ? $params[ 'display_type' ] : NGG_BASIC_THUMBNAILS;
				// inside if because Mixin_Displayed_Gallery_Instance_Methods->get_entities() doesn't handle null container_ids, so correctly
				if ( isset( $params[ 'id' ] ) ) $params[ 'container_ids' ] = ( isset( $params[ 'id' ] ) ) ? $params[ 'id' ] : null;
				unset( $params[ 'max' ], $params[ 'id' ] );
				break;
			case 'recent':
			case 'nggrecent':
				$params[ 'source' ]             = ( isset( $params[ 'source' ] ) ) ? $params[ 'source' ] : 'recent';
				$params[ 'images_per_page' ]    = ( isset( $params[ 'max' ] ) ) ? $params[ 'max' ] : null;
				$params[ 'disable_pagination' ] = ( isset( $params[ 'disable_pagination' ] ) ) ? $params[ 'disable_pagination' ] : true;
				$params[ 'display_type' ]       = ( isset( $params[ 'display_type' ] ) ) ? $params[ 'display_type' ] : NGG_BASIC_THUMBNAILS;
				if ( isset( $params[ 'id' ] ) ) $params[ 'container_ids' ] = ( isset( $params[ 'id' ] ) ) ? $params[ 'id' ] : null;
				unset( $params[ 'max' ], $params[ 'id' ] );
				break;
			case 'thumb':
			case 'nggthumb':
				$params[ 'entity_ids' ]   = ( isset( $params[ 'id' ] ) ) ? $params[ 'id' ] : null;
				$params[ 'source' ]       = ( isset( $params[ 'source' ] ) ) ? $params[ 'source' ] : 'galleries';
				$params[ 'display_type' ] = ( isset( $params[ 'display_type' ] ) ) ? $params[ 'display_type' ] : NGG_BASIC_THUMBNAILS;
				unset( $params[ 'id' ] );
				break;
			case 'slideshow':
			case 'nggslideshow':
				$params[ 'gallery_ids' ]    = ( isset( $params[ 'id' ] ) ) ? $params[ 'id' ] : null;
				$params[ 'display_type' ]   = ( isset( $params[ 'display_type' ] ) ) ? $params[ 'display_type' ] : NGG_BASIC_SLIDESHOW;
				$params[ 'gallery_width' ]  = ( isset( $params[ 'w' ] ) ) ? $params[ 'w' ] : null;
				$params[ 'gallery_height' ] = ( isset( $params[ 'h' ] ) ) ? $params[ 'h' ] : null;
				unset( $params[ 'id' ], $params[ 'w' ], $params[ 'h' ] );
				break;
			case 'nggtags':
				if ( isset( $params[ 'gallery' ] ) ) {		$params[ 'tag_ids' ] =  $params[ 'gallery' ];
				} elseif ( isset( $params[ 'album' ] ) ) {	$params[ 'tag_ids' ] =  $params[ 'album' ];
				} else { 									$params[ 'tag_ids' ] = array(); }
				$params[ 'source' ]       = ( isset( $params[ 'source' ] ) ) ? $params[ 'source' ] : 'tags';
				$params[ 'display_type' ] = ( isset( $params[ 'display_type' ] ) ) ? $params[ 'display_type' ] : NGG_BASIC_THUMBNAILS;
				unset( $params[ 'gallery' ], $params[ 'album' ] );
				break;
			case 'tagcloud':
			case 'nggtagcloud':
				$params[ 'tagcloud' ]     = ( isset( $params[ 'tagcloud' ] ) ) ? $params[ 'tagcloud' ] : 'yes';
				$params[ 'source' ]       = ( isset( $params[ 'source' ] ) ) ? $params[ 'source' ] : 'tags';
				$params[ 'display_type' ] = ( isset( $params[ 'display_type' ] ) ) ? $params[ 'display_type' ] : NGG_BASIC_TAGCLOUD;
				break;
			case 'album':
			case 'nggalbum':
				$params[ 'source' ]           = ( isset( $params[ 'source' ] ) ) ? $params[ 'source' ] : 'albums';
				$params[ 'container_ids' ]    = ( isset( $params[ 'id' ] ) ) ? $params[ 'id' ] : null;
				$params[ 'display_type' ]     = ( isset( $params[ 'display_type' ] ) ) ? $params[ 'display_type' ] : NGG_BASIC_COMPACT_ALBUM;
				unset( $params[ 'id' ] );
				break;
			case 'singlepic':
			case 'nggsinglepic':
				$params[ 'display_type' ] = ( isset( $params[ 'display_type' ] ) ) ? $params[ 'display_type' ] : NGG_BASIC_SINGLEPIC;
				$params[ 'image_ids' ] = ( isset( $params[ 'id' ] ) ) ? $params[ 'id' ] : null;
				unset( $params[ 'id' ] );
				break;
		}
		 return $params;
	 }

	 /**
	 * Returns the id of the first image in the content, else 0
	 *
	 * @access   private
	 * @since     1.0.0 pro
	 *
	 * @return    integer    the post id of the image
	 */
	private function get_first_content_image_id ( $post_id, $content ) {
		// set variables
		global $wpdb;
		$timeout_seconds = 60;
		// look for images in HTML code
		preg_match_all( '/<[iI][mM][gG][^>]+>/', $content, $all_img_tags );
		if ( $all_img_tags ) {
			foreach ( $all_img_tags[ 0 ] as $img_tag ) {
				// find class attribute and catch its value
				preg_match( '/<img[^>]*class\s*=\s*[\'"]([^\'"]+)[\'"][^>]*>/i', $img_tag, $img_class );
				if ( $img_class ) {
					// Look for the WP image id
					preg_match( '/wp-image-([\d]+)/i', $img_class[ 1 ], $found_id );
					// if first image id found: check whether is image
					if ( $found_id ) {
						$img_id = absint( $found_id[ 1 ] );
						// if is image: return its id
						if ( wp_get_attachment_image_src( $img_id ) ) {
							return $img_id;
						}
					} // if(found_id)
				} // if(img_class)
				
				// else: try to catch image id by its url as stored in the database
				// find src attribute and catch its value
				preg_match( '/<img[^>]*src\s*=\s*[\'"]([^\'"]+)[\'"][^>]*>/i', $img_tag, $img_src );
				if ( $img_src ) {
					// delete optional query string in img src
					$url = preg_replace( '/([^?]+).*/', '\1', $img_src[ 1 ] );
					// delete image dimensions data in img file name, just take base name and extension
					$guid = preg_replace( '/(.+)-\d+x\d+\.(\w+)/', '\1.\2', $url );
					// look up its ID in the db
					$found_id = $wpdb->get_var( $wpdb->prepare( "SELECT `ID` FROM $wpdb->posts WHERE `guid` = '%s'", $guid ) );
					// if first image id found: return it, else download it and return its id
					if ( $found_id ) {
						return absint( $found_id );
					/*} else {
						// get full image resource path again
						$url = $img_src[ 1 ];
						// download image
						$path = download_url( $url, $timeout_seconds );
						// next image if error
						if ( is_wp_error( $path ) ) {
							continue;
						} else {
							// upload image into the media library and return its id
							return $this->get_inserted_image_id( $path, $post_id );
						} // if is_wp_error*/
					} // if(found_id)
				} // if(img_src)
			} // foreach(img_tag)
		} // if(all_img_tags)
		
		// if nothing found: return 0
		return 0;
	}

	/**
	 * Returns the id of the first image of the first WP standard gallery in the content, else 0
	 *
	 * @access   private
	 * @since     1.0.0 pro
	 *
	 * @return    integer    the post id of the image
	 */
	private function get_first_wp_gallery_image_id ( $post_id, $content ) {
		// try to find a gallery and pick its first image
		preg_match( '/\[gallery[^\]]*ids="(\d+)[^\]]*\]/i', $content, $found_id );
		// if first image id found: check whether is image
		if ( $found_id ) {
			$img_id = absint( $found_id[ 1 ] );
			// if is image: return its id
			if ( wp_get_attachment_image_src( $img_id ) ) {
				return $img_id;
			}
		} // if(found_id)
			
		// if nothing found: return 0
		return 0;
	}

	/**
	 * Returns the id of the first image attached to the post, else 0
	 *
	 * @access   private
	 * @since     1.0.0 pro
	 *
	 * @return    integer    the post id of the image
	 */
	private function get_first_attached_image_id ( $post_id, $content ) {
		// get the first image associated with the post
		$attachments = get_children( array(
			'post_parent'    => $post_id,
			'post_status'    => 'inherit',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'numberposts' => 1,
			'order' => 'ASC' # default: order by date (oldest image)
		) );
		if ( $attachments ) {
			// get first result
			$image_post = array_shift( $attachments );
			// if is image: return its id
			if ( wp_get_attachment_image_src( $image_post->ID ) ) {
				return $image_post->ID;
			}
		} // if(attachments)
		
		// if nothing found: return 0
		return 0;
	}

	/**
	 * Returns the id of the first internal image in the content, else 0
	 *
	 * @access   private
	 * @since     3.2 pro
	 *
	 * @return    integer    the post id of the image
	 */
	private function get_first_internal_image_id ( $post_id, $content ) {
		// set variables
		global $wpdb;
		$timeout_seconds = 60;
		// look for images in HTML code
		preg_match_all( '/<[iI][mM][gG][^>]+>/', $content, $all_img_tags );
		if ( $all_img_tags ) {
			foreach ( $all_img_tags[ 0 ] as $img_tag ) {
				// try to catch external image, upload it and return its id
				// find src attribute and catch its value
				preg_match( '/<img[^>]*src\s*=\s*[\'"]([^\'"]+)[\'"][^>]*>/i', $img_tag, $img_src );
				if ( $img_src ) {
					$url = $img_src[ 1 ];
					// exclude images not from current domain
					if ( false === strpos( $url, $this->site_domain ) ) {
						continue;
					}
					// check whether the image is already in the media library
					$thumb_id = $this->get_first_content_image_id( $post_id, $content );
					// if image is available, use it, skip rest
					if ( $thumb_id ) {
						return $thumb_id;
					}
					// download image, get temporary path to it
					$path = download_url( $url, $timeout_seconds );
					// next image if error
					if ( is_wp_error( $path ) ) {
						continue;
					} else {
						// upload image into the media library and return its id
						return $this->get_inserted_image_id( $path, $post_id );
					} // if is_wp_error
				} // if(img_src)
			} // foreach(img_tag)
		} // if(all_img_tags)
		
		// if nothing found: return 0
		return 0;
	}

	/**
	 * Returns the id of the first external image in the content, else 0
	 *
	 * @access   private
	 * @since     2.0.0 pro
	 *
	 * @return    integer    the post id of the image
	 */
	private function get_first_external_image_id ( $post_id, $content ) {
		// set variables
		global $wpdb;
		$timeout_seconds = 60;
		// look for images in HTML code
		preg_match_all( '/<[iI][mM][gG][^>]+>/', $content, $all_img_tags );
		if ( $all_img_tags ) {
			foreach ( $all_img_tags[ 0 ] as $img_tag ) {
				// try to catch external image, upload it and return its id
				// find src attribute and catch its value
				preg_match( '/<img[^>]*src\s*=\s*[\'"]([^\'"]+)[\'"][^>]*>/i', $img_tag, $img_src );
				if ( $img_src ) {
					// delete optional query string in img src
					$url = preg_replace( '/([^?]+).*/', '\1', $img_src[ 1 ] );
					// exclude images from current domain
					if ( false !== strpos( $url, $this->site_domain ) ) {
						continue;
					}
					// download image, get temporary path to it
					$path = download_url( $url, $timeout_seconds );
					// next image if error
					if ( is_wp_error( $path ) ) {
						continue;
					} else {
						// upload image into the media library and return its id
						return $this->get_inserted_image_id( $path, $post_id );
					} // if is_wp_error
				} // if(img_src)
			} // foreach(img_tag)
		} // if(all_img_tags)
		
		// if nothing found: return 0
		return 0;
	}

	/**
	 * Returns the post content without the first image
	 *
	 * @access   private
	 * @since     1.0.0 pro
	 *
	 * @return    string    the post content without the first image
	 */
	private function remove_first_post_image ( $content ) {
		// delete the first img element with its caption and link if existing
		return preg_replace( '/(\[caption[^\]]*\] )?(<a[^>]*>)?<img[^>]+>(<\/a>)?(.+?\[\/caption\] )?/i', '', $content, 1 );
	}

	/**
	 * Returns the filename extension of an image based on its bytes
	 *
	 * @access   private
	 * @since     2.0.0 pro
	 *
	 * @return    string    the filename with extension
	 */
	private function get_image_filename_extension ( $path ) {
		
		// quit if file is not available
		if( ! @file_exists( $path ) ) return '';
		
		// take the fast exif_imagetype(), else getimagesize()
		if ( $this->is_exif_imagetype ) {
			// get the image type number
			$type = exif_imagetype( $path );
		} else {
			list( $width, $height, $type, $attr ) = getimagesize( $path );
		}
		// quit if error
		if ( false === $type ) return '';
		// get the extension
		switch ( $type ) {
			case 1: // IMAGETYPE_GIF
				$ext = 'gif';
				break;
			case 2: // IMAGETYPE_JPEG
				$ext = 'jpg';
				break;
			case 3: // IMAGETYPE_PNG
				$ext = 'png';
				break;
			case 4: // IMAGETYPE_SWF
				$ext = 'swf';
				break;
			case 5: // IMAGETYPE_PSD
				$ext = 'psd';
				break;
			case 6: // IMAGETYPE_BMP
				$ext = 'bmp';
				break;
			case 7: // IMAGETYPE_TIFF_II (intel-Bytefolge)
			case 8: // IMAGETYPE_TIFF_MM (motorola-Bytefolge) 
				$ext = 'tif';
				break;
			case 9: // IMAGETYPE_JPC
				$ext = 'jpc';
				break;
			case 10: // IMAGETYPE_JP2
				$ext = 'jp2';
				break;
			case 11: // IMAGETYPE_JPX
				$ext = 'jpx';
				break;
			case 12: // IMAGETYPE_JB2
				$ext = 'jb2';
				break;
			case 13: // IMAGETYPE_SWC
				$ext = 'swc';
				break;
			case 14: // IMAGETYPE_IFF
				$ext = 'iff';
				break;
			case 15: // IMAGETYPE_WBMP
				$ext = 'wbmp';
				break;
			case 16: // IMAGETYPE_XBM
				$ext = 'xbm';
				break;
			case 17: // IMAGETYPE_ICO
				$ext = 'ico';
				break;
			default:
				$ext = '';
		}
		
		return $ext;
	 }
	
	/**
	 * Returns the post ids which are assigned with featured images smaller than user given dimensions
	 *
	 * @access   private
	 * @since     1.0.0 pro
	 *
	 * @return    array    the post ids assigned with the to small thumbnail
	 */
	private function get_post_ids_of_to_small_thumbnails() {
		$post_ids = array();
		$relevant_featured_image_ids = array();
		$max_width = $this->selected_image_dimensions[ 'max_width' ];
		$max_height = $this->selected_image_dimensions[ 'max_height' ];
		// get all images used as featured images
		$featured_image_ids = $this->get_featured_image_ids();
		// only use featured images smaller than user given dimensions
		foreach ( $featured_image_ids as $post_thumbnail_id ) {
			// get image of given size
			$arr_image = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
			if ( $arr_image )  {
				$is_below_max_width   = $arr_image[1] < $max_width ? true : false;
				$is_below_max_height  = $arr_image[2] < $max_height ? true : false;
				$is_original = $arr_image[3] ? false : true;
				// set as revelant image if it is not resized (= original) and within user given dimensions
				if  ( $is_original && ( $is_below_max_width || $is_below_max_height ) ) {
					$relevant_featured_image_ids[] = $post_thumbnail_id;
				}
			} // if( image )
		} // foreach()
		// get post ids assigned with the relevant featured image ids
		if ( $relevant_featured_image_ids ) {
			$post_ids = $this->get_post_ids_of_featured_image_ids( $relevant_featured_image_ids );
		}
		// return result
		return $post_ids;
	}

	/**
	 * Returns the url of the plugin's admin part
	 *
	 * @since    1.0.0
	 */
	public function get_plugin_admin_url() {
		return plugin_dir_url( __FILE__ );
	}

	/**
	 * Returns the url of the plugin's images folder without an trailing slash	
	 *
	 * @since    1.0.0
	 */
	public function get_admin_images_url() {
		return sprintf( '%s/assets/images', $this->get_plugin_admin_url() );
	}
	
	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Return the page headline.
	 *
	 * @since    7.0
	 *
	 *@return    page headline variable.
	 */
	public function get_page_headline() {
		return __( 'Set, replace, remove', 'quick-featured-images-pro' );
	}

	/**
	 * Return the page description.
	 *
	 * @since    8.0
	 *
	 *@return    page description variable.
	 */
	public function get_page_description() {
		return __( 'Bulk set, replace and remove featured images for existing posts', 'quick-featured-images-pro' );
	}

	/**
	 * Return the page slug.
	 *
	 * @since    7.0
	 *
	 *@return    page slug variable.
	 */
	public function get_page_slug() {
		return $this->page_slug;
	}

	/**
	 * Return the required user capability.
	 *
	 * @since    7.0
	 *
	 *@return    required user capability variable.
	 */
	public function get_required_user_cap() {
		return $this->required_user_cap;
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		// request css only if this plugin was called
		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), $this->plugin_version );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), $this->plugin_version );
		}

		/*
		 * Enqueue all stuff to use media API
		 *
		 * requires at least WP 3.5
		 */
		wp_enqueue_media();
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since     1.0.0
	 */
	public function add_plugin_admin_menu() {

		// get translated string of the menu label and page headline
		$label = $this->get_page_headline();
		
		/*
		 * Add the top level menu page of this plugin
		 *
		 */
		//$this->plugin_screen_hook_suffix = add_object_page(...);
		$this->plugin_screen_hook_suffix = add_submenu_page( 
			$this->parent_page_slug, // parent_slug
			sprintf( '%s: %s', $this->plugin_name, $label ), // page_title
			$label, // menu_title
			$this->required_user_cap, // capability to use the following function
			$this->page_slug, // menu_slug
			array( $this, 'main' ) // function to execute when loading this page
		);		
	}
	
	/**
	 * Add a "Bulk set" link to the media row actions
	 *
	 * @since    4.1
	 */
	function add_media_row_action( $actions, $post ) {

		// if current media is not an image or user has not the right or thumbnails are not supported return without change
		if ( 'image/' != substr( $post->post_mime_type, 0, 6 ) || ! current_user_can( $this->required_user_cap ) || ! current_theme_supports( 'post-thumbnails' ) )
			return $actions;
		
		// else build the link with nonce
		$url = wp_nonce_url( admin_url( sprintf( 'admin.php?page=%s&step=select&action=assign&image_id=%d', $this->page_slug, $post->ID ) ), 'bulk-assign' );
		
		// add it
		$actions[ 'quick-featured-images-pro' ] = sprintf( '<a href="%s">%s</a>', esc_url( $url ), __( 'Bulk set as featured image', 'quick-featured-images-pro' ) );
		
		// return extended action links list
		return $actions;
	}

}

