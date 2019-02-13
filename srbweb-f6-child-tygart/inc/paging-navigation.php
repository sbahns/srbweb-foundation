<?php

// Display navigation to next/previous post

if ( ! function_exists( 'srbweb_child_theme_post_nav' ) ) {
	function srbweb_child_theme_post_nav() {
		global $post;

		// Don't print empty markup if there's nowhere to navigate.
		$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
		$next	= get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous ) {
			return;
		}
		?>
			<nav class="navigation paging-navigation" role="navigation">
				<h2 class="screen-reader-text"><?php _e( 'Posts navigation', 'srbweb-foundation' ); ?></h2>
				<div class="grid-x nav-links">
					<div class="medium-6 cell">
						<div style="text-align:left">

				<?php previous_post_link( '%link', _x( '<span class="meta-nav"><i class="fi-arrow-left"></i></span> %title', 'Previous post link', 'srbweb-foundation' ) ); ?>
			</div>
		</div>
		<div class="medium-6 cell">
			<div style="text-align:right">
				<?php next_post_link( '%link', _x( '%title <span class="meta-nav"><i class="fi-arrow-right"></i></span>', 'Next post link', 'srbweb-foundation' ) ); ?>

			</div>
		</div>
	</div><!-- .nav-links -->
</nav><!-- .navigation -->
		<?php
	}
}

if ( ! function_exists( 'srbweb_issues_paging_nav' ) ) {
	function srbweb_issues_paging_nav($older = "Older Issues", $newer = "Newer Issues") {
		global $wp_query;

		// Don't print empty markup if there's only one page.
		if ( ! get_next_posts_link() && ! get_previous_posts_link() ) {
			return;
		}
		?>
		<div class="grid-x pagination-category">
			<nav class="navigation paging-navigation" role="navigation">
				<h2 class="screen-reader-text"><?php _e( 'Posts navigation', 'srbweb-foundation' ); ?></h2>
				<div class="grid-x nav-links">
					<div class="medium-6 cell">
						<div style="text-align:left">
							<?php if ( get_next_posts_link() ) : ?>
								<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav"><i class="fi-arrow-left"></i></span> ' . $older, 'srbweb-foundation' ) ); ?></div>
							<?php endif; ?>
						</div>
					</div>
					<div class="medium-6 cell">
						<div style="text-align:right">
							<?php if ( get_previous_posts_link() ) : ?>
								<div class="nav-next"><?php previous_posts_link( __( $newer.' <span class="meta-nav"><i class="fi-arrow-right"></i></span>', 'srbweb-foundation' ) ); ?></div>
							<?php endif; ?>
						</div>
					</div>
				</div><!-- .nav-links -->
			</nav><!-- .navigation -->
		</div>
		<?php
	}
}
