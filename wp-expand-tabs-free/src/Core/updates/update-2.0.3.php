<?php
/**
 * Update version.
 *
 * @package    WP_Tabs
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use ShapedPlugin\SmartTabsFree\Core\Constants;

update_option( 'wp_tabs_version', Constants::VERSION );
update_option( 'wp_tabs_db_version', Constants::VERSION );
