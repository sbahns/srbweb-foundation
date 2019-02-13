<?php get_header(); ?>

<!-- category.php -->
<div class="grid-x">

<div class="large-8 medium-8 cell" id="maincol">
	<div class="grid-x">
	    <div class="large-12 cell">
	          <div class="grid-x">
				<div class="large-12 cell">
					<?php
					if (is_category()) {
						echo "<h2 class='category-headline'>".single_cat_title('',false)."</h2>";
					}?>
				</div>
			</div>
			 <div class="grid-x">
				<div class="large-12 cell">
	   				<?php
						if ( is_category() ) {
							echo '<a href="'.$cat_link.'feed/" title="' . sprintf( __( 'Subscribe to this category', 'appthemes' ), $category->name ) . '" rel="nofollow"><i class="fi-rss"></i> ' . __( 'RSS Feed', 'appthemes' ) . '</a>';

						}
						?>


	   			</div>
   			</div>
	        <div class="grid-x">
				<div class="large-12 cell">
					<div class="category-icon">
	        		<?php

	        		 if (function_exists('get_cat_icon')) get_cat_icon('small=true');


					?>
	        	</div>
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
					get_template_part( 'template-parts/content', 'category' );
			}

			echo index_text_ad($this_category);

			srbweb_paging_nav();
			echo '<br clear="all">';
		} ?>

		<br clear="all">

	</div>

	<?php
	wp_reset_postdata();

	get_sidebar();
  ?>
</div>
<?php	get_footer();
