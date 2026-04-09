<?php
/**
 * The Tabs General section.
 *
 * @link       https://shapedplugin.com/
 * @since      3.2.0
 *
 * @package    ShapedPlugin\SmartTabsFree
 * @subpackage Modules\WPTabs\Admin\Configs
 */

namespace ShapedPlugin\SmartTabsFree\Modules\WPTabs\Admin\Configs\Generator;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

use ShapedPlugin\SmartTabsFree\Core\Constants;
use ShapedPlugin\SmartTabsFree\Lib\CSFramework\Classes\SP_CSFramework;

/**
 * Class General
 */
class General {
	/**
	 * Register Tabs General section.
	 *
	 * @param string $prefix The prefix for the section.
	 */
	public static function section( $prefix ) {
		SP_CSFramework::createSection(
			$prefix,
			array(
				'title'  => __( 'Tabs Settings', 'wp-expand-tabs-free' ),
				'icon'   => 'fa icon-general-icon',
				'fields' => array(
					array(
						'id'         => 'sptpro_tabs_layout',
						'type'       => 'image_select',
						'class'      => 'sp_wp_tabs_layout pro-preset-style',
						'title'      => __( 'Tabs Layout', 'wp-expand-tabs-free' ),
						'sanitize'   => 'sanitize_text_field',
						'title_help' => sprintf(
							'<div class="wptabspro-info-label">%s</div><div class="wptabspro-short-content">%s</div><a class="wptabspro-open-docs" href="https://wptabs.com/docs/tabs-settings/" target="_blank">%s</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/tabs-layout/" target="_blank">%s</a>',
							__( 'Tabs Layout', 'wp-expand-tabs-free' ),
							__( 'Choose a layout from five individual layout styles to customize how your tabs are displayed in the frontend.', 'wp-expand-tabs-free' ),
							__( 'Open Docs', 'wp-expand-tabs-free' ),
							__( 'Live Demo', 'wp-expand-tabs-free' )
						),
						'options'    => array(
							'horizontal'        => array(
								'image'           => Constants::wp_tabs_url() . 'Admin/Assets/img/tabs-layout/horizontal-top.svg',
								'option_name'     => __( 'Horizontal Top', 'wp-expand-tabs-free' ),
								'class'           => 'free-feature',
								'option_demo_url' => 'https://wptabs.com/horizontal-tabs/',
							),
							'horizontal-bottom' => array(
								'image'           => Constants::wp_tabs_url() . 'Admin/Assets/img/tabs-layout/horizontal-bottom.svg',
								'option_name'     => __( 'Horizontal Bottom', 'wp-expand-tabs-free' ),
								'class'           => 'free-feature',
								'option_demo_url' => 'https://wptabs.com/horizontal-tabs/',
							),
							'vertical-right'    => array(
								'image'           => Constants::wp_tabs_url() . 'Admin/Assets/img/tabs-layout/vertical-right.svg',
								'option_name'     => __( 'Vertical Right', 'wp-expand-tabs-free' ),
								'option_demo_url' => 'https://wptabs.com/vertical-tabs/#vertical-right',
							),
							'vertical'          => array(
								'image'           => Constants::wp_tabs_url() . 'Admin/Assets/img/tabs-layout/vertical-left.svg',
								'option_name'     => __( 'Vertical Left', 'wp-expand-tabs-free' ),
								'class'           => 'pro-feature',
								'option_demo_url' => 'https://demo.wptabs.com/vertical-tabs/',
							),
							'tabs-carousel'     => array(
								'image'           => Constants::wp_tabs_url() . 'Admin/Assets/img/tabs-layout/tabs-carousel.svg',
								'option_name'     => __( 'Tabs Carousel', 'wp-expand-tabs-free' ),
								'class'           => 'pro-feature',
								'option_demo_url' => 'https://wptabs.com/responsive-scrollable-tabs-2/',
							),
						),
						'radio'      => true,
						'default'    => 'horizontal',
					),
					array(
						'id'         => 'sptpro_tabs_horizontal_alignment',
						'type'       => 'image_select',
						'class'      => 'sptpro_tabs_horizontal_alignment',
						'title'      => __( 'Tabs Alignment', 'wp-expand-tabs-free' ),
						'sanitize'   => 'sanitize_text_field',
						'title_help' => sprintf(
							'<div class="wptabspro-info-label">%s</div><div class="wptabspro-short-content">%s</div><a class="wptabspro-open-docs" href="https://wptabs.com/docs/tabs-settings/" target="_blank">%s</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/tabs-position-alignment/" target="_blank">%s</a>',
							__( 'Tabs Alignment', 'wp-expand-tabs-free' ),
							__( 'Choose where you want your tabs to appear – at the top, right, bottom, or left of your content, allowing you to customize their position to best suit your layout and design.', 'wp-expand-tabs-free' ),
							__( 'Open Docs', 'wp-expand-tabs-free' ),
							__( 'Live Demo', 'wp-expand-tabs-free' )
						),
						'options'    => array(
							'tab-horizontal-alignment-left'      => array(
								'image'       => Constants::wp_tabs_url() . 'Admin/Assets/img/tabs-alignment/horizontal-top/horizontal-left.svg',
								'option_name' => __( 'Left', 'wp-expand-tabs-free' ),
								'class'       => 'free-feature',
							),
							'tab-horizontal-alignment-right'     => array(
								'image'       => Constants::wp_tabs_url() . 'Admin/Assets/img/tabs-alignment/horizontal-top/horizontal-right.svg',
								'option_name' => __( 'Right', 'wp-expand-tabs-free' ),
								'class'       => 'free-feature',
							),
							'tab-horizontal-alignment-center'    => array(
								'image'       => Constants::wp_tabs_url() . 'Admin/Assets/img/tabs-alignment/horizontal-top/horizontal-center.svg',
								'option_name' => __( 'Center', 'wp-expand-tabs-free' ),
								'class'       => 'free-feature',
							),
							'tab-horizontal-alignment-justified' => array(
								'image'       => Constants::wp_tabs_url() . 'Admin/Assets/img/tabs-alignment/horizontal-top/horizontal-justified.svg',
								'option_name' => __( 'Justified', 'wp-expand-tabs-free' ),
								'class'       => 'free-feature',
							),
						),
						'default'    => 'tab-horizontal-alignment-left',
						'dependency' => array( 'sptpro_tabs_layout', '!=', 'vertical-right', true ),
					),
					array(
						'id'         => 'sptpro_tabs_vertical_alignment',
						'type'       => 'image_select',
						'class'      => 'sptpro_tabs_vertical_alignment',
						'title'      => __( 'Tabs Alignment', 'wp-expand-tabs-free' ),
						'title_help' => sprintf(
							'<div class="wptabspro-info-label">%s</div><div class="wptabspro-short-content">%s</div><a class="wptabspro-open-docs" href="https://wptabs.com/docs/tabs-settings/" target="_blank">%s</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/tabs-position-alignment/" target="_blank">%s</a>',
							__( 'Tabs Alignment', 'wp-expand-tabs-free' ),
							__( 'Choose where you want your tabs to appear – at the top, right, bottom, or left of your content, allowing you to customize their position to best suit your layout and design.', 'wp-expand-tabs-free' ),
							__( 'Open Docs', 'wp-expand-tabs-free' ),
							__( 'Live Demo', 'wp-expand-tabs-free' )
						),
						'options'    => array(
							'tab-vertical-alignment-top' => array(
								'image'       => Constants::wp_tabs_url() . 'Admin/Assets/img/tabs-alignment/vertical-right/vertical-top.svg',
								'option_name' => __( 'Top', 'wp-expand-tabs-free' ),
							),
							'tab-vertical-alignment-middle' => array(
								'image'       => Constants::wp_tabs_url() . 'Admin/Assets/img/tabs-alignment/vertical-right/vertical-center.svg',
								'option_name' => __( 'Center', 'wp-expand-tabs-free' ),
							),
							'tab-vertical-alignment-bottom' => array(
								'image'       => Constants::wp_tabs_url() . 'Admin/Assets/img/tabs-alignment/vertical-right/vertical-bottom.svg',
								'option_name' => __( 'Bottom', 'wp-expand-tabs-free' ),
							),
							'tab-vertical-alignment-justified' => array(
								'image'       => Constants::wp_tabs_url() . 'Admin/Assets/img/tabs-alignment/vertical-right/vertical-justified.svg',
								'option_name' => __( 'Justified', 'wp-expand-tabs-free' ),
							),
						),
						'default'    => 'tab-vertical-alignment-top',
						'dependency' => array( 'sptpro_tabs_layout', '==', 'vertical-right', true ),
					),
					array(
						'id'         => 'sptpro_tabs_activator_event',
						'type'       => 'radio',
						'class'      => 'only_for_pro_event',
						'title'      => __( 'Activator Event', 'wp-expand-tabs-free' ),
						'sanitize'   => 'sanitize_text_field',
						'title_help' => sprintf(
							'<div class="wptabspro-info-label">%s</div><div class="wptabspro-short-content">%s</div><a class="wptabspro-open-docs" href="https://wptabs.com/docs/tabs-settings/" target="_blank">%s</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/activator-events/" target="_blank">%s</a>',
							__( 'Activator Event', 'wp-expand-tabs-free' ),
							__( 'Set an event to switch between tabs with AutoPlay (Pro), On Click, or Mouse Hover.', 'wp-expand-tabs-free' ),
							__( 'Open Docs', 'wp-expand-tabs-free' ),
							__( 'Live Demo', 'wp-expand-tabs-free' )
						),
						'options'    => array(
							'tabs-activator-event-click' => __( 'On Click', 'wp-expand-tabs-free' ),
							'tabs-activator-event-hover' => __( 'Mouseover', 'wp-expand-tabs-free' ),
						),
						'default'    => 'tabs-activator-event-click',
					),
					array(
						'id'              => 'sptpro_margin_between_tabs',
						'type'            => 'spacing',
						'title'           => __( 'Space Between Tabs', 'wp-expand-tabs-free' ),
						'title_help'      => '<div class="wptabspro-img-tag"><img src="' . Constants::cs_framework_assets() . 'images/help-visuals/margin-between-tabs.svg" alt="' . __( 'Margin between Tabs', 'wp-expand-tabs-free' ) . '"></div><div class="wptabspro-info-label">' . __( 'Margin between Tabs', 'wp-expand-tabs-free' ) . '</div>',
						'all'             => true,
						'all_icon'        => '<i class="fa fa-arrows-h"></i>',
						'all_placeholder' => 'margin',
						'sanitize'        => 'wptabspro_sanitize_number_array_field',
						'default'         => array(
							'all' => '10',
						),
						'units'           => array(
							'px',
						),
					),
					array(
						'id'         => 'sptpro_anchor_linking',
						'type'       => 'switcher',
						'title'      => __( 'Anchor Link for Tabs', 'wp-expand-tabs-free' ),
						'text_on'    => __( 'Enabled', 'wp-expand-tabs-free' ),
						'text_off'   => __( 'Disabled', 'wp-expand-tabs-free' ),
						'text_width' => 94,
						'default'    => true,
					),
					array(
						'id'         => 'sptpro_tab_link_type',
						'type'       => 'button_set',
						'title'      => __( 'Link Type ', 'wp-expand-tabs-free' ),
						'title_help' => sprintf(
							'<div class="wptabspro-info-label">%s</div>
							<div class="wptabspro-short-content">%s <br/>%s</div>',
							__( 'Link Type', 'wp-expand-tabs-free' ),
							__( 'Tab ID - Adds the tab\'s unique ID to the URL. i.e. https://example.com/#tab-id', 'wp-expand-tabs-free' ),
							__( 'Tab Title - Adds a URL-friendly version of the tab title (slug) to the URL. i.e. https://example.com/#tab-title-xx', 'wp-expand-tabs-free' )
						),
						'options'    => array(
							'tab_id'    => __( 'Tab ID', 'wp-expand-tabs-free' ),
							'tab_title' => __( 'Tab Title', 'wp-expand-tabs-free' ),
						),
						'default'    => 'tab_id',
						'dependency' => array( 'sptpro_anchor_linking', '==', 'true', true ),
					),
					array(
						'id'         => 'sptpro_preloader',
						'type'       => 'switcher',
						'title'      => __( 'Preloader', 'wp-expand-tabs-free' ),
						'text_on'    => __( 'Enabled', 'wp-expand-tabs-free' ),
						'text_off'   => __( 'Disabled', 'wp-expand-tabs-free' ),
						'text_width' => 94,
						'default'    => true,
					),

					array(
						'id'         => 'sptpro_fixed_height',
						'type'       => 'button_set',
						'class'      => 'only_pro_fixed_height',
						'ignore_db'  => true,
						'title'      => __( 'Content Height', 'wp-expand-tabs-free' ),
						'title_help' => '<div class="wptabspro-img-tag"><img src="' . Constants::cs_framework_assets() . 'images/help-visuals/content-height.svg" alt="' . __( 'Content Height', 'wp-expand-tabs-free' ) . '"></div><div class="wptabspro-info-label"> ' . __( 'Content Height', 'wp-expand-tabs-free' ) . '</div><a class="wptabspro-open-docs" href="https://wptabs.com/docs/how-to-set-tabs-content-height/" target="_blank">' . __( 'Open Docs', 'wp-expand-tabs-free' ) . '</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/tabs-fixed-content-height/" target="_blank">' . __( 'Live Demo', 'wp-expand-tabs-free' ) . '</a>',
						'options'    => array(
							'auto'   => __( 'Auto', 'wp-expand-tabs-free' ),
							'custom' => __( 'Custom', 'wp-expand-tabs-free' ),
						),
						'default'    => 'auto',
					),
					array(
						'id'         => 'sptpro_tab_opened',
						'type'       => 'spinner',
						'ignore_db'  => true,
						'class'      => 'only_pro_spinner',
						'title'      => __( 'Initial Open Tab', 'wp-expand-tabs-free' ),
						'title_help' => '<div class="wptabspro-img-tag"><img src="' . Constants::cs_framework_assets() . 'images/help-visuals/initial-tab-opened.svg" alt="' . __( 'Initial Open Tab', 'wp-expand-tabs-free' ) . '"></div><div class="wptabspro-info-label">' . __( 'Initial Open Tab', 'wp-expand-tabs-free' ) . '</div>',
						'sanitize'   => 'wptabspro_sanitize_number_field',
						'min'        => 1,
						'default'    => 1,
					),
				), // Fields array end.
			)
		); // End of tabs settings.
	}
}
