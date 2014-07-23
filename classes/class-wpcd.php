<?php
/**
 * WordPress Custom Dashboard
 * 
 * Build a custom dashboard to use in your plugins. This plugin creates a basic
 * Custom Dashboard with a few Widgets to show what you can do with a little bit
 * of imagination and a few knowledge of WordPress' Admin routine.
 *
 * @package   wp_custom_dashboard
 * @author    Charlie MERLAND <charlie@caercam.org>
 * @license   GPL-3.0
 * @link      http://www.caercam.org/
 * @copyright 2014 Charlie MERLAND
 */

if ( ! class_exists( 'wp_custom_dashboard' ) ) :

	/**
	* Plugin class
	*
	* @package wp_custom_dashboard
	* @author  Charlie MERLAND <charlie@caercam.org>
	*/
	class wp_custom_dashboard {

		/**
		 * Initialize the plugin by setting localization and loading public scripts
		 * and styles.
		 *
		 * @since     1.0.0
		 */
		public function __construct() {

			$this->init();
			$this->register_hook_callbacks();
		}

		/**
		 * Init
		 * 
		 * @since    1.0
		 */
		public function init() {

			$this->dashboard = new wpcd_dashboard();
		}

		/**
		 * Register callbacks for actions and filters
		 * 
		 * @since    1.0
		 */
		public function register_hook_callbacks() {

			// Add the options page and menu item.
			add_action( 'admin_menu', array( $this, 'admin_menu' ), 10 );

			// Enqueue admin scripts and styles
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10, 1 );
		}

		/**
		 * Register and enqueue admin styles
		 *
		 * @since    1.0
		 * 
		 * @param    string    $hook Current page's hook
		 */
		public function admin_enqueue_styles( $hook ) {

			if ( false !== strpos( $hook, 'wpcd_dashboard' ) )
				wp_enqueue_style( WPCD_SLUG . '-css', WPCD_URL . '/assets/css/admin.css', array(), WPCD_VERSION );
		}

		/**
		 * Register and enqueue admin scripts
		 *
		 * @since    1.0
		 * 
		 * @param    string    $hook Current page's hook
		 */
		public function admin_enqueue_scripts( $hook ) {

			if ( false !== strpos( 'wpcd_dashboard', $hook ) )
				wp_enqueue_script( WPCD_SLUG . '-js', WPCD_URL . '/assets/js/admin.js', array( 'jquery', 'jquery-ui-tabs' ), WPCD_VERSION, true );
		}

		/**
		 * Register the administration menu for this plugin into the WordPress
		 * Dashboard menu.
		 *
		 * @since    1.0
		 */
		public function admin_menu() {

			add_menu_page(
				$page_title = 'My Dashboard',
				$menu_title = 'My Dashboard',
				$capability = 'manage_options',
				$menu_slug = 'wpcd_dashboard',
				$function = null,
				$icon_url = 'dashicons-dashboard',
				$position = 100
			);

			add_submenu_page(
				'wpcd_dashboard',
				'My Dashboard',
				'My Dashboard',
				'manage_options',
				'wpcd_dashboard',
				array( $this->dashboard, 'dashboard' )
			);
		}

	}
endif;