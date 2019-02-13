<article class="grid-x daily-archive">

	<div class="large-12 cell">
		<?php
		if (strpos( $_SERVER['REQUEST_URI'], '/department/') !== false){
			?>
			<h4>There are no articles in this department at this time.</h4>
			<?php
		} else {
			?>
			<h4>Sorry, nothing else could be found.</h4>
			<?php
		}
		?>
	</div>
</article>
