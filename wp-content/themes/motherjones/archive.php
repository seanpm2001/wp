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
 * @subpackage Mother_Jones
 * @since Mother Jones 1.0
 */

get_header(); ?>

<div id="content" class="site-content">
	<div id="primary" class="content-area">
    <main id="main" class="site-main group" role="main">

	    <div class="main-index">
			<?php if ( have_posts() ) : ?>

				<div class="page-header">
	        <h1 class="page-title promo">
	          <?php
	            if ( is_tax() || is_category() ) {
	              global $wp_query;
	              $term = $wp_query->get_queried_object();
	              print $term->name;
	            } else {
	              the_archive_title();
	            }
	          ?>
	        </h1>
				</div><!-- .page-header -->

	      <ul class="articles-list">
	      <?php
		      $posts_shown = 0;

					// Start the Loop.
					while ( $wp_query->have_posts() ) : $wp_query->the_post();
		        if ( $posts_shown === 1 ) {
		          get_template_part( 'template-parts/top-index-article' );
		        } else {
		          get_template_part( 'template-parts/standard-article-li' );
		        }
		        $posts_shown++;

		        if ( $posts_shown === 5 ) : ?>
		          <script>
		            ad_code({
		                yieldmo: true,
		               docwrite: true,
		                desktop: false,
		              placement: 'ym_869408394552483686',
		            });
		          </script>
		        <?php endif;
					// End the loop.
					endwhile;
		    ?>
		    </ul>
		    <div id="pager">
		      <span class="pager_previous">
		        <?php previous_posts_link( 'Previous' ); ?>
		      </span>
		      <span class="pager_next">
		        <?php next_posts_link( 'Next' ); ?>
		      </span>
		    </div>

		    <?php
				// If no content, include the "No posts found" template.
				else :
					get_template_part( 'template-parts/content', 'none' );

				endif;
				?>
			</div> <!-- .main-index -->

	    <div id="sidebar-right">
	      <script language="javascript">
	          <!--
	          if ( typeof MJ_HideRightColAds === 'undefined' ) {
	            ad_code({
	              desktop: true,
	              placement: 'RightTopROS300x600',
	              height: 529,
	              doc_write: true,
	            });
	          }
	          //-->
	      </script>
	      <?php dynamic_sidebar( 'sidebar-section' ); ?>
	    </div>

		</main><!-- .site-main -->
	</div><!-- .content-area -->
  <script>
    ad_code({
      yieldmo: true,
     docwrite: true,
      desktop: false,
      placement: 'ym_869408549909503847',
    });
  </script>
	<?php get_footer(); ?>
