<?php
while ( have_posts() ) {
    the_post();
    ?>
    <article <?php post_class() ?> id="post-<?php the_ID(); ?>">
    	<div class="entry">
    		<?php the_content(); ?>
    	</div>
    </article>
    <?php
}
