<div class="article-image">
  <a href="<?php print esc_url( get_permalink() ); ?>">
    <?php print wp_get_attachment_image( 
      get_post_meta(get_the_ID(), 'master_image' )[0]['master_image'],
      'large_990'
    ); ?>
  </a>
</div>
<h2 class="homepage-fullwidth-section-label promo">
    <?php print $fullwidth_title; ?>
</h2>
<p class="homepage-art-byline">
  <?php print get_post_meta( get_the_ID(), 'master_image' )[0]['master_image_byline']; ?>
</p>
<div class="article-data">
  <h1 class="hed">
    <a href="<?php print esc_url( get_permalink() ); ?>">
        <?php print get_post_meta(get_the_ID(), 'alt')['alt_title']
                  ? get_post_meta(get_the_ID(), 'alt')['alt_title']
                  : get_the_title(); ?>
    </a>
  </h1>
  <h4 class="dek">
    <a href="<?php print esc_url( get_permalink() ); ?>">
      <?php print get_post_meta( get_the_ID(), 'alt' )['alt_dek']
                ? get_post_meta( get_the_ID(), 'alt' )['alt_dek']
                : get_post_meta( get_the_ID(), 'dek' )[0]; ?>
    </a>
  </h4>
  <p class="byline">
    <?php print mj_byline( get_the_ID() ); ?>
  </p>
</div>
