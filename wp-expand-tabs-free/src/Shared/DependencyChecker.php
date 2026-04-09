<?php
/**
 * Dependency Checker
 *
 * Provides utility methods to check if required or optional third-party
 * plugins, themes, or features are active, available, or compatible
 * with the Smart Tabs plugin.
 *
 * This class centralizes all environment and dependency checks to ensure
 * maintainable and consistent handling of third-party integrations such as:
 * - WooCommerce
 * - Divi Builder (Classic, Visual, Theme Builder)
 * - Other future plugin dependencies
 *
 * @package    ShapedPlugin\SmartTabsFree\Shared\Util
 * @subpackage DependencyChecker
 * @since      3.2.0
 * @author     ShapedPlugin <help@shapedplugin.com>
 */

namespace ShapedPlugin\SmartTabsFree\Shared;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use ShapedPlugin\SmartTabsFree\Modules\ProductTabs\TabHelper;

/**
 * Class DependencyChecker
 *
 * @package ShapedPlugin\SmartTabsFree\Shared
 */
class DependencyChecker {

	/**
	 * Flag indicating whether the Divi Builder is being used.
	 *
	 * @since 3.2.0
	 *
	 * @var bool
	 */
	protected static $use_divi_builder = null;

	/**
	 * Check whether WooCommerce is active (single or network site).
	 *
	 * @return bool
	 */
	public static function is_woocommerce_active() {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		return is_plugin_active( 'woocommerce/woocommerce.php' )
		|| is_plugin_active_for_network( 'woocommerce/woocommerce.php' );
	}

	/**
	 * Check if the current product/page is being rendered or edited with Divi Builder.
	 *
	 * Handles:
	 * - Classic Builder
	 * - Visual Builder (Frontend)
	 * - Theme Builder Templates
	 * - Divi Preview Modes
	 *
	 * @return bool
	 */
	public static function is_divi_builder_active() {
		if ( is_null( self::$use_divi_builder ) ) {
			self::$use_divi_builder = false;

			// Detect if Divi Theme Builder is rendering a layout.
			if ( function_exists( 'et_theme_builder_overrides_layout' ) && et_theme_builder_overrides_layout( ET_THEME_BUILDER_BODY_LAYOUT_POST_TYPE ) ) {
				self::$use_divi_builder = true;
			}

			// Detect Divi Frontend Builder (Visual Builder).
			if ( ! self::$use_divi_builder && function_exists( 'et_core_is_fb_enabled' ) && et_core_is_fb_enabled() ) {
				self::$use_divi_builder = true;
			}

			// Detect Divi backend or preview mode (via query vars).
			// if ( ! self::$use_divi_builder && ( isset( $_GET['et_fb'] ) || isset( $_GET['et_pb_preview'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
			// self::$use_divi_builder = true;
			// }

			// Detect product-specific builder usage (Classic or Visual).
			if ( ! self::$use_divi_builder ) {
				$product_id = TabHelper::get_woo_product_id();

				if ( $product_id ) {
					$use_divi_visual_builder  = get_post_meta( $product_id, '_et_pb_use_builder', true );
					$use_divi_classic_builder = get_post_meta( $product_id, 'et_pb_use_builder', true );

					if ( 'on' === $use_divi_visual_builder || 'on' === $use_divi_classic_builder ) {
						self::$use_divi_builder = true;
					}
				}
			}
		}

		return self::$use_divi_builder;
	}

	/**
	 * Check if Elementor is active and in preview mode.
	 *
	 * @return bool
	 */
	public static function is_elementor_preview(): bool {
		return class_exists( '\Elementor\Plugin' )
			&& isset( \Elementor\Plugin::$instance->preview )
			&& \Elementor\Plugin::$instance->preview->is_preview_mode();
	}

	/**
	 * Check if WPML is active.
	 *
	 * @return boolean
	 */
	private function is_wpml_active() {
		return function_exists( 'icl_object_id' );
	}
}
