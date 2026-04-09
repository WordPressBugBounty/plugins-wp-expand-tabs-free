<?php
/**
 * The WooCommerce Product Tabs module class.
 *
 * @since 3.2.1
 *
 * @package ShapedPlugin\SmartTabsFree
 * @subpackage Modules\WPTabs
 */

namespace ShapedPlugin\SmartTabsFree\Modules\ProductTabs;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use ShapedPlugin\SmartTabsFree\Core\Loader;
use ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin\Admin;
use ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Frontend\Frontend;
use ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Frontend\DeprecatedTabGroup;
use ShapedPlugin\SmartTabsFree\Shared\SettingsHelper;
use ShapedPlugin\SmartTabsFree\Core\Constants;
/**
 * ProductTabs class for the Product Tabs module.
 *
 * @since 3.2.1
 */
class ProductTabs {

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
	 * Initialize and register Product tabs Admin and Frontend components.
	 *
	 * @return void
	 */
	public function register() {
		$is_product_tabs_enabled = SettingsHelper::get_general_setting( 'enable_product_tabs', true );

		// Deprecated product tabs group (Frontend).
		$first_version         = get_option( 'wp_tabs_first_version' );
		$is_tab_group_enabled  = SettingsHelper::get_general_setting( 'sptpro_woo_tab', false );
		$is_version_compatible = version_compare( Constants::first_installed_version(), '3.0.0', '<' );

		if ( $is_product_tabs_enabled ) {
			// Product tabs admin area.
			if ( is_admin() ) {
				( new Admin( $this->loader ) )->register();
			}

			// Product tabs frontend area.
			( new Frontend( $this->loader ) )->register();
		}

		// Register the deprecated tab group functionality for backward compatibility.
		if ( $is_tab_group_enabled && ( $is_version_compatible || version_compare( $first_version, '3.0.0', '<' ) ) ) {
			( new DeprecatedTabGroup() )->register( $this->loader );
		}
	}
}
