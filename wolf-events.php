<?php
/**
 * Plugin Name: Events
 * Plugin URI: https://wlfthm.es/wolf-events
 * Description: A plugin to manage your events.
 * Version: 1.2.3
 * Author: WolfThemes
 * Author URI: https://wolfthemes.com
 * Requires at least: 5.0
 * Tested up to: 5.5
 *
 * Text Domain: wolf-events
 * Domain Path: /languages/
 *
 * @package WolfEvents
 * @category Core
 * @author WolfThemes
 *
 * Verified customers who have purchased a premium theme at https://wlfthm.es/tf/
 * will have access to support for this plugin in the forums
 * https://wlfthm.es/help/
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Wolf_Events' ) ) {
	/**
	 * Main Wolf_Events Class
	 *
	 * Contains the main functions for Wolf_Events
	 *
	 * @class Wolf_Events
	 * @version 1.2.3
	 * @since 1.0.0
	 */
	class Wolf_Events {

		/**
		 * @var string
		 */
		private $required_php_version = '5.5.0';

		/**
		 * @var string
		 */
		public $version = '1.2.3';

		/**
		 * @var Wolf Events The single instance of the class
		 */
		protected static $_instance = null;

		/**
		 * @var string
		 */
		private $update_url = 'http://plugins.wolfthemes.com/update';

		/**
		 * @var the support forum URL
		 */
		private $support_url = 'https://wlfthm.es/help/';

		/**
		 * @var string
		 */
		public $template_url;

		/**
		 * Main Wolf Events Instance
		 *
		 * Ensures only one instance of Wolf Events is loaded or can be loaded.
		 *
		 * @static
		 * @see WE()
		 * @return Wolf Events - Main instance
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Wolf Events Constructor.
		 */
		public function __construct() {

			$this->define_constants();
			$this->includes();
			$this->init_hooks();

			do_action( 'we_loaded' );
		}

		/**
		 * Hook into actions and filters
		 */
		private function init_hooks() {

			add_action( 'after_setup_theme', array( $this, 'include_template_functions' ), 11 );
			add_action( 'init', array( $this, 'init' ), 0 );
			register_activation_hook( __FILE__, array( $this, 'activate' ) );

			add_action( 'admin_init', array( $this, 'plugin_update' ) );
		}

		/**
		 * Add a flag that will allow to flush the rewrite rules when needed.
		 */
		public function activate() {

			add_option( '_wolf_events_needs_page', true );

			if ( ! get_option( '_we_flush_rewrite_rules_flag' ) ) {
				add_option( '_we_flush_rewrite_rules_flag', true );
			}
		}

		/**
		 * Flush rewrite rules on plugin activation to avoid 404 error
		 */
		public function flush_rewrite_rules(){
			if ( get_option( '_we_flush_rewrite_rules_flag' ) ) {
				flush_rewrite_rules();
				delete_option( '_we_flush_rewrite_rules_flag' );
			}
		}

		/**
		 * Define WE Constants
		 */
		private function define_constants() {

			$constants = array(
				'WE_DEV' => false,
				'WE_DIR' => $this->plugin_path(),
				'WE_URI' => $this->plugin_url(),
				'WE_CSS' => $this->plugin_url() . '/assets/css',
				'WE_JS' => $this->plugin_url() . '/assets/js',
				'WE_SLUG' => plugin_basename( dirname( __FILE__ ) ),
				'WE_PATH' => plugin_basename( __FILE__ ),
				'WE_VERSION' => $this->version,
				'WE_SUPPORT_URL' => $this->support_url,
				'WE_DOC_URI' => 'http://docs.wolfthemes.com/documentation/plugins/' . plugin_basename( dirname( __FILE__ ) ),
				'WE_WOLF_DOMAIN' => 'wolfthemes.com',
			);

			foreach ( $constants as $name => $value ) {
				$this->define( $name, $value );
			}
		}

		/**
		 * Define constant if not already set
		 * @param  string $name
		 * @param  string|bool $value
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * What type of request is this?
		 * string $type ajax, frontend or admin
		 * @return bool
		 */
		private function is_request( $type ) {
			switch ( $type ) {
				case 'admin' :
					return is_admin();
				case 'ajax' :
					return defined( 'DOING_AJAX' );
				case 'cron' :
					return defined( 'DOING_CRON' );
				case 'frontend' :
					return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
			}
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 */
		public function includes() {

			/**
			 * Functions used in frontend and admin
			 */
			include_once( 'inc/we-core-functions.php' );
			include_once( 'inc/frontend/we-functions.php' );

			if ( $this->is_request( 'admin' ) ) {
				include_once( 'inc/admin/class-we-admin.php' );
			}

			if ( $this->is_request( 'ajax' ) ) {
				include_once( 'inc/ajax/we-ajax-functions.php' );
			}

			if ( $this->is_request( 'frontend' ) ) {
				include_once( 'inc/frontend/we-template-hooks.php' );
				include_once( 'inc/frontend/class-we-shortcode.php' );
			}
		}

		/**
		 * Function used to Init Wolf Events Template Functions - This makes them pluggable by plugins and themes.
		 */
		public function include_template_functions() {
			include_once( 'inc/frontend/we-template-functions.php' );
		}

		/**
		 * register_widget function.
		 *
		 * @return void
		 */
		public function register_widget() {

			// Include
			//include_once( 'inc/class-we-events-widget.php' );
			include_once( 'inc/class-we-event-list-widget.php' );

			// Register widget
			//register_widget( 'WE_Events_Widget' );
			register_widget( 'WE_Event_List_Widget' );
		}

		/**
		 * Init Wolf Events when WordPress Initialises.
		 */
		public function init() {
			// Before init action
			do_action( 'before_wolf_events_init' );

			// Set up localisation
			$this->load_plugin_textdomain();

			// Variables
			$this->template_url = apply_filters( 'wolf_events_template_url', 'wolf-events/' );

			// Classes/actions loaded for the frontend and for ajax requests
			if ( ! is_admin() || defined( 'DOING_AJAX' ) ) {

				// Hooks
				add_filter( 'template_include', array( $this, 'template_loader' ) );
			}

			// Hooks
			add_action( 'widgets_init', array( $this, 'register_widget' ) );

			$this->register_post_type();
			$this->register_taxonomy();

			$this->flush_rewrite_rules();

			// Init action
			do_action( 'wolf_events_init' );
		}

		/**
		 * Register post type
		 */
		public function register_post_type() {
			include_once( 'inc/we-register-post-type.php' );
		}

		/**
		 * Register taxonomy
		 */
		public function register_taxonomy() {
			include_once( 'inc/we-register-taxonomy.php' );
		}

		/**
		 * Load a template.
		 *
		 * Handles template usage so that we can use our own templates instead of the themes.
		 *
		 * @param mixed $template
		 * @return string
		 */
		public function template_loader( $template ) {

			$find = array();
			$file = '';

			if ( is_single() && 'event' == get_post_type() ) {

				$file    = 'single-event.php';
				$find[] = $file;
				$find[] = $this->template_url . $file;

			} elseif ( is_tax( 'we_artist' ) ) {

				$term = get_queried_object();

				$file   = 'taxonomy-' . $term->taxonomy . '.php';
				$find[] = 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
				$find[] = $this->template_url . 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
				$find[] = $file;
				$find[] = $this->template_url . $file;
			}

			if ( $file ) {
				$template = locate_template( $find );
				if ( ! $template ) $template = $this->plugin_path() . '/templates/' . $file;
			}

			return $template;
		}

		/**
		 * Loads the plugin text domain for translation
		 */
		public function load_plugin_textdomain() {

			$domain = 'wolf-events';
			$locale = apply_filters( 'wolf-events', get_locale(), $domain );
			load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
			load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Get the plugin url.
		 * @return string
		 */
		public function plugin_url() {
			return untrailingslashit( plugins_url( '/', __FILE__ ) );
		}

		/**
		 * Get the plugin path.
		 * @return string
		 */
		public function plugin_path() {
			return untrailingslashit( plugin_dir_path( __FILE__ ) );
		}

		/**
		 * Get the template path.
		 * @return string
		 */
		public function template_path() {
			return apply_filters( 'we_template_path', 'wolf-events/' );
		}

		/**
		 * Plugin update
		 */
		public function plugin_update() {

			if ( ! class_exists( 'WP_GitHub_Updater' ) ) {
				include_once 'inc/admin/updater.php';
			}

			$repo = 'wolfthemes/wolf-events';

			$config = array(
				'slug' => plugin_basename( __FILE__ ),
				'proper_folder_name' => 'wolf-events',
				'api_url' => 'https://api.github.com/repos/' . $repo . '',
				'raw_url' => 'https://raw.github.com/' . $repo . '/master/',
				'github_url' => 'https://github.com/' . $repo . '',
				'zip_url' => 'https://github.com/' . $repo . '/archive/master.zip',
				'sslverify' => true,
				'requires' => '5.0',
				'tested' => '5.5',
				'readme' => 'README.md',
				'access_token' => '',
			);

			new WP_GitHub_Updater( $config );
		}

	} // end class
} // end class check

/**
 * Returns the main instance of WE to prevent the need to use globals.
 *
 * @return Wolf_Events
 */
function WE() {
	return Wolf_Events::instance();
}

WE(); // Go
