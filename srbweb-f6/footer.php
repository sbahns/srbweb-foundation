</div> <!-- .grid-container-->

	<?php
	get_template_part( 'template-parts/modal', 'login' );

	?>
	<div id="footer" class="grid-container grid-container-padded footer-wrap">
		<footer class="site-footer" role="contentinfo">
			<div class="grid-x">
				<div class="small-10 cell small-centered">
					<?php
					if ( has_nav_menu( 'footer' ) ) {
						wp_nav_menu( array(
						    'theme_location' => 'footer',
						    'container' => false,
						    'depth' => 0,
						    'items_wrap' => '<ul class="footer-menu">%3$s</ul>',
						    'after' => '',
						    'fallback_cb' => 'srbweb_menu_fallback', // workaround to show a message to set up a menu
						    'walker' => new srbweb_walker( array(
						        'in_top_bar' => true,
						        'item_type' => 'li',
						        'menu_type' => 'main-menu'
						    ) ),
						) );
					}
					?>
				</div>
			</div>

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
		</footer>
	</div> <!-- end div.site-wrap.footer-wrap -->
</section> <!-- end section#page -->

<?php
wp_footer();
?>

</body>
</html>
