
							<div class="main">
<?php
	if ( ! empty( $latest_posts ) ) :
		foreach ( $latest_posts as $latest ) :
			$post = $latest;
			setup_postdata( $post );
?>
								<div id="post-<?php the_ID(); ?>" class="post wpcd-post">
									<h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
									<div class="entry-meta">
<?php if ( '1' == $settings['show_date'] ) : ?>
										<span class="post-date"><?php _e( 'Posted', WPCD_SLUG ); ?> <?php the_date(); ?></span>
<?php endif; if ( '1' == $settings['show_author'] ) : ?>
										<span class="post-author"><?php _e( 'by', WPCD_SLUG ); ?> <?php the_author(); ?></span>
<?php endif; if ( '1' == $settings['show_taxonomies'] ) : ?>
										<span class="post-caterogies"><?php _e( 'in', WPCD_SLUG ); ?> <?php the_category(); ?></span>
<?php endif; if ( '1' == $settings['show_comments'] ) : ?>
										<span class="post-caterogies"> − <?php comments_number( __( 'no response', WPCD_SLUG ), __( 'one response', WPCD_SLUG ), __( '% responses', WPCD_SLUG ) ) ?></span>
<?php endif; ?>
									</div>
									<div class="entry-excerpt"><?php the_excerpt(); ?></div>
									<div class="read-more"><a href="<?php the_permalink(); ?>"><?php _e( 'Read more &raquo;' ); ?></a></div>
								</div>
<?php
		endforeach;
		wp_reset_postdata();
	endif;
?>
							</div>
