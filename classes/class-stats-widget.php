<?php
/**
 * WordPress Custom Dashboard Class extension.
 * 
 * Create a Statistics Widget.
 *
 * @package   wp_custom_dashboard
 * @author    Charlie MERLAND <charlie@caercam.org>
 * @license   GPL-3.0
 * @link      http://www.caercam.org/
 * @copyright 2014 Charlie MERLAND
 */

if ( ! class_exists( 'wpcd_stats_widget' ) ) :

	class wpcd_stats_widget extends wpcd_dashboard {

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

			$this->widget_id = 'wpcd_stats_widget';
			$this->widget_name = __( 'Statistics', WPCD_SLUG );
			$this->callback = array( $this, 'dashboard_widget' );
			$this->control_callback = null;
			$this->callback_args = null;
			$this->location = 'side';
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
		 * Render the Widget's Content
		 * 
		 * @since    1.0
		 */
		public function dashboard_widget() {

			$count = (array) wp_count_posts( 'post' );
			$count = array(
				'post'        => $count['publish'],
				'trashed'     => $count['trash'],
				'private'     => $count['private'],
				'draft'       => $count['draft'],
				'total'       => 0,
			);
			$count['total'] = array_sum( $count );
			$count['categories'] = wp_count_terms( 'category' );
			$count['post_tags'] = wp_count_terms( 'post_tag' );

			$links = array();
			$list = array(
				'post' => array(
					'single' => __( 'One post', WPCD_SLUG ),
					'plural' => __( '%d posts', WPCD_SLUG ),
					'empty'  => sprintf( '%s <a href="%s">%s</a>', __( 'No post added yet.', WPCD_SLUG ), admin_url( 'post-new.php?post_status=publish' ), __( 'Add one!', WPCD_SLUG ) ),
					'url'    => admin_url( 'edit.php' ),
					'icon'   => 'dashicons dashicons-admin-post',
					'string' => '<a href="%s">%s</a>'
				),
				'trashed' => array(
					'single' => __( 'One trashed post', WPCD_SLUG ),
					'plural' => __( '%d trashed posts', WPCD_SLUG ),
					'empty'  => __( 'No trashed post.', WPCD_SLUG ),
					'url'    => admin_url( 'edit.php?post_status=trash' ),
					'icon'   => 'dashicons dashicons-trash',
					'string' => '<a href="%s">%s</a>'
				),
				'private' => array(
					'single' => __( 'One private post', WPCD_SLUG ),
					'plural' => __( '%d private posts', WPCD_SLUG ),
					'empty'  => __( 'No private post.', WPCD_SLUG ),
					'url'    => admin_url( 'edit.php?post_status=private' ),
					'icon'   => 'dashicons dashicons-lock',
					'string' => '<a href="%s">%s</a>'
				),
				'draft' => array(
					'single' => __( 'One post draft', WPCD_SLUG ),
					'plural' => __( '%d posts drafts', WPCD_SLUG ),
					'empty'  => __( 'No draft', WPCD_SLUG ),
					'url'    => admin_url( 'edit.php?post_status=draft' ),
					'icon'   => 'dashicons dashicons-edit',
					'string' => '<a href="%s">%s</a>'
				),
			);

			foreach ( $list as $status => $data ) {
				if ( isset( $count[ $status ] ) ) {
					$posts = $count[ $status ];
					if ( $posts ) {
						$plural = ( 1 < $posts ? sprintf( $data['plural'], $posts ) : $data['single'] );
						$link = sprintf( $data['string'], $data['url'], $plural, $posts );
					}
					else
						$link = $data['empty'];

					$links[] = '<li><span class="' . $data['icon'] . '"></span> ' . $link . '</li>';

				}
			}

			$links = implode( '', $links );

			include( WPCD_PATH . '/views/dashboard-statistics.php' );
		}

	}

endif;