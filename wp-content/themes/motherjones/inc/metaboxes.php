<?php
/**
 * Anything and everything metabox-related.
 *
 * @package WordPress
 * @subpackage Mother_Jones
 * @since Mother Jones 1.0
 */

/**
 * Hide and rearrange some of the default metaboxes.
 * Everyone loses slug, excerpt, trackbacks, revisions and comments.
 * Admins get custom fields.
 */
function mj_remove_metaboxes() {
	global $current_user;
	wp_get_current_user();
	// Remove these for everyone.
	$remove = array( 'trackbacksdiv', 'revisionsdiv', 'commentstatusdiv', 'postexcerpt', 'slugdiv' );
	// Show these for admins only.
	if ( ! current_user_can( 'manage_options' ) ) {
		$remove[] = 'postcustom';
		$remove[] = 'mj_custom_css_js';
	}
	foreach ( $remove as $box ) {
		remove_meta_box( $box, 'post', 'normal' );
	}
}
add_action( 'do_meta_boxes','mj_remove_metaboxes' );

/**
 * Show all the other metaboxes by default.
 *
 * @param array  $hidden The array of metaboxes that are already hidden.
 * @param string $screen The type of admin screen we're looking at.
 */
function mj_change_default_hidden_metaboxes( $hidden, $screen ) {
	if ( 'post' === $screen->base ) {
		$hidden = array();
	}
	return $hidden;
}
add_filter( 'default_hidden_meta_boxes', 'mj_change_default_hidden_metaboxes', 10, 2 );

/**
 * Register the extra headline fields metabox
 */
largo_add_meta_box(
	'mj_headline_extra',
	__( 'Other Headline Fields', 'mj' ),
	'mj_headline_extra_meta_box_display',
	'post',
	'after_title',
	'high'
);
/**
 * Move the mj_headline_extra metabox to just below the title/headline.
 *
 * Usually the fifth argument in add_meta_box is side, core or advanced,
 * but apparently you can set it to something arbitrary and then call do_meta_boxes
 * with that as the context arg, hooked on the edit_form hook you want
 * (options: edit_form_after_title, edit_form_after_editor, edit_form_advanced)
 */
function mj_move_headline_extra() {
	global $post, $wp_meta_boxes;
	do_meta_boxes( get_current_screen(), 'after_title', $post );
	unset( $wp_meta_boxes['post']['test'] );
}
add_action( 'edit_form_after_title', 'mj_move_headline_extra' );
/**
 * And output the markup.
 */
function mj_headline_extra_meta_box_display() {
	global $post;
	$values = get_post_custom( $post->ID );
	$prefix = 'mj_';
	$fields = array(
		'dek' => array(
			'title' => 'Dek',
			'desc' => 'Short subtitle for this post.',
		),
		'social_hed' => array(
			'title' => 'Social Headline',
			'desc' => 'The headline used when sharing on social.',
		),
		'social_dek' => array(
			'title' => 'Social Dek',
			'desc' => 'The subtitle used when sharing on social.',
		),
	);
	wp_nonce_field( 'largo_meta_box_nonce', 'meta_box_nonce' );
	foreach ( $fields as $field => $attr ) {
		$field_slug = $prefix . $field;
		$field_title = isset( $attr['title'] ) ? $attr['title'] : '';
		$field_desc = isset( $attr['desc'] ) ? ' <span>' . $attr['desc'] . '</span>' : '';
		$field_value = isset( $values[ $field_slug ] ) ? $values[ $field_slug ][0] : '';
		printf(
			'<p>
				<label for="%1$s">%2$s%3$s</label>
				<input type="text" name="%1$s" id="%1$s" value="%4$s" />
			</p>',
			esc_attr( $field_slug ),
			esc_html( $field_title ),
			wp_kses( $field_desc, array( 'span' => array() ) ),
			esc_attr( $field_value )
		);
	}
}

/**
 * Register the post custom fields metabox
 */
largo_add_meta_box(
	'mj_custom_fields',
	__( 'Extra Fields', 'mj' ),
	'mj_custom_meta_box_display',
	'post',
	'normal',
	'core'
);
/**
 * And output the markup.
 */
function mj_custom_meta_box_display() {
	global $post;
	$values = get_post_custom( $post->ID );
	$prefix = 'mj_';
	$fields = array(
		'promo_hed' => array(
			'title' => 'Homepage Headline',
			'desc' => 'Headline used for this story on the homepage only.',
		),
		'promo_dek' => array(
			'title' => 'Homepage Dek',
			'desc' => 'Subtitle used for this story on the homepage only.',
		),
		'byline_override' => array(
			'title' => 'Byline Override',
			'desc' => 'Text to display instead of the default byline.',
		),
		'dateline_override' => array(
			'title' => 'Dateline Override',
			'desc' => 'Text (for example, issue number/date) to display instead of the default dateline.',
		),
	);
	wp_nonce_field( 'largo_meta_box_nonce', 'meta_box_nonce' );
	foreach ( $fields as $field => $attr ) {
		$field_slug = $prefix . $field;
		$field_title = isset( $attr['title'] ) ? $attr['title'] : '';
		$field_desc = isset( $attr['desc'] ) ? ' <span>' . $attr['desc'] . '</span>' : '';
		$field_value = isset( $values[ $field_slug ] ) ? $values[ $field_slug ][0] : '';
		printf(
			'<p>
				<label for="%1$s">%2$s%3$s</label>
				<input type="text" name="%1$s" id="%1$s" value="%4$s" />
			</p>',
			esc_attr( $field_slug ),
			esc_html( $field_title ),
			wp_kses( $field_desc, array( 'span' => array() ) ),
			esc_attr( $field_value )
		);
	}
}

/**
 * Register the custom css/js metabox.
 */
largo_add_meta_box(
	'mj_custom_css_js',
	__( 'Custom CSS and JavaScript', 'mj' ),
	'mj_custom_css_js_meta_box_display',
	'post',
	'normal',
	'low'
);
/**
 * And output the markup.
 */
function mj_custom_css_js_meta_box_display() {
	global $post;
	$values = get_post_custom( $post->ID );
	$prefix = 'mj_';
	$fields = array(
		'custom_css' => array(
			'title' => 'Custom CSS',
			'desc' => 'Inline CSS to be applied to this post.',
		),
		'custom_js' => array(
			'title' => 'Custom JavaScript',
			'desc' => 'Inline javascript to be output for this post.',
		),
	);
	wp_nonce_field( 'largo_meta_box_nonce', 'meta_box_nonce' );
	foreach ( $fields as $field => $attr ) {
		$field_slug = $prefix . $field;
		$field_title = isset( $attr['title'] ) ? $attr['title'] : '';
		$field_desc = isset( $attr['desc'] ) ? ' <span>' . $attr['desc'] . '</span>' : '';
		$field_value = isset( $values[ $field_slug ] ) ? $values[ $field_slug ][0] : '';
		printf(
			'<p>
				<label for="%1$s">%2$s%3$s</label>
				<input type="textarea" name="%1$s" id="%1$s" value="%4$s" />
			</p>',
			esc_attr( $field_slug ),
			esc_html( $field_title ),
			wp_kses( $field_desc, array( 'span' => array() ) ),
			esc_attr( $field_value )
		);
	}
}




/**
 * Register and sanitize the input fields.
 */
largo_register_meta_input(
	array(
		'mj_dek',
		'mj_social_hed',
		'mj_social_dek',
		'mj_promo_hed',
		'mj_promo_dek',
		'mj_byline_override',
		'mj_dateline_override',
	),
	'sanitize_text_field'
);
largo_register_meta_input(
	'mj_custom_css',
	'sanitize_text_field'
);
