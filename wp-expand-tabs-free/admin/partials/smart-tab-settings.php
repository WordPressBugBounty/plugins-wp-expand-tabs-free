<?php
/**
 * The Smart Tab settings of the plugin.
 *
 * @link       https://shapedplugin.com/
 * @since      2.2.2
 *
 * @package wp-expand-tabs-free
 * @subpackage wp-expand-tabs-free/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

//
// Set a unique slug-like ID.
//
$prefix = 'sptabs_product_tabs_settings';
SP_WP_TABS::createOptions(
	$prefix,
	array(
		'menu_title'         => __( 'Style', 'wp-expand-tabs-free' ),
		'menu_slug'          => 'tab_styles',
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
		'framework_title'    => 'Product Tabs Style',
		'framework_class'    => 'sp-woo_product_tabs__options',
		'theme'              => 'light',
	)
);

SP_WP_TABS::createSection(
	$prefix,
	array(
		'title'  => __( 'General Settings', 'wp-expand-tabs-free' ),
		'icon'   => 'fa icon-general-icon',
		'fields' => array(
			array(
				'id'      => 'product_tabs_layout',
				'type'    => 'image_select',
				'class'   => 'sptpro_product_tabs_layout sp_wp_tabs_layout pro-preset-style',
				'title'   => __( 'Tabs Layout', 'wp-expand-tabs-free' ),
				'options' => array(
					'horizontal'      => array(
						'image'           => SP_TABS_FREE_ADMIN_IMAGES . '/product-tabs-layout/horizontal.svg',
						'option_name'     => __( 'Horizontal', 'wp-expand-tabs-free' ),
						'option_demo_url' => 'https://demo.wptabs.com/product/nike-sportswear-jdi/',
					),
					'vertical-right'  => array(
						'image'           => SP_TABS_FREE_ADMIN_IMAGES . '/product-tabs-layout/vertical-right.svg',
						'option_name'     => __( 'Vertical Right', 'wp-expand-tabs-free' ),
						'option_demo_url' => 'https://demo.wptabs.com/vertical-right-product-tabs/product/adjustable-dumbbell-set/',
					),
					'vertical'        => array(
						'image'           => SP_TABS_FREE_ADMIN_IMAGES . '/product-tabs-layout/vertical-left.svg',
						'option_name'     => __( 'Vertical Left', 'wp-expand-tabs-free' ),
						'class'           => 'pro-feature',
						'option_demo_url' => 'https://demo.wptabs.com/vertical-left-product-tabs/product/meta-quest-3-vr-headset/',
					),

					'tabs-accordion'  => array(
						'image'           => SP_TABS_FREE_ADMIN_IMAGES . '/product-tabs-layout/accordion.svg',
						'option_name'     => __( 'Accordion', 'wp-expand-tabs-free' ),
						'class'           => 'pro-feature',
						'option_demo_url' => 'https://demo.wptabs.com/accordion-product-tabs/product/hot-air-balloon-flight/',
					),
					'below-summary'   => array(
						'image'           => SP_TABS_FREE_ADMIN_IMAGES . '/product-tabs-layout/below-summary.svg',
						'option_name'     => __( 'Below Summary', 'wp-expand-tabs-free' ),
						'class'           => 'pro-feature',
						'option_demo_url' => 'https://demo.wptabs.com/below-summary-product-tabs/product/the-alchemist/',
					),
					'accordion-below' => array(
						'image'           => SP_TABS_FREE_ADMIN_IMAGES . '/product-tabs-layout/accordion-below.svg',
						'option_name'     => __( 'Accordion Below', 'wp-expand-tabs-free' ),
						'class'           => 'pro-feature',
						'option_demo_url' => 'https://demo.wptabs.com/below-summary-accordion-product-tabs/product/the-plant-protein/',
					),
				),
				'radio'   => true,
				'default' => 'horizontal',
			),
			array(
				'id'         => 'product_tabs_alignemnt',
				'type'       => 'image_select',
				'title'      => __( 'Tabs Alignemnt', 'wp-expand-tabs-free' ),
				'options'    => array(
					'left'          => array(
						'image'       => SP_TABS_FREE_ADMIN_IMAGES . '/product-tabs-alignment/tab_left.svg',
						'option_name' => __( 'Left', 'wp-expand-tabs-free' ),
						'class'       => 'free-feature',
					),
					'center'        => array(
						'image'       => SP_TABS_FREE_ADMIN_IMAGES . '/product-tabs-alignment/tab_center.svg',
						'option_name' => __( 'Center', 'wp-expand-tabs-free' ),
						'class'       => 'free-feature',
					),
					'right'         => array(
						'image'       => SP_TABS_FREE_ADMIN_IMAGES . '/product-tabs-alignment/tab_right.svg',
						'option_name' => __( 'Right', 'wp-expand-tabs-free' ),
						'class'       => 'free-feature',
					),
					'space-between' => array(
						'image'       => SP_TABS_FREE_ADMIN_IMAGES . '/product-tabs-alignment/justified.svg',
						'option_name' => __( 'Justified', 'wp-expand-tabs-free' ),
						'class'       => 'free-feature',
					),
				),
				'radio'      => true,
				'default'    => 'left',
				'dependency' => array( 'product_tabs_layout', '==', 'horizontal', true ),
			),
			array(
				'id'         => 'product_tabs_activator_event',
				'type'       => 'radio',
				'title'      => __( 'Activator Event', 'wp-expand-tabs-free' ),
				'options'    => array(
					'tabs-activator-event-click' => __( 'On Click', 'wp-expand-tabs-free' ),
					'tabs-activator-event-hover' => __( 'Mouseover', 'wp-expand-tabs-free' ),
				),
				'default'    => 'tabs-activator-event-click',
				'dependency' => array( 'product_tabs_layout', '!=', 'tabs-carousel', true ),
			),

			array(
				'id'         => 'sp_autoplay_time',
				'type'       => 'spinner',
				'title'      => __( 'AutoPlay Delay Time', 'wp-expand-tabs-free' ),
				'title_info' => sprintf(
					'<div class="wptabspro-info-label">%1$s</div>%2$s ',
					__( 'AutoPlay Delay Time', 'wp-expand-tabs-free' ),
					__( 'Set autoplay delay or interval time. The amount of time to delay between automatically cycling a weather item. e.g. 1000 milliseconds(ms) = 1 second.', 'wp-expand-tabs-free' )
				),
				'unit'       => 'ms',
				'min'        => 1000,
				'max'        => 100000,
				'default'    => 3000,
				'dependency' => array( 'sptpro_tabs_activator_event', '==', 'tabs-activator-event-auto' ),
			),
			array(
				'id'         => 'sptpro_pause_on_hover',
				'type'       => 'switcher',
				'title'      => __( 'Pause on Hover', 'wp-expand-tabs-free' ),
				'text_on'    => __( 'Enabled', 'wp-expand-tabs-free' ),
				'text_off'   => __( 'Disabled', 'wp-expand-tabs-free' ),
				'text_width' => 96,
				'default'    => true,
				'dependency' => array( 'sptpro_tabs_activator_event', '==', 'tabs-activator-event-auto' ),
			),
			array(
				'id'         => 'sptpro_preloader',
				'type'       => 'switcher',
				'title'      => __( 'Preloader', 'wp-expand-tabs-free' ),
				'text_on'    => __( 'Enabled', 'wp-expand-tabs-free' ),
				'text_off'   => __( 'Disabled', 'wp-expand-tabs-free' ),
				'text_width' => 96,
				'default'    => true,
			),
			array(
				'id'         => 'sptpro_tab_content_height',
				'type'       => 'button_set',
				'ignore_db'  => true,
				'class'      => 'only_pro_fixed_height',
				'title'      => __( 'Content Height', 'wp-expand-tabs-free' ),
				'title_help' => '<div class="wptabspro-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'partials/models/assets/images/help-visuals/content-height.svg" alt="Content Height"></div><div class="wptabspro-info-label"> ' . __( 'Content Height', 'wp-expand-tabs-free' ) . '</div><a class="wptabspro-open-docs" href="https://wptabs.com/docs/style/display-settings/" target="_blank">Open Docs</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/tabs-fixed-content-height/" target="_blank">Live Demo</a>',

				'options'    => array(
					'auto'   => __( 'Auto', 'wp-expand-tabs-free' ),
					'custom' => __( 'Custom', 'wp-expand-tabs-free' ),
				),
				'default'    => 'auto',
				'dependency' => array( 'product_tabs_layout', '!=', 'tabs-accordion' ),
			),
			array(
				'id'         => 'sptpro_tab_opened',
				'type'       => 'spinner',
				'ignore_db'  => true,
				'class'      => 'only_pro_spinner',
				'title'      => __( 'Initial Open Tab', 'wp-expand-tabs-free' ),
				'title_help' => '<div class="wptabspro-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'partials/models/assets/images/help-visuals/initial-tab-opened.svg" alt="' . __( 'Initial Open Tab', 'wp-expand-tabs-free' ) . '"></div><div class="wptabspro-info-label">' . __( 'Initial Open Tab', 'wp-expand-tabs-free' ) . '</div>',
				'min'        => 1,
				'default'    => 1,
			),
			array(
				'id'         => 'tab_hash_anchor_at_url',
				'type'       => 'switcher',
				'ignore_db'  => true,
				'class'      => 'only_pro_switcher',
				'title'      => __( 'Tab Hash Anchor at URL', 'wp-expand-tabs-free' ),
				'title_help' => sprintf(
					'<div class="wptabspro-info-label">%s</div><div class="wptabspro-short-content">%s <br/>%s <br/>%s</div>',
					__( 'Tab Hash Anchor at URL', 'wp-expand-tabs-free' ),
					__( 'Choose how the tab’s hash appears in the URL for SEO-friendly slugs and shareable links.', 'wp-expand-tabs-free' ),
					__( 'Tab Slug – Uses the tab’s slug (e.g., #overview).', 'wp-expand-tabs-free' ),
					__( 'Tab Slug with ID – Combines slug and ID (e.g., #overview-12).', 'wp-expand-tabs-free' ),
				),
				'text_on'    => __( 'Enabled', 'wp-expand-tabs-free' ),
				'text_off'   => __( 'Disabled', 'wp-expand-tabs-free' ),
				'text_width' => 96,
				'default'    => false,
			),
			array(
				'id'      => 'product_tabs_notice',
				'type'    => 'notice',
				'style'   => 'normal',
				'class'   => 'smart-tabs-notice',
				'content' => sprintf(
					/* translators: 1: start link and strong tag, 2: close link and strong tag, 3: start strong tag, 4: close strong tag. 5: start link and strong tag, 6: close link and strong tag. */
					__( 'Highlight Product Info Perfectly with More Beautiful & Customizable Tabs Layouts to Boost Sales — %1$sUpgrade to Pro!%2$s', 'wp-expand-tabs-free' ),
					'<a class="wcgs-open-live-demo" href="' . esc_url( SP_SMART_TABS_PRO_LINK ) . '" target="_blank"><strong>',
					'</strong></a>'
				),
			),
		),
	)
);

SP_WP_TABS::createSection(
	$prefix,
	array(
		'title'  => __( 'Display Settings', 'wp-expand-tabs-free' ),
		'icon'   => 'fa icon-display',
		'fields' => array(
			array(
				'id'         => 'sptpro_tab_style',
				'type'       => 'image_select',
				'class'      => 'smart_tab_style pro-preset-style sp_wp_tabs_layout',
				'title'      => __( 'Tabs Style', 'wp-expand-tabs-free' ),
				'options'    => array(
					'default'  => array(
						'image'           => SP_TABS_FREE_ADMIN_IMAGES . '/tab-styles/default.svg',
						'option_name'     => __( 'Default', 'wp-expand-tabs-free' ),
						'option_demo_url' => 'https://demo.wptabs.com/product/nike-sportswear-jdi/',
					),
					'top-line' => array(
						'image'           => SP_TABS_FREE_ADMIN_IMAGES . '/tab-styles/top-line.svg',
						'option_name'     => __( 'Top Line', 'wp-expand-tabs-free' ),
						'option_demo_url' => 'https://demo.wptabs.com/horizontal-topline-tabs-style/product/apple-watch-ultra-2/',
					),
					'classic'  => array(
						'image'           => SP_TABS_FREE_ADMIN_IMAGES . '/tab-styles/classic.svg',
						'class'           => 'pro-feature',
						'option_name'     => __( 'Classic', 'wp-expand-tabs-free' ),
						'option_demo_url' => 'https://demo.wptabs.com/horizontal-classic-tabs-style/product/adjustable-dumbbell-set/',
					),
					'button'   => array(
						'image'           => SP_TABS_FREE_ADMIN_IMAGES . '/tab-styles/button.svg',
						'class'           => 'pro-feature',
						'option_name'     => __( 'Button', 'wp-expand-tabs-free' ),
						'option_demo_url' => 'https://demo.wptabs.com/horizontal-button-tabs-style/product/meta-quest-3-vr-headset/',
					),
					'rounded'  => array(
						'image'           => SP_TABS_FREE_ADMIN_IMAGES . '/tab-styles/rounded.svg',
						'class'           => 'pro-feature',
						'option_name'     => __( 'Rounded', 'wp-expand-tabs-free' ),
						'option_demo_url' => 'https://demo.wptabs.com/horizontal-rounded-tabs-style/product/the-alchemist/',
					),
				),
				'radio'      => true,
				'default'    => 'default',
				'dependency' => array( 'product_tabs_layout', 'not-any', 'vertical,vertical-right,tabs-accordion,accordion-below', true ),
			),
			array(
				'id'         => 'sptpro_tab_vertical_style',
				'type'       => 'image_select',
				'class'      => 'smart_tab_style pro-preset-style vertical sp_wp_tabs_layout',
				'title'      => __( 'Tabs Style', 'wp-expand-tabs-free' ),
				'options'    => array(
					'default' => array(
						'image'           => SP_TABS_FREE_ADMIN_IMAGES . '/tab-styles/vertical-default.svg',
						'option_name'     => __( 'Default', 'wp-expand-tabs-free' ),
						'option_demo_url' => 'https://demo.wptabs.com/vertical-left-product-tabs/product/meta-quest-3-vr-headset/',
					),
					'classic' => array(
						'image'           => SP_TABS_FREE_ADMIN_IMAGES . '/tab-styles/vertical-classic.svg',
						'option_name'     => __( 'Classic', 'wp-expand-tabs-free' ),
						'class'           => 'pro-feature',
						'option_demo_url' => 'https://demo.wptabs.com/vertical-left-classic-tabs-style/product/apple-watch-ultra-2/',
					),
					'button'  => array(
						'image'           => SP_TABS_FREE_ADMIN_IMAGES . '/tab-styles/vertical-button.svg',
						'option_name'     => __( 'Button', 'wp-expand-tabs-free' ),
						'class'           => 'pro-feature',
						'option_demo_url' => 'https://demo.wptabs.com/vertical-left-button-tabs-style/product/adjustable-dumbbell-set/',
					),
					'rounded' => array(
						'image'           => SP_TABS_FREE_ADMIN_IMAGES . '/tab-styles/vertical-rounded.svg',
						'option_name'     => __( 'Rounded', 'wp-expand-tabs-free' ),
						'class'           => 'pro-feature',
						'option_demo_url' => 'https://demo.wptabs.com/vertical-left-rounded-tabs-style/product/ai-powered-design-workshop/',
					),
				),
				'radio'      => true,
				'default'    => 'default',
				'dependency' => array( 'product_tabs_layout', 'any', 'vertical,vertical-right', true ),
			),
			array(
				'id'         => 'sptpro_margin_between_tabs',
				'type'       => 'spacing',
				'title'      => __( 'Spacing Between Tabs', 'wp-expand-tabs-free' ),
				'title_help' => '<div class="wptabspro-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'partials/models/assets/images/help-visuals/margin-between-tabs.svg" alt="Spacing Between Tabs"></div><div class="wptabspro-info-label">' . __( 'Spacing between Tabs', 'wp-expand-tabs-free' ) . '</div>',
				'all'        => true,
				'all_icon'   => '<i class="fa fa-arrows-h"></i>',
				'default'    => array(
					'all' => '10',
				),
				'units'      => array(
					'px',
				),
			),
			array(
				'id'      => 'tab_name_color',
				'type'    => 'color_group',
				'class'   => 'tab_name_bg_color',
				'title'   => __( 'Tabs Name Color', 'wp-expand-tabs-free' ),
				'options' => array(
					'color'        => __( 'Color', 'wp-expand-tabs-free' ),
					'active-color' => __( 'Active Color', 'wp-expand-tabs-free' ),
					'hover-color'  => __( 'Hover Color', 'wp-expand-tabs-free' ),
				),
				'default' => array(
					'color'        => '#828282',
					'active-color' => '#111111',
					'hover-color'  => '#111111',
				),
			),
			array(
				'id'      => 'tab_name_bg_color',
				'type'    => 'color_group',
				'class'   => 'tab_name_bg_color',
				'title'   => __( 'Tabs Name Background', 'wp-expand-tabs-free' ),
				'options' => array(
					'color'        => __( 'Color', 'wp-expand-tabs-free' ),
					'active-color' => __( 'Active Color', 'wp-expand-tabs-free' ),
					'hover-color'  => __( 'Hover Color', 'wp-expand-tabs-free' ),
				),
				'default' => array(
					'color'        => '#fff',
					'active-color' => '#fff',
					'hover-color'  => '#fff',
				),
			),
			array(
				'id'         => 'tabs_name_padding',
				'type'       => 'spacing',
				'title'      => __( 'Tabs Name Padding', 'wp-expand-tabs-free' ),
				'title_help' => '<div class="wptabspro-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'partials/models/assets/images/help-visuals/title-padding.svg" alt="' . __( 'Tabs Name Padding', 'wp-expand-tabs-free' ) . '"></div><div class="wptabspro-info-label">' . __( 'Tabs Name Padding', 'wp-expand-tabs-free' ) . '</div>',
				'top_text'   => __( 'Top', 'wp-expand-tabs-free' ),
				'units'      => array( 'px' ),
				'default'    => array(
					'top'    => '14',
					'right'  => '16',
					'bottom' => '14',
					'left'   => '16',
				),
			),
			array(
				'id'         => 'tabs_bottom_line',
				'type'       => 'border',
				'class'      => 'tabs_bottom_line',
				'title'      => __( 'Tabs Bottom Line', 'wp-expand-tabs-free' ),
				'all_icon'   => false,
				'all'        => true,
				'style'      => false,
				'default'    => array(
					'all'   => '2',
					'color' => '#E7E7E7',
				),
				'dependency' => array( 'product_tabs_layout', '==', 'horizontal', true ),
			),
			array(
				'id'         => 'tabs_vertical_line',
				'type'       => 'border',
				// 'class'      => 'tabs_bottom_line',
				'title'      => __( 'Tabs Vertical Line', 'wp-expand-tabs-free' ),
				'all_icon'   => false,
				'all'        => true,
				'style'      => false,
				'default'    => array(
					'all'   => '2',
					'color' => '#E7E7E7',
				),
				'dependency' => array( 'product_tabs_layout', 'any', 'vertical,vertical-right', true ),
			),
			array(
				'id'         => 'active_tab_bottom_line',
				'type'       => 'border',
				'title'      => __( 'Active Tab Line', 'wp-expand-tabs-free' ),
				'all_icon'   => false,
				'all'        => true,
				'style'      => false,
				'default'    => array(
					'all'   => '3',
					'color' => '#121212',
				),
				'dependency' => array( 'product_tabs_layout', '!=', 'tabs-accordion', true ),
			),
			array(
				'id'         => 'active_tab_indicator_style',
				'type'       => 'fieldset',
				'ignore_db'  => true,
				'class'      => 'sptpro_tabs_carousel_navigation_style indicator-arrow-style only_pro_spinner',
				'title'      => __( 'Active Tab Indicator Arrow', 'wp-expand-tabs-free' ),
				'fields'     => array(
					array(
						'id'      => 'indicator_style',
						'type'    => 'button_set',
						'class'   => 'tabs_navigation_styles',
						'options' => array(
							'none'          => __( 'None', 'wp-expand-tabs-free' ),
							'chevron'       => '<i class="sp-indicator-icon-indicator-right-open"></i>',
							'angle'         => '<i class="sp-indicator-icon-indicator-right-open-1"></i>',
							'chevron-light' => '<i class="sp-indicator-icon-indicator-right-open-big"></i>',
							'logn-arrow'    => '<i class="sp-indicator-icon-right"></i>',
						),
						'default' => 'none',
					),
					array(
						'id'      => 'inditcator_color',
						'type'    => 'color',
						'class'   => 'desc_background_color',
						'default' => '#111',
						// 'dependency' => array( 'indicator_style', '!=', 'none', true ),
					),
				),
				'dependency' => array( 'product_tabs_layout', 'any', 'vertical,vertical-right', true ),
			),
			array(
				'type'    => 'subheading',
				'content' => __( 'Tabs Description', 'wp-expand-tabs-free' ),
			),
			array(
				'id'         => 'space_between_title_and_desc',
				'type'       => 'spacing',
				'title'      => __( 'Space Between Tabs and Description', 'wp-expand-tabs-free' ),
				'title_help' => '<div class="wptabspro-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'partials/models/assets/images/help-visuals/margin-between-tabs-and-description.svg" alt="' . __( 'Space Between Tabs and Description', 'wp-expand-tabs-free' ) . '"></div><div class="wptabspro-info-label">' . __( 'Space Between Tabs and Description', 'wp-expand-tabs-free' ) . '</div>',
				'all_icon'   => '<i class="fa fa-arrows-v"></i>',
				'all'        => true,
				'all_text'   => false,
				'default'    => array(
					'all' => '0',
				),
				'units'      => array(
					'px',
				),
				'dependency' => array( 'product_tabs_layout', '!=', 'tabs-accordion', true ),
			),
			array(
				'id'      => 'description_color',
				'type'    => 'color_group',
				'class'   => 'tab_name_bg_color',
				'title'   => __( 'Description Color', 'wp-expand-tabs-free' ),
				'options' => array(
					'color'    => __( 'Font Color', 'wp-expand-tabs-free' ),
					'bg_color' => __( 'Background', 'wp-expand-tabs-free' ),
				),
				'default' => array(
					'color'    => '#444',
					'bg_color' => '#fff',
				),
			),
			array(
				'id'            => 'desc_border',
				'class'         => 'tabs_desc_border',
				'type'          => 'border',
				'title'         => __( 'Description Border', 'wp-expand-tabs-free' ),
				'all_icon'      => false,
				'all'           => true,
				'style'         => true,
				'border_radius' => true,
				'default'       => array(
					'all'           => '0',
					'style'         => 'solid',
					'color'         => '#c3c4c7',
					'border_radius' => '0',
				),
			),
			array(
				'id'         => 'desc_padding',
				'type'       => 'spacing',
				'title'      => __( 'Description Padding', 'wp-expand-tabs-free' ),
				'title_help' => '<div class="wptabspro-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'partials/models/assets/images/help-visuals/description-padding.svg" alt="' . __( 'Description Padding', 'wp-expand-tabs-free' ) . '"></div><div class="wptabspro-info-label"> ' . __( 'Description Padding', 'wp-expand-tabs-free' ) . '</div>',
				'top_text'   => __( 'Top', 'wp-expand-tabs-free' ),
				'units'      => array( 'px' ),
				'default'    => array(
					'top'    => '20',
					'right'  => '20',
					'bottom' => '20',
					'left'   => '20',
				),
			),
			array(
				'id'              => 'sptpro_set_small_screen',
				'type'            => 'spacing',
				'title'           => __( 'When Screen Width is Less Than', 'wp-expand-tabs-free' ),
				'all'             => true,
				'all_text'        => false,
				'all_placeholder' => 'width',
				'default'         => array(
					'all' => '480',
				),
				'units'           => array(
					'px',
				),
			),
			array(
				'id'         => 'sptpro_tabs_on_small_screen',
				'type'       => 'radio',
				'title'      => __( 'Tabs Mode on Small Screen', 'wp-expand-tabs-free' ),
				'title_help' => sprintf(
					/* translators: 1: start div tag, 2: close div tag, 3: start div tag, 4: start anchor tag, 5: close anchor tag, 6: start anchor tag, 7: close anchor tag */
					__( '%1$sTabs Mode on Small Screen%2$sChoose how your tabs behave on small screens, such as mobile devices. You can select "Full Width" to maintain the current layout or "Accordion" to switch to a collapsible format, ensuring the best user experience on mobile.%3$sOpen Docs%4$sOpen Live Demo%5$s', 'wp-expand-tabs-free' ),
					'<div class="wptabspro-info-label">',
					'</div><div class="wptabspro-short-content">',
					'</div><a class="wptabspro-open-docs" href="https://wptabs.com/docs/style/display-settings/" target="_blank">',
					'</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/tabs-mood-on-small-screen/" target="_blank">',
					'</a>'
				),
				'options'    => array(
					'default'        => __( 'Same as Desktop', 'wp-expand-tabs-free' ),
					'full_width'     => __( 'Full Width', 'wp-expand-tabs-free' ),
					'accordion_mode' => __( 'Accordion', 'wp-expand-tabs-free' ),
				),
				'default'    => 'default',
			),
			array(
				'id'         => 'sptpro_expand_and_collapse_icon',
				'type'       => 'switcher',
				'title'      => __( 'Expand and Collapse Icon', 'wp-expand-tabs-free' ),
				'default'    => true,
				'text_on'    => __( 'Show', 'wp-expand-tabs-free' ),
				'text_off'   => __( 'Hide', 'wp-expand-tabs-free' ),
				'text_width' => 75,
				'dependency' => array( 'sptpro_tabs_on_small_screen', '==', 'accordion_mode' ),
			),
			array(
				'id'         => 'tabs_icon',
				'type'       => 'switcher',
				'ignore_db'  => true,
				'class'      => 'only_pro_switcher',
				'title'      => __( 'Tabs Icon', 'wp-expand-tabs-free' ),
				'default'    => false,
				'text_on'    => __( 'Show', 'wp-expand-tabs-free' ),
				'text_off'   => __( 'Hide', 'wp-expand-tabs-free' ),
				'text_width' => 75,
			),
		),
	)
);

SP_WP_TABS::createSection(
	$prefix,
	array(
		'title'  => __( 'Typography', 'wp-expand-tabs-free' ),
		'icon'   => 'fa icon-typography',
		'fields' => array(
			array(
				'type'    => 'notice',
				'class'   => 'only_pro_notice_typo smart-tabs-notice',
				'content' => sprintf(
					/* translators: 1: start link and bold tag, 2: close tag 3: start bold tag 4: close bold tag. */
					__( 'Access 1500+ Google Fonts & Advanced Typography Options — %1$sUpgrade to Pro!%2$s', 'wp-expand-tabs-free' ),
					'<a href="' . esc_url( SP_SMART_TABS_PRO_LINK ) . '" target="_blank"><b>',
					'</b></a>',
					'<b class="sptpro-notice-typo-exception">',
					'</b>'
				),
			),
			array(
				'id'        => 'product_tabs_title_font_load',
				'type'      => 'switcher',
				'ignore_db' => true,
				'class'     => 'only_pro_switcher',
				'title'     => __( 'Enable Google Fonts for Tab Name', 'wp-expand-tabs-free' ),
				'default'   => false,
			),
			array(
				'id'           => 'product_tabs_title_typo',
				'type'         => 'typography',
				'title'        => __( 'Tabs Name', 'wp-expand-tabs-free' ),
				'default'      => array(
					'font-family'    => '',
					'font-weight'    => '600',
					'font-style'     => 'normal',
					'font-size'      => '16',
					'line-height'    => '22',
					'letter-spacing' => '0',
					'text-align'     => 'center',
					'text-transform' => 'none',
					'type'           => 'google',
				),
				'preview_text' => 'Tabs Name',
				'preview'      => 'always',
				'color'        => false,
			),

			array(
				'id'        => 'product_desc_font_load',
				'type'      => 'switcher',
				'ignore_db' => true,
				'class'     => 'only_pro_switcher',
				'title'     => __( 'Enable Google Fonts for Tab Description', 'wp-expand-tabs-free' ),
				'default'   => false,
			),
			array(
				'id'           => 'product_desc_typo',
				'type'         => 'typography',
				'title'        => __( 'Description', 'wp-expand-tabs-free' ),
				'color'        => false,
				'default'      => array(
					'font-family'    => '',
					'font-weight'    => '400',
					'font-style'     => 'normal',
					'font-size'      => '16',
					'line-height'    => '24',
					'letter-spacing' => '0',
					'text-align'     => 'left',
					'text-transform' => 'none',
					'type'           => 'google',
				),
				'preview'      => 'always',
				'preview_text' => 'Smart Tabs makes your content organized and stylish. Easily create customizable tabs to enhance user experience.',
			),

		),
	)
);


//
// Set a unique slug-like ID.
//
$prefix = 'sp_products_tabs_advanced';
SP_WP_TABS::createOptions(
	$prefix,
	array(
		'menu_title'         => __( 'Advanced', 'wp-expand-tabs-free' ),
		'menu_slug'          => 'tab_advanced',
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
		'framework_title'    => 'Advanced',
		'framework_class'    => 'sp-woo_product_tabs__options',
		'theme'              => 'light',
	)
);

SP_WP_TABS::createSection(
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
				// 'ignore_db'  => true,
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
										'id'         => 'tab_name',
										'type'       => 'text',
										'class'      => 'tabs_content-title',
										'wrap_class' => 'sp-tab__content_source',
										'title'      => __( 'Tab Name', 'wp-expand-tabs-free' ),
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
										'id'         => 'tab_name',
										'type'       => 'text',
										'class'      => 'tabs_content-title',
										'wrap_class' => 'sp-tab__content_source',
										'title'      => __( 'Tab Name', 'wp-expand-tabs-free' ),
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
										'id'         => 'tab_name',
										'type'       => 'text',
										'class'      => 'tabs_content-title',
										'wrap_class' => 'sp-tab__content_source',
										'title'      => __( 'Tab Name', 'wp-expand-tabs-free' ),
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
					SP_WP_TABS::upgrade_to_pro_section()
				),
			),
		),
	)
);
