<?php
/**
 * WordPress Custom Dashboard Class extension.
 * 
 * Create a Latest Posts Widget.
 *
 * @package   wp_custom_dashboard
 * @author    Charlie MERLAND <charlie@caercam.org>
 * @license   GPL-3.0
 * @link      http://www.caercam.org/
 * @copyright 2014 Charlie MERLAND
 */

if ( ! class_exists( 'wpcd_latest_posts_widget' ) ) :

	class wpcd_latest_posts_widget extends wpcd_dashboard {

		/**
		 * Widget ID
		 * 
		 * @since    1.0
		 * 
		 * @var      string
		 */
		protected $widget_id = '';

		/**
		 * Widget Name.
		 * 
		 * @since    1.0
		 * 
		 * @var      string
		 */
		protected $widget_name = '';

		/**
		 * Widget callback method.
		 * 
		 * @since    1.0
		 * 
		 * @var      array
		 */
		protected $callback = null;

		/**
		 * Widget Controls callback method.
		 * 
		 * @since    1.0
		 * 
		 * @var      array
		 */
		protected $control_callback = null;

		/**
		 * Widget callback method arguments.
		 * 
		 * @since    1.0
		 * 
		 * @var      array
		 */
		protected $callback_args = null;

		/**
		 * Constructor
		 *
		 * @since   1.0
		 */
		public function __construct() {

			$this->init();
			$this->register_hook_callbacks();
		}

		/**
		 * Initializes variables
		 *
		 * @since    1.0
		 */
		public function init() {

			// Required to avoid "undefined function get_userdata()" fatal error
			if ( ! function_exists( 'get_userdata' ) )
				require_once ( ABSPATH . 'wp-includes/pluggable.php' );

			$this->widget_id = 'wpcd_latest_posts_widget';
			$this->widget_name = __( 'Latest Posts', WPCD_SLUG );
			$this->callback = array( $this, 'dashboard_widget' );
			$this->control_callback = array( $this, 'widget_handle' );
			$this->callback_args = array( 'id' => $this->widget_id );
			$this->location = 'normal';

			$this->default_settings = array(
				'posts_per_page'  => 4,
				'show_date'       => 1,
				'show_comments'   => 1,
				'show_author'     => 1,
				'show_taxonomies' => 1,
				'show_more'       => 1
			);
			$this->settings = $this->widget_settings();
		}

		/**
		 * Register callbacks for actions and filters
		 * 
		 * @since    1.0
		 */
		public function register_hook_callbacks() {

			add_action( 'wpcd_dashboard_setup', array( $this, '_add_dashboard_widget' ), 10 );
		}

		/**
		 * Register the Widget
		 * 
		 * @since    1.0
		 */
		public function _add_dashboard_widget() {

			$this->add_dashboard_widget( $this->widget_id, $this->widget_name, $this->callback, $this->control_callback, $this->callback_args, $this->location );
		}

		/**
		 * Widget Settings. Get the stored Widget Settings if existing,
		 * save default settings if none.
		 * 
		 * @since    1.0.0
		 * 
		 * @return   array    Widget Settings.
		 */
		private function widget_settings() {

			$widget_id = $this->widget_id;
			$defaults = $this->default_settings;
			$settings = get_user_option( $widget_id . '_settings', get_current_user_id() );

			if ( ! $settings ) {
				update_user_option( get_current_user_id(), $widget_id . '_settings', $defaults );
				$settings = $defaults;
			}
			else
				$settings = wp_parse_args( $settings, $defaults );

			return $settings;
		}

		/**
		 * Update Widget settings when config form is posted.
		 * 
		 * @since    1.0.0
		 */
		private function update_settings() {

			check_admin_referer( "save-{$this->widget_id}" );

			$settings = get_user_option( $this->widget_id . '_settings' );
			$_settings = array();

			foreach ( $this->default_settings as $key => $value ) {
				if ( ! isset( $_POST[ $this->widget_id ][ $key ] ) )
					$_settings[ $key ] = 0;
				else
					$_settings[ $key ] = $_POST[ $this->widget_id ][ $key ];
			}

			$settings = wp_parse_args( $_settings, $settings );
			$update = update_user_option( get_current_user_id(), $this->widget_id . '_settings', $settings );

			if ( $update )
				$this->settings = $settings;
		}

		/**
		 * Prepare and include the Widget's content. Get and apply
		 * settings.
		 * 
		 * @since    1.0.0
		 * 
		 * @param    int    $limit Number of posts to show
		 * @param    int    $offset Starting after n posts
		 */
		public function widget_content( $limit = null, $offset = 0 ) {

			global $post;

			$latest_posts = $this->get_widget_content( $limit, $offset );
			$settings = $this->settings;

			include( WPCD_PATH . 'views/dashboard-latest-posts.php' );
		}

		/**
		 * Retrieve and prepare the posts to display in the Widget.
		 * 
		 * @since    1.0.0
		 * 
		 * @param    int    $limit How many posts to get
		 * @param    int    $limit Offset to select posts
		 * 
		 * @return   array    Requested Movies.
		 */
		private function get_widget_content( $limit = null, $offset = 0 ) {

			if ( is_null( $limit ) )
				$limit = $this->settings['posts_per_page'];

			$offset = intval( esc_attr( $offset ) );

			$latest_posts = get_posts( "posts_per_page=$limit&offset=$offset" );

			return $latest_posts;
		}

		/**
		 * Render the Widget's Content
		 * 
		 * @since    1.0
		 */
		public function dashboard_widget() {

			if ( isset( $_POST[ $this->widget_id ] ) )
				$this->update_settings();

			$editing = false;
			$offset = false;

			$this->widget_content();

			$settings = $this->settings;
			$widget = $this;

			include( WPCD_PATH . 'views/dashboard-latest-posts-admin.php' );

			$this->get_widget_content();
		}

		/**
		 * Widget's configuration callback
		 * 
		 * @since    1.0.0
		 * 
		 * @param    string    $context box context
		 * @param    mixed     $object gets passed to the box callback function as first parameter
		 */
		public function widget_handle( $context, $object ) {

			$settings = $this->settings;
			$editing = ( isset( $_GET['edit'] ) && $object['id'] == $_GET['edit'] );
			$widget = $this;

			if ( $editing && ( ! current_user_can( 'edit_dashboard' ) || ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], "edit_{$this->widget_id}" ) ) ) ) {
				printf( '%s <a href="%s">%s</a>', __( 'You are not allowed to edit this item.' ), admin_url( '/admin.php?page=wpcd_dashboard' ), __( 'Go back' ) );
				return false;
			}

			include( WPCD_PATH . 'views/dashboard-latest-posts-admin.php' );
		}

	}

endif;