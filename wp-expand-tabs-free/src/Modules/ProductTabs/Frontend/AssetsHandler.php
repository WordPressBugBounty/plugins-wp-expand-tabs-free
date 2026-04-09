<?php
/**
 * The file that handles registering and enqueuing assets for the Product Tabs module.
 *
 * @link http://shapedplugin.com
 * @since 3.2.1
 *
 * @package    SmartTabsFree\Modules\ProductTabs
 * @subpackage Frontend
 */

namespace ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Frontend;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use ShapedPlugin\SmartTabsFree\Core\Constants;
use ShapedPlugin\SmartTabsFree\Modules\ProductTabs\TabHelper;
use ShapedPlugin\SmartTabsFree\Shared\SettingsHelper;
use ShapedPlugin\SmartTabsFree\Shared\DependencyChecker;
use ShapedPlugin\SmartTabsFree\Modules\WPTabs\Frontend\Helper;
/**
 * Class AssetsHandler.
 */
class AssetsHandler {
	/**
	 * Suffix for minified files.
	 *
	 * @var string
	 */
	private $min;

	/**
	 * Registerer of product tabs assets.
	 *
	 * @param Loader $loader The loader that's responsible for maintaining and registering all hooks.
	 * @var string
	 */
	public function register( $loader ) {
		$this->min = ( apply_filters( 'smart_tabs_enqueue_dev_mode', false ) || WP_DEBUG ) ? '' : '.min';

		$loader->add_action( 'wp_enqueue_scripts', $this, 'enqueue_frontend_assets' );
		$loader->add_action( 'elementor/frontend/after_enqueue_styles', $this, 'enqueue_frontend_assets' );
		$loader->add_action( 'admin_enqueue_scripts', $this, 'enqueue_admin_assets' );
	}

	/**
	 * Enqueue product tabs frontend assets.
	 */
	public function enqueue_frontend_assets() {
		$is_elementor_preview = class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->preview->is_preview_mode();

		if ( ! is_product() && ! DependencyChecker::is_divi_builder_active() && ! $is_elementor_preview ) {
			return;
		}

		$tabs_query = new \WP_Query(
			array(
				'post_type'      => 'sp_products_tabs',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);

		$count_total_tabs = $tabs_query->found_posts;

		if ( $count_total_tabs <= 3 && version_compare( Constants::first_installed_version(), '3.0.0', '<' ) ) {
			return;
		}

		wp_enqueue_style( 'sptpro-product-tabs-style', Constants::product_tabs_url() . 'Frontend/Assets/css/tabs-style' . $this->min . '.css', array(), Constants::VERSION, 'all' );
		wp_enqueue_script( 'sp-product-tabs-script', Constants::product_tabs_url() . 'Frontend/Assets/js/tabs-type' . $this->min . '.js', array(), Constants::VERSION, 'all' );

		$product_tabs_settings        = get_option( 'sptabs_product_tabs_settings' );
		$product_tabs_activator_event = $product_tabs_settings['product_tabs_activator_event'] ?? 'tabs-activator-event-click';
		$product_tabs_layout          = $product_tabs_settings['product_tabs_layout'] ?? 'horizontal';
		$preloader                    = $sp_woo_tabs_settings['sptpro_preloader'] ?? true;

		$set_small_screen_width            = $product_tabs_settings['sptpro_set_small_screen']['all'] ?? 480;
		$sptpro_tabs_on_small_screen       = $product_tabs_settings['sptpro_tabs_on_small_screen'] ?? 'full_width';
		$small_screen_expand_collapse_icon = filter_var(
			$product_tabs_settings['sptpro_expand_and_collapse_icon'] ?? false,
			FILTER_VALIDATE_BOOLEAN
		);

		$advanced_settings      = get_option( 'sp_products_tabs_advanced' );
		$skip_product_tab_style = $advanced_settings['skip_product_tab_style'] ?? false;
		// If "Skip Product Tab Style" is enabled in advanced settings, disable the tab layout style of the plugin
		// so that no accordion or tab-specific scripts/styles are applied on the frontend.
		if ( $skip_product_tab_style ) {
			$product_tabs_layout = '';
		}

		wp_localize_script(
			'sp-product-tabs-script',
			'sp_tabs_ajax',
			array(
				'ajax_url'                   => admin_url( 'admin-ajax.php' ),
				'activator_event'            => $product_tabs_activator_event,
				'tabs_layout'                => $product_tabs_layout,
				'tabs_preloader'             => $preloader,
				'set_min_small_screen_width' => $set_small_screen_width,
				'small_screen_tab_layout'    => $sptpro_tabs_on_small_screen,
				'expand_collapse_icon'       => $small_screen_expand_collapse_icon,
			)
		);

		if ( TabHelper::is_skip_product_style() ) {
			return;
		}

		$dynamic_style = '';
		// Load common configuration variables and settings that define the product tabs appearance.
		include Constants::product_tabs_dynamic_style() . '/woo-tabs-config.php';
		$is_divi_theme = 'divi' === strtolower( wp_get_theme()->get( 'Name' ) );

		// Check if the Divi Builder (visual or classic) has been used for this specific product page.
		if ( $is_divi_theme && DependencyChecker::is_divi_builder_active() ) {
			// Divi-specific compatibility styles to correctly target Divi's generated HTML markup.
			include Constants::product_tabs_dynamic_style() . '/woo-tabs-divi-comatibility-styles.php';
		} else {
			// default styling designed for standard WooCommerce tab markup.
			include Constants::product_tabs_dynamic_style() . '/woo-tabs-style.php';
		}

		$load_dynamic_styles = Helper::minify_output( $dynamic_style );
		wp_add_inline_style( 'sptpro-product-tabs-style', $load_dynamic_styles );

		wp_enqueue_script( 'sptpro-tabs-type' );
	}

	/**
	 * Enqueue product admin tabs assets.
	 */
	public function enqueue_admin_assets() {
		$current_screen = get_current_screen();
		$_post_type     = $current_screen->post_type;
		// Check if we are on the product tabs admin screen, otherwise return early.
		$allowed_screens = array( 'sp_products_tabs', 'product' );
		// If we are in the admin area, check if the current screen is one of the allowed screens otherwise return early.
		if ( ! is_admin() || ! in_array( $_post_type, $allowed_screens, true ) ) {
			return;
		}

		wp_enqueue_style( 'sptpro-product-tabs-style', Constants::product_tabs_url() . 'Admin/Assets/css/product-admin-tabs' . $this->min . '.css', array(), Constants::VERSION, 'all' );
		wp_enqueue_script( 'sptpro-product-tabs-script', Constants::product_tabs_url() . 'Admin/Assets/js/product-admin-tabs' . $this->min . '.js', array(), Constants::VERSION, true );
		wp_enqueue_script( 'jquery-ui-sortable' );
	}

	/**
	 * Prepare merged + validated settings for passing to JS.
	 */
	private function prepare_settings(): array {
		$general  = get_option( 'sptabs_product_tabs_settings', array() );
		$advanced = get_option( 'sp_products_tabs_advanced', array() );

		$tabs_layout = $general['product_tabs_layout'] ?? 'horizontal';

		// Normalize tabs→accordion fallback.
		if ( in_array( $tabs_layout, array( 'tabs-accordion', 'accordion-below' ), true ) ) {
			$tabs_layout = 'tabs-accordion';
		}

		// Advanced override: skip plugin styling.
		if ( ! empty( $advanced['skip_product_tab_style'] ) ) {
			$tabs_layout = '';
		}

		return array(
			'ajax_url'                   => admin_url( 'admin-ajax.php' ),
			'activator_event'            => $general['product_tabs_activator_event'] ?? 'tabs-activator-event-click',
			'initial_open_tab'           => $general['sptpro_tab_opened'] ?? '1',
			'activate_tab_from_hash'     => ( $general['tab_hash_anchor_at_url'] ?? false ),
			'accordion_multiple_open'    => ( $general['sptpro_multiple_open'] ?? false ),
			'tabs_layout'                => $tabs_layout,
			'toggle_icon_type'           => $general['sptpro_toggle_icon_style'] ?? 'plus-minus-light',
			'toggle_icon'                => (bool) ( $general['sptpro_toggle_icon'] ?? true ),
			'tabs_preloader'             => ( $general['sptpro_preloader'] ?? true ),
			'set_min_small_screen_width' => $general['sptpro_set_small_screen']['all'] ?? 480,
			'small_screen_tab_layout'    => $general['sptpro_tabs_on_small_screen'] ?? 'accordion_mode',
			'expand_collapse_icon'       => ( $general['sptpro_expand_and_collapse_icon'] ?? true ),
		);
	}
}
