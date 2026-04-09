<?php
/**
 * Centralized constants and plugin paths/URLs.
 *
 * @link       https://shapedplugin.com/
 * @since      3.2.0
 *
 * @package    ShapedPlugin\SmartTabsFree
 * @subpackage Core
 */

namespace ShapedPlugin\SmartTabsFree\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Constants
 *
 * @since 3.2.1
 */
final class Constants {
	/**
	 * Absolute path of the plugin root file.
	 *
	 * @var string
	 */
	private static $file;

	/**
	 * Absolute directory path of the plugin.
	 *
	 * @var string
	 */
	private static $path;

	/**
	 * Plugin base URL.
	 *
	 * @var string
	 */
	private static $url;

	/**
	 * Plugin basename.
	 *
	 * @var string
	 */
	private static $basename;

	/**
	 * Plugin version.
	 */
	public const VERSION = '3.1.4';

	/**
	 * Product item slug.
	 */
	public const ITEM_SLUG = 'wp-expand-tabs-free';

	/**
	 * Product name.
	 */
	public const ITEM_NAME = 'Smart Tabs';

	/**
	 * Product name.
	 */
	public const PRO_LINK = 'https://wptabs.com/pricing/?ref=1';

	/**
	 * Initialize constant values using the plugin root file reference.
	 *
	 * @param string $plugin_file Absolute path to the main plugin file.
	 *
	 * @return void
	 */
	public static function boot( string $plugin_file ): void {
		self::$file     = $plugin_file;
		self::$path     = plugin_dir_path( $plugin_file );
		self::$url      = plugin_dir_url( $plugin_file );
		self::$basename = plugin_basename( $plugin_file );
	}

	/**
	 * Get absolute root plugin file path.
	 */
	public static function file(): string {
		return self::$file;
	}

	/**
	 * Get absolute plugin directory path.
	 */
	public static function path(): string {
		return self::$path;
	}

	/**
	 * Get plugin URL path.
	 */
	public static function url(): string {
		return self::$url;
	}

	/**
	 * Get plugin basename.
	 */
	public static function basename(): string {
		return self::$basename;
	}

	/**
	 * Get absolute path to the /src directory.
	 */
	public static function src_path(): string {
		return self::$path . 'src/';
	}

	/**
	 * Returns first installed version from DB.
	 * This cannot be a const, because it depends on runtime DB data.
	 *
	 * @return string|null
	 */
	public static function first_installed_version() {
		return get_option( 'wp_tabs_first_version' );
	}

	/**
	 * Get WP Tab module root URLs.
	 */
	public static function wp_tabs_url(): string {
		return self::$url . 'src/Modules/WPTabs/';
	}

	/**
	 * Get Product Tab module root URLs.
	 */
	public static function product_tabs_url(): string {
		return self::$url . 'src/Modules/ProductTabs/';
	}

	/**
	 * Get Product Tab module root URLs.
	 */
	public static function product_tabs_dynamic_style(): string {
		return self::$path . 'src/Modules/ProductTabs/Frontend/Assets/dynamic/';
	}

	/**
	 * Get Product Tab module root URLs.
	 */
	public static function wp_tabs_dynamic_style(): string {
		return self::$path . 'src/Modules/WPTabs/Frontend/Assets/dynamic/';
	}

	/**
	 * Get base URL for shared assets.
	 */
	public static function shared_assets(): string {
		return self::$url . 'src/Shared/Assets/';
	}

	/**
	 * Get framework assets.
	 */
	public static function cs_framework_assets(): string {
		return self::$url . 'src/Lib/CSFramework/assets/';
	}

	/**
	 * Get help page assets.
	 */
	public static function help_page_assets(): string {
		return self::$url . 'src/Core/AdminMenus/Assets/';
	}
}
