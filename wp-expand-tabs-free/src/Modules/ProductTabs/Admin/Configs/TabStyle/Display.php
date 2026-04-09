<?php
/**
 * Display settings of product tabs.
 *
 * @link       https://shapedplugin.com/
 * @since      3.2.0
 *
 * @package    ShapedPlugin\SmartTabsFree
 * @subpackage Modules\ProductTabs\Admin\Configs\TabStyle
 */

namespace ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin\Configs\TabStyle;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

use ShapedPlugin\SmartTabsFree\Core\Constants;
use ShapedPlugin\SmartTabsFree\Lib\CSFramework\Classes\SP_CSFramework;

/**
 * Class Display.
 */
class Display {
	/**
	 * Section.
	 *
	 * @param mixed $prefix Prefix.
	 */
	public static function section( $prefix ) {
		//
		// Custom CSS Fields.
		//
		SP_CSFramework::createSection(
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
								'image'           => Constants::product_tabs_url() . 'Admin/Assets/img/tab-styles/default.svg',
								'option_name'     => __( 'Default', 'wp-expand-tabs-free' ),
								'option_demo_url' => 'https://demo.wptabs.com/product/nike-sportswear-jdi/',
							),
							'top-line' => array(
								'image'           => Constants::product_tabs_url() . 'Admin/Assets/img/tab-styles/top-line.svg',
								'option_name'     => __( 'Top Line', 'wp-expand-tabs-free' ),
								'option_demo_url' => 'https://demo.wptabs.com/horizontal-topline-tabs-style/product/apple-watch-ultra-2/',
							),
							'classic'  => array(
								'image'           => Constants::product_tabs_url() . 'Admin/Assets/img/tab-styles/classic.svg',
								'class'           => 'pro-feature',
								'option_name'     => __( 'Classic', 'wp-expand-tabs-free' ),
								'option_demo_url' => 'https://demo.wptabs.com/horizontal-classic-tabs-style/product/adjustable-dumbbell-set/',
							),
							'button'   => array(
								'image'           => Constants::product_tabs_url() . 'Admin/Assets/img/tab-styles/button.svg',
								'class'           => 'pro-feature',
								'option_name'     => __( 'Button', 'wp-expand-tabs-free' ),
								'option_demo_url' => 'https://demo.wptabs.com/horizontal-button-tabs-style/product/meta-quest-3-vr-headset/',
							),
							'rounded'  => array(
								'image'           => Constants::product_tabs_url() . 'Admin/Assets/img/tab-styles/rounded.svg',
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
								'image'           => Constants::product_tabs_url() . 'Admin/Assets/img/tab-styles/vertical-default.svg',
								'option_name'     => __( 'Default', 'wp-expand-tabs-free' ),
								'option_demo_url' => 'https://demo.wptabs.com/vertical-left-product-tabs/product/meta-quest-3-vr-headset/',
							),
							'classic' => array(
								'image'           => Constants::product_tabs_url() . 'Admin/Assets/img/tab-styles/vertical-classic.svg',
								'option_name'     => __( 'Classic', 'wp-expand-tabs-free' ),
								'class'           => 'pro-feature',
								'option_demo_url' => 'https://demo.wptabs.com/vertical-left-classic-tabs-style/product/apple-watch-ultra-2/',
							),
							'button'  => array(
								'image'           => Constants::product_tabs_url() . 'Admin/Assets/img/tab-styles/vertical-button.svg',
								'option_name'     => __( 'Button', 'wp-expand-tabs-free' ),
								'class'           => 'pro-feature',
								'option_demo_url' => 'https://demo.wptabs.com/vertical-left-button-tabs-style/product/adjustable-dumbbell-set/',
							),
							'rounded' => array(
								'image'           => Constants::product_tabs_url() . 'Admin/Assets/img/tab-styles/vertical-rounded.svg',
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
						'title_help' => '<div class="wptabspro-img-tag"><img src="' . Constants::cs_framework_assets() . 'images/help-visuals/margin-between-tabs.svg" alt="Spacing Between Tabs"></div><div class="wptabspro-info-label">' . __( 'Spacing between Tabs', 'wp-expand-tabs-free' ) . '</div>',
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
						'title_help' => '<div class="wptabspro-img-tag"><img src="' . Constants::cs_framework_assets() . 'images/help-visuals/title-padding.svg" alt="' . __( 'Tabs Name Padding', 'wp-expand-tabs-free' ) . '"></div><div class="wptabspro-info-label">' . __( 'Tabs Name Padding', 'wp-expand-tabs-free' ) . '</div>',
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
						'title_help' => '<div class="wptabspro-img-tag"><img src="' . Constants::cs_framework_assets() . 'images/help-visuals/margin-between-tabs-and-description.svg" alt="' . __( 'Space Between Tabs and Description', 'wp-expand-tabs-free' ) . '"></div><div class="wptabspro-info-label">' . __( 'Space Between Tabs and Description', 'wp-expand-tabs-free' ) . '</div>',
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
						'title_help' => '<div class="wptabspro-img-tag"><img src="' . Constants::cs_framework_assets() . 'images/help-visuals/description-padding.svg" alt="' . __( 'Description Padding', 'wp-expand-tabs-free' ) . '"></div><div class="wptabspro-info-label"> ' . __( 'Description Padding', 'wp-expand-tabs-free' ) . '</div>',
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
	}
}
