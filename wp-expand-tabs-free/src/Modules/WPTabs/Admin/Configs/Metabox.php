<?php
/**
 * Plugin metabox settings section: WP Tabs.
 *
 * @link       https://shapedplugin.com/
 * @since      3.2.0
 *
 * @package    ShapedPlugin\SmartTabsFree
 * @subpackage Modules\WPTabs\Admin\Configs
 */

namespace ShapedPlugin\SmartTabsFree\Modules\WPTabs\Admin\Configs;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

use ShapedPlugin\SmartTabsFree\Lib\CSFramework\Classes\SP_CSFramework;

use ShapedPlugin\SmartTabsFree\Modules\WPTabs\Admin\Configs\Generator\TabsSource;
use ShapedPlugin\SmartTabsFree\Modules\WPTabs\Admin\Configs\Generator\General;
use ShapedPlugin\SmartTabsFree\Modules\WPTabs\Admin\Configs\Generator\Display;
use ShapedPlugin\SmartTabsFree\Modules\WPTabs\Admin\Configs\Generator\Typography;
use ShapedPlugin\SmartTabsFree\Modules\WPTabs\Admin\Configs\Generator\TabsPreview;

/**
 * Class Metabox
 */
class Metabox {

	/**
	 * Register Preview section.
	 *
	 * @param string $prefix The prefix for the metabox.
	 */
	public static function preview_section( $prefix ) {
		/**
		 * Preview metabox.
		 *
		 * @param string $prefix The metabox main Key.
		 * @return void
		 */
		SP_CSFramework::createMetabox(
			$prefix,
			array(
				'title'        => __( 'Live Preview', 'wp-expand-tabs-free' ),
				'post_type'    => 'sp_wp_tabs',
				'show_restore' => false,
				'context'      => 'normal',
			)
		);
		TabsPreview::section( $prefix );
	}

	/**
	 * Register Tab Content Source section.
	 *
	 * @param string $prefix The prefix for the metabox.
	 */
	public static function tabs_source( $prefix ) {
		//
		// Create a metabox for content source settings.
		//
		SP_CSFramework::createMetabox(
			$prefix,
			array(
				'title'     => __( 'Smart Tabs', 'wp-expand-tabs-free' ),
				'post_type' => 'sp_wp_tabs',
				'context'   => 'normal',
			)
		);
		TabsSource::section( $prefix );
	}

	/**
	 * Register Tab General, Display and Typography sections.
	 *
	 * @param string $prefix The prefix for the metabox.
	 */
	public static function tabs_metaboxes( $prefix ) {
		//
		// Create a metabox for content source settings.
		//
		SP_CSFramework::createMetabox(
			$prefix,
			array(
				'title'     => __( 'Shortcode Section', 'wp-expand-tabs-free' ),
				'post_type' => 'sp_wp_tabs',
				'context'   => 'normal',
				'theme'     => 'light',
			)
		);
		// Tabs general section.
		General::section( $prefix );
		Display::section( $prefix );
		Typography::section( $prefix );
	}
}
