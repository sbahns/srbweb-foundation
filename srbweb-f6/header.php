<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<?php endif; ?>
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>

		<header id="header" class="topBar">
			<div class="grid-container grid-container-padded">
				<div class="grid-x">
					<div class="small-12 medium-12 large-8 cell alignleft">
						<h1 class="title"><?php get_template_part('template-parts/logo'); ?></h1>
					</div>
					<div class="small-12 medium-12 large-4 cell">
						<ul class="menu social-login">
							<?php
							if ( ! is_user_logged_in() ) {
								?>
								<li class="hide-for-medium-down login-logout"><a href="#" data-open="srbwebLogin">Login</a></li>
								<?php
							} else {
								global $current_user, $user_id;
								wp_get_current_user();
								$user_id = $user_ID;
								$greeting = $current_user->user_firstname ? $current_user->user_firstname : $current_user->display_name;
								?>
								<li class="hide-for-medium-down login-logout"><a href="/account/" style="float:right;"><span class="greeting">Hi,&nbsp;</span><?php echo $greeting; ?></a></li>
								<li class="hide-for-medium-down login-logout"><a href="<?php echo wp_logout_url( get_permalink() ); ?>">Log Out</a></li>
								<?php
							}
							if ( function_exists( 'get_post_by_name' ) ) {
								echo do_shortcode('[get_post_by_name page_title="Header Social Icons" post_type="uc"]');
							}
							?>
						</ul>
					</div>
					<div class="grid-x">
						<div class="small-12 medium-12 large-12 cell">
							<div class="title-bar show-for-small-only" data-hide-for="medium">
								<button class="menu-icon" type="button" data-open="offCanvasLeftOverlap"></button>
							  <div class="title-bar-title">Menu</div>
							</div>

							<div class="off-canvas position-left" id="offCanvasLeftOverlap" data-off-canvas data-transition="overlap">
								<button class="close-button" aria-label="Close menu" type="button" data-close>
						      <span aria-hidden="true">&times;</span>
						    </button>
								<div class="title-bar-title"><?php bloginfo('name'); ?></div>
								<?php // off-canvas menu
								wp_nav_menu(array(
										 'container' => false,                           // Remove nav container
										 'menu_class' => 'vertical menu',       // Adding custom nav class
										 'items_wrap' => '<ul id="%1$s" class="%2$s" data-accordion-menu>%3$s</ul>',
										 'theme_location' => 'off-canvas',        			// Where it's located in the theme
										 'depth' => 5,                                   // Limit the depth of the nav
										 'fallback_cb' => false,                         // Fallback function (see below)
										 'walker' => new srbweb_walker(array(
											 'in_top_bar'	=> true,
											 'item_type'		=> 'li',
											 'menu_type'		=> 'main-menu'
										 )),
								 ));
								?>
							</div>
							<div class="top-bar hide-for-small-only">
									<div class="top-bar-left">
										<?php
										if ( has_nav_menu( 'global' ) ) {
											wp_nav_menu(array(
												'theme_location'	=> 'global',
												'container'			=> false,
												'depth'				=> 0,
												'items_wrap'		=> '<ul class="dropdown menu align-center" data-dropdown-menu>%3$s</ul>',
												'fallback_cb'		=> 'srbweb_menu_fallback', // workaround to show a message to set up a menu
												'walker' => new srbweb_walker(array(
													'in_top_bar'	=> true,
													'item_type'		=> 'li',
													'menu_type'		=> 'main-menu'
												)),
											));
										}
										?>
									</div>
								</div>
						</div>

					</div>
				</div>

			</div>
		</header>

		<section id="page" class="pagecontent">
			<h2 class="hidden"><?php the_title(); ?></h2> <!--Hidden title added for validation purposes-->
				<div class="grid-container grid-container-padded">
