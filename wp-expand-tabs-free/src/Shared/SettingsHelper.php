<?php
/**
 * Reusable Settings Helper class.
 *
 * Provides utility methods for managing and retrieving general or shared
 * plugin settings across different Smart Tabs modules, such as WP Tabs
 * and Product Tabs.
 *
 * @since      3.2.0
 * @package    ShapedPlugin\SmartTabsFree\Shared
 */

namespace ShapedPlugin\SmartTabsFree\Shared;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class SettingsHelper.
 *
 * @package ShapedPlugin\SmartTabsFree\Shared
 */
class SettingsHelper {
	/**
	 * General Tab Settings.
	 *
	 * @var array
	 */
	protected static $general_tab_settings = null;

	/**
	 * Get General Tab Settings.
	 *
	 * @param string $key The setting key to retrieve.
	 * @param mixed  $option_default The default value to return if the key does not exist.
	 * @return mixed The value of the setting if it exists, otherwise the default value.
	 */
	public static function get_general_setting( $key, $option_default = null ) {
		if ( null === self::$general_tab_settings ) {
			$settings                   = get_option( 'sp-tab__settings', array() );
			self::$general_tab_settings = is_array( $settings ) ? $settings : array();
		}

		return self::$general_tab_settings[ $key ] ?? $option_default;
	}
}
