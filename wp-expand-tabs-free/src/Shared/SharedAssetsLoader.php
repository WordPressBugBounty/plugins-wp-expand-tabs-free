<?php
namespace ShapedPlugin\SmartTabsFree\Shared;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use ShapedPlugin\SmartTabsFree\Core\Constants;
// use ShapedPlugin\SmartTabs\Shared\SettingsHelper;

/**
 * Class SharedAssetsLoader
 *
 * Handles loading of shared assets (CSS/JS) used across different
 * Smart Tabs modules like WP Tabs and Product Tabs.
 *
 * @package ShapedPlugin\SmartTabs\Shared
 */
class SharedAssetsLoader {

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $minify scripts.
	 */
	private static $min;

	/**
	 * Initialize hooks.
	 *
	 * @param mixed $loader The loader instance.
	 */
	public function register( $loader ) {
		$loader->add_action( 'wp_enqueue_scripts', $this, 'register_frontend_assets' );
		$loader->add_action( 'admin_enqueue_scripts', $this, 'enqueue_admin_assets' );
		self::$min = ( apply_filters( 'smart_tabs_enqueue_dev_mode', false ) || WP_DEBUG ) ? '' : '.min';
	}

	/**
	 * Enqueue common frontend assets.
	 */
	public function register_frontend_assets() {
		// ---- Styles ----
		wp_register_style(
			'smart-tabs-shared-fontawesome-css',
			Constants::shared_assets() . '/font-awesome.min.css',
			array(),
			Constants::VERSION
		);

		wp_register_style(
			'smart-tabs-shared-fontello-css',
			Constants::shared_assets() . 'css/fontello' . self::$min . '.css',
			array(),
			Constants::VERSION
		);

		// Enqueue Shared CSS files.
		wp_enqueue_style( 'smart-tabs-shared-fontawesome-css' );
	}

	/**
	 * Enqueue shared admin assets.
	 */
	public function enqueue_admin_assets() {
		// ---- Admin Styles ----
		wp_enqueue_style(
			'smart-tabs-shared-fontawesome-css',
			Constants::shared_assets() . '/font-awesome.min.css',
			array(),
			Constants::VERSION
		);

		wp_enqueue_style(
			'smart-tabs-shared-style-css',
			Constants::shared_assets() . '/wp-tabs-admin' . self::$min . '.css',
			array(),
			Constants::VERSION
		);
	}
}
