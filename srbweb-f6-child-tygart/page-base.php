<?php
/*
Template Name: Page Base with Modules
*/
start_session_if_needed();
get_header();

$has_slider = get_field( 'has_slider' );
$add_slider = get_field( 'add_slider' );

$add_customers_section = get_field( 'add_customers_section' );
$hide_page_content = get_field('hide_page_content');
?>
<section id="page" class="modules">
		<?php if( get_field('has_slider') ) { ?>
			<div class="grid-x slider-section">
					<div class="small-12 cell">
						<?php echo $add_slider; ?>
					</div>
			</div>
		<?php } ?>
		<?php get_template_part( 'template-parts/modules/icon', 'grid' );?>


			<div class="grid-x grid-padding-y white-background add-25">
				<div class="grid-container">
					<div class="small-12 cell">
				<?php
					while ( have_posts() ) {
							the_post();
							?>
							<p class="postmeta-comment alignright"><?php edit_post_link( 'Edit' ); ?></p>
							<h1 class="archive-title"><?php the_title(); ?></h1>

							<?php if( !$hide_page_content ) { ?>
							<article <?php post_class() ?> id="post-<?php the_ID(); ?>">
								<div class="entry content">
									<?php the_content(); ?>
								</div>
							</article>
						<?php
							}
						}
					?>
			</div>
		</div>
	</div>

	<?php get_template_part( 'template-parts/modules/articles', 'section' );?>

			<?php if( $add_customers_section ) {
				 echo do_shortcode('[get_post_by_name page_title="Our Customers" post_type="uc"]');
				} ?>
</section>
<?php
get_footer();
