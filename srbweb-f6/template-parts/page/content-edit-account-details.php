<?php
/*
 * Make sure to add the following CSS to _custom.scss so various sections are hidden properly
 *
.hidden,
label + input.input-text.hidden, label + textarea.hidden, label + select.hidden, label + div.dropdown.hidden, select + div.dropdown.hidden  {
	display: none;
	height: 0;
	font-size: 0px;
	visibility: hidden;
}
 *
 */

if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }

// if the query string value is not correct, set to show personal details
if ( $_GET["e"] != 'cpd' && $_GET["e"] != 'cem' && $_GET["e"] != 'cpwd' && $_GET["e"] != 'cnl' ) {
	$_GET["e"] = 'cpd';
}

// Set titles
if ($_GET["e"] == 'cpd') {
    $title = "Change Personal Information";
    $message = '<p class="disclosure tight">Fields marked with an asterisk (<span class="required">*</span>) are required.</p>';
} else if ($_GET["e"] == 'cem') {
    $title = "Change E-Mail Address";
    $message = '<p class="disclosure tight">Fields marked with an asterisk (<span class="required">*</span>) are required.</p>';
} else if ($_GET["e"] == 'cpwd') {
    $title = "Change Password";
    $message = '';
} elseif ($_GET["e"] == 'cnl') {
    $title = "Change My E-Mail Newsletter Preferences";
    $message = '';
}

global $user_id;
if ( ! $user_id && is_user_logged_in() ) {
	wp_get_current_user();
	$user_id = $current_user->ID;
}
$u = get_userdata($user_id);
if ( $u ) {
    // must be known user to get to this form
    $first_name = $u->first_name;
    $last_name = $u->last_name;
    $display_name = $u->display_name;
    $user_login = $u->user_login;
    $user_email = $u->user_email;
    $address = $u->address;
    $address2 = $u->address2;
    $city = $u->city;
    $state = $u->state;
    $zip_code = $u->zip_code;
    $country = $u->country;
    $phone = $u->phone;
    $subscriptions = is_array($u->subscriptions) ? $u->subscriptions : array();
    $products = is_array($u->products) ? $u->products : array();
} else {
    // redirected here because of error in processing script
    $first_name = $_SESSION["post_values"]["first_name"];
    $last_name = $_SESSION["post_values"]["last_name"];
    $display_name = $_SESSION["post_values"]["display_name"];
    $user_id = $_SESSION["post_values"]["user_ID"];
    $user_login = $_SESSION["post_values"]["user_login"];
    $user_email = $_SESSION["post_values"]["user_email"];
    $address = $_SESSION["post_values"]["address"];
    $address2 = $_SESSION["post_values"]["address2"];
    $city = $_SESSION["post_values"]["city"];
    $state = $_SESSION["post_values"]["state"];
    $zip_code = $_SESSION["post_values"]["zip_code"];
    $country = $_SESSION["post_values"]["country"];
    $phone = $_SESSION["post_values"]["phone"];
    $subscriptions = is_array($_SESSION["post_values"]["subscriptions"]) ? $_SESSION["post_values"]["subscriptions"] : array();
    $products = is_array($_SESSION["post_values"]["products"]) ? $_SESSION["post_values"]["products"] : array();
}
?>
<section class="profile">

	<div class="grid-x account-links">
		<div class="large-12 cell">
			<div class="grid-x display">
				<div class="large-9 cell" >
					<h2 style="margin-bottom:1em;" class="issue-archive-header"><?php the_title() ?></h2>
				</div>
				<div class="large-3 cell account_button" style="margin-top:10px;text-align:right;">
				<a href="/account/" class="small button">Return to My Account</a> </div>
			</div>
		</div>
	</div>



	<div class="container">
		<?php
		if ( isset( $_SESSION['user_profile_update_errors'] ) && is_wp_error( $_SESSION['user_profile_update_errors'] ) ) {
		    ?>
		    <div class="alert-box alert">
		    	<p><?php echo implode( "</p>\n<p>", $_SESSION['user_profile_update_errors']->get_error_messages() ); ?></p>
		    </div>
		    <?php
		    unset( $_SESSION['user_profile_update_errors'] );
		    $_POST = array_merge( $_POST, $_SESSION['user_profile_update_post'] );
		    unset( $_SESSION['user_profile_update_post'] );
		} elseif ( isset($_SESSION["errors"]) ) { // old style
			if ( is_array($_SESSION["errors"]) ) {
				echo '<div class="alert-box alert">';
				foreach ( $_SESSION["errors"] as $e ) {
					?>
					<p class="msg"><?php echo($e); ?></p>
					<?php
				}
				echo '</div>';
			}
			unset($_SESSION["errors"]);
		} else {
			if ( empty($_GET["updated"]) ) {
				?>
				<p>Use the form below to manage your <em><?php bloginfo('name'); ?></em> profile.</p>
				<?php
			} else {
				?>
				<div class="alert-box success">Your <em><?php bloginfo('name'); ?></em> profile has been updated.</div>
				<?php
			}
		}
		?>
		<p class="disclosure tight required">Fields marked with an asterisk (<span class="required">*</span>) are required.</p>
	</div>

	<div>

		<form id="your-profile" class="nice" action="<?php echo esc_url( self_admin_url( 'profile.php' ) ); ?>" method="post"<?php do_action('user_edit_form_tag'); ?>>
			<!-- $user_id before wp_nonce_field(): <?php echo $user_id; ?> -->
			<?php
			wp_nonce_field('update-user_' . $user_id);
		    $current_user = wp_get_current_user();
		    if ( ! defined( 'IS_PROFILE_PAGE' ) ) {
		    	define( 'IS_PROFILE_PAGE', ( $user_id == $current_user->ID ) );
		    	?>
		    	<!-- $user_id: <?php echo $user_id; ?> -->
		    	<!-- $current_user->ID: <?php echo $current_user->ID;  ?> -->
		    	<?php
			}
		    if ( ! function_exists( 'get_user_to_edit' ) ) {
		    	require_once(ABSPATH . 'wp-admin/includes/user.php');
		    }
			?>
		    <input type="hidden" name="from" value="profile" />
		    <input type="hidden" name="checkuser_id" value="<?php echo $user_id ?>" />
		    <input type="hidden" name="nickname" value="<?php echo esc_attr($current_user->nickname) ?>" />
		    <input type="hidden" name="action" value="update" />
		    <input type="hidden" name="user_id" id="user_id" value="<?php echo absint($user_id); ?>" />

			<input type="hidden" name="e" id="e" value="<?php echo $_GET["e"]; ?>" />

           <div class="panel <?php echo $_GET["e"] == 'cem' ? '' : 'hidden';?>">
				<div class="grid-x display">
					<div class="large-12 cell">
						<label for="email">Email<span class="required">*</span></label>
						<input name="user_email" type="email" maxlength="50" id="user_email" class="full input-text" value="<?php echo($user_email); ?>" />
					</div>
				</div>
			</div>


			<div class="panel <?php echo $_GET["e"] == 'cpd' ? '' : 'hidden';?>">

				<div class="grid-x display">
					<div class="large-6 cell">
						<label for="first_name">First name<span class="required">*</span></label>
						<input name="first_name" type="text" maxlength="20" id="first_name" class="full input-text" value="<?php echo($first_name); ?>" />
					</div>
					<div class="large-6 cell">
						<label for="last_name">Last name<span class="required">*</span></label>
						<input name="last_name" type="text" maxlength="30" id="last_name" class="full input-text" value="<?php echo($last_name); ?>" />
					</div>
				</div>

				<!-- <div class="grid-x display">
					<div class="large-6 cell">
						<label for="phone">Phone</label>
						<input id="phone" type="tel" class="full input-text" name="phone" maxlength="20" value="<?php esc_attr_e($phone); ?>" />
					</div>
				</div>

				<div class="grid-x display">
					<div class="large-12 cell">
						<label for="address">Street Address</label>
						<input type="text" id="address" class="full input-text" name="address" maxlength="30" value="<?php esc_attr_e($address); ?>" />
					</div>
				</div>

				<div class="grid-x display">
					<div class="large-12 cell">
						<label for="address2">(<em>If needed</em> — “Apt.,” “Suite,” etc.)</label>
						<input type="text" id="address2" class="full input-text" name="address2" maxlength="30" value="<?php esc_attr_e($address2); ?>" />
					</div>
				</div>

				<div class="grid-x display">
					<div class="large-6 cell">
						<label for="city">City</label>
						<input type="text" id="city" class="full input-text" name="city" maxlength="20" value="<?php esc_attr_e($city); ?>" />
					</div>
					<div class="large-6 cell">
						<label for="state">State</label>
						<select name="state" id="state" class="full"><?php state_list($state); ?></select>
					</div>
				</div>

				<div class="grid-x display">
					<div class="large-6 cell">
						<label for="country">Country <span class="description">(required)</span></label>
						<select name="country" id="country" ><?php country_list($country); ?></select>
					</div>
					<div class="large-6 cell">
						<label for="zip_code">Zip/Postal Code</label>
						<input type="text" id="zip_code" class="full input-text" name="zip_code" maxlength="20" value="<?php esc_attr_e($zip_code); ?>" />
					</div>
				</div> -->

			</div>

			<div class="panel <?php echo $_GET["e"] == 'cpwd' ? '' : 'hidden';?>">
            <div class="grid-x display">
				<a name="password"></a>
				<div class="large-12 cell">
					<fieldset class="panel">
						<h5>New&nbsp;Password:</h5>
                        <?php //old text: To change your password, type a new one twice below. Otherwise, leave the password fields&nbsp;blank. ?>
						<p>To change your password, type a new one in the two password fields below. Otherwise, leave these password fields blank.</p>
						<input class="medium input-text tight" type="password" name="pass1" id="pass1" size="16" value="" autocomplete="off" />
						<p class="disclosure">Type your new password again.</p>
						<input  class="medium input-text tight" type="password" name="pass2" id="pass2" size="16" value="" autocomplete="off" />
					</fieldset>
				</div>
			</div>
          </div>


      <div class="grid-x <?php echo $_GET["e"] == 'cnl' ? '' : 'hidden';?>">
				<div class="large-12 cell">
					<fieldset class="panel radius">
						<?php
						if ( class_exists( 'mqWhatCountsFramework' ) ) {
							$mqWhatCountsFramework = mqWhatCountsFramework::getInstance();
							$mqWhatCountsFramework->list_checkboxes( );
						}
						?>
					</fieldset>
				</div>
			</div>


			<input type="submit" class="medium button secondary account" name="Update Profile" value="Update Profile" />

		</form>

	</div>

</section>

<div class="clear"></div>
