<?php
if ( function_exists( 'get_post_by_name' ) ) {
	?>
	<div id="cvv_modal" class="reveal small quick-login" data-reveal>
		<?php echo do_shortcode('[get_post_by_name page_title="Credit Card Identification Number" post_type="uc"]'); ?>
		<button class="close-button" data-close aria-label="Close reveal" type="button">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	<?php
}
