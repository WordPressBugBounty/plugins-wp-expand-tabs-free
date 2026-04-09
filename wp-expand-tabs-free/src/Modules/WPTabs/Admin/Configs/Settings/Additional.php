<?php
/**
 * Additional Seettings section.
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
 * Class Additional
 */
class Additional {
	/**
	 * Register Additional Settings section.
	 *
	 * @param string $prefix The prefix for the metabox.
	 */
	public static function section( $prefix ) {
		//
		// Custom CSS Fields.
		//
		SP_CSFramework::createSection(
			$prefix,
			array(
				'id'     => 'custom_css_section',
				'title'  => __( 'Additional CSS', 'wp-expand-tabs-free' ),
				'icon'   => 'fa sp-tab-icon-code',
				'fields' => array(
					array(
						'id'       => 'sptpro_custom_css',
						'type'     => 'code_editor',
						'title'    => __( 'Custom CSS', 'wp-expand-tabs-free' ),
						'settings' => array(
							'mode'  => 'css',
							'theme' => 'default',
						),
					),
				),
			)
		);
	}
}
