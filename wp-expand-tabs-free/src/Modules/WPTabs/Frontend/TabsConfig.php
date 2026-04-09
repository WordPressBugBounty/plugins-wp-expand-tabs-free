<?php
/**
 * Tabs Configuration File.
 *
 * @package   ShapedPlugin\SmartTabsFree
 * @subpackage ShortcodeTabs\Frontend
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

$sptpro_tab_type = isset( $sptpro_data_upload['sptpro_tab_type'] ) ? $sptpro_data_upload['sptpro_tab_type'] : 'content-tabs';

$sptpro_data_src             = isset( $sptpro_data_upload['sptpro_content_source'] ) ? $sptpro_data_upload['sptpro_content_source'] : null;
$sptpro_preloader            = isset( $sptpro_shortcode_options['sptpro_preloader'] ) ? $sptpro_shortcode_options['sptpro_preloader'] : false;
$sptpro_tabs_layout          = isset( $sptpro_shortcode_options['sptpro_tabs_layout'] ) ? $sptpro_shortcode_options['sptpro_tabs_layout'] : 'horizontal';
$sptpro_tabs_activator_event = isset( $sptpro_shortcode_options['sptpro_tabs_activator_event'] ) ? $sptpro_shortcode_options['sptpro_tabs_activator_event'] : '';
$sptpro_tab_opened           = 1;
$sptpro_tabs_on_small_screen = isset( $sptpro_shortcode_options['sptpro_tabs_on_small_screen'] ) ? $sptpro_shortcode_options['sptpro_tabs_on_small_screen'] : 'full_widht';

$sptpro_title_heading_tag = isset( $sptpro_shortcode_options['sptpro_title_heading_tag'] ) ? $sptpro_shortcode_options['sptpro_title_heading_tag'] : 'H4';
$sptpro_section_title     = isset( $sptpro_shortcode_options['sptpro_section_title'] ) ? $sptpro_shortcode_options['sptpro_section_title'] : false;

$sptpro_anchor_linking = filter_var(
	$sptpro_shortcode_options['sptpro_anchor_linking'] ?? true,
	FILTER_VALIDATE_BOOLEAN
);
$sptpro_tab_link_type  = isset( $sptpro_shortcode_options['sptpro_tab_link_type'] ) ? $sptpro_shortcode_options['sptpro_tab_link_type'] : 'tab_id';

$sptpro_tabs_vertical_position = isset( $sptpro_shortcode_options['sptpro_tabs_vertical_position'] ) ? $sptpro_shortcode_options['sptpro_tabs_vertical_position'] : '';

$allow_meta_tag = array(
	'div'  => array(
		'class' => array(),
	),
	'a'    => array(
		'href'  => array(),
		'title' => array(),
	),
	'span' => array(
		'class' => array(),
	),
	'img'  => array(
		'src'    => array(),
		'class'  => array(),
		'alt'    => array(),
		'width'  => array(),
		'height' => array(),
	),
);

// Animation.
$sptpro_tabs_animation      = isset( $sptpro_shortcode_options['sptpro_tabs_animation'] ) ? $sptpro_shortcode_options['sptpro_tabs_animation'] : false;
$sptpro_tabs_animation_type = isset( $sptpro_shortcode_options['sptpro_tabs_animation_type'] ) ? $sptpro_shortcode_options['sptpro_tabs_animation_type'] : '';
$animation_name             = $sptpro_tabs_animation ? 'animated ' . $sptpro_tabs_animation_type : '';

$wrapper_class   = 'sp-tab__lay-default';
$content_class   = 'sp-tab__lay-default';
$title_data_attr = '';

$sptpro_tabs_position_bottom = '';
if ( 'horizontal-bottom' === $sptpro_tabs_layout ) {
	$sptpro_tabs_position_bottom = ' sp-tab__horizontal-bottom';
}
