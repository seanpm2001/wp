<li class="article-item homepage-section-item">
  <div class="article-data">
    <h3 class="hed">
      <a href="<?php print esc_url( get_permalink() ); ?>">
        <?php print get_post_field( 'alt', get_the_ID() )['alt_title']
                  ? get_post_field( 'alt', get_the_ID() )['alt_title']
                  : get_the_title(); ?>
      </a>
    </h3>
    <p class="byline">
      <?php print mj_byline( get_the_ID() ); ?>
    </p>
  </div>
</li>

