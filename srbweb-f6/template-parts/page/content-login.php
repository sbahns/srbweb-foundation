<?php
if( isset( $_SESSION['error_redirect_to'] ) ){
	$existing_email_error = $_SESSION['error_redirect_to'];
	$split_off_errors = explode('&', $existing_email_error);
	$existing_email_redirect = $split_off_errors[0];
} else {
	$existing_email_redirect = false;
}

?>
<div class="grid-x">
    <div class="large-9 cell">
    <h1 class="pageheadline"><?php the_title() ?></h1>

    <?php
    /**
     * If the user failed the login, get the error messages and display them.
     */
    if ( isset( $_GET['failed'] ) && isset( $_SESSION[ $_GET['failed'] ] ) ) {
        $wp_error = $_SESSION[$_GET['failed']];
        unset( $_SESSION[$_GET['failed']] );
        if ( is_wp_error($wp_error) ) {
          if ( $wp_error->get_error_code() ) {
            $errors = '';
            $messages = '';
            foreach ( $wp_error->get_error_codes() as $code ) {
              $severity = $wp_error->get_error_data($code);
              foreach ( $wp_error->get_error_messages($code) as $error ) {
                if ( 'message' == $severity ) {
                  $messages .= ' ' . $error . "<br />\n";
                } else {
                  $errors .= ' ' . $error . "<br />\n";
                }
              }
            }
            if ( ! empty($errors) ) {
              echo '<p class="alert-box alert radius">' . apply_filters('login_errors', $errors) . "</p>\n";
            }
            if ( ! empty($messages) ) {
              echo '<p class="alert-box radius">' . apply_filters('login_messages', $messages) . "</p>\n";
            }
          }
        }
    } else {
		if (is_user_logged_in() && !isset($_REQUEST['redirect_to'])) {
			echo '<script>window.location = "/account/";</script>';
		}
	}

    the_content();

    if($existing_email_redirect) {
      $redirect_to = $existing_email_redirect;
    } else {
      $redirect_to = isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : site_url( '/', 'http' );
    }

    $login_form_args = array(
        'redirect'			=> $redirect_to,
        'label_username'	=> __( 'Email' ),
        'value_remember'	=> true,
		'unique'			=> 'loop',
    );
    wp_login_form_alt($login_form_args);

	?>
    <ul class="disclosure login_list">
        <li><?php _e('Lost your password?'); ?> <a href="<?php echo site_url('/account/change-password/', 'http'); ?>" title="<?php _e('Reset Password') ?>"><?php _e('Create New Password'); ?></a></li>
        <li><?php _e('No password?'); ?> <a href="<?php echo site_url('/get-download/', 'http'); ?>"><?php _e('Sign Up'); ?></a></li>
    </ul>
    </div>

</div>
