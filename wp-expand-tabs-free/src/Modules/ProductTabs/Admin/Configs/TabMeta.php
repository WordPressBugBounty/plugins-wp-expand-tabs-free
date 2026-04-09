<?php
/**
 * The Tabs Meta section.
 *
 * @link       https://shapedplugin.com/
 * @since      3.2.0
 *
 * @package    ShapedPlugin\SmartTabsFree
 * @subpackage Modules\WPTabs\Admin\Configs
 */

namespace ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin\Configs;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

use ShapedPlugin\SmartTabsFree\Core\Constants;
use ShapedPlugin\SmartTabsFree\Lib\CSFramework\Classes\SP_CSFramework;

/**
 * Class TabMeta
 */
class TabMeta {
	/**
	 * Register Tab General, Display and Typography sections.
	 *
	 * @param string $prefix The prefix for the metabox.
	 */
	public static function tabs_metabox( $prefix ) {
		/**
		 * Content source settings metabox.
		 */
		SP_CSFramework::createMetabox(
			$prefix,
			array(
				'title'     => __( 'Smart Tabs', 'wp-expand-tabs-free' ),
				'post_type' => 'sp_products_tabs',
				'context'   => 'normal',
			)
		);

		// Tabs general section.
		self::section( $prefix );
	}

	/**
	 * Register Tabs Meta section.
	 *
	 * @param string $prefix The prefix for the section.
	 */
	public static function section( $prefix ) {
		SP_CSFramework::createSection(
			$prefix,
			array(
				'fields' => array(
					array(
						'id'     => 'product_tab_title_section',
						'type'   => 'fieldset',
						'class'  => 'tabs_title_section',
						'title'  => '',
						'fields' => array(
							array(
								'id'         => 'tab_title',
								'type'       => 'text',
								'class'      => 'tabs_content-title',
								'wrap_class' => 'sp-tab__content_source',
								'title'      => __( 'Tab Name', 'wp-expand-tabs-free' ),
							),
						),
					),
					array(
						'id'          => 'tabs_content_type',
						'class'       => 'sp_product_tabs_content_type full-width pro-preset-style',
						'type'        => 'image_select',
						'title'       => __( 'Tab Content Type', 'wp-expand-tabs-free' ),
						'title_video' => '<div class="wptabspro-img-tag"><video autoplay loop muted playsinline><source src="' . plugin_dir_url( __DIR__ ) . 'partials/models/assets/images/tab-content-type.webm" type="video/webm"></video></div><div class="wcgs-info-label">' . esc_html__( 'Access More Powerful Tab Content Types in', 'wp-expand-tabs-free' ) . '
                        <a href="' . esc_url( Constants::PRO_LINK ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Pro!', 'wp-expand-tabs-free' ) . '</a></div>',
						'options'     => array(
							'content'   => array(
								'icon'        => 'icon-content',
								'class'       => 'free-feature',
								'option_name' => __( 'Content', 'wp-expand-tabs-free' ),
							),
							'products'  => array(
								'icon'        => 'icon-products',
								'class'       => 'free-feature',
								'option_name' => __( 'Products', 'wp-expand-tabs-free' ),
							),
							'pro-types' => array(
								'image' => Constants::product_tabs_url() . 'Admin/Assets/img/pro-presets/woo-tab-types.svg',
								'class' => 'pro-feature',
							),
						),
						'default'     => 'content',
					),
					array(
						'id'         => 'tabs_content_description',
						'type'       => 'wp_editor',
						'class'      => 'full-width',
						'wrap_class' => 'sp-tab__content_source',
						'title'      => __( 'Description', 'wp-expand-tabs-free' ),
						'height'     => '150px',
						'sanitize'   => false,
						'dependency' => array( 'tabs_content_type', 'any', 'content,products' ),
					),

					/**
					 * Products Section.
					 */
					array(
						'id'         => 'filter_products',
						'type'       => 'select',
						'title'      => __( 'Filter Products', 'wp-expand-tabs-free' ),
						'class'      => 'chosen sptpro-tabs-pro-select',
						'options'    => array(
							'latest'    => __( 'Latest', 'wp-expand-tabs-free' ),
							'related'   => __( 'Related', 'wp-expand-tabs-free' ),
							'specific'  => __( 'Specific Products (Pro)', 'wp-expand-tabs-free' ),
							'on_sale'   => __( 'On Sale (Pro)', 'wp-expand-tabs-free' ),
							'best_sell' => __( 'Best Selling (Pro)', 'wp-expand-tabs-free' ),
						),
						'desc'       => sprintf(
							// translators: %s is the link to the Woo Product Tabs generator page.
							__( 'Hide WooCommerce default related products %s.', 'wp-expand-tabs-free' ),
							'<a href="' . esc_url(
								admin_url( 'edit.php?post_type=sp_wp_tabs&page=tab_advanced' )
							) . '" target="_blank" rel="noopener noreferrer">' . __( 'here', 'wp-expand-tabs-free' ) . '</a>'
						),
						'default'    => 'latest',
						'dependency' => array( 'tabs_content_type', '==', 'products' ),
					),
					array(
						'id'         => 'products_order_by',
						'type'       => 'select',
						'title'      => __( 'Order By', 'wp-expand-tabs-free' ),
						'options'    => array(
							'ID'         => __( 'ID', 'wp-expand-tabs-free' ),
							'date'       => __( 'Date', 'wp-expand-tabs-free' ),
							'rand'       => __( 'Random', 'wp-expand-tabs-free' ),
							'title'      => __( 'Title', 'wp-expand-tabs-free' ),
							'modified'   => __( 'Modified', 'wp-expand-tabs-free' ),
							'menu_order' => __( 'Menu Order', 'wp-expand-tabs-free' ),
							'post__in'   => __( 'Drag & Drop', 'wp-expand-tabs-free' ),
						),
						'default'    => 'date',
						'dependency' => array( 'tabs_content_type', '==', 'products' ),
					),
					array(
						'id'         => 'products_order',
						'type'       => 'select',
						'title'      => __( 'Order', 'wp-expand-tabs-free' ),
						'options'    => array(
							'ASC'  => __( 'Ascending', 'wp-expand-tabs-free' ),
							'DESC' => __( 'Descending', 'wp-expand-tabs-free' ),
						),
						'default'    => 'DESC',
						'dependency' => array( 'tabs_content_type', '==', 'products' ),
					),
					array(
						'id'         => 'number_of_total_products',
						'type'       => 'spinner',
						'title'      => __( 'Limit', 'wp-expand-tabs-free' ),
						'title_help' => __( 'Number of total posts to display. Default value is 8.', 'wp-expand-tabs-free' ),
						'default'    => '8',
						'min'        => 1,
						'max'        => 1000,
						'dependency' => array( 'tabs_content_type', '==', 'products' ),
					),
					/**
					 * Tabs Visibility Settings.
					 */
					array(
						'id'         => 'tabs_show_in',
						'type'       => 'select',
						'class'      => 'chosen sptpro-tabs-pro-select',
						'title'      => __( 'Show Tab in', 'wp-expand-tabs-free' ),
						'options'    => array(
							'all_product'            => __( 'All Products', 'wp-expand-tabs-free' ),
							'brand'                  => __( 'Brands', 'wp-expand-tabs-free' ),
							'categories'             => __( 'Categories (Pro)', 'wp-expand-tabs-free' ),
							'product_tags'           => __( 'Tags (Pro)', 'wp-expand-tabs-free' ),
							'product_sku'            => __( 'SKU (Pro)', 'wp-expand-tabs-free' ),
							'specific_product'       => __( 'Specific Products (Pro)', 'wp-expand-tabs-free' ),
							'product_visibility'     => __( 'Product Visibility (Pro)', 'wp-expand-tabs-free' ),
							'product_shipping_class' => __( 'Product Shipping Classes (Pro)', 'wp-expand-tabs-free' ),
							'product_color'          => __( 'Product Colors (Pro)', 'wp-expand-tabs-free' ),
							'product_size'           => __( 'Product Sizes (Pro)', 'wp-expand-tabs-free' ),
						),
						'default'    => 'latest',
						'dependency' => array( 'tabs_content_type', 'any', 'content,products' ),
					),
					array(
						'id'          => 'tabs_show_by_brands',
						'type'        => 'select',
						'class'       => 'sp_tab_specific_posts',
						'title'       => __( 'Choose Brands', 'wp-expand-tabs-free' ),
						'options'     => 'brands_term',
						'chosen'      => true,
						'sortable'    => true,
						'multiple'    => true,
						// 'ajax'        => true,
						'placeholder' => __( 'Choose Brand(s)', 'wp-expand-tabs-free' ),
						'attributes'  => array(
							'style' => 'min-width: 250px;',
						),
						'dependency'  => array( 'tabs_show_in', '==', 'brand' ),
					),
					array(
						'id'         => 'tabs_exclude',
						'type'       => 'checkbox',
						'wrap_class' => 'sp-tab__content_source',
						'title'      => __( 'Exclude', 'wp-expand-tabs-free' ),
						'default'    => false,
						'dependency' => array( 'tabs_content_type', 'any', 'content,products' ),
					),
					array(
						'id'          => 'exclude_specific',
						'type'        => 'select',
						'title'       => __( 'Specific', 'wp-expand-tabs-free' ),
						'class'       => 'sp_tab_specific_posts',
						'options'     => 'posts',
						'query_args'  => array(
							'post_type'      => 'product',
							'orderby'        => 'post_date',
							'order'          => 'DESC',
							'numberposts'    => 100,
							'posts_per_page' => 100,
							'cache_results'  => false,
							'no_found_rows'  => true,
						),
						'ajax'        => true,
						'chosen'      => true,
						'sortable'    => true,
						'multiple'    => true,
						'placeholder' => __( 'Choose Product(s)', 'wp-expand-tabs-free' ),
						'dependency'  => array( 'tabs_content_type|tabs_exclude', 'any|==', 'content,products|true' ),
					),
					array(
						'id'         => 'override_tab',
						'type'       => 'checkbox',
						'title'      => __( 'Override this tab in each product', 'wp-expand-tabs-free' ),
						'default'    => true,
						'title_help' => sprintf(
							/* translators: 1: start div tag, 2: close div tag, 3: start div tag, 4: close div tag. */
							'<div class="wptabspro-info-label">%s</div><div class="wptabspro-short-content">%s</div>',
							__( 'Override this tab in each product', 'wp-expand-tabs-free' ),
							__( 'Enable this option to override the default behavior and customize this tab’s visibility for specific products.', 'wp-expand-tabs-free' )
						),
						'dependency' => array( 'tabs_content_type', 'any', 'content' ),
					),
					array(
						'id'      => 'product_tabs_notice',
						'type'    => 'notice',
						'style'   => 'normal',
						'class'   => 'smart-tabs-notice',
						'content' => sprintf(
							/* translators: 1: start link and strong tag, 2: close link and strong tag, 3: start strong tag, 4: close strong tag. 5: start link and strong tag, 6: close link and strong tag. */
							__( 'Add unlimited custom tabs with any content to boost engagement and sales — %1$sUpgrade to Pro!%2$s', 'wp-expand-tabs-free' ),
							'<a class="wcgs-open-live-demo" href="' . esc_url( Constants::PRO_LINK ) . '" target="_blank"><strong>',
							'</strong></a>'
						),
					),
					// ), // End of Content Tabs.
				), // End of fields array.
			)
		); // End of tabs settings.
	}
}
