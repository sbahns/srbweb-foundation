<?php
/**
 * Template for displaying content on tag archive pages.
 */

$author = get_userdata( get_query_var('author') );
$stylesheet_dir = get_stylesheet_directory_uri();

$category = get_the_category();
$cat_name = $category[0]->cat_name;
$post_noun = 'Read More';

$pubs_only = false;
$depts_only = false;
if (strpos($_SERVER['REQUEST_URI'], '/topics/') !== false) {
	$pubs_only = true;
} 
if (strpos($_SERVER['REQUEST_URI'], '/department/') !== false) {
	$depts_only = true;
}
?>

<div class="article-excerpt category-archive">
	<div class="section-content entry-content">
		<article id="post-<?php the_ID(); ?>">
			<?php
			$txtcolwidth = 'medium-12';
			if ( has_post_thumbnail() ) {
				$has_image = 'has_image';
				$txtcolwidth = 'medium-8';
				$default_attr = array(
					'class' => 'hide-for-small-only entry-image',
					'alt' => the_title_attribute('echo=0'),
					'title' => the_title_attribute('echo=0'),
				);
				?>
				<div class="grid-x grid-padding-x grid-padding-y">
				<div class="medium-4 cell">
					<a href="<?php the_permalink() ?>" title="Permanent link to <?php the_title_attribute() ?>" rel="bookmark"><?php the_post_thumbnail(); ?></a>
					<?php if ( in_category('videos') || has_term('video','featured_cat') ) { ?>
						<div class="video-meta">
							<a href="<?php the_permalink() ?>" title="Permanent link to <?php the_title_attribute() ?>" rel="bookmark"><img class="vid-play" src="<?php echo $stylesheet_dir; ?>/img/play_button.png" /></a>
						</div>
					<?php } ?>
				</div>

			<?php } ?>

			<div class="<?php echo $txtcolwidth;?> cell">

				<h3><a href="<?php the_permalink() ?>" title="Permanent link to <?php the_title_attribute() ?>" rel="bookmark"><?php the_title() ?></a></h3>

				<div class="clearfix post-meta">
					<?php
					$byline = "";
					$author_id = get_the_author_meta('ID');
					$authordata = get_userdata($author_id);
					$post_id = get_the_ID();
					?>
					<span class="post-date"><?php the_time(get_option('date_format')); ?></span> &#8226;
					<span class="by-line"><?php printf(__($byline.'%s', 'carrington-blog'), '<a href="' . get_author_posts_url( $post->post_author ) . '" title="View all posts by ' . esc_attr($authordata->display_name) . '"  rel="author">' . get_the_author() . '</a>'); ?></span> &#8226;
					<?php if (has_term('video','featured_cat')) {
							echo ('<i class="fi-video"></i> &#8226;');
					}
					$categories = get_the_category($post_id);
					$category_url = get_category_link($categories[0]->term_id);
					if ($pubs_only || $depts_only) {
						$category_url = str_replace('category/', 'category/topics/', $category_url);
						//$category_url = str_replace("/'>", "/?pub_topics=1'>", $category_url);
						$category_url = add_query_arg('pub_topics', 1, $category_url);
					}
					$category_link = "<a href='" . $category_url . "'>".$categories[0]->cat_name."</a>";
					?>
					<span class="home-catname"><?php echo $category_link; ?></span>
				</div>

				<div class="excerpt">
					<?php echo improved_trim_excerpt('', 30, '<i class="fi-play"></i>', ''); ?>
				</div>

			</div>
		</article>
	</div>
</div>
