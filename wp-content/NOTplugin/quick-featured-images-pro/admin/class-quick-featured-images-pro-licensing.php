<?php
/**
 * Quick Featured Images
 *
 * @package   Quick_Featured_Images_Pro_Licensing
 * @author    Martin Stehle <m.stehle@gmx.de>
 * @license   GPL-2.0+
 * @link      http://quickfeaturedimages.com/
 * @copyright 2015
 */

/**
 * @package Quick_Featured_Images_Pro_Licensing
 * @author    Martin Stehle <m.stehle@gmx.de>
 */
class Quick_Featured_Images_Pro_Licensing { // only for debugging: extends Quick_Featured_Images_Pro_Base {

	/**
	 * Instance of this class.
	 *
	 * @since    2.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Required user capability to use this plugin
	 *
	 * @since    2.0
	 *
	 * @var     string
	 */
	protected $required_user_cap = 'manage_options';

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    2.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Name of this plugin.
	 *
	 * @since    2.0
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
	 * @since    2.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = null;

	/**
	 * Unique identifier for the admin page of this class.
	 *
	 * @since    2.0
	 *
	 * @var      string
	 */
	protected $page_slug = null;

	/**
	 * Unique identifier for the admin parent page of this class.
	 *
	 * @since    2.0
	 *
	 * @var      string
	 */
	protected $parent_page_slug = null;

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since    2.0
	 *
	 * @var     string
	 */
	protected $plugin_version = null;

	/**
	 * Plugin author.
	 *
	 * @since    3.2
	 *
	 * @var     string
	 */
	protected $plugin_author = 'Martin Stehle';

	/**
	 * Name of action for license activation
	 *
	 *
	 * @since    3.2
	 *
	 * @var      string
	 */
	protected $license_activation_action_name = 'edd_license_activate';

	/**
	 * Name of action for license deactivation
	 *
	 *
	 * @since    3.2
	 *
	 * @var      string
	 */
	protected $license_deactivation_action_name = 'edd_license_deactivate';

	/**
	 * Unique identifier in the WP options table
	 *
	 *
	 * @since    2.0
	 *
	 * @var      string
	 */
	protected $license_key_option_name = 'quick-featured-images-pro-license-key';

	/**
	 * Slug of license status option in the DB
	 *
	 *
	 * @since    2.0
	 *
	 * @var      array
	 */
	protected $license_status_option_name = 'quick-featured-images-pro-license-status';
	
	/**
	 * Group name of options
	 *
	 *
	 * @since    2.0
	 *
	 * @var      array
	 */
	protected $settings_fields_slug = 'qfip_edd_setting_fields';

	/**
	 * Slug of nonce
	 *
	 *
	 * @since    2.0
	 *
	 * @var      string
	 */
	protected $nonce_field_name = 'edd_sample_nonce';
	
	/**
	 * This is the URL the Easy Digital Downloads updater / license checker pings
	 * This should be the URL of the site with EDD installed
	 *
	 * @since    2.0
	 *
	 * @var      string
	 *
	 */
	protected $shop_url = 'http://www.quickfeaturedimages.com'; # /de wenn deutsch

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     2.0
	 */
	private function __construct() {

		// Call variables from public plugin class.
		$plugin = Quick_Featured_Images_Pro_Admin::get_instance();
		$this->plugin_name = $plugin->get_plugin_name();
		$this->plugin_slug = $plugin->get_plugin_slug();
		$this->page_slug = $this->plugin_slug . '-license';
		$this->parent_page_slug = $plugin->get_page_slug();
		$this->plugin_version = $plugin->get_plugin_version();

		if( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
			// load EDD custom updater
			include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );
		}

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// create instance of EDD Updater class
		add_action( 'admin_init', array( $this, 'plugin_updater' ), 0 );
		
		// register activation key input field
		add_action( 'admin_init', array( $this, 'register_options' ) );
		
		// listen to $_POST and activate the license key
		add_action( 'admin_init', array( $this, 'activate_license' ) );

		// listen to $_POST and deactivate the license key
		add_action( 'admin_init', array( $this, 'deactivate_license' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    2.0
	 */
	public function main() {
		$license_key 	  = get_option( $this->license_key_option_name );
		$license_validity = get_option( $this->license_status_option_name );
		if ( false === $license_data = $this->check_license() ) {
			$timestamp = false;
			$activations_left = 0;
			$license_status = $license_validity; // or "invalid" ?
		} else {
			$timestamp = strtotime( $license_data->expires ); // returns false if ->expires is false, too
			$activations_left = $license_data->activations_left;
			$license_status = $license_data->license;
		};

		// print message based on status
		switch ( $license_status ) {
			case 'valid':
				$msg = __( 'The license is valid and active.', 'quick-featured-images-pro' );
				break;
			case 'expired':
				$msg = __( 'The license is expired.', 'quick-featured-images-pro' );
				break;
			case 'invalid':
				$msg = __( 'The license key does not match.', 'quick-featured-images-pro' );
				break;
			case 'inactive':
				$msg = __( 'The license is not activated.', 'quick-featured-images-pro' );
				break;
			case 'disabled':
				$msg = __( 'The license is disabled.', 'quick-featured-images-pro' );
				break;
			case 'site_inactive':
				$msg = __( 'The license is inactive.', 'quick-featured-images-pro' );
				break;
			default:
				$msg = __( 'The status of the license is unknown.', 'quick-featured-images-pro' );
				$msg .= ' ' . (string) $license_status;
		} // switch ( $license_status )
		
		// print page
		$this->display_header();
		include_once( 'views/section_license.php' );
		$this->display_footer();
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     2.0
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
	 * @since    2.0
	 *
	 *@return    page headline variable.
	 */
	public function get_page_headline() {
		return __( 'License Activation', 'quick-featured-images-pro' );
	}

	/**
	 * Return the page description.
	 *
	 * @since    2.0
	 *
	 *@return    page description variable.
	 */
	public function get_page_description() {
		return __( 'Enter the license key to get automatic upgrades of the plugin.', 'quick-featured-images-pro' );
	}

	/**
	 * Return the page slug.
	 *
	 * @since    2.0
	 *
	 *@return    page slug variable.
	 */
	public function get_page_slug() {
		return $this->page_slug;
	}

	/**
	 * Return the required user capability.
	 *
	 * @since    2.0
	 *
	 *@return    required user capability variable.
	 */
	public function get_required_user_cap() {
		return $this->required_user_cap;
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     2.0
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
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    2.0
	 */
	public function add_plugin_admin_menu() {

		// get translated string of the menu label and page headline
		$label = $this->get_page_headline();
		
		// Add a settings page for this plugin to the Licensing menu.
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
	 * Add licsense action link to the plugins page.
	 *
	 * @since    2.0
	 */
	public function add_action_links( $links ) {
		$url = sprintf( 'admin.php?page=%s', $this->page_slug );
		return array_merge(
			$links,
			array(
				'activation' => sprintf( '<a href="%s">%s</a>', admin_url( $url ), $this->get_page_headline() )
			)
		);

	}

	/**
	 *
	 * Render the header of the admin page
	 *
	 * @access   private
	 * @since    2.0
	 */
	private function display_header() {
		include_once( 'views/section_header.php' );
	}
	
	/**
	 *
	 * Render the footer of the admin page
	 *
	 * @access   private
	 * @since    2.0
	 */
	private function display_footer() {
		include_once( 'views/section_footer.php' );
	}
	
	/**
	 *
	 * Creates an instance of EDD Updater class
	 *
	 * @access   private
	 * @since    2.0
	 */
	public function plugin_updater() {

		// retrieve our license key from the DB
		$license_key = trim( get_option( $this->license_key_option_name ) );

		// setup the updater
		$edd_updater = new EDD_SL_Plugin_Updater(
			$this->shop_url,
			dirname( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'quick-featured-images-pro.php',
			array(
				'version' 	=> $this->plugin_version, // current version number
				'license' 	=> $license_key, 		// license key (used get_option above to retrieve from DB)
				'item_name' => $this->plugin_name, 	// name of this plugin
				'author' 	=> $this->plugin_author  // author of this plugin
			)
		);
	}

	/**
	 *
	 * Register activation key input field
	 *
	 * @access   public
	 * @since    2.0
	 */
	public function register_options() {
		// creates our settings in the options table
		register_setting(
			// group name in settings_fields() ?
			$this->settings_fields_slug,
			// name of the option to sanitize and save in the db
			$this->license_key_option_name,
			// callback function that sanitizes the option's values
			array( $this, 'sanitize_options' )
		);
	}

	/**
	* Check and return correct values for the settings
	*
	* @since   2.0
	*
	* @param   array    $input    Options and their values after submitting the form
	*
	* @return  array              Options and their sanatized values
	*/
	public function sanitize_options ( $new ) {
		$old = get_option( $this->license_key_option_name );
		if( $old && $old != $new ) {
			delete_option( $this->license_status_option_name ); // new license has been entered, so must reactivate
		}
		return $new;
	} // end sanitize_options()
	
	/**
	* Activate license key if desired
	*
	* @since   2.0
	*
	*/
	public function activate_license() {

		// listen for our activate button to be clicked
		if( isset( $_POST[ $this->license_activation_action_name ] ) ) {

			// run a quick security check
			if( ! check_admin_referer( $this->license_activation_action_name, $this->nonce_field_name ) ) {
				return; // get out if we didn't click the Activate button
			}

			// retrieve the license from the database
			$license = trim( get_option( $this->license_key_option_name ) );

			// data to send in our API request
			$api_params = array(
				'edd_action'=> 'activate_license',
				'license' 	=> $license,
				'item_name' => urlencode( $this->plugin_name ), // the name of our product in EDD
				'url'       => home_url()
			);
			
			// Call the custom API.
			$response = wp_remote_post(
				$this->shop_url,
				array(
					'timeout' => 15,
					'sslverify' => false,
					'body' => $api_params 
				)
			);
			
			// make sure the response came back okay
			if ( is_wp_error( $response ) ) {
				return false;
			}

			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			
			// $license_data->license will be either "valid" or "invalid"

			update_option( $this->license_status_option_name, $license_data->license );

		} // if( isset( $_POST[ $this->license_activation_action_name ] ) )
	}

	/**
	* Dectivate license key if desired
	*
	* @since   2.0
	*
	*/
	public function deactivate_license() {

		// listen for our activate button to be clicked
		if( isset( $_POST[ $this->license_deactivation_action_name ] ) ) {

			// run a quick security check
			if( ! check_admin_referer( $this->license_deactivation_action_name, $this->nonce_field_name ) ) {
				return; // get out if we didn't click the Activate button
			}

			// retrieve the license from the database
			$license = trim( get_option( $this->license_key_option_name ) );

			// data to send in our API request
			$api_params = array(
				'edd_action'=> 'deactivate_license',
				'license' 	=> $license,
				'item_name' => urlencode( $this->plugin_name ), // the name of our product in EDD
				'url'       => home_url()
			);
			
			// Call the custom API.
			$response = wp_remote_post(
				$this->shop_url,
				array(
					'timeout' => 15,
					'sslverify' => false,
					'body' => $api_params 
				)
			);

			// make sure the response came back okay
			if ( is_wp_error( $response ) ) {
				return false;
			}

			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			
			// $license_data->license will be either "deactivated" or "failed"
			if( $license_data->license == 'deactivated' ) {
				delete_option( $this->license_status_option_name );
			}
			
		} // if( isset( $_POST[ $this->license_deactivation_action_name ] ) )
	}


	/************************************
	* this illustrates how to check if
	* a license key is still valid
	* the updater does this for you,
	* so this is only needed if you
	* want to do something custom
	*************************************/

	private function check_license() {

		global $wp_version;

		$api_params = array(
			'edd_action' => 'check_license',
			'license'    => trim( get_option( $this->license_key_option_name ) ),
			'item_name'  => urlencode( $this->plugin_name ),
			'url'        => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post(
			// url
			$this->shop_url,
			// arguments	
			array(
				'timeout' => 15,
				'sslverify' => false,
				'body' => $api_params 
			)
		);

		if ( is_wp_error( $response ) ) {
			print $response->get_error_message();
			return false;
		}

		// return license informations
		/* 	object(stdClass) (10) {
			$license_data->activations_left : int(0)
			$license_data->customer_email : string(15) "pewtah@snafu.de"
			$license_data->customer_name : string(13) "Martin Stehle"
			$license_data->expires : string(19) "2016-06-22 13:09:01"
			$license_data->item_name : string(25) "Quick+Featured+Images+Pro"
			$license_data->license : string(5) "valid"
			$license_data->license_limit : string(1) "1"
			$license_data->payment_id : string(3) "235"
			$license_data->site_count : int(1)
			$license_data->success : bool(true)
			} */
		return json_decode( wp_remote_retrieve_body( $response ) );
		
		/*
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if( $license_data->license == 'valid' ) {
			echo 'valid'; exit;
			// this license is still valid
		} else {
			echo 'invalid'; exit;
			// this license is no longer valid
		}
		*/
	}
}

