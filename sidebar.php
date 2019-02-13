<div class="small-12 large-4 medium-4 cell rr">

	<aside id="sidebar">
		<?php
		if ( is_user_logged_in() && is_active_sidebar( 'sidebar-known' ) ) {
			dynamic_sidebar( 'sidebar-known' );
		} elseif ( ! is_user_logged_in() && is_active_sidebar( 'sidebar-unknown' ) ) {
			dynamic_sidebar( 'sidebar-unknown' );
		}
		?>
	</aside>

</div>
