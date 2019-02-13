<?php
start_session_if_needed();
get_header();
?>
<section id="page">
	<div class="grid-x">
		<div class="small-12 cell">
			<div class="grid-container">
				<div class="grid-x grid-margin-x grid-padding-y">
					<div class="small-12 medium-12 large-12 cell" role="main">
						  <p class="postmeta-comment alignright"><?php edit_post_link( 'Edit' ); ?></p>
							<h1 class="archive-title"><?php the_title(); ?></h1>
							<?php
							while ( have_posts() ) {
							    the_post();
									get_template_part( 'template-parts/page/content', $post->post_name );
							}
							?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
