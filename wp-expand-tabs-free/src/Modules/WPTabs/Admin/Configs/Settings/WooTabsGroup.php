<?php
/**
 * The Woo Tabs Group section.
 *
 * @link       https://shapedplugin.com/
 * @since      3.2.0
 *
 * @package    ShapedPlugin\SmartTabsFree
 * @subpackage Modules\WPTabs\Admin\Configs\Settings
 */

namespace ShapedPlugin\SmartTabsFree\Modules\WPTabs\Admin\Configs\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

use ShapedPlugin\SmartTabsFree\Lib\CSFramework\Classes\SP_CSFramework;

/**
 * Class WooTabsGroup
 */
class WooTabsGroup {
	/**
	 * Register Woo Tabs Group section.
	 *
	 * @param string $prefix The prefix for the metabox.
	 */
	public static function section( $prefix ) {
		SP_CSFramework::createSection(
			$prefix,
			array(
				'title'  => __( 'Woo Tab Group', 'wp-expand-tabs-free' ),
				'icon'   => 'fa sp-tab-icon-woo-tabs-group',
				'fields' => array(
					array(
						'type'    => 'submessage',
						'content' => sprintf(
							// translators: %s is the link to the Woo Product Tabs generator page.
							__(
								'⚠️ Note: We\'ve introduced a new and powerful %s feature that gives you full control to create and 	customize individual tabs per product — with advanced options, flexible layouts, and complete content freedom.
							Your existing Woo Tabs Group settings will continue to work as before. However, we recommend switching to the new Woo Product Tabs style to take advantage of all the new capabilities.',
								'wp-expand-tabs-free'
							),
							'<a href="' . esc_url( admin_url( 'edit.php?post_type=sp_products_tabs' ) ) . '" target="_blank" rel="noopener noreferrer">' . __( 'Woo Product Tabs', 'wp-expand-tabs-free' ) . '</a>'
						),
						'style'   => 'warning',
					),
					array(
						'id'         => 'sptpro_woo_tab',
						'type'       => 'switcher',
						'title'      => __( 'WooCommerce Additional Tabs', 'wp-expand-tabs-free' ),
						'default'    => false,
						'text_on'    => __( 'Enabled', 'wp-expand-tabs-free' ),
						'text_off'   => __( 'Disabled', 'wp-expand-tabs-free' ),
						'text_width' => 100,
					),
					array(
						'id'         => 'sptpro_woo_set_tab',
						'type'       => 'group',
						'title'      => 'Additional Tabs',
						'fields'     => array(
							array(
								'id'      => 'sptpro_display_tab_for',
								'type'    => 'select',
								'title'   => __( 'Display Tabs on', 'wp-expand-tabs-free' ),
								'options' => array(
									'all'               => __( 'All Products', 'wp-expand-tabs-free' ),
									'taxonomy'          => __( 'Category', 'wp-expand-tabs-free' ),
									'Specific_Products' => __( 'Specific Products', 'wp-expand-tabs-free' ),
								),
								'default' => 'latest',
								'class'   => 'chosen',
							),
							array(
								'id'          => 'sptpro_specific_product',
								'type'        => 'select',
								'title'       => __( 'Specific Product(s)', 'wp-expand-tabs-free' ),
								'class'       => 'only-for-pro',
								'options'     => 'posts',
								'query_args'  => array(
									'post_type'      => 'product',
									'orderby'        => 'post_date',
									'order'          => 'DESC',
									'numberposts'    => 3000,
									'posts_per_page' => 3000,
									'cache_results'  => false,
									'no_found_rows'  => true,
								),
								'chosen'      => true,
								'sortable'    => true,
								'multiple'    => true,
								'placeholder' => __( 'Choose Product', 'wp-expand-tabs-free' ),
								'dependency'  => array( 'sptpro_display_tab_for', '==', 'Specific_Products' ),
							),
							array(
								'id'          => 'sptpro_taxonomy_terms',
								'type'        => 'select',
								'class'       => 'only-for-pro',
								'title'       => __( 'Category Term(s)', 'wp-expand-tabs-free' ),
								'options'     => 'sp_wp_tabs_subcategory',
								'chosen'      => true,
								'sortable'    => true,
								'multiple'    => true,
								'placeholder' => __( 'Choose term(s)', 'wp-expand-tabs-free' ),
								'dependency'  => array( 'sptpro_display_tab_for', '==', 'taxonomy' ),
								'attributes'  => array(
									'style' => 'min-width: 250px;',
								),
							),
							array(
								'id'          => 'sptpro_woo_tab_shortcode',
								'type'        => 'select',
								'class'       => 'sptpro_woo_tab_shortcode',
								'title'       => __( 'Select Tabs Group(s)', 'wp-expand-tabs-free' ),
								'options'     => 'shortcode_list',
								'query_args'  => array(
									'post_type'      => 'sp_wp_tabs',
									'orderby'        => 'post_date',
									'order'          => 'DESC',
									'posts_per_page' => 100,
								),
								'placeholder' => __( 'Select tabs group(s)', 'wp-expand-tabs-free' ),
								'chosen'      => true,
								'sortable'    => true,
								'multiple'    => true,
								'dependency'  => array( 'sptpro_display_tab_for', '==', 'all' ),
							),
							array(
								'id'      => 'sptpro_woo_tab_label_priority',
								'type'    => 'spinner',
								'class'   => 'sptpro_woo_tab_label_priority only_pro_spinner',
								'title'   => __( 'Tabs Priority', 'wp-expand-tabs-free' ),
								'default' => '50',
							),
						),
						'dependency' => array( 'sptpro_woo_tab', '==', true ),
						'attributes' => array(
							'style' => 'max-width: 250px;',
						),
					),
				),
			)
		);
	}
}
