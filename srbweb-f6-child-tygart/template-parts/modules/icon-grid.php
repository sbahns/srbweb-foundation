<?php
$has_icon_grid = get_field( 'has_icon_grid' );
$add_icon_grid = get_field( 'add_icon_grid' );
$icon_grid_read_more_link = get_field( 'icon_grid_read_more_link' );
if( get_field('has_icon_grid') ) {
  $number_of_icon_grid_columns = get_field( 'number_of_icon_grid_columns' );
  ?>
  <div class="grid-x charcoal-background add-50">
      <div class="small-12 cell">
        <div class="grid-container">
            <div class="grid-x grid-padding-x grid-padding-y small-up-1 <?php echo $number_of_icon_grid_columns;?> align-center-top icon-grid text-center" data-equalizer data-equalize-on="medium" id="ed-eq">
              <?php if( have_rows('add_icon_grid') ){
                 while ( have_rows('add_icon_grid') ) {
                   the_row();
                   $icon = get_sub_field( 'icon' );
                   $headline = get_sub_field( 'headline' );
                   $text = get_sub_field( 'text' );
                   $link_or_jump_link = get_sub_field( 'link_or_jump_link' );
                   $jump_link = get_sub_field( 'jump_link' );
                   $link = get_sub_field( 'link' );

                   ?>
                <div class="cell">
                  <?php if( $link_or_jump_link == 'Link') {
                    echo '<a href="'.$link.'">';
                  } elseif($link_or_jump_link == 'Jump Link') {
                    echo '<a href="#'.$jump_link.'">';
                  }

                  echo '<img src="'.$icon.'" />';
                  echo '<h3>'.$headline.'</h3>';
                  echo '<p>'.$text.'</p>';

                  if( $link_or_jump_link == 'Link' || $link_or_jump_link == 'Jump Link') {
                    echo '</a>';
                  } ?>
                </div>

              <?php }
              } ?>
              </div>

          <?php if( $icon_grid_read_more_link ) { ?>
          <div class="grid-x">
            <div class="small-12 cell float-right">
              <a href="<?php echo $icon_grid_read_more_link; ?>" class="float-right">Read More</a>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>
  </div>
<?php } ?>
