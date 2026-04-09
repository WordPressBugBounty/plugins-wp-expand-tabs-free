<?php
/**
 * The class that defines the core plugin class
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @link       https://shapedplugin.com/
 * @since      2.0.0
 *
 * @package    ShapedPlugin\SmartTabsFree
 * @subpackage Core
 */

namespace ShapedPlugin\SmartTabsFree\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use ShapedPlugin\SmartTabsFree\Core\Constants;
use ShapedPlugin\SmartTabsFree\Modules\WPTabs\WPTabs;
use ShapedPlugin\SmartTabsFree\Core\Tools\ImportExport;
use ShapedPlugin\SmartTabsFree\Core\Notices\Review;
use ShapedPlugin\SmartTabsFree\Core\AdminMenus\AdminMenus;
use ShapedPlugin\SmartTabsFree\Integration\GutenbergBlock\GutenbergBlock;
use ShapedPlugin\SmartTabsFree\Integration\ElementorAddons\Elementor_Addon;
use ShapedPlugin\SmartTabsFree\Core\Notices\ShapedPlugin_Offer_Banner;
use ShapedPlugin\SmartTabsFree\Shared\DependencyChecker;

use ShapedPlugin\SmartTabsFree\Shared\SharedAssetsLoader;
use ShapedPlugin\SmartTabsFree\Modules\ProductTabs\ProductTabs;

/**
 * Class Plugin.
 */
class Plugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    2.0.0
	 */
	public function __construct() {
		$this->load_dependencies();
		$this->register_admin_services();
		$this->load_modules();
		$this->load_shared_assets();
	}

	/**
	 * Load all modules.
	 *
	 * @since    3.0.1
	 * @access   private
	 */
	private function load_modules() {
		if ( DependencyChecker::is_woocommerce_active() ) {
			// Instantiate the ProductTabs module and register its hooks.
			( new ProductTabs( $this->loader ) )->register();
		}

		// Instantiate the WPTabs module and register its hooks.
		( new WPTabs() )->register( $this->loader );
	}

	/**
	 * Register all core admin services and attach their hooks
	 * to the plugin's loader.
	 *
	 * This includes:
	 * - License management
	 * - Database updates
	 * - Admin menu pages
	 * - Import/Export functionality
	 * - Activation redirect handling
	 * - Gutenberg block registration (WP 5.3+)
	 * - Elementor addon registration
	 *
	 * @since 3.2.1
	 * @return void
	 */
	private function register_admin_services() {

		// Runs during plugin updates.
		new DBUpdater();

		// Export Import.
		( new ImportExport( Constants::ITEM_SLUG, Constants::VERSION ) )->register_hooks( $this->loader );

		// Plugin admin menus.
		( new AdminMenus( $this->loader ) );

		( new Review() )->register_hooks( $this->loader );

		if ( ! defined( 'SHAPEDPLIUGIN_OFFER_BANNER_LOADED' ) ) {
			define( 'SHAPEDPLIUGIN_OFFER_BANNER_LOADED', true );
			/**
			 * The file is responsible for generating admin offer banner.
			 */
			ShapedPlugin_Offer_Banner::instance();
		}

		/**
		 * Gutenberg block.
		 */
		if ( version_compare( $GLOBALS['wp_version'], '5.3', '>=' ) ) {
			new GutenbergBlock();
		}

		/**
		* ElementOr shortcode block.
		*/
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		if ( ( is_plugin_active( 'elementor/elementor.php' ) || is_plugin_active_for_network( 'elementor/elementor.php' ) ) ) {
			new Elementor_Addon();
		}
	}

	/**
	 * Load shared assets used across multiple modules.
	 *
	 * @since    3.2.1
	 * @access   private
	 */
	private function load_shared_assets() {
		$shared_assets = new SharedAssetsLoader();
		$shared_assets->register( $this->loader );
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - WP_Tabs_Loader. Orchestrates the hooks of the plugin.
	 * - WP_Tabs_i18n. Defines internationalization functionality.
	 * - WP_Tabs_Admin. Defines all hooks for the admin area.
	 * - WP_Tabs_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		$this->loader = new Loader();
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    2.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     2.0.0
	 * @return    WP_Tabs_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}
}
