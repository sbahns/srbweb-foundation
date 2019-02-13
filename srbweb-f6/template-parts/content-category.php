<?php
/**
 * Template for displaying content on tag archive pages.
 */
$author = get_userdata( get_query_var('author') );
$stylesheet_dir = get_stylesheet_directory_uri();

$category = get_the_category();
$cat_name = $category[0]->cat_name;
$post_noun = 'Read More';
?>
<div class="section-content entry-content">
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header>
		<?php //include('misc/sponsored-by.php'); ?>
	</header>
	<div class="grid-x">
    <?php
    $txtcolwidth = 'large-12';
	if ( has_post_thumbnail() ) {
		$default_attr = array(
			'class' => 'hide-on-phones entry-image',
			'alt' => the_title_attribute('echo=0'),
			'title' => the_title_attribute('echo=0'),
			);?>
    <div class="large-4 cell hide-for-small">
				<a href="<?php the_permalink() ?>" title="Permanent link to <?php the_title_attribute() ?>" rel="bookmark"><?php the_post_thumbnail( 'large-thumbnail', $default_attr ); ?></a>

		   <?php if (in_category('videos') || has_term('video','featured_cat')) {?>
					<div class="video-meta">
						<a href="<?php the_permalink() ?>" title="Permanent link to <?php the_title_attribute() ?>" rel="bookmark"><img class="vid-play" src="<?php echo $stylesheet_dir; ?>/img/play_button.png" /></a>
					</div>
				  <?php } ?>
    </div>
				<?php
				$has_image = 'has_image';
				$txtcolwidth = 'large-8';
			}

			?>

		  <div class="<?php echo $txtcolwidth;?> cell">
		      <h2><a href="<?php the_permalink() ?>" title="Permanent link to <?php the_title_attribute() ?>" rel="bookmark"><?php the_title() ?></a></h2>
		      <div class="clearfix" style="margin-bottom: 0">
		      <span class="post-date"><?php the_time(get_option('date_format')); ?></span><span class="divider"> &middot; </span>
		      <span class="by-line">
		        <?php echo '<a href="' . get_author_posts_url( $post->post_author ) . '" title="View all posts by ' . get_the_author() . '"  rel="author">' . get_the_author() . '</a>'; ?>
		      </span>
		      <span class="divider"> &middot; </span>
		      <?php
			      if(has_term('video','featured_cat')) {
			      	echo ('<i class="fi-video"></i> <span class="divider"> &middot; </span>');
			      	}
		      ?>
		      <span class="home-catname"><?php my_get_lowest_category_link( get_the_ID() , false ); ?></span>
		      </div>
		      <?php echo improved_trim_excerpt('', POST_EXCERPT_LENGTH_HOME, '<span class="readmore">Read More</span>', '', '', '', '', ''); ?>
			</div>
		</div>
	</article>
</div>
