
							<div id="wpml-latest-posts-widget-config"<?php if ( ! $editing ) echo ' class="main-config"'; ?>>
								<form method="post" action="<?php echo admin_url( "admin.php?page=wpcd_dashboard#{$widget->widget_id}" ) ?>">
									<?php wp_nonce_field( "save-{$widget->widget_id}", "save_{$widget->widget_id}" ) ?>
									<?php wp_nonce_field( 'load-more-posts', 'load-more-posts' ) ?>
									<table class="wp-list-table">
										<tbody>
											<tr>
												<td colspan="4">
													<em><?php _e( 'Use the following options to customize this Widget.', WPCD_SLUG ) ?></em>
												</td>
											</tr>
											<tr>
												<td style="vertical-align:top;width:25%">
													<label><strong><?php _e( 'Number of posts:', WPCD_SLUG ) ?></strong>
													<br /><input step="1" min="1" max="999" class="screen-per-page" name="<?php echo $widget->widget_id ?>[posts_per_page]" id="latest_posts_posts_per_page" maxlength="3" value="<?php echo $settings['posts_per_page'] ?>" type="number" /> <?php _e( 'posts', WPCD_SLUG ) ?></label>
												</td>
												<td style="vertical-align:top;width:25%">
													<label><input id="latest_posts_show_date" name="<?php echo $widget->widget_id ?>[show_date]"<?php checked( $settings['show_date'], '1' ) ?> type="checkbox" value="1" /> <strong><?php _e( 'Show date', WPCD_SLUG ) ?></strong></label><br />
													<em><?php _e( 'Show post date', WPCD_SLUG ) ?></em><br />
													<label><input id="latest_posts_show_author" name="<?php echo $widget->widget_id ?>[show_author]"<?php checked( $settings['show_author'], '1' ) ?> type="checkbox" value="1" /> <strong><?php _e( 'Show author', WPCD_SLUG ) ?></strong></label><br />
													<em><?php _e( 'Show post author', WPCD_SLUG ) ?></em>
												</td>
												<td style="vertical-align:top;width:25%">
													<label><input id="latest_posts_show_comments" name="<?php echo $widget->widget_id ?>[show_comments]"<?php checked( $settings['show_comments'], '1' ) ?> type="checkbox" value="1" /> <strong><?php _e( 'Show comments', WPCD_SLUG ) ?></strong></label><br />
													<em><?php _e( 'Show post comments number.', WPCD_SLUG ) ?></em><br />
													<label><input id="latest_posts_show_taxonomies" name="<?php echo $widget->widget_id ?>[show_taxonomies]"<?php checked( $settings['show_taxonomies'], '1' ) ?> type="checkbox" value="1" /> <strong><?php _e( 'Show taxonomies', WPCD_SLUG ) ?></strong></label><br />
													<em><?php _e( 'Show post taxonomies', WPCD_SLUG ) ?></em>
												</td>
												<td style="vertical-align:top;width:25%">
													<label><input id="latest_posts_show_more" name="<?php echo $widget->widget_id ?>[show_more]"<?php checked( $settings['show_more'], '1' ) ?> type="checkbox" value="1" /> <strong><?php _e( 'Show "Load more" button', WPCD_SLUG ) ?></strong></label><br />
													<em><?php _e( 'Show a button to load more posts to the Widget.', WPCD_SLUG ) ?></em> <em class="hide-if-js"><?php _e( 'JavaScript required', WPCD_SLUG ) ?></em><br />
												</td>
											</tr>
											<tr>
												<td colspan="4" style="text-align:right">
													<hr />
													<input type="submit" name="save" id="<?php echo "save_{$widget->widget_id}" ?>" class="button button-primary hide-if-js" value="<?php _e( 'Save' ) ?>" />
												</td>
											</tr>
										</tbody>
									</table>
								</form>
							</div>