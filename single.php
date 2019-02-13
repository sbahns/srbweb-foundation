<?php
get_header();
?>

	<div class="small-12 medium-8 large-8 cell" id="maincol">
   		<div class="grid-x">
	   		<?php get_template_part( 'template-parts/loop', 'single' ); ?>
   		</div>
	</div>

<?php
get_sidebar();
get_footer();
?>
