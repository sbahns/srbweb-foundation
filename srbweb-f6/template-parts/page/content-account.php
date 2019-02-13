<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }

global $user_id;
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
?><section class="profile account">

	<div class="row container">

		<div class="MyActTitle">
			<div class="row display">
				<div class="large-12 cell" >
					<h1 class="pageheadline"><?php the_title() ?></h1>
				</div>
			</div>
		</div>

		<!-- start area 1 -->
		<div class="MyActPanelTitle">
			<div class="row display">
				<div class="large-12 cell"><h2>My Contact Information</h2></div>
			</div>
		</div>

		<div class="callout panel panelTightFirst">
			<div class="row display">
				<div class="large-8 medium-6 small-12 cell addleftmargin">
					<?php
					$name = isset( $name_prefix  ) ? trim($name_prefix . ' ' . $first_name) : $first_name;
					if ( ! empty($middle_name) ) {
						$name .= ' ' . $middle_name;
					}
					if ( ! empty($last_name) ) {
						$name .= ' ' . $last_name;
					}
					if ( ! empty($name_suffix) ) {
						$name .= ', ' . $name_suffix;
					}
					if ( ! empty($name) ) {
						?>
						<p style="margin-bottom:0;"><?php echo $name; ?></p>
						<?php
					}
					if ( ! empty($address) ) {
						?>
						<p style="margin-bottom:0;"><?php echo $address; ?></p>
						<?php
					}
					if ( ! empty($address2) ) {
						?>
						<p style="margin-bottom:0;"><?php echo $address2; ?></p>
						<?php
					}
					$city_state_zip = ! empty($city) ? $city : '';
					if ( ! empty($state) && ! empty($city_state_zip) ) {
						$city_state_zip .= ', ' . $state;
					} elseif ( empty($city_state_zip) && ! empty($state) ) {
						$city_state_zip .= $state;
					}
					if ( ! empty($zip_code) && ! empty($city_state_zip) ) {
						$city_state_zip .= ' ' . $zip_code;
					}
					if ( ! empty($city_state_zip) ) {
						?>
						<p style="margin-bottom:0;"><?php echo $city_state_zip; ?></p>
						<?php
					}
					if ( ! empty($country) && 'US' != $country ) {
						?>
						<p style="margin-bottom:0;"><?php echo country_list_selectone($country); ?></p>

						<?php
					}
					if ( ! empty($user_email) ) {
						?>
						<p style="margin-bottom:0;"><?php echo $user_email; ?></p>
						<?php
					}
					if ( ! empty($phone) ) {
						?>
						<p style="margin-bottom:0;"><?php echo $phone; ?></p>
						<?php
					}
					?>
				</div>
				<div class="large-4 medium-6 small-12 cell MyAcctRightPad myaccountlink">
					<ul class="bullet_list addleftmargin" style="margin-left: 0;">
						<li><a href="/account/edit-account-details/?e=cpd">Change personal information</a></li>
						<li><a href="/account/edit-account-details/?e=cem">Update e-mail address</a></li>
						<li><a href="/account/edit-account-details/?e=cpwd">Change password</a></li>
						<!-- li><a href="/account/order-history/">Order History</a></li -->
						<!-- li><a href="/store/account/?downloads">Downloads</a></li -->
						<!-- li><a href="/account/edit-account-details/?e=cnl">Newsletter preferences</a></li -->
					</ul>
				</div>
			</div> <!-- end div.row display -->
		</div> <!-- end div.panel panelTightFirst -->

		<!-- end area 1 -->

		<!-- start area 1.5 -->

		<div class="MyActPanelTitle">
			<div class="row display">
				<div class="large-12 cell"><h2>Subscriptions</h2>
                <p>Existing Subscribers: <a href="#" title="Update Magazine Subscription Details" target="_blank">Update subscription details</a></p>
                </div>
			</div>
		</div>

				<div class="callout panel panelTight subscriptions" >
					<div class="row display">
						<div class="large-12 cell magazine-subscriptions">

							<?php

							$soon = strtotime('+90days');
							$subscribed = array();

							$channel_text = array(
								'web'		=> ' Website Access',
								'print'		=> ' Print Subscription',
								'tablet'	=> ' Tablet App'
							);

							$entitlements = array();
							if ( function_exists('load_entitlements') )
							{
								$entitlements = load_entitlements();
							}

							$rows = false;

							if ( is_array( $entitlements ) ) {
								foreach ( $entitlements as $pub_id => $expire ) {
									if ( $pub_id == 'SP' ) {
										break;
									}
									$inner_table = $button = $reminder = '';
									$title       = get_magazine_title( $pub_id );
									$slug        = get_magazine_slug( $pub_id );

									$button = "<a href='/subscription-offers/" . $slug . "/'><input type='button' class='tiny button radius' style='float: right;' value='Renew'></a>";

									foreach ( $expire as $channel => $e ) {
										if ( $e > time() ) {
											$subscribed[] = $pub_id;

											$inner_table .= "<div style='position: relative; width: 100%; overflow: auto; padding: 3px 15px; box-sizing: border-box;'>";
											$inner_table .= "<div style='float: left;'>" . $channel_text[ $channel ] . "</div>";
											$inner_table .= "<div style='float: right;'>Expires " . date( 'F Y', $e ) . "</div>";
											$inner_table .= "</div>";

											$reminder = ( $e < $soon ) ? "<div class='expire' style='text-align: center;'>Your subscription to " . $title . " expires in the next 90 days. Don't miss an issue. Renew today!</div>" : false;
										}
									}

									if ( $inner_table ) {
										//$report = (substr($m,0,1) == 'l') ? 'UHN Health Letter' : 'UHN Health Report';
										$rows .= "<div class='large-3 medium-3 small-12 cell' style='float: right; width: 30%; padding: 0 15px 10px;'>" . $button . "</div>";
										$rows .= "<div class='large-3 medium-3 small-12 cell' style='float: left; width: 30%; padding: 0 15px 10px;'>" . $title . "</div>";
										$rows .= $inner_table;
									}
								}
							}

							if ($rows) { echo "<div class='row colors'>".$rows.$reminder.'</div>'; }

							// simplified, since the only magazine is Yankee
							// if they are not a subscriber, show the upsell

							if (!subscribed()) {

								//$coverurl = wp_get_attachment_url(get_latest_cover('YM'));
                                $coverurl = '/wp-content/uploads/FC_Jan17CM-150x194.jpg';

								$output = "<div class='panelize'>";
								$output .= "<div class='row'>";
								$output .= "<div class='large-3 small-3 cell'>";
								$output .= "<a href='/magazine-subscriptions/change-slug/'><img src=".$coverurl." alt='Subscribe to Our Magazine' title='Subscribe to Our Magazine'></a>";
								$output .= "</div>";
								$output .= "<div class='large-9 small-9 cell'>";
								$output .= "<h5>";
								$output .= ($first_name) ? $first_name.", would" : "Would";
								$output .= " you enjoy a subscription to our magazine?";
								$output .= "</h5>";
								$output .= "<p>Why not subscribe today? Click the button below.</p>";
								$output .= "<p><a class='button medium radius' href='/magazine-subscriptions/change-slug/'>Click To Subscribe</a></p>";
								$output .= "</div>";
								$output .= "</div>";
								$output .= "</div>";

								echo $output;

							}

							?>

						</div> <!-- /left column -->
					</div> <!-- /div.row display -->
				</div> <!-- /panel -->

		<!-- end area 1.5 -->

		<!-- start area 2 -->

		<div class="MyActPanelTitle">
			<div class="row display">
				<div class="large-12 cell"><h2>Newsletter Preferences</h2></div>
			</div>
		</div>
		<div class="callout panel panelTight" >
				<form id="your-profile" class="nice" action="<?php echo esc_url( self_admin_url( 'profile.php' ) ); ?>" method="post"<?php do_action('user_edit_form_tag'); ?>>

						<div class="row display rowTight">
							<div class="large-8 cell">
								<?php if ( isset( $_GET['updated'] ) ) { ?>
									<div class="success radius"><p><b>Your <em><?php bloginfo('name'); ?></em> e-mail preferences have been updated.</b></p></div>
								<?php } else { ?>
									<p>Please check the email newsletters you wish to receive.<br/>Unchecking a box will unsubscribe you from future emails.</p>
								<?php } ?>
							</div>
							<div class="large-4 cell MyAcctRightPadButton UpdateProfile">
								<input type="submit" class="medium button radius right" name="Update E-mail Preferences" value="Update E-mail Preferences" />
							</div>
						</div>

						<div class="row display">
							<div class="large-12 cell"> <!-- start left column -->

								<?php wp_nonce_field('update-user_' . $user_id); ?>

								<?php
									$current_user = wp_get_current_user();
									if (!defined('IS_PROFILE_PAGE')) { define('IS_PROFILE_PAGE',($user_id == $current_user->ID )); }
									if (!function_exists('get_user_to_edit')) { require_once( ABSPATH . 'wp-admin/includes/user.php' ); }
								?>

								<input type="hidden" name="from" value="profile" />
								<input type="hidden" name="action" value="update" />

								<input type="hidden" name="user_id" id="user_id" value="<?php echo absint($user_id); ?>" />
								<input type="hidden" name="checkuser_id" value="<?php echo $user_id ?>" />
								<input type="hidden" name="nickname" value="<?php echo esc_attr($current_user->nickname) ?>" />

								<input type="hidden" name="user_email" value="<?php echo $user_email ?>" />
								<input type="hidden" name="phone" value="<?php echo $phone ?>" />
								<input type="hidden" name="first_name" value="<?php echo $first_name ?>" />
								<input type="hidden" name="last_name" value="<?php echo $last_name ?>" />
								<input type="hidden" name="address" value="<?php echo $address ?>" />
								<input type="hidden" name="address2" value="<?php echo $address2 ?>" />
								<input type="hidden" name="city" value="<?php echo $city ?>" />
								<input type="hidden" name="state" value="<?php echo $state ?>" />
								<input type="hidden" name="zip_code" value="<?php echo $zip_code ?>" />
								<input type="hidden" name="country" value="<?php echo $country ?>" />

								<div class="row">
									<div class="large-12 cell myaccount-newsletters">
										<fieldset class="panelx radiusx">
											<?php
											if ( class_exists( 'mqWhatCountsFramework' ) ) {
												$mqWhatCountsFramework = mqWhatCountsFramework::getInstance();
												$mqWhatCountsFramework->list_checkboxes( );
											}
											?>
										</fieldset>
									</div>
								</div> <!-- /div.row -->
							</div> <!-- /left column -->
						</div> <!-- /div.row display -->
				</form>
			</div> <!-- /second box, update email preferences -->

		<!-- end area 2 -->

	</div><!-- /container -->

</section><!-- /profile -->

<div class="clear"></div>
