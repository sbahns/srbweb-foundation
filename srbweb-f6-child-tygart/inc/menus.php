<?php

function register_child_theme_menus() {
    register_nav_menus(array(
    'off-canvas' => __('Off-Canvas/Pancake Menu', 'srbweb-foundation'),
    'footer-column-1' => __('Footer Column 1', 'srbweb-foundation'),
		'footer-column-2' => __('Footer Column 2', 'srbweb-foundation'),
		'footer-column-3' => __('Footer Column 3', 'srbweb-foundation'),
		'footer-column-4' => __('Footer Column 4', 'srbweb-foundation'),
	));
}
add_action( 'init', 'register_child_theme_menus' );

// The Off Canvas Menu
function srbweb_off_canvas_nav() {
	 wp_nav_menu(array(
        'container' => false,                           // Remove nav container
        'menu_class' => 'my-off-canvas-menu vertical menu',       // Adding custom nav class
        'items_wrap' => '<ul id="%1$s" class="%2$s" data-accordion-menu>%3$s</ul>',
        'theme_location' => 'off-canvas',        			// Where it's located in the theme
        'depth' => 5,                                   // Limit the depth of the nav
        'fallback_cb' => false,                         // Fallback function (see below)
        'walker' => new Off_Canvas_Menu_Walker()
    ));
}

class Off_Canvas_Menu_Walker extends Walker_Nav_Menu {
    function start_lvl(&$output, $depth = 0, $args = Array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"vertical menu\">\n";
    }
}

class off_canvas_mobile_login extends Walker_Nav_Menu {

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		// We've got a sub-menu, you can remove this condition and
		// have the attribute display for all the li elements, but just in case you only wanted sub menus
		if ( ! is_user_logged_in() && substr($item->url, -9) == '/account/' ) {
			// This is where the magic happens lol
			$item->classes[] = 'hidden';
			$output .= '<li class="menu-item menu-item-type-custom menu-item-object-custom"><a <a href="#" data-open="Login">Log In</a></li>';

		} else {

			$output .= $indent . '<li' . $id . $value . $class_names .'>';
		}
		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

class Walker_Nav_Menu_AAA extends Walker_Nav_Menu
{
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        global $wp_query;

        $highlight_daily = array(
	        '/daily/',
	        '/browse-topics/'
        );
        $highlight_calendar = array(
	        '/event/',
	        '/calendar/'
        );
        $highlight_magazine = array(
	        '/magazine/',
	        '/toc/',
	        '/category/topics/'
        );
        $highlight_guides = array(
	        '/guides/',
	        '/get-download/'
        );
        $do_not_highlight_network = array_merge($highlight_daily, $highlight_calendar, $highlight_magazine, $highlight_guides);
        $do_not_highlight_network[] = '/category/';

        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $class_names = $value = '';

        $classes = empty($item->classes) ? array() : (array)$item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        if (substr($item->url, -7) == '/daily/') {
	        if (strpos($_SERVER['REQUEST_URI'], '/category/') !== false && strpos($_SERVER['REQUEST_URI'], '/category/topics/') === false) {
		        $classes[] = 'active';
		    } else {
	        	foreach ($highlight_daily as $hd) {
		    	    if (strpos($_SERVER['REQUEST_URI'], $hd) !== false) {
				        $classes[] = 'active';
				        break;
				    }
	        	}
		    }
        } elseif (substr($item->url, -10) == '/calendar/') {
	        foreach ($highlight_calendar as $hc) {
		        if (strpos($_SERVER['REQUEST_URI'], $hc) !== false) {
			        $classes[] = 'active';
			        break;
			    }
	        }
        } elseif (substr($item->url, -10) == '/magazine/') {
	        foreach ($highlight_magazine as $hm) {
		        if (strpos($_SERVER['REQUEST_URI'], $hm) !== false) {
			        $classes[] = 'active';
			        break;
			    }
	        }
        } elseif (substr($item->url, -8) == '/guides/') {
	        foreach ($highlight_guides as $hg) {
		        if (strpos($_SERVER['REQUEST_URI'], $hg) !== false) {
			        $classes[] = 'active';
			        break;
			    }
	        }
        } elseif ($item->url == '/') {
			if ( is_front_page() ) {
				$classes[] = 'active';
			} else {
				$highlight_network = true;
				foreach ($do_not_highlight_network as $dnhn) {
					if (strpos($_SERVER['REQUEST_URI'], $dnhn) !== false) {
						$highlight_network = false;
						break;
					}
				}
				if ( $highlight_network ) {
					$classes[] = 'active';
				}
			}
		}

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = ' class="' . esc_attr($class_names) . '"';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = strlen($id) ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $value . $class_names . '>';

        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}
