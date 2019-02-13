<?php
if ( function_exists( 'get_post_by_name' ) ) {
	?>
	<div class="grid-x">
		<div class="large-3 cell small-centered icons">
			<?php echo do_shortcode('[get_post_by_name page_title="Footer Social Icons" post_type="uc"]'); ?>
		</div>
	</div>
	<?php
}
