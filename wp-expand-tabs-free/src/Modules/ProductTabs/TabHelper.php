<?php
/**
 * The Helper class to manage all Admin-public facing stuffs.
 *
 * @version   3.2.1
 *
 * @package   ShapedPlugin\SmartTabsFree
 * @subpackage Modules\ProductTabs
 */

namespace ShapedPlugin\SmartTabsFree\Modules\ProductTabs;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use ShapedPlugin\SmartTabsFree\Core\Constants;
use ShapedPlugin\SmartTabsFree\Shared\DependencyChecker;

/**
 * The Product Tab Helper Class.
 *
 * @since 3.2.1
 */
class TabHelper {
	/**
	 * General Tab Settings.
	 *
	 * @var array
	 */
	protected static $advanced_settings = null;

	/**
	 * Get Advanced Tab Settings.
	 *
	 * @param string $key The setting key to retrieve.
	 * @param mixed  $option_default The default value to return if the key does not exist.
	 * @return mixed The value of the setting if it exists, otherwise the default value.
	 */
	public static function get_advanced_setting( $key, $option_default = null ) {
		if ( null === self::$advanced_settings ) {
			$settings                = get_option( 'sp_products_tabs_advanced', array() );
			self::$advanced_settings = is_array( $settings ) ? $settings : array();
		}

		return self::$advanced_settings[ $key ] ?? $option_default;
	}

	/**
	 * Get product tab settings for a specific product.
	 *
	 * @param int $post_id Product ID.
	 * @return array The tab settings.
	 */
	public static function get_product_tab_meta_options( $post_id ) {
		$tab_meta_options = get_post_meta( $post_id, 'sptpro_woo_product_tabs_settings', true );

		return ! empty( $tab_meta_options ) ? $tab_meta_options : array();
	}

	/**
	 * Determine whether to skip loading product tab styles.
	 *
	 * @return bool True if styles should be skipped, false otherwise.
	 */
	public static function is_skip_product_style() {
		$skip_product_tab_default = empty( Constants::first_installed_version() )
			|| version_compare( Constants::first_installed_version(), '3.0.0', '>=' )
			? false : true;

		// Determine whether to skip loading the product tab styles based on the advanced settings.
		return self::get_advanced_setting( 'skip_product_tab_style', $skip_product_tab_default );
	}

	/**
	 * Check whether a product tab is globally enabled.
	 *
	 * @param int $tab_id The post ID of the product tab.
	 * @return bool True if the tab is enabled, false otherwise.
	 */
	public static function is_product_tab_globally_enabled( $tab_id ) {
		$is_enabled = get_post_meta( $tab_id, 'show_product_tabs', true );

		// Consider it enabled only if meta exists and is not false or empty.
		return ! empty( $is_enabled ) && false !== $is_enabled;
	}

	/**
	 * Check if a default WooCommerce tab is globally enabled.
	 *
	 * @param string $tab_slug The slug of the default tab (e.g., 'description').
	 * @return bool True if enabled, false if globally disabled.
	 */
	public static function is_default_tab_globally_enabled( $tab_slug ) {
		$custom_tab_query = get_posts(
			array(
				'post_type'      => 'sp_products_tabs',
				'meta_key'       => '_wc_default_tab_slug',
				'meta_value'     => $tab_slug,
				'posts_per_page' => 1,
				'post_status'    => 'publish',
			)
		);

		if ( ! empty( $custom_tab_query ) ) {
			$custom_tab_id = $custom_tab_query[0]->ID;

			// check global enabled/disabe status.
			return self::is_product_tab_globally_enabled( $custom_tab_id );
		}

		return true; // Default to enabled.
	}

	/**
	 * Returns the current WooCommerce product ID.
	 *
	 * If the product object is not available, it tries to get the product ID from the current post object.
	 * If the post object is not a product, it will return 0.
	 * If the product object is not available and in the Divi builder, it will return the first product ID for preview.
	 *
	 * @return int The WooCommerce product ID.
	 */
	public static function get_woo_product_id() {
		global $product, $post;
		$product_id = 0;

		if ( $product instanceof \WC_Product ) {
			$product_id = $product->get_id();
		} elseif ( $post && 'product' === $post->post_type ) {
			$product_id = $post->ID;
		}

		$et_pb_preview = isset( $_GET['et_pb_preview'] ) ? sanitize_text_field( wp_unslash( $_GET['et_pb_preview'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Only checking builder preview state, no data processing.
		$et_fb         = isset( $_GET['et_fb'] ) ? sanitize_text_field( wp_unslash( $_GET['et_fb'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Only checking builder preview state, no data processing.

		if ( ( $et_fb || $et_pb_preview ) && ! $product_id ) {

			$preview_product = get_posts(
				array(
					'post_type'      => 'product',
					'posts_per_page' => 1,
					'post_status'    => 'publish',
				)
			);

			if ( ! empty( $preview_product ) ) {
				$product_id = $preview_product[0]->ID; // Get only the first product ID.
				$product    = wc_get_product( $product_id );
			}
		}

		return intval( $product_id );
	}

	/**
	 * Determine whether a tab should be visible for a specific product.
	 *
	 * @param int   $product_id         The WooCommerce product ID.
	 * @param array $settings           Tab settings from post meta.
	 * @param array $product_brands     Array of product brand term IDs.
	 *
	 * @return bool True if the tab should be shown, false otherwise.
	 */
	public static function is_tab_visible_for_product( $product_id, $settings, $product_brands ) {
		$show_in = $settings['tabs_show_in'] ?? 'all_product';

		$check_exclude = $settings['tabs_exclude'] ?? false;
		$excluded      = $settings['exclude_specific'] ?? array();
		$show_brands   = $settings['tabs_show_by_brands'] ?? array();

		if ( 'specific_product' !== $show_in && $check_exclude && ! empty( $excluded ) && in_array( $product_id, $excluded, false ) ) {
			return false;
		}

		switch ( $show_in ) {
			case 'brand':
				return ! empty( array_intersect( array_map( 'absint', (array) $show_brands ), $product_brands ) );

			case 'all_product':
			default:
				return true;
		}
	}

	/**
	 * Retrieves WooCommerce products based on different query types for tabbed display.
	 *
	 * Supported query types:
	 * - 'latest'   : Fetches the latest published products.
	 * - 'specific'  : Fetches specific products by ID.
	 * - 'taxonomy' : Fetches products matching given taxonomy terms (e.g., category, tag).
	 * - 'related'  : Fetches related products for a given reference product ID.
	 *
	 * @param array $args An array of arguments to control the product query.
	 * @param array $specific_products Specific Product IDs.
	 * @param array $limit The total number of displaying products.
	 *
	 * @return array List of product IDs matching the given query type.
	 */
	public static function sp_get_tabbed_products( $args = array(), $specific_products = array(), $limit = 6 ) {
		// Defaults & Sanitization.
		$defaults = array(
			'query_type'      => 'latest',
			'product_ids'     => $specific_products,
			'orderby'         => 'date',
			'order'           => 'DESC',
			'limit'           => $limit,
			'taxonomy'        => 'product_cat',
			'current_post_id' => get_the_ID(),
		);

		$args = wp_parse_args( $args, $defaults );

		$query_args = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => (int) $args['limit'],
			'orderby'        => sanitize_text_field( $args['orderby'] ),
			'order'          => sanitize_text_field( $args['order'] ),
			'fields'         => 'ids',
		);

		switch ( $args['query_type'] ) {
			case 'related':
				$product_id = absint( $args['current_post_id'] );

				// Bail early if no valid product ID or taxonomy doesn't exist.
				if ( ! $product_id ) {
					break;
				}

				$term_ids_cat = wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'ids' ) );
				$term_ids_tag = wp_get_post_terms( $product_id, 'product_tag', array( 'fields' => 'ids' ) );

				// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				$query_args['tax_query'] = array(
					'relation' => 'OR',
				);

				if ( ! empty( $term_ids_cat ) ) {
					$query_args['tax_query'][] = array(
						'taxonomy' => 'product_cat',
						'field'    => 'id',
						'terms'    => $term_ids_cat,
					);
				}

				if ( ! empty( $term_ids_tag ) ) {
					$query_args['tax_query'][] = array(
						'taxonomy' => 'product_tag',
						'field'    => 'id',
						'terms'    => $term_ids_tag,
					);
				}

				// @codingStandardsIgnoreLine
				$query_args['post__not_in'] = array( $product_id );
				break;

			case 'latest':
			default:
				break;
		}
		$query = new \WP_Query( $query_args );
		return $query->posts;
	}

	/**
	 * Determine whether the tab has meaningful content.
	 *
	 * @param string $content Raw tab content from DB.
	 * @return bool True if content is non-empty.
	 */
	public static function has_tab_content( $content ) {

		if ( ! is_string( $content ) ) {
			return false;
		}

		// Expand shortcodes (many produce empty HTML).
		$content = do_shortcode( $content );

		// Normalize non-breaking spaces.
		$content = str_replace( array( '&nbsp;', "\xc2\xa0" ), ' ', $content );

		// Detect images, galleries, iframes, videos, etc.
		if ( preg_match( '/<(img|figure|iframe|video|audio|source|embed)[^>]*>/i', $content ) ) {
			return true;
		}

		// Strip all HTML tags.
		$content = wp_strip_all_tags( $content );

		// Remove any leftover whitespace, including Unicode.
		$content = preg_replace( '/\s+/u', '', $content );

		return '' !== $content;
	}
}
