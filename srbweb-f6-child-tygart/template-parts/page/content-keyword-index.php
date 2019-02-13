<section class="keyword-index">
	<headline>
        <h1 class="pageheadline"><?php the_title(); ?></h1>
    </headline>

  <div id="tag_cloud" class="alttextlink">
    <?php
		$args = array(
			'smallest' => 12,
			'largest' => 18,
			'unit' => 'px',
			'number' => 0,
			'format' => 'flat',
			'orderby' => 'count',
			'order' => 'DESC',
			'separator'  => ', ',
			'topic_count_text_callback' => 'my_tag_text_callback',
		);
		wp_tag_cloud( $args );

		function my_tag_text_callback( $count ) {
			return sprintf( _n( '%s articles', '%s articles', $count ), number_format_i18n( $count ) );
		}
		?>

  </div>
</section>
<div class="clear"></div>
