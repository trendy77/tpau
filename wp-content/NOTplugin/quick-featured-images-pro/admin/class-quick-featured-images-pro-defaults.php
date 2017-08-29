<?php
/**
 * Quick Featured Images
 *
 * @package   Quick_Featured_Images_Pro_Defaults
 * @author    Martin Stehle <m.stehle@gmx.de>
 * @license   GPL-2.0+
 * @link      http://quickfeaturedimages.com/
 * @copyright 2014 
 */

/**
 * @package Quick_Featured_Images_Pro_Defaults
 * @author    Martin Stehle <m.stehle@gmx.de>
 */
class Quick_Featured_Images_Pro_Defaults {

	/**
	 * Instance of this class.
	 *
	 * @since    8.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Required user capability to use this plugin
	 *
	 * @since   8.0
	 *
	 * @var     string
	 */
	protected $required_user_cap = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    8.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Name of this plugin.
	 *
	 * @since    8.0
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
	 * @since    8.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = null;

	/**
	 * Unique identifier for the admin page of this class.
	 *
	 * @since    8.0
	 *
	 * @var      string
	 */
	protected $page_slug = null;

	/**
	 * Unique identifier for the admin parent page of this class.
	 *
	 * @since    8.0
	 *
	 * @var      string
	 */
	protected $parent_page_slug = null;

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since    8.0
	 *
	 * @var     string
	 */
	protected $plugin_version = null;

	/**
	 * Unique identifier in the WP options table
	 *
	 *
	 * @since    8.0
	 *
	 * @var      string
	 */
	protected $qfip_defaults_db_slug = 'quick-featured-images-pro-defaults';

	/**
	 * Slug of the menu page on which to display the form sections
	 *
	 *
	 * @since    8.0
	 *
	 * @var      array
	 */
	protected $main_options_page_slug = 'quick-featured-images-pro-defaultspage';

	/**
	 * Unique identifier in the WP options table for the plugin's settings
	 *
	 *
	 * @since    5.0 pro
	 *
	 * @var      string
	 */
	protected $settings_db_slug = null;

	/**
	 * Stored settings in an array
	 *
	 *
	 * @since    8.0
	 *
	 * @var      array
	 */
	protected $stored_settings = array();

	/**
	 * User selected rules
	 *
	 * @since    8.0
	 *
	 * @var     string
	 */
	protected $selected_rules = null;

	/**
	 * Actions for 'first image' selection
	 *
	 * @since    3.6 pro
	 *
	 * @var     string
	 */
	protected $valid_first_image_actions = null;

	/**
	 * Options
	 *
	 * @since    4.0 pro
	 *
	 * @var     string
	 */
	protected $valid_first_image_options = null;

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
	 * Host name of the current site
	 *
	 * @since     2.0.0 pro
	 *
	 * @var      string
	 */
	protected $site_domain = null;
	
	/**
	 * Time to wait for a server's response
	 *
	 * @since     3.7.0 pro
	 *
	 * @var      string
	 */
	protected $timeout_seconds = null;
	
	/**
	 * Default order of applying the rules
	 *
	 * @since     3.7.0 pro
	 *
	 * @var      array
	 */
	protected $default_rules_order = null;
	
	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     8.0
	 */
	private function __construct() {

		// Call variables from public plugin class.
		$plugin = Quick_Featured_Images_Pro_Admin::get_instance();
		$this->plugin_name = $plugin->get_plugin_name();
		$this->plugin_slug = $plugin->get_plugin_slug();
		$this->page_slug = $this->plugin_slug . '-defaults';
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
		
		// Domain name of WP site
		$parsed_url = parse_url( home_url() );
		$this->site_domain = $parsed_url[ 'host' ];

		// existence of the exif_imagetype()
		$this->is_exif_imagetype = function_exists( 'exif_imagetype' );

		// time in seconds to wait for a response
		$this->timeout_seconds = 60;
		
		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Auto set featured image on saving a post
		add_action( 'save_post', array( $this, 'add_featured_image' ), 10 , 3 );

		// Auto set random featured image at each page load, after the theme is initialized
		add_action( 'the_post', array( $this, 'random_featured_image' ), 10 );
		
		// Auto delete rule if an image is deleted in the media library
		add_action( 'delete_attachment', array( $this, 'delete_rules_by_thumb_id' ) );
		
		/*
		// check whether the query parameter 'qfi_notice' is in the URL; if yes, show admin notice
		if ( isset( $_REQUEST[ 'qfi_notice' ] ) ) {
			add_action( 'admin_notices', array( $this, 'display_qfi_notice' ) );
		}
		*/
	}

	public function random_featured_image ( $post ) {
		if ( isset( $post->ID ) and ! is_admin() ) {
			$this->set_random_featured_image( $post->ID, $post );
		}
	}
	
	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    8.0
	 */
	public function main() {
		// define actions
		$this->valid_first_image_actions = array(
			'ignore'			=> __( 'Do not use the first image', 'quick-featured-images-pro' ),
			'embedded'			=> __( 'Use first content image', 'quick-featured-images-pro' ),
			'attached'			=> __( 'Use first attached image', 'quick-featured-images-pro' ),
		);
		// define options
		$this->valid_first_image_options = array(
			'remove_first_img'	=> __( 'Remove the first image in the post content after the featured image was set successfully', 'quick-featured-images-pro' ),
		);
		$this->default_rules_order = $this->get_default_rules_order();

		// run
		$this->display_header();
		// store user selections
		if ( ! empty( $_POST ) ) {
			// verify allowed submission
			check_admin_referer( 'save_default_images', 'knlk235rf' );
			// if import: get rules from QFI and copy them to QFIP
			if ( isset( $_POST[ 'importrules' ] ) ) {
				// try to get QFI rules
				$qfi_rules = get_option( 'quick-featured-images-defaults' );
				// if successful copy them to QFIP
				if ( false !== $qfi_rules ) {
					// sanitze stored input
					$settings = $this->sanitize_options( $qfi_rules );
				}
			} else {			
				// sanitze user input
				$settings = $this->sanitize_options( $_POST );
			} // if importrules
			// store in db
			if ( update_option( $this->qfip_defaults_db_slug, $settings ) ) {
				$msg = __( 'Changes saved.', 'quick-featured-images-pro' );
				$class = 'updated';
			} else {
				$msg = __( 'No changes were saved.', 'quick-featured-images-pro' );
				$class = 'error';
			}
			printf ( '<div class="%s"><p><strong>%s</strong></p></div>', $class, $msg );
		} // if $_POST
		// get rules
		$this->selected_rules = $this->get_stored_settings();
		// print rest of page
		$this->display_page_content();
		$this->display_footer();
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     8.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Return the page headline.
	 *
	 * @since    8.0
	 *
	 *@return    page headline variable.
	 */
	public function get_page_headline() {
		return __( 'Preset Featured Images', 'quick-featured-images-pro' );
	}

	/**
	 * Return the page description.
	 *
	 * @since    8.0
	 *
	 *@return    page description variable.
	 */
	public function get_page_description() {
		return __( 'Set default featured images for future posts', 'quick-featured-images-pro' );
	}

	/**
	 * Return the page slug.
	 *
	 * @since    8.0
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
	 * Return the default order of the rules types.
	 *
	 * @since    3.7.1 pro
	 *
	 *@return    array.
	 */
	private function get_default_rules_order() {
		return array(
			'first_image' 			=> '1',
			'matched_search'		=> '2',
			'matched_taxonomy'		=> '3',
			'matched_tag'			=> '4',
			'matched_category'		=> '5',
			'matched_author'		=> '6',
			'matched_postformat'	=> '7',
			'matched_posttype'		=> '8',
		);
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     8.0
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
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array( ), $this->plugin_version );
		}

 	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     8.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		/* collect js for the color picker */
		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin-defaults.js', __FILE__ ), array( 'jquery' ), $this->plugin_version );
		}
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    8.0
	 */
	public function add_plugin_admin_menu() {

		// get translated string of the menu label and page headline
		$label = $this->get_page_headline();
		
		// Add a defaults page for this plugin to the Settings menu.
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
	 * Add defaults action link to the plugins page.
	 *
	 * @since    8.0
	 */
	public function add_action_links( $links ) {
		$url = sprintf( 'admin.php?page=%s', $this->page_slug );
		return array_merge(
			$links,
			array(
				'defaults' => sprintf( '<a href="%s">%s</a>', admin_url( $url ), $this->get_page_headline() )
			)
		);

	}

	/**
	* Check and return correct values for the settings
	*
	* @since   8.0
	*
	* @param   array    $input    Options and their values after submitting the form
	* 
	* @return  array              Options and their sanatized values
	*/
	public function sanitize_options ( $input ) {
		$sanitized_input = array();
		foreach ( $input as $key => $value ) {
			// ignore values with 'false' value, ie. 'null', zero, empty string, empty array
			if ( ! $value ) {
				continue;
			}
			switch ( $key ) {
				// checkboxes
				case 'overwrite_automatically':
				case 'remove_first_img':
					$sanitized_input[ $key ] = isset( $input[ $key ] ) ? '1' : '0';
					break;
				// radio buttons
				case 'use_first_image_as_default':
					// compatibility to older versions
					if ( '1' == $value ) {
						$sanitized_input[ $key ] = 'embedded';
					} else {
						$sanitized_input[ $key ] = ( in_array( $input[ $key ], array( 'ignore', 'embedded', 'attached' ) ) ? $input[ $key ] : 'ignore' );
					}
					break;
				// positive integers
				case 'default_image_id':
					$sanitized_input[ $key ] = absint( $value );
					break;
				// rules
				case 'rules':
					if ( is_array( $value ) ) {
						$c = 1;
						foreach ( $value as $rule ) {
							// ignored only partially defined rule
							// alt: if ( ! $rule[ 'id' ] or ! $rule[ 'taxonomy' ] or ! $rule[ 'matchterm' ] ) {
							// if ( ! ( ( $rule[ 'id' ] or $rule[ 'ids' ] ) and $rule[ 'taxonomy' ] and $rule[ 'matchterm' ] ) ) {
							if ( ( empty( $rule[ 'id' ] ) or empty( $rule[ 'ids' ] ) ) and empty( $rule[ 'taxonomy' ] ) and empty( $rule[ 'matchterm' ] ) ) {
								continue;
							}
							// clean complete rule
							foreach ( $rule as $name => $setting ) {
								switch ( $name ) {
									case 'id':
										if ( $setting ) {
											// cast string to positive integer
											$sanitized_input[ $key ][ $c ][ $name ] = absint( $setting );
										} else {
											$sanitized_input[ $key ][ $c ][ $name ] = 0;
										}
										break;
									case 'ids':
										if ( is_string( $setting ) and ! empty( $setting ) ) {
											// divide string at commas and collect each value in array
											$setting = explode( ',', $setting );
										} 
										if ( is_array( $setting ) ) {
											// cast to array of positive integers
											$sanitized_input[ $key ][ $c ][ $name ] = array_map( 'absint', $setting );
										} else {
											// if checks failed set to empty array
											$sanitized_input[ $key ][ $c ][ $name ] = array();
										}
										break;
									case 'taxonomy':
										// clean string
										$sanitized_input[ $key ][ $c ][ $name ] = sanitize_text_field( $setting );
										break;
									case 'matchterm':
										// clean string, default is positive integer
										if ( 'searchterm' == $rule[ 'taxonomy' ] ) {
											$sanitized_input[ $key ][ $c ][ $name ] = ( isset( $rule[ 'searchterm' ] ) ) ? sanitize_text_field( $rule[ 'searchterm' ] ) : sanitize_text_field( $rule[ 'matchterm' ] );
										} elseif ( 'post_type' == $rule[ 'taxonomy' ] or 'post_format' == $rule[ 'taxonomy' ] ) {
											$sanitized_input[ $key ][ $c ][ $name ] = sanitize_text_field( $setting );
										} else {
											$sanitized_input[ $key ][ $c ][ $name ] = absint( $setting );
										}
										break;
									case 'randomize':
										$sanitized_input[ $key ][ $c ][ $name ] = isset( $input[ $key ][ $c ][ $name ] ) ? '1' : '0';
										// unset if no multiple images
										if ( empty( $rule[ 'ids' ] ) ) {
											unset( $sanitized_input[ $key ][ $c ][ $name ] );
										}
										break;
								} // switch()
							} // foreach
							$c = $c + 1;
						} // foreach
					} // if ( is_array )
					break;
				// rules order
				case 'rules_order':
					if ( is_array( $value ) ) {
						// store sanitized rules order
						$sanitized_input[ $key ] = wp_parse_args( $value, $this->get_default_rules_order() );
					} // if ( is_array )
					break;
			} // switch( $key )
		} // foreach( $input as $key => $value )
	
		// make sure some values are set
		if ( isset( $sanitized_input[ 'rules_order' ] ) and $sanitized_input[ 'rules_order' ] ) {
			// sort rules
			asort( $sanitized_input[ 'rules_order' ] ); 
		} else {
			// set default order
			$sanitized_input[ 'rules_order' ] = $this->get_default_rules_order();
		}
		// compatibility to older versions
		if ( ! isset( $sanitized_input[ 'use_first_image_as_default' ] ) ) {
			$sanitized_input[ 'use_first_image_as_default' ] = 'ignore';
		}

		// add empty set for random images if there are rules and if 'ids' key not available, e.g. after import from QFI
		if ( isset( $sanitized_input[ 'rules' ] ) ) {
			foreach ( $sanitized_input[ 'rules' ] as $i => $rule ) {
				if ( ! isset( $sanitized_input[ 'rules' ][ $i ][ 'ids' ] ) ) {
					$sanitized_input[ 'rules' ][ $i ][ 'ids' ] = array();
				}
			}
		}
		
		// return sanitized settings
		return $sanitized_input;
	} // end sanitize_options()

	/**
	 *
	 * Auto set featured image at saving a post
	 *
	 * @access   private
	 * @since    8.0
	 */
	public function add_featured_image( $post_id, $post, $is_update ) {
		// get out if post is autosave type
		if ( wp_is_post_autosave( $post_id ) ) return;
		// get out if post is revision
		if ( wp_is_post_revision( $post_id ) ) return;
		// get out if post is a newly created post, with no content
		if ( 'auto-draft' == get_post_status( $post_id ) ) return;
		// get post object if not valid
		if ( ! $post ) {
			$post = get_post( $post_id );
		}
		// get out if post does not support featured images
		if ( ! post_type_supports( $post->post_type, 'thumbnail' ) ) return;
		// else go on

		// load all rules
		$settings = $this->get_stored_settings();

		// get out if user wishes not to overwrite existing featured images and post has already a featured image
		if ( has_post_thumbnail( $post_id ) and ( ! isset( $settings[ 'overwrite_automatically' ] ) ) ) return;
		
		// set the thumbnail if a rule matches in the order the user desires
		/*
		 * Default rule order until the user changes it:
		 * 1. first embedded or attached image
		 * 2. matched search string
		 * 3. matched custom taxonomy
		 * 4. matched tag
		 * 5. matched category
		 * 6. matched user
		 * 7. matched post format
		 * 8. matched post type
		 */

		// initialize variables
		$thumb_id = 0;

		// looop through rules until an ID is set
		foreach ( $settings[ 'rules_order' ] as $name => $index ) {
			// check rules
			switch ( $name ) {

				// Image by first image (embedded or attached)
				case 'first_image':
					if ( 'embedded' == $settings[ 'use_first_image_as_default' ] ) {
						// get first content image
						$thumb_id = $this->get_first_content_image_id( $post->post_content );
					} elseif ( 'attached' == $settings[ 'use_first_image_as_default' ] ) {
						// get first attached image
						$thumb_id = $this->get_first_attached_image_id( $post->ID );
					} // if (use_first_image_as_default)
					break;
				
				// Image by matched search string
				case 'matched_search':
					if ( isset( $settings[ 'rules' ] ) ) {
						foreach ( $settings[ 'rules' ] as $rule ) {
							if ( 'searchterm' != $rule[ 'taxonomy' ] ) {
								continue; // ommit non-post-tag rules here
							}
							$thumb_id = $this->get_thumb_id( $post_id, $rule, $post->post_type );
							if ( $thumb_id ) {
								break;
							}
						} // foreach()
					} // if ( rules )
					break;
				
				// Image by matched custom taxonomy
				case 'matched_taxonomy':
					if ( isset( $settings[ 'rules' ] ) ) {
						$skipped_taxonomies = array( 'searchterm', 'post_tag', 'category', 'user', 'post_type' );
						foreach ( $settings[ 'rules' ] as $rule ) {
							if ( in_array( $rule[ 'taxonomy' ], $skipped_taxonomies ) ) {
								continue;
							}
							$thumb_id = $this->get_thumb_id( $post_id, $rule, $post->post_type );
							if ( $thumb_id ) {
								break;
							}
						} // foreach()
					} // if ( rules )
					break;
				
				// Image by matched tag
				case 'matched_tag':
					if ( isset( $settings[ 'rules' ] ) ) {
						foreach ( $settings[ 'rules' ] as $rule ) {
							if ( 'post_tag' != $rule[ 'taxonomy' ] ) {
								continue; // ommit non-post-tag rules here
							}
							$thumb_id = $this->get_thumb_id( $post_id, $rule, $post->post_type );
							if ( $thumb_id ) {
								break;
							}
						} // foreach()
					} // if ( rules )
					break;
				
				// Image by matched category
				case 'matched_category':
					if ( isset( $settings[ 'rules' ] ) ) {
						foreach ( $settings[ 'rules' ] as $rule ) {
							if ( 'category' != $rule[ 'taxonomy' ] ) {
								continue; // ommit non-post-category rules here
							}
							$thumb_id = $this->get_thumb_id( $post_id, $rule, $post->post_type );
							if ( $thumb_id ) {
								break;
							}
						} // foreach()
					} // if ( rules )
					break;
				
				// Image by matched user
				case 'matched_author':
					if ( isset( $settings[ 'rules' ] ) ) {
						foreach ( $settings[ 'rules' ] as $rule ) {
							if ( 'user' != $rule[ 'taxonomy' ] ) {
								continue; // ommit non-post-author rules here
							}
							if ( $post->post_author != $rule[ 'matchterm' ] ) {
								continue;
							}
							if ( $id = $this->get_single_id( $rule, $post->post_type ) ) {
								$thumb_id = $id;
								break;
							}
						} // foreach()
					} // if ( rules )
					break;
				
				// Image by post format
				case 'matched_postformat':
					if ( isset( $settings[ 'rules' ] ) ) {
						foreach ( $settings[ 'rules' ] as $rule ) {
							if ( 'post_format' != $rule[ 'taxonomy' ] ) {
								continue; // ommit non-post-format rules here
							}
							if ( ! has_post_format ( $rule[ 'matchterm' ], $post_id ) ) {
								continue;
							}
							if ( $id = $this->get_single_id( $rule, $post->post_type ) ) {
								$thumb_id = $id;
								break;
							}
						} // foreach()
					} // if ( rules )
					break;
				
				// Image by post type
				case 'matched_posttype':
					if ( isset( $settings[ 'rules' ] ) ) {
						foreach ( $settings[ 'rules' ] as $rule ) {
							if ( 'post_type' != $rule[ 'taxonomy' ] ) {
								continue; // ommit non-post-type rules here
							}
							if ( $post->post_type != $rule[ 'matchterm' ] ) {
								continue;
							}
							/*if ( 'pf_feed_item' == $post->post_type ) {
								$thumb_id = $this->get_oembed_thumb_id ( $post->guid );
							} else { */
							if ( $id = $this->get_single_id( $rule, $post->post_type ) ) {
								$thumb_id = $id;
								break;
							}
						} // foreach()
					} // if ( rules )
					break;
				
			} // switch ( name )
			
			// quit loop if an ID is set
			if ( $thumb_id ) {
				break;
			}
		} // foreach( rules_order )
			
		// set image as featured image to post
		$success = false;
		if ( $thumb_id ) {
			$success = set_post_thumbnail( $post_id, $thumb_id );
			// remove first image if desired
			if ( isset( $settings[ 'remove_first_img' ] ) ) {
				// delete the first img element with its caption and link if existing in post content
				$post_content =  preg_replace( '/(\[caption[^\]]*\]\s*)?(<a[^>]*>)?<img[^>]+>(<\/a>)?(.+?\[\/caption\]\s*)?(\r)?(\n)?/i', '', $post->post_content, 1 );
				// store new post content, in case of failure return 0
				$returned_id = wp_update_post( array( 'ID' => $post_id, 'post_content' => $post_content ) );
			} // if(removal)
		}
	}

	/**
	 *
	 * Auto set featured image at displaying a post
	 *
	 * @access   private
	 * @since    5.0 pro
	 */
	public function set_random_featured_image( $post_id, $post ) {
		// get out if post does not support featured images
		if ( ! post_type_supports( $post->post_type, 'thumbnail' ) ) return;
		// else go on

		// load all rules
		$settings = $this->get_stored_settings();

		// initialize variables
		$thumb_id = 0;

		// looop through rules until an ID is set
		foreach ( $settings[ 'rules_order' ] as $name => $index ) {
			// check rules
			switch ( $name ) {

				// Image by matched search string
				case 'matched_search':
					if ( isset( $settings[ 'rules' ] ) ) {
						foreach ( $settings[ 'rules' ] as $rule ) {
							if ( empty( $rule[ 'ids' ] ) or ! isset( $rule[ 'randomize' ] ) ) {
								continue; // omit non-random rule
							}
							if ( 'searchterm' != $rule[ 'taxonomy' ] ) {
								continue; // ommit non-post-tag rules here
							}
							$thumb_id = $this->get_thumb_id( $post_id, $rule, $post->post_type );
							if ( $thumb_id ) {
								break;
							}
						} // foreach()
					} // if ( rules )
					break;
				
				// Image by matched custom taxonomy
				case 'matched_taxonomy':
					if ( isset( $settings[ 'rules' ] ) ) {
						$skipped_taxonomies = array( 'searchterm', 'post_tag', 'category', 'user', 'post_type' );
						foreach ( $settings[ 'rules' ] as $rule ) {
							if ( empty( $rule[ 'ids' ] ) or ! isset( $rule[ 'randomize' ] ) ) {
								continue; // omit non-random rule
							}
							if ( in_array( $rule[ 'taxonomy' ], $skipped_taxonomies ) ) {
								continue;
							}
							$thumb_id = $this->get_thumb_id( $post_id, $rule, $post->post_type );
							if ( $thumb_id ) {
								break;
							}
						} // foreach()
					} // if ( rules )
					break;
				
				// Image by matched tag
				case 'matched_tag':
					if ( isset( $settings[ 'rules' ] ) ) {
						foreach ( $settings[ 'rules' ] as $rule ) {
							if ( empty( $rule[ 'ids' ] ) or ! isset( $rule[ 'randomize' ] ) ) {
								continue; // omit non-random rule
							}
							if ( 'post_tag' != $rule[ 'taxonomy' ] ) {
								continue; // ommit non-post-tag rules here
							}
							$thumb_id = $this->get_thumb_id( $post_id, $rule, $post->post_type );
							if ( $thumb_id ) {
								break;
							}
						} // foreach()
					} // if ( rules )
					break;
				
				// Image by matched category
				case 'matched_category':
					if ( isset( $settings[ 'rules' ] ) ) {
						foreach ( $settings[ 'rules' ] as $rule ) {
							if ( empty( $rule[ 'ids' ] ) or ! isset( $rule[ 'randomize' ] ) ) {
								continue; // omit non-random rule
							}
							if ( 'category' != $rule[ 'taxonomy' ] ) {
								continue; // ommit non-post-category rules here
							}
							$thumb_id = $this->get_thumb_id( $post_id, $rule, $post->post_type );
							if ( $thumb_id ) {
								break;
							}
						} // foreach()
					} // if ( rules )
					break;
				
				// Image by matched user
				case 'matched_author':
					if ( isset( $settings[ 'rules' ] ) ) {
						foreach ( $settings[ 'rules' ] as $rule ) {
							if ( empty( $rule[ 'ids' ] ) or ! isset( $rule[ 'randomize' ] ) ) {
								continue; // omit non-random rule
							}
							if ( 'user' != $rule[ 'taxonomy' ] ) {
								continue; // ommit non-post-author rules here
							}
							if ( $post->post_author != $rule[ 'matchterm' ] ) {
								continue;
							}
							if ( $id = $this->get_single_id( $rule, $post->post_type ) ) {
								$thumb_id = $id;
								break;
							}
						} // foreach()
					} // if ( rules )
					break;
				
				// Image by post format
				case 'matched_postformat':
					if ( isset( $settings[ 'rules' ] ) ) {
						foreach ( $settings[ 'rules' ] as $rule ) {
							if ( empty( $rule[ 'ids' ] ) or ! isset( $rule[ 'randomize' ] ) ) {
								continue; // omit non-random rule
							}
							if ( 'post_format' != $rule[ 'taxonomy' ] ) {
								continue; // ommit non-post-format rules here
							}
							if ( ! has_post_format ( $rule[ 'matchterm' ], $post_id ) ) {
								continue;
							}
							if ( $id = $this->get_single_id( $rule, $post->post_type ) ) {
								$thumb_id = $id;
								break;
							}
						} // foreach()
					} // if ( rules )
					break;
				
				// Image by post type
				case 'matched_posttype':
					if ( isset( $settings[ 'rules' ] ) ) {
						foreach ( $settings[ 'rules' ] as $rule ) {
							if ( empty( $rule[ 'ids' ] ) or ! isset( $rule[ 'randomize' ] ) ) {
								continue; // omit non-random rule
							}
							if ( 'post_type' != $rule[ 'taxonomy' ] ) {
								continue; // ommit non-post-type rules here
							}
							if ( $post->post_type != $rule[ 'matchterm' ] ) {
								continue;
							}
							/*if ( 'pf_feed_item' == $post->post_type ) {
								$thumb_id = $this->get_oembed_thumb_id ( $post->guid );
							} else { */
							if ( $id = $this->get_single_id( $rule, $post->post_type ) ) {
								$thumb_id = $id;
								break;
							}
						} // foreach()
					} // if ( rules )
					break;
				
			} // switch ( name )
			
			// quit loop if an ID is set
			if ( $thumb_id ) {
				break;
			}
		} // foreach( rules_order )
			
		// set image as featured image to post
		if ( $thumb_id ) {
			set_post_thumbnail( $post_id, $thumb_id );
		}
	}

	/**
	 *
	 * Delete the rules assigned to an image; is called after an image was deleted in the media library
	 *
	 * @access   private
	 * @since    3.7.0 pro
	 */
	public function delete_rules_by_thumb_id( $thumb_id ) {
		// initialize flag
		$changed = false;
		// load all rules
		$settings = $this->get_stored_settings();

		// if rules are available look for rules with the given thumb_id and delete them
		if ( isset( $settings[ 'rules' ] ) ) {
			foreach ( $settings[ 'rules' ] as $key => $rule ) {
				// delete rule with single image
				if ( $thumb_id == $rule[ 'id' ] ) {
					unset( $settings[ 'rules' ][ $key ] );
					$changed = true;
				// or if image in rule with multiple images
				} else {
					// remove image id out of image set
					$i = array_search( $thumb_id, $rule[ 'ids' ] );
					if ( false !== $i ) {
						unset( $rule[ 'ids' ][ $i ] );
						$settings[ 'rules' ][ $key ][ 'ids' ] = array_values( $rule[ 'ids' ] ); // re-index array
						$changed = true;
					}
				}
			} // foreach()
			// if no rules anymore: delete 'rules' item
			if ( empty( $settings[ 'rules' ] ) ) {
				unset( $settings[ 'rules' ] );
			} else {
				// reindex array	
				$settings[ 'rules' ] = array_values( $settings[ 'rules' ] );
			}
		} // if ( rules )
		
		// store
		if ( $changed ) {
			// store in db
			update_option( $this->qfip_defaults_db_slug, $settings );
		} // if ( changed )
	}
	
	/**
	 *
	 * Render the header of the admin page
	 *
	 * @access   private
	 * @since    8.0
	 */
	private function display_header() {
		include_once( 'views/section_header.php' );
	}
	
	/**
	 *
	 * Render the footer of the admin page
	 *
	 * @access   private
	 * @since    8.0
	 */
	private function display_footer() {
		include_once( 'views/section_footer.php' );
	}
	
	/**
	 *
	 * Render the the admin page
	 *
	 * @access   private
	 * @since    8.0
	 */
	private function display_page_content() {
		include_once( 'views/section_defaults.php' );
	}
	
	/**
	 * Set default settings
	 *
	 * @since    8.0
	 */
	private function set_default_settings() {

		// check if there are already stored settings under the option's database slug
		if ( false === get_option( $this->qfip_defaults_db_slug, false ) ) {
			// store default values in the db as a single and serialized entry
			add_option( 
				$this->qfip_defaults_db_slug, 
				array(
					'rules_order' => $this->get_default_rules_order()
				)
			);
		} // if ( false )
		
	}

	/**
	 * Define parameters and return thumbnail supporting custom post types
	 *
	 * @access   private
	 * @since     7.0
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
	 * Return registered custom taxonomies with their labels
	 *
	 * @access   private
	 * @since    8.0
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
	 * Get current or default settings
	 *
	 * @since    8.0
	 *
	 * @return    array    Return settings for default featured images
	 */
	private function get_stored_settings() {
		// try to load current settings. If they are not in the DB return set default settings
		$stored_settings = get_option( $this->qfip_defaults_db_slug, array() );
		// if empty array set and store default values
		if ( 0 == sizeof( $stored_settings ) ) {
			$this->set_default_settings();
			// try to load current settings again. Now there should be the data
			$stored_settings = get_option( $this->qfip_defaults_db_slug, array() );
		}

		return $this->sanitize_options( $stored_settings );
	}
	
	/**
	 *
	 * Test term and image id
	 *
	 * @access   private
	 * @since    8.0
	 */
	private function get_thumb_id ( $post_id, $rule, $post_type ) {

		// get single ID or an ID randomly, else invalid value
		$id = $this->get_single_id( $rule, $post_type );

		if ( 'searchterm' == $rule[ 'taxonomy' ] ) {
			if ( ! empty( $rule[ 'matchterm' ] ) and false !== strpos( get_the_title( $post_id ), $rule[ 'matchterm' ] ) ) {
				return $id;
			} else {
				return 0;
			}
		} else {
			$terms = wp_get_post_terms( $post_id, $rule[ 'taxonomy' ], array( 'fields' => 'ids' ) );
			if ( is_wp_error( $terms ) ) {
				return 0;
			} 
			if ( ! in_array( $rule[ 'matchterm' ], $terms ) ) {
				return 0;
			}
			return $id;
		} // if taxonomy == 'searchterm'
	}

	/**
	 *
	 * Evaluate the ID depending on existing array of IDs or single ID
	 *
	 * @access   private
	 * @since    3.8 pro
	 */
	private function get_single_id ( $rule, $post_type ) {

		$id = 0;
		
		if ( ! empty( $rule[ 'ids' ] ) and is_array( $rule[ 'ids' ] ) ) {
			// if there are more than 1 image to set as featured and to prevent two identical featured images one behind the other:
			if ( 1 < count( $rule[ 'ids' ] ) ) {
				// get the last post
				$last_post = get_posts( array(
					'posts_per_page'	=> 2,
					'orderby'			=> 'date',
					'order'				=> 'DESC',
					'post_type'			=> $post_type,
					'post_status'		=> 'any',
					'fields'			=> 'ids', // return only post IDs
				) );
				// if there is more than 1 post: get its featured image
				if ( 1 < count( $last_post ) ) {
					$last_post_thumb_id = (int) get_post_thumbnail_id( $last_post[ 1 ] );
					if ( $last_post_thumb_id ) {
						// remove found image ID from array, get re-indexed array
						array_splice( $rule[ 'ids' ], array_search( $last_post_thumb_id, $rule[ 'ids' ] ), 1);
					}
				}
			} // if ( 1 < count( $rule[ 'ids' ] ) )
			// return random value
			$id = $rule[ 'ids' ][ rand( 0, count( $rule[ 'ids' ] ) - 1 ) ]; // old: shuffle( $rule[ 'ids' ] ); $id = $rule[ 'ids' ][ 0 ];
		} elseif ( ! empty( $rule[ 'id' ] ) ) {
			// return single id
			$id = $rule[ 'id' ];
		} else {
			// return invalid value
			return 0;
		}

		// check if media id returns an image
		if ( ! wp_attachment_is_image( $id ) ) {
			return 0;
		}
		
		// finally return the id
		return $id;
		
	}

	/**
	 * Returns the id of the first image in the content, else 0
	 *
	 * @access   private
	 * @since     5.0
	 *
	 * @return    integer    the post id of the image
	 */
	private function get_first_content_image_id ( $content ) {
		// set variables
		global $wpdb;
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
				
				// else: try to catch content image id by its url as stored in the database
				// find src attribute and catch its value
				preg_match( '/<img[^>]*src\s*=\s*[\'"]([^\'"]+)[\'"][^>]*>/i', $img_tag, $img_src );
				if ( $img_src ) {
					// delete optional query string in img src
					$url = preg_replace( '/([^?]+).*/', '\1', $img_src[ 1 ] );
					// delete image dimensions data in img file name, just take base name and extension
					$guid = preg_replace( '/(.+)-\d+x\d+\.(\w+)/', '\1.\2', $url );
					// look up its ID in the db
					$found_id = $wpdb->get_var( $wpdb->prepare( "SELECT `ID` FROM $wpdb->posts WHERE `guid` = '%s'", $guid ) );
					// if first image id found: return it
					if ( $found_id ) {
						return absint( $found_id );
					} // if(found_id)

				} // if(img_src)

				// else: try to catch external image, upload it and return its id
				/*// exclude images from current domain
				if ( false !== strpos( $url, $this->site_domain ) ) {
					continue;
				}*/
				// download image, get temporary path to it
				$path = download_url( $url, $this->timeout_seconds );
				// next image if error
				if ( is_wp_error( $path ) ) {
					continue;
				} else {
					// upload image into the media library and return its id
					return $this->get_inserted_image_id( $path );
				} // if is_wp_error

			} // foreach(img_tag)
		} // if(all_img_tags)
		
		// if nothing found: return 0
		return 0;
	}

	/**
	 * Returns the id of the first attached image, else 0
	 *
	 * @access   private
	 * @since     3.6 pro
	 *
	 * @return    integer    the post id of the image
	 */
	private function get_first_attached_image_id ( $post_id ) {

	// look for post's attached images
		$attachments = get_children( 
			array(
				'post_parent' => $post_id,
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'orderby' => 'menu_order'
			)
		);
		
		if ( $attachments ) {
			$first_attachment = array_shift( $attachments );
			$img_id = absint( $first_attachment->ID );
			// if is image: return its id
			if ( wp_get_attachment_image_src( $img_id ) ) {
				return $img_id;
			}
		} // if(attachments)
				
		// if nothing found: return 0
		return 0;
	}

	/**
	 * Inserts given image into the media library, attachs it to given post and returns image ID
	 *
	 * @access   private
	 * @since     1.0.0 pro
	 *
	 * @return    object|integer    WP Error|the ID of inserted image
	 */
	private function get_inserted_image_id ( $source_file, $parent_post_id = 0, $img_data = array(), $keep_file = false )  {

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
	 * Returns the id of the thumbnail given by the oEmbed meta param of the web page
	 *
	 * @access   private
	 * @since    3.7.0 pro
	 *
	 * @return    integer    the post id of the image
	 */
	private function get_oembed_thumb_id ( $url ) {
		$response = wp_safe_remote_get( $url );
		if ( 501 == wp_remote_retrieve_response_code( $response ) ) {
			#return new WP_Error( 'not-implemented' );
			return false;
		} else {
			if ( ! $body = wp_remote_retrieve_body( $response ) ) {
				return false;
			} else {
				preg_match( '/property=[\'"]og:image[\'"]\s*content=[\'"]([^\'"]+)[\'"]/', $body, $matches );
				if ( $matches ) {
					// download image, get temporary path to it
					$path = download_url( $matches[ 1 ], $this->timeout_seconds );
					// quit if error
					if ( is_wp_error( $path ) ) {
						return false;
					} else {
						// upload image into the media library and return its id
						return $this->get_inserted_image_id( $path );
					} // if is_wp_error
				} // if matches
			} // if no response body
		} // if 501
	}

/*
	function add_qfi_param( $loc ) {
		return esc_url( add_query_arg( 'qfi_notice', 1, $loc ) );
	}

	function display_qfi_notice() {
		if ( 1 == $_REQUEST[ 'qfi_notice' ] ) {
			printf( '<div class="updated"><p>%s</p></div>', __( 'Changed featured image successfully.', $this->plugin-slug ) );
		} else {
			#printf( '<div class="error"><p>%s</p></div>', __( 'Featured image not changed.', $this->plugin-slug ) );
		}
	}
*/	

}
