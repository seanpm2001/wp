<div id="related-articles">
  <h2 class="promo">Related</h2>
    <ul class="related-articles-list">
      <?php 
        $related = get_post_field( 'related_articles', get_the_ID() ); 
        print_r($related);
        print_r($related['relateds']);
        print_r(Array(60, 100));
        $related_query = new WP_Query(array(
          'post__in' => Array(60, 61, 62)
        ) );
        while ( $related_query->have_posts() ) : $related_query->the_post(); 
      ?>
<h1>lookin for an article</h1>

        <?php get_template_part( 'standard-article-li', 'single' ); ?>

      <?php endwhile; ?>
    </ul>
</div>
