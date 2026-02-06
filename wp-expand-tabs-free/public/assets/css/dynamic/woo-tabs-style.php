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
 * @package wp-expand-tabs-free
 * @subpackage wp-expand-tabs-free/public
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Apply Dynamic CSS styles.
$dynamic_style .= '
    /* WooCommerce Tabs Style */
    html body.woocommerce.sptpro-smart-tabs ul.tabs.wc-tabs:not(.is-width-constrained) {
        margin: 0 !important;
        margin-bottom: ' . $space_between_title_and_desc . 'px !important;
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
        background-color: ' . $description_bg_color . ';
        border: ' . $desc_border_width . 'px ' . $desc_border_style . ' ' . $desc_border_color . ';
        border-radius: ' . $desc_border_radius . 'px;
        padding: ' . $desc_padding['top'] . 'px ' . $desc_padding['right'] . 'px ' . $desc_padding['bottom'] . 'px ' . $desc_padding['left'] . 'px;
    }

    /* Remove WC default border line */
    .woocommerce-page div.product .woocommerce-tabs.wc-tabs-wrapper ul.tabs::before,
    .woocommerce.sptpro-smart-tabs div.product .woocommerce-tabs ul.tabs li.active:before{
        background: none;
        border: none;
    }
    /* Default WooCommerce Tabs content padding. */
    html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs.wc-tabs-wrapper #tab-description,
    html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs.wc-tabs-wrapper .woocommerce-Tabs-panel#tab-additional_information,
    html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs.wc-tabs-wrapper .woocommerce-Tabs-panel#tab-reviews {
        max-height: 100%;
        background-color: ' . $description_bg_color . ';
        border: ' . $desc_border_width . 'px ' . $desc_border_style . ' ' . $desc_border_color . ';
        border-radius: ' . $desc_border_radius . 'px;
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

	// Tabs area and active tab bottom line.
	if ( 'default' === $tabs_style ) {
		$dynamic_style .= '
        html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs ul.tabs.wc-tabs {
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
        html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs ul.tabs.wc-tabs {
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
} else { // Vertical Right layout.
	$dynamic_style .= '
    .sptpro-smart-tabs.woocommerce div.product .woocommerce-tabs {
        display: flex;
        margin-top: 30px;
    }
    
    /* --- Left tab menu --- */
    .sptpro-smart-tabs.woocommerce div.product .woocommerce-tabs ul.wc-tabs {
        flex: 0 0 280px;
        display: flex;
        flex-direction: column;
        list-style: none;
        padding: 0;
        margin: 0;
        gap: ' . $margin_between_tabs . 'px;
        overflow: hidden;
    }
    
    .sptpro-smart-tabs.woocommerce div.product .woocommerce-tabs ul.tabs::before {
        border-bottom: none;
    }
    
    /* ---- Tab items style ---- */
     html .woocommerce-page div.product .woocommerce-tabs.wc-tabs-wrapper ul.tabs::before,
    html .woocommerce.sptpro-smart-tabs div.product .woocommerce-tabs.wc-tabs-wrapper ul.tabs li.active:before{
        background: none !important;
        border: none;
    }
   .sptpro-smart-tabs.woocommerce div.product .woocommerce-tabs ul.wc-tabs li {
        border: none !important;
        width: 100% !important;
        border-left: ' . $bottom_border_width . 'px solid transparent !important; 
    }
    .sptpro-smart-tabs.woocommerce div.product .woocommerce-tabs ul.wc-tabs li.active,
    .woocommerce div.product .woocommerce-tabs ul.wc-tabs li {
        margin: 0;
        cursor: pointer;
        font-weight: 500;
        transition: background 0.3s ease;
    }
    .sptpro-smart-tabs.woocommerce div.product .woocommerce-tabs ul.wc-tabs li:last-child {
        border-bottom: none;
    }
    .sptpro-smart-tabs.woocommerce div.product .woocommerce-tabs ul.wc-tabs li.active {
        border-left-color: ' . $bottom_border_color . ' !important; 
        z-index: 999;
        position: relative;
        width: 100%;
    }
    html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs.wc-tabs-wrapper #tab-description,
    html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs.wc-tabs-wrapper .woocommerce-Tabs-panel#tab-additional_information,
    html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs.wc-tabs-wrapper .woocommerce-Tabs-panel#tab-reviews,
    body.woocommerce.sptpro-smart-tabs .woocommerce-tabs.wc-tabs-wrapper .woocommerce-Tabs-panel {
        z-index: 999;
    }

    /* ----- Right content panel area ----- */

    body.woocommerce.sptpro-smart-tabs div.product .woocommerce-Tabs-panel {
        flex: 1 1 auto;
        min-width: 0;
        padding: 20px 25px;
        background: #fff;
        border-radius: 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        padding: ' . $desc_padding['top'] . 'px ' . $desc_padding['right'] . 'px ' . $desc_padding['bottom'] . 'px ' . $desc_padding['left'] . 'px;
    }
    
    /* Tabs vertical to horizontal for better responsiveness */
    @media (max-width: 768px) {
        .woocommerce div.product .woocommerce-tabs {
            flex-direction: column;
        }
        .woocommerce div.product .woocommerce-tabs ul.wc-tabs {
            flex-direction: row;
            flex-wrap: wrap;
            flex: auto;
            overflow-x: auto;
            border-radius: 4px;
            border: none;
        }
        .woocommerce div.product .woocommerce-tabs ul.wc-tabs li {
            white-space: nowrap;
            border-bottom: none;
        }
        .woocommerce div.product .woocommerce-tabs ul.wc-tabs li.active {
            border-left: none;
            border-bottom: ' . $bottom_border_width . 'px solid ' . $bottom_border_color . ';
        }
        .woocommerce div.product .woocommerce-Tabs-panel {
            margin-top: 15px;
            width: 100%;
        }
    }';

	$dynamic_style .= '
    .theme-hello-elementor div.product .woocommerce-tabs ul.tabs li {
        border: 0;
    }
    .woocommerce div.product .woocommerce-tabs {
        flex-direction: row-reverse;
    }
    .sptpro-smart-tabs.woocommerce div.product .woocommerce-tabs ul.wc-tabs li {
        border: none !important;
        border-right: ' . $bottom_border_width . 'px solid  ' . $tab_name_bg_color . ' !important;
    }
    .sptpro-smart-tabs.woocommerce div.product .woocommerce-tabs ul.wc-tabs li.active {
        border-right-color: ' . $bottom_border_color . ' !important;
        border-left: ' . $bottom_border_width . 'px solid ' . $tab_name_active_color . ';
    }
    html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs.wc-tabs-wrapper #tab-description,
    html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs.wc-tabs-wrapper .woocommerce-Tabs-panel#tab-additional_information,
    html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs.wc-tabs-wrapper .woocommerce-Tabs-panel#tab-reviews,
    body.woocommerce.sptpro-smart-tabs .woocommerce-tabs.wc-tabs-wrapper .woocommerce-Tabs-panel {
        margin-right: -' . $tabs_vertical_line['all'] . 'px !important;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        padding-right: ' . $space_between_title_and_desc . 'px;  /* Used as Vertical Gap */
        border-right: ' . $tabs_vertical_line['all'] . 'px solid ' . $tabs_vertical_line['color'] . ' !important;
    }
    .sptpro-smart-tabs.woocommerce div.product .woocommerce-tabs.wc-tabs-wrapper ul.wc-tabs li.active::after {
        left: 24px;
    }
    .sptpro-smart-tabs.woocommerce div.product .woocommerce-tabs.wc-tabs-wrapper ul.wc-tabs li::after {
        left: -1em;
        transform: rotate(180deg);
    }
    /* Remove top border from content panel from elementor preview tab */
    .woocommerce.sptpro-smart-tabs div.product.elementor .woocommerce-tabs .panel {
        border-top: none;
    }
    .woocommerce.sptpro-smart-tabs #content div.product.elementor .woocommerce-tabs ul.tabs::before {
        display: none;
    }';
}

$dynamic_style .= '
    body.woocommerce.sptpro-smart-tabs .woocommerce-tabs.wc-tabs-wrapper .woocommerce-Tabs-panel {
        padding: 0;
        margin: 0;
    }
';


/**
 * Typography Section Styling for Woo Tab Name.
 */
$dynamic_style .= '
    body.woocommerce.sptpro-smart-tabs #content-area div.product .woocommerce-tabs ul.tabs li a,
    html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs ul.wc-tabs li a {
        padding: 0;
        box-sizing: border-box;
        font-size: ' . $product_tabs_title_typo['font-size'] . 'px;
        text-align: ' . $product_tabs_title_typo['text-align'] . ';
        text-transform: ' . $product_tabs_title_typo['text-transform'] . ';
        line-height: ' . $product_tabs_title_typo['line-height'] . 'px;
        letter-spacing: ' . $product_tabs_title_typo['letter-spacing'] . 'px;
        color: ' . $tab_name_color['color'] . ' !important;
    ';

if ( ! empty( $product_tabs_title_typo['font-family'] ) ) {
	$font_style     = ! empty( $product_tabs_title_typo['font-style'] ) ? $product_tabs_title_typo['font-style'] : 'normal';
	$dynamic_style .= '
        font-family: ' . $product_tabs_title_typo['font-family'] . ';
        font-style: ' . $font_style . ';
        font-weight: ' . $product_tabs_title_typo['font-weight'] . ';
        ';
}

// Close the tab title typography block & start the hover/active/focus state.
$dynamic_style .= '} 
    html body.woocommerce.sptpro-smart-tabs div.product .woocommerce-tabs ul.tabs.wc-tabs li.active a {
        color: ' . $tab_name_color['active-color'] . ' !important;
    } 
    html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs ul.tabs.wc-tabs li.active a:focus {
    outline: none !important;}
    html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs ul.tabs.wc-tabs li:not(.active) a:hover {
        color: ' . $tab_name_color['hover-color'] . ' !important;
    }
';

/**
 * Typography Section Styling for Woo product Description Area.
 */
$dynamic_style .= '
html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs.wc-tabs-wrapper #tab-description,
 html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs.wc-tabs-wrapper .woocommerce-Tabs-panel#tab-additional_information,
 html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs.wc-tabs-wrapper .woocommerce-Tabs-panel#tab-reviews,
html body.woocommerce .woocommerce-tabs.wc-tabs-wrapper .woocommerce-Tabs-panel .sp-tab__tab-content {
    text-align: ' . $product_desc_typo['text-align'] . ';
    text-transform: ' . $product_desc_typo['text-transform'] . ';
    font-size: ' . $product_desc_typo['font-size'] . 'px;
    line-height: ' . $product_desc_typo['line-height'] . 'px;
    letter-spacing: ' . $product_desc_typo['letter-spacing'] . 'px;
    font-size: ' . $product_desc_typo['font-size'] . 'px !important;
    color: ' . $description_font_color . ' !important;';

if ( ! empty( $product_desc_typo['font-family'] ) ) {
	$font_style     = ! empty( $product_desc_typo['font-style'] ) ? $product_desc_typo['font-style'] : 'normal';
	$dynamic_style .= '
        font-family: ' . $product_desc_typo['font-family'] . ';
        font-style: ' . $font_style . ';
        font-weight: ' . $product_desc_typo['font-weight'] . ';';
}
// Close the description typography block.
$dynamic_style .= '}';

// Show/Hide tabs on mobile.
if ( ! $show_tabs_on_mobile ) {
	$dynamic_style .= '
    @media (max-width: 480px) {
        .woocommerce .woocommerce-tabs.wc-tabs-wrapper {
            display: none !important;
        }
    }';
}

// Hide WooCommerce’s default related products section based on the show/hide option.
if ( ! $show_wc_default_related_products ) {
	$dynamic_style .= '
    html body.woocommerce.sptpro-smart-tabs .wp-block-woocommerce-product-collection,
    html body.woocommerce.sptpro-smart-tabs .wp-block-woocommerce-related-products,
    html body.woocommerce.sptpro-smart-tabs section.related.products {
        display: none;
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

/**
 * Divi theme compatibility:
 * When the active theme is Divi, apply specific dynamic CSS to adjust the WooCommerce
 * tabs layout and styles inside the "#content-area" wrapper of Divi theme.
 */
if ( 'divi' === strtolower( get_template() ) ) {
	$dynamic_style .= '
    /* --- Product tab style ---  */
	body.woocommerce.sptpro-smart-tabs #content-area .woocommerce-tabs {
	    border: none;
	}
	body.woocommerce.sptpro-smart-tabs #content-area div.product .woocommerce-tabs ul.tabs {
	    background: none !important;
	}
    body.woocommerce.sptpro-smart-tabs #content-area div.product .woocommerce-tabs ul.tabs li.active,
    body.woocommerce.sptpro-smart-tabs #content-area div.product .woocommerce-tabs ul.tabs li {
        border-right: 0;
    }
    html body.woocommerce.sptpro-smart-tabs #content-area div.product .woocommerce-tabs ul.tabs li.active {
        background-color: ' . $tab_name_active_color . ' !important;
    }body.woocommerce.sptpro-smart-tabs #content-area div.product .woocommerce-tabs ul.tabs li.active:before {
        display: block; }
    body.woocommerce.sptpro-smart-tabs #content-area div.product .woocommerce-tabs ul.tabs li a {
        width: 100%;
        padding: ' . $_name_padding_top . 'px ' . $_name_padding_right . 'px ' . $_name_padding_bottom . 'px ' . $_name_padding_left . 'px !important;
    }body.woocommerce.sptpro-smart-tabs #content-area div.product .woocommerce-tabs ul.tabs li:not(.active) a:hover {
        color: ' . $tab_name_color['hover-color'] . ' !important;}';
}

/**
 * Avada theme compatibility:
 * When the active theme is Avada, apply specific dynamic CSS to adjust the WooCommerce tabs layout and styles.
 */
if ( 'avada' === strtolower( get_template() ) ) {
	$dynamic_style .= '
    /* --- Product tab style ---  */
	html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs ul.wc-tabs {
        width: 100%;
        float: none;
    }
    .sptpro-smart-tabs .woocommerce-tabs .tabs li a {
        border-bottom: 0;
    }
    html body.woocommerce.sptpro-smart-tabs ul.wc-tabs li.active a {
        background-color: ' . $tab_name_active_color . ' !important;
    }
    html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs .panel {
        border: none;
    }
    .sptpro-tabs-loaded .sp-tab__tab-content .featured-image.crossfade-images {
        height: auto !important;
    }
';
}
