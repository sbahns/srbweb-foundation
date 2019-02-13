<?php


get_header();

?>

	<section id="post">
		<div class="grid-x">
			<div class="small-12 cell">
				<div class="grid-container">
					<div class="grid-x grid-margin-x grid-padding-y">
						<div class="small-12 medium-8 large-8 cell" id="maincol" role="main">

								<?php get_template_part( 'template-parts/post/content', 'single' ); ?>

						</div>

						<?php
					      get_sidebar(); // sidebar.php
					    ?>
					</div>
				</div>
			</div>
		</div>
	</section>

<?php
get_footer();
