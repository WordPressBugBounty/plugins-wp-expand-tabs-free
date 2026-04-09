<?php
/**
 * Legacy global constants for backward compatibility.
 *
 * @since 3.2.1
 *
 * @package    ShapedPlugin\SmartTabsFree
 * @subpackage Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'WP_TABS_PRO_FILE' ) ) {
	define( 'WP_TABS_PRO_FILE', __FILE__ );
}

if ( ! defined( 'WP_TABS_NAME' ) ) {
	define( 'WP_TABS_NAME', 'Smart Tabs' );
}

if ( ! defined( 'WP_TABS_SLUG' ) ) {
	define( 'WP_TABS_SLUG', 'wp-expand-tabs-free' );
}

if ( ! defined( 'WP_TABS_FIRST_VERSION' ) ) {
	define( 'WP_TABS_FIRST_VERSION', get_option( 'wp_tabs_first_version' ) );
}
