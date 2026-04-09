<?php
/**
 * WooCommerce Product Admin Integration for Product Tabs.
 *
 * @since     3.2.1
 * @package   ShapedPlugin\SmartTabsFree
 * @subpackage Modules\ProductTabs\Admin\WooCommerce
 */

namespace ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin\WooCommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use ShapedPlugin\SmartTabsFree\Core\Constants;

/**
 * WooCommerce Product Admin Integration for Product Tabs.
 */
class WooIntegration {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that
	 *
	 * @var $loader
	 */
	protected $loader;

	/**
	 * Initialize the Admin Table Hooks for Product Tabs module.
	 *
	 * @param object $loader The admin loader object.
	 */
	public function __construct( $loader ) {
		$this->loader = $loader;
	}

	/**
	 * Register all hooks related to Tab Navigation in the admin area.
	 *
	 * @return void
	 */
	public function register() {
		$this->loader->add_filter( 'woocommerce_product_data_tabs', $this, 'add_custom_tabs' );
		$this->loader->add_action( 'woocommerce_product_data_panels', $this, 'render_panel' );
	}

	/**
	 * Add custom tab for WooCommerce product tab in edit product page.
	 *
	 * @param array $tabs Existing WooCommerce product tabs.
	 * @return array Modified WooCommerce product tabs.
	 */
	public function add_custom_tabs( array $tabs ): array {
		$tabs['sp_products_table'] = array(
			'label'    => esc_html__( 'Product Tabs', 'wp-expand-tabs-free' ),
			'target'   => 'sp_product_tabs_panel', // This will be the ID of the div we create later.
			'class'    => array(),
			'priority' => 80, // Adjust position among tabs.
		);

		return $tabs;
	}

	/**
	 * Render the WooCommerce product edit page custom tab panel.
	 */
	public function render_panel(): void {
		include Constants::src_path() . 'Modules/ProductTabs/Admin/Configs/product-tabs-panel.php';
	}
}
