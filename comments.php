<div class="grid-x comments_section">
    <div class="large-12 cell">
		<?php function srbweb_comments($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment; ?>

		<?php //Added to grab first name of commenter and last initial - reference: https://wordpress.org/support/topic/user-first-name
		$commenter_id = $comment->user_id;
		$commenter_fname =  get_usermeta($commenter_id,'first_name');
		$commenter_lname =  substr(get_usermeta($commenter_id,'last_name'), 0, 1);
		$commenter_name = ucwords(strtolower($commenter_fname . " " . $commenter_lname . "."));
		?>


        <li <?php comment_class(); ?>>
            <article id="comment-<?php comment_ID(); ?>">
                <header class="comment-author">
					<?php echo get_avatar($comment,$size='48'); ?>
                    <div class="author-meta">
                        <!--<?php printf(__('<cite class="fn">%s</cite>', 'srbweb-foundation'), get_comment_author()) //commented out because defaulting to username only ?>-->


						<?php
						if ($commenter_name == ' .' ) {
							$pieces = explode(" ", get_comment_author());
							echo $pieces[0];
							$lastName = substr($pieces[1], 0, 1);  // returns "cde"
							if (!empty($lastName)) {
								echo ' '.$lastName.'.';
							} else {
								echo ' ';
							}
						} else {
							echo $commenter_name;

						}
						?>
                        <time datetime="<?php echo comment_date('c') ?>"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s', 'srbweb-foundation'), get_comment_date(),  get_comment_time()) ?></a></time>
						<?php edit_comment_link(__('(Edit)', 'srbweb-foundation'), '', '') ?>
                    </div>
                </header>

				<?php if ($comment->comment_approved == '0') : ?>
                    <div class="notice">
                        <p class="bottom"><?php _e('Your comment is awaiting moderation.', 'srbweb-foundation') ?></p>
                    </div>
				<?php endif; ?>

                <section class="comment">
					<?php comment_text() ?>
					<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
                </section>

            </article>
			<?php } ?>

			<?php
			// Do not delete these lines
			if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
				die (__('Please do not load this page directly. Thanks!', 'srbweb-foundation'));

			if ( post_password_required() ) { ?>
                <section id="comments">
                    <div class="notice">
                        <p class="bottom"><?php _e('This post is password protected. Enter the password to view comments.', 'srbweb-foundation'); ?></p>
                    </div>
                </section>
				<?php
				return;
			}
			?>

      <div class="comment_title">
          <i class="fi-comment blog-comments">Comments</i>
      </div>

			<?php // You can start editing here. Customize the respond form below ?>
			<?php if ( have_comments() ) : ?>
                <section id="comments">	<!--<h3><?php comments_number(__('No Responses to', 'srbweb-foundation'), __('One Response to', 'srbweb-foundation'), __('% Responses to', 'srbweb-foundation') ); ?> &#8220;<?php the_title(); ?>&#8221;</h3>-->
                    <ul class="commentlist">
						<?php wp_list_comments('type=comment&callback=srbweb_comments'); ?>

                    </ul>
                    <footer>
                        <nav id="comments-nav">
                            <div class="comments-previous"><?php previous_comments_link( __( '&larr; Older comments', 'srbweb-foundation' ) ); ?></div>
                            <div class="comments-next"><?php next_comments_link( __( 'Newer comments &rarr;', 'srbweb-foundation' ) ); ?></div>
                        </nav>
                    </footer>
                </section>

			<?php else : // this is displayed if there are no comments so far ?>
				<?php if ( comments_open() ) : ?>
				<?php else : // comments are closed ?>
                    <section id="comments">
                        <div class="notice">
                            <p class="bottom"><?php _e('Comments are closed.', 'srbweb-foundation') ?></p>
                        </div>
                    </section>

				<?php endif; ?>
			<?php endif; ?>
			<?php if ( comments_open() ) : ?>
                <section id="respond">
                    <h3><?php comment_form_title( __('Leave a Reply', 'srbweb-foundation'), __('Leave a Reply to %s', 'srbweb-foundation') ); ?></h3>
                    <p class="cancel-comment-reply"><?php cancel_comment_reply_link(); ?></p>
					<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
                        <p><?php printf( __('You must be <a href="%s">logged in</a> to post a comment.', 'srbweb-foundation'), wp_login_url( get_permalink() ) ); ?></p>
					<?php else : ?>
                        <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
							<?php if ( is_user_logged_in() ) :
								global $current_user;
								get_currentuserinfo();
								$user_formal = $current_user->user_firstname . ' ' . $current_user->user_lastname;
								?>
                                <p><?php printf(__('Logged in as <a href="%s/wp-admin/profile.php">%s</a>.', 'srbweb-foundation'), get_option('siteurl'), $user_formal); ?> <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php __('Log out of this account', 'srbweb-foundation'); ?>"><?php _e('Log out &raquo;', 'srbweb-foundation'); ?></a></p>
							<?php else : ?>
                                <p>
                                    <label for="author"><?php _e('Name', 'srbweb-foundation'); if ($req) _e(' (required)', 'srbweb-foundation'); ?></label>
                                    <input type="text" class="five" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?>>
                                </p>
                                <p>
                                    <label for="email"><?php _e('Email (will not be published)', 'srbweb-foundation'); if ($req) _e(' (required)', 'srbweb-foundation'); ?></label>
                                    <input type="text" class="five" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?>>
                                </p>
                                <!--<p>
					<label for="url"><?php _e('Website', 'srbweb-foundation'); ?></label>
					<input type="text" class="five" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" size="22" tabindex="3">
				</p>-->
							<?php endif; ?>
                            <p>
                                <label for="comment"><?php _e('Comment', 'srbweb-foundation'); ?></label>
                                <textarea name="comment" id="comment" tabindex="4"></textarea>
                            </p>
                            <!--<p id="allowed_tags" class="small"><strong>XHTML:</strong> <?php _e('You can use these tags:','srbweb-foundation'); ?> <code><?php echo allowed_tags(); ?></code></p>-->
                            <p><input name="submit" class="medium alert button radius full" type="submit" id="submit" tabindex="5" value="<?php esc_attr_e('Submit Comment', 'srbweb-foundation'); ?>"></p>
							<?php comment_id_fields(); ?>
							<?php do_action('comment_form', $post->ID); ?>
            </form>
					<?php endif; // If registration required and not logged in ?>
        </section>

			<?php endif; // if you delete this the sky will fall on your head ?>
    </div>
</div>
