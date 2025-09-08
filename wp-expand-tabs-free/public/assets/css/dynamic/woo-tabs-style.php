<?php
/**
 * The dynamic product tab style generator file.
 *
 * This file handles generating and outputting dynamic inline CSS or a dynamic CSS file
 * for WooCommerce tabs based on saved settings.
 *
 * @link       https://shapedplugin.com/
 * @since      2.0.0
 *
 * @package    WP_Tabs_Pro
 * @subpackage WP_Tabs_Pro/public
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

$tabs_bottom_line_position = $sp_woo_tabs_settings['tabs_bottom_line_position'] ?? 'bottom-line';
// Tabs bottom line color and width.
$active_tab_bottom_line = $sp_woo_tabs_settings['active_tab_bottom_line'] ?? array(
	'all'   => '3',
	'color' => '#121212',
);
$bottom_border_width    = $active_tab_bottom_line['all'] ?? '3';
$bottom_border_color    = $active_tab_bottom_line['color'] ?? '#121212';

$space_between_title_and_desc = $sp_woo_tabs_settings['space_between_title_and_desc']['all'] ?? '0';
$desc_background_color        = $sp_woo_tabs_settings['desc_background_color'] ?? '#ffffff';

$desc_border       = $sp_woo_tabs_settings['desc_border'] ?? array(
	'all'   => '0',
	'style' => 'solid',
	'color' => '#c3c4c7',
);
$desc_border_width = $desc_border['all'] ?? '0';
$desc_border_style = $desc_border['style'] ?? 'solid';
$desc_border_color = $desc_border['color'] ?? '#c3c4c7';
$desc_padding      = $sp_woo_tabs_settings['desc_padding'] ?? array(
	'top'    => '20',
	'right'  => '20',
	'bottom' => '20',
	'left'   => '20',
);

// Apply Dynamic CSS styles.
$dynamic_style .= '
    /* WooCommerce Tabs Style */
    html body.woocommerce.sptpro-smart-tabs ul.tabs.wc-tabs:not(.is-width-constrained) {
        margin: 0 !important;
    }
    html body.woocommerce.sptpro-smart-tabs ul.wc-tabs li {
        text-align: center;
        padding: 0 !important;
        background-color: ' . $tab_name_bg_color . ' !important;
    }
    html body.woocommerce.sptpro-smart-tabs ul.wc-tabs li:hover {
        background-color: ' . $tab_name_hover_color . ' !important;
    }
    html body.woocommerce.sptpro-smart-tabs ul.wc-tabs li.active {
        background-color: ' . $tab_name_active_color . ' !important;
    }
    html body.woocommerce.sptpro-smart-tabs ul.wc-tabs li a {
        width: 100%;
        padding: ' . $_name_padding_top . 'px ' . $_name_padding_right . 'px ' . $_name_padding_bottom . 'px ' . $_name_padding_left . 'px !important;
    }
    html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs.wc-tabs-wrapper .woocommerce-Tabs-panel .sp-tab__tab-content {
        max-height: 100%;
        background-color: ' . $desc_background_color . ';
        border: ' . $desc_border_width . 'px ' . $desc_border_style . ' ' . $desc_border_color . ';
        padding: ' . $desc_padding['top'] . 'px ' . $desc_padding['right'] . 'px ' . $desc_padding['bottom'] . 'px ' . $desc_padding['left'] . 'px;
    }

    /* Remove WC default border line */
    .woocommerce-page div.product .woocommerce-tabs ul.tabs::before,
    .woocommerce.sptpro-smart-tabs div.product .woocommerce-tabs ul.tabs li.active:before{
        background: none;
        border: none;
    }
    /* Default WooCommerce Tabs content padding. */
    html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs.wc-tabs-wrapper #tab-description,
    html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs.wc-tabs-wrapper .woocommerce-Tabs-panel#tab-additional_information,
    html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs.wc-tabs-wrapper .woocommerce-Tabs-panel#tab-reviews {
        max-height: 100%;
        background-color: ' . $desc_background_color . ';
        border: ' . $desc_border_width . 'px ' . $desc_border_style . ' ' . $desc_border_color . ';
        padding: ' . $desc_padding['top'] . 'px ' . $desc_padding['right'] . 'px ' . $desc_padding['bottom'] . 'px ' . $desc_padding['left'] . 'px;
    }';


// Border bottom line for the tabs area.
$dynamic_style .= '
html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs ul.wc-tabs li {
    position: relative;
}
.sptpro-smart-tabs div.product .woocommerce-tabs ul.tabs li.active::before {
    content: " ";
    position: absolute;
    bottom: 0;
    left: 0;
    height: ' . $bottom_border_width . 'px;
    top: unset;
    border-radius: 0;
    width: 100%;
    background-color: ' . $bottom_border_color . ' !important;
}';

// Tabs alignments.
if ( 'horizontal' === $product_tabs_layout ) {
	$dynamic_style .= '
    html body.woocommerce ul.tabs.wc-tabs:not(.is-width-constrained) {
        margin: 0;
        display: flex;
        flex-wrap: wrap;
        gap: ' . $margin_between_tabs . 'px !important;
        justify-content: ' . $product_tabs_alignemnt . ';
    }';
}

// Tabs area and active tab bottom line.
if ( 'bottom-line' === $tabs_bottom_line_position ) {
	$dynamic_style .= '
    html body.woocommerce ul.tabs.wc-tabs {
        border-bottom: ' . $tabs_bottom_line['all'] . 'px solid ' . $tabs_bottom_line['color'] . ' !important;  
    }
    /* Active tab bottom line in Block theme */
    html body.sptpro-smart-tabs .wp-block-woocommerce-product-details ul.tabs.wc-tabs li:hover{
        border: 0;
    }
    html body.sptpro-smart-tabs .wp-block-woocommerce-product-details ul.tabs.wc-tabs li.active {
        border-bottom: ' . $bottom_border_width . 'px solid ' . $bottom_border_color . ' !important;
    }';
} else {
	$dynamic_style .= '
    html body.woocommerce ul.tabs.wc-tabs {
        border-top: ' . $tabs_bottom_line['all'] . 'px solid ' . $tabs_bottom_line['color'] . ' !important;  
    }
    .sptpro-smart-tabs div.product .woocommerce-tabs ul.tabs li.active::before {
        bottom: unset;
        top: 0;
    }
    /* Active tab bottom line in Block theme */
    html body.sptpro-smart-tabs .wp-block-woocommerce-product-details ul.tabs.wc-tabs li:hover{
        border: 0;
    }
    html body.sptpro-smart-tabs .wp-block-woocommerce-product-details ul.tabs.wc-tabs li.active {
        border-bottom: 0;
        border-top: ' . $bottom_border_width . 'px solid ' . $bottom_border_color . ' !important;
    }';
}

$dynamic_style .= '
    body.woocommerce.sptpro-smart-tabs .woocommerce-tabs.wc-tabs-wrapper .woocommerce-Tabs-panel {
        padding: 0;
        margin: 0;
    }
';

/**
 * Typography Section Styling for Woo Product Tabs Area.
 */
$product_tabs_title_font_load = isset( $sp_woo_tabs_settings['product_tabs_title_font_load'] ) ? $sp_woo_tabs_settings['product_tabs_title_font_load'] : false;
$product_tabs_title_typo      = isset( $sp_woo_tabs_settings['product_tabs_title_typo'] ) ? $sp_woo_tabs_settings['product_tabs_title_typo'] : '';

$dynamic_style .= '
    body.woocommerce.sptpro-smart-tabs #content-area div.product .woocommerce-tabs ul.tabs li a,
    html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs ul.wc-tabs li a {
        padding: 0;
        box-sizing: border-box;
        font-size: ' . $product_tabs_title_typo['font-size'] . 'px;
        color: ' . $product_tabs_title_typo['color'] . ' !important;
    }';

$dynamic_style .= '
    html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs ul.tabs.wc-tabs li.active a {
        color: ' . $product_tabs_title_typo['active_color'] . ';
    } 
    html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs ul.tabs.wc-tabs li.active a:focus {
    outline: none !important;}
    html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs ul.tabs.wc-tabs li:not(.active) a:hover {
        color: ' . $product_tabs_title_typo['hover_color'] . ' !important;
    }
';

/**
 * Typography Section Styling for Woo Description Area.
 */
$product_desc_font_load = isset( $sp_woo_tabs_settings['product_desc_font_load'] ) ? $sp_woo_tabs_settings['product_desc_font_load'] : false;
$product_desc_typo      = isset( $sp_woo_tabs_settings['product_desc_typo'] ) ? $sp_woo_tabs_settings['product_desc_typo'] : '';

$dynamic_style .= '
html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs.wc-tabs-wrapper #tab-description,
 html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs.wc-tabs-wrapper .woocommerce-Tabs-panel#tab-additional_information,
 html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs.wc-tabs-wrapper .woocommerce-Tabs-panel#tab-reviews,
html body.woocommerce .woocommerce-tabs.wc-tabs-wrapper .woocommerce-Tabs-panel .sp-tab__tab-content {
    font-size: ' . $product_desc_typo['font-size'] . 'px !important;
    color: ' . $product_desc_typo['color'] . ' !important;
}';

// Advanced Settings.
$product_tabs_advanced = get_option( 'sp_products_tabs_advanced' ); // Main key of woo product tabs advanced.
$show_tabs_on_mobile   = isset( $product_tabs_advanced['show_product_tabs_on_mobile'] ) ? $product_tabs_advanced['show_product_tabs_on_mobile'] : false;
// Show/Hide tabs on mobile.
if ( ! $show_tabs_on_mobile ) {
	$dynamic_style .= '
    @media (max-width: 480px) {
        .woocommerce .woocommerce-tabs.wc-tabs-wrapper {
            display: none !important;
        }
    }';
}

// Product Tabs Preloader.
if ( $preloader ) {
	$dynamic_style .= '
    .woocommerce-tabs.wc-tabs-wrapper {
        opacity: 0;
        position: relative;
    }
    .sptpro-tabs-preloader {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        z-index: 10;
    }
    .sptpro-spinner {
        width: 35px;
        height: 35px;
        border: 4px solid #ccc;
        border-top-color: #0073aa;
        border-radius: 50%;
        animation: sptpro-spin 0.8s linear infinite;
    }

    @keyframes sptpro-spin {
        to {
            transform: rotate(360deg);
        }
    }

    body.sptpro-tabs-loaded .woocommerce-tabs {
        opacity: 1;
    }
    
    body.sptpro-tabs-loaded .sptpro-tabs-preloader {
        display: none;
    }';
}

// Add styles compatibility for Divi theme.
if ( 'divi' === strtolower( get_template() ) ) {
	$dynamic_style .= '
    html body.woocommerce.sptpro-smart-tabs #content-area div.product .woocommerce-tabs ul.tabs li.active {
        background-color: ' . $tab_name_active_color . ' !important;
    }body.woocommerce.sptpro-smart-tabs #content-area div.product .woocommerce-tabs ul.tabs li.active:before {
        display: block; }   
    body.woocommerce.sptpro-smart-tabs #content-area div.product .woocommerce-tabs ul.tabs li a {
        width: 100%;
        padding: ' . $_name_padding_top . 'px ' . $_name_padding_right . 'px ' . $_name_padding_bottom . 'px ' . $_name_padding_left . 'px !important;
    }body.woocommerce.sptpro-smart-tabs #content-area div.product .woocommerce-tabs ul.tabs li:not(.active) a:hover {
        color: ' . $product_tabs_title_typo['hover_color'] . ' !important;}';
}

// Tabs on small screen.
if ( 'accordion_mode' === $tabs_on_small_screen ) {
	$dynamic_style .= '
        /* Responsive Columns for the Image Grid. */
        @media (max-width: ' . intval( $set_small_screen_width ) . 'px) {
            /* Accordion style for tabs on small screen */
            .woocommerce.sptpro-smart-tabs.single-product .woocommerce-tabs .accordion-header {
                background-color: transparent;
                width: 100%;
                padding: 10px 15px;
                border-radius: 0;
                display: flex;
                align-items: center;
                justify-content: space-between;
                cursor: pointer;
                box-sizing: border-box;
            }

            html body.woocommerce.single-product .woocommerce-tabs.wc-tabs-wrapper .accordion-header, 
            html body.woocommerce ul.tabs.wc-tabs {
                border-bottom: 1px solid #9B9B9B;
            }

            html body.woocommerce.sptpro-smart-tabs.single-product .woocommerce-tabs.wc-tabs-wrapper #tab-description,
            html body.woocommerce.sptpro-smart-tabs.single-product .woocommerce-tabs.wc-tabs-wrapper .woocommerce-Tabs-panel#tab-additional_information,
            html body.woocommerce.sptpro-smart-tabs.single-product .woocommerce-tabs.wc-tabs-wrapper .woocommerce-Tabs-panel#tab-reviews,
            body.woocommerce.sptpro-smart-tabs.single-product .woocommerce-tabs.wc-tabs-wrapper .woocommerce-Tabs-panel {
                border: 0;
                margin-left: 0;
            }

            .woocommerce.single-product div.product .woocommerce-tabs {
                flex-direction: column;
            }
    ';

	// Conditional CSS inside the same @media block.
	if ( $expand_and_collapse_icon ) {
		$dynamic_style .= '
            /** Accordion style for mobile devices */
            .woocommerce.single-product .woocommerce-tabs.wc-tabs-wrapper .accordion-header {
                position: relative;
                display: block;
            }

            .woocommerce.single-product .woocommerce-tabs.wc-tabs-wrapper .accordion-header span::after {
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

            .woocommerce.single-product .woocommerce-tabs.wc-tabs-wrapper .accordion-header.active span::after {
                content: "\2212";
            }
        ';
	}

	// Close the @media block.
	$dynamic_style .= '
        }
    ';
}
