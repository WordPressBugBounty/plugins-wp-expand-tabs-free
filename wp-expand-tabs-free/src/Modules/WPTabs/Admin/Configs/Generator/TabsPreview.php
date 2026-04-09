<?php
/**
 * The Tabs Preview section.
 *
 * @link       https://shapedplugin.com/
 * @since      3.2.0
 *
 * @package    ShapedPlugin\SmartTabsFree
 * @subpackage Modules\WPTabs\Admin\Configs
 */

namespace ShapedPlugin\SmartTabsFree\Modules\WPTabs\Admin\Configs\Generator;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

use ShapedPlugin\SmartTabsFree\Lib\CSFramework\Classes\SP_CSFramework;

/**
 * Class TabsPreview
 */
class TabsPreview {
	/**
	 * Register Tabs Preview section.
	 *
	 * @param string $prefix The prefix for the metabox.
	 */
	public static function section( $prefix ) {
		SP_CSFramework::createSection(
			$prefix,
			array(
				'fields' => array(
					array(
						'type' => 'preview',
					),
				),
			)
		);
	}
}
