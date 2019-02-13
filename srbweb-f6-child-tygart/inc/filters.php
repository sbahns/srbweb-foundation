<?php
/*--------------------------------------------------
// Filter the comment author name to use First Name
* if not set, then use display_name
---------------------------------------------------*/
function my_comment_author( $author, $comment_ID, $comment ){

	// get the user of the comment
	$comment_user = get_user_by( 'ID', $comment->user_id );

	// get the first name for the user
	$first_name = $comment_user->first_name;

	// if no first name set, then use display name
	if( '' == $first_name ){
		$first_name = $comment_user->display_name;
	}

	return $first_name;
}
/* @todo - I don't think this filter is needed
 *
add_filter( 'get_comment_author', 'my_comment_author', 10, 3 );
 */

/*--------------------------------------
// Filter for Embedded video content
---------------------------------------*/
function my_embed_oembed_html($html, $url, $attr, $post_id) {
  return '<div class="video-container">' . $html . '</div>';
}
add_filter('embed_oembed_html', 'my_embed_oembed_html', 99, 4);


/* Add Featured Image to Content Filter */
add_filter( 'the_content', 'featured_image_before_content' );
function featured_image_before_content( $content ) {
	 if ( is_singular('post') && has_post_thumbnail()) {
		 	 $thumbnail = get_the_post_thumbnail();
			 $outputCredit = false;
		 	 $outputCaption = false;
			 $imageCaption = get_post(get_post_thumbnail_id())->post_excerpt;
			 if ( ! empty( $imageCaption ) ) {
			 	$outputCaption = '<p class="caption">' .$imageCaption. '</p>';
		 	 }
			 $photo_credit = get_field('photo_credit', get_post(get_post_thumbnail_id()));
			 if ( ! empty( $photo_credit ) ) {
		 		$outputCredit = '<p class="caption credit" style="text-align:left">(Photo: ' . $photo_credit . ')</p>';
		 	 }

			 $content = '<div class="single-thumbnail">'. $thumbnail .$outputCaption. $outputCredit. '</div>'. $content;

	 }

	 return $content;
}


/* Add Photo Credit to images within content - shortcode filter.
 * Also keeps captions the width of caption to the width of image */
add_filter( 'img_caption_shortcode', 'my_img_caption_shortcode', 10, 3 );
function my_img_caption_shortcode( $empty, $attr, $content ){
	$attr = shortcode_atts( array(
		'id'      => '',
		'align'   => 'alignnone',
		'width'   => '',
		'caption' => ''
	), $attr );

	// not sure what this does, so commenting out
	if ( 1 > (int) $attr['width'] ) {
		return '';
	}

	// Get the photo ID to get photo credit from custom field
	$photoID = preg_replace("/[^0-9]/","",$attr['id']);
	// $photo_credit = get_field('photo_credit', $photoID );
	$photo_credit = apply_filters('image_photo_credit', get_post_meta($photoID, 'photo_credit', true));
	$outputCredit = false;
	$outputCaption = false;
	if ( ! empty( $attr['caption']) ) {
		$outputCaption = '<p class="caption" style="text-align:left">' . $attr['caption'] . '</p>';
	}
	if ( ! empty( $photo_credit ) ) {
		$outputCredit = '<p class="caption credit" style="text-align:left">(Photo:' . $photo_credit . ')</p>';
	}
	if ( $attr['id'] ) {
		$attr['id'] = 'id="' . esc_attr( $attr['id'] ) . '" ';
	}

	return '<div ' . $attr['id']
	. 'class="wp-caption ' . esc_attr( $attr['align'] ) . '"'
	. 'style="max-width: ' . ( 10 + (int) $attr['width'] ) . 'px;">'
	. do_shortcode( $content )
	. $outputCaption
	. $outputCredit
	. '</div>';
}
