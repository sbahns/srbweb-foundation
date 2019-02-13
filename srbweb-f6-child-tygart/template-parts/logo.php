<?php
global $post;
if ( is_home() || is_front_page() ) {
	$nameplate_url = '/';
	$nameplate_logo = 'logo.png';
	$nameplate_class = ' network';
	$namplate_heading = 'Home';
} else {
	$nameplate_url = '/';
	$nameplate_logo = 'logo.png';
	$nameplate_class = ' network';
	$namplate_heading = 'Home';
} ?>
<div class="title">
	<span class="hidden"><?php bloginfo('name');?> <?php echo  $namplate_heading; ?></span>
	<a href="<?php echo $nameplate_url; ?>"><img class="nameplate-logo<?php echo $nameplate_class; ?>" src="<?php echo get_stylesheet_directory_uri(); ?>/img/<?php echo $nameplate_logo; ?>" alt="<?php bloginfo('name'); ?> Logo" title="<?php bloginfo('name');?> <?php echo $namplate_heading; ?>" /></a>
</div>
