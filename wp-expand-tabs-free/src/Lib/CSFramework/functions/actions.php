<?php
/**
 * The admin actions functions.
 *
 * @since        2.0.1
 *
 * @package    WP_Tabs
 * @subpackage WP_Tabs/admin/partials
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! function_exists( 'wptabspro_chosen_ajax' ) ) {
	/**
	 *
	 * Chosen Ajax
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function wptabspro_chosen_ajax() {
		$nonce = ( ! empty( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		$type  = ( ! empty( $_POST['type'] ) ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';
		$term  = ( ! empty( $_POST['term'] ) ) ? sanitize_text_field( wp_unslash( $_POST['term'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'wptabspro_chosen_ajax_nonce' ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Error: Nonce verification has failed. Please try again.', 'wp-expand-tabs-free' ) ) );
		}

		if ( empty( $type ) || empty( $term ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Error: Missing request arguments.', 'wp-expand-tabs-free' ) ) );
		}

		$capability = apply_filters( 'wptabspro_chosen_ajax_capability', 'manage_options' );

		if ( ! current_user_can( $capability ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'You do not have required permissions to access.', 'wp-expand-tabs-free' ) ) );
		}

		// Success. Query arguments from the request are reduced to the keys the query-type fields declare before use.
		$query_args = wptabspro_scrub_chosen_query_args( isset( $_POST['query_args'] ) ? wp_unslash( $_POST['query_args'] ) : array() ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized within wptabspro_scrub_chosen_query_args().

		$options = SP_WP_TABS_Fields::field_data( $type, $term, $query_args );

		wp_send_json_success( $options );
	}
	add_action( 'wp_ajax_wptabspro-chosen', 'wptabspro_chosen_ajax' );
}

if ( ! function_exists( 'wptabspro_scrub_chosen_query_args' ) ) {
	/**
	 * Reduce chosen AJAX query arguments to the keys the query-type fields declare.
	 *
	 * Values are validated against what WordPress registers (post types,
	 * taxonomies, orderings) or reduced to their scalar shape, so
	 * request-supplied arguments cannot steer the queries arbitrarily and
	 * never reach an allow-listed callable.
	 *
	 * @since 3.1.8
	 * @param array $query_args Unslashed query arguments from the request.
	 * @return array
	 */
	function wptabspro_scrub_chosen_query_args( $query_args ) {
		$scrubbed = array();

		if ( ! is_array( $query_args ) ) {
			return $scrubbed;
		}

		// Post-related keys used by the posts, pages and custom post query types.
		if ( ! empty( $query_args['post_type'] ) ) {
			$post_types = array_values( array_intersect( array_map( 'sanitize_key', (array) $query_args['post_type'] ), get_post_types() ) );

			if ( ! empty( $post_types ) ) {
				$scrubbed['post_type'] = $post_types;
			}
		}

		// Term-related keys used by the category, tag and menu query types.
		if ( ! empty( $query_args['taxonomy'] ) ) {
			$taxonomies = array_values( array_intersect( array_map( 'sanitize_key', (array) $query_args['taxonomy'] ), get_taxonomies() ) );

			if ( ! empty( $taxonomies ) ) {
				$scrubbed['taxonomy'] = $taxonomies;
			}
		}

		if ( isset( $query_args['orderby'] ) ) {
			$orderby         = sanitize_key( $query_args['orderby'] );
			$allowed_orderby = array( 'none', 'ID', 'author', 'title', 'name', 'type', 'date', 'post_date', 'modified', 'parent', 'rand', 'comment_count', 'menu_order', 'post__in', 'relevance', 'slug', 'term_order', 'count' );

			if ( in_array( $orderby, $allowed_orderby, true ) ) {
				$scrubbed['orderby'] = $orderby;
			}
		}

		if ( isset( $query_args['order'] ) ) {
			$order = strtoupper( sanitize_text_field( $query_args['order'] ) );

			if ( in_array( $order, array( 'ASC', 'DESC' ), true ) ) {
				$scrubbed['order'] = $order;
			}
		}

		foreach ( array( 'posts_per_page', 'numberposts', 'number', 'offset' ) as $limit_key ) {
			if ( isset( $query_args[ $limit_key ] ) ) {
				$scrubbed[ $limit_key ] = absint( $query_args[ $limit_key ] );
			}
		}

		foreach ( array( 'cache_results', 'no_found_rows', 'hide_empty' ) as $flag_key ) {
			if ( isset( $query_args[ $flag_key ] ) ) {
				$scrubbed[ $flag_key ] = filter_var( $query_args[ $flag_key ], FILTER_VALIDATE_BOOLEAN );
			}
		}

		return $scrubbed;
	}
}

add_filter( 'wptabspro_fa4', '__return_true' );
