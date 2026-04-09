<?php
/**
 * General settings of product tabs.
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
 * Class General
 */
class General {
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
								'image'           => Constants::product_tabs_url() . 'Admin/Assets/img/product-tabs-layout/horizontal.svg',
								'option_name'     => __( 'Horizontal', 'wp-expand-tabs-free' ),
								'option_demo_url' => 'https://demo.wptabs.com/product/nike-sportswear-jdi/',
							),
							'vertical-right'  => array(
								'image'           => Constants::product_tabs_url() . 'Admin/Assets/img/product-tabs-layout/vertical-right.svg',
								'option_name'     => __( 'Vertical Right', 'wp-expand-tabs-free' ),
								'option_demo_url' => 'https://demo.wptabs.com/vertical-right-product-tabs/product/adjustable-dumbbell-set/',
							),
							'vertical'        => array(
								'image'           => Constants::product_tabs_url() . 'Admin/Assets/img/product-tabs-layout/vertical-left.svg',
								'option_name'     => __( 'Vertical Left', 'wp-expand-tabs-free' ),
								'class'           => 'pro-feature',
								'option_demo_url' => 'https://demo.wptabs.com/vertical-left-product-tabs/product/meta-quest-3-vr-headset/',
							),

							'tabs-accordion'  => array(
								'image'           => Constants::product_tabs_url() . 'Admin/Assets/img/product-tabs-layout/accordion.svg',
								'option_name'     => __( 'Accordion', 'wp-expand-tabs-free' ),
								'class'           => 'pro-feature',
								'option_demo_url' => 'https://demo.wptabs.com/accordion-product-tabs/product/hot-air-balloon-flight/',
							),
							'below-summary'   => array(
								'image'           => Constants::product_tabs_url() . 'Admin/Assets/img/product-tabs-layout/below-summary.svg',
								'option_name'     => __( 'Below Summary', 'wp-expand-tabs-free' ),
								'class'           => 'pro-feature',
								'option_demo_url' => 'https://demo.wptabs.com/below-summary-product-tabs/product/the-alchemist/',
							),
							'accordion-below' => array(
								'image'           => Constants::product_tabs_url() . 'Admin/Assets/img/product-tabs-layout/accordion-below.svg',
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
								'image'       => Constants::product_tabs_url() . 'Admin/Assets/img/product-tabs-alignment/tab_left.svg',
								'option_name' => __( 'Left', 'wp-expand-tabs-free' ),
								'class'       => 'free-feature',
							),
							'center'        => array(
								'image'       => Constants::product_tabs_url() . 'Admin/Assets/img/product-tabs-alignment/tab_center.svg',
								'option_name' => __( 'Center', 'wp-expand-tabs-free' ),
								'class'       => 'free-feature',
							),
							'right'         => array(
								'image'       => Constants::product_tabs_url() . 'Admin/Assets/img/product-tabs-alignment/tab_right.svg',
								'option_name' => __( 'Right', 'wp-expand-tabs-free' ),
								'class'       => 'free-feature',
							),
							'space-between' => array(
								'image'       => Constants::product_tabs_url() . 'Admin/Assets/img/product-tabs-alignment/justified.svg',
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
						'title_help' => '<div class="wptabspro-img-tag"><img src="' . Constants::cs_framework_assets() . 'images/help-visuals/content-height.svg" alt="Content Height"></div><div class="wptabspro-info-label"> ' . __( 'Content Height', 'wp-expand-tabs-free' ) . '</div><a class="wptabspro-open-docs" href="https://wptabs.com/docs/style/display-settings/" target="_blank">Open Docs</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/tabs-fixed-content-height/" target="_blank">Live Demo</a>',

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
						'title_help' => '<div class="wptabspro-img-tag"><img src="' . Constants::cs_framework_assets() . 'images/help-visuals/initial-tab-opened.svg" alt="' . __( 'Initial Open Tab', 'wp-expand-tabs-free' ) . '"></div><div class="wptabspro-info-label">' . __( 'Initial Open Tab', 'wp-expand-tabs-free' ) . '</div>',
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
							__( 'Tab Slug with ID – Combines slug and ID (e.g., #overview-12).', 'wp-expand-tabs-free' )
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
							'<a class="wcgs-open-live-demo" href="' . esc_url( Constants::PRO_LINK ) . '" target="_blank"><strong>',
							'</strong></a>'
						),
					),
				),
			)
		);
	}
}
