<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="http://gmpg.org/xfn/11">
	
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>

		<header id="my-fixed-header" data-sticky-container>
			<div class="off-canvas position-left" id="offCanvasLeftOverlap" data-off-canvas data-transition="overlap">
				<button class="close-button" aria-label="Close menu" type="button" data-close>
					<span aria-hidden="true">&times;</span>
				</button>
				<div class="title-bar-title">
					<?php bloginfo('name'); ?>
				</div>
				<?php srbweb_off_canvas_nav(); ?>
			</div>
			<div class="my-fixed-header sticky white-bg" data-sticky data-sticky-on="small" data-margin-top="0">
				<div class="grid-container grid-container-padded">
					<div class="grid-x grid-margin-x large-margin-collapse mq-logo-login">
							<div class="small-12 cell text-center">
								<?php get_template_part('template-parts/logo'); ?>
							</div>
					</div>
					<div class="grid-x show-for-small-only">
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
						</div>
					</div>
		</div>
		<div class="topnav hide-for-small-only">
		<div class="grid-container">
			<div class="grid-x">

				<div class="show-for-medium large-12 large-centered cell" id="nav-container">
					<nav class="top-bar" data-topbar data-options="data-closing-time: 5000;">
						<button class="menu-icon" type="button" data-open="offCanvasLeftOverlap"></button>
						<div class="top-bar-section float-center">
							<?php
							if ( has_nav_menu( 'global' ) ) {
								wp_nav_menu(array(
									'theme_location'	=> 'global',
									'container'			=> false,
									'depth'				=> 0,
									'items_wrap'		=> '<ul class="dropdown menu my-menu" data-dropdown-menu>%3$s</ul>',
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

						<div class="social float-right">
								 <?php
								 if ( function_exists( 'get_post_by_name' ) ) {
									 echo do_shortcode('[easy-profiles ukey="1524320435"]');
								 }
								 ?>
						 </div>
						<div class="search-icon float-right">
							<i class="fi-magnifying-glass"></i>
								<section class="nameplate-search search-field google-search slide-in mui-enter">
									<h3 class="hidden">Search</h3>
										<form action="/search/" id="cse-search-box" class="collapse nameplate-search nav-search cell small-12">
											<div class="grid-x">
												<div class="small-10 cell">
													<input type="text" name="q" class="googleq siq-expsearch-input" value="Search this site..." />
												</div>
												<div class="small-2 cell cse-search-button-wrap">
													<button type="submit" name="sa" id="cse_search_submit" class="small btn-search button"><i class="fi-magnifying-glass"></i></button>
												</div>
											</div>
										</form>
									</section>
								</div>
							</nav>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>
