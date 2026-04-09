<?php
/**
 * Demonstrates Upgrade to Pro feature list section.
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
class UpgradeToPro {
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
				'id'     => 'upgrade_to_pro_section',
				'title'  => __( 'UPGRADE TO PRO', 'wp-expand-tabs-free' ),
				'icon'   => 'fa icon-upgrade-to-pro',
				'fields' => array(
					array(
						'type'      => 'notice',
						'ignore_db' => true,
						'class'     => 'upgrade-to-pro-notice',
						'content'   => (
							SP_CSFramework::upgrade_to_pro_section()
						),
					),
				),
			)
		);
	}
}
