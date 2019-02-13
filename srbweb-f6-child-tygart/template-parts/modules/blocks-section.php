<?php if( get_field('blocks_section') ) { ?>
  <div class="grid-x white-background blocks">
    <div class="small-12 cell">
        <?php if( have_rows('blocks_section') ){
           while ( have_rows('blocks_section') ) {
             the_row();
             $text_column = get_sub_field( 'text_column' );
             $text_column_heading = get_sub_field( 'text_column_heading' );
             $text_column_position = get_sub_field( 'text_column_position' );
             $image_column = get_sub_field( 'image_column' );
             $custom_css_class = get_sub_field( 'custom_css_class' );
             $read_more_link = get_sub_field( 'read_more_link' );
             $read_more_link_text = get_sub_field( 'read_more_link_text' );
             $read_more_link_target = get_sub_field( 'read_more_link_target' );
             ?>
        <div class="grid-x<?php echo ' '.$custom_css_class;?>">
          <div class="small-12 medium-6 large-6 cell">
            <?php if ($text_column_position == 'Left') {
              echo '<div class="text-column left">';
              echo '<a name="'.$text_column_heading.'"></a>';
              echo '<h2>'.$text_column_heading.'</h2>';
              echo $text_column;
              if ($read_more_link) {
                echo '<div class="text-right"><a href="'.$read_more_link.'" target="'.$read_more_link_target.'">'.$read_more_link_text.'</a></div>';
              }
              echo '</div>';
            } else {
              echo '<div class="image-column left">';
              echo '<img src="'.$image_column.'" />';
              echo '</div>';
            } ?>
          </div>
          <div class="small-12 medium-6 large-6 cell">
            <?php if ($text_column_position == 'Right') {
              echo '<div class="text-column right">';
              echo '<a name="'.$text_column_heading.'"></a>';
              echo '<h2>'.$text_column_heading.'</h2>';
              echo $text_column;
              if ($read_more_link) {
                echo '<div class="text-right"><a href="'.$read_more_link.'" target="'.$read_more_link_target.'">'.$read_more_link_text.'</a></div>';
              }
              echo '</div>';
            } else {
              echo '<div class="image-column right">';
              echo '<img src="'.$image_column.'" />';
              echo '</div>';
            } ?>
          </div>
        </div>
      <?php }
      }?>
    </div>
  </div>
  <?php
  } ?>
