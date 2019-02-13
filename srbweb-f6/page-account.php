<?php
/*
Template Name: Account parent
Template Post Type: page
*/
if ( '' == session_id() ) {
	session_start();
}
if ( ! is_user_logged_in() ) {
	wp_redirect( site_url('/account/login/', 'http') );
	exit();
}
get_header();
$user_id = get_current_user_id();
?>
<div class="grid-x">
	<div class="small-12 large-12 cell" id="fullcol">
		<div class="content">
			<?php get_template_part( 'template-parts/page/content', $post->post_name ); ?>
			<!-- this page loads several different loops to manage account details -->
		</div>
	</div>
</div>
  	<?php //get_sidebar('account'); ?>



<?php get_footer(); ?>

</body>
</html>
