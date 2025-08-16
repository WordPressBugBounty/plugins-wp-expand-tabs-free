<?php
/**
 * Option config file.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package wp-expand-tabs-free
 * @subpackage wp-expand-tabs-free/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

//
// Set a unique slug-like ID.
//
$prefix = 'sp-tab__settings';

//
// Create options.
//
SP_WP_TABS::createOptions(
	$prefix,
	array(
		'menu_title'         => __( 'Settings', 'wp-expand-tabs-free' ),
		'menu_slug'          => 'tabs_settings',
		'menu_parent'        => 'edit.php?post_type=sp_wp_tabs',
		'menu_type'          => 'submenu',
		'show_bar_menu'      => false,
		'ajax_save'          => true,
		'save_defaults'      => true,
		'show_reset_all'     => false,
		'show_all_options'   => false,
		'show_reset_section' => true,
		'show_search'        => false,
		'show_footer'        => false,
		'framework_title'    => __( 'Settings', 'wp-expand-tabs-free' ),
		'framework_class'    => 'sp-tab__options',
		'theme'              => 'light',
	)
);

//
// Create a section.
//
SP_WP_TABS::createSection(
	$prefix,
	array(
		'title'  => __( 'Advanced Controls', 'wp-expand-tabs-free' ),
		'icon'   => 'fa sp-tab-icon-advanced',
		'fields' => array(
			array(
				'id'         => 'sptpro_data_remove',
				'type'       => 'checkbox',
				'title'      => __( 'Clean-up Data on Deletion', 'wp-expand-tabs-free' ),
				'title_help' => __( 'Check this box if you would like Smart Tabs to completely remove all of its data when the plugin is deleted.', 'wp-expand-tabs-free' ),
				'default'    => false,
			),
			array(
				'id'         => 'enable_product_tabs',
				'type'       => 'switcher',
				'title'      => __( 'Product Tabs', 'wp-expand-tabs-free' ),
				'text_on'    => __( 'Enabled', 'wp-expand-tabs-free' ),
				'text_off'   => __( 'Disabled', 'wp-expand-tabs-free' ),
				'title_help' => __( 'Disable this option if your website does not have a WooCommerce store. In this case, the product tabs functionality will not be loaded, keeping your admin panel optimized and faster.', 'wp-expand-tabs-free' ),
				'default'    => true,
				'text_width' => 100,
			),
		),
	)
);

// Check if the version is less than 3.0.0 before creating the Woo Tab Group section.
if ( version_compare( WP_TABS_FIRST_VERSION, '3.0.0', '<' ) ) {
	//
	// Create a section.
	//
	SP_WP_TABS::createSection(
		$prefix,
		array(
			'title'  => __( 'Woo Tab Group', 'wp-expand-tabs-free' ),
			'icon'   => 'fa fa-shopping-bag',
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


//
// Custom CSS Fields.
//
SP_WP_TABS::createSection(
	$prefix,
	array(
		'id'     => 'custom_css_section',
		'title'  => __( 'Additional CSS', 'wp-expand-tabs-free' ),
		'icon'   => 'fa sp-tab-icon-code',
		'fields' => array(
			array(
				'id'       => 'sptpro_custom_css',
				'type'     => 'code_editor',
				'title'    => __( 'Custom CSS', 'wp-expand-tabs-free' ),
				'settings' => array(
					'mode'  => 'css',
					'theme' => 'monokai',
				),
			),
		),
	)
);
