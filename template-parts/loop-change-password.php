<div class="grid-x">

	<div class="large-10 cell">

		<section>

			<h2 class="headline"><?php the_title() ?></h2>

			<?php
			$http_post = ('POST' == $_SERVER['REQUEST_METHOD']);

			if ( $http_post ) {
				$errors = srbweb_retrieve_password();
				if ( ! is_wp_error($errors) ) {
					$message = "An email has been sent with a link to reset your password. Click on the link in your email and you will be taken to a page where you can define a new password.";
				} else {
					$display_errors = $errors->get_error_messages();
					foreach ( $display_errors as $error ) {
						?>
						<p class="alert-box alert radius"><?php echo($error); ?></p>
						<?php
					}
				}
			}

			$user_email = isset($_POST['user_email']) ? stripslashes($_POST['user_email']) : '';

			if ( $http_post && ! is_wp_error($errors) && isset($message) ) {
				echo "<p>$message</p>\n";
			} else {
				?>
				<form class="panel radius" name="lostpasswordform" id="lostpasswordform" action="" method="post">
					<label for="email"><b>Registered Email</b></label>
					<div class="grid-x display">
						<div class="large-12 cell"><input class="medium text-input" name="user_email" type="email" maxlength="50" id="user_email" value="" /></div>
						<div class="large-12 cell"><input type="submit" class="secondary button radius password-reset" name="Get Link to Reset Password" value="Get Link to Reset Password" /></div>
					</div>
				</form>
				<?php
			}
			?>

		</section>

	</div>

</div>
