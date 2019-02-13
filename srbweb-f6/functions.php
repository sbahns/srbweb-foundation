<?php
/*---------------------------------
 * Add theme support
---------------------------------*/

if ( ! function_exists( 'srbweb_theme_support' ) ) {
	function srbweb_theme_support() {
		/*------------------------------------------
		* Image Sizes - Add post thumbnail supports.
		* http://codex.wordpress.org/Post_Thumbnails
		--------------------------------------------*/
		add_theme_support('post-thumbnails');

		// Add RSS feed links to <head> for posts and comments.
		add_theme_support('automatic-feed-links');

		/*-----------------------------------------------------------
		* Switches default core markup for search form, comment form,
		* and comments to output valid HTML5.
		------------------------------------------------------------*/
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

		// Add post formarts supports. http://codex.wordpress.org/Post_Formats
		add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));

		/*----------------------------------------------------
		* Register Navigation - Add menu supports
		* http://codex.wordpress.org/Function_Reference/register_nav_menus
		------------------------------------------------------*/
		add_theme_support('menus');
		register_nav_menus(array(
			'global' => __('Global Navigation', 'srbweb-foundation'),
			'footer' => __('Footer Navigation', 'srbweb-foundation')
		));
	}
	add_action('after_setup_theme', 'srbweb_theme_support');
}

if ( ! function_exists( 'srbweb_image_sizes' ) ) {
	function srbweb_image_sizes() {
		add_image_size('retina-thumbnail', 300, 300, true);
		add_image_size('retina-medium', 600, 600, true);
		add_image_size('large-thumbnail', 377, 252, array( 'left', 'top' ));
		add_image_size('featured-image-thumbnail', 744, 423, true);
	}
	add_action('after_setup_theme', 'srbweb_image_sizes');
}

// Set to FALSE to disable the admin bar, set to TRUE if you want it to be visible.
   show_admin_bar( false );

/*-------------------------------------------
 * Shortcodes - Enable shortcodes in sidebars.
---------------------------------------------*/
add_filter('widget_text', 'do_shortcode');

/*-------------------------------------------
 * User Bio allow HTML
---------------------------------------------*/
remove_filter('pre_user_description', 'wp_filter_kses');

/*-------------------------------------------
* Shortcodes - Function to call posts by name using a shortcode.
* usage: do_shortcode('[get_post_by_name page_title="some title" post_type="posttype"]');
* echo do_shortcode('[get_post_by_name page_title="UCNameplate Ad" post_type="uc"]');
---------------------------------------------*/

if ( ! function_exists( 'get_post_by_name' ) ) {
	function get_post_by_name($attr, $content = '') {
		$attr = shortcode_atts(
			array(
				'page_title'	=> '',
				'post_type'		=> 'uc',
				'autop'			=> 'yes',
				'shortcodes'	=> true
			)
			,$attr
		);

		global $wpdb;
		if ( 'yes' == $attr['autop'] ) {
			$post = wpautop($wpdb->get_var( $wpdb->prepare( "SELECT post_content FROM $wpdb->posts WHERE post_title = %s AND post_type = %s", $attr['page_title'], $attr['post_type'] )));
		} else {
			$post = $wpdb->get_var( $wpdb->prepare( "SELECT post_content FROM $wpdb->posts WHERE post_title = %s AND post_type = %s", $attr['page_title'], $attr['post_type'] ));
		}
		if ($attr['shortcodes']) {
			return do_shortcode( $post );
		} else {
			return $post;
		}
	}
	add_shortcode('get_post_by_name', 'get_post_by_name');
}

if ( ! function_exists( 'get_excerpt_by_name' ) ) {
	function get_excerpt_by_name($attr, $content = '') {
		$attr = shortcode_atts(
			array(
				'page_title'	=> '',
				'post_type'		=> 'uc',
				'autop'			=> 'yes',
				'shortcodes'	=> true
			)
			,$attr
		);

		global $wpdb;
		if ( 'yes' == $attr['autop'] ) {
			$post = wpautop($wpdb->get_var( $wpdb->prepare( "SELECT post_excerpt FROM $wpdb->posts WHERE post_title = %s AND post_type = %s", $attr['page_title'], $attr['post_type'] )));
		} else {
			$post = $wpdb->get_var( $wpdb->prepare( "SELECT post_excerpt FROM $wpdb->posts WHERE post_title = %s AND post_type = %s", $attr['page_title'], $attr['post_type'] ));
		}
		if ($attr['shortcodes']) {
			return do_shortcode( $post );
		} else {
			return $post;
		}
	}
	add_shortcode('get_excerpt_by_name', 'get_excerpt_by_name');
}

/*---------------------------------
 * Helper Functions
---------------------------------*/
// include('functions/functions-custom.php');
// include('shortcodes.php');

/*---------------------------------
 * Custom Post Types & Taxonomies
---------------------------------*/
// include('custom_post/downloads.php');

/*----------------------------------------------
 * Enqueue scripts and styles for the front end
------------------------------------------------*/

if ( ! function_exists( 'srbweb_foundation_scripts_styles' ) ) {
	function srbweb_foundation_scripts_styles() {
		// register latest jQuery
		wp_deregister_script('jquery');
		wp_register_script(
			'jquery', // handle
			get_template_directory_uri() . '/bower_components/jquery/dist/jquery.min.js', // source
			array(),
			'3.2.1', // version
			false // place in header
		);

		// register foundation.min.js
		wp_register_script(
			'foundation-js', // handle
			get_template_directory_uri() . '/bower_components/foundation-sites/dist/js/foundation.min.js', // source
			array('jquery'), // dependencies
			'6.4.1', // version
			true // place in footer
		);

		// register app.js
		wp_register_script(
			'app-js', // handle
			get_template_directory_uri() . '/js/app.js', // source
			array('foundation-js'), // dependencies
			'6.4.1', // version
			true // place in footer
		);

		// Loads main stylesheet.
		wp_enqueue_style( 'srbweb-stylesheet', get_stylesheet_uri(), array() );
		// Enqueue javascript.
		wp_enqueue_script( 'app-js' );
	}
	add_action( 'wp_enqueue_scripts', 'srbweb_foundation_scripts_styles' );
}

/*===================================
=			Navigation			=
===================================*/

/**
 * Cleaner walker for wp_nav_menu()
 *
 * class required_walker
 * Custom output to enable the the ZURB Navigation style.
 * Courtesy of Kriesi.at. http://www.kriesi.at/archives/improve-your-wordpress-navigation-menu-output
 * From required+ Foundation http://themes.required.ch
 *
 * And Swalkinshaw https://github.com/swalkinshaw
 *
 * Walker_Nav_Menu (WordPress default) example output:
 * <li id="menu-item-8" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-8"><a href="/">Home</a></li>
 * <li id="menu-item-9" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9"><a href="/sample-page/">Sample Page</a></l
 *
 * srbweb_walker as above example output:
 * <li id="menu-item-home" class="menu-item menu-item-main-menu menu-item-home"><a href="/">Home</a></li>
 * <li id="menu-item-sample-page" class="menu-item menu-item-main-menu menu-item-sample-page"><a href="/sample-page/">Sample Page</a></li>
 *
 */
class srbweb_walker extends Walker_Nav_Menu {

	/**
	* Specify the item type to allow different walkers
	* @var array
	*/
	var $nav_bar = '';

	function __construct( $nav_args = '' ) {

		$defaults = array(
			'item_type' => 'li',
			'in_top_bar' => false,
			'menu_type' => 'main-menu' //enable menu differenciation, used in preg_replace classes[] below
		);
		$this->nav_bar = apply_filters( 'req_nav_args', wp_parse_args( $nav_args, $defaults ) );
	}

	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {

		$id_field = $this->db_fields['id'];
		if ( is_object( $args[0] ) ) {
			$args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
		}
		return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

	// Additionnal Class cleanup, as found in Roots_Nav_Walker - Roots Theme lib/nav.php
	// see http://roots.io/ and https://github.com/roots/roots
	$slug = sanitize_title($item->title);
	$classes = preg_replace('/(current(-menu-|[-_]page[-_])(item|parent|ancestor))/', '', $classes);
	$classes = preg_replace('/^((menu|page)[-_\w+]+)+/', '', $classes);

	$menu_type = $this->nav_bar['menu_type'];
	$classes[] = 'menu-item menu-item-' . $menu_type . ' menu-item-' . $slug;

	$classes = array_unique($classes);

		// Check for flyout
		$flyout_toggle = '';
		if ( $args->has_children && $this->nav_bar['item_type'] == 'li' ) {

			if ( $depth == 0 && $this->nav_bar['in_top_bar'] == false ) {

				$classes[] = 'has-flyout';
				$flyout_toggle = '<a href="#" class="flyout-toggle"><span></span></a>';

			} else if ( $this->nav_bar['in_top_bar'] == true ) {

				$classes[] = 'has-dropdown';
				$flyout_toggle = '';
			}

		}

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		if ( $depth > 0 ) {
			$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
		} else {
			$output .= $indent . ( $this->nav_bar['in_top_bar'] == true ? '<li class="divider"></li>' : '' ) . '<' . $this->nav_bar['item_type'] . ' id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
		}

		$attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )	? ' target="' . esc_attr( $item->target	) .'"' : '';
		$attributes .= ! empty( $item->xfn )		? ' rel="'	. esc_attr( $item->xfn		) .'"' : '';
		$attributes .= ! empty( $item->url )		? ' href="' . esc_attr( $item->url		) .'"' : '';

		if ( strpos( $item->url, 'primecp') != false ) {
			$attributes .= ' target="_blank" ';
		}

		$item_output = $args->before;
		$item_output .= '<a '. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $flyout_toggle; // Add possible flyout toggle
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	function end_el( &$output, $item, $depth = 0, $args = array() ) {

		if ( $depth > 0 ) {
			$output .= "</li>\n";
		} else {
			$output .= "</" . $this->nav_bar['item_type'] . ">\n";
		}
	}

	function start_lvl( &$output, $depth = 0, $args = array() ) {

		if ( $depth == 0 && $this->nav_bar['item_type'] == 'li' ) {
			$indent = str_repeat("\t", 1);
			$output .= $this->nav_bar['in_top_bar'] == true ? "\n$indent<ul class=\"dropdown\">\n" : "\n$indent<ul class=\"flyout\">\n";
		} else {
			$indent = str_repeat("\t", $depth);
			$output .= $this->nav_bar['in_top_bar'] == true ? "\n$indent<ul class=\"dropdown\">\n" : "\n$indent<ul class=\"level-$depth\">\n";
		}
 	}
}

/**
 * Top Bar Walker
 *
 * @since 1.0.0
 */
class Top_Bar_Walker extends Walker_Nav_Menu {
	/**
	* @see Walker_Nav_Menu::start_lvl()
	* @since 1.0.0
	*
	* @param string $output Passed by reference. Used to append additional content.
	* @param int $depth Depth of page. Used for padding.
	*/
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= "\n<ul class=\"sub-menu dropdown\">\n";
	}

	/**
	* @see Walker_Nav_Menu::start_el()
	* @since 1.0.0
	*
	* @param string $output Passed by reference. Used to append additional content.
	* @param object $item Menu item data object.
	* @param int $depth Depth of menu item. Used for padding.
	* @param object $args
	*/

	function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ) {
		$item_html = '';
		parent::start_el( $item_html, $object, $depth, $args );

		$output .= ( $depth == 0 ) ? '<li class="divider"></li>' : '';

		$classes = empty( $object->classes ) ? array() : ( array ) $object->classes;

		if ( in_array('label', $classes) ) {
			$item_html = preg_replace( '/<a[^>]*>( .* )<\/a>/iU', '<label>$1</label>', $item_html );
		}

		if ( in_array('divider', $classes) ) {
			$item_html = preg_replace( '/<a[^>]*>( .* )<\/a>/iU', '', $item_html );
		}

		$output .= $item_html;
	}

	/**
	* @see Walker::display_element()
	* @since 1.0.0
	*
	* @param object $element Data object
	* @param array $children_elements List of elements to continue traversing.
	* @param int $max_depth Max depth to traverse.
	* @param int $depth Depth of current element.
	* @param array $args
	* @param string $output Passed by reference. Used to append additional content.
	* @return null Null on failure with no changes to parameters.
	*/
	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
		$element->has_children = !empty( $children_elements[$element->ID] );
		$element->classes[] = ( $element->current || $element->current_item_ancestor ) ? 'active' : '';
		$element->classes[] = ( $element->has_children ) ? 'has-dropdown' : '';

		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}

}

/**
 * class srbweb_off_canvas_walker
 * Custom output to enable the the ZURB Navigation style.
 * Courtesy of Kriesi.at. http://www.kriesi.at/archives/improve-your-wordpress-navigation-menu-output
 * From required+ Foundation http://themes.required.ch
 *
 * @since WP-Forge 5.2.3.1a
 */
class srbweb_off_canvas_walker extends Walker_Nav_Menu {

	/**
	* Specify the item type to allow different walkers
	* @var array
	*/
	var $nav_bar = '';

	function __construct( $nav_args = '' ) {

		$defaults = array(
			'item_type' => 'li',
			'in_top_bar' => false,
			'menu_type' => 'off-canvas' //enable menu differenciation, used in preg_replace classes[] below
		);
		$this->nav_bar = apply_filters( 'req_nav_args', wp_parse_args( $nav_args, $defaults ) );
	}

	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {

		$id_field = $this->db_fields['id'];
		if ( is_object( $args[0] ) ) {
			$args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
		}
		return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

	// Additional Class cleanup, as found in Roots_Nav_Walker - Roots Theme lib/nav.php
	// see http://roots.io/ and https://github.com/roots/roots
	$slug = sanitize_title($item->title);
	$classes = preg_replace('/(current(-menu-|[-_]page[-_])(item|parent|ancestor))/', '', $classes);
	$classes = preg_replace('/^((menu|page)[-_\w+]+)+/', '', $classes);

	$menu_type = $this->nav_bar['menu_type'];
	$classes[] = 'menu-item-bold menu-item-' . $menu_type . ' menu-item-' . $slug;

	$classes = array_unique($classes);

		// Check for flyout
		$flyout_toggle = '';
		if ( $args->has_children && $this->nav_bar['item_type'] == 'li' ) {

			if ( $depth == 0 && $this->nav_bar['in_top_bar'] == false ) {

				$classes[] = 'has-flyout';
				$flyout_toggle = '<a href="#" class="flyout-toggle"><span></span></a>';

			} else if ( $this->nav_bar['in_top_bar'] == true ) {

				$classes[] = '';
				$flyout_toggle = '';
			}

		}

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		if ( $depth > 0 ) {
			$output .= $indent . '<li id="#menu-item-'. $item->ID . '"' . $value . $class_names .'>';
		} else {
			$output .= $indent . '<' . $this->nav_bar['item_type'] . ' id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
		}

		$attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )	? ' target="' . esc_attr( $item->target	) .'"' : '';
		$attributes .= ! empty( $item->xfn )		? ' rel="'	. esc_attr( $item->xfn		) .'"' : '';
		$attributes .= ! empty( $item->url )		? ' href="' . esc_attr( $item->url		) .'"' : '';

		if ( $args->has_children ) {
			$item_output .= '<dl class="accordion" data-accordion="myAccordionGroup'. $item->ID . '" active_class: \'active\' toggleable: true><dd class="accordion-navigation"><a href="#panel'. $item->ID . '" class="menu-plus">';
			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
			$item_output .= '<i class="fi-plus medium"></i></a>';
		} else {
			$item_output = $args->before;
			$item_output .= '<a '. $attributes .'>';
			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
			$item_output .= '</a>';
			$item_output .= $args->after;
		}
		$item_output .= $flyout_toggle; // Add possible flyout toggle

		global $menuid;
		$menuid = $item->ID;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	function end_el( &$output, $item, $depth = 0, $args = array() ) {

		if ( $depth > 0 ) {
			$output .= "</li>\n";
		} else {
			$output .= "</" . $this->nav_bar['item_type'] . ">\n";
		}
	}

	function start_lvl( &$output, $depth = 0, $args = array() ) {

		$classes = 'panel'. $GLOBALS['menuid'];

		if ( $depth == 0 && $this->nav_bar['item_type'] == 'li' ) {
			$indent = str_repeat("\t", 1);
			$output .= $this->nav_bar['in_top_bar'] == true ? "\n$indent<div id=\"$classes\" class=\"content\"><ul>\n" : "\n$indent<ul class=\"flyout\">\n";
		} else {
			$indent = str_repeat("\t", $depth);
			$output .= $this->nav_bar['in_top_bar'] == true ? "\n$indent<div id=\"$classes\" class=\"content\"><ul>\n" : "\n$indent<ul class=\"level-$depth\">\n";
		}
 	}
}

// Add Foundation 'active' class for the current menu item

if ( ! function_exists( 'srbweb_active_nav_class' ) ) {
	function srbweb_active_nav_class( $classes, $item ) {
		if ( $item->current == 1 || $item->current_item_ancestor == true ) {
			$classes[] = 'active';
		}
		return $classes;
	}
	add_filter( 'nav_menu_css_class', 'srbweb_active_nav_class', 10, 2 );
}

/**
 * Use the active class of ZURB Foundation on wp_list_pages output.
 * From required+ Foundation http://themes.required.ch
 */

if ( ! function_exists( 'srbweb_active_list_pages_class' ) ) {
	function srbweb_active_list_pages_class( $input ) {
		$pattern = '/current_page_item/';
		$replace = 'current_page_item active';
		$output = preg_replace( $pattern, $replace, $input );
		return $output;
	}
	add_filter( 'wp_list_pages', 'srbweb_active_list_pages_class', 10, 2 );
}

// Display navigation to next/previous set of posts

if ( ! function_exists( 'srbweb_daily_paging_nav' ) ) {
	function srbweb_daily_paging_nav() {
		global $wp_query;

		// Don't print empty markup if there's only one page.
		if ( ! get_next_posts_link() && ! get_previous_posts_link() ) {
			return;
		}
		?>
		<nav class="navigation paging-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'srbweb-foundation' ); ?></h1>

				<?php if ( get_next_posts_link() ) : ?>
				<div class="small-6 columns"><p class="readmore previous"><?php next_posts_link( __( 'More Stories', 'srbweb-foundation' ) ); ?></p></div>
				<?php endif; ?>

				<?php if ( get_previous_posts_link() ) : ?>
				<div class="small-6 columns"><div class="readmore previous right"><?php previous_posts_link( __( 'Newer Stories', 'srbweb-foundation' ) ); ?></p></div>
				<?php endif; ?>

			<!-- .nav-links -->
		</nav><!-- .navigation -->
		<?php
	}
}

// Display navigation to next/previous set of posts

if ( ! function_exists( 'srbweb_paging_nav' ) ) {
	function srbweb_paging_nav($older = "Older posts", $newer = "Newer posts") {
		global $wp_query;

		// Don't print empty markup if there's only one page.
		if ( ! get_next_posts_link() && ! get_previous_posts_link() ) {
			return;
		}
		?>
		<nav class="navigation paging-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'srbweb-foundation' ); ?></h1>
			<div class="nav-links">

				<?php if ( get_next_posts_link() ) : ?>
				<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav"><i class="fi-arrow-left"></i></span> '.$older, 'srbweb-foundation' ) ); ?></div>
				<?php endif; ?>

				<?php if ( get_previous_posts_link() ) : ?>
				<div class="nav-next"><?php previous_posts_link( __( $newer.' <span class="meta-nav"><i class="fi-arrow-right"></i></span>', 'srbweb-foundation' ) ); ?></div>
				<?php endif; ?>

			</div><!-- .nav-links -->
		</nav><!-- .navigation -->
		<?php
	}
}

// Display navigation to next/previous post

if ( ! function_exists( 'srbweb_post_nav' ) ) {
	function srbweb_post_nav() {
		global $post;

		// Don't print empty markup if there's nowhere to navigate.
		$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
		$next	= get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous ) {
			return;
		}
		?>
		<nav class="navigation post-navigation" role="navigation">
			<!--<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'srbweb-foundation' ); ?></h1>-->
			<div class="nav-links">

				<?php previous_post_link( '%link', _x( '<span class="meta-nav"><i class="fi-arrow-left"></i></span> %title', 'Previous post link', 'srbweb-foundation' ) ); ?>
				<?php next_post_link( '%link', _x( '%title <span class="meta-nav"><i class="fi-arrow-right"></i></span>', 'Next post link', 'srbweb-foundation' ) ); ?>

			</div><!-- .nav-links -->
		</nav><!-- .navigation -->
		<?php
	}
}

// return entry meta information for posts, used by multiple loops.

if ( ! function_exists('srbweb_entry_meta') ) {
	function srbweb_entry_meta() {
		echo '<p><time class="updated" datetime="'. get_the_time('c') .'" pubdate>'. sprintf(__('%s', 'srbweb-foundation'), get_the_time('F jS, Y'), get_the_time()) .'</time></p>';
	}
}

/*-------------------------------
// User Content custom post type
---------------------------------*/

if ( ! function_exists('uc_register') ) {
	function uc_register() {
		$args = array(
			'label' => __('User Content'),
			'singular_label' => __('User Content'),
			'public' => true,
			'description' => 'Enter content here',
			//'taxonomies'	=> array( 'category' ),
			'show_ui' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => 8,
			'rewrite' => true,
			'supports' => array('title', 'editor', 'thumbnail')
		);

		register_post_type( 'uc' , $args );
	}
	add_action('init', 'uc_register');
}

/*-------------------------------
// Featured Categories taxonomy
---------------------------------*/

if ( ! function_exists('srbweb_featured_categories_setup') ) {
	function srbweb_featured_categories_setup() {
		$labels = array(
			'name'					=> _x( 'Featured Categories', 'taxonomy general name', 'srbweb-foundation' ),
			'singular_name'			=> _x( 'Featured Category', 'taxonomy singular name', 'srbweb-foundation' ),
			'search_items'			=> __( 'Search Featured Categories', 'srbweb-foundation' ),
			'all_items'				=> __( 'All Featured Categories', 'srbweb-foundation' ),
			'parent_item'			=> __( 'Parent Featured Category', 'srbweb-foundation' ),
			'parent_item_colon'		=> __( 'Parent Featured Category:', 'srbweb-foundation' ),
			'edit_item'				=> __( 'Edit Featured Category', 'srbweb-foundation' ),
			'view_item'				=> __( 'View Featured Category', 'srbweb-foundation' ),
			'update_item'			=> __( 'Update Featured Category', 'srbweb-foundation' ),
			'add_new_item'			=> __( 'Add New Featured Category', 'srbweb-foundation' ),
			'new_item_name'			=> __( 'New Featured Category Name', 'srbweb-foundation' ),
		);

		register_taxonomy(
			'featured_cat',
			array( 'post', 'wpbdp_listing' ),
			array(
				'labels'		=> $labels,
				'hierarchical'	=> true,
			)
		);

		add_image_size( 'featured-cat-image', 652, 404, true );
		add_image_size( 'featured-cat-image-thumbnail', 116, 116, true );
	}
	add_action( 'after_setup_theme', 'srbweb_featured_categories_setup' );
}

/*-------------------------------
 * Login Forms
 --------------------------------*/

/*
 * Filter for wrong password in login
 */

if ( ! function_exists('srbweb_login_error_message') ) {
	function srbweb_login_error_message($error){
		//check if that's the error you are looking for
		$pos = strpos($error, 'incorrect');
		if ( is_int($pos) ) {
			$error = "The password entered is incorrect. <a href='/account/change-password/'>Forgot password? Click here.</a>";
		}

		return $error;
	}
	add_filter('login_errors','srbweb_login_error_message');
}

if ( ! function_exists('wp_login_form_alt') ) {
	function wp_login_form_alt($args = array()) {
		$defaults = array(
			'echo'				=> true,
			'redirect'			=> (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
			'form_id'			=> 'loginform-ajax',
			'label_username'	=> __('Username'),
			'label_password'	=> __('Password'),
			'label_remember'	=> __('Remember Me'),
			'label_log_in'		=> __('Log In'),
			'id_username'		=> 'user_login',
			'id_password'		=> 'user_pass',
			'id_remember'		=> 'rememberme',
			'id_submit'			=> 'wp-submit',
			'remember'			=> true,
			'value_username'	=> '',
			'value_remember'	=> false,
			'action'			=> esc_url(site_url('wp-login.php', 'login_post')),
			'unique'			=> ''
		);
		$args = wp_parse_args($args, apply_filters('login_form_defaults', $defaults));

		$form_id = $args['unique'] . $args['form_id'];

		$form = '<form class="panel" name="' . $form_id.'" id="' . $form_id.'" action="' . $args['action'] . '" method="post">' . "\n" .
			apply_filters('login_form_top', '', $args) .
			'<div class="alert-box alert radius" style="display: none;"></div>' . "\n" .
			'<p class="login-username">' . "\n" .
				'<label class="inline_label_desktops_tablets" for="' . esc_attr($args['id_username']) . '">' . esc_html($args['label_username']) . "</label>\n" .
				'<input type="text" name="log" id="' . esc_attr($args['id_username']) . '" class="ajax_username medium text-input" value="' . esc_attr($args['value_username']) . '" size="30" tabindex="910" />' . "\n" .
			"</p>\n" .
			'<p class="login-password">' . "\n" .
				'<label class="inline_label_desktops_tablets" for="' . esc_attr($args['id_password']) . '">' . esc_html($args['label_password']) . "</label>\n" .
				'<input type="password" name="pwd" id="' . esc_attr($args['id_password']) . '" class="ajax_password medium text-input" value="" size="30" tabindex="920" />' . "\n" .
			"</p>\n" .
			apply_filters('login_form_middle', '', $args) .
			' ' . ($args['remember'] ? '<p class="login-remember"><label class="inline_indent_desktops_tablets"><input name="rememberme" type="checkbox" id="' . esc_attr($args['id_remember']) . '" value="forever" tabindex="930"' . ($args['value_remember'] ? ' checked="checked"' : '') . ' /> ' . esc_html($args['label_remember']) . "</label><div class='rememberme'>This setting should only be used on your home or work computer.</div></p>\n" : '') .
			'<p class="login-submit">' . "\n" .
				'<input type="submit" name="wp-submit" id="' . esc_attr($args['id_submit']) . '" class="small secondary medium button radius" value="' . esc_attr($args['label_log_in']) . '" tabindex="940" /><span class="status" style="padding-left: 20px;"></span>' . "\n" .
				'<input type="hidden" name="redirect_to" value="' . esc_url($args['redirect']) . '" />' . "\n" .
			"</p>\n" .
			apply_filters('login_form_bottom', '', $args) .
			"</form>\n";

		$script = "\n\n";

		/*if ( ! empty($args['unique']) ) {
			$script .= "<script type='text/javascript'>
				jQuery(document).ready(function($) {

					// Perform AJAX login on form submit
					$('form#" . $form_id . "').on('submit', function(e){
						$('form#" . $form_id . " input').focus(function() {
							$('form#" . $form_id . " div.alert').slideUp().text('');
						});
						$('form#" . $form_id . " span.status').fadeIn().text(ajax_login_object.loadingmessage);
						$.ajax({
							type: 'POST',
							dataType: 'json',
							url: ajax_login_object.ajaxurl,
							data: {
								'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
								'username': $('form#" . $form_id . " .ajax_username').val(),
								'password': $('form#" . $form_id . " .ajax_password').val()
								}, //'security': $('form#" . $form_id . " .ajax_nonce').val() // nonce
							success: function(data){
								//$('form#" . $form_id . " p.status').text(data.message);
								$('form#" . $form_id . " span.status').fadeOut(function(){
									$('form#" . $form_id . " span.status').text(data.message);
								});
								if (data.loggedin == true){
									$('form#" . $form_id . " span.status').fadeIn();
									location.reload();
								} else {
									$('form#" . $form_id . " div.alert').text(data.message);
									$('form#" . $form_id . " div.alert').slideDown();
								}
							}
						});
						e.preventDefault();
					});

				});
			</script>\n";
		}*/

		if ( $args['echo'] ) {
			echo $form . $script;
		} else {
			return $form . $script;
		}
	}
}

if ( ! function_exists('my_login_form_bottom') ) {
	function my_login_form_bottom( $form_bottom, $args = array() ) {
		if ( empty( $args['failed_redirect_to'] ) ) {
			$args['failed_redirect_to'] = site_url( '/account/login/' );
		}
		$form_bottom .= '<input type="hidden" name="failed_redirect_to" value="' . esc_attr( $args['failed_redirect_to'] ) . '" />' . "\n";
		return $form_bottom;
	}
	add_action( 'login_form_bottom', 'my_login_form_bottom' );
}

if ( ! function_exists('my_authentication_redirect') ) {
	function my_authentication_redirect($user, $username, $password) {
		// If the user was successfully logged in, let them go
		if ( is_a($user, 'WP_User') ) {
			return $user;
		}

		// Process $user as a WP_Error object, pull out the errors, and redirect with them:
		if ( ! empty( $_POST['failed_redirect_to'] ) ) {
			$_POST['failed_redirect_to'] = add_query_arg( 'failed', store_in_session( $user ), $_POST['failed_redirect_to'] );
			wp_safe_redirect( $_POST['failed_redirect_to'] );
			exit;
		}
	}
	add_filter('authenticate', 'my_authentication_redirect', 999, 3);
}

if ( ! function_exists('store_in_session') ) {
	function store_in_session( $data ) {
		start_session_if_needed();
		$data_id = sha1(microtime(true));
		$_SESSION[$data_id] = $data;

		return $data_id;
	}
}

if ( ! function_exists('start_session_if_needed') ) {
	function start_session_if_needed() {
		if ( '' == session_id() ) {
			session_start();
		}
	}
}

// http://www.christianschenk.org/blog/wordpress-is_child-function/
function is_child($parent) {
	global $post;
	return $post->post_parent == $parent;
}

if ( ! function_exists('srbweb_foundation_widgets_init') ) {
	function srbweb_foundation_widgets_init() {
		register_sidebar( array(
			'name'		=> __( 'Main Sidebar: Unknown User', 'srbweb-foundation' ),
			'id'			=> 'sidebar-unknown',
			'description' => __( 'Main sidebar for unknown users.', 'srbweb-foundation' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
		register_sidebar( array(
			'name'		=> __( 'Main Sidebar: Known User', 'srbweb-foundation' ),
			'id'			=> 'sidebar-known',
			'description' => __( 'Main sidebar for logged-in users.', 'srbweb-foundation' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
		register_sidebar( array(
			'name'		=> __( 'Footer Copyright and Disclosure', 'srbweb-foundation' ),
			'id'			=> 'footer-copyright',
			'description' => __( 'Footer copyright and/or disclosure content.', 'srbweb-foundation' ),
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '',
			'after_title' => '',
		) );
	}
	add_action( 'widgets_init', 'srbweb_foundation_widgets_init' );
}


/**
 * Tests if any of a post's assigned categories are descendants of target categories
 *
 * @param int|array $cats The target categories. Integer ID or array of integer IDs
 * @param int|object $_post The post. Omit to test the current post in the Loop or main query
 * @return bool True if at least 1 of the post's categories is a descendant of any of the target categories
 * @see get_term_by() You can get a category by name or slug, then pass ID to this function
 * @uses get_term_children() Passes $cats
 * @uses in_category() Passes $_post (can be empty)
 * @version 2.7
 * @link http://codex.wordpress.org/Function_Reference/in_category#Testing_if_a_post_is_in_a_descendant_category
 */

if ( ! function_exists( 'post_is_in_descendant_category' ) ) {
	function post_is_in_descendant_category( $cats, $_post = null ) {
		foreach ( (array) $cats as $cat ) {
			// get_term_children() accepts integer ID only
			$descendants = get_term_children( (int) $cat, 'category' );
			if ( $descendants && in_category( $descendants, $_post ) )
				return true;
		}
		return false;
	}
}

if ( ! function_exists( 'my_get_lowest_category_link' ) ) {
	function my_get_lowest_category_link($post_id, $get_parent = false, $nonzero_parents_only = false, $echo = true) {
		$cats = get_the_category($post_id);
		$first_key = 0;
		if ( $nonzero_parents_only ) {
			foreach ( $cats as $key => $c ) {
				if ( $c->category_parent == '0' ) {
					unset($cats[$key]);
				}
			}
			reset($cats);
			$first_key = key($cats);
		}

		if ( empty($cats) ) {

			if ($echo) {
				echo '';
				return;
			} else {
				return '';
			}
		}

		$low_id = $cats[$first_key]->term_id;
		$low_nm = $cats[$first_key]->cat_name;
		$low_pt = $cats[$first_key]->category_parent;
		foreach ( $cats as $c ) {
			if ( $low_id > $c->term_id ) {
				$low_id = $c->term_id;
				$low_nm = $c->cat_name;
				$low_pt = $c->category_parent;
			}
		}
		if ( $get_parent ) {
			$low_id = $low_pt;
			$low_nm = get_cat_name($low_id);
		}

		$link = "<a href='" . get_category_link($low_id) . "'>" . $low_nm . "</a>";

		if ($echo) {
			echo $link;
		} else {
			return $link;
		}
	}
}

if ( ! function_exists( 'check_cat_children' ) ) {
	function check_cat_children() {
		global $wpdb;
		$term = get_queried_object();
		$check = $wpdb->get_results( "SELECT * FROM wp_term_taxonomy WHERE parent = '$term->term_id'" );
		if ( $check ) {
			return true;
		} else {
			return false;
		}
	}
}

/**
 * AJAX login
 *
*/

if ( ! function_exists( 'ajax_login_init' ) ) {
	function ajax_login_init() {
		wp_register_script('ajax-login-script', get_template_directory_uri() . '/js/ajax-login-script.js', array('jquery'));
		wp_enqueue_script('ajax-login-script');

		wp_localize_script('ajax-login-script', 'ajax_login_object', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'redirecturl' => home_url(),
			'loadingmessage' => __('Sending user info, please wait...')
		));

		// Enable the user with no privileges to run ajax_login() in AJAX
		add_action( 'wp_ajax_nopriv_ajaxlogin', 'ajax_login' );
	}
	// Execute the action only if the user isn't logged in
	if ( ! is_user_logged_in() ) {
		add_action( 'init', 'ajax_login_init' );
	}
}

if ( ! function_exists( 'ajax_login' ) ) {
	function ajax_login() {
		// First check the nonce, if it fails the function will break
		// check_ajax_referer( 'ajax-login-nonce', 'security' );

		// Nonce is checked, get the POST data and sign user on
		$info = array();
		$info['user_login'] = $_POST['username'];
		$info['user_password'] = $_POST['password'];
		$info['remember'] = true;

	    // Login with email address enabled by Improved User Experience plugin isn't working
	    if ( is_email($info['user_login']) ) {
		    if ( $user = get_user_by( 'email', $info['user_login'] ) ) {
			    $info['user_login'] = $user->user_login;
		    }
	    }

		$user_signon = wp_signon( $info, force_ssl_admin() );

		if ( is_wp_error($user_signon) ){
			echo json_encode(array('loggedin'=>false, 'message'=>__('ERROR: Your email address or password is incorrect.')));
		} else {
			echo json_encode(array('loggedin'=>true, 'message'=>__('Login successful, one second please...')));
		}

		die();
	}
}


/*
 * Don't allow non-admin users to add, edit, or delete admins
 * http://wordpress.stackexchange.com/questions/4479/editor-can-create-any-new-user-except-administrator
 * John P Bloch
 */
class JPB_User_Caps {

	// Add our filters
	function __construct() {
		add_filter( 'editable_roles', array(&$this, 'editable_roles') );
		add_filter( 'map_meta_cap', array(&$this, 'map_meta_cap'),10,4 );
	}

	// Remove 'Administrator' from the list of roles if the current user is not an admin
	function editable_roles( $roles ) {
		if ( isset( $roles['administrator'] ) && ! current_user_can('administrator') ) {
			unset( $roles['administrator']);
		}
		return $roles;
	}

	// If someone is trying to edit or delete and admin and that user isn't an admin, don't allow it
	function map_meta_cap( $caps, $cap, $user_id, $args ) {
		switch( $cap ) {
			case 'edit_user':
			case 'remove_user':
			case 'promote_user':
				if ( isset($args[0]) && $args[0] == $user_id ) {
					break;
				} elseif ( ! isset($args[0]) ) {
					$caps[] = 'do_not_allow';
				}
				$other = new WP_User( absint($args[0]) );
				if ( $other->has_cap( 'administrator' ) ) {
					if ( ! current_user_can('administrator') ) {
						$caps[] = 'do_not_allow';
					}
				}
    	        break;
			case 'delete_user':
			case 'delete_users':
				if ( ! isset($args[0]) ) {
					break;
				}
				$other = new WP_User( absint($args[0]) );
				if ( $other->has_cap( 'administrator' ) ) {
					if ( ! current_user_can('administrator') ) {
						$caps[] = 'do_not_allow';
					}
				}
				break;
			default:
				break;
		}
    	return $caps;
	}

}

$jpb_user_caps = new JPB_User_Caps();

if ( ! function_exists( 'get_first_sentence' ) ) {
	function get_first_sentence( $string ) {
		$pieces = explode(".", $string);
		return $pieces[0] . '.'; // piece1
	}
}

/************************/

function my_sort_options_filter( $sortoptions ) {
    if ( function_exists( 'is_shopp_page' ) ) {
        foreach ( $sortoptions as $value => $label ) {
            if ( $value == 'random' ) unset($sortoptions[ $value ]);
        }
    }
    return $sortoptions;
}
add_filter( 'shopp_category_sortoptions', 'my_sort_options_filter' );

// Adds post-type support for excerpts in pages

function page_excerpt_init() {
	add_post_type_support( 'page', 'excerpt' );
}
add_action('init', 'page_excerpt_init');

// allow html in category and taxonomy descriptions
remove_filter( 'pre_term_description', 'wp_filter_kses' );
remove_filter( 'pre_link_description', 'wp_filter_kses' );
remove_filter( 'pre_link_notes', 'wp_filter_kses' );
remove_filter( 'term_description', 'wp_kses_data' );

/**
 * If the from email is set to the default, we adjust it to meet standards
 *
 * @param string $from_email - E-Mail address
 *
 * @return string - E-Mail address
 */

function my_wp_mail_from($from_email) {
	// Get the site domain and get rid of www.
	$sitename = strtolower( $_SERVER['SERVER_NAME'] );
	if ( substr( $sitename, 0, 4 ) == 'www.' ) {
		$sitename = substr( $sitename, 4 );
	}
	if ($from_email == 'wordpress@' . $sitename)
		$from_email = 'support@' . $sitename;

	return $from_email;
}
add_filter('wp_mail_from', 'my_wp_mail_from');

/**
 * If the from name for an email is set to the default, we adjust it to meet standards
 *
 * @param string $from_name - Name
 *
 * @return string - Name
 */

function my_wp_mail_from_name($from_name) {
	if ($from_name == 'WordPress') {
		$from_name = get_bloginfo('name');
	}
	return $from_name;
}
add_filter('wp_mail_from_name', 'my_wp_mail_from_name');

/**
 * Added the folowing 2 filters beacuse you are trying to use shortcodes in
 * category descriptions. Shortcodes dont normally work outside of the wp-conmtent
 * area of a post so you need to tell it where else to work.
 */

// add_filter( 'term_description', 'shortcode_unautop');
// add_filter( 'term_description', 'do_shortcode' );

/**
 * remove the update nag so clients don't press it?
 */
add_action('admin_menu','my_remove_update_nag');
function my_remove_update_nag()
{
	remove_action( 'admin_notices', 'update_nag', 3 );
}

//remove the confirmation email functionality when a user updates the email
add_action('personal_options_update', 'remove_email_change_confirmation_action', 1);
function remove_email_change_confirmation_action () {
	remove_action('personal_options_update', 'send_confirmation_on_profile_email');
}
