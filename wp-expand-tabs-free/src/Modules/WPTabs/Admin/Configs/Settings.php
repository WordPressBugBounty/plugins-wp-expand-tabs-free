<?php
/**
 * The settings options of the plugin.
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

use ShapedPlugin\SmartTabsFree\Core\Constants;
use ShapedPlugin\SmartTabsFree\Modules\WPTabs\Admin\Configs\Settings\Advanced;
use ShapedPlugin\SmartTabsFree\Modules\WPTabs\Admin\Configs\Settings\WooTabsGroup;
use ShapedPlugin\SmartTabsFree\Modules\WPTabs\Admin\Configs\Settings\Additional;
use ShapedPlugin\SmartTabsFree\Modules\WPTabs\Admin\Configs\Settings\UpgradeToPro;

/**
 * Class Settings
 */
class Settings {

	/**
	 * Register Settings options.
	 *
	 * @param string $prefix The prefix for the metabox.
	 */
	public static function option_settings( $prefix ) {
		SP_CSFramework::createOptions(
			$prefix,
			array(
				'menu_title'         => __( 'Settings', 'wp-expand-tabs-free' ),
				'menu_slug'          => 'tabs_settings',
				'menu_parent'        => 'edit.php?post_type=sp_wp_tabs',
				'menu_type'          => 'submenu',
				'show_bar_menu'      => false,
				'ajax_save'          => true,
				'save_defaults'      => true,
				'show_reset_all'     => false,
				'show_all_options'   => false,
				'show_reset_section' => true,
				'show_search'        => false,
				'show_footer'        => false,
				'framework_title'    => __( 'Settings', 'wp-expand-tabs-free' ),
				'framework_class'    => 'sp-tab__options',
				'theme'              => 'light',
			)
		);

		Advanced::section( $prefix );

		$first_version = get_option( 'wp_tabs_first_version' );
		// WooCommerce Tabs Group section (Deprecated).
		if ( version_compare( Constants::first_installed_version(), '3.0.0', '<' ) || version_compare( $first_version, '3.0.0', '<' ) ) {
			WooTabsGroup::section( $prefix );
		}

		Additional::section( $prefix );
		UpgradeToPro::section( $prefix );
	}
}
