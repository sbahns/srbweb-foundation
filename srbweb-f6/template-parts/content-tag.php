<?php
/**
 * Template for displaying content on tag archive pages.
 */
?>
<div class="entry-content">
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="grid-x">
		<?php
		    $txtcolwidth = 'large-12';
			if ( has_post_thumbnail() ) {
				$default_attr = array(
					'class' => 'hide-on-phones entry-image',
					'alt' => the_title_attribute('echo=0'),
					'title' => the_title_attribute('echo=0'),
					);?>
               <div class="large-5 cell hide-for-small">
				<a href="<?php the_permalink() ?>" title="Permanent link to <?php the_title_attribute() ?>" rel="bookmark"><?php the_post_thumbnail( 'large-thumbnail', $default_attr ); ?></a>
                </div>
				<?php
				$has_image = 'has_image';
				$txtcolwidth = 'large-7';
			}

			?>
		  <div class="<?php echo $txtcolwidth;?> cell archive-excerpt">
		      <h2 class="archive-excerpt-titles"><a href="<?php the_permalink() ?>" title="Permanent link to <?php the_title_attribute() ?>" rel="bookmark"><?php the_title() ?></a></h2>
		      <p class="by-line">
		       <?php printf(__($byline.'%s', 'carrington-blog'), '<a href="' . get_author_posts_url( $post->post_author ) . '" title="View all posts by ' . esc_attr($authordata->display_name) . '"  rel="author">' . get_the_author() . '</a>');?>
		       <?php
				echo  edit_post_link( ' - Edit' );
		?></p>
		<?php echo improved_trim_excerpt('', POST_EXCERPT_LENGTH_SINGLEARCHIVE, '', '', '', '', '', ''); ?>
		  </div>
	</div>

</article>
</div>
