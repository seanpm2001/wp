<?php
/**
 * Register all the sidebars
 *
 * @package WordPress
 * @subpackage Mother_Jones
 * @since Mother Jones 1.0
 */
function mj_widgets_init() {
	$sidebars = array (
		// the default widget areas
		array (
			'name'          => __( 'Sidebar', 'motherjones' ),
	 		'id'            => 'sidebar',
	 		'description'   => __( 'Shows up on article pages', 'mj' )
		),
		array (
			'name'          => __( 'Ticker', 'mj' ),
	 		'id'            => 'ticker',
	 		'description'   => __( 'Shows up right beneath the top menu', 'mj' )
		),
		array (
			'name'          => __( 'End of content', 'mj' ),
	 		'id'            => 'content-end',
	 		'description'   => __( 'Between the comments and the footer', 'mj' )
		),
		array (
			'name'          => __( 'End of page', 'mj' ),
	 		'id'            => 'page-end',
	 		'description'   => __( 'after EVERYTHING else.', 'mj' )
		),
		array (
			'name'          => __( 'Top of page', 'mj' ),
	 		'id'            => 'page-top',
	 		'description'   => __( 'before EVERYTHING else.', 'mj' )
		)
	);
	foreach ( $sidebars as $sidebar ) {
		register_sidebar( array(
			'name' 					=> $sidebar['name'],
			'description' 	=> $sidebar['description'],
			'id' 						=> $sidebar['id'],
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
	 		'after_widget'  => '</section>',
	 		'before_title'  => '<h2 class="widget-title">',
	 		'after_title'   => '</h2>',
		) );
	}
}
add_action( 'widgets_init', 'mj_widgets_init' );

/**
 * Unregister unused default wp widgets.
 * Register our custom widgets.
 */
function mj_widgets() {
	$unregister = array(
		'WP_Widget_Pages',
		'WP_Widget_Calendar',
		'WP_Widget_Links',
		'WP_Widget_Tag_Cloud',
		'WP_Widget_Meta',
		'WP_Widget_Recent_Comments',
		'WP_Widget_RSS',
		'WP_Widget_Recent_Posts',
	);
	foreach ( $unregister as $widget ) {
		unregister_widget( $widget );
	}
	$register = array(
		'mj_author_bio_widget' => '/inc/widgets/mj-author-bio.php',
		'mj_related_articles' => '/inc/widgets/mj-related-articles.php',
		'mj_blog_pager' => '/inc/widgets/mj-blog-pager.php',
	);
	foreach ( $register as $key => $val ) {
		require_once( get_template_directory() . $val );
		register_widget( $key );
	}
}
add_action( 'widgets_init', 'mj_widgets', 1 );

/*
content-single
if ( mj_is_article_type( 'blogpost', $post->ID ) ) {
	get_template_part( 'template-parts/blog-pager' );
} else {
	get_template_part( 'template-parts/related-articles' );
}

single-full_width_article

get_template_part( 'template-parts/related-articles' );


*/
