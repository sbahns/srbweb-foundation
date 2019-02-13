<?php
/*
 * To use compiled CSS from parent theme and make CSS overrides in child stylesheet,
 * uncomment add_action( 'wp_enqueue_scripts', 'srbweb_theme_enqueue_styles' ) so parent
 * stylesheet will be enqueued.
 *
 * To compile CSS into child theme, leave call to add_action commented out and make sure
 * parent theme gulpfile.js has gulp.src changed to point to scss/app.scss in child theme
 * (example: '../srbweb-f6-child/scss/app.scss') and gulp.dest changed to point to
 * css directory in child theme (example: '../srbweb-f6-child/css'). Make modifications
 * to app.scss and _settings.scss in child theme but cd to parent theme to compile with
 * 'foundation watch' command.
 */

 /*---------------------------------
  * Enqueue
 ---------------------------------*/
function srbweb_theme_enqueue_styles() {
    //wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', array(), '123' );

	$parent_style = 'srbweb-stylesheet'; // wp_enqueue_style() handle from parent theme
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );

	$style_src = get_stylesheet_directory() . '/css/app.css';
	if ( file_exists($style_src) ) {
		$stylesheet_version = filemtime($style_src); // timestamp of app.css date
	} else {
		$stylesheet_version = wp_get_theme()->get('Version'); // theme version declared in style.css
	}
    wp_enqueue_style( 'srbweb-child-stylesheet', get_stylesheet_uri(), array( $parent_style ), $stylesheet_version );

	  // register footer-scripts.js
	  	wp_register_script(
		  	'header-scripts', //handle
		  	get_stylesheet_directory_uri(). '/js/header-scripts.js', // source
		  	array('jquery'), // dependencies
		  	'1', // version
		  	false // place in header
	  	);

	  // register footer-scripts.js
	  	wp_register_script(
		  	'footer-scripts', //handle
		  	get_stylesheet_directory_uri(). '/js/footer-scripts.js', // source
		  	array('jquery'), // dependencies
		  	'2', // version
		  	true // place in footer
	  	);

    // Enqueue javascripts
    wp_enqueue_script( 'header-scripts');
    wp_enqueue_script( 'footer-scripts');
    }

 add_action( 'wp_enqueue_scripts', 'srbweb_theme_enqueue_styles' );

 /*---------------------------------
  * Custom Functions
 ---------------------------------*/
 include('custom_post/custom-post-fields.php');

 include( 'inc/helpers.php' );
 include( 'inc/settings.php' );
 include( 'inc/lightbox.php'); //image lightbox function
 include( 'inc/menus.php'); //menus and walkers for this theme
 include( 'inc/paging-navigation.php'); //post and pagination functions
 include( 'inc/sidebars.php'); //register aditional sidebars and widgets for this theme
 include( 'inc/filters.php' );
 include( 'inc/shortcodes.php');
 include( 'inc/image-sizes.php' ); //register additional image sizes for this theme

 //Required function for mobile device detection:
 include( 'inc/Mobile_Detect.php' ); //PHP Mobile Detection class

 // custom post types & taxonomies:
 include('custom_post/testimonials.php');

 // Special functions for plugins:
 //NOTE: These functions target specific plugins that are in use on this site. Remove any unneeded functions.
 //include( 'inc/related-posts-functions.php' ); //Related Posts by Taxonomy functions
 //include( 'inc/calendar-functions.php' ); //Calendar functions

 /*------------------------------------------------------------------------*/
 // NOTE: In order to keep functions.php modular and from getting cluttered,
 // please consider adding additonal functions to a relevant existing inc
 // file (see above) or creating a separate inc file.
 // Plugin-specific functions should always use a seprate inc file.
