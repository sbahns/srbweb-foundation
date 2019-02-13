<div class="grid-x grid-padding-x small-up-2 medium-up-3 large-up-6">
	<div class="cell">
		<?php echo do_shortcode('[get_post_by_name page_title="Social Icons" post_type="uc" autop="no"]');?>
		<?php wp_nav_menu( array(
			'theme_location' => 'footer-column-1',
			'container' => false,
			'depth' => 0,
			'items_wrap' => '<ul class="footer-menu footer-column-1">%3$s</ul>',
			'after' => '',
			'fallback_cb' => 'srbweb_menu_fallback', // workaround to show a message to set up a menu
			'walker' => new srbweb_walker( array(
				'in_top_bar' => true,
				'item_type' => 'li',
				'menu_type' => 'footer-column-1-menu'
			) ),
		) );
		?>
		<?php echo do_shortcode('[get_post_by_name page_title="Footer App Icons" post_type="uc" autop="no"]');?>
	</div>
	<div class="cell">
		<?php
		wp_nav_menu( array(
			'theme_location' => 'footer-column-2',
			'container' => false,
			'depth' => 0,
			'items_wrap' => '<ul class="footer-menu footer-column-2">%3$s</ul>',
			'after' => '',
			'fallback_cb' => 'srbweb_menu_fallback', // workaround to show a message to set up a menu
			'walker' => new srbweb_walker( array(
				'in_top_bar' => true,
				'item_type' => 'li',
				'menu_type' => 'footer-column-2-menu'
			) ),
		) );
		?>
	</div>
	<div class="cell">
		<?php
		wp_nav_menu( array(
			'theme_location' => 'footer-column-3',
			'container' => false,
			'depth' => 0,
			'items_wrap' => '<ul class="footer-menu footer-column-3">%3$s</ul>',
			'after' => '',
			'fallback_cb' => 'srbweb_menu_fallback', // workaround to show a message to set up a menu
			'walker' => new srbweb_walker( array(
				'in_top_bar' => true,
				'item_type' => 'li',
				'menu_type' => 'footer-column-3-menu'
			) ),
		) );
		?>
	</div>
	<div class="cell">
		<?php
		wp_nav_menu( array(
			'theme_location' => 'footer-column-4',
			'container' => false,
			'depth' => 0,
			'items_wrap' => '<ul class="footer-menu footer-column-4">%3$s</ul>',
			'after' => '',
			'fallback_cb' => 'srbweb_menu_fallback', // workaround to show a message to set up a menu
			'walker' => new srbweb_walker( array(
				'in_top_bar' => true,
				'item_type' => 'li',
				'menu_type' => 'footer-column-4-menu'
			) ),
		) );
		?>
	</div>
	<div class="cell">


		<?php
		wp_nav_menu( array(
			'theme_location' => 'footer-column-5',
			'container' => false,
			'depth' => 0,
			'items_wrap' => '<ul class="footer-menu footer-column-5">%3$s</ul>',
			'after' => '',
			'fallback_cb' => 'srbweb_menu_fallback', // workaround to show a message to set up a menu
			'walker' => new srbweb_walker( array(
				'in_top_bar' => true,
				'item_type' => 'li',
				'menu_type' => 'footer-column-5-menu'
			) ),
		) );

		?>
	</div>

	<div class="cell">
		<?php
		if ( is_active_sidebar( 'footer-copyright' ) ) {
			?>
			<div id="footer-text" class="grid-x">
				<div class="large-12 cell">
					<?php dynamic_sidebar('footer-copyright');?>
				</div>
			</div>
			<?php
		}
		?>
	</div>
</div>
