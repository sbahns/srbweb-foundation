<?php

/**
 * Find the gif, jpg or png file and return the URL
 * Usage: $guide_file = get_the_pdf($post_id);
  */
function get_the_pdf( $main_guide_post ) {
	$args = array(
		'post_type'      => 'attachment',
		'post_mime_type' => 'application/pdf',
		'numberposts'    => 1,
		'post_status'    => null,
		'post_parent'    => $main_guide_post
	);
	$attachments = get_posts( $args );

	if ( $attachments )
		return wp_get_attachment_url( $attachments[0]->ID );

	return '';
}


/*
** Get taxonmy slug
**
*/
function get_taxonomy_slug() {
	$pieces = explode("/", $_SERVER["REQUEST_URI"]);
	return $pieces[2];
}


function get_rclpid_from_querystringslug() {
	$dtd = '';
	if ( isset($_REQUEST['dtd']) ) {
		 $dtd = absint($_REQUEST['dtd']);
	} elseif ( isset($_REQUEST['free_report_id']) ) {
		 $dtd = absint($_REQUEST['free_report_id']);
	} elseif ( isset($_REQUEST['id']) ) {
		 $dtd = absint($_REQUEST['id']);
	}
	return $dtd;
}


// Function for VURL output
// http://code.google.com/apis/analytics/docs/tracking/asyncMigrationExamples.html#VirtualPageviews
function vurl_rclp_newuser($page_type, $product_id) {
	if (!is_user_logged_in() || $_REQUEST['n'] === '1') {
		global $post;
        $output  = "<script type='text/javascript'>\n";
        $output .= "window.onload = function() {\n";
        $output .= "      if (_gaq) \n";
        $output .= "       {\n";
        $output .= "          _gaq.push(['_trackPageview', '/funnel/rclp/unknown/".$product_id."/".$page_type.".html']);\n";
        $output .= "        }\n";
        $output .= "  }\n";
	    $output .= "</script>\n";
		return $output;
	}
}

// Trim Excerpt
// TO DO: This should be a plugin!

function improved_trim_excerpt($text = '', $excerpt_length = 30, $readmore = '', $date_format = '') {
	global $post;
	if ( absint($excerpt_length) < 1 ) {
		$excerpt_length = 30;
	}
	if ('' == $text) {
		if ('' != $post->post_excerpt) {
			$text = $post->post_excerpt;
            $tofilter = 'the_excerpt';
		} else if ('' != get_the_content()) {
			$text = get_the_content();
            $tofilter = 'the_content';
		} else { // this is only if short description ACF field is present
			if ( function_exists('get_field') ) {
				$text = get_field('short_description',get_the_ID() );
			}
			$tofilter = 'the_excerpt';
		}
	}

	$text = str_replace(']]>', ']]&gt;', $text);
	$text = preg_replace('@<script[^>]*?>.*?</script>@si', '', $text);
	$text = preg_replace('#([[]caption)(.*)([[]/caption[]])#', '', $text);

	$text = strip_tags($text, '');
	$words = explode(' ', $text, $excerpt_length + 1);
	$words = array_slice($words, 0, $excerpt_length);
	if ( ! empty($readmore) ) {
		$words[] = '&nbsp; <a href="' . get_permalink() . '">' . $readmore . '</a>';
	} else {
		$words[] = '&nbsp;<a href="' . get_permalink() . '">&#8230;</a>';
	}
	$text = empty($date_format) ? '' : '<span class="post_excerpt_date">' . get_the_date($date_format) . ' | </span>';
	$text .= implode(' ', $words);

	// if ( 'the_content' == $tofilter && class_exists( 'FLBuilder' ) ) {
	// 	// Remove the Beaver builder's render_content filter so it doesn't format the content.
	// 	remove_filter( 'the_content', 'FLBuilder::render_content' );
	// }
	// $text = apply_filters($tofilter, $text);
	// if ( 'the_content' == $tofilter && class_exists( 'FLBuilder' ) ) {
	// 	// Put Beaver builder's render_content filter back.
	// 	add_filter( 'the_content', 'FLBuilder::render_content' );
	// }

	return apply_filters('srbweb_improved_trim_excerpt', $text, $post);
}
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'improved_trim_excerpt');

/**
 * Excerpt For SLLP
 * @param string $text
 * @param int $excerpt_length
 * @param string $readmore
 * @param string $link
 *
 * @return string
 */
function improved_trim_excerpt_subs($text = '', $excerpt_length = 30, $readmore = '', $readmoreClass = 'readmore', $link='', $nolink = false,  $readmore_inline = true ) {

	$text = str_replace(']]>', ']]&gt;', $text);
	$text = preg_replace('@<script[^>]*?>.*?</script>@si', '', $text);

	$text = strip_tags($text, '');
	$words = explode(' ', $text, $excerpt_length + 1);
	$exc = array_slice($words, 0, $excerpt_length);
	$lastWord = array_slice($exc, -1, 1);
	$words = array_slice($exc, 0, $excerpt_length-1);

	$lastWordLength = strlen($lastWord[0]);

	if(strpos($lastWord[0], '.') == $lastWordLength - 1){
		$ellipse = '.. ';
	} else {
		$ellipse = '... ';
	}
	$words[] = $lastWord[0].$ellipse;

	if (!empty($readmore)) {
		if($readmore_inline) {
			if ( $nolink ) {
				$words[] = $readmore;
			} else {
				$words[] = '<a class="' . $readmoreClass . '" href="' . $link . '">' . $readmore . '</a>';
			}
		} else {
			if ( $nolink ) {
				$words[] = '<p>'.$readmore.'</p>';
			} else {
				$words[] = '<p class="no-btm-margin"><a class="' . $readmoreClass . '" href="' . $link . '">' . $readmore . '</a></p>';
			}
		}
	}

	//$text = empty($date_format) ? '' : '<span class="post_excerpt_date">' . get_the_date($date_format) . ' | </span>';
	$text = implode(' ', $words);

	//uncomment to keep html tags in excerpt
	//$text = apply_filters($tofilter, $text);

	return wpautop($text);
}

function html_rclp_excerpt($text = '', $excerpt_length = 55, $readmore = '', $date_format = '', $vicon='', $nameplate_cat='') {
	global $post;

	if ('' == $text) {
		if ('' != $post->post_excerpt) {
			$text = $post->post_excerpt;
            $tofilter = 'the_excerpt';
		} else {
			$text = get_the_content();
            $tofilter = 'the_content';
		}
		$text = str_replace(']]>', ']]&gt;', $text);
		$text = preg_replace('@<script[^>]*?>.*?</script>@si', '', $text);

		$text = strip_tags($text, '');
		$words = explode(' ', $text, $excerpt_length + 1);
		$words = array_slice($words, 0, $excerpt_length);
		if (!empty($readmore)) {
			$words[] = '&nbsp;&#8230; <a href="' . get_permalink() . '?view=html">' . $readmore . '</a>';
		}
		$text = empty($date_format) ? '' : '<span class="post_excerpt_date">' . get_the_date($date_format) . ' | </span>';
		$text .= $vicon;
		$text .= implode(' ', $words);
		$text = apply_filters($tofilter, $text);
	}
	return $text;
}
remove_filter('get_the_excerpt', 'html_rclp_excerpt');
add_filter('html_rclp_excerpt', 'html_rclp_excerpt');


function wpse_allowedtags() {
    // Add custom tags to this string
        return '<script>,<style>,<br>,<em>,<i>,<ul>,<ol>,<li>,<a>,<p>,<img>,<video>,<audio>';
    }

if ( ! function_exists( 'wpse_custom_wp_trim_excerpt' ) ) :

    function wpse_custom_wp_trim_excerpt($wpse_excerpt) {
    $raw_excerpt = $wpse_excerpt;
        if ( '' == $wpse_excerpt ) {

            $wpse_excerpt = get_the_content('');
           // $wpse_excerpt = strip_shortcodes( $wpse_excerpt );
            $wpse_excerpt = apply_filters('the_content', $wpse_excerpt);
           // $wpse_excerpt = str_replace(']]>', ']]&gt;', $wpse_excerpt);
           // $wpse_excerpt = strip_tags($wpse_excerpt, wpse_allowedtags()); /*IF you need to allow just certain tags. Delete if all tags are allowed */

            //Set the excerpt word count and only break after sentence is complete.
                $excerpt_word_count = 75;
                $excerpt_length = apply_filters('excerpt_length', $excerpt_word_count);
                $tokens = array();
                $excerptOutput = '';
                $count = 0;

                // Divide the string into tokens; HTML tags, or words, followed by any whitespace
                preg_match_all('/(<[^>]+>|[^<>\s]+)\s*/u', $wpse_excerpt, $tokens);

                foreach ($tokens[0] as $token) {

                    if ($count >= $excerpt_length && preg_match('/[\,\;\?\.\!]\s*$/uS', $token)) {
                    // Limit reached, continue until , ; ? . or ! occur at the end
                        $excerptOutput .= trim($token);
                        break;
                    }

                    // Add words to complete sentence
                    $count++;

                    // Append what's left of the token
                    $excerptOutput .= $token;
                }

            $wpse_excerpt = trim(force_balance_tags($excerptOutput));

         //       $excerpt_end = ' <a href="'. esc_url( get_permalink() ) . '">' . sprintf(__( 'Learn more', 'wpse' ), get_the_title()) . '</a>';
                $excerpt_more = apply_filters('excerpt_more', ' ' . $excerpt_end);

                //$pos = strrpos($wpse_excerpt, '</');
                //if ($pos !== false)
                // Inside last HTML tag
                //$wpse_excerpt = substr_replace($wpse_excerpt, $excerpt_end, $pos, 0); /* Add read more next to last word */
                //else
                // After the content
                $wpse_excerpt .= $excerpt_more; /*Add read more in new paragraph */

            return $wpse_excerpt;

        }
        return apply_filters('wpse_custom_wp_trim_excerpt', $wpse_excerpt, $raw_excerpt);
    }

endif;

remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'wpse_custom_wp_trim_excerpt');


// Openx slug display for ofies and ads

function get_ox_slug($match_category = 'daily') {
	if (is_home()) {
		return 'home';
	} elseif (is_page('daily')) {
		return 'daily';
	} elseif (is_category()) {
		global $wp_query;
		$category = $wp_query->get_queried_object();
		$category = get_category($category->cat_ID);

		return $category->category_nicename;
	} elseif (is_single()) {
		$cat_ID = get_cat_id($match_category);
		foreach(get_the_category() as $category) {
			if (cat_is_ancestor_of($cat_ID, $category->cat_ID)) {
				return $category->slug;
			}
		}
	}

	return 'ROS';
}

// Openx slug display for ofies and ads

function get_user_state() {
	if (is_user_logged_in()) {
		return 'known';
	} else {
		return 'unknown';
	}
}

function state_list($sel, $return = false) {
	$state_list = '';
	$state_list .= "<option value=''" . ($sel=='' ? ' selected="selected"' : '') . " >Select State/Province</option>";
	$state_list .= "<option value='NA'".($sel=='NA'?' selected="selected"':'').">N/A</option>";
	$state_list .= "<optgroup label='United States'>";
	$state_list .= "<option value='AL'".($sel=='AL'?' selected="selected"':'').">Alabama</option>";
	$state_list .= "<option value='AK'".($sel=='AK'?' selected="selected"':'').">Alaska</option>";
	$state_list .= "<option value='AS'".($sel=='AS'?' selected="selected"':'').">American Samoa</option>";
	$state_list .= "<option value='AZ'".($sel=='AZ'?' selected="selected"':'').">Arizona</option>";
	$state_list .= "<option value='AR'".($sel=='AR'?' selected="selected"':'').">Arkansas</option>";
	$state_list .= "<option value='CA'".($sel=='CA'?' selected="selected"':'').">California</option>";
	$state_list .= "<option value='CO'".($sel=='CO'?' selected="selected"':'').">Colorado</option>";
	$state_list .= "<option value='CT'".($sel=='CT'?' selected="selected"':'').">Connecticut</option>";
	$state_list .= "<option value='DE'".($sel=='DE'?' selected="selected"':'').">Delaware</option>";
	$state_list .= "<option value='DC'".($sel=='DC'?' selected="selected"':'').">District of Columbia</option>";
	$state_list .= "<option value='FM'".($sel=='FM'?' selected="selected"':'').">Federated States of Micronesia</option>";
	$state_list .= "<option value='FL'".($sel=='FL'?' selected="selected"':'').">Florida</option>";
	$state_list .= "<option value='GA'".($sel=='GA'?' selected="selected"':'').">Georgia</option>";
	$state_list .= "<option value='GU'".($sel=='GU'?' selected="selected"':'').">Guam</option>";
	$state_list .= "<option value='HI'".($sel=='HI'?' selected="selected"':'').">Hawaii</option>";
	$state_list .= "<option value='ID'".($sel=='ID'?' selected="selected"':'').">Idaho</option>";
	$state_list .= "<option value='IL'".($sel=='IL'?' selected="selected"':'').">Illinois</option>";
	$state_list .= "<option value='IN'".($sel=='IN'?' selected="selected"':'').">Indiana</option>";
	$state_list .= "<option value='IA'".($sel=='IA'?' selected="selected"':'').">Iowa</option>";
	$state_list .= "<option value='KS'".($sel=='KS'?' selected="selected"':'').">Kansas</option>";
	$state_list .= "<option value='KY'".($sel=='KY'?' selected="selected"':'').">Kentucky</option>";
	$state_list .= "<option value='LA'".($sel=='LA'?' selected="selected"':'').">Louisiana</option>";
	$state_list .= "<option value='ME'".($sel=='ME'?' selected="selected"':'').">Maine</option>";
	$state_list .= "<option value='MH'".($sel=='MH'?' selected="selected"':'').">Marshall Islands</option>";
	$state_list .= "<option value='MD'".($sel=='MD'?' selected="selected"':'').">Maryland</option>";
	$state_list .= "<option value='MA'".($sel=='MA'?' selected="selected"':'').">Massachusetts</option>";
	$state_list .= "<option value='MI'".($sel=='MI'?' selected="selected"':'').">Michigan</option>";
	$state_list .= "<option value='MN'".($sel=='MN'?' selected="selected"':'').">Minnesota</option>";
	$state_list .= "<option value='MS'".($sel=='MS'?' selected="selected"':'').">Mississippi</option>";
	$state_list .= "<option value='MO'".($sel=='MO'?' selected="selected"':'').">Missouri</option>";
	$state_list .= "<option value='MT'".($sel=='MT'?' selected="selected"':'').">Montana</option>";
	$state_list .= "<option value='NE'".($sel=='NE'?' selected="selected"':'').">Nebraska</option>";
	$state_list .= "<option value='NV'".($sel=='NV'?' selected="selected"':'').">Nevada</option>";
	$state_list .= "<option value='NH'".($sel=='NH'?' selected="selected"':'').">New Hampshire</option>";
	$state_list .= "<option value='NJ'".($sel=='NJ'?' selected="selected"':'').">New Jersey</option>";
	$state_list .= "<option value='NM'".($sel=='NM'?' selected="selected"':'').">New Mexico</option>";
	$state_list .= "<option value='NY'".($sel=='NY'?' selected="selected"':'').">New York</option>";
	$state_list .= "<option value='NC'".($sel=='NC'?' selected="selected"':'').">North Carolina</option>";
	$state_list .= "<option value='ND'".($sel=='ND'?' selected="selected"':'').">North Dakota</option>";
	$state_list .= "<option value='MP'".($sel=='MP'?' selected="selected"':'').">Northern Mariana Islands</option>";
	$state_list .= "<option value='OH'".($sel=='OH'?' selected="selected"':'').">Ohio</option>";
	$state_list .= "<option value='OK'".($sel=='OK'?' selected="selected"':'').">Oklahoma</option>";
	$state_list .= "<option value='OR'".($sel=='OR'?' selected="selected"':'').">Oregon</option>";
	$state_list .= "<option value='PW'".($sel=='PW'?' selected="selected"':'').">Palau</option>";
	$state_list .= "<option value='PA'".($sel=='PA'?' selected="selected"':'').">Pennsylvania</option>";
	$state_list .= "<option value='PR'".($sel=='PR'?' selected="selected"':'').">Puerto Rico</option>";
	$state_list .= "<option value='RI'".($sel=='RI'?' selected="selected"':'').">Rhode Island</option>";
	$state_list .= "<option value='SC'".($sel=='SC'?' selected="selected"':'').">South Carolina</option>";
	$state_list .= "<option value='SD'".($sel=='SD'?' selected="selected"':'').">South Dakota</option>";
	$state_list .= "<option value='TN'".($sel=='TN'?' selected="selected"':'').">Tennessee</option>";
	$state_list .= "<option value='TX'".($sel=='TX'?' selected="selected"':'').">Texas</option>";
	$state_list .= "<option value='UT'".($sel=='UT'?' selected="selected"':'').">Utah</option>";
	$state_list .= "<option value='VT'".($sel=='VT'?' selected="selected"':'').">Vermont</option>";
	$state_list .= "<option value='VI'".($sel=='VI'?' selected="selected"':'').">Virgin Islands</option>";
	$state_list .= "<option value='VA'".($sel=='VA'?' selected="selected"':'').">Virginia</option>";
	$state_list .= "<option value='WA'".($sel=='WA'?' selected="selected"':'').">Washington</option>";
	$state_list .= "<option value='WV'".($sel=='WV'?' selected="selected"':'').">West Virginia</option>";
	$state_list .= "<option value='WI'".($sel=='WI'?' selected="selected"':'').">Wisconsin</option>";
	$state_list .= "<option value='WY'".($sel=='WY'?' selected="selected"':'').">Wyoming</option>";
	$state_list .= "</optgroup>";
	$state_list .= "<optgroup label='Canadian Provinces'>";
	$state_list .= "<option value='AB'".($sel=='AB'?' selected="selected"':'').">Alberta</option>";
	$state_list .= "<option value='BC'".($sel=='BC'?' selected="selected"':'').">British Columbia</option>";
	$state_list .= "<option value='MB'".($sel=='MB'?' selected="selected"':'').">Manitoba</option>";
	$state_list .= "<option value='NB'".($sel=='NB'?' selected="selected"':'').">New Brunswick</option>";
	$state_list .= "<option value='NL'".($sel=='NL'?' selected="selected"':'').">Newfoundland and Labrador</option>";
	$state_list .= "<option value='NS'".($sel=='NS'?' selected="selected"':'').">Nova Scotia</option>";
	$state_list .= "<option value='NT'".($sel=='NT'?' selected="selected"':'').">Northwest Territories</option>";
	$state_list .= "<option value='NU'".($sel=='NU'?' selected="selected"':'').">Nunavut</option>";
	$state_list .= "<option value='ON'".($sel=='ON'?' selected="selected"':'').">Ontario</option>";
	$state_list .= "<option value='PE'".($sel=='PE'?' selected="selected"':'').">Prince Edward Island</option>";
	$state_list .= "<option value='QC'".($sel=='QC'?' selected="selected"':'').">Quebec</option>";
	$state_list .= "<option value='SK'".($sel=='SK'?' selected="selected"':'').">Saskatchewan</option>";
	$state_list .= "<option value='YT'".($sel=='YT'?' selected="selected"':'').">Yukon Territory</option>";
	$state_list .= "</optgroup>";
	if ( $return ) {
		return $state_list;
	}
	echo $state_list;
}

function state_list_usa($sel) {
	echo "<option value=''".($sel==''?' selected="selected"':'')." >Select Your State</option>";
	echo "<option value='AL'".($sel=='AL'?' selected="selected"':'').">Alabama</option>";
	echo "<option value='AK'".($sel=='AK'?' selected="selected"':'').">Alaska</option>";
	echo "<option value='AS'".($sel=='AS'?' selected="selected"':'').">American Samoa</option>";
	echo "<option value='AZ'".($sel=='AZ'?' selected="selected"':'').">Arizona</option>";
	echo "<option value='AR'".($sel=='AR'?' selected="selected"':'').">Arkansas</option>";
	echo "<option value='CA'".($sel=='CA'?' selected="selected"':'').">California</option>";
	echo "<option value='CO'".($sel=='CO'?' selected="selected"':'').">Colorado</option>";
	echo "<option value='CT'".($sel=='CT'?' selected="selected"':'').">Connecticut</option>";
	echo "<option value='DE'".($sel=='DE'?' selected="selected"':'').">Delaware</option>";
	echo "<option value='DC'".($sel=='DC'?' selected="selected"':'').">District of Columbia</option>";
	echo "<option value='FM'".($sel=='FM'?' selected="selected"':'').">Federated States of Micronesia</option>";
	echo "<option value='FL'".($sel=='FL'?' selected="selected"':'').">Florida</option>";
	echo "<option value='GA'".($sel=='GA'?' selected="selected"':'').">Georgia</option>";
	echo "<option value='GU'".($sel=='GU'?' selected="selected"':'').">Guam</option>";
	echo "<option value='HI'".($sel=='HI'?' selected="selected"':'').">Hawaii</option>";
	echo "<option value='ID'".($sel=='ID'?' selected="selected"':'').">Idaho</option>";
	echo "<option value='IL'".($sel=='IL'?' selected="selected"':'').">Illinois</option>";
	echo "<option value='IN'".($sel=='IN'?' selected="selected"':'').">Indiana</option>";
	echo "<option value='IA'".($sel=='IA'?' selected="selected"':'').">Iowa</option>";
	echo "<option value='KS'".($sel=='KS'?' selected="selected"':'').">Kansas</option>";
	echo "<option value='KY'".($sel=='KY'?' selected="selected"':'').">Kentucky</option>";
	echo "<option value='LA'".($sel=='LA'?' selected="selected"':'').">Louisiana</option>";
	echo "<option value='ME'".($sel=='ME'?' selected="selected"':'').">Maine</option>";
	echo "<option value='MH'".($sel=='MH'?' selected="selected"':'').">Marshall Islands</option>";
	echo "<option value='MD'".($sel=='MD'?' selected="selected"':'').">Maryland</option>";
	echo "<option value='MA'".($sel=='MA'?' selected="selected"':'').">Massachusetts</option>";
	echo "<option value='MI'".($sel=='MI'?' selected="selected"':'').">Michigan</option>";
	echo "<option value='MN'".($sel=='MN'?' selected="selected"':'').">Minnesota</option>";
	echo "<option value='MS'".($sel=='MS'?' selected="selected"':'').">Mississippi</option>";
	echo "<option value='MO'".($sel=='MO'?' selected="selected"':'').">Missouri</option>";
	echo "<option value='MT'".($sel=='MT'?' selected="selected"':'').">Montana</option>";
	echo "<option value='NE'".($sel=='NE'?' selected="selected"':'').">Nebraska</option>";
	echo "<option value='NV'".($sel=='NV'?' selected="selected"':'').">Nevada</option>";
	echo "<option value='NH'".($sel=='NH'?' selected="selected"':'').">New Hampshire</option>";
	echo "<option value='NJ'".($sel=='NJ'?' selected="selected"':'').">New Jersey</option>";
	echo "<option value='NM'".($sel=='NM'?' selected="selected"':'').">New Mexico</option>";
	echo "<option value='NY'".($sel=='NY'?' selected="selected"':'').">New York</option>";
	echo "<option value='NC'".($sel=='NC'?' selected="selected"':'').">North Carolina</option>";
	echo "<option value='ND'".($sel=='ND'?' selected="selected"':'').">North Dakota</option>";
	echo "<option value='MP'".($sel=='MP'?' selected="selected"':'').">Northern Mariana Islands</option>";
	echo "<option value='OH'".($sel=='OH'?' selected="selected"':'').">Ohio</option>";
	echo "<option value='OK'".($sel=='OK'?' selected="selected"':'').">Oklahoma</option>";
	echo "<option value='OR'".($sel=='OR'?' selected="selected"':'').">Oregon</option>";
	echo "<option value='PW'".($sel=='PW'?' selected="selected"':'').">Palau</option>";
	echo "<option value='PA'".($sel=='PA'?' selected="selected"':'').">Pennsylvania</option>";
	echo "<option value='PR'".($sel=='PR'?' selected="selected"':'').">Puerto Rico</option>";
	echo "<option value='RI'".($sel=='RI'?' selected="selected"':'').">Rhode Island</option>";
	echo "<option value='SC'".($sel=='SC'?' selected="selected"':'').">South Carolina</option>";
	echo "<option value='SD'".($sel=='SD'?' selected="selected"':'').">South Dakota</option>";
	echo "<option value='TN'".($sel=='TN'?' selected="selected"':'').">Tennessee</option>";
	echo "<option value='TX'".($sel=='TX'?' selected="selected"':'').">Texas</option>";
	echo "<option value='UT'".($sel=='UT'?' selected="selected"':'').">Utah</option>";
	echo "<option value='VT'".($sel=='VT'?' selected="selected"':'').">Vermont</option>";
	echo "<option value='VI'".($sel=='VI'?' selected="selected"':'').">Virgin Islands</option>";
	echo "<option value='VA'".($sel=='VA'?' selected="selected"':'').">Virginia</option>";
	echo "<option value='WA'".($sel=='WA'?' selected="selected"':'').">Washington</option>";
	echo "<option value='WV'".($sel=='WV'?' selected="selected"':'').">West Virginia</option>";
	echo "<option value='WI'".($sel=='WI'?' selected="selected"':'').">Wisconsin</option>";
	echo "<option value='WY'".($sel=='WY'?' selected="selected"':'').">Wyoming</option>";
}

function country_list($sel, $return = false) {
	$country_list = '';
	//$country_list .= "<option value=''" . ($sel=='' ? ' selected="selected"' : '') . " >Select Country</option>";
	$country_list .= "<option value='US'" . ($sel=='US' || $sel=='' ? ' selected="selected"' : '') . ">United States of America</option>";
	$country_list .= "<option value='CA'" . ($sel=='CA' ? ' selected="selected"' : '') . " >Canada</option>";
	$country_list .= "<option value='UM'" . ($sel=='UM' ? ' selected="selected"' : '') . ">United States Minor Outlying Islands</option>";
	$country_list .= "<option value='GB'" . ($sel=='GB' ? ' selected="selected"' : '') . ">United Kingdom</option>";
	$country_list .= "<option value='AU'".($sel=='AU'?' selected="selected"':'').">Australia</option>";
	$country_list .= "<option value='NZ'".($sel=='NZ'?' selected="selected"':'').">New Zealand</option>";
	$country_list .= "<option value='AF'".($sel=='AF'?' selected="selected"':'').">Afghanistan</option>";
	$country_list .= "<option value='AL'".($sel=='AL'?' selected="selected"':'').">Albania</option>";
	$country_list .= "<option value='AS'".($sel=='AS'?' selected="selected"':'').">American Samoa</option>";
	$country_list .= "<option value='AD'".($sel=='AD'?' selected="selected"':'').">Andorra</option>";
	$country_list .= "<option value='AO'".($sel=='AO'?' selected="selected"':'').">Angola</option>";
	$country_list .= "<option value='AI'".($sel=='AI'?' selected="selected"':'').">Anguilla</option>";
	$country_list .= "<option value='AQ'".($sel=='AQ'?' selected="selected"':'').">Antarctica</option>";
	$country_list .= "<option value='AG'".($sel=='AG'?' selected="selected"':'').">Antigua and Barbuda</option>";
	$country_list .= "<option value='AR'".($sel=='AR'?' selected="selected"':'').">Argentina</option>";
	$country_list .= "<option value='AM'".($sel=='AM'?' selected="selected"':'').">Armenia</option>";
	$country_list .= "<option value='AW'".($sel=='AW'?' selected="selected"':'').">Aruba</option>";
	$country_list .= "<option value='AT'".($sel=='AT'?' selected="selected"':'').">Austria</option>";
	$country_list .= "<option value='AZ'".($sel=='AZ'?' selected="selected"':'').">Azerbaijan</option>";
	$country_list .= "<option value='BS'".($sel=='BS'?' selected="selected"':'').">Bahamas</option>";
	$country_list .= "<option value='BH'".($sel=='BH'?' selected="selected"':'').">Bahrain</option>";
	$country_list .= "<option value='BD'".($sel=='BD'?' selected="selected"':'').">Bangladesh</option>";
	$country_list .= "<option value='BB'".($sel=='BB'?' selected="selected"':'').">Barbados</option>";
	$country_list .= "<option value='BY'".($sel=='BY'?' selected="selected"':'').">Belarus</option>";
	$country_list .= "<option value='BE'".($sel=='BE'?' selected="selected"':'').">Belgium</option>";
	$country_list .= "<option value='BZ'".($sel=='BZ'?' selected="selected"':'').">Belize</option>";
	$country_list .= "<option value='BJ'".($sel=='BJ'?' selected="selected"':'').">Benin</option>";
	$country_list .= "<option value='BM'".($sel=='BM'?' selected="selected"':'').">Bermuda</option>";
	$country_list .= "<option value='BT'".($sel=='BT'?' selected="selected"':'').">Bhutan</option>";
	$country_list .= "<option value='BO'".($sel=='BO'?' selected="selected"':'').">Bolivia</option>";
	$country_list .= "<option value='BA'".($sel=='BA'?' selected="selected"':'').">Bosnia and Herzegovina</option>";
	$country_list .= "<option value='BW'".($sel=='BW'?' selected="selected"':'').">Botswana</option>";
	$country_list .= "<option value='BV'".($sel=='BV'?' selected="selected"':'').">Bouvet Island</option>";
	$country_list .= "<option value='BR'".($sel=='BR'?' selected="selected"':'').">Brazil</option>";
	$country_list .= "<option value='IO'".($sel=='IO'?' selected="selected"':'').">British Indian Ocean Territory</option>";
	$country_list .= "<option value='BN'".($sel=='BN'?' selected="selected"':'').">Brunei Darussalam</option>";
	$country_list .= "<option value='BG'".($sel=='BG'?' selected="selected"':'').">Bulgaria</option>";
	$country_list .= "<option value='BF'".($sel=='BF'?' selected="selected"':'').">Burkina Faso</option>";
	$country_list .= "<option value='BI'".($sel=='BI'?' selected="selected"':'').">Burundi</option>";
	$country_list .= "<option value='KH'".($sel=='KH'?' selected="selected"':'').">Cambodia</option>";
	$country_list .= "<option value='CM'".($sel=='CM'?' selected="selected"':'').">Cameroon</option>";
	$country_list .= "<option value='CV'".($sel=='CV'?' selected="selected"':'').">Cape Verde</option>";
	$country_list .= "<option value='KY'".($sel=='KY'?' selected="selected"':'').">Cayman Islands</option>";
	$country_list .= "<option value='CF'".($sel=='CF'?' selected="selected"':'').">Central African Republic</option>";
	$country_list .= "<option value='TD'".($sel=='TD'?' selected="selected"':'').">Chad</option>";
	$country_list .= "<option value='CL'".($sel=='CL'?' selected="selected"':'').">Chile</option>";
	$country_list .= "<option value='CN'".($sel=='CN'?' selected="selected"':'').">China</option>";
	$country_list .= "<option value='CX'".($sel=='CX'?' selected="selected"':'').">Christmas Island</option>";
	$country_list .= "<option value='CC'".($sel=='CC'?' selected="selected"':'').">Cocos (Keeling) Islands</option>";
	$country_list .= "<option value='CO'".($sel=='CO'?' selected="selected"':'').">Colombia</option>";
	$country_list .= "<option value='KM'".($sel=='KM'?' selected="selected"':'').">Comoros</option>";
	$country_list .= "<option value='CG'".($sel=='CG'?' selected="selected"':'').">Congo</option>";
	$country_list .= "<option value='CK'".($sel=='CK'?' selected="selected"':'').">Cook Islands</option>";
	$country_list .= "<option value='CR'".($sel=='CR'?' selected="selected"':'').">Costa Rica</option>";
	$country_list .= "<option value='CI'".($sel=='CI'?' selected="selected"':'').">C&ocirc;te d&#8217;Ivoire</option>";
	$country_list .= "<option value='HR'".($sel=='HR'?' selected="selected"':'').">Croatia</option>";
	$country_list .= "<option value='CU'".($sel=='CU'?' selected="selected"':'').">Cuba</option>";
	$country_list .= "<option value='CZ'".($sel=='CZ'?' selected="selected"':'').">Czech Republic</option>";
	$country_list .= "<option value='DK'".($sel=='DK'?' selected="selected"':'').">Denmark</option>";
	$country_list .= "<option value='DJ'".($sel=='DJ'?' selected="selected"':'').">Djibouti</option>";
	$country_list .= "<option value='DM'".($sel=='DM'?' selected="selected"':'').">Dominica</option>";
	$country_list .= "<option value='DO'".($sel=='DO'?' selected="selected"':'').">Dominican Republic</option>";
	$country_list .= "<option value='EC'".($sel=='EC'?' selected="selected"':'').">Ecuador</option>";
	$country_list .= "<option value='EG'".($sel=='EG'?' selected="selected"':'').">Egypt</option>";
	$country_list .= "<option value='SV'".($sel=='SV'?' selected="selected"':'').">El Salvador</option>";
	$country_list .= "<option value='GQ'".($sel=='GQ'?' selected="selected"':'').">Equatorial Guinea</option>";
	$country_list .= "<option value='ER'".($sel=='ER'?' selected="selected"':'').">Eritrea</option>";
	$country_list .= "<option value='EE'".($sel=='EE'?' selected="selected"':'').">Estonia</option>";
	$country_list .= "<option value='ET'".($sel=='ET'?' selected="selected"':'').">Ethiopia</option>";
	$country_list .= "<option value='FK'".($sel=='FK'?' selected="selected"':'').">Falkland Islands</option>";
	$country_list .= "<option value='FO'".($sel=='FO'?' selected="selected"':'').">Faroe Islands</option>";
	$country_list .= "<option value='FJ'".($sel=='FJ'?' selected="selected"':'').">Fiji</option>";
	$country_list .= "<option value='FI'".($sel=='FI'?' selected="selected"':'').">Finland</option>";
	$country_list .= "<option value='FR'".($sel=='FR'?' selected="selected"':'').">France</option>";
	$country_list .= "<option value='GF'".($sel=='GF'?' selected="selected"':'').">French Guiana</option>";
	$country_list .= "<option value='PF'".($sel=='PF'?' selected="selected"':'').">French Polynesia</option>";
	$country_list .= "<option value='TF'".($sel=='TF'?' selected="selected"':'').">French Southern Territories</option>";
	$country_list .= "<option value='GA'".($sel=='GA'?' selected="selected"':'').">Gabon</option>";
	$country_list .= "<option value='GM'".($sel=='GM'?' selected="selected"':'').">Gambia</option>";
	$country_list .= "<option value='GE'".($sel=='GE'?' selected="selected"':'').">Georgia</option>";
	$country_list .= "<option value='DE'".($sel=='DE'?' selected="selected"':'').">Germany</option>";
	$country_list .= "<option value='GH'".($sel=='GH'?' selected="selected"':'').">Ghana</option>";
	$country_list .= "<option value='GI'".($sel=='GI'?' selected="selected"':'').">Gibraltar</option>";
	$country_list .= "<option value='GR'".($sel=='GR'?' selected="selected"':'').">Greece</option>";
	$country_list .= "<option value='GL'".($sel=='GL'?' selected="selected"':'').">Greenland</option>";
	$country_list .= "<option value='GD'".($sel=='GD'?' selected="selected"':'').">Grenada</option>";
	$country_list .= "<option value='GP'".($sel=='GP'?' selected="selected"':'').">Guadeloupe</option>";
	$country_list .= "<option value='GU'".($sel=='GU'?' selected="selected"':'').">Guam</option>";
	$country_list .= "<option value='GT'".($sel=='GT'?' selected="selected"':'').">Guatemala</option>";
	$country_list .= "<option value='GN'".($sel=='GN'?' selected="selected"':'').">Guinea</option>";
	$country_list .= "<option value='GW'".($sel=='GW'?' selected="selected"':'').">Guinea-Bissau</option>";
	$country_list .= "<option value='GY'".($sel=='GY'?' selected="selected"':'').">Guyana</option>";
	$country_list .= "<option value='HT'".($sel=='HT'?' selected="selected"':'').">Haiti</option>";
	$country_list .= "<option value='HM'".($sel=='HM'?' selected="selected"':'').">Heard Island and McDonald Islands</option>";
	$country_list .= "<option value='VA'".($sel=='VA'?' selected="selected"':'').">Holy See (Vatican City State)</option>";
	$country_list .= "<option value='HN'".($sel=='HN'?' selected="selected"':'').">Honduras</option>";
	$country_list .= "<option value='HU'".($sel=='HU'?' selected="selected"':'').">Hungary</option>";
	$country_list .= "<option value='IS'".($sel=='IS'?' selected="selected"':'').">Iceland</option>";
	$country_list .= "<option value='IN'".($sel=='IN'?' selected="selected"':'').">India</option>";
	$country_list .= "<option value='ID'".($sel=='ID'?' selected="selected"':'').">Indonesia</option>";
	$country_list .= "<option value='IR'".($sel=='IR'?' selected="selected"':'').">Iran</option>";
	$country_list .= "<option value='IQ'".($sel=='IQ'?' selected="selected"':'').">Iraq</option>";
	$country_list .= "<option value='IE'".($sel=='IE'?' selected="selected"':'').">Ireland</option>";
	$country_list .= "<option value='IL'".($sel=='IL'?' selected="selected"':'').">Israel</option>";
	$country_list .= "<option value='IT'".($sel=='IT'?' selected="selected"':'').">Italy</option>";
	$country_list .= "<option value='JM'".($sel=='JM'?' selected="selected"':'').">Jamaica</option>";
	$country_list .= "<option value='JP'".($sel=='JP'?' selected="selected"':'').">Japan</option>";
	$country_list .= "<option value='JO'".($sel=='JO'?' selected="selected"':'').">Jordan</option>";
	$country_list .= "<option value='KZ'".($sel=='KZ'?' selected="selected"':'').">Kazakstan</option>";
	$country_list .= "<option value='KE'".($sel=='KE'?' selected="selected"':'').">Kenya</option>";
	$country_list .= "<option value='KI'".($sel=='KI'?' selected="selected"':'').">Kiribati</option>";
	$country_list .= "<option value='KW'".($sel=='KW'?' selected="selected"':'').">Kuwait</option>";
	$country_list .= "<option value='KG'".($sel=='KG'?' selected="selected"':'').">Kyrgyzstan</option>";
	$country_list .= "<option value='LA'".($sel=='LA'?' selected="selected"':'').">Lao</option>";
	$country_list .= "<option value='LV'".($sel=='LV'?' selected="selected"':'').">Latvia</option>";
	$country_list .= "<option value='LB'".($sel=='LB'?' selected="selected"':'').">Lebanon</option>";
	$country_list .= "<option value='LS'".($sel=='LS'?' selected="selected"':'').">Lesotho</option>";
	$country_list .= "<option value='LY'".($sel=='LY'?' selected="selected"':'').">Libya</option>";
	$country_list .= "<option value='LI'".($sel=='LI'?' selected="selected"':'').">Liechtenstein</option>";
	$country_list .= "<option value='LT'".($sel=='LT'?' selected="selected"':'').">Lithuania</option>";
	$country_list .= "<option value='LU'".($sel=='LU'?' selected="selected"':'').">Luxembourg</option>";
	$country_list .= "<option value='MO'".($sel=='MO'?' selected="selected"':'').">Macau</option>";
	$country_list .= "<option value='MK'".($sel=='MK'?' selected="selected"':'').">Macedonia (FYR)</option>";
	$country_list .= "<option value='MG'".($sel=='MG'?' selected="selected"':'').">Madagascar</option>";
	$country_list .= "<option value='MW'".($sel=='MW'?' selected="selected"':'').">Malawi</option>";
	$country_list .= "<option value='MY'".($sel=='MY'?' selected="selected"':'').">Malaysia</option>";
	$country_list .= "<option value='MV'".($sel=='MV'?' selected="selected"':'').">Maldives</option>";
	$country_list .= "<option value='ML'".($sel=='ML'?' selected="selected"':'').">Mali</option>";
	$country_list .= "<option value='MT'".($sel=='MT'?' selected="selected"':'').">Malta</option>";
	$country_list .= "<option value='MH'".($sel=='MH'?' selected="selected"':'').">Marshall Islands</option>";
	$country_list .= "<option value='MQ'".($sel=='MQ'?' selected="selected"':'').">Martinique</option>";
	$country_list .= "<option value='MR'".($sel=='MR'?' selected="selected"':'').">Mauritania</option>";
	$country_list .= "<option value='MU'".($sel=='MU'?' selected="selected"':'').">Mauritius</option>";
	$country_list .= "<option value='YT'".($sel=='YT'?' selected="selected"':'').">Mayotte</option>";
	$country_list .= "<option value='MX'".($sel=='MX'?' selected="selected"':'').">Mexico</option>";
	$country_list .= "<option value='FM'".($sel=='FM'?' selected="selected"':'').">Micronesia</option>";
	$country_list .= "<option value='MD'".($sel=='MD'?' selected="selected"':'').">Moldova</option>";
	$country_list .= "<option value='MC'".($sel=='MC'?' selected="selected"':'').">Monaco</option>";
	$country_list .= "<option value='MN'".($sel=='MN'?' selected="selected"':'').">Mongolia</option>";
	$country_list .= "<option value='MS'".($sel=='MS'?' selected="selected"':'').">Montserrat</option>";
	$country_list .= "<option value='MA'".($sel=='MA'?' selected="selected"':'').">Morocco</option>";
	$country_list .= "<option value='MZ'".($sel=='MZ'?' selected="selected"':'').">Mozambique</option>";
	$country_list .= "<option value='MM'".($sel=='MM'?' selected="selected"':'').">Myanmar</option>";
	$country_list .= "<option value='NA'".($sel=='NA'?' selected="selected"':'').">Namibia</option>";
	$country_list .= "<option value='NR'".($sel=='NR'?' selected="selected"':'').">Nauru</option>";
	$country_list .= "<option value='NP'".($sel=='NP'?' selected="selected"':'').">Nepal</option>";
	$country_list .= "<option value='NL'".($sel=='NL'?' selected="selected"':'').">Netherlands</option>";
	$country_list .= "<option value='AN'".($sel=='AN'?' selected="selected"':'').">Netherlands Antilles</option>";
	$country_list .= "<option value='NT'".($sel=='NT'?' selected="selected"':'').">Neutral Zone</option>";
	$country_list .= "<option value='NC'".($sel=='NC'?' selected="selected"':'').">New Caledonia</option>";
	$country_list .= "<option value='NI'".($sel=='NI'?' selected="selected"':'').">Nicaragua</option>";
	$country_list .= "<option value='NE'".($sel=='NE'?' selected="selected"':'').">Niger</option>";
	$country_list .= "<option value='NG'".($sel=='NG'?' selected="selected"':'').">Nigeria</option>";
	$country_list .= "<option value='NU'".($sel=='NU'?' selected="selected"':'').">Niue</option>";
	$country_list .= "<option value='NF'".($sel=='NF'?' selected="selected"':'').">Norfolk Island</option>";
	$country_list .= "<option value='KP'".($sel=='KP'?' selected="selected"':'').">North Korea</option>";
	$country_list .= "<option value='MP'".($sel=='MP'?' selected="selected"':'').">Northern Mariana Islands</option>";
	$country_list .= "<option value='NO'".($sel=='NO'?' selected="selected"':'').">Norway</option>";
	$country_list .= "<option value='OM'".($sel=='OM'?' selected="selected"':'').">Oman</option>";
	$country_list .= "<option value='PK'".($sel=='PK'?' selected="selected"':'').">Pakistan</option>";
	$country_list .= "<option value='PW'".($sel=='PW'?' selected="selected"':'').">Palau</option>";
	$country_list .= "<option value='PA'".($sel=='PA'?' selected="selected"':'').">Panama</option>";
	$country_list .= "<option value='PG'".($sel=='PG'?' selected="selected"':'').">Papua New Guinea</option>";
	$country_list .= "<option value='PY'".($sel=='PY'?' selected="selected"':'').">Paraguay</option>";
	$country_list .= "<option value='PE'".($sel=='PE'?' selected="selected"':'').">Peru</option>";
	$country_list .= "<option value='PH'".($sel=='PH'?' selected="selected"':'').">Philippines</option>";
	$country_list .= "<option value='PN'".($sel=='PN'?' selected="selected"':'').">Pitcairn</option>";
	$country_list .= "<option value='PL'".($sel=='PL'?' selected="selected"':'').">Poland</option>";
	$country_list .= "<option value='PT'".($sel=='PT'?' selected="selected"':'').">Portugal</option>";
	$country_list .= "<option value='PR'".($sel=='PR'?' selected="selected"':'').">Puerto Rico</option>";
	$country_list .= "<option value='RE'".($sel=='RE'?' selected="selected"':'').">Reunion</option>";
	$country_list .= "<option value='RO'".($sel=='RO'?' selected="selected"':'').">Romania</option>";
	$country_list .= "<option value='RU'".($sel=='RU'?' selected="selected"':'').">Russian Federation</option>";
	$country_list .= "<option value='RW'".($sel=='RW'?' selected="selected"':'').">Rwanda</option>";
	$country_list .= "<option value='SH'".($sel=='SH'?' selected="selected"':'').">Saint Helena</option>";
	$country_list .= "<option value='KN'".($sel=='KN'?' selected="selected"':'').">Saint Kitts and Nevis</option>";
	$country_list .= "<option value='LC'".($sel=='LC'?' selected="selected"':'').">Saint Lucia</option>";
	$country_list .= "<option value='PM'".($sel=='PM'?' selected="selected"':'').">Saint Pierre and Miquelon</option>";
	$country_list .= "<option value='VC'".($sel=='VC'?' selected="selected"':'').">Saint Vincent and the Grenadines</option>";
	$country_list .= "<option value='WS'".($sel=='WS'?' selected="selected"':'').">Samoa</option>";
	$country_list .= "<option value='SM'".($sel=='SM'?' selected="selected"':'').">San Marino</option>";
	$country_list .= "<option value='ST'".($sel=='ST'?' selected="selected"':'').">Sao Tome and Principe</option>";
	$country_list .= "<option value='SA'".($sel=='SA'?' selected="selected"':'').">Saudi Arabia</option>";
	$country_list .= "<option value='SN'".($sel=='SN'?' selected="selected"':'').">Senegal</option>";
	$country_list .= "<option value='SC'".($sel=='SC'?' selected="selected"':'').">Seychelles</option>";
	$country_list .= "<option value='SL'".($sel=='SL'?' selected="selected"':'').">Sierra Leone</option>";
	$country_list .= "<option value='SG'".($sel=='SG'?' selected="selected"':'').">Singapore</option>";
	$country_list .= "<option value='SK'".($sel=='SK'?' selected="selected"':'').">Slovakia</option>";
	$country_list .= "<option value='SI'".($sel=='SI'?' selected="selected"':'').">Slovenia</option>";
	$country_list .= "<option value='SB'".($sel=='SB'?' selected="selected"':'').">Solomon Islands</option>";
	$country_list .= "<option value='SO'".($sel=='SO'?' selected="selected"':'').">Somalia</option>";
	$country_list .= "<option value='ZA'".($sel=='ZA'?' selected="selected"':'').">South Africa</option>";
	$country_list .= "<option value='GS'".($sel=='GS'?' selected="selected"':'').">South Georgia</option>";
	$country_list .= "<option value='KR'".($sel=='KR'?' selected="selected"':'').">South Korea</option>";
	$country_list .= "<option value='ES'".($sel=='ES'?' selected="selected"':'').">Spain</option>";
	$country_list .= "<option value='LK'".($sel=='LK'?' selected="selected"':'').">Sri Lanka</option>";
	$country_list .= "<option value='SD'".($sel=='SD'?' selected="selected"':'').">Sudan</option>";
	$country_list .= "<option value='SR'".($sel=='SR'?' selected="selected"':'').">Suriname</option>";
	$country_list .= "<option value='SJ'".($sel=='SJ'?' selected="selected"':'').">Svalbard and Jan Mayen Islands</option>";
	$country_list .= "<option value='SZ'".($sel=='SZ'?' selected="selected"':'').">Swaziland</option>";
	$country_list .= "<option value='SE'".($sel=='SE'?' selected="selected"':'').">Sweden</option>";
	$country_list .= "<option value='CH'".($sel=='CH'?' selected="selected"':'').">Switzerland</option>";
	$country_list .= "<option value='SY'".($sel=='SY'?' selected="selected"':'').">Syria</option>";
	$country_list .= "<option value='TW'".($sel=='TW'?' selected="selected"':'').">Taiwan</option>";
	$country_list .= "<option value='TJ'".($sel=='TJ'?' selected="selected"':'').">Tajikistan</option>";
	$country_list .= "<option value='TZ'".($sel=='TZ'?' selected="selected"':'').">Tanzania</option>";
	$country_list .= "<option value='TH'".($sel=='TH'?' selected="selected"':'').">Thailand</option>";
	$country_list .= "<option value='TL'".($sel=='TL'?' selected="selected"':'').">Timor-Leste</option>";
	$country_list .= "<option value='TG'".($sel=='TG'?' selected="selected"':'').">Togo</option>";
	$country_list .= "<option value='TK'".($sel=='TK'?' selected="selected"':'').">Tokelau</option>";
	$country_list .= "<option value='TO'".($sel=='TO'?' selected="selected"':'').">Tonga</option>";
	$country_list .= "<option value='TT'".($sel=='TT'?' selected="selected"':'').">Trinidad and Tobago</option>";
	$country_list .= "<option value='TN'".($sel=='TN'?' selected="selected"':'').">Tunisia</option>";
	$country_list .= "<option value='TR'".($sel=='TR'?' selected="selected"':'').">Turkey</option>";
	$country_list .= "<option value='TM'".($sel=='TM'?' selected="selected"':'').">Turkmenistan</option>";
	$country_list .= "<option value='TC'".($sel=='TC'?' selected="selected"':'').">Turks and Caicos Islands</option>";
	$country_list .= "<option value='TV'".($sel=='TV'?' selected="selected"':'').">Tuvalu</option>";
	$country_list .= "<option value='UG'".($sel=='UG'?' selected="selected"':'').">Uganda</option>";
	$country_list .= "<option value='UA'".($sel=='UA'?' selected="selected"':'').">Ukraine</option>";
	$country_list .= "<option value='AE'".($sel=='AE'?' selected="selected"':'').">United Arab Emirates</option>";
	$country_list .= "<option value='UY'".($sel=='UY'?' selected="selected"':'').">Uruguay</option>";
	$country_list .= "<option value='UZ'".($sel=='UZ'?' selected="selected"':'').">Uzbekistan</option>";
	$country_list .= "<option value='VU'".($sel=='VU'?' selected="selected"':'').">Vanuatu</option>";
	$country_list .= "<option value='VE'".($sel=='VE'?' selected="selected"':'').">Venezuela</option>";
	$country_list .= "<option value='VN'".($sel=='VN'?' selected="selected"':'').">Viet Nam</option>";
	$country_list .= "<option value='VG'".($sel=='VG'?' selected="selected"':'').">Virgin Islands (British)</option>";
	$country_list .= "<option value='VI'".($sel=='VI'?' selected="selected"':'').">Virgin Islands (U.S.)</option>";
	$country_list .= "<option value='WF'".($sel=='WF'?' selected="selected"':'').">Wallis and Futuna Islands</option>";
	$country_list .= "<option value='EH'".($sel=='EH'?' selected="selected"':'').">Western Sahara</option>";
	$country_list .= "<option value='YE'".($sel=='YE'?' selected="selected"':'').">Yemen</option>";
	$country_list .= "<option value='YU'".($sel=='YU'?' selected="selected"':'').">Yugoslavia</option>";
	$country_list .= "<option value='ZR'".($sel=='ZR'?' selected="selected"':'').">Zaire</option>";
	$country_list .= "<option value='ZM'".($sel=='ZM'?' selected="selected"':'').">Zambia</option>";
	$country_list .= "<option value='ZW'".($sel=='ZW'?' selected="selected"':'').">Zimbabwe</option>";
	if ( $return ) {
		return $country_list;
	}
	echo $country_list;
}

/* form arrays */

$credit_card_types = array(
	"AMEX" => "American Express",
	//"DINERS" => "Diners Club",
	"DS" => "Discover",
	"MasterCard" => "MasterCard",
	"Visa" => "Visa"
	);

$access_control_names = array(
	'web',
	'tablet'
	);

function user_has_access($access_control_name) {
	global $user_ID, $access_control_names;
	if ( !in_array($access_control_name, $access_control_names) ) {
		return false;
	}
    $expire_date = 'expire_date_' . $access_control_name;
    $auto_renew = 'auto_renew_' . $access_control_name;

	if ( is_user_logged_in() ) {
		if ( !$user_ID ) {
			wp_get_current_user();
		}
		$user = get_userdata($user_ID);

		if ( intval($user->$expire_date) >= intval(date('Ymd')) ) {
			return true;
		} else {
			if ( $user->$auto_renew ) {
				return true;
			}
		}
	}
	return false;
}

function user_has_autorenew($access_control_name) {
	global $user_ID, $access_control_names;
	if ( !in_array($access_control_name, $access_control_names) ) {
		return false;
	}
    $auto_renew = 'auto_renew_' . $access_control_name;

	if ( is_user_logged_in() ) {
		if ( !$user_ID ) {
			wp_get_current_user();
		}
		$user = get_userdata($user_ID);

		if ( $user->$auto_renew ) {
			return true;
		}
	}
	return false;
}

function country_list_selectone($sel, $return = false) {
	$country_list = '';
	$country_list .= ($sel=='CA'?' Canada':'');
	// $country_list .= ($sel=='US'?' United States of America':''); // dont show USA, assumed country
	// $country_list .= ($sel=='UM'?' United States Minor Outlying Islands':'');
	$country_list .= ($sel=='GB'?' United Kingdom':'');
	$country_list .= ($sel=='AU'?' Australia':'');
	$country_list .= ($sel=='NZ'?' New Zealand':'');
	$country_list .= ($sel=='AF'?' Afghanistan':'');
	$country_list .= ($sel=='AL'?' Albania':'');
	$country_list .= ($sel=='AS'?' American Samoa':'');
	$country_list .= ($sel=='AD'?' Andorra':'');
	$country_list .= ($sel=='AO'?' Angola':'');
	$country_list .= ($sel=='AI'?' Anguilla':'');
	$country_list .= ($sel=='AQ'?' Antarctica':'');
	$country_list .= ($sel=='AG'?' Antigua and Barbuda':'');
	$country_list .= ($sel=='AR'?' Argentina':'');
	$country_list .= ($sel=='AM'?' Armenia':'');
	$country_list .= ($sel=='AW'?' Aruba':'');
	$country_list .= ($sel=='AT'?' Austria':'');
	$country_list .= ($sel=='AZ'?' Azerbaijan':'');
	$country_list .= ($sel=='BS'?' Bahamas':'');
	$country_list .= ($sel=='BH'?' Bahrain':'');
	$country_list .= ($sel=='BD'?' Bangladesh':'');
	$country_list .= ($sel=='BB'?' Barbados':'');
	$country_list .= ($sel=='BY'?' Belarus':'');
	$country_list .= ($sel=='BE'?' Belgium':'');
	$country_list .= ($sel=='BZ'?' Belize':'');
	$country_list .= ($sel=='BJ'?' Benin':'');
	$country_list .= ($sel=='BM'?' Bermuda':'');
	$country_list .= ($sel=='BT'?' Bhutan':'');
	$country_list .= ($sel=='BO'?' Bolivia':'');
	$country_list .= ($sel=='BA'?' Bosnia and Herzegovina':'');
	$country_list .= ($sel=='BW'?' Botswana':'');
	$country_list .= ($sel=='BV'?' Bouvet Island':'');
	$country_list .= ($sel=='BR'?' Brazil':'');
	$country_list .= ($sel=='IO'?' British Indian Ocean Territory':'');
	$country_list .= ($sel=='BN'?' Brunei Darussalam':'');
	$country_list .= ($sel=='BG'?' Bulgaria':'');
	$country_list .= ($sel=='BF'?' Burkina Faso':'');
	$country_list .= ($sel=='BI'?' Burundi':'');
	$country_list .= ($sel=='KH'?' Cambodia':'');
	$country_list .= ($sel=='CM'?' Cameroon':'');
	$country_list .= ($sel=='CV'?' Cape Verde':'');
	$country_list .= ($sel=='KY'?' Cayman Islands':'');
	$country_list .= ($sel=='CF'?' Central African Republic':'');
	$country_list .= ($sel=='TD'?' Chad':'');
	$country_list .= ($sel=='CL'?' Chile':'');
	$country_list .= ($sel=='CN'?' China':'');
	$country_list .= ($sel=='CX'?' Christmas Island':'');
	$country_list .= ($sel=='CC'?' Cocos (Keeling) Islands':'');
	$country_list .= ($sel=='CO'?' Colombia':'');
	$country_list .= ($sel=='KM'?' Comoros':'');
	$country_list .= ($sel=='CG'?' Congo':'');
	$country_list .= ($sel=='CK'?' Cook Islands':'');
	$country_list .= ($sel=='CR'?' Costa Rica':'');
	$country_list .= ($sel=='CI'?' C&ocirc;te d&#8217;Ivoire':'');
	$country_list .= ($sel=='HR'?' Croatia':'');
	$country_list .= ($sel=='CU'?' Cuba':'');
	$country_list .= ($sel=='CZ'?' Czech Republic':'');
	$country_list .= ($sel=='DK'?' Denmark':'');
	$country_list .= ($sel=='DJ'?' Djibouti':'');
	$country_list .= ($sel=='DM'?' Dominica':'');
	$country_list .= ($sel=='DO'?' Dominican Republic':'');
	$country_list .= ($sel=='EC'?' Ecuador':'');
	$country_list .= ($sel=='EG'?' Egypt':'');
	$country_list .= ($sel=='SV'?' El Salvador':'');
	$country_list .= ($sel=='GQ'?' Equatorial Guinea':'');
	$country_list .= ($sel=='ER'?' Eritrea':'');
	$country_list .= ($sel=='EE'?' Estonia':'');
	$country_list .= ($sel=='ET'?' Ethiopia':'');
	$country_list .= ($sel=='FK'?' Falkland Islands':'');
	$country_list .= ($sel=='FO'?' Faroe Islands':'');
	$country_list .= ($sel=='FJ'?' Fiji':'');
	$country_list .= ($sel=='FI'?' Finland':'');
	$country_list .= ($sel=='FR'?' France':'');
	$country_list .= ($sel=='GF'?' French Guiana':'');
	$country_list .= ($sel=='PF'?' French Polynesia':'');
	$country_list .= ($sel=='TF'?' French Southern Territories':'');
	$country_list .= ($sel=='GA'?' Gabon':'');
	$country_list .= ($sel=='GM'?' Gambia':'');
	$country_list .= ($sel=='GE'?' Georgia':'');
	$country_list .= ($sel=='DE'?' Germany':'');
	$country_list .= ($sel=='GH'?' Ghana':'');
	$country_list .= ($sel=='GI'?' Gibraltar':'');
	$country_list .= ($sel=='GR'?' Greece':'');
	$country_list .= ($sel=='GL'?' Greenland':'');
	$country_list .= ($sel=='GD'?' Grenada':'');
	$country_list .= ($sel=='GP'?' Guadeloupe':'');
	$country_list .= ($sel=='GU'?' Guam':'');
	$country_list .= ($sel=='GT'?' Guatemala':'');
	$country_list .= ($sel=='GN'?' Guinea':'');
	$country_list .= ($sel=='GW'?' Guinea-Bissau':'');
	$country_list .= ($sel=='GY'?' Guyana':'');
	$country_list .= ($sel=='HT'?' Haiti':'');
	$country_list .= ($sel=='HM'?' Heard Island and McDonald Islands':'');
	$country_list .= ($sel=='VA'?' Holy See (Vatican City State)':'');
	$country_list .= ($sel=='HN'?' Honduras':'');
	$country_list .= ($sel=='HU'?' Hungary':'');
	$country_list .= ($sel=='IS'?' Iceland':'');
	$country_list .= ($sel=='IN'?' India':'');
	$country_list .= ($sel=='ID'?' Indonesia':'');
	$country_list .= ($sel=='IR'?' Iran':'');
	$country_list .= ($sel=='IQ'?' Iraq':'');
	$country_list .= ($sel=='IE'?' Ireland':'');
	$country_list .= ($sel=='IL'?' Israel':'');
	$country_list .= ($sel=='IT'?' Italy':'');
	$country_list .= ($sel=='JM'?' Jamaica':'');
	$country_list .= ($sel=='JP'?' Japan':'');
	$country_list .= ($sel=='JO'?' Jordan':'');
	$country_list .= ($sel=='KZ'?' Kazakstan':'');
	$country_list .= ($sel=='KE'?' Kenya':'');
	$country_list .= ($sel=='KI'?' Kiribati':'');
	$country_list .= ($sel=='KW'?' Kuwait':'');
	$country_list .= ($sel=='KG'?' Kyrgyzstan':'');
	$country_list .= ($sel=='LA'?' Lao':'');
	$country_list .= ($sel=='LV'?' Latvia':'');
	$country_list .= ($sel=='LB'?' Lebanon':'');
	$country_list .= ($sel=='LS'?' Lesotho':'');
	$country_list .= ($sel=='LY'?' Libya':'');
	$country_list .= ($sel=='LI'?' Liechtenstein':'');
	$country_list .= ($sel=='LT'?' Lithuania':'');
	$country_list .= ($sel=='LU'?' Luxembourg':'');
	$country_list .= ($sel=='MO'?' Macau':'');
	$country_list .= ($sel=='MK'?' Macedonia (FYR)':'');
	$country_list .= ($sel=='MG'?' Madagascar':'');
	$country_list .= ($sel=='MW'?' Malawi':'');
	$country_list .= ($sel=='MY'?' Malaysia':'');
	$country_list .= ($sel=='MV'?' Maldives':'');
	$country_list .= ($sel=='ML'?' Mali':'');
	$country_list .= ($sel=='MT'?' Malta':'');
	$country_list .= ($sel=='MH'?' Marshall Islands':'');
	$country_list .= ($sel=='MQ'?' Martinique':'');
	$country_list .= ($sel=='MR'?' Mauritania':'');
	$country_list .= ($sel=='MU'?' Mauritius':'');
	$country_list .= ($sel=='YT'?' Mayotte':'');
	$country_list .= ($sel=='MX'?' Mexico':'');
	$country_list .= ($sel=='FM'?' Micronesia':'');
	$country_list .= ($sel=='MD'?' Moldova':'');
	$country_list .= ($sel=='MC'?' Monaco':'');
	$country_list .= ($sel=='MN'?' Mongolia':'');
	$country_list .= ($sel=='MS'?' Montserrat':'');
	$country_list .= ($sel=='MA'?' Morocco':'');
	$country_list .= ($sel=='MZ'?' Mozambique':'');
	$country_list .= ($sel=='MM'?' Myanmar':'');
	$country_list .= ($sel=='NA'?' Namibia':'');
	$country_list .= ($sel=='NR'?' Nauru':'');
	$country_list .= ($sel=='NP'?' Nepal':'');
	$country_list .= ($sel=='NL'?' Netherlands':'');
	$country_list .= ($sel=='AN'?' Netherlands Antilles':'');
	$country_list .= ($sel=='NT'?' Neutral Zone':'');
	$country_list .= ($sel=='NC'?' New Caledonia':'');
	$country_list .= ($sel=='NI'?' Nicaragua':'');
	$country_list .= ($sel=='NE'?' Niger':'');
	$country_list .= ($sel=='NG'?' Nigeria':'');
	$country_list .= ($sel=='NU'?' Niue':'');
	$country_list .= ($sel=='NF'?' Norfolk Island':'');
	$country_list .= ($sel=='KP'?' North Korea':'');
	$country_list .= ($sel=='MP'?' Northern Mariana Islands':'');
	$country_list .= ($sel=='NO'?' Norway':'');
	$country_list .= ($sel=='OM'?' Oman':'');
	$country_list .= ($sel=='PK'?' Pakistan':'');
	$country_list .= ($sel=='PW'?' Palau':'');
	$country_list .= ($sel=='PA'?' Panama':'');
	$country_list .= ($sel=='PG'?' Papua New Guinea':'');
	$country_list .= ($sel=='PY'?' Paraguay':'');
	$country_list .= ($sel=='PE'?' Peru':'');
	$country_list .= ($sel=='PH'?' Philippines':'');
	$country_list .= ($sel=='PN'?' Pitcairn':'');
	$country_list .= ($sel=='PL'?' Poland':'');
	$country_list .= ($sel=='PT'?' Portugal':'');
	$country_list .= ($sel=='PR'?' Puerto Rico':'');
	$country_list .= ($sel=='RE'?' Reunion':'');
	$country_list .= ($sel=='RO'?' Romania':'');
	$country_list .= ($sel=='RU'?' Russian Federation':'');
	$country_list .= ($sel=='RW'?' Rwanda':'');
	$country_list .= ($sel=='SH'?' Saint Helena':'');
	$country_list .= ($sel=='KN'?' Saint Kitts and Nevis':'');
	$country_list .= ($sel=='LC'?' Saint Lucia':'');
	$country_list .= ($sel=='PM'?' Saint Pierre and Miquelon':'');
	$country_list .= ($sel=='VC'?' Saint Vincent and the Grenadines':'');
	$country_list .= ($sel=='WS'?' Samoa':'');
	$country_list .= ($sel=='SM'?' San Marino':'');
	$country_list .= ($sel=='ST'?' Sao Tome and Principe':'');
	$country_list .= ($sel=='SA'?' Saudi Arabia':'');
	$country_list .= ($sel=='SN'?' Senegal':'');
	$country_list .= ($sel=='SC'?' Seychelles':'');
	$country_list .= ($sel=='SL'?' Sierra Leone':'');
	$country_list .= ($sel=='SG'?' Singapore':'');
	$country_list .= ($sel=='SK'?' Slovakia':'');
	$country_list .= ($sel=='SI'?' Slovenia':'');
	$country_list .= ($sel=='SB'?' Solomon Islands':'');
	$country_list .= ($sel=='SO'?' Somalia':'');
	$country_list .= ($sel=='ZA'?' South Africa':'');
	$country_list .= ($sel=='GS'?' South Georgia':'');
	$country_list .= ($sel=='KR'?' South Korea':'');
	$country_list .= ($sel=='ES'?' Spain':'');
	$country_list .= ($sel=='LK'?' Sri Lanka':'');
	$country_list .= ($sel=='SD'?' Sudan':'');
	$country_list .= ($sel=='SR'?' Suriname':'');
	$country_list .= ($sel=='SJ'?' Svalbard and Jan Mayen Islands':'');
	$country_list .= ($sel=='SZ'?' Swaziland':'');
	$country_list .= ($sel=='SE'?' Sweden':'');
	$country_list .= ($sel=='CH'?' Switzerland':'');
	$country_list .= ($sel=='SY'?' Syria':'');
	$country_list .= ($sel=='TW'?' Taiwan':'');
	$country_list .= ($sel=='TJ'?' Tajikistan':'');
	$country_list .= ($sel=='TZ'?' Tanzania':'');
	$country_list .= ($sel=='TH'?' Thailand':'');
	$country_list .= ($sel=='TL'?' Timor-Leste':'');
	$country_list .= ($sel=='TG'?' Togo':'');
	$country_list .= ($sel=='TK'?' Tokelau':'');
	$country_list .= ($sel=='TO'?' Tonga':'');
	$country_list .= ($sel=='TT'?' Trinidad and Tobago':'');
	$country_list .= ($sel=='TN'?' Tunisia':'');
	$country_list .= ($sel=='TR'?' Turkey':'');
	$country_list .= ($sel=='TM'?' Turkmenistan':'');
	$country_list .= ($sel=='TC'?' Turks and Caicos Islands':'');
	$country_list .= ($sel=='TV'?' Tuvalu':'');
	$country_list .= ($sel=='UG'?' Uganda':'');
	$country_list .= ($sel=='UA'?' Ukraine':'');
	$country_list .= ($sel=='AE'?' United Arab Emirates':'');
	$country_list .= ($sel=='UY'?' Uruguay':'');
	$country_list .= ($sel=='UZ'?' Uzbekistan':'');
	$country_list .= ($sel=='VU'?' Vanuatu':'');
	$country_list .= ($sel=='VE'?' Venezuela':'');
	$country_list .= ($sel=='VN'?' Viet Nam':'');
	$country_list .= ($sel=='VG'?' Virgin Islands (British)':'');
	$country_list .= ($sel=='VI'?' Virgin Islands (U.S.)':'');
	$country_list .= ($sel=='WF'?' Wallis and Futuna Islands':'');
	$country_list .= ($sel=='EH'?' Western Sahara':'');
	$country_list .= ($sel=='YE'?' Yemen':'');
	$country_list .= ($sel=='YU'?' Yugoslavia':'');
	$country_list .= ($sel=='ZR'?' Zaire':'');
	$country_list .= ($sel=='ZM'?' Zambia':'');
	$country_list .= ($sel=='ZW'?' Zimbabwe':'');
	if ( $return ) {
		return $country_list;
	}
	echo $country_list;
}

/*------------------------------------------------------------------
 * Filter to correct display of category list in Appearance > Menus
--------------------------------------------------------------------*/

add_filter( 'get_terms_args', 'checklist_args', 10, 2 );
  function checklist_args( $args, $taxonomies )
  {
    $menu_taxonomies = array('product_cat', 'page', 'category','post');
    if(in_array($taxonomies[0], $menu_taxonomies))
    {
      $args['number'] = 1000;
    }
    return $args;
  }

	/*------------------------------------------------------------------
	 * Valdiate Gravater images
	--------------------------------------------------------------------*/
	function validate_gravatar($id_or_email) {
	  //id or email code borrowed from wp-includes/pluggable.php
	    $email = '';
	    if ( is_numeric($id_or_email) ) {
	        $id = (int) $id_or_email;
	        $user = get_userdata($id);
	        if ( $user )
	            $email = $user->user_email;
	    } elseif ( is_object($id_or_email) ) {
	        // No avatar for pingbacks or trackbacks
	        $allowed_comment_types = apply_filters( 'get_avatar_comment_types', array( 'comment' ) );
	        if ( ! empty( $id_or_email->comment_type ) && ! in_array( $id_or_email->comment_type, (array) $allowed_comment_types ) )
	            return false;

	        if ( !empty($id_or_email->user_id) ) {
	            $id = (int) $id_or_email->user_id;
	            $user = get_userdata($id);
	            if ( $user)
	                $email = $user->user_email;
	        } elseif ( !empty($id_or_email->comment_author_email) ) {
	            $email = $id_or_email->comment_author_email;
	        }
	    } else {
	        $email = $id_or_email;
	    }

	    $hashkey = md5(strtolower(trim($email)));
	    $uri = 'http://www.gravatar.com/avatar/' . $hashkey . '?d=404';

	    $data = wp_cache_get($hashkey);
	    if (false === $data) {
	        $response = wp_remote_head($uri);
	        if( is_wp_error($response) ) {
	            $data = 'not200';
	        } else {
	            $data = $response['response']['code'];
	        }
	        wp_cache_set($hashkey, $data, $group = '', $expire = 60*5);

	    }
	    if ($data == '200'){
	        return true;
	    } else {
	        return false;
	    }
	}
