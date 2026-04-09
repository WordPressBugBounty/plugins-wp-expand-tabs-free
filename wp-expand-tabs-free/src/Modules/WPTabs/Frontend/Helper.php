<?php
/**
 * The Helper class to manage all public facing stuffs.
 *
 * @version   3.2.0
 *
 * @package   ShapedPlugin\SmartTabsFree
 * @subpackage Frontend
 */

namespace ShapedPlugin\SmartTabsFree\Modules\WPTabs\Frontend;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use ShapedPlugin\SmartTabsFree\Core\Constants;

/**
 * The Helper Class.
 *
 * @since 3.2.0
 */
class Helper {
	/**
	 * Load dynamic style for the existing shortcode ids.
	 *
	 * @param  mixed $found_shortcode_id to push id for getting how many shortcode in the current page.
	 * @param  mixed $shortcode_data to get all options from the existing shortcode id.
	 * @return array
	 */
	public static function load_dynamic_style( $found_shortcode_id, $shortcode_data = '' ) {
		$settings             = get_option( 'sp-tab__settings' );
		$sptpro_custom_css    = isset( $settings['sptpro_custom_css'] ) ? trim( html_entity_decode( $settings['sptpro_custom_css'] ) ) : '';
		$accordion_mode       = false;
		$sptpro_dynamic_style = '';
		if ( is_array( $found_shortcode_id ) ) {
			wp_enqueue_style( 'sptpro-animate-css' );

			foreach ( $found_shortcode_id as $post_id ) {
				if ( $post_id && is_numeric( $post_id ) && get_post_status( $post_id ) !== 'trash' ) {
					$sptpro_shortcode_options = get_post_meta( $post_id, 'sp_tab_shortcode_options', true );
					include Constants::wp_tabs_dynamic_style() . '/dynamic_style.php';
					if ( ! $accordion_mode && 'accordion_mode' === $sptpro_tabs_on_small_screen ) {
						$accordion_mode = true;
					}
				}
			}
		} else {
			$post_id                  = $found_shortcode_id;
			$sptpro_shortcode_options = $shortcode_data;
			include Constants::wp_tabs_dynamic_style() . '/dynamic_style.php';
			if ( ! $accordion_mode && 'accordion_mode' === $sptpro_tabs_on_small_screen ) {
				$accordion_mode = true;
			}
		}
		if ( ! empty( $sptpro_custom_css ) ) {
			$sptpro_dynamic_style .= $sptpro_custom_css;
		}
		$dynamic_style = array(
			'dynamic_css' => self::minify_output( $sptpro_dynamic_style ),
			'accordion'   => $accordion_mode,
		);
		return $dynamic_style;
	}

	/**
	 * Get the existing shortcode id, page id and option key from the current page.
	 *
	 * @return array
	 */
	public static function get_page_data() {
		$current_page_id = get_queried_object_id();

		if ( ! $current_page_id || ! is_singular() ) {
			return array(
				'page_id'      => 0,
				'generator_id' => array(),
			);
		}

		$used_tabs = get_post_meta(
			$current_page_id,
			'_sp_tabs_used_generators',
			true
		);

		return array(
			'page_id'      => $current_page_id,
			'generator_id' => is_array( $used_tabs ) ? $used_tabs : array(),
		);
	}

	/**
	 * If the option does not exist, it will be created.
	 *
	 * It will be serialized before it is inserted into the database.
	 *
	 * @param  string $post_id shortcode id.
	 * @param  array  $get_page_data Get the existing page-id, shortcode-id and option-key from the current page.
	 * @return void
	 */
	public static function tabs_update_options( $post_id, $get_page_data ) {
		$current_page_id = absint( $get_page_data['page_id'] );

		// Only track on singular pages.
		if ( ! $current_page_id || ! is_singular() ) {
			return;
		}

		$meta_key  = '_sp_tabs_used_generators';
		$used_tabs = get_post_meta( $current_page_id, $meta_key, true );
		$used_tabs = is_array( $used_tabs ) ? $used_tabs : array();

		if ( ! in_array( $post_id, $used_tabs, true ) ) {
			$used_tabs[] = $post_id;
			update_post_meta( $current_page_id, $meta_key, $used_tabs );
		}
	}

	/**
	 * Minify output
	 *
	 * @param  string $html output minifier.
	 * @return statement
	 */
	public static function minify_output( $html ) {
		$html = preg_replace( '/<!--(?!s*(?:[if [^]]+]|!|>))(?:(?!-->).)*-->/s', '', $html );
		$html = str_replace( array( "\r\n", "\r", "\n", "\t" ), '', $html );
		while ( stristr( $html, '  ' ) ) {
			$html = str_replace( '  ', ' ', $html );
		}
		return $html;
	}
}
