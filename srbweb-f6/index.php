<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * For site home page, create front-page.php template. If displaying
 * latest posts on site home page, home.php template can be used, while
 * front-page.php template can be used for latest posts or static page.
 * If it exists, front-page.php will take precedence over home.php.
 */

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
