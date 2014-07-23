
<?php do_action( 'wpcd_dashboard_setup' ); ?>

	<div id="wpcd-home" class="wrap">

		<h2><?php echo 'WordPress Custom Dashboard'; ?></h2>

		<?php require( WPCD_PATH . 'views/welcome-panel.php' ); ?>

		<div id="dashboard-widgets-wrap">
			<div id="dashboard-widgets" class="metabox-holder">
				<div id="postbox-container-1" class="postbox-container">
					<?php do_meta_boxes( $screen->id, 'normal', '' ); ?>
				</div>
				<div id="postbox-container-2" class="postbox-container">
					<?php do_meta_boxes( $screen->id, 'side', '' ); ?>
				</div>
			</div>
		</div>

	</div>
 
