<?php get_header(); ?>

<section id="category">
	<!-- category.php -->
	<div class="grid-x">
		<div class="small-12 cell">
			<div class="grid-container">
				<div class="grid-x grid-margin-x grid-padding-y">
					<div class="large-8 medium-8 cell" id="maincol">
							<section id="category-<?php the_ID(); ?>" class="archive-content">

								<?php
								if (is_category()) {
									echo "<h1 class='category-title'>".single_cat_title('',false)."</h1>";
								}?>

							  <div class="grid-x">
									<div class="large-12 cell">
											<?php
												if ( is_category() ) {
													$category = get_category( get_query_var('cat') );
													echo '<a href="' . get_category_feed_link( $category->cat_ID ) . '" title="' . sprintf( __( 'Subscribe to this category', 'appthemes' ), $category->name ) . '" rel="nofollow"><i class="fi-rss"></i> ' . __( 'RSS Feed', 'appthemes' ) . '</a>';
												}
											?>
					  			</div>
					   		</div>
						    <div class="grid-x">
									<div class="large-12 cell">
										<?php
										echo "<div class='category-description'>".category_description()."</div>";
										$str = category_description();
										$pcount =  substr_count($str, '<p');

										if ($pcount >= 1) {
											echo "<p class='toggleWrap'><a class='toggle-description'>See full description</a></p>";
										}

										$this_category = get_query_var('cat');
										if (is_category('daily')) {
											echo "<div class='archive_links'>";
											echo "Archive by Date | <a href='/archive-by-most-comments/'>Most Commented</a>";
											echo "</div>";
										}
										?>
						     	</div>
						    </div>

							<?php
							$paged = get_query_var('paged') ? get_query_var('paged') : 1;

							// daily category excerpt loop
							// -------------------------------------------

							$post_count ='10';

							$post_args = array(
								'cat'				=> $this_category,
								'category__not_in'	=> '',
								'posts_per_page'	=> $post_count,
								'paged'				=> $paged
							);
							$mypost = new WP_Query($post_args);

							if ($mypost->have_posts()) {
								while ($mypost->have_posts()) {
									$mypost->the_post();
										get_template_part( 'template-parts/archive/content', 'category' );
								}


								if ( $mypost->found_posts > 10 ) {
									$paginate_args = array(
										'prev_next'		=> true,
										'type'			=> 'list',
										'current'   => max( 1, $mypost->get( 'paged' ) ),
					    			'total'     => $mypost->max_num_pages
									);?>
									<div id="pagination" class="clearfix">
										<ul class="pagination" role="navigation" aria-label="Pagination">
											<?php echo paginate_links($paginate_args); ?>
										</ul>
									</div>
						<?php }

							} ?>
						</section>


						<?php wp_reset_postdata(); ?>

					</div>

					<?php get_sidebar(); ?>

				</div>
			</div>
		</div>
	</div>
</section>
<?php	get_footer();
