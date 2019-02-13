<?php
start_session_if_needed();
get_header();
?>
	<div class="grid-x">

		<div class="large-8 medium-8 cell" role="main" id="maincol">
			<?php get_template_part( 'template-parts/page/content', 'page' ); ?>
		</div>

		<?php get_sidebar(); ?>

	</div>
<?php
get_footer();
