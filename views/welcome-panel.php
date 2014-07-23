<?php
/* 
 * Custom Welcome Panel
 * 
 * You can edit pretty much anything you want, but you may want to respect the
 * basic HTML structure to keep 
 * 
 * 
 */

$style = '';
if ( in_array( 'wpcd_dashboard_welcome_panel_widget', $hidden ) )
	$style = ' class="hidden hide-if-js"';

$nonce = wp_nonce_url( admin_url( 'admin.php?page=wpcd_dashboard&amp;show_wpcd_welcome_panel=1' ), 'show-wpcd-welcome-panel', 'show_wpcd_welcome_panel_nonce' );

?>
		<div id="wpcd_dashboard_welcome_panel_widget"<?php echo $style; ?>>
			<div id="wpcd-welcome-panel" class="welcome-panel">
				<a id="wpcd-welcome-panel-close" href="<?php echo $nonce ?>" class="welcome-panel-close"><span class="dashicons dashicons-dismiss"></span><?php _e( 'Dismiss' ); ?></a>
				<div class="welcome-panel-content">
					<h3><?php _e( 'Welcome to your Custom WordPress Dashboard!', WPCD_SLUG ); ?></h3>
					<p class="about-description">
						<?php _e( 'Most of the links below are dummy, but that\'s to show you what you can do with a simple Welcome Panel like the classic WordPress Dashboard\'s one.', WPCD_SLUG ); ?>
					</p>
					<div class="welcome-panel-column-container">
						<div class="welcome-panel-column">
							<h4><?php _e( 'Get Started', WPCD_SLUG ); ?></h4>
							<a class="button button-primary button-hero" href="#"><?php _e( 'Call to action', WPCD_SLUG ); ?></a>
							<p><?php _e( 'and some more descriptive text too.', WPCD_SLUG ) ?></p>
						</div>

						<div class="welcome-panel-column">
							<h4><?php _e( 'A few important links', WPCD_SLUG ); ?></h4>
							<ul>
								<li><span class="dashicons dashicons-welcome-write-blog"></span><a href="<?php echo admin_url( 'post-new.php' ); ?>"><?php _e( 'Write your first blog post' ); ?></a></li>
								<li><span class="dashicons dashicons-list-view"></span><a href="<?php echo admin_url( 'post-new.php?post_type=page' ); ?>"><?php _e( 'Add an About page' ); ?></a></li>
								<li><span class="dashicons dashicons-format-video"></span><a href="<?php echo home_url(); ?>"><?php _e( 'View your site' ); ?></a></li>
							</ul>
						</div>

						<div class="welcome-panel-column">
							<h4><?php _e( 'Furthermore', WPCD_SLUG ); ?></h4>
							<ul>
								<li><span class="dashicons dashicons-category"></span><a href="<?php echo admin_url( 'edit-tags.php?taxonomy=category' ); ?>"><?php _e( 'Edit Categories', WPCD_SLUG ); ?></a></li>
								<li><span class="dashicons dashicons-tag"></span><a href="<?php echo admin_url( 'edit-tags.php?taxonomy=post_tag' ); ?>"><?php _e( 'Edit Tags', WPCD_SLUG ); ?></a></li>
								<li><span class="dashicons dashicons-admin-settings"></span><a href="<?php echo admin_url( 'options-general.php' ); ?>"><?php _e( 'Settings', WPCD_SLUG ); ?></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>