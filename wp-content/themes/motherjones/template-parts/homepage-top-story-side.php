<li class="article-item group">
  <div class="article-data">
    <h3 class="hed">
      <a href="<?php print esc_url( get_permalink() ); ?>">
        <?php
          if ( $hed = get_post_meta( get_the_ID(), 'mj_promo_hed', true ) ) {
            echo $hed;
          } else {
            the_title();
          }
        ?>
      </a>
    </h3>
    <p class="byline">
      <?php print mj_byline( get_the_ID() ); ?>
    </p>
  </div>
</li>
