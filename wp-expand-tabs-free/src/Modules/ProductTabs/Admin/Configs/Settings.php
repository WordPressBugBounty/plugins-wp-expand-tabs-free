<?php
/**
 * The settings options of the plugin.
 *
 * @link       https://shapedplugin.com/
 * @since      3.2.0
 *
 * @package    ShapedPlugin\SmartTabsFree
 * @subpackage Modules\ProductTabs\Admin\Configs
 */

namespace ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin\Configs;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

use ShapedPlugin\SmartTabsFree\Lib\CSFramework\Classes\SP_CSFramework;

use ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin\Configs\Advanced;
use ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin\Configs\TabStyle\General;
use ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin\Configs\TabStyle\Display;
use ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin\Configs\TabStyle\Typography;

/**
 * Class Settings
 */
class Settings {

	/**
	 * Register tab style settings.
	 *
	 * @param string $prefix The settings key.
	 */
	public static function style_settings( $prefix ) {
		SP_CSFramework::createOptions(
			$prefix,
			array(
				'menu_title'         => __( 'Style', 'wp-expand-tabs-free' ),
				'menu_slug'          => 'tab_styles',
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
				'framework_title'    => 'Product Tabs Style',
				'framework_class'    => 'sp-woo_product_tabs__options',
				'theme'              => 'light',
			)
		);

		General::section( $prefix );
		Display::section( $prefix );
		Typography::section( $prefix );
	}


	/**
	 * Register Advanced Tabs Settings.
	 *
	 * @param string $prefix The settings key.
	 */
	public static function advanced_settings( $prefix ) {
		SP_CSFramework::createOptions(
			$prefix,
			array(
				'menu_title'         => __( 'Advanced', 'wp-expand-tabs-free' ),
				'menu_slug'          => 'tab_advanced',
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
				'framework_title'    => 'Advanced',
				'framework_class'    => 'sp-woo_product_tabs__options',
				'theme'              => 'light',
			)
		);

		Advanced::section( $prefix );
	}
}
