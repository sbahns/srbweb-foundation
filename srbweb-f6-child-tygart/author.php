<?php
$author = get_userdata( get_query_var('author') );
get_header();
?>
<section id="page" class="archive-content">
	<div class="grid-x">
		<div class="small-12 cell">
			<div class="grid-container">
				<div class="grid-x grid-margin-x grid-padding-y">
					<div class="large-8 medium-8 cell author-archive" id="maincol">
						<div class="content-border">
						<h2 class="page-header">About the Author</h2>
						<section class="author-section section-content">
							<div class="grid-x">
							<?php

								if (validate_gravatar( $author->ID)) {
									$add_article_meta_class = ' article-meta';
								?>
									<div class="auto cell">
									 <div class="user_thumb">
										<?php	echo get_avatar($author->ID, 54); ?>
										</div>
									</div>
							 <?php }	elseif (function_exists ( 'mt_profile_img' ) ) {
									$add_article_meta_class = ' article-meta';
									$profile_pic = mt_profile_img( $author->ID, array( 'echo' => false ) );
									if ( $profile_pic ) {
								?>
								 <div class="auto cell">
									 <div class="user_thumb">
										<?php	 mt_profile_img( $author->ID, array(
														'size' => 'small-user-thumbnail',
														'echo' => true )
													); ?>
										</div>
									</div>
							<?php }
							}
							?>
							<div class="large-11 cell<?php echo $add_article_meta_class; ?>">
								<h1><?php echo $author->display_name ?></h1>
								<p><?php echo $author->user_description; ?></p>
							</div>
						</div>
						</section>

						<?php
						if ( have_posts() ) {
						?>
						<h3 class="archive-title author-archive">Articles by <?php echo $author->display_name ?></h3>
						<?php
							while ( have_posts() ) {
								the_post();
								get_template_part( 'template-parts/page/content', 'author' );
							}
							srbweb_paging_nav();
						} else {
							get_template_part( 'template-parts/content', 'none' );
						}
						?>
						</div>
					</div>
					<?php get_sidebar(); ?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php get_footer(); ?>
