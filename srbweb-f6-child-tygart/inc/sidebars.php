<?php

function remove_parent_sidebars(){
// Unregister some of the SRBWeb F6 sidebars (if needed)
	//unregister_sidebar( 'sidebar-unknown' );
	//unregister_sidebar( 'sidebar-known' );
}
add_action( 'widgets_init', 'remove_parent_sidebars', 11 );

function child_theme_register_sidebar() {
	/*
	 * srbweb-f6 parent theme registers sidebars also,
	 * any sidebars registered in child theme must use different
	 * id values. If child_theme_register_sidebar() attempts to
	 * register a sidebar with same id value already used in parent
	 * theme, then none of the sidebars in child theme will be
	 * registered.
	 */
/*	register_sidebar( array(
		'name'		=> __( 'My Library (Unknown User)', 'haven-foundation' ),
		'id'			=> 'my-library-unknown',
		'description' => __( 'My Library Unknown Sidebar', 'haven-foundation' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name'		=> __( 'My Library (Known User)', 'haven-foundation' ),
		'id'			=> 'my-library-known',
		'description' => __( 'My Library Known Sidebar', 'haven-foundation' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );*/

	register_sidebar( array(
    'name'		=> __( 'Events Sidebar', 'haven-foundation' ),
    'id'			=> 'events-sidebar',
    'description' => __( 'Events Sidebar', 'haven-foundation' ),
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ) );


}
add_action( 'widgets_init', 'child_theme_register_sidebar' );
