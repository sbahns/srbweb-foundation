<?php
// Allow shortcodes in widgets
add_filter('widget_text', 'do_shortcode');

/////// make_year
function make_year($attr, $content = '') {
	return date("Y");
}
add_shortcode('make_year', 'make_year');

/////// Browse Topics Widget with Accordion Effect
function my_widget_topics_accordion_func() {
	$output = '<div class="bt">' . "\n";
	$output .= '<div class="browse-topics-list-container">' . "\n";
	//$output .= '<ul id="browse-topics-list" class="vertical menu" data-accordion-menu data-options="multi_expand:true;toggleable:true">' . "\n";
	$output .= '<ul id="browse-topics-list" class="vertical menu" data-accordion-menu data-multi-expand="false">' . "\n";
	$args = array(
		'orderby'		=> 'name',
		'order'			=> 'ASC',
		'hide_empty'	=> 0,
		'hierarchical'	=> 1,
		//'exclude'		=> array(1),
		'fields'		=> 'id=>parent',
		'taxonomy'		=> 'category',
		'pad_counts'	=> false
	);
	$categories = get_categories( $args );
	foreach ( $categories as $key => $value ) {
		if ( 0 == $value ) {
			$category = get_category($key);
			$output .= '<li class="browse-categories-item ' . $category->slug .'">';
			$output .= '<a rel="bookmark" title="Permanent link to ' . $category->name . '" href="' . get_category_link( $category->cat_ID ) . '">' . $category->name . '</a>';
			$args = array(
				'orderby'		=> 'name',
				'order'			=> 'ASC',
				'hide_empty'	=> 0,
				'parent'		=> $key,
				'taxonomy'		=> 'category',
				'pad_counts'	=> false
			);
			$child_categories = get_categories( $args );
			if ( $child_categories ) {
				$output .= '<ul class="menu vertical nested">' . "\n";
				foreach ( $child_categories as $child_category ) {
					$output .= '<li class="browse-categories-item ' . $child_category->slug .'">';
					$output .= '<a rel="bookmark" title="Permanent link to ' . $child_category->name . '" href="' . get_category_link( $child_category->cat_ID ) . '">' . $child_category->name . '</a>';
					$output .= "</li>\n";
				}
				$output .= "</ul>\n";
			}
			$output .= "</li>\n";
		}
	}
	$output .= "</ul>\n";
	$output .= "</div>\n";
	$output .= "</div>\n";
	return $output;
}
add_shortcode('my_widget_topics_accordion', 'my_widget_topics_accordion_func');

/////// View Topics Widget with Accordion Effect (Publication Categories)
function my_widget_pubs_topics_accordion_func() {
	$output = '<div class="bt">' . "\n";
	$output .= '<div class="browse-topics-list-container">' . "\n";
	//$output .= '<ul id="browse-topics-list" class="vertical menu" data-accordion-menu data-options="multi_expand:true;toggleable:true">' . "\n";
	$output .= '<ul id="browse-topics-list" class="vertical menu" data-accordion-menu>' . "\n";
	$args = array(
		'orderby'		=> 'name',
		'order'			=> 'ASC',
		'hide_empty'	=> 0,
		'hierarchical'	=> 1,
		//'exclude'		=> array(1),
		'fields'		=> 'id=>parent',
		'taxonomy'		=> 'category',
		'pad_counts'	=> false
	);
	$categories = get_categories( $args );
	foreach ( $categories as $key => $value ) {
		if ( 0 == $value ) {
			$category = get_category($key);
			$pub_cat_slug = str_replace( '/category/', '/category/topics/', $category->slug );
			$pub_cat_link = str_replace( '/category/', '/category/topics/', get_category_link( $category->cat_ID ));
			$output .= '<li class="browse-categories-item ' . $pub_cat_slug .'">';
			$output .= '<a rel="bookmark" title="Permanent link to ' . $category->name . '" href="' . $pub_cat_link . '?pub_topics=1">' . $category->name . '</a>';
			$args = array(
				'orderby'		=> 'name',
				'order'			=> 'ASC',
				'hide_empty'	=> 0,
				'parent'		=> $key,
				'taxonomy'		=> 'category',
				'pad_counts'	=> false
			);
			$child_categories = get_categories( $args );
			if ( $child_categories ) {
				$output .= '<ul class="menu vertical nested">' . "\n";
				foreach ( $child_categories as $child_category ) {
					$child_pub_cat_slug = str_replace( '/category/', '/category/topics/', $child_category->slug );
					$child_pub_cat_link = str_replace( '/category/', '/category/topics/', get_category_link( $child_category->cat_ID ));
					$output .= '<li class="browse-categories-item ' . $child_pub_cat_slug .'">';
					$output .= '<a rel="bookmark" title="Permanent link to ' . $child_category->name . '" href="' . $child_pub_cat_link . '?pub_topics=1">' . $child_category->name . '</a>';
					$output .= "</li>\n";
				}
				$output .= "</ul>\n";
			}
			$output .= "</li>\n";
		}
	}
	$output .= "</ul>\n";
	$output .= "</div>\n";
	$output .= "</div>\n";
	return $output;
}
add_shortcode('my_widget_pubs_topics_accordion', 'my_widget_pubs_topics_accordion_func');

/////// Free Reports Widget
function my_widget_free_reports_func($attr, $content = '') {
	$attr = shortcode_atts(
		array(
			'title' => 'Guides',
			'show' => 5,
			'use_short_headline' => 0
		),
		$attr
	);

	$query_params = array(
		'showposts' => $attr['show'],
		'nopaging' => 0,
		'post_type' => array( 'my_downloads' ),
		'post_status' => 'publish',
		'orderby' => 'date'
	);
	$freemium = new WP_Query($query_params);

	$output = '';
	if ( $freemium->have_posts() ) {
		$output .= '<div class="styled-box">' . "\n";
		// $output .= "<h3 class='widget-title'>" . $attr['title'] . "</h3>\n";
		$output .= '<div class="styled-box-content">';
		$output .= '<ul>' . "\n";
		while ( $freemium->have_posts() ) {
			$freemium->the_post();
			global $post;
			if ($attr['use_short_headline']) {
				$output .= '<li class="cat-post-item ' . $post->post_type . '">';
				$output .= '<a href="' . get_permalink() . '" title="Permanent link to ' . get_the_title() . '" rel="bookmark" class="shortname">';
				$output .= get_post_meta($post->ID, "short_name", $single = true);
				$output .= '</a>';
				$output .= "</li>\n";
			} else {
				$output .= '<li class="cat-post-item ' . $post->post_type . '">';
				$output .= '<a href="' . get_permalink() . '" title="Permanent link to ' . get_the_title() . '" rel="bookmark" class="shortname">' . get_the_title() . '</a>';
				$output .= "</li>\n";
			}
      	}
		$output .= "</ul>\n";
		$output .= "</div>\n";
		$output .= "</div>\n";

		wp_reset_query();  // Restore global post data stomped by the_post().
	}

	return $output;
}
add_shortcode('my_widget_free_reports', 'my_widget_free_reports_func');

/////// Free Reports Widget by Category
function my_widget_free_reports_by_cat_func($attr, $content = '') {
	$attr = shortcode_atts(
		array(
			'title' => 'Guides',
			'show' => 5,
			'use_short_headline' => 0
		),
		$attr
	);
	$output = '';
	$i = 0;
	$categories = get_categories( array(
	    'orderby' => 'name',
	    'parent'  => 0
	) );
	$cat_count = count( $categories );
	foreach ($categories as $cat) {
		$query_params = array(
		'showposts' => $attr['show'],
		'nopaging' => 0,
		'post_type' => array( 'my_downloads' ),
		'post_status' => 'publish',
		'orderby' => 'date',
		'tax_query' => array(
                array(
                    'taxonomy'  => 'category',
                    'field'     => 'slug',
                    'terms'     => $cat->slug
                )
            )
		);
		$freemium = new WP_Query($query_params);
		$i++;
		if ( $freemium->have_posts() ) {
		if ( $i == 1 ) {
			$output .= '<div class="free-guides-list-container">' . "\n";
			$output .= '<div class="styled-box">' . "\n";
			// $output .= "<h3 class='widget-title'>" . $attr['title'] . "</h3>\n";
			$output .= '<div class="styled-box-content">';
			$output .= '<ul id="free-guides-list" class="vertical menu" data-accordion-menu>' . "\n";
		}
		$output .= '<li><a href="#">' . $cat->name . '</a>';
		$output .= '<ul class="menu vertical nested">' . "\n";
		while ( $freemium->have_posts() ) {
			$freemium->the_post();
			global $post;
			if ($attr['use_short_headline']) {
				$output .= '<li class="cat-post-item ' . $post->post_type . '">';
				$output .= '<a href="' . get_permalink() . '" title="Permanent link to ' . get_the_title() . '" rel="bookmark" class="shortname">';
				$output .= get_post_meta($post->ID, "short_name", $single = true);
				$output .= '</a>';
				$output .= "</li>\n";
			} else {
				$output .= '<li class="cat-post-item ' . $post->post_type . '">';
				$output .= '<a href="' . get_permalink() . '" title="Permanent link to ' . get_the_title() . '" rel="bookmark" class="shortname">' . get_the_title() . '</a>';
				$output .= "</li>\n";
			}
      	}
      	$output .= '</ul>';
      	$output .= '</li>';
      	if ( $i == $cat_count ) {
			$output .= "</ul>\n";
			$output .= "</div>\n";
			$output .= "</div>\n";
			$output .= "</div>\n";
		}
	}
	}
	wp_reset_query();  // Restore global post data stomped by the_post().
	return $output;
}
add_shortcode('my_widget_free_reports_by_cat', 'my_widget_free_reports_by_cat_func');


/// RCLP Buttons
function rclp_buttons_func() {
	global $user_ID, $post;

	$post_id =  get_the_ID();

	if ('my_downloads' == get_post_type() && $user_ID != '' ) {
		$rclp_link = "/get-download/force-download/?dtd=".$post_id ;
		$button_text = 'Download PDF';
		$text = '';
		$comment_text = "Comment";
	} elseif ('my_fr_online' == get_post_type() ) { // build the button for the html version of the free report
		$relatedRCLP = get_post_meta( get_the_ID(), 'rclp_linkback', true ); // get slug of related rclp
		$args=array(
			'name' => $relatedRCLP,
			'post_type' => 'my_downloads',
			'post_status' => 'publish',
			'showposts' => 1,
			'ignore_sticky_posts'=> 1
		);
		$my_posts = get_posts($args);
		if ( $my_posts ) {
			$relatedRCLPID = $my_posts[0]->ID;
		}
		$rclp_link = "/get-download/force-download/?dtd=" . $relatedRCLPID ;
			$button_text = 'Download PDF';
			$text = '';
			$comment_text = "Comment";
		} else {
			$rclp_link = "/get-download/?dtd=".$post_id ;
			$button_text = 'Download PDF';
			$text = '';
			$comment_text = "Comment";
		}

		if ( is_page('download') ) {
			$comments = '';
		} else {
			if (is_user_logged_in()){
			$comments = '<div><a class="button radius full" href="#comments">Add a ' . $comment_text . '</a></div>';
		} else {
				$comments = '';
			}
		}
		$output = '';
        $output .=  '<div id="categoryposts-recent-101" class="widget-free-reports">';
		$output .=  $text;
        $output .=  '<div><a class="button radius full" href="' . $rclp_link.'">' . $button_text . '</a></div>';
      $output .=  $comments;
		$output .=  '</div>';

	return $output;
}

add_shortcode('rclp_buttons', 'rclp_buttons_func');

/**
 * Show Products List Based On User Subscription
 * @param $attr
 * @param string $content
 *
 * @return string
 * @usage [my_widget_products_by_subscription]
 */
function my_widget_products_by_subscription($attr){
    $attr = shortcode_atts(
    array(
        'title' => '',
        )
        ,$attr
    );

	global $wpdb;
	$pub_ids = get_pub_ids();
	$sub_pubs = array();
	$pub_terms = array();
	foreach($pub_ids as $pub_id) {
		if(subscribed($pub_id)) {
			$sub_pubs[] = $pub_id;
			$pub_terms[] = get_term_id($pub_id);
		}
	}
    $output = '<div class="styled-box">' . "\n";
    	$output .= '<ul>';
    	foreach ( $sub_pubs as $sub_pub ) {
		 $pub_title = get_pub_title($sub_pub);
		 $pub_slug = get_pub_slug($sub_pub);
		 if ( $pub_title == 'Change This To Non-Magazine Subscription Name') {
			 $output .= '<li><a href="/magazines-subscriptions/' . $pub_slug .'">' . $pub_title . '</a></li>';
		 } else {
			 $output .= '<li><a href="/magazines-subscriptions/' . $pub_slug . '/issues/">' . $pub_title . '</a></li>';
		 }
		}
		if ( empty($sub_pubs) ) {
			$output .= '<li>You are not subscribed to any of our magazines. Take a look at our subscription offers <a href="/magazines-subscriptions/">here</a>.</li>';
		}
    	$output .="</ul>";
    $output .="</div>";

    return $output;
}
add_shortcode('my_widget_products_by_subscription', 'my_widget_products_by_subscription');

function my_widget_ad_by_subscription_func() {
/*	global $wpdb;
	$pub_ids = get_pub_ids();
	$sub_pubs = array();
	foreach($pub_ids as $pub_id) {
		if(subscribed($pub_id)) {
			$sub_pubs[] = $pub_id;
		}
	}*/
	get_template_part( 'template-parts/ad/content', 'subscriptions' );
}
add_shortcode('my_widget_ad_by_subscription', 'my_widget_ad_by_subscription_func');


/////////-----------------------------------------------------------------------///////////
/*
 * shortcodes below this marker should not be used until checked and cleaned up for use on this project.
 * Cut from below and paste above when ready for use.
 */
/////////-----------------------------------------------------------------------///////////


/////////-----------------------------------------------------------------------///////////
//usage: do_shortcode('[get_post_ID_by_name page_title="some title" post_type="posttype"]');
// echo do_shortcode('[get_post_ID_by_name page_title="UCNameplate Ad" post_type="uc"]');
function get_post_ID_by_name($attr, $content = '') {
	$attr = shortcode_atts(
		array(
			'page_title'	=> '',
			'post_type'		=> 'uc',
		)
		,$attr
	);
	global $wpdb;
	$post = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = %s", $attr['page_title'], $attr['post_type'] ));
	return do_shortcode( $post );
}
add_shortcode('get_post_ID_by_name', 'get_post_ID_by_name');


/////////--------------------------------------------------------------------------///////////
//usage: [sitemap_byposttype post_type="my_paid_guides" title="Comprehensive Guides" titlelink="/comprehensive-guides/"]
function sitemap_byposttype($attr, $content = '') {
	$attr = shortcode_atts(
		array(
			'title'			=> '',
			'post_type'		=> 'uc',
			'titlelink'		=> 'User Defined Content'
		)
		,$attr
	);

	  $sitemaploop = new WP_Query( array(
		  'post_type' => $attr['post_type'],
		  'posts_per_page' => -1,
		  'orderby' => 'date'
		  )
	  );

	  $output = '<h3 class="widget-title"><a href="'.$attr['titlelink'].'">'.$attr['title'].'</a>:<h3>';

	  if ( $sitemaploop->have_posts() ) {
		  $output .= '<ul class="sitemap"><ul class="sitemap">';
				while ( $sitemaploop->have_posts() ) {
			    	$sitemaploop->the_post();
					$formatted_title = get_the_title();
					$link = get_permalink();
					$output .= '<li><a href="'.$link.'">'.$formatted_title.'</a></li>';
			}
		  $output .= '</ul></ul>';
	}
	wp_reset_postdata();
	return $output;
}
add_shortcode('sitemap_byposttype', 'sitemap_byposttype');


/////////------------------------------------------------------------------------------------------------///////////
//usage: [sitemap_byposttype post_type="my_paid_guides" title="Comprehensive Guides" titlelink="/comprehensive-guides/"]
function sitemap_bytopic($attr, $content = '') {
	$attr = shortcode_atts(
		array(
			'category'		=> '',
			'title'			=> '',
			'titlelink'		=> ''
		)
		,$attr
	);

	  $args = array(
		  'child_of' 		=> $attr['category'],
		  'orderby'			=> 'name',
		  'order' 		=> 'ASC',
		  'echo' 			=> '0',
		  'title_li'		=> ''
	  );

	  $output = '<h3 class="widget-title"><a href="'.$attr['titlelink'].'">'.$attr['title'].'</a>:</h3>';


		  $output .= '<ul class="sitemap"><ul class="sitemap">';
		  $output .= wp_list_categories($args);
		  $output .= '</ul></ul>';

	return $output;
}
add_shortcode('sitemap_bytopic', 'sitemap_bytopic');



// Customizable RCLP OFIE w/ Registration
// RCLP OFIE
function superofie_rclp($attr, $content='') {

	// Set the defaults
	$attr = shortcode_atts(array(
		'photo' => '',
		'header' => '',
		'body' => '',
		'above_form' => '',
		'below_form' => '',
		'below_buton' => '',
		'privacy' => '',
		'style' => '',
		'button_text' => '',
		'order_of_appearance' => ''
		), $attr);

	global $user_ID, $post;
	$slug =  $post->post_name;

	$post_ID = $post->ID; // same thing: $rclp_ID = get_the_ID();
	$rclp_slug = get_permalink();
	$rclp_title = get_the_title();
	$rclp_filename = get_post_meta($post_ID,'file_name', TRUE);

	// the image might contain the full path from the custom fields
	// so, we need to just get the image name itself
	$report_image_source = get_post_meta($post_ID, 'photo', TRUE);
	$report_image_array = explode('/', $report_image_source);
	$count = count($report_image_array) - 1;
	$report_image = $report_image_array[$count];

	if ( $user_ID != '' ) {
		$myformaction 	= '<form action="/get-download/download/" method="get" class="rclp_ofie_form">' . "\n";
		$myorderlink 	= '/get-download/download/?dtd=' . $post_ID . "\n";
		$myclass		= "hidden";
	} else {
		//$myformaction 	= '<form action="/wp-login.php?action=register" method="post" class="nice bg large radius reverse">' . "\n";
		$myformaction 	= '<form action="/hr-ultra-popup-submit" method="post" class="nice bg large radius reverse">' . "\n";
		$myorderlink 	= '/get-download/?dtd=' . $post_ID . "\n";
		$myclass		= "";
	}

	if ( $user_ID == '' ) {
		$contact = (object) array(
			'first_name' => ''
			,'last_name' => ''
			,'user_email' => isset($_REQUEST['user_email']) ? $_REQUEST['user_email'] : ''
			);
	} else {
		$contact = get_userdata($user_ID);
		$contact->user_email = isset($_REQUEST['user_email']) ? $_REQUEST['user_email'] : $contact->user_email;
		$contact->subscriptions = is_array($contact->subscriptions) ? $contact->subscriptions : array();
	}

	// Use image value from custom field if no image was passed
	$attr['photo'] = empty($attr['photo']) && !empty($report_image) ? $report_image : $attr['photo'];

	$attr['button_text'] = empty($attr['button_text']) ? 'Yes! I want my FREE Guide!' : strip_tags($attr['button_text']);

	$style = empty($attr['style']) ? '' : ' style="' . $attr['style'] . '"';

	$number_of_form_fields = 3;
	$index_control = ( intval($attr['order_of_appearance']) - 1 ) * $number_of_form_fields;

	$my_output = '<aside class="rclp_ofie super_ofie callout"' . $style . ' >' . "\n";
	$my_output .= '<div class="grid-x grid-padding-x grid-padding-y">';
	$my_output .= '<div class="hide-for-small-only large-3 cell">';
	if ( !empty($attr['photo']) ) {
		$img_attr = array(
			'class'	=> 'thumbnail',
			'alt'	=> trim(strip_tags( $rclp_title )),
			'title'	=> trim(strip_tags( $rclp_title )),
		);
		$my_output .= '<a href="' . $myorderlink . '">';
		$my_output .= get_the_post_thumbnail( $post_ID, 'medium-thumbnail', $img_attr ) . "\n";
		$my_output .= '</a>';
	}
	$my_output .= '</div><div class="large-9 cell">';

	if ( !empty($attr['header']) ) {
		$my_output .= "<h2>" . $attr['header'] . "</h2>\n";
	}
	if ( !empty($attr['body']) ) {
		$my_output .= '<div class="hide-for-small-only">' . wpautop($attr['body']) . "</div>\n";
	}
	if ( !empty($attr['order_of_appearance']) ) {
		$i = '_' . intval($attr['order_of_appearance']);
	} else {
		$i = '';
	}
	$my_output .= '</div>';
	$my_output .= '</div>';
	$my_output .= $myformaction . "\n";
	//$my_output .= '<p class="mouse ' . $myclass . '">All fields are required.</p>' . "\n";
	//if ( $user_ID == '' ) {
		$my_output .= '<input type="hidden" name="n" value="1" />' . "\n";
		$my_output .= '<input type="hidden" name="register" value="1" />' . "\n";
		$my_output .= '<input type="hidden" name="dtd" value="' . $post_ID . '" />' . "\n";
		$my_output .= '<input type="hidden" name="error_redirect_to" value="/get-download/?dtd=' . $post_ID . '" />' . "\n";
		$my_output .= '<input type="hidden" name="redirect_to" value="/get-download/thank-you/?signed_up=1&amp;dtd=' . $post_ID . '" />' . "\n";
		$my_output .= '<input type="hidden" name="report_file" value="' . $rclp_filename . '" />' . "\n";
		$my_output .= '<input type="hidden" name="terms_agreement_required" value="0" />' . "\n";
	//}

    $my_output .= '<div class="grid-x grid-padding-x display">';
    $my_output .= '<div class="medium-6 cell">';

	$my_output .= '<label for="first_name_field' . $i . '" class="'.$myclass.'">First Name</label>' . "\n";
	$my_output .= '<input id="first_name_field' . $i . '" type="text" class="full input-text '.$myclass.'" name="first_name"  value="' . $contact->first_name . '" />' . "\n";

    $my_output .= '</div>';
    $my_output .= '<div class="medium-6 cell">';

	$my_output .= '<label for="last_name_field' . $i . '"  class="'.$myclass.'">Last Name</label>' . "\n";
	$my_output .= '<input id="last_name_field' . $i . '" type="text" class="full input-text '.$myclass.'" name="last_name"  value="' . $contact->last_name . '" />' . "\n";


    $my_output .= '</div>';
    $my_output .= '</div><!-- end class grid-x -->';

	$my_output .= '<div class="grid-x display">';
    $my_output .= '<div class="small-12 cell">';
	$my_output .= '<label for="user_email_field' . $i . '"  class="'.$myclass.'">Email</label>' . "\n";
	$my_output .= '<input id="user_email_field' . $i . '" type="text" class="full input-text '.$myclass.'" name="user_email"  value="' . $contact->user_email . '" />' . "\n";
    $my_output .= '</div>';
    $my_output .= '</div><!-- end class grid-x -->';

	$my_output .= '<div class="grid-x display">';
	$my_output .= '<div class="small-12 cell">';
	$my_output .= '<span class="show-for-large"><input type="submit" class="button radius" name="get_free_ebook" value="' . $attr['button_text'] . '" /></span>' . "\n";
	$my_output .= '<span class="hide-for-large"><input type="submit" class="button radius" name="get_free_ebook" value="Download" /></span>' . "\n";
    $my_output .= '</div>';
    $my_output .= '</div><!-- end class grid-x -->';

	$my_output .= '</form>' . "\n";

	if ( !empty($attr['below_form']) ) {
		$my_output .= wpautop($attr['below_form']) . "\n";
	}

	$my_output .= wpautop($attr['above_form']) . "\n";

	//$my_output .= '<p class="disclosure">We understand your email address is private. You will receive email and newsletters from  '.get_bloginfo('name').', and we will only share your email with approved sponsors. And remember you can unsubscribe at any time.</p>' . "\n";
	$my_output .= '<span class="disclosure">'.do_shortcode('[get_post_by_name page_title="Disclosure" post_type="uc"]').'</span>';

	$my_output .= '</aside>' . "\n";

	return $my_output;
}
add_shortcode('superofie_rclp', 'superofie_rclp');



// Customizable SLLP
// SLLP OFIE
function superofie_sllp($attr, $content = '') {

	// set defaults
	$attr = shortcode_atts(array(
		'use_photo' => 'xx',
		'header' => '',
		'body' => '',
		'text' => '',
		'style' => '',
		'target' => '_blank',
		'button_text' => '',
		'button_mobile' => 'Subscribe Now!'
		), $attr);

	// configuration
	$sfg = true;

	global $user_ID, $post;
	$post_id = $post->ID; // same thing: $rclp_ID = get_the_ID();
	$rclp_slug = get_permalink();
	$rclp_title = get_the_title();
	$productimg = get_post_meta( get_the_ID(), 'ofie_image', true );

	$producturl = ($sfg) ? get_field('sfg_subscription_url') : get_field('ofie_link').'?product_id='.$post_id;
	$urlparts = parse_url($producturl);
	$url = $urlparts['scheme'].'://'.$urlparts['host'].$urlparts['path'];
	parse_str($urlparts['query'], $query);

	if ($sfg) {
		$query['uid'] = $user_ID;
		if (strpos($_SERVER['HTTP_HOST'], 'dev') !== false ) {
			$query['testmode'] = 'Y';
			$query['dev'] = '1';
		}
		$field_names = array('first' => 'fn', 'last' => 'ln', 'email' => 'em', 'asid' => 'mqsc');
	} else {
		$query['ikey'] = 'G**EVG';
		$field_names = array('first' => 'iOrdBillFname', 'last' => 'iOrdBillLname', 'email' => 'iOrdBillEmail', 'asid' => 'iws');
	}

	if (function_exists('mqSourceTracking')) {
		$mqSourceTracking = mqSourceTracking::getInstance();
		$asid = $mqSourceTracking->getCurrentSourceCode();
		$query[$field_names['asid']] = $asid;
	}

	$u = get_userdata($user_ID);
	if ( $u ) {
		$first_name = $u->first_name;
		$last_name = $u->last_name;
		$user_email = $u->user_email;
		if (!$sfg) {
			$display_name = $u->display_name;
			$user_login = $u->user_login;
			$address = $u->address;
			$address2 = $u->address2;
			$city = $u->city;
			$state = $u->state;
			$zip_code = $u->zip_code;
			$country = $u->country;
			$phone = $u->phone;
			$subscriptions = is_array($u->subscriptions) ? $u->subscriptions : array();
			$products = is_array($u->products) ? $u->products : array();
		}
	}

	$my_output = '<aside class="rclp_ofie super_ofie_sllp callout primary" ' . $attr['style'] . ' >';

	if ( $attr['use_photo'] ) {
		$img_attr = array(
			'class'	=> 'thumb hide-on-phones',
			'alt'	=> trim(strip_tags( $rclp_title )),
			'title'	=> trim(strip_tags( $rclp_title )),
			'style' => 'margin-bottom:2%'
		);

		$my_output .= '<div align="center"><figure>';
			$my_output .= ($sfg) ? '' : '<a href="'.$producturl.'" class="centeronmobile" target="' .$attr['target'].'">';
			if (!empty($productimg)) {
				$my_output .= "<img src='/wp-content/uploads/".$productimg."'>";
			} else {
				$my_output .= get_the_post_thumbnail( $post_id, 'small-thumbnail', $img_attr );
			}
			$my_output .= ($sfg) ? '' : '</a>';
		$my_output .= '</figure></div>';
	}


	if ( !empty($attr['header']) ) {
		$my_output .= "<h2 class='sllp_headline'>" . $attr['header'] . "</h2>";
	}

	if ( !empty($attr['text']) ) {
		$my_output .= "<p>" . $attr['text'] . "</p>";
	}


	$my_output .= '<form action="'.$url.'"  method="get" target="' .$attr['target'].'">';

	foreach ($query as $key => $value) {
		if (!empty($key)) {
			$my_output .= '<input type="hidden" name="'.$key.'" value="'.$value.'">';
		}
	}

	$my_output .= '<div class="grid-x grid-padding-x"> ';
		$my_output .= '<div class="cell medium-6">';
			$my_output .= '<input type="text" placeholder="First Name" value="'.$first_name.'" name="'.$field_names['first'].'">';
		$my_output .= '</div>';
		$my_output .= '<div class="cell medium-6">';
			$my_output .= '<input type="text" placeholder="Last Name" value="'.$last_name.'" name="'.$field_names['last'].'">';
		$my_output .= '</div>';
	$my_output .= '</div>';

	$my_output .= '<div class="grid-x">';
		$my_output .= '<div class="small-12 cell">';
			$my_output .= '<input type="email" placeholder="Email Address" value="'.$user_email.'" name="'.$field_names['email'].'">';
		$my_output .= '</div>';
	$my_output .= '</div>';
	$my_output .= '<div class="grid-x">';
		$my_output .= '<div class="small-12 cell">';
			$my_output .= '<div class="sllpButton centeronmobile">';
				$my_output .= '<span class="show-for-large"><button type="submit" value="'. $attr['button_text'] . '" class="button centeronmobile arrow">'. $attr['button_text'] . '</button></span>';
				$my_output .= '<span class="hide-for-large"><button type="submit" value="'. $attr['button_mobile'] . '" class="button centeronmobile arrow">'. $attr['button_mobile'] . '</button></span>';
			$my_output .= '</div>';
		$my_output .= '</div>';
	$my_output .= '</div>';

	$my_output .= '</form>';

	if ( !empty($attr['body']) ) {
		$my_output .= '<div>' . wpautop($attr['body']) . "</div>\n";
	}

	$my_output .= '<div class="clear_floats"></div>';

	//$my_output .= '<p class="disclosure">We understand your email address is private. You will receive email and newsletters from  '.get_bloginfo('name').', and we will only share your email with approved sponsors. And remember you can unsubscribe at any time.</p>' . "\n";
	$my_output .= '<span class="disclosure">'.do_shortcode('[get_post_by_name page_title="Disclosure" post_type="uc"]').'</span>';

	$my_output .= '</aside>' . "\n";

	return $my_output;
}

add_shortcode('superofie_sllp', 'superofie_sllp');


function button_sllp($attr, $content='') {
	$producturl = get_post_meta( get_the_ID(), 'product_url', true );
	// Set the defaults
	$attr = shortcode_atts(array(
		'button_text' => '',
		'order_of_appearance' => ''
		), $attr);

	global $user_ID, $post;

	$post_ID = $post->ID; // same thing: $rclp_ID = get_the_ID();
	$rclp_slug = get_permalink();
	$rclp_title = get_the_title();
	$rclp_filename = get_post_meta($post_ID,'file_name', TRUE);

	$attr['button_text'] = empty($attr['button_text']) ? 'Yes! I want my FREE Guide!' : strip_tags($attr['button_text']);

	$my_output .= '<div class="aligncenter"><a href="'.$producturl.'" class="small alert button centeronmobile">'. $attr['button_text'] . '</a></div>' . "\n";
	$my_output .= '<div class="clear_floats"></div>';


	return $my_output;
}

add_shortcode('button_sllp', 'button_sllp');


function smallofie_sllp($attr, $content='') {
	// http://healthadvisory.mequodaprojects.com/get-download/thank-you/?freemium_id=2352

	// Set the defaults
	$attr = shortcode_atts(array(
		'photo' => '',
		'header' => '',
		'body' => '',
		'text' => '',
		'privacy' => '',
		'style' => '',
		'button_text' => '',
		'order_of_appearance' => ''
		), $attr);

	$photowidth = $attr['photo'];
	global $user_ID, $post;
	$slug =  $post->post_name;
	$post_ID = $post->ID; // same thing: $rclp_ID = get_the_ID();
	$rclp_slug = get_permalink();
	$rclp_title = get_the_title();
	//$rclp_filename = get_post_meta($post_ID,'file_name', TRUE);


	$attr['button_text'] = empty($attr['button_text']) ? 'Yes! I want to Subscribe' : strip_tags($attr['button_text']);

	$style = empty($attr['style']) ? '' : ' style="' . $attr['style'] . '"';

	$number_of_form_fields = 3;
	$index_control = ( intval($attr['order_of_appearance']) - 1 ) * $number_of_form_fields;


		$my_formactionoutput 	.= '<form action="/order/offers/?product_id=238" method="get" class="rclp_ofie_form">' . "\n";
		$my_rclplink 			.= '/order/offers/?product_id='.$post_ID . "\n";

	$my_output .= '<div class="grid-x grid-padding-x">';
	$my_output .= '<div class="large-12 cell">';
	$my_output .= '<aside class="rclp_ofie small_ofie"' . $style . ' >' . "\n";
	$my_output .= '<h3 class="hidden">Special Offer</h3>' . "\n";
	$my_output .= '<div class="grid-x grid-padding-x">';
	if ( !empty($attr['photo']) ) {
		$img_attr = array(
			'class'	=> 'show-on-desktops',
			'alt'	=> trim(strip_tags( $rclp_title )),
			'title'	=> trim(strip_tags( $rclp_title )),
		);

		$my_output.= '<div class="medium-3 cell">';
		//$my_output .= get_the_post_thumbnail( $post_ID, array($photowidth,999), $img_attr ) . "\n";
		$my_output .= '<a href="'.$my_rclplink.'" title="Permanent link to '.the_title_attribute(array( 'before' => 'Permalink to: ', 'after' => '' , 'echo' => '0' )) .'" rel="bookmark">';
		$my_output .= get_the_post_thumbnail( $post_ID, 'small-thumbnail', $img_attr ) . "\n";
		$my_output .= '</a>';
		$my_output .= '</div>';
	}
	$my_output .= "<div class='medium-9 cell'>";
	$my_output .= "<h3>" . $attr['header'] . "</h3>\n";
	$my_output .= wpautop($attr['text']) . "\n";
	$my_output .= $my_formactionoutput . "\n";
	//direct to data collection
	$my_output .= '<input type="hidden" value="' . $post_ID . '" name="product_id" />' . "\n";
	$my_output .= '<input type="hidden" value="/order/offers/?product_id=' . $post_ID . '" name="error_redirect_to" />' . "\n";
	$my_output .= '<input type="hidden" value="/order/offers/?signed_up=1&amp;product_id=' . $post_ID . '" name="success_redirect_to" />' . "\n";
	$my_output .= '<input type="submit" class="button radius" name="get_download" value="' . $attr['button_text'] . '" />' . "\n";
	$my_output .= '</form>' . "\n";
	//$my_output .= '<p class="disclosure" style="margin-top:20px;line-height:1.15em;">We understand your email address is private. You will receive email and newsletters from  '.get_bloginfo('name').' Network, and we will only share your email with approved sponsors. And remember you can unsubscribe at any time.</p>' . "\n";
	$my_output .= '<span class="disclosure" style="margin-top:20px;line-height:1.15em;">'.do_shortcode('[get_post_by_name page_title="Disclosure" post_type="uc"]').'</span>';

	if ( !empty($attr['below_form']) ) {
		$my_output .= wpautop($attr['below_form']) . "\n";
	}
	if ( !empty($attr['body']) ) {
		$my_output .= wpautop($attr['body']) . "\n";
	}
	$my_output .= '</div>' . "\n";
	$my_output .= '</div>';
	$my_output .= '</aside>' . "\n";
	$my_output .= '</div>';
	$my_output .= '</div>';


	if (!is_page('download') ) {
		return $my_output;
	}
}
add_shortcode('smallofie_sllp', 'smallofie_sllp');



function smallofie_rclp($attr, $content='') {
	// Set the defaults
	$attr = shortcode_atts(array(
		'photo' => '',
		'header' => '',
		'body' => '',
		'text' => '',
		'privacy' => '',
		'style' => '',
		'button_text' => '',
		'order_of_appearance' => ''
		), $attr);

	$photowidth = $attr['photo'];
	global $user_ID, $post;
	$slug =  $post->post_name;
	$post_ID = $post->ID; // same thing: $rclp_ID = get_the_ID();
	$rclp_slug = get_permalink();
	$rclp_title = get_the_title();
	//$rclp_filename = get_post_meta($post_ID,'file_name', TRUE);


	$attr['button_text'] = empty($attr['button_text']) ? 'Yes! I want my FREE Download!' : strip_tags($attr['button_text']);

	$style = empty($attr['style']) ? '' : ' style="' . $attr['style'] . '"';

	$number_of_form_fields = 3;
	$index_control = ( intval($attr['order_of_appearance']) - 1 ) * $number_of_form_fields;

	$my_formactionoutput = '';
	$my_rclplink         = '';
	if ($user_ID != '' ) {
		$my_formactionoutput 	.= '<form action="/get-download/download/" method="get" class="rclp_ofie_form centeronmobile">' . "\n";
		$my_rclplink 			.= '/get-download/download/?dtd='.$post_ID . "\n";
	} else {
		$my_formactionoutput 	.= '<form action="/get-download/" method="get" class="rclp_ofie_form centeronmobile">' . "\n";
		$my_rclplink 			.= '/get-download/?dtd='.$post_ID . "\n";
	}

	$my_output = '<aside class="rclp_ofie small_ofie callout"' . $style . ' >' . "\n";
	$my_output .= '<h2 class="hidden">Special Offer</h2>' . "\n";
	$my_output .= '<div class="grid-x grid-padding-x">';
	if ( !empty($attr['photo']) ) {
		$img_attr = array(
			'class'	=> 'show-on-desktops',
			'alt'	=> trim(strip_tags( $rclp_title )),
			'title'	=> trim(strip_tags( $rclp_title )),
		);

		$my_output.= '<div class="medium-3 cell hide-for-small-only">';
		//$my_output .= get_the_post_thumbnail( $post_ID, array($photowidth,999), $img_attr ) . "\n";
		$my_output .= '<a href="'.$my_rclplink.'" title="Permanent link to '.the_title_attribute(array( 'before' => 'Permalink to: ', 'after' => '' , 'echo' => '0' )) .'" rel="bookmark">';
		$my_output .= get_the_post_thumbnail( $post_ID, 'small-thumbnail', $img_attr ) . "\n";
		$my_output .= '</a>';
		$my_output .= '</div>';
	}
	$my_output .= "<div class='medium-9 cell'>";
	$my_output .= '<a href="'.$my_rclplink.'" title="Permanent link to '.the_title_attribute(array( 'before' => 'Permalink to: ', 'after' => '' , 'echo' => '0' )) .'" rel="bookmark" class="centeronmobile">';
	$my_output .= "<h2>" . $attr['header'] . "</h2>\n";
	$my_output .= '</a>';
	$my_output .= wpautop($attr['text']) . "\n";
	$my_output .= $my_formactionoutput . "\n";
	//direct to data collection
	$my_output .= '<input type="hidden" value="' . $post_ID . '" name="dtd" />' . "\n";
	$my_output .= '<input type="hidden" value="/get-download/?dtd=' . $post_ID . '" name="error_redirect_to" />' . "\n";
	$my_output .= '<input type="hidden" value="/get-download/thank-you/?signed_up=1&amp;dtd=' . $post_ID . '" name="success_redirect_to" />' . "\n";


	$my_output .= '<span class="show-for-large"><input type="submit" class="small red button radius" name="get_download" value="' . $attr['button_text'] . '" /></span>' . "\n";
	$my_output .= '<span class="hide-for-large"><input type="submit" class="small red button radius" name="get_download" value="Download" /></span>' . "\n";


	$my_output .= '</form>' . "\n";
	// $my_output .= '<p class="disclosure">We understand that your email address is private. We promise to never sell, rent or disclose your email address to any third parties.</p>' . "\n";
	if ( !empty($attr['below_form']) ) {
		$my_output .= wpautop($attr['below_form']) . "\n";
	}
	if ( !empty($attr['body']) ) {
		$my_output .= wpautop($attr['body']) . "\n";
	}
	$my_output .= '</div>' . "\n";
	$my_output .= '</div>';
	$my_output .= '</aside>' . "\n";


	if (!is_page('download') ) {
		return $my_output;
	}
}
add_shortcode('smallofie_rclp', 'smallofie_rclp');


function premiumofie_sllp($attr, $content='') {
	// Set the defaults
	$attr = shortcode_atts(array(
		'bgimage' => '',
		'photowidth' => '',
		'header' => '',
		'body' => '',
		'text' => '',
		'privacy' => '',
		'style' => '',
		'class' => 'hide-on-phones',
		'button_text' => '',
		'order_of_appearance' => ''
		), $attr);

	  $photowidth = $attr['photowidth'];
	  global $user_ID, $post;
	  $post_ID = $post->ID; // same thing: $rclp_ID = get_the_ID();
	  $rclp_slug = get_permalink();
	  $rclp_title = get_the_title();



			  if ( has_post_thumbnail() ) {
					  $has_image = 'has_image';
					  $img_attr = array(
						  'class'	=> 'thumb show-on-desktops',
						  'alt'	=> trim(strip_tags( $rclp_title )),
						  'title'	=> trim(strip_tags( $rclp_title )),
					  );
					  $my_imageoutput = get_the_post_thumbnail( $post_ID, array($photowidth,999), $img_attr ) . "\n";
				  } else {
					  $has_image = 'no_image';
					  $my_imageoutput = '';
				  }

				$my_output = '<aside class="premiumrclp_ofie '.$attr['class'].' " style="background-image:url(/wp-content/uploads/numbers.png);border:1px solid #D6D9D9;">' . "\n";
				$my_output .= '<h2 class="hidden">Special Offer</h2>' . "\n";
				$my_output .= '<div class="container">';
				$my_output .= '  <div class="grid-x grid-padding-x">';
				$my_output .= '    <div class="medium-3 cell">';
				$my_output .= 		$my_imageoutput;
				$my_output .= '    </div>';
				$my_output .= '    <div class="medium-9 cell">';
				$my_output .= "		<h2>" . $attr['header'] . "</h2>\n";
				$my_output .= "		<h3>" . $attr['text'] . "</h3>\n";
						$my_output .= "  <div class='grid-x buttons'>\n";
						$my_output .= "    <div class='medium-6 cell'>\n";
						$my_output .= "      <p class='nice small button red radius'><a href='PATHINFO' target='_blank'>Join as a FIRM Partner</a></p>";
						$my_output .= "    </div>\n";
						$my_output .= "    <div class='medium-6 cell'>\n";
						$my_output .= "      <p class='nice small button red radius'><a href='PATH' target='_blank'>Join as a Member</a></p>";
						$my_output .= "    </div>\n";
						$my_output .= "  </div>\n";
				$my_output .= '    </div>';

				$my_output .= '  </div>';
				$my_output .= '</div>';
				$my_output .= '</aside>' . "\n";
				$my_output .= '<p class="aright learnmore"><a href="/membership/" class="readmore arrow_link">Learn More</a></p>';
			  return $my_output;

}
add_shortcode('premiumofie_sllp', 'premiumofie_sllp');





// simple true or false test

// remove misc stuff from a string
function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

// sort the category by term ID, the lowest term ID is at the end of the array
function sortbyid($a, $b)
{
	$t1 = $a->term_id;
	$t2 = $b->term_id;

	return $t2 - $t1; // last item is the lowest id number, make that primary category
}


//
// move nameplate ad into sidebar for mobile devices
// When implementing to the live site, put the ad server calls in place of the hard coded ad
function show_nameplate_ad_in_sidebar ($attr, $content = '')
{
		$attr = shortcode_atts(
		array(
			'unused1'	=> 'none',
			'unused2'	=> 'none',
			'unused3'	=> 'none'
		)
		,$attr
	);

	if ( wpmd_is_phone() ) {
		echo do_shortcode('[get_post_by_name page_title="Microsidebar Ad" post_type="uc"]');
      }
}
add_shortcode('show_nameplate_ad_in_sidebar', 'show_nameplate_ad_in_sidebar');


//
// Mequoda free reports using shortname if exists
// Usage: [free_and_premium_downloads show="5" title="Research Downloads"]
//
function free_and_premium_downloads($attr, $content = '') {
	$attr = shortcode_atts(
	array(
		'title' => 'Free Reports',
		'show' => 5,
		'use_short_headline' => 0,
		'signup_for_this_free_newsletter_topic_desc' => 0
		)
		,$attr
	);

	$query_params = array(
		'showposts' => $attr['show'],
		'nopaging' => 0,
		'post_type' => array( 'my_downloads', 'premium_downloads' ),
		'post_status' => 'publish',
		'orderby' => 'date'
	);
	$freemium = new WP_Query($query_params);

	if ($freemium->have_posts()) {
		$output .= '<div class="fr bt">' . "\n";
		$output .= "<h3 class='widget-title'>" . $attr['title'] . "</h3>\n";
		$output .= '<div class="styled-box-content">';
		$output .= '<ul class="free_reports">' . "\n";
		while ($freemium->have_posts()) {
			$freemium->the_post();
			global $post;


$mystring = get_the_title();
$findme   = ':';
$pos = strpos($mystring, $findme);
	if ($pos === false) {
		// dont manipulate string if no colon was found
		$myTitle = $mystring;
	} else {
		// Provides: <body text='black'>
		$mystring = str_replace(":", "</b>:", $mystring);
		$myTitle = '<b>'.$mystring;
	}

			if ($attr['use_short_headline']) {
				$output .= '<li>';
				$output .= '<a href="' . get_permalink() . '" title="Permanent link to ' . get_the_title() . '" rel="bookmark" class="shortname">'. "\n";
				$output .= get_post_meta($post->ID, "short_name", $single = true). "\n";
				$output .= '</a>' . "\n";
				$output .= "<p class='topic-description'>" .get_post_meta($post->ID, "signup_for_this_free_newsletter_topic_desc", $single = true) . "</p>\n";
				$output .= "</li>\n";
			} else {
				$output .= '<li>';
				$output .= '<a href="' . get_permalink() . '" title="Permanent link to ' . get_the_title() . '" rel="bookmark" class="shortname">' . $myTitle . '</a><br />'. "\n";
				$output .= "<p class='topic-description'>" .get_post_meta($post->ID, "signup_for_this_free_newsletter_topic_desc", $single = true) . "</p>\n";
				$output .= "</li>\n";
			}
      	}
		$output .="</ul>\n";
	//	if ( !is_post_type_archive('my_downloads') ) { // my_downloads
	//		$output .= '<p class="arrow_link"><a href="' . site_url('/free-recipes/', 'http') . '"> View all Recipes</a></p>' . "\n";
	//	}
		$output .="</div>\n";
		$output .="</div>\n";

		wp_reset_query();  // Restore global post data stomped by the_post().
	}

	return $output;
}
add_shortcode('free_and_premium_downloads', 'free_and_premium_downloads');


//
// Mequoda free reports using shortname if exists - optimized for widget display
// Usage: [free_and_premium_downloads_widget show="5" title="Research Downloads"]
//
function free_and_premium_downloads_widget($attr, $content = '') {
	$attr = shortcode_atts(
	array(
		'title' => '',
		'show' => 5,
		'use_short_headline' => 0
		)
		,$attr
	);

	$query_params = array(
		'showposts' => $attr['show'],
		'nopaging' => 0,
		'post_type' => array( 'my_downloads', 'premium_downloads' ),
		'post_status' => 'publish',
		'orderby' => 'date'
	);
	$freemium = new WP_Query($query_params);

	if ($freemium->have_posts()) {
		$output .= '<div class="styled-box">' . "\n";
		$output .= "<h3 class='widget-title'>" . $attr['title'] . "</h3>\n";
		$output .= '<div class="styled-box-content">';
		$output .= '<ul>' . "\n";
		while ($freemium->have_posts()) {
			$freemium->the_post();
			global $post;
			if ($attr['use_short_headline']) {
				// not used right now. Can be updated to use post title up to the colon ":" in the post name
				$output .= '<li class="cat-post-item '.$post->post_type.'">';
				$output .= '<a href="' . get_permalink() . '" title="Permanent link to ' . get_the_title() . '" rel="bookmark" class="shortname">';
				$output .= get_post_meta($post->ID, "short_name", $single = true);
				$output .= '</a>';
				$output .= "</li>\n";
			} else {
				$output .= '<li class="cat-post-item '.$post->post_type.'">';
				$output .= '<a href="' . get_permalink() . '" title="Permanent link to ' . get_the_title() . '" rel="bookmark" class="shortname">' . get_the_title() . '</a>';
				$output .= "</li>\n";
			}
      	}
		$output .="</ul>\n";
	//	if ( !is_post_type_archive('my_downloads') ) { // my_downloads
	//		$output .= '<p class="arrow_link"><a href="' . site_url('/free-recipes/', 'http') . '"> View all Recipes</a></p>' . "\n";
	//	}
		$output .="</div>\n";
		$output .="</div>\n";

		wp_reset_query();  // Restore global post data stomped by the_post().
	}

	return $output;
}
add_shortcode('free_and_premium_downloads_widget', 'free_and_premium_downloads_widget');


// free reports in footer
function paid_guide_list($attr, $content = '') {
	$attr = shortcode_atts(
	array(
		'posts_per_page' => -1,
		'orderby' => 'date',
		'use_short_headline' => 0
		)
		,$attr
	);
	$query_params = array(
		'post_type' => 'my_paid_guides',
		'posts_per_page' => $attr['posts_per_page'],
		'orderby' => $attr['orderby']
	);
	$paid_guide_query = new WP_Query($query_params);
	$i = 100;
	if ( $paid_guide_query->have_posts() ) {
		$i++;
		$output .= "<p><span>Comprehensive Guides: </span>\n";
		while ($paid_guide_query->have_posts()) {
			$paid_guide_query->the_post();
			global $post;


				/* From http://codex.wordpress.org/The_Loop_in_Action */
				$last="";
				if( ($paid_guide_query->current_post + 1) == ($paid_guide_query->post_count) )
				{
				 $last = 'class="last"';
				}


			if ($attr['use_short_headline']) {
				//$output .= '<li>';
				$output .= '<a href="' . get_permalink() . '" title="Permanent link to ' . get_the_title() . '" rel="bookmark" '.$last.'>';
				$output .= get_post_meta($post->ID, "short_name", $single = true);
				$output .= '</a>';
				//$output .= "</li>\n";
			} else {
				//$output .= '<li>';
				$output .= '<a href="' . get_permalink() . '" title="Permanent link to ' . get_the_title() . '" rel="bookmark" '.$last.'>' . get_the_title() . '</a>';
				//$output .= "</li>\n";
			}
      	}
		$output .="</p>\n";

		wp_reset_query();  // Restore global post data stomped by the_post().
	}

	return $output;
}
add_shortcode('paid_guide_list', 'paid_guide_list');


// free reports in footer
function free_reports_list($attr, $content = '') {
	$attr = shortcode_atts(
	array(
		'posts_per_page' => -1,
		'orderby' => 'date',
		'post_type' => 'my_downloads',
		'use_short_headline' => 0
		)
		,$attr
	);


	$query_params = array(
		//'post_type' => $attr['post_type'],
		'post_type' => $attr['post_type'],
		'posts_per_page' => $attr['posts_per_page'],
		'post_status' => 'publish'
	);

	$freemium = new WP_Query($query_params);
	$i = 100;

	if ( $freemium->have_posts() ) {
		$i++;
		//$output .= "<p><span>Free Downloads: </span>\n";
		$output = '<ul>';
		while ($freemium->have_posts()) {
			$freemium->the_post();
			global $post;

				/* From http://codex.wordpress.org/The_Loop_in_Action */
				$last="";
				if( ($freemium->current_post + 1) == ($freemium->post_count) )
				{
				 $last = 'class="last"';
				}


			if ($attr['use_short_headline']) {
				$output .= '<li>';
				$output .= '<a href="' . get_permalink() . '" title="Permanent link to ' . get_the_title() . '" rel="bookmark" '.$last.'>';
				$output .= get_post_meta($post->ID, "short_name", $single = true);
				$output .= '</a>';
				$output .= "</li>\n";
			} else {
				$output .= '<li>';
				$output .= '<a href="' . get_permalink() . '" title="Permanent link to ' . get_the_title() . '" rel="bookmark" '.$last.'>' . get_the_title() . '</a>';
				$output .= "</li>\n";
			}
      	}
		$output .="</ul>\n";

		wp_reset_query();  // Restore global post data stomped by the_post().
	}

	return $output;
}
add_shortcode('free_reports_list', 'free_reports_list');


// function used inside my_widget_topics_func

function topic_nav_open($category_name, $seq, $subcat_limit=5) {
    $ubertopic_id = get_cat_ID(htmlentities($category_name));
    $parent_category = get_category($ubertopic_id);
    $parent_slug = $parent_category->category_nicename;
    $topics =  get_categories('child_of=' . $ubertopic_id . '&hide_empty=0');
    $is_open = 'no';
	$active_category = '';

    if ( is_archive() || isset($_REQUEST['topicid']) ) { // detailed archive page or uber archive page
    	global $cat;
		if (!empty($topics)) {
			  foreach ($topics as $thiscat) { // compare $cat (this category when on an archive page) to all of the topics under each uber category.
				  // if there is a match, set that nav open
				  //	echo $thiscat->cat_ID.'-'.$cat.'<br>';
				  if ( $thiscat->cat_ID == $cat || $ubertopic_id == $_REQUEST['topicid'] ) {
					  $is_open = 'yes';
				  }
			  }
		} else {
			if ( $cat == $ubertopic_id) { $active_category = ' class="active_category" '; }
		}
    	if ($is_open == 'yes' ) { // if the child category exists while looping through the categories in the uber category, the set the uber category open
    		// open display
    		$nav_display = "block";
    		$nav_image = 'close.gif';
    		$nav_alt = 'close';
    	} else {
    		// closed display
    		$nav_display = "none";
    		$nav_image = 'open.gif';
    		$nav_alt = 'open';
    	}
    } elseif (is_single()) {
    	$getcatarray = get_the_category();
    	$cat = $getcatarray[0]->cat_ID;
    	foreach ($topics as $thiscat) {
    		// compare $cat (this category when on an  page) to all of the topics under each uber category.
    		// if there is a match, set that nav open
    		//	echo $thiscat->cat_ID.'-'.$cat.'<br>';
    		if ($thiscat->cat_ID == $cat) {
    			$is_open = 'yes';
    		}
    	}
    	if ($is_open == 'yes' ) { // if the child category exists while looping through the categories in the uber category, the set the uber category open
    		// open display
    		$nav_display = "block";
    		$nav_image = 'close.gif';
    		$nav_alt = 'close';
    	} else {
    		// closed display
    		$nav_display = "none";
    		$nav_image = 'open.gif';
    		$nav_alt = 'open';
    	}
		if ( $cat == $ubertopic_id) { $active_category = ' class="active_category" '; }
    } else {
    	$nav_display = "none";
    	$nav_image = 'open.gif';
    	$nav_alt = 'open';
    }


	$nav_icon_none = '';
    $outputx .= '<li '.$active_category.'>' . $nav_icon_none . '<a href="/category/daily/' . $parent_slug . '/">' . htmlentities($category_name) . "</a>";
    if ( count($topics) ) {
	    $outputx .= "\n" . '<ul id="contentDivImg' . $seq . '" class="sub-nav" style="display: ' . $nav_display . ';">' . "\n";
	    $j = 0;
	    foreach ($topics as $topic) {
	    	$j++;
	    	if ($j > $subcat_limit) {
	    		$displayitem = 'display: none;';
	    	} else {
	    		$displayitem = 'display: list-item;';
	    	}
	    	$outputx .= '<li id="' . $ubertopic_id . '-cat-' . $j . '" class="cat-post-item"  style="' . $displayitem . '">';
	    	$outputx .= '<a rel="bookmark" title="Permanent link to ' . $topic->name . '" href="/category/daily/' . $parent_slug . '/' . $topic->slug . '/">' . $topic->name . '</a>';
	    	$outputx .= '</li>' . "\n";
	    }
	    if ( $subcat_limit < count($topics) ) {
	    	$outputx .= '<li><a onclick="toggleCatNav(\'' . $ubertopic_id . '\',' . $j . ');" id="' . $ubertopic_id . '-more" class="moreLink" style="">&hellip;more</a></li>';
	    }
	    $outputx .= "</ul>\n";
    }
    $outputx .= "</li>\n";

    return $outputx;

}


// Sidebar TOPICS widget

function my_widget_topics_func($attr) {
	$attr = shortcode_atts( array('title' => '', 'topics' => '', 'subtopic_limit' => 10), $attr );
	$output = '<div class="styled-box browse">' . "\n";
	if ( '' != trim($attr['title']) ) {
		$output .= '<h3 class="widget-title">' . $attr['title'] . "</h3>\n";
	}
	$output .= '<div class="menu-browse-topics-menu-container">' . "\n";
	$output .= '<ul class="menu-browse-topics-menu">' . "\n";
	$topic_array = explode('|', $attr['topics']);
	for ( $i = 0; $i < count($topic_array); $i++ ) {
		$output .= topic_nav_open( $topic_array[$i], $i, $attr['subtopic_limit'] );
	}
	$output .= "</ul>\n";
	if ( !is_page('Browse Topics') ) {
	 $output .= '<p class="arrow_link"><a href="' . site_url('/browse-topics/', 'http') . '">See all topics</a></p>' . "\n";
	}
	$output .= "</div>\n";
	$output .= "</div>\n";
	return $output;
}
add_shortcode('my_widget_topics', 'my_widget_topics_func');



function my_widget_viewtopics_func_all($attr) {

	$attr = shortcode_atts( array(
		'title' => ''),
	$attr );

	$output = '<div class="bt">' . "\n";
	if ( '' != trim($attr['title']) ) {
		$output .= '<h3 class="widget-title">' . $attr['title'] . "</h3>\n";
	}
	$output .= '<div class="menu-browse-topics-menu-container">' . "\n";
	$output .= '<ul id="menu-browse-topics-menu" class="menu">' . "\n";

	$args = array(
	'type'                     => 'post',
	'child_of'                 => '',
	'parent'                   => 1460,
	'orderby'                  => 'name',
	'order'                    => 'ASC',
	'hide_empty'               => 0,
	'hierarchical'             => 1,
	'exclude'                  => '',
	'include'                  => '',
	'number'                   => '',
	'taxonomy'                 => 'category',
	'pad_counts'               => false );

	$categories = get_categories( $args );
	$insertMensHealth = false;
	$insertWomensHealth = false;



	// start loop
	foreach ($categories as $category) {
	$insert_menshealth= strcmp ("Men's Health", $category->name );
	$insert_womenshealth = strcmp ("Women's Health", $category->name );
	$string =  category_description(get_category_by_slug($category->slug)->cat_ID);
	$firstSentence = get_first_sentence($string);

   // insert custom post types or other links alphabetically that are not under Daily
	if ($insert_changetotopicname < 0 && $insertChangeToTopicName) {
		$output .= '<li class="cat-post-item change-to-topic-name">';
		$output .= "<a rel='bookmark' title='Permanent link to ???' href='/change-to-topic-slug/'>???</a>";
		$output .= '<span class="description change-to-topic-name">';
		$output .= "<a rel='bookmark' title='Permanent link to ???' href='/change-to-topic-slug/'>";
		$output .= category_description(get_category_by_slug($category->slug)->cat_ID). '</a>';
		$output .= '</span>';
		$output .= '</li>' . "\n";
		$insertMensHealth = false;
	}

	$output .= '<li class="cat-post-item ' . $category->slug .'">';
	$output .= '<span class="topic">';
	$output .= '<a rel="bookmark" title="Permanent link to ' . $category->name . '" href="' . get_category_link( $category->cat_ID ) . '">' . $category->name . '</a>';
	$output .= '</span>';
	$output .= '<span class="description '. $category->slug .'">';


	$output .= '<a rel="bookmark" title="Permanent link to ' . $category->name . '" href="' . get_category_link( $category->cat_ID ) . '">';
	//$output .= category_description(get_category_by_slug($category->slug)->cat_ID). '</a>';
	$output .= $firstSentence. '</a>';
	$output .= '</span>';
	$output .= '</li>' . "\n";

	}
	// end loop
	$output .= "</ul>\n";
	$output .= "</div>\n";

	$output .= "</div>\n";
	return $output;
}
add_shortcode('my_widget_viewtopics_all', 'my_widget_viewtopics_func_all');


function my_widget_shopp_accordion_func($attr) {

	// takes a comma separated list of topics (categories)
	// and outputs as an accordion, with all children beneath the topics

	$attr = shortcode_atts( array(
		'title' => '',
		'topics' => 'Uncategorized'
		),
	$attr );

	if ($attr['topics']) {
		$topic_array=explode(',',$attr['topics']);
	}

	$output = '<div class="bt">' . "\n";
	if ( '' != trim($attr['title']) ) {
		$output .= '<h3 class="widget-title">' . $attr['title'] . "</h3>\n";
	}
	$output .= '<div class="menu-browse-topics-menu-container accordion-widget">' . "\n";
	$output .= '<ul id="menu-browse-topics-menu" class="menu accordion" data-accordion data-options="multi_expand:true;toggleable: true">' . "\n";

	$i=0;

	foreach( $topic_array as $topic) {

		$output .= '
		<li class="accordion-navigation '. $topic .'">
			<a href="#panel'.$i.'_topic" class="topic-top">'.$topic.'</a>
			<div id="panel'.$i.'_topic" class="content">
				<ul>';
		if(!$local_cat=get_cat_ID( $topic ))
			$local_cat=1;

		$args = array(
		'type'                     => 'page',
		'child_of'                 => $local_cat,
		'parent'                   => '',
		'orderby'                  => 'name',
		'order'                    => 'ASC',
		'hide_empty'               => '',
		'hierarchical'             => '1',
		'exclude'                  => '',
		'include'                  => '',
		'number'                   => '',
		'taxonomy'                 => 'shopp-products',
		'pad_counts'               => false );

		$categories = get_categories( $args );
		// start loop
		foreach ($categories as $category) {


		$output .= '<li class="cat-post-item subcategory';

		$cparent=$category->parent;

		// if($cparent=='11659' || $cparent=='80' || $cparent=='11656' || $cparent=='76' || $cparent=='11658' || $cparent=='71' ){
			// $output .='subcategory';
		// }

		$output .= '">';
		$output .= '<a rel="bookmark" title="Permanent link to ' . $category->name . '" href="' . get_category_link( $category->cat_ID ) . '">' . $category->name . '</a>';
		$output .= '</li>' . "\n";
		}

		$output .= '</ul></div></li>';
		$i++;
	}
	// end loop
	$output .= '</ul></div>';
	$output .= "</div>\n";
	return $output;
}

add_shortcode('my_widget_shopp_accordion', 'my_widget_shopp_accordion_func');

function my_widget_pub_topics_accordion_func($attr) {
	//this shortcode creates an accordion widget of topics for magazine/publication-specific categories.
	// Uses Category IDs as the category names would be the same as the daily topics.

	$attr = shortcode_atts( array( 'title' => '', 'topics' => ''), $attr );
	$output = '<div class="bt">' . "\n";

	if ( $attr['topics'] ){ $topic_array = explode( ',', $attr[ 'topics' ] ); }
	if ( '' != trim( $attr['title'] ) ) { $output .= '<h3 class="widget-title">' . $attr['title'] . "</h3>\n"; }

		$output .= '<div class="menu-browse-topics-menu-container accordion-widget">' . "\n";
			$output .= '<ul id="menu-browse-topics-menu" class="vertical menu" data-accordion-menu>' . "\n";

	$i=0;
	foreach( $topic_array as $topic) {

		$output .= '<li class="'. $topic .'">';
		if ($topic != 'Videos') {

		$output .= '<a href="#">'.get_the_category_by_id($topic).'</a>';
		//$output .= '<div id="'.$i.'_topic">'; // this div breaks the top level accordion
			$output .= '<ul class="menu vertical nested">';

			if( !$local_cat = is_category( $topic ) ){ $local_cat = 211; }//change category ID for Topics category here
			$output .= wp_list_categories( array(

				'title_li' => '',
				'child_of' => $topic,
				'orderby' => 'name',
				'order' => 'ASC',
				'hide_empty' => false,
				'hierarchical' => true,
				'taxonomy' => 'category',
				'pad_counts' => false,
				'echo' => false

			) );

						$output .= '</ul>';
					//$output .= '</div>';
				$output .= '</li>';

			$i++;

		} else {

					$output .= '<div onClick="location.href=/video/">'.$topic.'</div>';
				$output .= '</li>'; // this was missing

		}

	}

			$output .= '</ul>';
		$output .= '</div>';
	$output .= "</div>\n";

	return $output;

}

add_shortcode('my_widget_pub_topics_accordion', 'my_widget_pub_topics_accordion_func');


function my_authors($attr, $content = '')
{
		$attr = shortcode_atts(
		array(
			'posts_per_page'	=> '10'
		)
		,$attr
	);

$privatePosts = get_private_posts_cap_sql('post');

global $wpdb;
$query = <<<QUERY
SELECT
	`post_author`
FROM `$wpdb->posts`
WHERE
	`post_type` = 'post' AND
	`post_author` != 1 AND
	{$privatePosts}
GROUP BY `post_author`
ORDER BY COUNT(ID) DESC
QUERY;

 $users = $wpdb->get_col($wpdb->prepare($query));
		  $output = '<ul>';
		  // Loop through users
		  foreach ($users as $iUserID) {
			  // Get user data
			  $user = get_userdata($iUserID);

			  /**
			   * The strtolower and ucwords part is to be sure the full names will all be
			   * capitalized.
			   */
			  $userLink = '<a href="/author/'.$user->user_login.'" title="'.$user->display_name.'">' .$user->display_name.'</a>';

			  if( in_array('editor', array_keys($user->wp_capabilities)) ) {
				  $output .= '<li class="cat-post-item">'.$userLink.'</li>';
			  }
		  }
		$output .= "</ul>";
	return $output;
}
add_shortcode('my_authors', 'my_authors');


/**
 * function priority_code
 * Handles a shortcode by getting several attributes and displaying them in preformatted HTML.
 * [priority_code]
 *
 * @param array $attr - Attributes of the shortcode.
 *
 * @param string[optional] $content - Content of the shortcode.
 *
 * @return mixed - the variable from the global scope (needs to be something
 * 		that can be echo'd)
 */
function priority_code($attr, $content='') {

	// Set the defaults
	$attr = shortcode_atts(array(
		'title'	=> '',
		'post_id'	=> '',
		'form_action'	=> '',
		'instructions1' => '',
		'instructions2' => '',
		'instructions3' => '',
		'action_link' => '',
		'action_button' => '',
		'action_text' => '',
		'readmore_link' => '',
		'readmore_button' => '',
		'readmore_text' => ''
		), $attr);

remove_filter('shortcode_atts', 'mequoda_tag_links');
$templateUri = get_template_directory_uri(); //apply update 5/15/09
$date = date('ym'); //apply update 5/15/09

$my_output	 = '<div align="center">'."\n";
$my_output	.= '<div id="container_promotion">'."\n";
$my_output	.= '<div style="border-bottom:2px solid #758FC4;background-color:#fff;padding:12px 0;"> <a href="/"><img src="'.get_template_directory_uri().'/img/design/insidearm_logo.gif" alt="'.get_bloginfo('name').'" border="0"></a></div>'."\n";
$my_output	.= '<div class="section_promotion" style="padding:0 12px;">'."\n";
$my_output	.= '<div class="center">'."\n";
$my_output	.= '<h3>'.$attr['title'].'</h3>'."\n";
$my_output	.= '<p class="small">'.$attr['instructions1'].'</p>'."\n";
$my_output	.= '<div class="box" style="margin-bottom:12px;">'."\n";
$my_output	.= '<h5 class="text"><strong>'.$attr['instructions2'].'</strong></h5>'."\n";

//$my_output	.= '<form action="'.$attr['form_action'].'" method="get" name="promotion" id="promotion" >'."\n";
$my_output	.= "<form action='{$attr['form_action']}' method='get' name='promotion' id='promotion' onsubmit=\"if (document.getelementbyid('mqsc').value == '') {document.getelementbyid('mqsc').value = 'DM{$date}';}\" >\n";//apply update 5/15/09

$my_output	.= '<div class="section_spacer">'."\n";
//$my_output	.= '<input  class="textbox" style="width:200px;" type="text" name="promotioncode" id="promotioncode" value="" />'."\n";
$my_output	.= '<input type="hidden" name="dtd" value="'.$attr['post_id'].'" />'."\n";
//$my_output	.= '<input type="hidden" name="signup_for_this_free_newsletter_topic" value="'.$attr['signup_for_this_free_newsletter_topic'].'" />'."\n";
$my_output	.= '<input  class="textbox" style="width:200px;" type="text" name="mqsc" id="mqsc" value="" />'."\n";
$my_output	.= '</div>'."\n";

if ($attr['readmore_link'] != 'none') {
	$sep = (strpos($attr['readmore_link'], '?') === false)? '?':'&';
	$my_output	.= "<a href='{$attr['readmore_link']}' onclick=\"if (document.getelementbyid('mqsc').value == '') {document.getelementbyid('mqsc').value = 'DM{$date}';} this.href += '{$sep}mqsc=' + document.getElementById('mqsc').value;\">" . $attr['readmore_text'] . "</a><br/>\n";
}


// $my_output	.= "&nbsp;<input type='image' name='submit' id='submit' src='{$templateUri}/img/elements/{$attr['action_button']}' alt='{$attr['action_text']}'/>\n";

$my_output .= '<input  src="{$templateUri}/img/elements/{$attr[\"action_button\"]}" type="submit" value="'.$attr['action_text'].'" name="'.$attr['action_text'].'" class="submit">';


$my_output	.= '</form>'."\n";
$my_output	.= '</div>'."\n";
$my_output	.= '<!-- end class box -->'."\n";
$my_output	.= '<div>'."\n";
$my_output	.= '<p class="small">'.$attr['instructions3'].'</p>'."\n";
$my_output	.= '</div>'."\n";
$my_output	.= '</div>'."\n";
$my_output	.= '<!-- end class section_promotion -->'."\n";
$my_output	.= '</div>'."\n";
$my_output	.= '<!-- end id container_promotion -->'."\n";
$my_output	.= '</div>'."\n";

return $my_output;

}
add_shortcode('priority_code', 'priority_code');


// ==========================================================
// MAGAZINE BUTTONS
// ==========================================================

function magazine_buttons($attr) {

	$attr = shortcode_atts(array(
		'mag'	=> false,
		'mini'	=> false
		), $attr);

	$mag = $attr['mag'];
	$user_id = (is_user_logged_in()) ? get_current_user_id() : false;
	$membertype = ($_REQUEST['membertype']) ? $_REQUEST['membertype'] : false;
	$web = false;
	$tablet = false;
	$print = false;
	$subscriber = false;
	$expand = array('', 'start');

	$mags = get_magazine_array();
	$online = $mags[$mag]['online'];
	$slug = $mags[$mag]['slug'];

	if ($user_id && $mag) {
		$now = date('Ymd');
		if ($now < get_user_meta($user_id, 'expire_date_'.$mag.'_web', true)) { $web = true; }
		if ($now < get_user_meta($user_id, 'expire_date_'.$mag.'_tablet', true)) { $tablet = true; }
		if ($now < get_user_meta($user_id, 'expire_date_'.$mag.'_print', true)) { $print = true; }
	}

	if ($online) {
		$subscriber = ($web && $tablet && $print) ? true : false;
		if ($web || $tablet || $print) { $expand = array('full', 'expand'); }
	} else {
		$subscriber = ($print) ? true : false;
	}

	$output = '<!-- magazine_buttons shortcode.php -->';

	if ($mag) {

		if ($mini) {

			$output .= '<ul class="inline-list">';

			if (!$user_id && !$membertype) {
				$output .= '<li><a href="#" data-open="Login" class="button full">Log in</a></li>';
			}

			if (!$subscriber && !$membertype) {
				$output .= '<li><a href="/subscription-offers/'.$slug.'" class="button full">Subscribe Now</a></li>';
			}

			if ($membertype == 'activetrial') {
				$output .= '<li><a href="/subscription-offers/'.$slug.'/?dtd=trial-renew" class="button full">Renew Now</a></li>';
			} else if ($membertype == 'expired') {
				$output .= '<li><a href="/subscription-offers/'.$slug.'/?dtd=expired-renew" class="button full">Renew Now</a></li>';
			}

			if ($user_id && $online) {
				$output .= '<li><a href="/'.$mag.'-issues" class="button full">Subscribers, View Issue</a></li>';
			}

			$output .= "</ul>\n";

		} else {

			if (!$user_id && !$membertype) {
				$output .= '<div class="callout">Already a subscriber? <a href="#" data-open="Login">Login here</a></div>';
			}

			if (!$subscriber && !$membertype) {
				$output .='<p style="margin-bottom:5px;">If you are not a '.$expand[0].' subscriber, click the "SUBSCRIBE NOW" button to '.$expand[1].' your subscription today.';
				if ($online) {
					$output .=' Subscribers have immediate & unlimited access to our articles and more!';
				}
				$output .='</p>';
				$output .= '<p><a href="/subscription-offers/'.$slug.'/" class="button rmargin-toc">Subscribe Now</a></p>';
			}

			if ($membertype == 'activetrial') {
				$output .= '<p><a href="/subscription-offers/'.$slug.'/?dtd=trial-renew" class="medium alert radius button full">Renew Now</a></p>';
				$output .= '<p><a href="/" class="button rmargin-toc">Learn More</a></p>';
			} else if ($membertype == 'expired') {
				$output .= '<p><a href="/subscription-offers/'.$slug.'/?dtd=expired-renew" class="medium alert radius button full">Renew Now</a></p>';
				$output .= '<p><a href="/" class="button rmargin-toc">Learn More</a></p>';
			}

			if (!is_user_logged_in()) {
				$output .= '<p><b>Looking for more?</b>  <a href="/'.$mag.'-issues">Browse through our Issue Archive</a>.</p>';
			}

		}

	}

	$output .= '<!-- /magazine_buttons -->';

 	return $output;

}

add_shortcode('magazine_buttons', 'magazine_buttons');

function magazine_buttons_mini($attr) {

	$attr = shortcode_atts(array(
		'mag'	=> false
		), $attr);

	$mag = $attr['mag'];

	return magazine_buttons(array('mag' => $mag, 'mini' => true));

}

add_shortcode('magazine_buttons_mini', 'magazine_buttons_mini');


/// rclp_featured_comments
function rclp_featured_comments($attr) {
	global $post;
	$attr = shortcode_atts(array(
		'custom_key' => 'featured_comments_IDs',
		'title' => 'Featured Comments',
        'post_id' => ''
		), $attr);
	$id = $attr['post_id'] ? $attr['post_id'] : $post->ID;

	$output = '';
	if ( $commentIDs = get_post_meta($id, $attr['custom_key'], true) ) {
        $output  = '<div class="styled-box rclpcomments bt">';
		$output .= '<h3 class="widget-title">'.$attr['title'].'</h3>';
        $output .=  '<div class="styled-box-content">';
		$output .= "<ul class='no_bullet comment-box'>\n";
		$pieces = explode(" ", $commentIDs);
		foreach($pieces as $ftID)  {
			$str = $ftID;
			$num = (int)$str;
			$commentid = get_comment($num);
			$commentid->comment_ID;
			$authorfullname  = $commentid->comment_author;
			$firstname = explode(" ", $authorfullname);
			$output .= '<li class="divider"><p class="commentor smalltext tight">' . $firstname[0] . '<br>';
			$output .= '' .  mysql2date('l jS F, Y', $commentid->comment_date) . '</p>'; // comment_date('n-j-Y') //date('M j Y', $begin);
			$output .= '<p>' . $commentid->comment_content . "</p></li>\n";
		}
		$output .= "</ul>\n";
        $output .=  '</div>';
        $output .=  '</div>';
	}
	return $output;
}
add_shortcode('rclp_featured_comments', 'rclp_featured_comments');


function sllp_buttons($attr) {
	global $user_ID, $post;
	$attr = shortcode_atts(array(
		'aaa' => 'nul',
        'bbb' => 'nul'
		), $attr);

		$post_id =  get_the_ID();
		$link = $magsuburl;


          $output  =  '<div class="styled-box rclp_buttons sllp_buttons">';
          $output .=  '<div id="categoryposts-recent-101" class="widget-free-reports">';
          $output .=  '<p class="nice full button radius"><a href="'.$link.'">Subscribe Today!</a></p>';
          $output .=  '<p class="nice full button radius"><a href="#comments">Add a Testimonial</a></p>';
		  $output .=  '</div>';
          $output .=  '</div>';

	return $output;
}
add_shortcode('sllp_buttons', 'sllp_buttons');


//  Subpage Peek - allows Parent pages to display excerpts from Child pages [subpage_peek]
function subpage_peek() {
	global $post;

	//query subpages
	$args = array(
		'post_parent' => $post->ID,
		'post_type' => 'page',
		'order' => 'ASC',
		'orderby'=> 'menu_order'
	);
	$subpages = new WP_query($args);

	// create output
	if ($subpages->have_posts()) :
		$output = '';
		while ($subpages->have_posts()) : $subpages->the_post();
			$output .= '
				<div class="page-archive">
					<div class="grid-x">
						<div class="small-3 cell">
							<a href="'.get_permalink().'">'.get_the_post_thumbnail($page->ID, 'subpage_peek-thumbnail').'</a>
						</div>
						<div class="small-9 cell">
							<h3 class="page-name"><a href="'.get_permalink().'">'.get_the_title().'</a></h3>
							<p>'.get_the_excerpt().' </p>
						</div>
					</div>
				</div>';
		endwhile;
		$output .= '';
	else :
		// $output = '<p>No subpages found.</p>';
	endif;

	// reset the query
	wp_reset_postdata();

	// return something
	return $output;
}

add_shortcode('subpage_peek', 'subpage_peek');


/** Freemium Shortcodes **/

function freemium_desc() {
            $output .= _FREEMIUM_DESC;
			return $output;
			}
add_shortcode('freemium_desc', 'freemium_desc');

function freemiumPDF_link() {
	if ( is_user_logged_in() ) {
	$freemium_id = absint($_REQUEST['dtd']);
		if ( empty($freemium_id) ) {
			$freemium_id = my_DEFAULT_FREE_REPORT;
		}

            $query_params = array(
				'p' => $freemium_id,
				'showposts' => 1,
				'post_type' => array( 'my_downloads'),
				'post_status' => 'publish'
			);

			$freemium = new WP_Query($query_params);
			if ( $freemium->have_posts() ) {
				while ( $freemium->have_posts() ) {
					$freemium->the_post();
		            $title = get_the_title();
		            $_SESSION['download_file'] = get_post_meta($freemium_id, 'file_name', TRUE);

					// Output freemium Link
					if ( isset($_SESSION['download_file']) && isset($freemium_id) ) {
					    $upsell_post_ID = get_post_meta($freemium_id, 'upsell_to_post_id', TRUE);
		                $default_attr = array(
		                    'class'	=> 'thank-you-upsell',
		                    'alt'	=> the_title_attribute('echo=0'),
		                    'title'	=> the_title_attribute('echo=0'),
		                );

		$output .= ' <a class="medium button radiusX full fullOnMobile" href="/get-download/force-download/?dtd='.$freemium_id.'">Download PDF Now!</a>';

     wp_reset_postdata();

	// return something
	return $output;

		}}
  	}
  }
}
add_shortcode('freemiumPDF_link', 'freemiumPDF_link');

function freemium_link() {
	$freemium_id = absint($_REQUEST['dtd']);
		if ( empty($freemium_id) ) {
			$freemium_id = my_DEFAULT_FREE_REPORT;
		}

            $query_params = array(
				'p' => $freemium_id,
				'showposts' => 1,
				'post_type' => array( 'my_downloads'),
				'post_status' => 'publish'
			);

			$freemium = new WP_Query($query_params);
			if ( $freemium->have_posts() ) {
				while ( $freemium->have_posts() ) {
					$freemium->the_post();
		            $title = get_the_title();
		            $_SESSION['download_file'] = get_post_meta($freemium_id, 'file_name', TRUE);

					// Output freemium Link
					if ( isset($_SESSION['download_file']) && isset($freemium_id) ) {
					    $upsell_post_ID = get_post_meta($freemium_id, 'upsell_to_post_id', TRUE);
		                $default_attr = array(
		                    'class'	=> 'thank-you-upsell',
		                    'alt'	=> the_title_attribute('echo=0'),
		                    'title'	=> the_title_attribute('echo=0'),
		                );

		$output .= ' <a class="button fullOnMobile" href="/get-download/force-download/?dtd='.$freemium_id.'">PDF Download</a>';

     wp_reset_postdata();

	// return something
	return $output;

	}}
  }
}
add_shortcode('freemium_link', 'freemium_link');


function freemium_html_link() {
	global $wpdb;

	$freemium_id = absint($_REQUEST['dtd']);
	if ( empty($freemium_id) ) {
		$freemium_id = my_DEFAULT_FREE_REPORT;
	}

	$slug = get_post_field('post_name', $freemium_id);

	$sql = $wpdb->prepare("SELECT ID FROM wp_posts WHERE (post_type='my_fr_online') AND (post_status = 'publish') AND (post_name = '%s');", $slug);
	$id = $wpdb->get_var($sql);

	if ($id) {
		$output .= ' <a class="medium button radiusx fullOnMobile" href="/free-reports-online/'.$slug.'">View on the Web</a>';
		return $output;
	}
}
add_shortcode('freemium_html_link', 'freemium_html_link');

function freemium_name() {
	$freemium_id = absint($_REQUEST['dtd']);
		if ( empty($freemium_id) ) {
			$freemium_id = my_DEFAULT_FREE_REPORT;
		}
            $query_params = array(
				'p' => $freemium_id,
				'showposts' => 1,
				'post_type' => array( 'my_downloads'),
				'post_status' => 'publish'
			);

			$freemium = new WP_Query($query_params);
			if ( $freemium->have_posts() ) {
				while ( $freemium->have_posts() ) {
					$freemium->the_post();
		            $title = get_the_title();
					 $output = $title;
					 wp_reset_postdata();
					return $output;
	}
  }
}
add_shortcode('freemium_name', 'freemium_name');

function share_structure($attr, $content='') {
	global $user_ID, $post;
	$slug =  $post->post_name;
	$post_ID = $post->ID; // same thing: $rclp_ID = get_the_ID();
	$share_structure = get_post_meta($post_ID,'share_structure', TRUE);
	return $share_structure;
}
add_shortcode('share_structure', 'share_structure');


/** Magazine Title **/
function magazine_title() {
	return get_magazine_title($_GET['mag']);
}
add_shortcode('magazine_title', 'magazine_title');


function select_magazine_loggedin( $atts, $content = null ) {
	if ( is_user_logged_in() ) { return  $content ; }
}
add_shortcode( 'select_magazine_loggedin', 'select_magazine_loggedin' );

function select_magazine_loggedout( $atts, $content = null ) {
	if ( !is_user_logged_in() ) { return  $content ; }
}
add_shortcode( 'select_magazine_loggedout', 'select_magazine_loggedout' );


function content_subscribed( $atts, $content = null ) {
	if ( is_user_logged_in() ) { return  $content ; }
}
add_shortcode( 'content_subscribed', 'content_subscribed' );

function content_not_subscribed( $atts, $content = null ) {
	if ( is_user_logged_in() ) { return  $content ; }
}
add_shortcode( 'content_not_subscribed', 'content_not_subscribed' );


function content_loggedin( $atts, $content = null ) {
	if ( is_user_logged_in() ) { return  $content ; }
}
add_shortcode( 'content_loggedin', 'content_loggedin' );

function content_loggedout( $atts, $content = null ) {
	if ( !is_user_logged_in() ) { return  $content ; }
}
add_shortcode( 'content_loggedout', 'content_loggedout' );

function meqRRMenu($attr, $content = '') {
		$attr = shortcode_atts(
		array(
			'title'				=> 'none',
			'subtitle' 			=> 'none',
			'menu'				=> 'none'
		)
		,$attr
	);

	if ($attr['subtitle'] != 'none') {
			$class="noMargin";
		} else {
			$class="marginBottom";
		}
	$output = '';
	$output .= '<aside class="widget widget_text meqRRMenu">';
	$output .=  '<div class="bt noDivider">';
	if ($attr['title'] != 'none') {
	$output .=  '<h3 class="widget-title '.$class.'">'.$attr['title'].'</h3>';
	}
	if ($attr['subtitle'] != 'none') {
	$output .=  '<p class="widgetSubtitle">'.$attr['subtitle'].'</p>';
	}

		      $output .=  wp_nav_menu( array(
			            'theme_location' => $attr['menu'], //'health-report',
			            'container' => false,
									'echo' => false,
			            'depth' => 0,
			            'items_wrap' => '<ul class="bullet_list healthReport">%3$s</ul>',
			            'after' => '',
			            'fallback_cb' => 'srbweb_menu_fallback', // workaround to show a message to set up a menu
			            'walker' => new srbweb_walker( array(
			                'in_top_bar' => false,
			                'item_type' => 'li',
			                'menu_type' => 'main-menu'
			            ) ),
			        ) );
	$output .=  '</div>';
	$output .=  '</aside>';
	return $output;

}
add_shortcode('meqRRMenu', 'meqRRMenu');

function alreadySubscriber( $attr ) {
	global $user_id, $mobile, $post;
	$alreadySubscriberLoggedOut = do_shortcode('[get_post_by_name page_title="alreadySubscriberLoggedOut" post_type="uc"]');
	$alreadySubscriberNotSubscribed = do_shortcode('[get_post_by_name page_title="alreadySubscriberNotSubscribed" post_type="uc"]');

	if ( !is_user_logged_in() ) {
		$output = $alreadySubscriberLoggedOut;
	} elseif ( !subscribed( 'CM' ) && !subscribed( 'PMI' ) ) {
		 $output =  $alreadySubscriberNotSubscribed;
	} else {
		 $output =  "<p><a title='Start Reading' href='".first_article_link($post->ID)."' class='button full'>Start Reading Online</a></p>";
	}

	return $output;
}
add_shortcode( 'alreadySubscriber', 'alreadySubscriber' );

/**
 * Easy way to add buttons to widgets and other places as needed
 * @param $attr
 * @param string $content
 *
 * @return string
 * @usage [my_button text="Learn More" size="small" expanded="1" link="/get-report"]
 * size options: tiny, small, medium, large
 * expand option: 0 for no (default) or 1 for yes (this makes button span the entire width of the containing element)
 * link: enter url
 */
function my_button($attr, $content=''){
	$attr = shortcode_atts(
		array(
			'text' => '',
			'size' => 'medium',
			'expanded' => 0,
			'link' => '/',
			'class' => ''
		)
		,$attr
	);
	$c = '';
	if($attr['class'] != ''){
		$c = $attr['class'].' ';
	}

	if($attr['expanded'] == 1){
		$class = $c.$attr['size'].' expanded';
	} else {
		$class = $c.$attr['size'];
	}

	$button = '<a href="'.$attr['link'].'" class="meq-button button '.$class.'">'.$attr['text'].'</a>';

	return $button;
}
add_shortcode('my_button', 'my_button');

/// Masthead
function pub_masthead(){

	global $post;
	$args = array(
		'post_type'			=> 'toc',
		'post_status'		=> 'publish',
		'meta_key'			=> 'toc_issue_date',
		'orderby'			=> 'meta_value',
		'order'				=> 'DESC',
		'posts_per_page'	=> 1,
	);
	query_posts($args);
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			$masthead = false;
			$masthead_array = get_post_meta($post->ID, 'toc_masthead', true);
			if ($masthead_array) {
				echo "<h3 class='widget-title'>Editorial Staff</h3>";
				$masthead = "<section class='row post_content credits collapse'>";
				foreach($masthead_array as $m) {
					$masthead .= "<span class='masthead-title'>".$m['title']."</span>";
					$masthead .= "<span class='masthead-body'>".$m['body']."</span>";
				}
				$masthead .= "</section>";
			}
		}
	}
	echo $masthead;
	wp_reset_postdata();
}
add_shortcode('pub_masthead', 'pub_masthead');
