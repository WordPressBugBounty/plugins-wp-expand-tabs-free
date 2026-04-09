<?php
/**
 * Dynamic style.
 *
 * @link       https://shapedplugin.com/
 * @since      2.0.0
 *
 * @package    WP_Tabs
 * @subpackage WP_Tabs/public
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$sptpro_tabs_layout               = isset( $sptpro_shortcode_options['sptpro_tabs_layout'] ) ? $sptpro_shortcode_options['sptpro_tabs_layout'] : 'horizontal';
$is_horizontal_layout             = in_array( $sptpro_tabs_layout, array( 'horizontal', 'horizontal-bottom' ), true );
$sptpro_tabs_horizontal_alignment = isset( $sptpro_shortcode_options['sptpro_tabs_horizontal_alignment'] ) ? $sptpro_shortcode_options['sptpro_tabs_horizontal_alignment'] : 'tab-horizontal-alignment-left';
$sptpro_tabs_vertical_alignment   = isset( $sptpro_shortcode_options['sptpro_tabs_vertical_alignment'] ) ? $sptpro_shortcode_options['sptpro_tabs_vertical_alignment'] : 'tab-vertical-alignment-top';

$sptpro_set_small_screen    = isset( $sptpro_shortcode_options['sptpro_set_small_screen'] ) ? $sptpro_shortcode_options['sptpro_set_small_screen'] : array(
	'all' => '480',
);
$sptpro_tabs_animation      = isset( $sptpro_shortcode_options['sptpro_tabs_animation'] ) ? $sptpro_shortcode_options['sptpro_tabs_animation'] : false;
$sptpro_animation_time      = isset( $sptpro_shortcode_options['sptpro_animation_time'] ) ? $sptpro_shortcode_options['sptpro_animation_time'] : '';
$sptpro_title_bg_color_type = isset( $sptpro_shortcode_options['sptpro_tabs_bg_color_type'] ) ? $sptpro_shortcode_options['sptpro_tabs_bg_color_type'] : 'solid';

$sptpro_tabs_border = isset( $sptpro_shortcode_options['sptpro_tabs_border'] ) ? $sptpro_shortcode_options['sptpro_tabs_border'] : array(
	'all'           => 1,
	'style'         => 'solid',
	'color'         => '#cccccc',
	'border_radius' => '2',
);

$sptpro_margin_between_tabs = isset( $sptpro_shortcode_options['sptpro_margin_between_tabs']['all'] ) ? $sptpro_shortcode_options['sptpro_margin_between_tabs']['all'] : '10';
$sptpro_tabs_border_radius  = isset( $sptpro_tabs_border['border_radius'] ) ? $sptpro_tabs_border['border_radius'] : '2';

$sptpro_title_padding     = isset( $sptpro_shortcode_options['sptpro_title_padding'] ) ? $sptpro_shortcode_options['sptpro_title_padding'] : array(
	'left'   => '15',
	'top'    => '15',
	'bottom' => '15',
	'right'  => '15',
);
$sptpro_desc_border       = isset( $sptpro_shortcode_options['sptpro_desc_border'] ) ? $sptpro_shortcode_options['sptpro_desc_border'] : array(
	'all'   => 1,
	'style' => 'solid',
	'color' => '#cccccc',
);
$sptpro_desc_border_style = isset( $sptpro_desc_border['style'] ) ? $sptpro_desc_border['style'] : 'solid';
$sptpro_desc_padding      = isset( $sptpro_shortcode_options['sptpro_desc_padding'] ) ? $sptpro_shortcode_options['sptpro_desc_padding'] : array(
	'left'   => '30',
	'top'    => '20',
	'bottom' => '20',
	'right'  => '20',
);
$sptpro_desc_bg_color     = isset( $sptpro_shortcode_options['sptpro_desc_bg_color'] ) ? $sptpro_shortcode_options['sptpro_desc_bg_color'] : '#ffffff';

$sptpro_section_title            = isset( $sptpro_shortcode_options['sptpro_section_title'] ) ? $sptpro_shortcode_options['sptpro_section_title'] : false;
$sptpro_tabs_on_small_screen     = isset( $sptpro_shortcode_options['sptpro_tabs_on_small_screen'] ) ? $sptpro_shortcode_options['sptpro_tabs_on_small_screen'] : 'full_widht';
$sptpro_expand_and_collapse_icon = isset( $sptpro_shortcode_options['sptpro_expand_and_collapse_icon'] ) ? $sptpro_shortcode_options['sptpro_expand_and_collapse_icon'] : true;


$sptpro_section_title_typo = isset( $sptpro_shortcode_options['sptpro_section_title_typo'] ) ? $sptpro_shortcode_options['sptpro_section_title_typo'] : array(
	'color'          => '#444444',
	'font-family'    => '',
	'font-style'     => '600',
	'font-size'      => '28',
	'line-height'    => '28',
	'letter-spacing' => '0',
	'text-align'     => 'left',
	'text-transform' => 'none',
	'type'           => 'google',
	'unit'           => 'px',
	'margin-bottom'  => '30',
);

$section_title_margin_bottom = isset( $sptpro_section_title_typo['margin-bottom'] ) ? $sptpro_section_title_typo['margin-bottom'] : '30';

$sptpro_tabs_title_typo = isset( $sptpro_shortcode_options['sptpro_tabs_title_typo'] ) ? $sptpro_shortcode_options['sptpro_tabs_title_typo'] : array(
	'font-family'    => '',
	'font-weight'    => '600',
	'font-style'     => 'normal',
	'font-size'      => '16',
	'line-height'    => '22',
	'letter-spacing' => '0',
	'text-align'     => 'center',
	'text-transform' => 'none',
	'color'          => '#444',
	'hover_color'    => '#444',
	'active_color'   => '#444',
	'type'           => 'google',
);
$sptpro_desc_typo       = isset( $sptpro_shortcode_options['sptpro_desc_typo'] ) ? $sptpro_shortcode_options['sptpro_desc_typo'] : array(
	'color'          => '#444',
	'font-family'    => '',
	'font-style'     => '400',
	'font-size'      => '16',
	'line-height'    => '24',
	'letter-spacing' => '0',
	'text-align'     => 'left',
	'text-transform' => 'none',
	'type'           => 'google',
);

// Animation.
if ( $sptpro_tabs_animation ) {
	$sptpro_dynamic_style .= '#sp-tabpro-wrapper_' . $post_id . '.sp-tab__lay-default .sp-tab__tab-content {
		width: 100%;
	}
	#sp-tabpro-wrapper_' . $post_id . ' .animated {
		-webkit-animation-duration: ' . $sptpro_animation_time . 'ms;
		animation-duration: ' . $sptpro_animation_time . 'ms;
	}';
}

// Common selector for this post's tab navigation.
$selector = sprintf( '#sp-wp-tabs-wrapper_%d .sp-tab__nav', (int) $post_id );

// Define alignment maps for horizontal and vertical layouts.
$horizontal_map = array(
	'tab-horizontal-alignment-left'      => 'flex-start',
	'tab-horizontal-alignment-center'    => 'center',
	'tab-horizontal-alignment-right'     => 'flex-end',
	'tab-horizontal-alignment-justified' => 'space-between',
);

$vertical_map = array(
	'tab-vertical-alignment-top'       => 'flex-start',
	'tab-vertical-alignment-middle'    => 'center',
	'tab-vertical-alignment-bottom'    => 'flex-end',
	'tab-vertical-alignment-justified' => 'space-between',
);

// Apply styles based on tab layout and alignments.
if ( $is_horizontal_layout ) {
	$horizontal_aligment_value = isset( $horizontal_map[ $sptpro_tabs_horizontal_alignment ] ) ? $horizontal_map[ $sptpro_tabs_horizontal_alignment ] : 'flex-start';

	// Apply the horizontal alignment style.
	if ( $horizontal_aligment_value ) {
		$sptpro_dynamic_style .= sprintf(
			'%s { justify-content: %s; }',
			$selector,
			$horizontal_aligment_value
		);
	}
} elseif ( 'vertical-right' === $sptpro_tabs_layout ) {
	$vertical_alignment_value = isset( $vertical_map[ $sptpro_tabs_vertical_alignment ] ) ? $vertical_map[ $sptpro_tabs_vertical_alignment ] : 'flex-start';

	// Apply the vertical alignment style.
	if ( $vertical_alignment_value ) {
		$sptpro_dynamic_style .= sprintf(
			'%s { justify-content: %s; }',
			$selector,
			$vertical_alignment_value
		);
	}
}

/* Tabs active & inactive icon in mobile devices (Accordion mode) */
if ( $sptpro_expand_and_collapse_icon && 'accordion_mode' === $sptpro_tabs_on_small_screen ) {
	$sptpro_dynamic_style .= '
	@media only screen and (max-width: ' . $sptpro_set_small_screen['all'] . 'px) {
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default .sp-tab__card label,
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default a.sp-tab__link{
			position: relative;
			display: block;
		}

		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default .sp-tab__card .sp-tab__nav-link .sp-tab__card-header {
			padding-right: 40px; 
		}

		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default .sp-tab__card label[data-sptoggle]:after,
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default a.sp-tab__link:after,
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default .sp-tab__card span[aria-expanded="false"]:after {
			font-family: "FontAwesome";
			content: "\002B";
			color: #1a1515;
			font-weight: bold;
			float: right;
			position: absolute;
			right: 15px;
			font-size: 25px;
			bottom: auto;
			top: 50%;
			transform: translateY(-50%);
		}
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default .sp-tab__card label[aria-expanded="true"]:after {
			content: "\2212";
		}
	}';
}

if ( $is_horizontal_layout ) {
	$sptpro_dynamic_style .= '#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default.sp-tab__horizontal-bottom {
		display: flex;
		flex-direction: column-reverse;
	}
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default.sp-tab__horizontal-bottom > ul {
		border-top: ' . $sptpro_tabs_border['all'] . 'px solid ' . $sptpro_tabs_border['color'] . ';
		border-bottom: 0;
		margin-top: 0;
	}
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default.sp-tab__horizontal-bottom > ul > li .sp-tab__nav-link.sp-tab__active {
		border-color: transparent ' . $sptpro_tabs_border['color'] . $sptpro_tabs_border['color'] . ';
		margin-top: -' . $sptpro_tabs_border['all'] . 'px;
	}
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default.sp-tab__horizontal-bottom > ul > li .sp-tab__nav-link,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default.sp-tab__horizontal-bottom > ul > li a,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default.sp-tab__horizontal-bottom > ul > .sp-tab__nav-item {
		border-top: 0;
		border-top-left-radius: 0;
		border-top-right-radius: 0;
		border-bottom-left-radius: ' . $sptpro_tabs_border_radius . 'px;
		border-bottom-right-radius: ' . $sptpro_tabs_border_radius . 'px;
	}
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default.sp-tab__horizontal-bottom > ul {
		border-bottom: none;
	}
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default.sp-tab__horizontal-bottom .sp-tab__tab-content .sp-tab__tab-pane {
		border-top: ' . $sptpro_desc_border['all'] . 'px ' . $sptpro_desc_border_style . ' ' . $sptpro_desc_border['color'] . ';
		border-bottom: 0;
	}
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default .sp-tab__tab-content .sp-tab-content > ul,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default .sp-tab__tab-content .sp-tab-content > ol {
		border-bottom: none;
	}

	#sp-wp-tabs-wrapper_' . $post_id . ' .sp-tab__nav-tabs .sp-tab__nav-item.show .sp-tab__nav-link,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default ul li .sp-tab__nav-link.sp-tab__active {
		border-color: ' . $sptpro_tabs_border['color'] . $sptpro_tabs_border['color'] . ' transparent;
	}';
}

	/* Tabs Border Style */
	$sptpro_dynamic_style .= '#sp-wp-tabs-wrapper_' . $post_id . ' > .sp-tab__nav-tabs .sp-tab__nav-link.sp-tab__active .sp-tab__tab_title,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default .sp-tab__nav-link > .sp-tab__card-header {
			color: ' . $sptpro_tabs_title_typo['active_color'] . ';
	}

	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul > li > .sp-tab__nav-link,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul > li > a {
		cursor: pointer;
		border-color: ' . $sptpro_tabs_border['color'] . ';
		padding-top: ' . $sptpro_title_padding['top'] . 'px;
		padding-right: ' . $sptpro_title_padding['right'] . 'px;
		padding-bottom: ' . $sptpro_title_padding['bottom'] . 'px;
		padding-left: ' . $sptpro_title_padding['left'] . 'px;
	}
	#sp-wp-tabs-wrapper_' . $post_id . ' > .sp-tab__nav-tabs .sp-tab__nav-link {
		border: ' . $sptpro_tabs_border['all'] . 'px ' . $sptpro_tabs_border['style'] . ' ' . $sptpro_tabs_border['color'] . ';
		height: 100%;
	}
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul > li .sp-tab__nav-link,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul > li a,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul > .sp-tab__nav-item {
		border-top-left-radius: ' . $sptpro_tabs_border_radius . 'px;
		border-top-right-radius: ' . $sptpro_tabs_border_radius . 'px;
	}';
	$sptpro_dynamic_style .= '#sp-wp-tabs-wrapper_' . $post_id . ' .sp-tab__nav-tabs .sp-tab__nav-item {
		margin-bottom: -' . $sptpro_tabs_border['all'] . 'px; 
	}';
	$sptpro_dynamic_style .= '
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul .sp-tab__nav-item {
		margin-right: ' . $sptpro_margin_between_tabs . 'px;
	}
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default.sp-tab__horizontal-bottom > ul .sp-tab__nav-item {
		margin-right: ' . $sptpro_margin_between_tabs . 'px;
		margin-top: 0;
	}
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul .sp-tab__nav-item .sp-tab__nav-link:hover .sp-tab__tab_title,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul .sp-tab__nav-item a:hover .sp-tab__tab_title,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default .sp-tab__nav-link.collapsed .sp-tab__card-header:hover {
		color: ' . $sptpro_tabs_title_typo['hover_color'] . ';
		transition: .3s;
	}';

// Vertical Right Layout.
if ( 'vertical-right' === $sptpro_tabs_layout ) {
	$sptpro_dynamic_style .= '#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default {
		display: flex;
		flex-direction: row-reverse;
	}
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul {
		border-bottom: none;
		margin-top: 0px;
		display: flex;
		flex-flow: column nowrap;
		z-index: 9;
		gap: ' . $sptpro_margin_between_tabs . 'px;
		margin-left: -' . $sptpro_tabs_border['all'] . 'px;
	}
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul li > label {
		white-space: nowrap;
		border-radius: 0;
	}
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul .sp-tab__nav-item {
		margin-right: 0;
	}
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul .sp-tab__nav-item .sp-tab__nav-link.sp-tab__active {
		border-left: none;
	}

	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul li > label,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul li > a,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul .sp-tab__nav-item {
		border-top-left-radius: 0 !important;
		border-top-right-radius: ' . $sptpro_tabs_border_radius . 'px;
		border-bottom-right-radius: ' . $sptpro_tabs_border_radius . 'px;
	}
	
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default .sp-tab__tab-content {
		width: 100%;
	}
		
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default .sp-tab__nav-link {
		white-space: nowrap;
	}
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul > li > .sp-tab__nav-link,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul > li > a {
		width: 100%;
	}';

	if ( 'tab-vertical-alignment-bottom' !== $sptpro_tabs_vertical_alignment ) {
		$sptpro_dynamic_style .= '#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default .sp-tab__nav-tabs .sp-tab__nav-item:last-child {
			margin-bottom: 0;
		}';
	}
}

// Tabs' Name background color (Solid & Gradient).
switch ( $sptpro_title_bg_color_type ) {
	case 'solid':
		// Tabs' background normal color.
		$sptpro_title_hover_bg_color  = isset( $sptpro_shortcode_options['sptpro_title_bg_color']['title-hover-bg-color'] ) ? $sptpro_shortcode_options['sptpro_title_bg_color']['title-hover-bg-color'] : '';
		$sptpro_title_bg_color        = isset( $sptpro_shortcode_options['sptpro_title_bg_color']['title-bg-color'] ) ? $sptpro_shortcode_options['sptpro_title_bg_color']['title-bg-color'] : '';
		$sptpro_title_active_bg_color = isset( $sptpro_shortcode_options['sptpro_title_bg_color']['title-active-bg-color'] ) ? $sptpro_shortcode_options['sptpro_title_bg_color']['title-active-bg-color'] : '';

		$sptpro_dynamic_style .= '
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul .sp-tab__nav-item,
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default .sp-tab__nav-link.collapsed > .sp-tab__card-header {
			background-color: ' . $sptpro_title_bg_color . ';
			transition: 0.3s;
		}

		#sp-wp-tabs-wrapper_' . $post_id . ' > .sp-tab__nav-tabs > .sp-tab__nav-item.show .sp-tab__nav-link,
		#sp-wp-tabs-wrapper_' . $post_id . ' > .sp-tab__nav-tabs > .sp-tab__nav-item .sp-tab__nav-link.sp-tab__active,
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default .sp-tab__nav-link > .sp-tab__card-header {
			background-color: ' . $sptpro_title_active_bg_color . ';
		}

		#sp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul .sp-tab__nav-item .sp-tab__nav-link:not(.sp-tab__active):hover,
		#sp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default .sp-tab__tab-content > .sp-tab__tab-pane .sp-tab__card-header:hover {
			background-color: ' . $sptpro_title_hover_bg_color . ';
		}
			
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul .sp-tab__nav-item:not(.sp-tab__active):hover,
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default .sp-tab__nav-link.collapsed > .sp-tab__card-header:hover {
			background-color: ' . $sptpro_title_hover_bg_color . ';
		}
		';
		break;
	case 'gradient':
		// Tabs' background gradient color.
		$tabs_bg_gradient_colors = isset( $sptpro_shortcode_options['sptpro_tabs_bg_gradient_color'] ) ? $sptpro_shortcode_options['sptpro_tabs_bg_gradient_color'] : '';
		$tab_gradient_direction  = isset( $tabs_bg_gradient_colors['background-gradient-direction'] ) ? $tabs_bg_gradient_colors['background-gradient-direction'] : '135deg';
		$tab_background_color    = isset( $tabs_bg_gradient_colors['background-color'] ) ? $tabs_bg_gradient_colors['background-color'] : '#fff';
		$tab_gradient_color      = isset( $tabs_bg_gradient_colors['background-gradient-color'] ) ? $tabs_bg_gradient_colors['background-gradient-color'] : '#eee';

		$sptpro_dynamic_style .= '
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul .sp-tab__nav-item .sp-tab__nav-link:not(.sp-tab__active),
		#sp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default .sp-tab__tab-content > .sp-tab__tab-pane .sp-tab__card-header{
			background-color: ' . $tab_background_color . ';
			background-image: linear-gradient( ' . $tab_gradient_direction . ' , ' . $tab_background_color . ', ' . $tab_gradient_color . ' );
		}
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul .sp-tab__nav-item .sp-tab__nav-link.sp-tab__active {
			border-bottom: ' . $sptpro_tabs_border['all'] . 'px ' . $sptpro_tabs_border['style'] . ' ' . $sptpro_desc_bg_color . ';
		}
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default.sp-tab__horizontal-bottom > ul .sp-tab__nav-item .sp-tab__nav-link.sp-tab__active {
			border-top: ' . $sptpro_tabs_border['all'] . 'px ' . $sptpro_tabs_border['style'] . ' ' . $sptpro_desc_bg_color . ';
		}';
		break;
}

// Don't load the "margin-right" property in rtl site of the tabs.
if ( ! is_rtl() ) {
	$sptpro_dynamic_style .= '#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul .sp-tab__nav-item:last-child {
		margin-right: 0;
	}';
}
if ( is_rtl() ) {
	$sptpro_dynamic_style .= '#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul .sp-tab__nav-item:first-child {
		margin-right: 0;
	}';
}

	$sptpro_dynamic_style .= '#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > .sp-tab__tab-content .sp-tab__tab-pane {
		border: ' . $sptpro_desc_border['all'] . 'px ' . $sptpro_desc_border_style . ' ' . $sptpro_desc_border['color'] . ';
		padding-top: ' . $sptpro_desc_padding['top'] . 'px;
		padding-right: ' . $sptpro_desc_padding['right'] . 'px;
		padding-bottom: ' . $sptpro_desc_padding['bottom'] . 'px;
		padding-left: ' . $sptpro_desc_padding['left'] . 'px;
			background-color: ' . $sptpro_desc_bg_color . ';
		}';

if ( $is_horizontal_layout ) {
	$sptpro_dynamic_style .= '#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul {
		border-bottom: ' . $sptpro_desc_border['all'] . 'px ' . $sptpro_desc_border_style . ' ' . $sptpro_desc_border['color'] . ';
	}
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > .sp-tab__tab-content .sp-tab__tab-pane {
		border-top: 0px;
	}';
}

if ( 'full_widht' === $sptpro_tabs_on_small_screen ) {
	$sptpro_dynamic_style .= '@media(max-width:' . $sptpro_set_small_screen['all'] . 'px) {
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul li.sp-tab__nav-item {
			width: 100%;
			margin-right: 0px;
		}
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul li.sp-tab__nav-item:last-child {
			margin-bottom: -1px;
		}';
	if ( 'vertical-right' === $sptpro_tabs_layout ) {
		$sptpro_dynamic_style .= '#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default {
			flex-direction: column;
		}
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul .sp-tab__nav-item .sp-tab__nav-link.sp-tab__active {
			border-left: ' . $sptpro_tabs_border['all'] . 'px ' . $sptpro_tabs_border['style'] . ' ' . $sptpro_tabs_border['color'] . ';
		}';
	}
	$sptpro_dynamic_style .= '}'; // @media end.
}

if ( 'accordion_mode' === $sptpro_tabs_on_small_screen ) {
		$sptpro_dynamic_style .= '

		.sp-tab__default-accordion .sp-tab__card {
			border-radius: 0;
		}

		.sp-tab__default-accordion .sp-tab__card-header {
			cursor: pointer;
		}

	@media(min-width:' . $sptpro_set_small_screen['all'] . 'px) {
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__default-accordion .sp-tab__nav-tabs {
			display: flex;
		}
		#sp-wp-tabs-wrapper_' . $post_id . ' .sp-tab__card {
			border: none;
		}
		#sp-wp-tabs-wrapper_' . $post_id . ' .sp-tab__card .sp-tab__card-header {
			display: none;
		}
		#sp-wp-tabs-wrapper_' . $post_id . ' .sp-tab__card .sp-tab__collapse {
			display: block;
		}
	}

	@media(max-width:' . $sptpro_set_small_screen['all'] . 'px) {
		.sp-tab__lay-default.sp-tab__default-accordion .sp-tab__nav-tabs {
			display: none !important;
		}
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default.sp-tab__default-accordion > .sp-tab__tab-content > .sp-tab__tab-pane {
			border: 0;
			padding: 0;
			margin-bottom: 5px;
		}

		.sp-tab__collapse:not(.sp-tab__show) {
			display: none;
		}
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default.sp-tab__default-accordion > .sp-tab__tab-content > .sp-tab__tab-pane {
			display: block;
			opacity: 1;
		}
		.sp-tab__default-accordion .sp-tab__tab-content .sp-tab__tab-pane {
			border: none;
			padding: 0;
		}
		.sp-tab__default-accordion .sp-tab__card-header {
			border: 1px solid #ccc;
		}
		.sp-tab__default-accordion .sp-tab__card-body {
			border: 1px solid #ccc;
			border-top: none;
			-ms-flex: 1 1 auto;
			flex: 1 1 auto;
			padding: 1.25rem;
		}
	}';
}

// Typography.
if ( $sptpro_section_title ) {
	$sptpro_dynamic_style .= '#poststuff h2.sp-tab__section_title_' . $post_id . ', h2.sp-tab__section_title_' . $post_id . ' ,.editor-styles-wrapper .wp-block h2.sp-tab__section_title_' . $post_id . '{
		margin-bottom: ' . $section_title_margin_bottom . 'px !important;
		font-weight: 600;
		font-style: normal;
		font-size: ' . $sptpro_section_title_typo['font-size'] . 'px;
		line-height: ' . $sptpro_section_title_typo['line-height'] . 'px;
		letter-spacing: ' . $sptpro_section_title_typo['letter-spacing'] . 'px;
		padding: 0;
		color: ' . $sptpro_section_title_typo['color'] . ';';

	if ( ! empty( $sptpro_section_title_typo['font-family'] ) ) {
		$font_style            = ! empty( $sptpro_section_title_typo['font-style'] ) ? $sptpro_section_title_typo['font-style'] : 'normal';
		$sptpro_dynamic_style .= '
			font-family: ' . $sptpro_section_title_typo['font-family'] . ';
			font-style: ' . $font_style . ';
			font-weight: ' . $sptpro_section_title_typo['font-weight'] . ';';
	}
	$sptpro_dynamic_style .= '}';
}
	$sptpro_dynamic_style .= '
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul .sp-tab__nav-item .sp-tab__tab_title,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default span > .sp-tab__card-header {
		font-weight: 600;
		font-style: normal;
		font-size: ' . $sptpro_tabs_title_typo['font-size'] . 'px;
		line-height: ' . $sptpro_tabs_title_typo['line-height'] . 'px;
		letter-spacing: ' . $sptpro_tabs_title_typo['letter-spacing'] . 'px;
		color: ' . $sptpro_tabs_title_typo['color'] . ';
		margin: 0px;';

if ( ! empty( $sptpro_tabs_title_typo['font-family'] ) ) {
	$font_style            = ! empty( $sptpro_tabs_title_typo['font-style'] ) ? $sptpro_tabs_title_typo['font-style'] : 'normal';
	$sptpro_dynamic_style .= '
		font-family: ' . $sptpro_tabs_title_typo['font-family'] . ';
		font-style: ' . $font_style . ';
		font-weight: ' . $sptpro_tabs_title_typo['font-weight'] . ';
		';
}
	$sptpro_dynamic_style .= '}';

	$sptpro_dynamic_style .= '#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > .sp-tab__tab-content .sp-tab__tab-pane,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > .sp-tab__tab-content .sp-tab__tab-pane p {
		font-weight: 400;
		font-style: normal;
		font-size: ' . $sptpro_desc_typo['font-size'] . 'px;
		line-height: ' . $sptpro_desc_typo['line-height'] . 'px;
		letter-spacing: ' . $sptpro_desc_typo['letter-spacing'] . 'px;
		color: ' . $sptpro_desc_typo['color'] . ';';

if ( ! empty( $sptpro_desc_typo['font-family'] ) ) {
	$font_style            = ! empty( $sptpro_desc_typo['font-style'] ) ? $sptpro_desc_typo['font-style'] : 'normal';
	$sptpro_dynamic_style .= '
			font-family: ' . $sptpro_desc_typo['font-family'] . ';
			font-style: ' . $font_style . ';
			font-weight: ' . $sptpro_desc_typo['font-weight'] . ';
			';
}
$sptpro_dynamic_style .= '}';

	$sptpro_dynamic_style .= '#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > .sp-tab__tab-content .sp-tab__tab-pane ul li a, #sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default .sp-tab__tab-content .sp-tab__tab-pane ol li a {
		color: ' . $sptpro_desc_typo['color'] . ';
	}';
