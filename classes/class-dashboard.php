<?php
/**
 * WordPress Custom Dashboard Class
 * 
 * @package   wp_custom_dashboard
 * @author    Charlie MERLAND <charlie@caercam.org>
 * @license   GPL-3.0
 * @link      http://www.caercam.org/
 * @copyright 2014 Charlie MERLAND
 */

if ( ! class_exists( 'wpcd_dashboard' ) ) :

	class wpcd_dashboard {

		/**
		 * Dashboard Widgets.
		 * 
		 * @since    1.0
		 * 
		 * @var      array
		 */
		protected $widgets = array();

		/**
		 * Dashboard allowed screen settings.
		 * 
		 * @since    1.0
		 * 
		 * @var      array
		 */
		protected $allowed_settings = array();

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

			$this->widgets = array(
				'wpcd_stats_widget' => new wpcd_stats_widget(),
				'wpcd_latest_posts_widget' => new wpcd_latest_posts_widget()
			);

			$this->allowed_settings = array(
				'welcome_panel' => _x( 'Welcome', 'Welcome panel' ),
				'statistics'    => __( 'Statistics', WPCD_SLUG ),
				'latest_posts'  => __( 'Latest Posts', WPCD_SLUG )
			);
		}

		/**
		 * Register callbacks for actions and filters
		 * 
		 * @since    1.0
		 */
		public function register_hook_callbacks() {

			add_filter( 'set-screen-option', array( $this, 'set_option' ), 10, 3 );
			add_filter( 'screen_settings', array( $this, 'screen_settings' ), 10, 2 );

			add_action( 'wp_ajax_wpcd_save_screen_option', array( $this, 'wpcd_save_screen_option_callback' ) );
			add_action( 'wp_ajax_wpcd_save_dashboard_widget_settings', array( $this, 'wpcd_save_dashboard_widget_settings_callback' ) );
			add_action( 'wp_ajax_wpcd_load_more_movies', array( $this, 'wpcd_load_more_movies_callback' ) );
		}

		/**
		 * AJAX Callback to update the plugin screen options.
		 * 
		 * 
		 * 
		 * @since     1.0.0
		 */
		public function wpcd_save_screen_option_callback() {

			check_ajax_referer( 'screen-options-nonce', 'screenoptionnonce' );

			$screen_id = ( isset( $_POST['screenid'] ) && '' != $_POST['screenid'] ? $_POST['screenid'] : null );
			$visible = ( isset( $_POST['visible'] ) && in_array( $_POST['visible'], array( '0', '1' ) ) ? $_POST['visible'] : '0' );
			$option = ( isset( $_POST['option'] ) && '' != $_POST['option'] ? $_POST['option'] : null );

			if ( is_null( $screen_id ) || is_null( $option ) || ! in_array( $option, $this->allowed_settings ) )
				wp_die( 0 );

			$update = $this->save_screen_option( $option, $visible, $screen_id );

			wp_die( $update );
		}

		/**
		 * AJAX Callback to update the plugin Widgets settings.
		 * 
		 * 
		 * 
		 * @since     1.0.0
		 */
		public function wpcd_save_dashboard_widget_settings_callback() {

			$widget = ( isset( $_POST['widget'] ) && '' != $_POST['widget'] ? $_POST['widget'] : null );
			$setting = ( isset( $_POST['setting'] ) && '' != $_POST['setting'] ? $_POST['setting'] : null );
			$value = ( isset( $_POST['value'] ) && '' != $_POST['value'] ? $_POST['value'] : null );

			if ( is_null( $widget ) || is_null( $setting ) || is_null( $value ) || ! class_exists( $widget ) )
				wp_die( 0 );

			
			WPML_Utils::check_ajax_referer( 'save-' . strtolower( $widget ) );

			$class = $widget::get_instance();
			$update = $this->save_widget_setting( $class->widget_id, $setting, $value );

			wp_die( $update );
		}

		/**
		 * AJAX Callback to load more movies to the Widget.
		 * 
		 * @since     1.0.0
		 */
		public function wpcd_load_more_movies_callback() {

			WPML_Utils::check_ajax_referer( 'load-more-widget-movies' );

			$widget = ( isset( $_GET['widget'] ) && '' != $_GET['widget'] ? $_GET['widget'] : null );
			$offset = ( isset( $_GET['offset'] ) && '' != $_GET['offset'] ? $_GET['offset'] : 0 );
			$limit  = ( isset( $_GET['limit'] ) && '' != $_GET['limit'] ? $_GET['limit'] : null );

			if ( is_null( $widget ) || ! class_exists( $widget ) )
				wp_die( 0 );

			$class = $widget::get_instance();
			$class->get_widget_content( $limit, $offset );
			wp_die();
		}

		/**
		 * Save plugin Welcome Panel screen option.
		 *
		 * @since    1.0.0
		 * 
		 * @param    bool|int    $status Screen option value. Default false to skip.
		 * @param    string      $option The option name.
		 * @param    int         $value The number of rows to use.
		 * 
		 * @return   bool|string
		 */
		public function set_option( $status, $option, $value ) {

			if ( in_array( $option, $this->allowed_settings ) )
				return $value;
		}

		/**
		 * Show plugin Welcome panel screen option form.
		 *
		 * @since    1.0.0
		 * 
		 * @param    string    $status Screen settings markup.
		 * @param    object    WP_Screen object.
		 * 
		 * @return   string    Updated screen settings
		 */
		public function screen_settings( $status, $args ) {

			if ( $args->base != 'toplevel_page_wpcd_dashboard' )
				return $status;

			$user_id = get_current_user_id();
			$hidden = get_user_option( 'metaboxhidden_' . $args->base );

			if ( ! is_array( $hidden ) )
				update_user_option( $user_id, 'metaboxhidden_' . $args->base, array(), true );

			$return = array( '<h5>' . __( 'Show on screen' ) . '</h5>' );

			foreach ( $this->allowed_settings as $slug => $title )
				$return[] = $this->set_screen_option( $slug, $title, $status );

			$return[] = get_submit_button( __( 'Apply', WPCD_SLUG ), 'button hide-if-js', 'screen-options-apply', false );

			$return = implode( '', $return );

			return $return;
		}

		/**
		 * Generate and render screen option.
		 *
		 * @since    1.0.0
		 * 
		 * @param    string    $option Screen option ID.
		 * @param    string    $title Screen option title.
		 * @param    string    $status Screen setting markup.
		 * 
		 * @return   string    Updated screen settings
		 */
		private function set_screen_option( $option, $title, $status ) {

			if ( ! in_array( $option, array_keys( $this->allowed_settings ) ) )
				return $status;

			$hidden = get_user_option( 'metaboxhidden_' . get_current_screen()->id );
			$visible = ( in_array( 'wpcd_dashboard_' . $option . '_widget', $hidden ) ? '0' : '1' );

			$return = $status . '<label for="show_wpcd_' . $option . '"><input id="show_wpcd_' . $option . '" type="checkbox"' . checked( $visible, '1', false ) . ' />' . __( $title, WPCD_SLUG ) . '</label>';
			
			return $return;
		}

		/**
		 * Save Widgets screen options. This is used to init the screen
		 * options if they don't exist yet.
		 *
		 * @since    1.0
		 * 
		 * @return   array    List of hidden Widgets ID
		 */
		private function save_screen_settings() {

			$edited  = false;
			$user_id = get_current_user_id();
			$screen  = get_current_screen();
			$hidden  = get_user_option( 'metaboxhidden_' . $screen->id );

			return $hidden;
		}

		/**
		 * Save a single Widget screen options.  This is used to save
		 * the options through AJAX.
		 *
		 * @since    1.0.0
		 * 
		 * @param    string    $option Screen setting ID.
		 * @param    string    $value Screen setting value.
		 * @param    string    $value Screen ID.
		 * 
		 * @return   int       Update status for JSON: 1 on success, 0 on failure.
		 */
		private function save_screen_option( $option, $value, $screen_id ) {

			$user_id = get_current_user_id();
			$hidden = get_user_option( 'metaboxhidden_' . $screen_id );
			$hidden = ( is_array( $hidden ) ? $hidden : array() );
			$option = 'wpcd_dashboard_' . $option . '_widget';

			$_option = array_search( $option, $hidden );

			if ( '0' == $value && ! $_option )
				$hidden[] = $option;
			else if ( '0' == $value && ! isset( $hidden[ $_option ] ) )
				$hidden[] = $option;
			else if ( '1' == $value && isset( $hidden[ $_option ] ) )
				unset( $hidden[ $_option ] );

			$hidden = array_unique( $hidden );

			$update = update_user_option( $user_id, 'metaboxhidden_' . $screen_id, $hidden, true );
			$update = ( true === $update ? 1 : 0 );

			return $update;
		}

		/**
		 * Save a plugin Dashboard Widget setting.
		 * 
		 * @since    1.0.0
		 * 
		 * @param    string    $widget_id Widget ID
		 * @param    string    $setting Setting name
		 * @param    string    $value Setting value
		 * 
		 * @return   boolean   Update status, success or failure
		 */
		private function save_widget_setting( $widget_id, $setting, $value ) {

			$settings = get_user_option( $widget_id . '_settings' );

			if ( ! $settings ) {
				update_user_option( get_current_user_id(), $widget_id . '_settings', array() );
				$settings = $defaults;
			}

			$settings[ $setting ] = esc_attr( $value );
			$update = update_user_option( get_current_user_id(), $widget_id . '_settings', $settings );

			return $update;
		}

		/**
		 * Render the Dashboard
		 * 
		 * @since    1.0
		 */
		public function dashboard() {

			$hidden = $this->save_screen_settings();
			if ( ! $hidden )
				$hidden = array();

			if ( ! isset( $screen ) )
				$screen = get_current_screen();

			require_once( WPCD_PATH . '/views/dashboard.php' );
		}
 
		/**
		 * Adds a new widget to the Plugin's Dashboard.
		 * 
		 * @since    1.0.0
		 * 
		 * @param    int       $widget_id Identifying slug for the widget. This will be used as its css class and its key in the array of widgets.
		 * @param    string    $widget_name Name the widget will display in its heading.
		 * @param    array     $callback Method that will display the actual contents of the widget.
		 * @param    array     $control_callback Method that will handle submission of widget options (configuration) forms, and will also display the form elements.
		 */
		public function add_dashboard_widget( $widget_id, $widget_name, $callback, $control_callback = null, $callback_args = null, $location = 'normal' ) {

			global $wp_dashboard_control_callbacks;

			$widget_name = __( $widget_name, WPCD_SLUG );

			if ( ! is_null( $control_callback ) && current_user_can( 'edit_dashboard' ) && is_callable( $control_callback ) ) {

				$wp_dashboard_control_callbacks[ $widget_id ] = $control_callback;
				$widget_name = __( $widget_name, WPCD_SLUG );

				if ( isset( $_GET['edit'] ) && $widget_id == $_GET['edit'] ) {
					list( $url ) = explode( '#', add_query_arg( 'edit', false ), 2 );
					$widget_name .= ' <span class="postbox-title-action"><a href="' . esc_url( $url ) . '" class="edit-box close-box"><span class="hide-if-js">' . __( 'Cancel' ) . '</span><span class="hide-if-no-js">' . __( 'Close' ) . '</span></a></span>';
					$callback = $control_callback;
				}
				else {
					list( $url ) = explode( '#', add_query_arg( 'edit', $widget_id ), 2 );
					$widget_name .= ' <span class="postbox-title-action"><a href="' . wp_nonce_url( "$url#$widget_id", "edit_$widget_id" ) . '" class="edit-box open-box">' . __( 'Configure' ) . '</a></span>';
					$widget_name .= ' <span class="postbox-title-action"><a href="' . esc_url( $url ) . '" class="edit-box close-box hide-if-no-js hide-if-js"><span class="hide-if-js">' . __( 'Cancel' ) . '</span><span class="hide-if-no-js">' . __( 'Close' ) . '</span></a></span>';
				}
			}

			$screen = get_current_screen();

			if ( 'side' != $location )
				$location = 'normal';

			$priority = 'core';

			add_meta_box( $widget_id, $widget_name, $callback, $screen, $location, $priority, $callback_args );

		}
	}

endif;