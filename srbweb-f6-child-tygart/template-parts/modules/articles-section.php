<?php
$add_article_section = get_field( 'add_article_section' );
$article_section_heading = get_field( 'article_section_heading' );
//$select_article_category = get_field( 'select_article_category' );
$article_section_read_more_link = get_field( 'article_section_read_more_link' );

if( $add_article_section ) { ?>
  <div class="grid-x grid-padding-x grid-padding-y featured-stories light-gray-background add-50">
    <div class="grid-container">
      <div class="small-12 cell">
        <h2><?php echo $article_section_heading; ?></h2>
        <div class="grid-x grid-padding-x grid-padding-y small-up-1 medium-up-3 large-up-6">
          <?php
          //	get_template_part( 'template-parts/page/content', 'news-module-posts' );
          ?>
          <?php
              $select_article_category = get_field( 'select_article_category' );

              if ($select_article_category) {
                if (!is_array($select_article_category)) {
                  $select_article_category = array($select_article_category);
                }

              $args = array(
                  'post_type' => 'post',
                  'posts_per_page'   => 6,
                  'tax_query'        => array(
                			array(
                				'taxonomy' => 'featured_cat',
                				'terms'    => $select_article_category,
                			),
                		),
                  );
              $news_posts = new WP_Query( $args );

            ?>
            <?php if( $news_posts->have_posts() ): ?>
              <?php while( $news_posts->have_posts() ) : $news_posts->the_post(); ?>
                <div class="cell">
                    <article class="post">
                      <?php
                        if ( has_post_thumbnail() ) {
                          $default_attr = array(
                            'class' => 'entry-image',
                            'alt' => the_title_attribute('echo=0'),
                            'title' => the_title_attribute('echo=0'),
                            );?>
                          <a href="<?php the_permalink() ?>" title="Permanent link to <?php the_title_attribute() ?>" rel="bookmark"><?php the_post_thumbnail( 'large-thumbnail', $default_attr ); ?></a>
                          <?php
                          $has_image = 'has_image';
                        }

                        ?>
                        <h3 class="title"><a href="<?php the_permalink() ?>" title="Permanent link to <?php the_title_attribute() ?>" rel="bookmark"><?php the_title() ?></a></h3>
                    </article>
                </div>

              <?php endwhile; ?>

            <?php endif;
          }
             ?>

          <?php wp_reset_postdata();	 // Restore global post data stomped by the_post(). ?>
        </div>
        <?php if( $article_section_read_more_link ) { ?>
        <div class="grid-x">
          <div class="small-12 cell float-right">
            <a href="<?php echo $article_section_read_more_link; ?>" class="float-right">Read More</a>
          </div>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
  <?php
  } ?>
