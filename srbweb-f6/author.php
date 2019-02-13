<?php $author = get_userdata( get_query_var('author') );?>
<?php get_header(); ?>

<div class="grid-x" role="main">
	<div class="large-8 medium-8 cell" id="maincol">
		<h1 class="blogheadline daily">About the Author</h1>
		<section class="author-section section-content grid-x">
			<div class="large-2 medium-2 hide-for-small cell">
			<?php //echo get_avatar($author->user_email); ?>
			<?php echo userphoto( $author->ID, $before = '', $after = '', array(style => 'border:0'), $default_src = '', 'thumbnail'); ?>
			</div>
			<div class="large-10 medium-10 cell">
			<h2><?php echo $author->display_name ?></h2>
			<p><?php echo $author->user_description; ?></p>
			</div>
		</section>
		<?php
		if ( have_posts() ) {
		?>
		<h3 class="archive-title author-archive">Articles by <?php echo $author->display_name ?></h3>
		<?php
			while ( have_posts() ) {
				the_post();
				get_template_part( 'template-parts/content', 'author' );
			}
			srbweb_paging_nav();
		} else {
			get_template_part( 'template-parts/content', 'none' );
		}
		?>
	</div>
	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
