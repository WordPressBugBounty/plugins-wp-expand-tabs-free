<?php
/**
 * The file that defines the Smart Tabs Woo Feature
 *
 * @link http://shapedplugin.com
 * @since 2.0.2
 *
 * @package    WP_Tabs_Pro
 * @subpackage WP_Tabs_Pro/includes
 */

namespace ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Frontend;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use ShapedPlugin\SmartTabsFree\Shared\DependencyChecker;
use ShapedPlugin\SmartTabsFree\Modules\ProductTabs\TabHelper;
use ShapedPlugin\SmartTabsFree\Shared\SettingsHelper;
use ShapedPlugin\SmartTabsFree\Shared\TabContentHelper;
use ShapedPlugin\SmartTabsFree\Shared\License;
use ShapedPlugin\SmartTabsFree\Core\Loader;
use ShapedPlugin\SmartTabsFree\Core\Constants;

/**
 * Class Frontend
 */
class Frontend {
	/**
	 * The single instance of the class.
	 *
	 * @var self
	 * @since 2.0.2
	 */
	private static $instance;

	/**
	 * Loader instance.
	 *
	 * @var Loader
	 */
	protected $loader;

	/**
	 * Stores the advanced tab settings loaded from the WordPress options table.
	 *
	 * @var array
	 */
	protected static $advanced_tab_settings = array();

	/**
	 * Flag indicating whether to skip loading the default product tab styles.
	 *
	 * @var bool
	 */
	public static $is_skip_product_style = false;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $minify scripts.
	 */
	private $min;

	/**
	 * Holds tab post IDs mapped by tab keys.
	 *
	 * @var array
	 */
	private $tab_post_ids = array();

	/**
	 * Holds the base class object.
	 *
	 * @since 2.0.2
	 *
	 * @var object
	 */
	public $base;

	/**
	 * WooCommerce product tab construct function.
	 *
	 * @param Loader $loader Loader instance.
	 */
	public function __construct( Loader $loader ) {
		$this->loader = $loader;
	}

	/**
	 * Registerer of product tabs assets.
	 *
	 * @var string
	 */
	public function register() {
		// Add WooCommerce product tabs.
		$this->loader->add_filter( 'woocommerce_product_tabs', $this, 'woo_product_tabs', 10, 2 );

		// Check if the product tabs and styles are enabled.
		if ( ! TabHelper::is_skip_product_style() ) {
			add_filter( 'body_class', array( $this, 'sptpro_add_tabs_body_class' ) );
		}

		// Register product tabs frontend assets.
		( new AssetsHandler() )->register( $this->loader );
	}

	/**
	 * WooCommerce Product Tabs.
	 *
	 * @return array $tabs
	 * @param array $tabs Existing WooCommerce product tabs.
	 */
	public function woo_product_tabs( $tabs ) {
		$product_id = TabHelper::get_woo_product_id();

		if ( $product_id > 0 ) {
			$product = wc_get_product( $product_id );
		}
		if ( ! $product instanceof \WC_Product ) {
			return $tabs;
		}

		$tabs = $this->filter_hidden_default_tabs( $tabs, $product_id );

		// Assign menu_order priority for default tabs linked to sp_products_tabs.
		foreach ( $tabs as $slug => &$tab ) {
			if ( isset( $tab['custom_tab_id'] ) ) {
				$tab['priority'] = (int) $tab['menu_order'];
			}
		}
		unset( $tab ); // avoid reference issues.

		$product_brands = wp_get_post_terms( $product_id, 'product_brand', array( 'fields' => 'ids' ) );

		$args = array(
			'post_type'              => 'sp_products_tabs',
			'post_status'            => 'publish',
			'ignore_sticky_posts'    => true,
			'posts_per_page'         => -1,
			'orderby'                => 'menu_order',
			'order'                  => 'ASC',
			'no_found_rows'          => true, // No need for pagination (Performance optimization).
			'update_post_term_cache' => false, // Avoid loading terms for better performance.
		);

		$query      = new \WP_Query( $args );
		$smart_tabs = array();

		if ( $query->have_posts() ) {
			foreach ( $query->posts as $product_tab ) {
				$settings     = TabHelper::get_product_tab_meta_options( $product_tab->ID );
				$tabs_content = $settings['tabs_content_description'] ?? '';
				$tab_type     = isset( $settings['tabs_content_type'] ) ? $settings['tabs_content_type'] : 'content';

				// Skip if product tab settings are empty or not an array.
				if ( empty( $settings ) || ! is_array( $settings ) ) {
					continue;
				}

				// Check if the tab is enabled globally (AJAX-saved meta).
				if ( ! TabHelper::is_product_tab_globally_enabled( $product_tab->ID ) ) {
					continue;
				}

				// Check Tabs visibility.
				if ( ! TabHelper::is_tab_visible_for_product( $product_id, $settings, $product_brands ) ) {
					continue;
				}

				// Tabs Name HTML.
				$tab_name_html = $this->get_tab_name_html( $settings );
				$tab_name      = $settings['product_tab_title_section']['tab_title'] ?? '';

				// No title -> skip tab.
				if ( empty( $tab_name ) ) {
					continue;
				}

				// Load settings/meta.
				$override          = get_post_meta( $product_id, "sp_tab_{$product_tab->ID}_override", true );
				$override_content  = 'yes' === $override ? get_post_meta( $product_id, "sp_tab_{$product_tab->ID}_content", true ) : '';
				$effective_content = 'yes' === $override ? $override_content : $tabs_content;

				// Skip tab if no content.
				if ( 'products' !== $tab_type && ! TabHelper::has_tab_content( $effective_content ) ) {
					continue;
				}

				$tab_key                        = 'sptpro_tab_' . $product_tab->ID;
				$this->tab_post_ids[ $tab_key ] = $product_tab->ID;
				$hide_tab_for_specific_product  = get_post_meta( $product_id, "sp_tab_{$product_tab->ID}_hide", true );

				if ( 'yes' === $hide_tab_for_specific_product ) {
					continue; // If the tab is hidden for this product, do not render it.
				}

				$smart_tabs[ $tab_key ] = array(
					'title'    => $tab_name_html,
					'priority' => (int) $product_tab->menu_order,
					'callback' => array( $this, 'sptpro_product_tabs_panel_content' ),
					'content'  => $product_tab->post_content,
				);
			}
			wp_reset_postdata();
		}

		// Merge default WooCommerce tabs with our custom smart tabs.
		$_smart_tabs = array_merge( $tabs, $smart_tabs );

		/**
		 * Sort all tabs by priority for display order
		 * Lower priority numbers appear first (e.g., priority 10 before priority 20)
		 * Tabs without priority default to 99 (appear last)
		 */
		uasort(
			$_smart_tabs,
			static function ( $tab_a, $tab_b ) {
				$priority_a = isset( $tab_a['priority'] ) ? absint( $tab_a['priority'] ) : 99;
				$priority_b = isset( $tab_b['priority'] ) ? absint( $tab_b['priority'] ) : 99;
				return $priority_a <=> $priority_b;
			}
		);

		return $_smart_tabs;
	}

	/**
	 * Filters out default WooCommerce product tabs based on per-product and global visibility settings.
	 *
	 * @param array $tabs       The existing WooCommerce product tabs.
	 * @param int   $product_id The current product ID.
	 * @return array Filtered tabs array.
	 */
	private function filter_hidden_default_tabs( array $tabs, int $product_id ) {
		$default_tab_slugs = array( 'description', 'additional_information', 'reviews' );

		foreach ( $default_tab_slugs as $tab_slug ) {
			$hide_meta_key         = "sp_wc_tab_{$tab_slug}_hide";
			$is_hidden_for_product = get_post_meta( $product_id, $hide_meta_key, true );

			// Hide tab if explicitly hidden for this product.
			if ( 'yes' === $is_hidden_for_product ) {
				unset( $tabs[ $tab_slug ] );
				continue;
			}

			// Hide tab if globally disabled via custom tab mapping.
			if ( ! TabHelper::is_default_tab_globally_enabled( $tab_slug ) ) {
				unset( $tabs[ $tab_slug ] );
				continue;
			}

			// If globally enabled, attach meta info (custom tab ID and menu order).
			$custom_tab_data = self::get_default_tab_mapping_data( $tab_slug );

			if ( $custom_tab_data ) {
				$tabs[ $tab_slug ]['custom_tab_id'] = $custom_tab_data['ID'];
				$tabs[ $tab_slug ]['menu_order']    = $custom_tab_data['menu_order'];
			}
		}

		return $this->validate_tabs( $tabs );
	}

	/**
	 * Append Product Custom Tabs repeater fields to the WooCommerce tabs array.
	 *
	 * @param int   $product_id The Product ID.
	 * @param array $tabs Existing tabs array.
	 * @param int   $start_priority Starting priority for custom tabs.
	 * @return array Tabs array with custom tabs added.
	 */
	public function append_product_custom_tabs( $product_id, $tabs, $start_priority = 100 ) {
		$custom_tabs = get_post_meta( $product_id, 'sp_product_per_custom_tab', true );

		if ( empty( $custom_tabs ) || ! is_array( $custom_tabs ) ) {
			return $tabs;
		}

		foreach ( $custom_tabs as $index => $tab ) {
			$tab_name    = $tab['tab_name'] ?? '';
			$tab_content = $tab['tab_content'] ?? '';

			// Skip empty tabs.
			if ( trim( $tab_name ) === '' || ! TabHelper::has_tab_content( $tab_content ) ) {
				continue;
			}

			$tab_slug = $tab_name ? sanitize_title( $tab_name ) : "custom_tab_{$index}";

			$tabs[ $tab_slug ] = array(
				'title'    => esc_html( $tab_name ),
				'priority' => $start_priority + $index, // ensure ordering after default tabs.
				'callback' => function () use ( $tab_content ) {
					echo '<div class="sp-tab__tab-content">';
					$this->sp_render_tab_content( $tab_content );
					echo '</div>';
				},
			);
		}

		return $tabs;
	}

	/**
	 * Retrieves the mapped custom tab post for a given default WooCommerce tab slug.
	 *
	 * @param string $tab_slug The WooCommerce default tab slug (e.g., 'description', 'reviews').
	 * @return array|null Returns an array with 'ID' and 'menu_order' if found, otherwise null.
	 */
	public static function get_default_tab_mapping_data( $tab_slug ) {
		$custom_tab_query = get_posts(
			array(
				'post_type'      => 'sp_products_tabs',
				'meta_key'       => '_wc_default_tab_slug',
				'meta_value'     => $tab_slug,
				'posts_per_page' => 1,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
				'post_status'    => 'publish',
			)
		);

		if ( empty( $custom_tab_query ) ) {
			return null;
		}

		return array(
			'ID'         => $custom_tab_query[0]->ID,
			'menu_order' => $custom_tab_query[0]->menu_order,
		);
	}

	/**
	 * Validates the tabs array to ensure each tab has a title and callback.
	 *
	 * @param array $tabs The array of tabs to validate.
	 * @return array The validated tabs array, with any invalid tabs removed.
	 * */
	private function validate_tabs( array $tabs ) {
		foreach ( $tabs as $key => $tab ) {
			if ( ! isset( $tab['title'], $tab['callback'] ) ) {
				unset( $tabs[ $key ] );
			}
		}
		return $tabs;
	}

	/**
	 * Build the HTML for the tab title, including icon or custom image.
	 *
	 * @param array $settings The tab's meta settings containing title and icon info.
	 *
	 * @return string HTML string for the tab's display title.
	 */
	private function get_tab_name_html( $settings ) {
		$title_section = $settings['product_tab_title_section'] ?? array();
		$tab_name      = $title_section['tab_title'] ?? '';

		return $tab_name;
	}

	/**
	 * WooCommerce Tab Content.
	 *
	 * @param array $key tab key.
	 * @param array $tab tab data.
	 * @return void
	 */
	public function sptpro_product_tabs_panel_content( $key, $tab ) {
		if ( empty( $this->tab_post_ids[ $key ] ) ) {
			echo esc_html__( 'Tab content unavailable.', 'wp-expand-tabs-free' );
			return;
		}

		$tab_id     = $this->tab_post_ids[ $key ];
		$_tabs_data = get_post_meta( $tab_id, 'sptpro_woo_product_tabs_settings', true );
		$tab_type   = isset( $_tabs_data['tabs_content_type'] ) ? $_tabs_data['tabs_content_type'] : 'content';

		// Get the current product ID.
		$product_id = TabHelper::get_woo_product_id();

		// Check if this tab is overridden for this product.
		$override_tab            = get_post_meta( $product_id, "sp_tab_{$tab_id}_override", true );
		$is_tab_override_enabled = $_tabs_data['override_tab'] ?? true;

		echo '<div id="sp-smart-tab-content-' . esc_attr( $tab_id ) . '" class="sp-tab__tab-content">';
		if ( 'yes' === $override_tab && $is_tab_override_enabled ) {
			// Render overridable tabs content.
			$this->render_overridable_tab_content( $product_id, $tab_id, $tab_type, $_tabs_data );
		} else {
			$tabs_content = $_tabs_data['tabs_content_description'] ?? '';
			// Render tab content based on type.
			$this->render_tab_content_by_type( $tab_type, $_tabs_data, $tabs_content );
		}
		echo '</div>';
	}

	/**
	 * Renders overridden tab content for a specific WooCommerce product.
	 *
	 * This method checks if a specific tab has custom (overridden) data for the current product.
	 * If so, it renders the appropriate content depending on the tab type—content, image, video, FAQ, or downloadable files.
	 *
	 * @param int    $product_id The ID of the WooCommerce product.
	 * @param int    $tab_id     The ID of the tab being rendered.
	 * @param string $tab_type   The type of the tab (e.g., 'content', 'image', 'video', 'faqs', 'download').
	 * @param array  $_tabs_data Additional tab configuration data passed from meta or tab settings.
	 *
	 * @return void Outputs the tab content directly.
	 */
	protected function render_overridable_tab_content( $product_id, $tab_id, $tab_type, $_tabs_data ) {
		// Overridable tab content rendering based on tab type.
		switch ( $tab_type ) {
			case 'content':
				$override_content = get_post_meta( $product_id, "sp_tab_{$tab_id}_content", true );
				$this->sp_render_tab_content( $override_content );
				break;

			default:
				echo esc_html__( 'Unsupported override tab type.', 'wp-expand-tabs-free' );
		}
	}

	/**
	 * Renders the tab content based on the tab type.
	 *
	 * @param string $tab_type The type of the tab (content, image, video, faqs, download, map, contact-form, products).
	 * @param array  $_tabs_data Array of tabs data.
	 * @param string $tabs_content The common content of the tabs.
	 */
	protected function render_tab_content_by_type( $tab_type, $_tabs_data, $tabs_content = '' ) {
		switch ( $tab_type ) {
			case 'content':
				// Display tabs description.
				$this->sp_render_tab_content( $tabs_content );
				break;
			case 'products':
				// Display tabs description.
				$this->sp_render_tab_content( $tabs_content );
				// Display tabs products.
				$this->sp_render_product_tabs_section( $_tabs_data );
				break;
			default:
				echo esc_html__( 'No content available.', 'wp-expand-tabs-free' );
				break;
		}
	}

	/**
	 * Outputs content for WC product tab if content exists.
	 *
	 * @access private
	 * @param string $content Tab Content.
	 *
	 * @return void Outputs HTML directly
	 */
	private function sp_render_tab_content( $content ) {
		if ( ! empty( $content ) ) {
			global $wp_embed;
			if ( apply_filters( 'sp_wc_tabs_content_wpautop_remove', true ) ) {
				$content = wpautop( trim( $content ) );
			}
			$content = $wp_embed->autoembed( $content );
			$content = do_shortcode( shortcode_unautop( $content ) );
			echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Renders the product tabs section based on provided settings.
	 *
	 * @param array $_tabs_data Array containing product tabs settings and data.
	 * @return void Outputs the product tabs section with products.
	 * @access private
	 */
	private function sp_render_product_tabs_section( $_tabs_data ) {
		// Product Settings.
		$filter_products       = $_tabs_data['filter_products'] ?? 'latest';
		$specific_products     = $_tabs_data['specific_products'] ?? '';
		$products_order_by     = $_tabs_data['products_order_by'] ?? '';
		$_total_products_limit = $_tabs_data['number_of_total_products'] ?? '';
		$products_order        = $_tabs_data['products_order'] ?? '';
		$_total_products       = ( empty( $_total_products_limit ) ? 8 : absint( $_total_products_limit ) );

		$product_ids = TabHelper::sp_get_tabbed_products(
			array(
				'query_type'      => $filter_products,
				'limit'           => $_total_products,
				'orderby'         => $products_order_by,
				'order'           => $products_order,
				'current_post_id' => get_the_ID(),
			),
			$specific_products,
			$_total_products
		);

		if ( $product_ids ) {
			$_column_value = 4;
			if ( function_exists( 'wc_get_loop_prop' ) ) {
				wc_set_loop_prop( 'columns', $_column_value );
			}
			// Display products.
			echo '<div id="sp-smart-product-tabs" class="woocommerce">';
			woocommerce_product_loop_start();

			global $post, $product;
			foreach ( $product_ids as $product_id ) {
				$post    = get_post( $product_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				$product = wc_get_product( $product_id ); // assign to global $product.

				setup_postdata( $post ); // setup postdata after assigning to $post.
				wc_get_template_part( 'content', 'product' );
			}
			wp_reset_postdata();
			woocommerce_product_loop_end();
			echo '</div>';
		} else {
			echo esc_html__( 'No products found.', 'wp-expand-tabs-free' );
		}
	}

	/**
	 * Add the main tabs class to the body for product tabs.
	 *
	 * @since 2.0.2
	 *
	 * @param array $classes The existing body classes.
	 * @return array
	 */
	public function sptpro_add_tabs_body_class( $classes ) {
		$is_elementor_preview = class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->preview->is_preview_mode();

		if ( is_product() || DependencyChecker::is_divi_builder_active() || $is_elementor_preview ) {
			// Add the main tabs class.
			$classes[] = 'sptpro-smart-tabs';
		}

		return $classes;
	}
}
