<?php
/*--------------------------------------
// Images Sizes for child theme
---------------------------------------*/
function child_theme_setup() {
  add_image_size('block-grid', 600, 415, true);
  add_image_size('network-home-featured-story', 150, 100, true);
  add_image_size('small-user-thumbnail', 54, 54, true);
}
add_action( 'after_setup_theme', 'child_theme_setup', 11 );
