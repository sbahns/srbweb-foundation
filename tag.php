<?php get_header(); ?>

<div class="small-12 large-8 medium-8 cell" id="maincol">
	<div class="headline">
		<h2 class="category-headline">Tag: <?php single_tag_title(); ?></h2>
	</div>
	<div class="tag-description"><?php echo tag_description(); ?></div>
	<?php	
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			get_template_part( 'template-parts/content', 'tag' );
		}
	}
	?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
