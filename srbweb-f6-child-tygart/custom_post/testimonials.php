<?php
/**
 * --------------------------------------------------------------
 * Register the Testimonial post type
 * --------------------------------------------------------------
 */

function register_testimonial_post_type() {
	$labels = array(
		'name'					=> __('Testimonials'),
		'singular_name'			=> __('Testimonial'),
		'add_new'				=> __('Add New Testimonial'),
		'add_new_item'			=> __('Add New Testimonial'),
		'edit_item'				=> __('Edit Testimonial'),
		'new_item'				=> __('New Testimonial'),
		'view_item'				=> __('View Testimonial'),
		'search_items'			=> __('Search Testimonials'),
		'not_found'				=> __('No Testimonials Found'),
		'not_found_in_trash'	=> __('No Testimonials Found in Trash')
	);
	$args = array(
		'labels'				=> $labels,
		'description'			=> 'Testimonial post types are used to display formatted testimonials.',
		'exclude_from_search'	=> true,
		'publicly_queryable'	=> false,
		'show_in_nav_menus'		=> false,
		'show_ui'				=> true,
		'show_in_menu'			=> 'edit.php?post_type=uc',
		'hierarchical'			=> false,
		'supports'				=> array('title', 'editor', 'thumbnail'),
		'rewrite'				=> array('slug' => 'testimonial')
	);
	register_post_type('testimonial', $args);
}


add_action('init',  'register_testimonial_post_type');

/**
 * --------------------------------------------------------------
 * Admin columns for Testimonial post type
 * --------------------------------------------------------------
 */

function testimonial_columns($columns){
	$columns = array(
		'cb'			=> '<input type=\'checkbox\' />',
		'title'			=> 'Testimonial Title',
		'author'		=> 'Author',
		'date'			=> 'Date'
	);
	return $columns;
}
add_filter('manage_edit-testimonial_columns', 'testimonial_columns');


// Testimonials custom fields
if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_testimonial-fields',
		'title' => 'Testimonial Fields',
		'fields' => array (
			array (
				'key' => 'field_5abd59d2e5513',
				'label' => 'Client Name, Title, & Company',
				'name' => 'client_name',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5abd5a1fe5515',
				'label' => 'Testimonial',
				'name' => 'testimonial',
				'type' => 'textarea',
				'default_value' => '',
				'placeholder' => '',
				'maxlength' => '',
				'rows' => '',
				'formatting' => 'br',
			),
			array (
				'key' => 'field_5abd5b30d0b90',
				'label' => 'Photo',
				'name' => 'photo',
				'type' => 'image',
				'save_format' => 'url',
				'preview_size' => 'small-user-thumbnail',
				'library' => 'all',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'testimonial',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
				0 => 'permalink',
				1 => 'the_content',
				2 => 'excerpt',
				3 => 'custom_fields',
				4 => 'discussion',
				5 => 'comments',
				6 => 'revisions',
				7 => 'slug',
				8 => 'author',
				9 => 'format',
				10 => 'categories',
				11 => 'tags',
				12 => 'send-trackbacks',
			),
		),
		'menu_order' => 0,
	));
}

//  Testimonials shortcode (uses custom fields)
function testimonial_shortcode() {
	global $post;

	//query subpages
	$args = array(
		//'post_parent' => $post->ID,
		'post_type' => 'testimonial',
		'order' => 'ASC',
		'orderby'=> 'menu_order'
	);
	$testimonials = new WP_query($args);

	// create output
	if ($testimonials->have_posts()) :
		$output = '';
		while ($testimonials->have_posts()) : $testimonials->the_post();

		$testimonial_name = get_field( "client_name", $testimonials->ID );
		$testimonial_text = get_field( "testimonial", $testimonials->ID );
		$testimonial_image_url = get_field( "photo", $testimonials->ID );

			$output .= '
				<div class="testimonials">
					<div class="grid-x">
						<div class="small-3 cell">
							<img src="'.$testimonial_image_url.'" title="'.$testimonial_name.'" alt="'.$testimonial_name.' logo" />
						</div>
						<div class="small-9 cell">
							<div class="testimonial-text">
								<p class="testimonial">"'.$testimonial_text.'"</p>
								<p class="testimonial-client">- '.$testimonial_name.' </p>
							</div>
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

add_shortcode('testimonial_shortcode', 'testimonial_shortcode');
