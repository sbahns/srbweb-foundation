<?php
if ( session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['error_redirect_to']) ) {
	$url_partial = site_url();
	$offer_id = $_SESSION['error_redirect_to'];
	$redirect = $url_partial . $offer_id;
} else {
	$redirect = site_url( $_SERVER['REQUEST_URI'] );
}
?>
<div id="login" class="reveal small quick-login" data-reveal>
	<h5>Enter Your Log In Credentials</h5>
	<div class="register_note"></div>
    <div>
		<?php
		$args = array(
			'id_submit'			=> 'quick_login_submit',
			'redirect'			=> $redirect,
			'label_username'	=> __( 'Email' ),
			'value_remember'	=> true,
			'unique'			=> 'pop',
		);
		wp_login_form_alt($args);
		?>
		<ul class="disclosure login_list">
		    <li><?php _e('Lost your password?'); ?> <a href="<?php echo site_url('/account/change-password/', 'http'); ?>" title="<?php _e('Reset Password') ?>"><?php _e('Create New Password'); ?></a></li>
		    <li><?php _e('No password?'); ?> <a href="<?php echo site_url('/get-download/', 'http'); ?>"><?php _e('Sign up'); ?></a></li>
		</ul>
    </div>
	<button class="close-button" data-close aria-label="Close reveal" type="button">
		<span aria-hidden="true">&times;</span>
	</button>
</div>
