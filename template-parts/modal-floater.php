<?php
if ( ! is_user_logged_in() ) {

	// Definied still throws a notice
	if( @BYPASS_OPENX == true){
		echo do_shortcode('[get_post_by_name page_title="Floater - Default"]');
	} else {
		if ( function_exists( 'widget_ad_func' ) ) {
			//echo do_shortcode('[openx_ad zone="828" target="_self" n_value="a4dd25bd"]');
		}
	}

 }
