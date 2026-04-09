<?php
/**
 * Admin Controller for Product Tabs module.
 *
 * Handles registration of admin-specific functionality such as:
 * - Custom Post Type setup
 * - Admin UI components (notices, popups, tabbed navigation etc.)
 * - WooCommerce Product Tabs metabox configuration
 * - Save hooks for Product Tabs
 * - WooCommerce Product-level integration
 *
 * @since 3.2.0
 *
 * @package ShapedPlugin\SmartTabsFree
 * @subpackage Modules\ProductTabs\Admin
 */

namespace ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use ShapedPlugin\SmartTabsFree\Core\Loader;
use ShapedPlugin\SmartTabsFree\Core\Constants;
use ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin\ProductTabsCPT;
use ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin\Configs\Metabox;

use ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin\UI\Popup;
use ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin\UI\Notices;
use ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin\UI\TabNavigation;
use ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin\UI\AdminTableHooks;

use ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin\SaveHooks;

use ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin\WooCommerce;
use ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin\Configs\Settings;
use ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin\Configs\TabMeta;

/**
 * Class Admin
 *
 * The central class that registers all admin-side functionality for the Product Tabs module.
 *
 * @since 3.2.1
 */
class Admin {

	/**
	 * The loader that registers hooks product tabs hooks.
	 *
	 * @var Loader
	 */
	protected $loader;

	/**
	 * Constructor.
	 *
	 * @param Loader $loader The loader instance responsible for managing hooks.
	 */
	public function __construct( Loader $loader ) {
		$this->loader = $loader;
	}

	/**
	 * Initialize and register all admin module components.
	 *
	 * @return void
	 */
	public function register() {
		// Register Custom Post Type (sp_products_tabs).
		$this->register_cpt();

		// Register Admin UI components (tabbed nav, popup, notices).
		$this->register_ui_components();

		// Register WooCommerce product edit page tabs integrations.
		$this->woocommerce_integrations();

		// Register metabox configurations (Product Tabs metabox UI).
		$this->register_configs();

		// Register Admin Support features (AJAX, permissions & necessary utility etc.).
		( new AdminSupport( $this->loader ) )->register();

		// Register Save Hooks for Product Tabs.
		( new SaveHooks( $this->loader ) )->register();
	}

	/**
	 * Register Product Tabs Custom Post Type and related hooks.
	 *
	 * @return void
	 */
	protected function register_cpt() {
		( new ProductTabsCPT( $this->loader ) )->register();
	}

	/**
	 * Register all Admin UI-related hooks and features.
	 *
	 * @return void
	 */
	protected function register_ui_components() {
		( new Popup() )->register( $this->loader );
		( new TabNavigation( $this->loader ) )->register();
		( new Notices( $this->loader ) )->register();
		( new AdminTableHooks( $this->loader ) )->register();
	}

	/**
	 * Register all admin tabs panel hooks of WC product edit page.
	 *
	 * @return void
	 */
	protected function woocommerce_integrations() {
		( new WooCommerce\WooIntegration( $this->loader ) )->register();
		( new WooCommerce\DefaultTabsSeeder() )->register( $this->loader );
	}

	/**
	 * Register Product Tabs Metabox configuration.
	 *
	 * @return void
	 */
	protected function register_configs() {
		$this->loader->add_action( 'after_setup_theme', $this, 'init_tabs_meta_configs' );
	}

	/**
	 * Register Product Tabs Metabox configuration.
	 *
	 * @return void
	 */
	public function init_tabs_meta_configs() {
		TabMeta::tabs_metabox( 'sptpro_woo_product_tabs_settings' ); // TODO: Replace 'settings' as it is a metabox.
		Settings::style_settings( 'sptabs_product_tabs_settings' );
		Settings::advanced_settings( 'sp_products_tabs_advanced' );
	}
}
