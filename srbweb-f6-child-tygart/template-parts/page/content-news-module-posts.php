<?php
		$select_article_category = get_field( 'select_article_category' );
		$args = array(
		    //'cat'              => $select_article_category,
		    'posts_per_page'   => 6,
		    'tax_query'	=> array(
						array(
							'taxonomy'  => 'featured_cat',
							'terms'     => $select_article_category,
							'field'     => 'slug',
							'operator'  => 'IN',
						),
				),
		);
		$news_posts = new WP_Query( $args );

	?>
	<?php if( $news_posts->have_posts() ): ?>

		<?php while( $news_posts->have_posts() ) : $news_posts->the_post(); ?>

			<div class="cell">

					<article class="post">
						<?php
							if ( has_post_thumbnail() ) {
								$default_attr = array(
									'class' => 'entry-image',
									'alt' => the_title_attribute('echo=0'),
									'title' => the_title_attribute('echo=0'),
									);?>
								<a href="<?php the_permalink() ?>" title="Permanent link to <?php the_title_attribute() ?>" rel="bookmark"><?php the_post_thumbnail( 'large-thumbnail', $default_attr ); ?></a>
								<?php
								$has_image = 'has_image';
							}

							?>
						  <h3 class="title"><a href="<?php the_permalink() ?>" title="Permanent link to <?php the_title_attribute() ?>" rel="bookmark"><?php the_title() ?></a></h3>

					</article>

			</div>

		<?php endwhile; ?>

	<?php endif; ?>

<?php wp_reset_postdata();	 // Restore global post data stomped by the_post(). ?>
