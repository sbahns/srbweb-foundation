<?php /* Define the custom box */
// Tutorial: http://wp.tutsplus.com/tutorials/plugins/how-to-create-custom-wordpress-writemeta-boxes/
// Getting the values:
//if( get_post_meta( $post->ID, ‘my_meta_box_text’, true ) )
//{
//echo get_post_meta( $post->ID, ‘my_meta_box_text, true ) );
//}

function cd_meta_box_add() {
	add_meta_box( 'commentary-box-id', 'Common Custom Fields', 'cd_meta_box_cb', array('post'), 'advanced', 'high' );
}
add_action( 'add_meta_boxes', 'cd_meta_box_add' );


add_action('edit_form_after_title', function() {
    global $post, $wp_meta_boxes;
    do_meta_boxes(get_current_screen(), 'advanced', $post);
    unset($wp_meta_boxes[get_post_type($post)]['advanced']);
});

function cd_meta_box_cb() {
	// $post is already set, and contains an object: the WordPress post
	global $post;
	$values = get_post_custom( $post->ID );
	$subheadline = isset( $values['subheadline'] ) ? $values['subheadline'][0] : '';
	//$hide_featured = isset( $values['hide_featured'] ) ? $values['hide_featured'][0] : '';
	//$selected = isset( $values['my_meta_box_select'] ) ? esc_attr( $values['my_meta_box_select'][0] ) : '';
	//$check = isset( $values['is_commentary_checkbox'] ) ? esc_attr( $values['is_commentary_checkbox'][0] ) : '';

	// We'll use this nonce field later on when saving.
	wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
	?>
    <p>   <?php // echo ''.htmlentities(str_replace('\"', "\'",str_replace("\'","\\\'",$subheadline)));?>
		<label for="my_meta_box_text">Sub-Headline</label>
		<input type="text" name="subheadline" id="subheadline" value="<?php  echo ''.htmlentities(str_replace('\"', "\'",str_replace("\'","\\\'",$subheadline))); ?>" style="width:80%;"/>
</p>

	<?php
}

function cd_meta_box_save( $post_id ) {
	// Bail if we're doing an auto save
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	// if our nonce isn't there, or we can't verify it, bail
	if ( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;

	// if our current user can't edit this post, bail
	if ( !current_user_can( 'edit_posts' ) ) return;

	// now we can actually save the data
	$allowed = array(
		'a' => array( // on allow a tags
			'href' => array() // and those anchors can only have href attribute
		),
		'i' => array(),
		'em'  => array(),
		'b'  => array(),
		'strong'  => array()
	);

	// Make sure your data is set before trying to save it
	if ( isset( $_POST['subheadline'] ) ) {
		update_post_meta( $post_id, 'subheadline', wp_kses( $_POST['subheadline'], $allowed ) );
	}
	//if ( isset( $_POST['hide_featured'] ) ) {
	//	update_post_meta( $post_id, 'hide_featured', wp_kses( $_POST['hide_featured'], $allowed ) );
	//}



	// This is purely my personal preference for saving check-boxes
	// modify this statement because I have turned off the other sample input fields
	// $chk = isset( $_POST['is_commentary_checkbox'] ) && $_POST['my_meta_box_select'] ? 'on' : 'off';
	//--$chk = isset( $_POST['is_commentary_checkbox'] )  ? 'on' : 'off';
	//--update_post_meta( $post_id, 'is_commentary_checkbox', $chk );

}
add_action( 'save_post', 'cd_meta_box_save' );
