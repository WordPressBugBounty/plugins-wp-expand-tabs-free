<?php
/**
 * The Smart Tab settings of the plugin.
 *
 * @link       https://shapedplugin.com/
 * @since      2.2.2
 *
 * @package    WP_Tabs_Pro
 * @subpackage WP_Tabs_Pro/admin
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
						'image'           => SP_TABS_FREE_ADMIN_IMAGES . '/product-tabs-layout/horizontal-top.svg',
						'option_name'     => __( 'Horizontal Top', 'wp-expand-tabs-free' ),
						'option_demo_url' => 'https://wptabs.com/horizontal-tabs/',
						'class'           => 'free-feature',
					),
					'tabs-layout-pro' => array(
						'image' => SP_TABS_FREE_ADMIN_IMAGES . '/pro-presets/woo-pro-presets.svg',
						'class' => 'pro-feature',
					),
					// 'vertical'       => array(
					// 'image'           => SP_TABS_FREE_ADMIN_IMAGES . '/product-tabs-layout/vertical-left.svg',
					// 'option_name'     => __( 'Vertical Left', 'wp-expand-tabs-free' ),
					// 'option_demo_url' => 'https://wptabs.com/vertical-tabs/',
					// 'class'           => 'pro-feature',
					// ),
					// 'vertical-right' => array(
					// 'image'           => SP_TABS_FREE_ADMIN_IMAGES . '/product-tabs-layout/vertical-right.svg',
					// 'option_name'     => __( 'Vertical Right', 'wp-expand-tabs-free' ),
					// 'option_demo_url' => 'https://wptabs.com/vertical-tabs/#vertical-right',
					// 'class'           => 'pro-feature',
					// ),
					// 'tabs-accordion' => array(
					// 'image'           => SP_TABS_FREE_ADMIN_IMAGES . '/product-tabs-layout/accordion.svg',
					// 'option_name'     => __( 'Tabs Accordion', 'wp-expand-tabs-free' ),
					// 'option_demo_url' => 'https://wptabs.com/responsive-scrollable-tabs-2/',
					// 'class'           => 'pro-feature',
					// ),
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
			// array(
			// 'id'         => 'accordion_templates',
			// 'type'       => 'image_select',
			// 'class'      => 'accordion_templates',
			// 'title'      => __( 'Accordion Templates', 'wp-expand-tabs-free' ),
			// 'options'    => array(
			// 'template-default' => array(
			// 'image'       => SP_TABS_FREE_ADMIN_IMAGES . '/accordion-type/accordion_default.svg',
			// 'option_name' => __( 'Default Template', 'wp-expand-tabs-free' ),
			// ),
			// 'template-two'     => array(
			// 'image'       => SP_TABS_FREE_ADMIN_IMAGES . '/accordion-type/accordion_two.svg',
			// 'option_name' => __( 'Template Two', 'wp-expand-tabs-free' ),
			// ),
			// 'template-three'   => array(
			// 'image'       => SP_TABS_FREE_ADMIN_IMAGES . '/accordion-type/accordion_three.svg',
			// 'option_name' => __( 'Template Three', 'wp-expand-tabs-free' ),
			// ),
			// ),
			// 'default'    => 'template-default',
			// 'dependency' => array( 'product_tabs_layout', '==', 'tabs-accordion', true ),
			// ),
			array(
				'id'         => 'tab_position',
				'type'       => 'image_select',
				'ignore_db'  => true,
				'class'      => 'smart_tabs_position pro-preset-style',
				'title'      => __( 'Tabs Position', 'wp-expand-tabs-free' ),
				'options'    => array(
					'below-details' => array(
						'image'       => SP_TABS_FREE_ADMIN_IMAGES . '/woo-tabs-position/below-details.svg',
						'option_name' => __( 'Default', 'wp-expand-tabs-free' ),
						'class'       => 'free-feature',
					),
					'below-info'    => array(
						'image'       => SP_TABS_FREE_ADMIN_IMAGES . '/woo-tabs-position/below-info.svg',
						'option_name' => __( 'Below Summary', 'wp-expand-tabs-free' ),
						'class'       => 'pro-feature',
					),
				),
				'default'    => 'below-details',
				'dependency' => array( 'product_tabs_layout', 'not-any', 'vertical,vertical-right', true ),
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
				'title_help' => '<div class="wptabspro-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'partials/models/assets/images/help-visuals/content-height.svg" alt="Content Height"></div><div class="wptabspro-info-label"> ' . __( 'Content Height', 'wp-expand-tabs-free' ) . '</div><a class="wptabspro-open-docs" href="https://docs.shapedplugin.com/docs/wp-tabs-pro/configurations/how-to-set-tabs-content-height/" target="_blank">Open Docs</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/tabs-fixed-content-height/" target="_blank">Live Demo</a>',

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
		),
	)
);

SP_WP_TABS::createSection(
	$prefix,
	array(
		'title'  => __( 'Display Settings', 'wp-expand-tabs-free' ),
		'icon'   => 'fa icon-display',
		'fields' => array(
			// array(
			// 'id'         => 'tabs_icon_position',
			// 'type'       => 'image_select',
			// 'class'      => 'sp_tabs-icon-position',
			// 'title'      => __( 'Tabs Icon Position', 'wp-expand-tabs-free' ),
			// 'options'    => array(
			// 'left'  => array(
			// 'image' => SP_TABS_FREE_ADMIN_IMAGES . '/woo-tab-icons-position/left.svg',
			// ),
			// 'top'   => array(
			// 'image' => SP_TABS_FREE_ADMIN_IMAGES . '/woo-tab-icons-position/top.svg',
			// ),
			// 'right' => array(
			// 'image' => SP_TABS_FREE_ADMIN_IMAGES . '/woo-tab-icons-position/right.svg',
			// ),
			// ),
			// 'radio'      => true,
			// 'default'    => 'left',
			// 'dependency' => array( 'sptpro_toggle_icon', '==', 'true', true ),
			// ),
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
				'id'       => 'tabs_name_padding',
				'type'     => 'spacing',
				'title'    => __( 'Tabs Name Padding', 'wp-expand-tabs-free' ),
				'top_text' => __( 'Top', 'wp-expand-tabs-free' ),
				'units'    => array( 'px' ),
				'default'  => array(
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
				'id'         => 'tabs_bottom_line_position',
				'type'       => 'image_select',
				'class'      => 'sp_tabs-icon-position',
				'title'      => __( 'Tabs Line Position', 'wp-expand-tabs-free' ),
				'options'    => array(
					'bottom-line' => array(
						'image'       => SP_TABS_FREE_ADMIN_IMAGES . '/bottom-line.svg',
						'class'       => 'free-feature',
						'option_name' => __( 'Bottom Line', 'wp-expand-tabs-free' ),
					),
					'top-line'    => array(
						'image'       => SP_TABS_FREE_ADMIN_IMAGES . '/top-line.svg',
						'class'       => 'free-feature',
						'option_name' => __( 'Top Line', 'wp-expand-tabs-free' ),
					),
				),
				'radio'      => true,
				'default'    => 'bottom-line',
				'dependency' => array( 'product_tabs_layout', '==', 'horizontal', true ),
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
				'id'         => 'space_between_title_and_desc',
				'type'       => 'spacing',
				'title'      => __( 'Space Between Tabs and Description', 'wp-expand-tabs-free' ),
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
				'id'      => 'desc_background_color',
				'type'    => 'color',
				'class'   => 'desc_background_color',
				'title'   => __( 'Description Background Color', 'wp-expand-tabs-free' ),
				'default' => '#ffffff',
			),
			array(
				'id'       => 'desc_border',
				'class'    => 'tabs_desc_border',
				'type'     => 'border',
				'title'    => __( 'Description Border', 'wp-expand-tabs-free' ),
				'all_icon' => false,
				'all'      => true,
				'style'    => true,
				'default'  => array(
					'all'   => '0',
					'style' => 'solid',
					'color' => '#c3c4c7',
				),
			),
			array(
				'id'       => 'desc_padding',
				'type'     => 'spacing',
				'title'    => __( 'Description Padding', 'wp-expand-tabs-free' ),
				'top_text' => __( 'Top', 'wp-expand-tabs-free' ),
				'units'    => array( 'px' ),
				'default'  => array(
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
				'subtitle'   => __( 'Choose a tabs mode on small screen.', 'wp-expand-tabs-free' ),
				'title_help' => sprintf(
					/* translators: 1: start div tag, 2: close div tag, 3: start div tag, 4: start anchor tag, 5: close anchor tag, 6: start anchor tag, 7: close anchor tag */
					__( '%1$sTabs Mode on Small Screen%2$sChoose how your tabs behave on small screens, such as mobile devices. You can select "Full Width" to maintain the current layout or "Accordion" to switch to a collapsible format, ensuring the best user experience on mobile.%3$sOpen Docs%4$sOpen Live Demo%5$s', 'wp-expand-tabs-free' ),
					'<div class="wptabspro-info-label">',
					'</div><div class="wptabspro-short-content">',
					'</div><a class="wptabspro-open-docs" href="https://docs.shapedplugin.com/docs/wp-expand-tabs-free/configurations/how-to-configure-tabs-mood-on-small-screen/" target="_blank">',
					'</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/tabs-mood-on-small-screen/" target="_blank">',
					'</a>'
				),
				'options'    => array(
					'default'        => __( 'Default', 'wp-expand-tabs-free' ),
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
				'class'   => 'only_pro_notice_typo',
				'content' => sprintf(
					/* translators: 1: start link and bold tag, 2: close tag 3: start bold tag 4: close bold tag. */
					__( 'Smart Tabs Lite lets you customize Font Size, Color, and 1500+ Google Fonts — %1$sUpgrade to Pro!%2$s for Advanced Typography!', 'wp-expand-tabs-free' ),
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
				'title'     => __( 'Load Tabs Name Font', 'wp-expand-tabs-free' ),
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
					'color'          => '#828282',
					'hover_color'    => '#595959',
					'active_color'   => '#2C3333',
					'type'           => 'google',
				),
				'preview_text' => 'Tabs Name',
				'preview'      => 'always',
				'color'        => true,
				'hover_color'  => true,
				'active_color' => true,
			),

			array(
				'id'        => 'product_desc_font_load',
				'type'      => 'switcher',
				'ignore_db' => true,
				'class'     => 'only_pro_switcher',
				'title'     => __( 'Load Description Font', 'wp-expand-tabs-free' ),
				'default'   => false,
			),
			array(
				'id'           => 'product_desc_typo',
				'type'         => 'typography',
				'title'        => __( 'Description', 'wp-expand-tabs-free' ),
				'default'      => array(
					'color'          => '#444',
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
				'preview_text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
			),

			array(
				'id'        => 'image_caption_font_load',
				'type'      => 'switcher',
				'ignore_db' => true,
				'class'     => 'only_pro_switcher',
				'title'     => __( 'Load Image Caption Font', 'wp-expand-tabs-free' ),
				'default'   => false,
			),
			array(
				'id'           => 'image_caption_typo',
				'type'         => 'typography',
				'title'        => __( 'Image Caption', 'wp-expand-tabs-free' ),
				'ignore_db'    => true,
				'class'        => 'disable-typos',
				'default'      => array(
					'color'          => '#444',
					'font-family'    => '',
					'font-weight'    => '400',
					'font-style'     => 'normal',
					'font-size'      => '14',
					'line-height'    => '24',
					'letter-spacing' => '0',
					'text-align'     => 'center',
					'text-transform' => 'none',
					'type'           => 'google',
				),
				'preview'      => 'always',
				'preview_text' => __( 'Image Caption', 'wp-expand-tabs-free' ),
			),

			array(
				'id'        => 'faq_font_load',
				'type'      => 'switcher',
				'ignore_db' => true,
				'class'     => 'only_pro_switcher',
				'title'     => __( 'Load FAQs Font', 'wp-expand-tabs-free' ),
				'default'   => false,
			),
			array(
				'id'           => 'faq_typo',
				'type'         => 'typography',
				'title'        => __( 'FAQs', 'wp-expand-tabs-free' ),
				'ignore_db'    => true,
				'class'        => 'disable-typos',
				'default'      => array(
					'color'          => '#444',
					'font-family'    => '',
					'font-weight'    => '400',
					'font-style'     => 'normal',
					'font-size'      => '20',
					'line-height'    => '24',
					'letter-spacing' => '0',
					'text-align'     => 'left',
					'text-transform' => 'none',
					'type'           => 'google',
				),
				'preview'      => 'always',
				'preview_text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
			),

			array(
				'id'        => 'faq_ans_font_load',
				'type'      => 'switcher',
				'ignore_db' => true,
				'class'     => 'only_pro_switcher',
				'title'     => __( 'Load FAQs Answer Font', 'wp-expand-tabs-free' ),
				'default'   => false,
			),
			array(
				'id'           => 'faq_ans_typo',
				'type'         => 'typography',
				'title'        => __( 'FAQs Answer', 'wp-expand-tabs-free' ),
				'ignore_db'    => true,
				'class'        => 'disable-typos',
				'default'      => array(
					'color'          => '#444',
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
				'preview_text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
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
				'id'        => 'contact_form_admin_email',
				'type'      => 'text',
				'ignore_db' => true,
				'class'     => 'tabs-custom-text-pro sp-tabs-advanced-text-field',
				'title'     => __( 'Contact Form Tab', 'wp-expand-tabs-free' ),
				'desc'      => __( 'Set up the email destination for all contact form submissions.', 'wp-expand-tabs-free' ),
				'default'   => get_option( 'admin_email' ),
			),
			array(
				'id'         => 'skip_product_tab_style',
				'type'       => 'switcher',
				'ignore_db'  => true,
				'class'      => 'only_pro_switcher',
				'title'      => __( 'Skip Product Tab Style (Smart Tabs)', 'wp-expand-tabs-free' ),
				'title_help' => __( 'Enable to prevent the plugin\'s default tab styling to maintain complete control over your product tabs\' appearance. This option is ideal when using custom themes or when you prefer to style your tabs exclusively through your own CSS rules.', 'wp-expand-tabs-free' ),
				'default'    => false,
				'text_on'    => __( 'Enabled', 'wp-expand-tabs-free' ),
				'text_off'   => __( 'Disabled', 'wp-expand-tabs-free' ),
				'text_width' => 100,
			),
		),
	)
);
