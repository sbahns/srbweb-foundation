<?php
$post_id = get_the_ID();
$sponsored_post = has_term('sponsored','featured_cat') ? 1 : 0;
$f_icon = has_term('video','featured_cat') || has_post_format('video') ? '<i class="fi-video"></i>' : '';
?>
<div class="cell-image">
	<?php
	if ( has_post_thumbnail() ) {
		$size = 'daily-story-grid';
		$thumbnail_attr = array(
			'class' => 'hide-on-phones entry-image',
			'alt' => the_title_attribute('echo=0'),
			'title' => the_title_attribute('echo=0'),
		);
		if ( $sponsored_post ) {
	        ?>
	        <div class="sponsored-content-image-container">
	            <div class="sponsored-content-banner"></div>
			<?php
	    }
		?>
		<a href="<?php the_permalink() ?>" title="Permanent link to <?php the_title_attribute() ?>" rel="bookmark"><?php the_post_thumbnail( $size, $thumbnail_attr ); ?></a>
		<?php
	    if ( $sponsored_post ) {
	        ?>
	        	</div>
	        <?php
	    }
	}
	?>
</div>

<div class="cell-excerpt">
	<p class="catname"><?php mq_get_lowest_category_link($post_id, false, true, true); ?></p>

	<h2 class="daily-headline"><a href="<?php the_permalink() ?>" title="Permanent link to <?php the_title_attribute() ?>" rel="bookmark"><?php echo $f_icon; ?> <?php the_title() ?></a></h2>
	<?php
	if ( function_exists('improved_trim_excerpt') ) {
		echo improved_trim_excerpt('', 30, '<i class="fi-play"></i>', '');
	}
	?>
</div>
