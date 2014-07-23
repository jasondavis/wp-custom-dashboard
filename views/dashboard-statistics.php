
							<div class="main">
								<p><?php _e( 'Here\'s some statistics about your blog:', WPCD_SLUG ) ?></p>
								<ul>
									<?php echo $links ?>
								</ul>
								<p><?php
								printf(
									__( 'All combined you have a total of %s regrouped in %s and %s.', WPCD_SLUG ),
									sprintf( '<a href="%s">%s</a>', admin_url( 'edit.php?post_type=post' ), sprintf( _n( 'one post', '%s posts', $count['total'], WPCD_SLUG ), '<strong>' . $count['total'] . '</strong>' ) ),
									sprintf( '<a href="%s">%s</a>', admin_url( 'edit-tags.php?taxonomy=category&post_type=post' ), sprintf( _n( 'one category', '%s categories', $count['categories'], WPCD_SLUG ), '<strong>' . $count['categories'] . '</strong>' ) ),
									sprintf( '<a href="%s">%s</a>', admin_url( 'edit-tags.php?taxonomy=post_tag&post_type=post' ), sprintf( _n( 'one tag', '%s tags', $count['post_tags'], WPCD_SLUG ), '<strong>' . $count['post_tags'] . '</strong>' ) )
								) ?></p>
							</div>
