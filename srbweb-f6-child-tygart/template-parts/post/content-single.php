<?php


if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		$post_id = get_the_ID();
		$subheadline = get_post_meta( $post_id, 'subheadline', true );

		?>

    <article id="single" class="scalable">
			<div class="entry blog_entry">
				<header>
          <div class="grid-x">
						<div class="small-9 cell">
							<p class="catname"><?php my_get_lowest_category_link($post->ID, false); ?></p>
						</div>
						<div class="small-3 cell">
							<p class="postmeta-comment alignright"><?php edit_post_link( 'Edit' ); ?></p>
						</div>
					</div>

					<div class="grid-x">
	          <div class="small-12 cell">
			        <h1><?php the_title(); ?></h1>
		         <?php if ( $subheadline ) { ?>
				           <h2 class="subheadline"><?php echo $subheadline; ?></h2>
				     <?php }?>
						</div>
					</div>

					<div class="grid-x">
	          <div class="small-12 cell">
		         <div class="postmeta-lefttop">
								<?php the_author_posts_link();
								 ?> &#8226;
								<?php echo get_the_date();?> <!--&#8226;
								<a href="<?php the_permalink() ?>#comment-on-post" title="Permanent link to <?php the_title_attribute() ?>" rel="bookmark" class="commentlink" id="commentlink">
									<?php get_comments_number(); comments_number( 'Add Comment', '1 Comment' , 'Read Comments (%) ' ); ?>
								</a>-->
							</div>
						</div>
					</div>

		    </header>

				<?php
				$post_format = get_post_format($post->ID);

				?>

				<div class="grid-x">
					<div class="small-12 cell">
						<div class="article-content">
							<?php the_content(); ?>
					  </div>
					</div>
				</div>
			</div>

			<aside class="related hide-on-print">
				<!-- article meta data -->
				<div class="grid-x pagination-single">
				<?php //haven_child_theme_post_nav();?>
				</div>

				<?php

				if ( function_exists( 'km_rpbt_related_posts_by_taxonomy_shortcode' ) ) {
					// Related Posts By Taxonomy plugin is active
					echo do_shortcode( '[related_posts_by_tax posts_per_page="3" format="thumbnails" image_size="related-posts-by-tax" orderby="post_date" order="ASC" taxonomies="category" filter="false" before_title="<h3 class=\'related_post_title\'>" after_title="</h3>" title="' . __( 'Related Articles' ) . '"]' );
				}
				?>

				<div id="rp_ajax_container" data-id="<?php the_ID(); ?> "></div>

				<?php the_tags(__('<p class="tags"><strong>Article tags:</strong> ', 'srbweb-foundation'), ', ', '.</p>'); ?>

			</aside>
  </article>

		<!--	<a class="comment-anchor" id="comment-on-post"></a>-->
	    <?php
		//	comments_template( '', true );
				}
			} else {
			?>
				<div class="alert-box error">Sorry, no posts matched your criteria.</div>
			<?php
			}
