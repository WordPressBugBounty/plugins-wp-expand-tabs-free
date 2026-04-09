<?php
/**
 * This class allows to create Advanced Settings section.
 *
 * @link       https://shapedplugin.com/
 * @since      3.2.1
 *
 * @package    ShapedPlugin\SmartTabsFree
 * @subpackage Modules\ProductTabs\Admin\Configs
 */

namespace ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin\Configs;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

use ShapedPlugin\SmartTabsFree\Core\Constants;
use ShapedPlugin\SmartTabsFree\Lib\CSFramework\Classes\SP_CSFramework;

/**
 * Advanced Settings section.
 *
 * @since 3.2.1
 */
class Advanced {
	/**
	 * Create Advanced Settings section.
	 *
	 * @param string $prefix Prefix.
	 *
	 * @return void
	 * @since 3.2.1
	 */
	public static function section( $prefix ) {
		SP_CSFramework::createSection(
			$prefix,
			array(
				'title'  => __( 'Section Settings', 'wp-expand-tabs-free' ),
				'fields' => array(
					array(
						'id'         => 'show_product_tabs_on_mobile',
						'type'       => 'switcher',
						'title'      => __( 'Product Tabs on Mobile Device', 'wp-expand-tabs-free' ),
						'default'    => true,
						'text_on'    => __( 'Show', 'wp-expand-tabs-free' ),
						'text_off'   => __( 'Hide', 'wp-expand-tabs-free' ),
						'text_width' => 75,
					),
					array(
						'id'         => 'skip_product_tab_style',
						'type'       => 'switcher',
						'title'      => __( 'Use Theme\'s Product Tab Style', 'wp-expand-tabs-free' ),
						'title_help' => __( 'Enable to prevent the plugin\'s default tab styling to maintain complete control over your product tabs\' appearance. This option is ideal when using custom themes or when you prefer to style your tabs exclusively through your own CSS rules.', 'wp-expand-tabs-free' ),
						'default'    => false,
						'text_on'    => __( 'Enabled', 'wp-expand-tabs-free' ),
						'text_off'   => __( 'Disabled', 'wp-expand-tabs-free' ),
						'text_width' => 100,
					),
					array(
						'id'         => 'show_wc_default_related_products',
						'type'       => 'switcher',
						'title'      => __( 'WooCommerce Default Related Products', 'wp-expand-tabs-free' ),
						'default'    => true,
						'title_help' => __( 'Hide WooCommerce’s default related products section on single product page. Recommended when using custom product tabs (e.g., Related, Specific, Best Selling, On Sale, or Latest products) to prevent duplication.', 'wp-expand-tabs-free' ),
						'text_on'    => __( 'Show', 'wp-expand-tabs-free' ),
						'text_off'   => __( 'Hide', 'wp-expand-tabs-free' ),
						'text_width' => 75,
					),

					/**
					 * Override Default WooCommerce Tabs.
					 */
					array(
						'id'         => 'override_wc_default_tabs',
						'class'      => 'sp_tabs_contact_form override_wc_default_tabs',
						'type'       => 'accordion',
						'title'      => __( 'Override WooCommerce Default Tabs', 'wp-expand-tabs-free' ),
						'accordions' => array(
							array(
								'title'  => __( 'Description', 'wp-expand-tabs-free' ),
								'fields' => array(
									array(
										'id'     => 'override_description',
										'type'   => 'fieldset',
										'class'  => 'tabs_title_section default_tab_override',
										'title'  => '',
										'fields' => array(
											array(
												'id'    => 'tab_name',
												'type'  => 'text',
												'class' => 'tabs_content-title',
												'wrap_class' => 'sp-tab__content_source',
												'title' => __( 'Tab Name', 'wp-expand-tabs-free' ),
												'title_help' => __( 'Leave it empty to show default tabs name.', 'wp-expand-tabs-free' ),
											),
											array(
												'id'      => 'tab_icon',
												'type'    => 'button_set',
												'options' => array(
													'add_icon' => __( 'Add Icon', 'wp-expand-tabs-free' ),
												),
												'default' => 'add_icon',
											),
										),
									),
								),
							),
							array(
								'title'  => __( 'Additional Information', 'wp-expand-tabs-free' ),
								'fields' => array(
									array(
										'id'     => 'override_additional_information',
										'type'   => 'fieldset',
										'class'  => 'tabs_title_section default_tab_override',
										'title'  => '',
										'fields' => array(
											array(
												'id'    => 'tab_name',
												'type'  => 'text',
												'class' => 'tabs_content-title',
												'wrap_class' => 'sp-tab__content_source',
												'title' => __( 'Tab Name', 'wp-expand-tabs-free' ),
												'title_help' => __( 'Leave it empty to show default tabs name.', 'wp-expand-tabs-free' ),
											),
										),
									),
								),
							),
							array(
								'title'  => __( 'Reviews', 'wp-expand-tabs-free' ),
								'fields' => array(
									array(
										'id'     => 'override_reviews',
										'type'   => 'fieldset',
										'class'  => 'tabs_title_section default_tab_override',
										'title'  => '',
										'fields' => array(
											array(
												'id'    => 'tab_name',
												'type'  => 'text',
												'class' => 'tabs_content-title',
												'wrap_class' => 'sp-tab__content_source',
												'title' => __( 'Tab Name', 'wp-expand-tabs-free' ),
												'title_help' => __( 'Leave it empty to show default tabs name.', 'wp-expand-tabs-free' ),
											),
										),
									),
								),
							),
						),
					),
					array(
						'id'         => 'search_in_tab_content',
						'type'       => 'switcher',
						'ignore_db'  => true,
						'class'      => 'only_pro_switcher',
						'title'      => __( 'Search in Tab Content', 'wp-expand-tabs-free' ),
						'title_help' => __( 'Allow customers to find products by matching keywords from product tab titles and their content in search results.', 'wp-expand-tabs-free' ),
						'default'    => false,
						'text_on'    => __( 'Enabled', 'wp-expand-tabs-free' ),
						'text_off'   => __( 'Disabled', 'wp-expand-tabs-free' ),
						'text_width' => 100,
					),
					array(
						'id'         => 'product_custom_tab',
						'type'       => 'switcher',
						'class'      => 'only_pro_switcher',
						'title'      => __( 'Add Custom Tab from Product Edit Page', 'wp-expand-tabs-free' ),
						'title_help' => __( 'Enable to create product-specific custom tabs from the product edit page.', 'wp-expand-tabs-free' ),
						'default'    => false,
						'text_on'    => __( 'Enabled', 'wp-expand-tabs-free' ),
						'text_off'   => __( 'Disabled', 'wp-expand-tabs-free' ),
						'text_width' => 100,
					),
					array(
						'id'         => 'show_tab_heading_inside_tab',
						'type'       => 'switcher',
						'class'      => 'only_pro_switcher',
						'title'      => __( 'WooCommerce Default Tab Heading', 'wp-expand-tabs-free' ),
						'title_help' => __( 'Hide repeated tab headings (e.g., Description, Additional Information, & Reviews) displayed inside the tab content.', 'wp-expand-tabs-free' ),
						'default'    => true,
						'text_on'    => __( 'Show', 'wp-expand-tabs-free' ),
						'text_off'   => __( 'Hide', 'wp-expand-tabs-free' ),
						'text_width' => 75,
					),
					array(
						'type'      => 'notice',
						'ignore_db' => true,
						'class'     => 'upgrade-to-pro-notice',
						'content'   => (
							SP_CSFramework::upgrade_to_pro_section()
						),
					),
				),
			)
		);
	}
}
