
<section id="footer" class="blue-background"> <!-- end section#page -->
<?php

get_template_part( 'template-parts/modal/content', 'login' );

?>

	<div class="grid-container grid-container-padded footer-wrap">
		<div class="grid-x grid-padding-x">
			<div class="small-12 cell">
				<footer class="site-footer">
					<?php
					get_template_part( 'template-parts/footer/content' );
					?>
				</footer>
			</div>
		</div>
	</div> <!-- end div.site-wrap.footer-wrap -->

</section> <!-- end section#page -->

<!-- srbweb Lightbox (image popups using Foundation (6) Reveal - js for this is located in js/footer-scripts.js-->
<?php foundation_lightbox(); ?>
<!-- end srbweb Lightbox -->


<?php


wp_footer(); ?>


</body>
</html>
