<?php
/**
 * Template for displaying content on category archive pages.
 */

$author = get_userdata( get_query_var('author') );
$stylesheet_dir = get_stylesheet_directory_uri();

$category = get_the_category();
$cat_name = $category[0]->cat_name;
$post_noun = 'Read More';

$pubs_only = (strpos($_SERVER['REQUEST_URI'], '/topics/')) ? true : false;

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
					<!--<div class="grid-x">

					<?php

						if (validate_gravatar( $post->post_author)) {
							$add_article_meta_class = ' article-meta';
						?>
							<div class="auto cell">
							 <div class="user_thumb">
								<?php	echo get_avatar($post->post_author, 60); ?>
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


						</div>
					</div>-->
					<div class="grid-x">
						<div class="auto cell excerpt">
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
