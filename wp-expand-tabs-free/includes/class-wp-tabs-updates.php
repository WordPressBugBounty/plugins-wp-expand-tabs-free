<?php
/**
 * Fired during plugin updates
 *
 * @link       https://shapedplugin.com/
 * @since      2.0.3
 *
 * @package    WP_Tabs
 * @subpackage WP_Tabs/includes
 */

// don't call the file directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fired during plugin updates.
 *
 * This class defines all code necessary to run during the plugin's updates.
 */
class WP_Tabs_Updates {

	/**
	 * DB updates that need to be run
	 *
	 * @var array
	 */
	private static $updates = array(
		'2.0.3'  => 'updates/update-2.0.3.php',
		'2.1.10' => 'updates/update-2.1.10.php',
		'2.2.0'  => 'updates/update-2.2.0.php',
		'2.2.10' => 'updates/update-2.2.10.php',
	);

	/**
	 * Binding all events
	 *
	 * @since 2.0.3
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'do_updates' ) );
	}

	/**
	 * Check if need any update
	 *
	 * @since 2.0.3
	 *
	 * @return boolean
	 */
	public function is_needs_update() {
		$installed_version = get_option( 'wp_tabs_version' );
		$first_version     = get_option( 'wp_tabs_first_version' );
		$activation_date   = get_option( 'wp_tabs_activation_date' );

		if ( false === $installed_version ) {
			update_option( 'wp_tabs_version', WP_TABS_VERSION );
			update_option( 'wp_tabs_db_version', WP_TABS_VERSION );
		}
		if ( false === $first_version ) {
			update_option( 'wp_tabs_first_version', WP_TABS_VERSION );
		}
		if ( false === $activation_date ) {
			update_option( 'wp_tabs_activation_date', current_time( 'timestamp' ) );
		}

		if ( version_compare( $installed_version, WP_TABS_VERSION, '<' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Do updates.
	 *
	 * @since 2.0.3
	 *
	 * @return void
	 */
	public function do_updates() {
		$this->perform_updates();
	}

	/**
	 * Perform all updates
	 *
	 * @since 2.0.3
	 *
	 * @return void
	 */
	public function perform_updates() {
		if ( ! $this->is_needs_update() ) {
			return;
		}

		$installed_version = get_option( 'wp_tabs_version' );

		foreach ( self::$updates as $version => $path ) {
			if ( version_compare( $installed_version, $version, '<' ) ) {
				include $path;
				update_option( 'wp_tabs_version', $version );
			}
		}

		update_option( 'wp_tabs_version', WP_TABS_VERSION );
	}
}
new WP_Tabs_Updates();
