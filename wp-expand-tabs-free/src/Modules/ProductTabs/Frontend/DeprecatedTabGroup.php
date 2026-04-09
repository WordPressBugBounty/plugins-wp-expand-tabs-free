<?php
/**
 * The file that defines the deprecated WooCommerce Product Tabs group for backward compatibility.
 *
 * @link http://shapedplugin.com
 * @since 3.0.1
 *
 * @package    SmartTabs\Modules\ProductTabs
 * @subpackage SmartTabs\Modules\ProductTabs\Frontend
 */

namespace ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Frontend;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use ShapedPlugin\SmartTabsFree\Modules\ProductTabs\TabHelper;
use ShapedPlugin\SmartTabsFree\Shared\SettingsHelper;

/**
 * Custom post class to register the Product tab.
 */
class DeprecatedTabGroup {
	/**
	 * Registerer of product tabs assets.
	 *
	 * @param Loader $loader The loader that's responsible for maintaining and registering all hooks.
	 * @var string
	 */
	public function register( $loader ) {
		$loader->add_filter( 'woocommerce_product_tabs', $this, 'sptpro_woo_tab_group', 10 );
	}

	/**
	 * Show tab title inside tab content if enabled in settings.
	 *
	 * @param array $tab Tab data array.
	 */
	private function tab_heading_inside_tab( $tab ) {
		// Return early if the setting to show heading inside tab is false.
		if ( ! TabHelper::get_advanced_setting( 'show_tab_heading_inside_tab', true ) ) {
			return;
		}

		// Tab Title HTML.
		$tab_title_html = '<h2 class="sptpro-woo-tab-title sptpro-woo-tab-title-' . urldecode( sanitize_title( $tab['title'] ) ) . '">' . $tab['title'] . '</h2>';
		echo wp_kses_post( apply_filters( 'sptpro_repeatable_product_tabs_heading', $tab_title_html, $tab ) );
	}

	/**
	 * Support the deprecated WooCommerce product tabs group for backward compatibility.
	 *
	 * @return array $tabs
	 * @param array $tabs woo tab.
	 */
	public function sptpro_woo_tab_group( $tabs ) {
		global $product;
		$sptpro_woo_set_tabs = SettingsHelper::get_general_setting( 'sptpro_woo_set_tab', array() );

		if ( empty( $sptpro_woo_set_tabs ) && ! is_array( $sptpro_woo_set_tabs ) ) {
			return $tabs;
		}

		foreach ( $sptpro_woo_set_tabs as $sptpro_woo_set_tab ) {
			$sptpro_display_tab_for = $sptpro_woo_set_tab['sptpro_display_tab_for'];

			// Get the current product ID.
			$product_id = TabHelper::get_woo_product_id();
			$cat_ids    = get_the_terms( $product_id, 'product_cat' );
			$cat_ids    = wp_list_pluck( $cat_ids, 'term_id' );

			if ( 'all' === $sptpro_display_tab_for ) {
				$sptpro_tab_priority          = isset( $sptpro_woo_set_tab['sptpro_woo_tab_label_priority'] ) ? $sptpro_woo_set_tab['sptpro_woo_tab_label_priority'] : '35';
				$sptpro_woo_tab_shortcode_ids = isset( $sptpro_woo_set_tab['sptpro_woo_tab_shortcode'] ) ? $sptpro_woo_set_tab['sptpro_woo_tab_shortcode'] : array();

				if ( ! empty( $sptpro_woo_tab_shortcode_ids ) ) {
					// If the shortcode IDs are not an array, convert it to an array.
					if ( ! is_array( $sptpro_woo_tab_shortcode_ids ) ) {
						$sptpro_woo_tab_shortcode_ids = explode( ',', $sptpro_woo_tab_shortcode_ids );
					}

					foreach ( $sptpro_woo_tab_shortcode_ids as $key => $sptpro_woo_tab_shortcode_id ) {
						$sptpro_data_src = get_post_meta( $sptpro_woo_tab_shortcode_id, 'sp_tab_source_options', true );
						$sptpro_tab_type = ( isset( $sptpro_data_src['sptpro_tab_type'] ) ? $sptpro_data_src['sptpro_tab_type'] : '' );
						$sptpro_data_src = isset( $sptpro_data_src['sptpro_content_source'] ) ? $sptpro_data_src['sptpro_content_source'] : '';

						if ( 'content-tabs' === $sptpro_tab_type && ! empty( $sptpro_data_src ) ) {
							foreach ( $sptpro_data_src as $key => $sptpro_tab ) {
								if ( ! is_array( $sptpro_tab ) ) {
									$sptpro_tab = array();
								}
								++$sptpro_tab_priority;

								// Check if WPML is active.
								if ( function_exists( 'icl_object_id' ) ) {
									// Get the language details for this product ID.
									$language_product_info   = apply_filters( 'wpml_post_language_details', null, $product_id );
									$language_shortcode_info = apply_filters( 'wpml_post_language_details', null, $sptpro_woo_tab_shortcode_id );

									$product_lang   = $language_product_info['language_code'];
									$shortcode_lang = $language_shortcode_info['language_code'];
								}

								if ( ! empty( $sptpro_tab ) && is_array( $sptpro_tab ) ) {
									if ( ! function_exists( 'icl_object_id' ) || $product_lang === $shortcode_lang ) {
										$tabs[ 'sptpro_tab_' . $sptpro_tab_priority . $key ] = array(
											'title'    => $sptpro_tab['tabs_content_title'],
											'priority' => '50',
											'callback' => array( $this, 'sptpro_product_tabs_group_panel_content' ),
											'content'  => $sptpro_tab['tabs_content_description'],
										);
									}
								}
							}
						}
					}
				}
			}
		}
		return $tabs;
	}

	/**
	 * Renders the content for WooCommerce product tab group.
	 *
	 * @param array $key tab key.
	 * @param array $tab woo tab content.
	 * @return void
	 */
	public function sptpro_product_tabs_group_panel_content( $key, $tab ) {
		$content = '';

		$sptpro_the_content_filter = apply_filters( 'sptpro_the_content_filter', true );

		if ( $sptpro_the_content_filter ) {
			$content = apply_filters( 'the_content', $tab['content'] );
		} else {
			$content = apply_filters( 'sptpro_woo_tab_content', $tab['content'] );
		}

		$tab_title_html = '<h2 class="sptpro-woo-tab-title sptpro-woo-tab-title-' . urldecode( sanitize_title( $tab['title'] ) ) . '">' . $tab['title'] . '</h2>';
		echo '<div class="sp-tab__tab-content">';
		echo wp_kses_post( apply_filters( 'sptpro_repeatable_product_tabs_heading', $tab_title_html, $tab ) );
		echo apply_filters( 'sptpro_repeatable_product_tabs_content', $content, $tab ); // @codingStandardsIgnoreLine
		echo '</div>';
	}
}
