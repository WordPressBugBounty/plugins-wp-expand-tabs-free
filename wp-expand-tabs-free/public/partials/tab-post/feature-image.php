<?php
/**
 * Post featured image.
 *
 * @package   wp-expand-tabs-free
 * @subpackage wp-expand-tabs-free/partials
 */

$show_post_feature_image = apply_filters( 'sp_wp_tabs_show_post_feature_image', true );

$post_thumb = '';
if ( $show_post_feature_image ) {
	$post_thumb_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
	$post_thumb_url = $post_thumb_url && is_array( $post_thumb_url ) ? $post_thumb_url : array( '', '', '' );
	$image_alt_text = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true );

	if ( has_post_thumbnail() && ! empty( $post_thumb_url[0] ) ) {
		$post_thumb = '<div class="wp-tabs-featured-image">
	<img src="' . $post_thumb_url[0] . '" width="' . $post_thumb_url[1] . '" height="' . $post_thumb_url[2] . '" alt="' . $image_alt_text . '" class="post-tabs-image">
	</div>';
	}
}
