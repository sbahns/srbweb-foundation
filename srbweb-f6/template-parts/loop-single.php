<?php
$category = get_the_category();
$cat_name = isset($category[0]) ? $category[0]->slug : '';

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		$post_id = get_the_ID();
		$subheadline = get_post_meta($post_id, 'subheadline', true);

		?>

   <article id="single" class="scalable">
			<div class="entry blog_entry">
        <header>
          	<div class="grid-x entry-header">
            	<div class="small-12 cell">
		          	<p class="home-catname"><?php my_get_lowest_category_link($post_id, false); ?></p>
								<p class="postmeta-comment"><?php  edit_post_link( 'Edit' ); ?></p>
							</div>
						</div>
					<?php if ( !empty($term) && $term->name ) { ?>
						<p class="toc-link">From the <a href="<?php echo $issue_link; ?>"><?php echo $term->name; ?></a> issue.</p>
					<?php } ?>
	          <h2><?php the_title(); ?></h2>
	          	<?php if ( $subheadline ) { ?>
			        <h3 class="subheadline"><?php echo $subheadline; ?></h3>
			        <?php } ?>
	                <div class="grid-x collapse postmetadatatop">
	                	<div class="small-12 medium-4 large-4 cell">
		                	<div class="postmeta-lefttop">
			                	<?php echo isset($posted_by) ? $posted_by : ''; ?> <?php the_author_posts_link(); ?> &#8226; <?php echo get_the_date();?>
		                	</div>
							<div class="commentlink">
								<a href="<?php the_permalink() ?>#comment-on-post" title="Permanent link to <?php the_title_attribute() ?>" rel="bookmark">
									<?php get_comments_number(); comments_number( 'Add Comment', '1 Comment' , 'Read Comments (%) ' ); ?>
								</a>
							</div>
						</div>
						<div class="small-12 medium-8 large-8 cell">
								<div class="social-home">
									<div class="social-maincolumn">
										<div class="grid-x">
											<div class="small-12 cell">
												<?php get_template_part( 'template-parts/social', 'share' );  ?>
											</div>
										</div>
									</div>
								</div>
						</div>
					</div>
		    </header>

				<?php
				if ( has_post_thumbnail() ) {
					?>
					<div class="show-for-medium-up">
						<?php the_post_thumbnail(); ?>
					</div>
					<?php
				}

				the_content();
				?>

			</div>

			<aside class="related hide-on-print">

				<!-- article meta data -->

				<?php
			

				if ( function_exists( 'km_rpbt_related_posts_by_taxonomy_shortcode' ) ) {
					// Related Posts By Taxonomy plugin is active
					echo do_shortcode( '[related_posts_by_tax format="thumbnails" image_size="featured-image-thumbnail" orderby="post_date" order="ASC" taxonomies="category" filter="false" before_title="<h3 class=\'related_post_title\'>" after_title="</h3>" title="' . __( 'Related Posts' ) . '"]' );
				}

				the_tags(__('<p class="tags"><h3 class=\'related_post_title\'>Tags</h3> ', 'srbweb-foundation'), ', ', '.</p>');
				?>

			</aside>


        </article>


        <a class="comment-anchor" id="comment-on-post"></a>

	    <?php
		comments_template( '', true );
	}
} else {
	?>
	<div class="alert-box error">Sorry, no posts matched your criteria.</div>
	<?php
}
