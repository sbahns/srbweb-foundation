<?php
get_header();
?>

<div class="grid-x grid-padding-x grid-padding-y">
	<div class="small-12 cell" id="maincol">
		<?php
		get_template_part( 'template-parts/page/content', 'news-posts' );
		?>
	</div> <!-- end div.small-12 columns -->
</div>

<?php
get_footer();
