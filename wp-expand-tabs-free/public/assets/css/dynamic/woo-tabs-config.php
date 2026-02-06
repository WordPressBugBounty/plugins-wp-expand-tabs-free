<?php
/**
 * Common Configuration Variables for WooCommerce Product Tabs.
 *
 * This file centralizes global configuration variables, options, and dynamic values
 * that are shared and utilized across different dynamic CSS generation files.
 *
 * @link       https://shapedplugin.com/
 * @since      3.1.0
 * @package    WP_Tabs
 * @subpackage WP_Tabs/public
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$sp_woo_tabs_settings = get_option( 'sptabs_product_tabs_settings', array() ); // Main key of woo product tabs settings.

$tabs_on_small_screen     = $sp_woo_tabs_settings['sptpro_tabs_on_small_screen'] ?? 'default';
$expand_and_collapse_icon = filter_var(
	$sp_woo_tabs_settings['sptpro_expand_and_collapse_icon'] ?? false,
	FILTER_VALIDATE_BOOLEAN
);

/**
 * Defines Woo Product Tabs Styles related settings (General Settings).
 */
$product_tabs_layout    = $sp_woo_tabs_settings['product_tabs_layout'] ?? 'horizontal';
$product_tabs_alignemnt = $sp_woo_tabs_settings['product_tabs_alignemnt'] ?? 'left';
$tabs_activator_event   = $sp_woo_tabs_settings['sptpro_tabs_activator_event'] ?? 'tabs-activator-event-click';
$margin_between_tabs    = $sp_woo_tabs_settings['sptpro_margin_between_tabs']['all'] ?? '10';
$_content_height        = $sp_woo_tabs_settings['sptpro_tab_content_height'] ?? 'auto';
$set_small_screen_width = $sp_woo_tabs_settings['sptpro_set_small_screen']['all'] ?? 480;
$preloader              = $sp_woo_tabs_settings['sptpro_preloader'] ?? true;

/**
 * Display Settings.
 */
$tab_name_bg_colors    = $sp_woo_tabs_settings['tab_name_bg_color'] ?? array();
$tab_name_bg_color     = $tab_name_bg_colors['color'] ?? '#fff';
$tab_name_active_color = $tab_name_bg_colors['active-color'] ?? '#fff';
$tab_name_hover_color  = $tab_name_bg_colors['hover-color'] ?? '#fff';

$tabs_name_padding    = $sp_woo_tabs_settings['tabs_name_padding'] ?? array(
	'top'    => '14',
	'right'  => '16',
	'bottom' => '14',
	'left'   => '16',
);
$_name_padding_left   = $tabs_name_padding['left'] ?? '16';
$_name_padding_top    = $tabs_name_padding['top'] ?? '14';
$_name_padding_bottom = $tabs_name_padding['bottom'] ?? '14';
$_name_padding_right  = $tabs_name_padding['right'] ?? '16';

$tabs_bottom_line   = $sp_woo_tabs_settings['tabs_bottom_line'] ?? array(
	'all'   => '2',
	'color' => '#E7E7E7',
);
$tabs_vertical_line = $sp_woo_tabs_settings['tabs_vertical_line'] ?? array(
	'all'   => '2',
	'color' => '#E7E7E7',
);

$tabs_style = $sp_woo_tabs_settings['sptpro_tab_style'] ?? 'default';

// Tabs bottom line color and width.
$active_tab_bottom_line = $sp_woo_tabs_settings['active_tab_bottom_line'] ?? array(
	'all'   => '3',
	'color' => '#121212',
);
$bottom_border_width    = $active_tab_bottom_line['all'] ?? '3';
$bottom_border_color    = $active_tab_bottom_line['color'] ?? '#121212';

$space_between_title_and_desc = $sp_woo_tabs_settings['space_between_title_and_desc']['all'] ?? '0';

$description_color = $sp_woo_tabs_settings['description_color'] ?? array(
	'color'    => '#444',
	'bg_color' => '#fff',
);

$description_font_color = $description_color['color'] ?? '#444';
$description_bg_color   = $description_color['bg_color'] ?? '#fff';

$desc_border = $sp_woo_tabs_settings['desc_border'] ?? array(
	'all'           => '0',
	'style'         => 'solid',
	'color'         => '#c3c4c7',
	'border_radius' => '0',
);

$desc_border_width  = $desc_border['all'] ?? '0';
$desc_border_style  = $desc_border['style'] ?? 'solid';
$desc_border_color  = $desc_border['color'] ?? '#c3c4c7';
$desc_border_radius = $desc_border['border_radius'] ?? '0';

$desc_padding = $sp_woo_tabs_settings['desc_padding'] ?? array(
	'top'    => '20',
	'right'  => '20',
	'bottom' => '20',
	'left'   => '20',
);

/**
 * Typography settings for Woo Product Tabs Area.
 */
$product_tabs_title_font_load = isset( $sp_woo_tabs_settings['product_tabs_title_font_load'] ) ? $sp_woo_tabs_settings['product_tabs_title_font_load'] : false;
$product_tabs_title_typo      = isset( $sp_woo_tabs_settings['product_tabs_title_typo'] ) ? $sp_woo_tabs_settings['product_tabs_title_typo'] : '';

$tab_name_color = $sp_woo_tabs_settings['tab_name_color'] ?? array(
	'color'        => '#828282',
	'active-color' => '#111111',
	'hover-color'  => '#111111',
);

/**
 * Typography settings for Woo product Description Area.
 */
$product_desc_font_load = isset( $sp_woo_tabs_settings['product_desc_font_load'] ) ? $sp_woo_tabs_settings['product_desc_font_load'] : false;
$product_desc_typo      = isset( $sp_woo_tabs_settings['product_desc_typo'] ) ? $sp_woo_tabs_settings['product_desc_typo'] : '';

/*
 * advanced settings for Woo Product Tabs.
 */
$product_tabs_advanced            = get_option( 'sp_products_tabs_advanced' ); // Main key of woo product tabs advanced.
$show_tabs_on_mobile              = isset( $product_tabs_advanced['show_product_tabs_on_mobile'] ) ? $product_tabs_advanced['show_product_tabs_on_mobile'] : false;
$show_wc_default_related_products = $product_tabs_advanced['show_wc_default_related_products'] ?? true;

$is_horizontal_layout = in_array( $product_tabs_layout, array( 'horizontal', 'horizontal-bottom' ), true );
