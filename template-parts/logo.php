<?php
if ( is_home() || is_front_page() ) {
	$nameplate_url = '/';
	$nameplate_logo = 'network-logo.png';
} else {
	$nameplate_url = '/';
	$nameplate_logo = 'network-logo.png';
} ?>

<a href="<?php echo $nameplate_url; ?>"><img class="nameplate-logo" src="<?php echo get_stylesheet_directory_uri(); ?>/img/<?php echo $nameplate_logo; ?>" alt="<?php bloginfo('name') ?> Logo" title="<?php bloginfo('name') ?>" /></a>
