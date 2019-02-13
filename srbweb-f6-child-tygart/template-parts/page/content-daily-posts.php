<?php
$author = get_userdata( get_query_var('author') );
$stylesheet_dir = get_stylesheet_directory_uri();

$category = get_the_category();
$post_noun = 'Read More';

$pubs_only = (strpos($_SERVER['REQUEST_URI'], '/topics/')) ? true : false;

?>
<section id="post-<?php the_ID(); ?>" class="archive-content">

			<h1 class="archive-title">Advice</h1>
			  <?php
						the_content();

						if ( get_query_var( 'paged' ) ) { $paged = get_query_var( 'paged' ); }
						elseif ( get_query_var( 'page' ) ) { $paged = get_query_var( 'page' ); }
						else { $paged = 1; }

						$post_args = array(
							'cat' => 39,
							'posts_per_page' => 12,
							'paged' => $paged,
							'orderby' => 'date',
							'order' => 'DESC'
							);

						$mypost = new WP_Query($post_args);
						if($mypost->have_posts()) {
							while($mypost->have_posts()) {
								$mypost->the_post();
						?>
								<article class="entry-archive" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

								<div class="grid-x grid-padding-y display archive-content-excerpt">


								<?php
								$txtcolwidth = 'large-12';
								$usercolwidth = 'large-11';
								$add_article_meta_class = '';
								if ( has_post_thumbnail() ) {
									$size = 'post-thumbnail';
									$has_image = 'has_image';
									$txtcolwidth = 'large-8';
									$usercolwidth = 'large-10';
									$default_attr = array(
										'class' => 'hide-on-phones entry-image',
										'alt' => the_title_attribute('echo=0'),
										'title' => the_title_attribute('echo=0'),
									);
								?>
								<div class="large-4 cell hide-for-small">
									<div class="article-image">
										<a href="<?php the_permalink() ?>" title="Permanent link to <?php the_title_attribute() ?>" rel="bookmark"><?php the_post_thumbnail($size, $default_attr); ?></a>
										<?php if ( in_category('videos') || has_term('video','featured_cat') ) { ?>
											<div class="video-meta">
												<a href="<?php the_permalink() ?>" title="Permanent link to <?php the_title_attribute() ?>" rel="bookmark"><img class="vid-play" src="<?php echo $stylesheet_dir; ?>/img/play_button.png" /></a>
											</div>
										<?php } ?>
									</div>
								</div>

							<?php } ?>
								<div class="<?php echo $txtcolwidth;?> cell">
									<div class="article-excerpt">
								<h3><a href="<?php the_permalink() ?>" title="Permanent link to <?php the_title_attribute() ?>" rel="bookmark"><?php the_title() ?></a></h3>

								<div class="clearfix post-meta">
									<div class="grid-x">

									<?php

										if (validate_gravatar( $post->post_author)) {
											$add_article_meta_class = ' article-meta';
										?>
											<div class="auto cell">
 											 <div class="user_thumb">
 												<?php	echo get_avatar($post->post_author, 54); ?>
 												</div>
 											</div>
								   <?php }	elseif (function_exists ( 'mt_profile_img' ) ) {
											$add_article_meta_class = ' article-meta';
											$profile_pic = mt_profile_img( $post->post_author, array( 'echo' => false ) );
											if ( $profile_pic ) {
										?>
										 <div class="auto cell">
											 <div class="user_thumb">
												<?php	 mt_profile_img( $post->post_author, array(
																'size' => 'small-user-thumbnail',
																'echo' => true )
															); ?>
												</div>
											</div>
									<?php }
									}
									?>
									<div class="<?php echo $usercolwidth;?> cell<?php echo $add_article_meta_class;?>">
										<?php
										$byline = "";
										$author_id = get_the_author_meta('ID');
										$authordata = get_userdata($author_id);
										$post_id = get_the_ID();
										?>
										<span class="by-line"><?php printf(__($byline.'%s', 'carrington-blog'), '<a href="' . get_author_posts_url( $post->post_author ) . '" title="View all posts by ' . esc_attr($authordata->display_name) . '"  rel="author">' . get_the_author() . '</a>'); ?></span> &#8226;

										<span class="post-date"><?php the_time(get_option('date_format')); ?></span>

										<?php if (has_term('video','featured_cat')) {
												echo ('<i class="fi-video"></i> &#8226;');
										}
										$categories = get_the_category($post_id);
										$category_url = get_category_link($categories[0]->term_id);
										if ($pubs_only) {
											$category_url = str_replace('category/', 'topics/', $category_url);
											$category_url = add_query_arg('pub_topics', 1, $category_url);
										}
										$category_link = "<a href='" . $category_url . "'>".$categories[0]->cat_name."</a>";
										?>
										<span class="home-catname clearfix"><?php echo $category_link; ?></span>
									</div>
								</div>
								<div class="grid-x">
									<div class="auto cell">
										<div class="excerpt">
											<?php echo improved_trim_excerpt('', 30, '<i class="fi-play"></i>', ''); ?>
										</div>
									</div>
								</div>
							</div>
						</div>
				 </div>
		</div>
	</article>
	<?php
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
	<?php } ?>

<?php
			wp_reset_postdata();
		} ?>
</section>
