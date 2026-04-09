<?php
/**
 * Advanced Settings section.
 *
 * @link       https://shapedplugin.com/
 * @since      3.2.0
 *
 * @package    ShapedPlugin\SmartTabsFree
 * @subpackage Modules\WPTabs\Admin\Configs\Settings
 */

namespace ShapedPlugin\SmartTabsFree\Modules\WPTabs\Admin\Configs\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

use ShapedPlugin\SmartTabsFree\Lib\CSFramework\Classes\SP_CSFramework;

/**
 * Class Advanced
 */
class Advanced {
	/**
	 * Register Advanced Settings section.
	 *
	 * @param string $prefix The prefix for the metabox.
	 */
	public static function section( $prefix ) {
		//
		// Create a section.
		//
		SP_CSFramework::createSection(
			$prefix,
			array(
				'title'  => __( 'Advanced Controls', 'wp-expand-tabs-free' ),
				'icon'   => 'fa sp-tab-icon-advanced',
				'fields' => array(
					array(
						'id'         => 'sptpro_data_remove',
						'type'       => 'checkbox',
						'title'      => __( 'Clean-up Data on Deletion', 'wp-expand-tabs-free' ),
						'title_help' => __( 'Check this box if you would like Smart Tabs to completely remove all of its data when the plugin is deleted.', 'wp-expand-tabs-free' ),
						'default'    => false,
					),
					array(
						'id'         => 'enable_product_tabs',
						'type'       => 'switcher',
						'title'      => __( 'Product Tabs', 'wp-expand-tabs-free' ),
						'text_on'    => __( 'Enabled', 'wp-expand-tabs-free' ),
						'text_off'   => __( 'Disabled', 'wp-expand-tabs-free' ),
						'title_help' => __( 'Disable this option if your website does not have a WooCommerce store. In this case, the product tabs functionality will not be loaded, keeping your admin panel optimized and faster.', 'wp-expand-tabs-free' ),
						'default'    => true,
						'text_width' => 100,
					),
				),
			)
		);
	}
}
