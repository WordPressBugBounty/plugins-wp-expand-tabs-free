<?php
/**
 * Post Meta ( post author, post date and categories list).
 *
 * @package   wp-expand-tabs-free
 * @subpackage wp-expand-tabs-free/partials
 */

// Post meta( author name, date and categories).
$enable_post_meta = apply_filters( 'sp_wp_tabs_enable_post_meta', true );
$post_meta_html   = '';
if ( $enable_post_meta ) {
	$post_author                = get_the_author_link();
	$post_date                  = esc_html( get_the_date( get_option( 'date_format' ) ) );
	$post_categories            = wp_get_post_categories( get_the_ID(), array( 'fields' => 'all' ) );
	$prefix_before_author_name  = apply_filters( 'sp_wp_tabs_author_before_author_name', __( 'By ', 'wp-expand-tabs-free' ) );
	$separator_among_posts_meta = apply_filters( 'sp_wp_tabs_meta_separator', ' / ' );
	$prefix_before_date         = apply_filters( 'sp_wp_tabs_prefix_before_date', __( 'On ', 'wp-expand-tabs-free' ) );

	$post_meta_html .= '<div class="wp-tabs-post-meta">
		<span class="post-author"> ' . esc_html( $prefix_before_author_name ) . ( $post_author ) . ' </span>' . esc_html( $separator_among_posts_meta ) . '
		<span class="post-date">' . esc_html( $prefix_before_date ) . esc_html( $post_date ) . '</span>';
	if ( $post_categories ) { // Always Check before loop for category name!
		$post_meta_html .= esc_html( $separator_among_posts_meta );
		// get the last array key (PHP 7.2 compatible).
		$category_keys    = array_keys( $post_categories );
		$last_key         = end( $category_keys );
		$count_total_cats = count( $post_categories ) >= 2 ? ', ' : '';

		foreach ( $post_categories as $category_key => $category ) {
			$count_total_cats = ( $category_key === $last_key ) ? '' : $count_total_cats; // remove comma from the last array element.
			$post_meta_html  .= '<span class="wp-tabs-post-category-name"><a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . esc_html( $count_total_cats ) . '</a></span>';
		}
	}
	$post_meta_html .= '</div>';
}

$post_meta_position            = apply_filters( 'sp_tabs_post_meta_position', 'before_descriptions' );
$post_meta_before_descriptions = 'before_descriptions' === $post_meta_position ? wp_kses( $post_meta_html, $allow_meta_tag ) : '';
$post_meta_after_title         = 'before_descriptions' !== $post_meta_position ? wp_kses( $post_meta_html, $allow_meta_tag ) : '';
