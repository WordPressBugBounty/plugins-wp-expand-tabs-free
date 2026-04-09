<?php
/**
 * The Tabs General section.
 *
 * @link       https://shapedplugin.com/
 * @since      3.2.0
 *
 * @package    ShapedPlugin\SmartTabsFree
 * @subpackage Modules\WPTabs\Admin\Configs\Generator
 */

namespace ShapedPlugin\SmartTabsFree\Modules\WPTabs\Admin\Configs\Generator;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

use ShapedPlugin\SmartTabsFree\Core\Constants;
use ShapedPlugin\SmartTabsFree\Lib\CSFramework\Classes\SP_CSFramework;

/**
 * Class Display
 */
class Display {
	/**
	 * Register Display section.
	 *
	 * @param string $prefix The prefix for the section.
	 */
	public static function section( $prefix ) {

		SP_CSFramework::createSection(
			$prefix,
			array(
				'title'  => __( 'Display Settings', 'wp-expand-tabs-free' ),
				'icon'   => 'fa icon-display',
				'fields' => array(
					array(
						'type' => 'tabbed',
						'tabs' => array(
							array(
								'title'  => __( 'Tabs Name & Description', 'wp-expand-tabs-free' ),
								'icon'   => '<i class="fa icon-title_des"></i>',
								'fields' => array(
									array(
										'id'         => 'sptpro_section_title',
										'type'       => 'switcher',
										'title'      => __( 'Tabs Section Title', 'wp-expand-tabs-free' ),
										'default'    => true,
										'text_on'    => __( 'Show', 'wp-expand-tabs-free' ),
										'text_off'   => __( 'Hide', 'wp-expand-tabs-free' ),
										'text_width' => 75,
									),
									array(
										'id'      => 'sptpro_title_heading_tag',
										'type'    => 'select',
										'class'   => 'vertical-gap',
										'title'   => __( 'Tab Name HTML Tag', 'wp-expand-tabs-free' ),
										'options' => array(
											'H1' => 'H1',
											'H2' => 'H2',
											'H3' => 'H3',
											'H4' => 'H4',
											'H5' => 'H5',
											'H6' => 'H6',
										),
										'default' => 'H4',
										'radio'   => true,
									),
									array(
										'id'         => 'sptpro_tabs_bg_color_type',
										'type'       => 'button_set',
										'class'      => 'sptpro_tabs_bg_color_type',
										'title'      => __( 'Tab Name BG Color Type', 'wp-expand-tabs-free' ),
										'title_help' => '<div class="wptabspro-img-tag"><img src="' . Constants::cs_framework_assets() . 'images/help-visuals/title-background-color-type.svg" alt="' . __( 'Title Background Color Type', 'wp-expand-tabs-free' ) . '"></div><div class="wptabspro-info-label">' . __( 'Title Background Color Type', 'wp-expand-tabs-free' ) . '</div><a class="wptabspro-open-docs" href="https://docs.shapedplugin.com/docs/wp-expand-tabs-free/configurations/how-to-set-tabs-title-background-color/" target="_blank">' . __( 'Open Docs', 'wp-expand-tabs-free' ) . '</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/title-background-color/" target="_blank">' . __( 'Live Demo', 'wp-expand-tabs-free' ) . '</a>',
										'options'    => array(
											'solid'    => __( 'Solid', 'wp-expand-tabs-free' ),
											'gradient' => __( 'Gradient', 'wp-expand-tabs-free' ),
										),
										'default'    => 'solid',
									),
									array(
										'id'         => 'sptpro_title_bg_color',
										'type'       => 'color_group',
										'title'      => __( 'Tab Name BG Color', 'wp-expand-tabs-free' ),
										'sanitize'   => 'wptabspro_sanitize_color_group_field',
										'options'    => array(
											'title-bg-color'       => __( 'Background', 'wp-expand-tabs-free' ),
											'title-active-bg-color' => __( 'Active BG', 'wp-expand-tabs-free' ),
											'title-hover-bg-color' => __( 'Hover BG', 'wp-expand-tabs-free' ),
										),
										'default'    => array(
											'title-bg-color'       => '#eee',
											'title-active-bg-color' => '#fff',
											'title-hover-bg-color' => '#fff',
										),
										'dependency' => array( 'sptpro_tabs_bg_color_type', '==', 'solid' ),
									),
									array(
										'id'               => 'sptpro_tabs_bg_gradient_color',
										'type'             => 'background',
										'title'            => __( 'Gradient Color', 'wp-expand-tabs-free' ),
										'background_gradient' => true,
										'background_color' => true,
										'background_image' => false,
										'background_position' => false,
										'background_repeat' => false,
										'background_attachment' => false,
										'background_size'  => false,
										'default'          => array(
											'background-color' => '#fff',
											'background-gradient-color' => '#eee',
											'background-gradient-direction' => '135deg',
										),
										'dependency'       => array( 'sptpro_tabs_bg_color_type', '==', 'gradient' ),
									),
									array(
										'id'         => 'sptpro_title_padding',
										'type'       => 'spacing',
										'title'      => __( 'Tab Name Padding', 'wp-expand-tabs-free' ),
										'sanitize'   => 'wptabspro_sanitize_number_array_field',
										'title_help' => '<div class="wptabspro-img-tag"><img src="' . Constants::cs_framework_assets() . 'images/help-visuals/title-padding.svg" alt="' . __( 'Title Padding', 'wp-expand-tabs-free' ) . '"></div><div class="wptabspro-info-label">' . __( 'Title Padding', 'wp-expand-tabs-free' ) . '</div>',
										'units'      => array( 'px' ),
										'default'    => array(
											'left'   => '15',
											'top'    => '15',
											'bottom' => '15',
											'right'  => '15',
										),
									),
									array(
										'id'            => 'sptpro_tabs_border',
										'type'          => 'border',
										'class'         => 'sptpro_tabs_border',
										'title'         => __( 'Border', 'wp-expand-tabs-free' ),
										'all'           => true,
										'border_radius' => true,
										'sanitize'      => 'wptabspro_sanitize_border_field',
										'default'       => array(
											'all'   => 1,
											'style' => 'solid',
											'color' => '#cccccc',
											'border_radius' => '2',
										),
									),
									array(
										'id'      => 'sptpro_desc_bg_color',
										'type'    => 'color',
										'title'   => __( 'Description Background Color', 'wp-expand-tabs-free' ),
										'default' => '#ffffff',
									),
									array(
										'id'         => 'sptpro_desc_padding',
										'type'       => 'spacing',
										'title'      => __( 'Description Padding', 'wp-expand-tabs-free' ),
										'title_help' => '<div class="wptabspro-img-tag"><img src="' . Constants::cs_framework_assets() . 'images/help-visuals/description-padding.svg" alt="' . __( 'Description Padding', 'wp-expand-tabs-free' ) . '"></div><div class="wptabspro-info-label"> ' . __( 'Description Padding', 'wp-expand-tabs-free' ) . '</div>',
										'units'      => array( 'px' ),
										'default'    => array(
											'left'   => '30',
											'top'    => '20',
											'bottom' => '20',
											'right'  => '20',
										),
									),
									array(
										'id'      => 'sptpro_desc_border',
										'type'    => 'border',
										'title'   => __( 'Description Border', 'wp-expand-tabs-free' ),
										'all'     => true,
										'style'   => true,
										'default' => array(
											'all'   => 1,
											'style' => 'solid',
											'color' => '#cccccc',
										),
									),
									array(
										'type'    => 'notice',
										'class'   => 'middle_notice smart-tabs-notice',
										'content' => sprintf(
											/* translators: 1: start bold tag 2: close bold tag 3: start link and bold tag, 4: close tag. */
											__( 'Unlock additional settings for Tab Name & Description — %1$sUpgrade to Pro!%2$s', 'wp-expand-tabs-free' ),
											'<a href="' . esc_url( Constants::PRO_LINK ) . '" target="_blank"><b>',
											'</b></a>'
										),
									),
									array(
										'id'         => 'sptpro_showhide_subtitle',
										'type'       => 'switcher',
										'ignore_db'  => true,
										'class'      => 'only_pro_switcher vertical-gap',
										'title'      => __( 'Tabs Subtitle', 'wp-expand-tabs-free' ),
										'default'    => false,
										'text_on'    => __( 'Show', 'wp-expand-tabs-free' ),
										'text_off'   => __( 'Hide', 'wp-expand-tabs-free' ),
										'text_width' => 75,
									),
									array(
										'id'         => 'sptpro_active_indicator_arrow',
										'type'       => 'switcher',
										'ignore_db'  => true,
										'class'      => 'only_pro_switcher vertical-gap',
										'title'      => __( 'Active Tab Indicator Arrow', 'wp-expand-tabs-free' ),
										'title_help' => '<div class="wptabspro-img-tag"><img src="' . Constants::cs_framework_assets() . 'images/help-visuals/active-tab-indicator-arrow.svg" alt="' . __( 'Active Tab Indicator Arrow (Pro)', 'wp-expand-tabs-free' ) . '"></div><div class="wptabspro-info-label">' . __( 'Active Tab Indicator Arrow (Pro)', 'wp-expand-tabs-free' ) . '</div><a class="wptabspro-open-docs" href="https://wptabs.com/docs/how-to-configure-an-active-tab-indicator-arrow/" target="_blank">' . __( 'Open Docs', 'wp-expand-tabs-free' ) . '</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/active-tab-indicator-arrow/" target="_blank">' . __( 'Live Demo', 'wp-expand-tabs-free' ) . '</a>',
										'default'    => false,
										'text_on'    => __( 'Show', 'wp-expand-tabs-free' ),
										'text_off'   => __( 'Hide', 'wp-expand-tabs-free' ),
										'text_width' => 75,
									),
									array(
										'id'              => 'sptpro_margin_between_tabs_and_desc',
										'type'            => 'spacing',
										'bottom_icon'     => '<i class="fa fa-arrows-v"></i>',
										'ignore_db'       => true,
										'class'           => 'only_pro_spinner',
										'title'           => __( 'Space Between Tabs and Description', 'wp-expand-tabs-free' ),
										'title_help'      => '<div class="wptabspro-img-tag"><img src="' . Constants::cs_framework_assets() . 'images/help-visuals/margin-between-tabs-and-description.svg" alt="' . __( 'Space Between Tabs and Description', 'wp-expand-tabs-free' ) . '"></div>',
										'all_placeholder' => 'margin',
										'bottom_text'     => '',
										'top'             => false,
										'left'            => false,
										'bottom'          => true,
										'right'           => false,
										'default'         => array(
											'bottom' => '0',
										),
										'units'           => array(
											'px',
										),
									),
									array(
										'id'         => 'sptpro_active_tab_style_horizontal',
										'type'       => 'image_select',
										'ignore_db'  => true,
										'class'      => 'only_pro_tabs_icon_section common-size',
										'title'      => __( 'Active Tab Style', 'wp-expand-tabs-free' ),
										'title_help' => sprintf(
											'<div class="wptabspro-info-label">%s</div><div class="wptabspro-short-content">%s</div><a class="wptabspro-open-docs" href="https://wptabs.com/docs/how-to-set-and-customize-active-tab-style/" target="_blank">%s</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/active-tab-style/" target="_blank">%s</a>',
											__( 'Active Tab Style (Pro)', 'wp-expand-tabs-free' ),
											__( 'Choose how the currently selected tab looks. You can add a line to the Top or Bottom of tab\'s title to make it stand out.', 'wp-expand-tabs-free' ),
											__( 'Open Docs', 'wp-expand-tabs-free' ),
											__( 'Live Demo', 'wp-expand-tabs-free' )
										),
										'options'    => array(
											'horizontal-active-tab-normal'      => array(
												'image' => Constants::wp_tabs_url() . 'Admin/Assets/img/active-tab-style/horizontal-active-tab-normal.svg',
												'option_name' => __( 'Normal', 'wp-expand-tabs-free' ),
											),
											'horizontal-active-tab-top-line'    => array(
												'image' => Constants::wp_tabs_url() . 'Admin/Assets/img/active-tab-style/horizontal-active-tab-top-line.svg',
												'option_name' => __( 'Top', 'wp-expand-tabs-free' ),
											),
											'horizontal-active-tab-bottom-line' => array(
												'image' => Constants::wp_tabs_url() . 'Admin/Assets/img/active-tab-style/horizontal-active-tab-bottom-line.svg',
												'option_name' => __( 'Bottom', 'wp-expand-tabs-free' ),
											),
										),
										'radio'      => true,
										'default'    => 'horizontal-active-tab-normal',
									),
									array(
										'id'         => 'sptpro_flat_tab_style_horizontal',
										'type'       => 'image_select',
										'ignore_db'  => true,
										'class'      => 'only_pro_tabs_icon_section common-size',
										'title'      => __( 'Flat Tab Style', 'wp-expand-tabs-free' ),
										'title_help' => sprintf(
											'<div class="wptabspro-info-label">%s</div><div class="wptabspro-short-content">%s</div><a class="wptabspro-open-docs" href="https://wptabs.com/docs/how-to-make-a-tab-flat-style/" target="_blank">%s</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/flat-contained-tabs/" target="_blank">%s</a>',
											__( 'Flat Tab Style (Pro)', 'wp-expand-tabs-free' ),
											__( 'Select the Underline option to enhance your tabs with flat underline positioned below the tab navigation for a clean and modern look.', 'wp-expand-tabs-free' ),
											__( 'Open Docs', 'wp-expand-tabs-free' ),
											__( 'Live Demo', 'wp-expand-tabs-free' )
										),
										'options'    => array(
											'horizontal-flat-tab-normal'    => array(
												'image' => Constants::wp_tabs_url() . 'Admin/Assets/img/flat-tab-style/horizontal-flat-tab-normal.svg',
												'option_name' => __( 'Normal', 'wp-expand-tabs-free' ),
											),
											'horizontal-flat-tab-underline' => array(
												'image' => Constants::wp_tabs_url() . 'Admin/Assets/img/flat-tab-style/horizontal-flat-tab-underline.svg',
												'option_name' => __( 'Underline', 'wp-expand-tabs-free' ),
											),
										),
										'radio'      => true,
										'default'    => 'horizontal-flat-tab-normal',
									),
								),
							),
							array(
								'title'  => __( 'Responsive Screen', 'wp-expand-tabs-free' ),
								'icon'   => '<i class="fa icon-responsive"></i>',
								'fields' => array(
									array(
										'id'              => 'sptpro_set_small_screen',
										'type'            => 'spacing',
										'title'           => __( 'When Screen Width is Less Than', 'wp-expand-tabs-free' ),
										'all'             => true,
										'all_icon'        => '<i class="fa fa-arrows-h"></i>',
										'all_placeholder' => 'margin',
										'sanitize'        => 'wptabspro_sanitize_number_array_field',
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
											'<div class="wptabspro-info-label">%s</div><div class="wptabspro-short-content">%s</div><a class="wptabspro-open-docs" href="https://wptabs.com/docs/how-to-configure-tabs-mood-on-a-small-screen/" target="_blank">%s</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/docs/style/display-settings/" target="_blank">%s</a>',
											__( 'Tabs Mode on Small Screen', 'wp-expand-tabs-free' ),
											__( 'Choose how your tabs behave on small screens, such as mobile devices. You can select "Full Width" to maintain the current layout or "Accordion" to switch to a collapsible format, ensuring the best user experience on mobile.', 'wp-expand-tabs-free' ),
											__( 'Open Docs', 'wp-expand-tabs-free' ),
											__( 'Live Demo', 'wp-expand-tabs-free' )
										),
										'options'    => array(
											'full_widht' => __( 'Full Width', 'wp-expand-tabs-free' ),
											'accordion_mode' => __( 'Accordion', 'wp-expand-tabs-free' ),
										),
										'default'    => 'full_widht',
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

								),
							),
							array(
								'title'  => __( 'Description Animation', 'wp-expand-tabs-free' ),
								'icon'   => '<i class="fa icon-animation"></i>',
								'fields' => array(
									array(
										'id'         => 'sptpro_tabs_animation',
										'type'       => 'switcher',
										'title'      => __( 'Animation', 'wp-expand-tabs-free' ),
										'title_help' => sprintf(
											'<div class="wptabspro-info-label">%s</div><div class="wptabspro-short-content">%s</div><a class="wptabspro-open-docs" href="https://wptabs.com/docs/how-to-configure-tab-animation/" target="_blank">%s</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/tabs-animation/" target="_blank">%s</a>',
											__( 'Animation', 'wp-expand-tabs-free' ),
											__( 'You can select animation to enhance your tab with over 50+ fascinating animations to add dynamic and eye-catching effects to your content.', 'wp-expand-tabs-free' ),
											__( 'Open Docs', 'wp-expand-tabs-free' ),
											__( 'Live Demo', 'wp-expand-tabs-free' )
										),
										'text_on'    => __( 'Enabled', 'wp-expand-tabs-free' ),
										'text_off'   => __( 'Disabled', 'wp-expand-tabs-free' ),
										'text_width' => 94,
										'default'    => true,
									),
									array(
										'id'         => 'sptpro_tabs_animation_type',
										'type'       => 'select',
										'class'      => 'sptpro-tabs-pro-select',
										'title'      => __( 'Animation Style', 'wp-expand-tabs-free' ),
										'sanitize'   => 'sanitize_text_field',
										'options'    => array(
											'fadeIn'       => __( 'fadeIn', 'wp-expand-tabs-free' ),
											'fadeInDown'   => __( 'fadeInDown', 'wp-expand-tabs-free' ),
											'fadeInLeft'   => __( 'fadeInLeft (Pro)', 'wp-expand-tabs-free' ),
											'fadeInRight'  => __( 'fadeInRight (Pro)', 'wp-expand-tabs-free' ),
											'fadeInUp'     => __( 'fadeInUp (Pro)', 'wp-expand-tabs-free' ),
											'fadeInDownBig' => __( 'fadeInDownBig (Pro)', 'wp-expand-tabs-free' ),
											'fadeInLeftBig' => __( 'fadeInLeftBig (Pro)', 'wp-expand-tabs-free' ),
											'fadeInRightBig' => __( 'fadeInRightBig (Pro)', 'wp-expand-tabs-free' ),
											'fadeInUpBig'  => __( 'fadeInUpBig (Pro)', 'wp-expand-tabs-free' ),
											'zoomIn'       => __( 'zoomIn (Pro)', 'wp-expand-tabs-free' ),
											'zoomInDown'   => __( 'zoomInDown (Pro)', 'wp-expand-tabs-free' ),
											'zoomInLeft'   => __( 'zoomInLeft (Pro)', 'wp-expand-tabs-free' ),
											'zoomInRight'  => __( 'zoomInRight (Pro)', 'wp-expand-tabs-free' ),
											'zoomInUp'     => __( 'zoomInUp (Pro)', 'wp-expand-tabs-free' ),
											'zoomOut'      => __( 'zoomOut (Pro)', 'wp-expand-tabs-free' ),
											'slideInDown'  => __( 'slideInDown (Pro)', 'wp-expand-tabs-free' ),
											'slideInLeft'  => __( 'slideInLeft (Pro)', 'wp-expand-tabs-free' ),
											'slideInRight' => __( 'slideInRight (Pro)', 'wp-expand-tabs-free' ),
											'slideInUp'    => __( 'slideInUp (Pro)', 'wp-expand-tabs-free' ),
											'flip'         => __( 'flip (Pro)', 'wp-expand-tabs-free' ),
											'flipInX'      => __( 'flipInX (Pro)', 'wp-expand-tabs-free' ),
											'flipInY'      => __( 'flipInY (Pro)', 'wp-expand-tabs-free' ),
											'bounce'       => __( 'bounce (Pro)', 'wp-expand-tabs-free' ),
											'bounceIn'     => __( 'bounceIn (Pro)', 'wp-expand-tabs-free' ),
											'bounceInLeft' => __( 'bounceInLeft (Pro)', 'wp-expand-tabs-free' ),
											'bounceInRight' => __( 'bounceInRight (Pro)', 'wp-expand-tabs-free' ),
											'bounceInUp'   => __( 'bounceInUp (Pro)', 'wp-expand-tabs-free' ),
											'bounceInDown' => __( 'bounceInDown (Pro)', 'wp-expand-tabs-free' ),
											'rotateIn'     => __( 'rotateIn (Pro)', 'wp-expand-tabs-free' ),
											'rotateInDownLeft' => __( 'rotateInDownLeft (Pro)', 'wp-expand-tabs-free' ),
											'rotateInDownRight' => __( 'rotateInDownRight (Pro)', 'wp-expand-tabs-free' ),
											'rotateInUpLeft' => __( 'rotateInUpLeft (Pro)', 'wp-expand-tabs-free' ),
											'rotateInUpRight' => __( 'rotateInUpRight (Pro)', 'wp-expand-tabs-free' ),
											'backInDown'   => __( 'backInDown (Pro)', 'wp-expand-tabs-free' ),
											'backInLeft'   => __( 'backInLeft (Pro)', 'wp-expand-tabs-free' ),
											'flash'        => __( 'flash (Pro)', 'wp-expand-tabs-free' ),
											'pulse'        => __( 'pulse (Pro)', 'wp-expand-tabs-free' ),
											'shake'        => __( 'shake (Pro)', 'wp-expand-tabs-free' ),
											'swing'        => __( 'swing (Pro)', 'wp-expand-tabs-free' ),
											'tada'         => __( 'tada (Pro)', 'wp-expand-tabs-free' ),
											'wobble'       => __( 'wobble (Pro)', 'wp-expand-tabs-free' ),
											'rubberBand'   => __( 'rubberBand (Pro)', 'wp-expand-tabs-free' ),
											'heartBeat'    => __( 'heartBeat (Pro)', 'wp-expand-tabs-free' ),
											'jello'        => __( 'jello (Pro)', 'wp-expand-tabs-free' ),
											'headShake'    => __( 'headShake (Pro)', 'wp-expand-tabs-free' ),
											'lightSpeedIn' => __( 'lightSpeedIn (Pro)', 'wp-expand-tabs-free' ),
											'jackInTheBox' => __( 'jackInTheBox (Pro)', 'wp-expand-tabs-free' ),
											'rollIn'       => __( 'rollIn (Pro)', 'wp-expand-tabs-free' ),
										),
										'default'    => 'fadeIn',
										'dependency' => array( 'sptpro_tabs_animation', '==', 'true' ),
									),
									array(
										'id'         => 'sptpro_animation_time',
										'type'       => 'spinner',
										'title'      => __( 'Transition Delay', 'wp-expand-tabs-free' ),
										'unit'       => 'ms',
										'min'        => 10,
										'max'        => 100000,
										'default'    => 500,
										'dependency' => array( 'sptpro_tabs_animation', '==', 'true' ),
									),
									array(
										'type'    => 'notice',
										'class'   => 'bottom_notice smart-tabs-notice',
										'content' => sprintf(
											/* translators: 1: start strong tag 2: close strong tag 3: start link and bold tag, 4: close tag. */
											__( 'To unlock %1$s50+ elegant Tabs Animations%2$s settings, %3$sUpgrade to Pro!%4$s', 'wp-expand-tabs-free' ),
											'<strong>',
											'</strong>',
											'<a href="' . esc_url( Constants::PRO_LINK ) . '" target="_blank"><b>',
											'</b></a>'
										),
									),
								),
							),
							array(
								'title'  => __( 'Tabs Icon', 'wp-expand-tabs-free' ),
								'icon'   => '<i class="fa icon-tabs_icon"></i>',
								'fields' => array(
									array(
										'type'      => 'notice',
										'ignore_db' => true,
										'class'     => 'only_pro_notice tab-icon-top-notice smart-tabs-notice',
										'content'   => sprintf(
											/* translators: 1: start link and blod tag, 2: close tag. */
											__( 'To unlock the following essential Tabs Icon options, %1$sUpgrade to Pro!%2$s', 'wp-expand-tabs-free' ),
											'<a href="' . esc_url( Constants::PRO_LINK ) . '" target="_blank"><b>',
											'</b></a>'
										),
									),
									array(
										'id'         => 'sptpro_tabs_icon',
										'type'       => 'switcher',
										'ignore_db'  => true,
										'class'      => 'only_pro_switcher',
										'title'      => __( 'Tabs Icon', 'wp-expand-tabs-free' ),
										'default'    => true,
										'text_on'    => __( 'Show', 'wp-expand-tabs-free' ),
										'text_off'   => __( 'Hide', 'wp-expand-tabs-free' ),
										'text_width' => 75,
									),
									array(
										'id'              => 'sptpro_tab_icon_size',
										'type'            => 'spacing',
										'ignore_db'       => true,
										'class'           => 'only_pro_spinner',
										'title'           => __( 'Icon Size', 'wp-expand-tabs-free' ),
										'all'             => true,
										'all_text'        => false,
										'all_placeholder' => 'size',
										'sanitize'        => 'wptabspro_sanitize_number_array_field',
										'default'         => array(
											'all' => '16',
										),
										'units'           => array(
											'px',
										),
										'dependency'      => array(
											'sptpro_tabs_icon',
											'==',
											'true',
										),
									),
									array(
										'id'              => 'sptpro_icon_space_title',
										'type'            => 'spacing',
										'ignore_db'       => true,
										'class'           => 'only_pro_spinner',
										'title'           => __( 'Space Between Title and Icon', 'wp-expand-tabs-free' ),
										'title_help'      => '<div class="wptabspro-img-tag"><img src="' . Constants::cs_framework_assets() . 'images/help-visuals/space-between-title-and-icon.svg" alt="' . __( 'Space Between Title and Icon', 'wp-expand-tabs-free' ) . '"></div><div class="wptabspro-info-label">' . __( 'Space Between Title and Icon', 'wp-expand-tabs-free' ) . '</div>',
										'all'             => true,
										'all_text'        => false,
										'all_placeholder' => 'size',
										'sanitize'        => 'wptabspro_sanitize_number_array_field',
										'default'         => array(
											'all' => '10',
										),
										'units'           => array(
											'px',
										),
										'dependency'      => array(
											'sptpro_tabs_icon',
											'==',
											'true',
										),
									),
									array(
										'id'         => 'sptpro_tab_icon_color',
										'type'       => 'color_group',
										'ignore_db'  => true,
										'class'      => 'only_pro_tabs_icon_section tab_icon_position',
										'title'      => __( 'Icon Color', 'wp-expand-tabs-free' ),
										'options'    => array(
											'tab-icon-color'       => __( 'Color', 'wp-expand-tabs-free' ),
											'tab-icon-color-active' => __( 'Active Color', 'wp-expand-tabs-free' ),
											'tab-icon-color-hover' => __( 'Hover Color', 'wp-expand-tabs-free' ),
										),
										'default'    => array(
											'tab-icon-color'       => '#444',
											'tab-icon-color-active' => '#444',
											'tab-icon-color-hover' => '#444',
										),
										'dependency' => array(
											'sptpro_tabs_icon',
											'==',
											'true',
										),
									),
									array(
										'id'         => 'sptpro_tab_icon_position',
										'type'       => 'image_select',
										'ignore_db'  => true,
										'class'      => 'only_pro_tabs_icon_section tab_icon_position',
										'title'      => __( 'Icon Position', 'wp-expand-tabs-free' ),
										'title_help' => sprintf(
											'<div class="wptabspro-info-label">%s</div><div class="wptabspro-short-content">%s</div><a class="wptabspro-open-docs" href="https://wptabs.com/docs/display-settings/tabs-icon/" target="_blank">%s</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/tabs-icon-position/" target="_blank">%s</a>',
											__( 'Icon Position (Pro)', 'wp-expand-tabs-free' ),
											__( 'This option allows you to specify the position of icons within your tab interface. You can place icons to the Top, Right, Bottom and Left of tab\'s title.', 'wp-expand-tabs-free' ),
											__( 'Open Docs', 'wp-expand-tabs-free' ),
											__( 'Live Demo', 'wp-expand-tabs-free' )
										),
										'options'    => array(
											'tab-icon-position-left'  => array(
												'image' => Constants::wp_tabs_url() . 'Admin/Assets/img/woo-tab-icons-position/left.svg',
												'option_name' => __( 'Left', 'wp-expand-tabs-free' ),
											),
											'tab-icon-position-top'   => array(
												'image' => Constants::wp_tabs_url() . 'Admin/Assets/img/woo-tab-icons-position/top.svg',
												'option_name' => __( 'Top', 'wp-expand-tabs-free' ),
											),
											'tab-icon-position-right' => array(
												'image' => Constants::wp_tabs_url() . 'Admin/Assets/img/woo-tab-icons-position/right.svg',
												'option_name' => __( 'Right', 'wp-expand-tabs-free' ),
											),
										),
										'radio'      => true,
										'default'    => 'tab-icon-position-left',
									),
								),
							),

							array(
								'title'  => __( 'Image & Video Gallery', 'wp-expand-tabs-free' ),
								'icon'   => '<i class="fa icon-image-and-video-gallery"></i>',
								'fields' => array(
									array(
										'type'      => 'notice',
										'ignore_db' => true,
										'class'     => 'only_pro_notice tab-icon-top-notice smart-tabs-notice',
										'content'   => sprintf(
											/* translators: 1: start link and blod tag, 2: close tag. */
											__( 'Unlock Image & Video Gallery Tabs with lightbox features & more— %1$sUpgrade to Pro!%2$s', 'wp-expand-tabs-free' ),
											'<a href="' . esc_url( Constants::PRO_LINK ) . '" target="_blank"><b>',
											'</b></a>'
										),
									),

									array(
										'id'        => 'content_image_layouts',
										'class'     => 'chosen sp-tabs-space-between only_pro_spinner',
										'type'      => 'select',
										'ignore_db' => true,
										'title'     => __( 'Layout Preset', 'wp-expand-tabs-free' ),
										'options'   => array(
											'grid'    => __( 'Grid', 'wp-expand-tabs-free' ),
											'masonry' => __( 'Masonry', 'wp-expand-tabs-free' ),
										),
										'default'   => 'grid',
									),
									array(
										'id'        => 'content_images_columns',
										'class'     => 'content_images_columns only_pro_spinner',
										'type'      => 'column',
										'ignore_db' => true,
										'title'     => __( 'Columns', 'wp-expand-tabs-free' ),
										'default'   => array(
											'lg_desktop' => '5',
											'desktop'    => '4',
											'laptop'     => '4',
											'tablet'     => '3',
											'mobile'     => '2',
										),
										'min'       => '1',
									),
									array(
										'id'        => 'space_between_image_and_video',
										'type'      => 'spacing',
										'ignore_db' => true,
										'class'     => 'sp-tabs-space-between only_pro_spinner',
										'title'     => __( 'Space Between', 'wp-expand-tabs-free' ),
										'all'       => true,
										'all_icon'  => '<i class="fa fa-arrows"></i>',
										'default'   => array(
											'all' => '24',
										),
										'units'     => array(
											'px',
										),
									),
									array(
										'id'         => 'content_image_caption',
										'type'       => 'switcher',
										'ignore_db'  => true,
										'class'      => 'sp-tabs-space-between only_pro_switcher',
										'title'      => __( 'Caption', 'wp-expand-tabs-free' ),
										'text_on'    => __( 'Enabled', 'wp-expand-tabs-free' ),
										'text_off'   => __( 'Disabled', 'wp-expand-tabs-free' ),
										'text_width' => 96,
										'default'    => true,
									),
									array(
										'id'         => 'enable_image_lightbox',
										'type'       => 'switcher',
										'ignore_db'  => true,
										'class'      => 'sp-tabs-space-between only_pro_switcher',
										'title'      => __( 'Image Gallery Lightbox', 'wp-expand-tabs-free' ),
										'text_on'    => __( 'Enabled', 'wp-expand-tabs-free' ),
										'text_off'   => __( 'Disabled', 'wp-expand-tabs-free' ),
										'text_width' => 96,
										'default'    => true,
									),
									array(
										'id'        => 'video_play_mode',
										'type'      => 'button_set',
										'class'     => 'only_pro_spinner',
										'ignore_db' => true,
										'title'     => __( 'Video Play Mode', 'wp-expand-tabs-free' ),
										'options'   => array(
											'inline'   => __( 'Inline', 'wp-expand-tabs-free' ),
											'lightbox' => __( 'Lightbox', 'wp-expand-tabs-free' ),
										),
										'default'   => 'inline',
									),
								),
							),
						),
					),
				),
			)
		); // Carousel settings section end.
	}
}
