<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
    <main id="main" class="site-main group" role="main">

    <div class="main-index"> 
		<?php if ( have_posts() ) : ?>

			<header class="page-header">
        <h1 class="page-title promo">
          <?php
            if (is_tax() || is_category()) {
              global $wp_query;
              $term = $wp_query->get_queried_object();
              print $term->name;
            } else {
              the_archive_title();
            }
          ?>
        </h1>
			</header><!-- .page-header -->

      <ul>
      <?php 
      $curated_length = 0;
        // if it's the first page, set up the curated posts
  print '<h1> offset ' . $wp_query->get_query_var('offset') . '</h1>';
      if ($wp_query->get_query_var('offset')) {
        //get the curated posts (but only 4)
        $curated = z_get_zone_query(
          $wp_query->get_queried_object->name,
          array(
            'posts_per_page' => 4,
          )
        );
        $curated_length = $curated->post_count;
        print '<h2> curated found '.$curated_length . '</h2>';
        while ( $curated->have_posts() ) : $curated->the_post();
          if ($wp_query->current_post == 0) {
            //do sometihng funky for first post?
          }
          get_template_part( 'template-parts/standard-article-li' );
        endwhile;
      } // end curation mess
         
			// Start the Loop.
			while ( $wp_query->have_posts() ) : $wp_query->the_post();
        //don't do it if it's in the curated bits
        if ($curated_length && in_array(
          $post->ID,
          array_map(function($p) { return $p->ID; }, $curated->posts)
        )) {
          continue;
        } elseif ($wp_query->current_post + $curated_length == 0) {
          //do sometihng funky for first post?
        }

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'template-parts/standard-article-li' );

			// End the loop.
			endwhile;
    ?>
    </ul>
    <?php

			// Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text'          => __( 'Previous page', 'twentysixteen' ),
				'next_text'          => __( 'Next page', 'twentysixteen' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>',
			) );

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>
    </div>

    <div id="sidebar-right">
      <?php dynamic_sidebar( 'sidebar-section' ); ?>
    </div>

		</main><!-- .site-main -->
	</div><!-- .content-area -->
<?php get_footer(); ?>
