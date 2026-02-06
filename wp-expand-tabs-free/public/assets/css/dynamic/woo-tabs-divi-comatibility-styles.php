<?php
/**
 * Dynamic CSS for WooCommerce Product Tabs (Divi Theme Compatibility).
 *
 * This file generates dynamic CSS rules specifically designed to ensure compatibility
 * and proper styling with the Divi theme when single product tabs are overridden.
 *
 * @link       https://shapedplugin.com/
 * @since      3.1.0
 * @package    WP_Tabs
 * @subpackage WP_Tabs/public
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/*
* Dynamic CSS for Divi Theme Compatibility.
*/
$dynamic_style .= '
	html body.woocommerce.sptpro-smart-tabs #et-boc .et_pb_wc_tabs {
	    border: none;
	}

    html body.woocommerce.sptpro-smart-tabs #et-boc .et_pb_wc_tabs .et_pb_tabs_controls {
        margin: 0 !important;
        margin-bottom: ' . $space_between_title_and_desc . 'px !important;
        display: flex;
        flex-wrap: wrap;
        gap: ' . $margin_between_tabs . 'px; /* Space between tab items */
        background: none;
    }

    html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tab {
        padding: 0;
    }

    /* ---- Tab items style ---- */
    html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls li {
        text-align: center;
        position: relative;
        padding: 0 !important;
        background-color: ' . $tab_name_bg_color . ' !important;
        border: none;
    }

    html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .accordion-header,
    html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .accordion-header {
        background-color: ' . $tab_name_bg_color . ';
    }

    /* Hover state for Divi tab items and accordion headers */
    html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls li:hover,
    html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .accordion-header:hover {
        background-color: ' . $tab_name_hover_color . ' !important;
    }

    /* Active state for Divi tab items and accordion headers */
    html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls li.et_pb_tab_active,
    html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .accordion-header.active {
        background-color: ' . $tab_name_active_color . ' !important;
    }

    body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls li a {
        width: 100%; /* Ensure link fills the li */
        display: block;
        padding: ' . $_name_padding_top . 'px ' . $_name_padding_right . 'px ' . $_name_padding_bottom . 'px ' . $_name_padding_left . 'px !important;
    }

    /* Styling for the custom tab content wrapper */
    html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tab_content:not(:has(.sp-tab__tab-content)),
    html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tab .sp-tab__tab-content {
        max-height: 100%;
        background-color: ' . $description_bg_color . ';
        border: ' . $desc_border_width . 'px ' . $desc_border_style . ' ' . $desc_border_color . ';
        border-radius: ' . $desc_border_radius . 'px;
        padding: ' . $desc_padding['top'] . 'px ' . $desc_padding['right'] . 'px ' . $desc_padding['bottom'] . 'px ' . $desc_padding['left'] . 'px;
    }

    .woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls span.tab_title_area,
    .woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .accordion-header span.tab_title_area { /* Adjust for accordion too */
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .woocommerce-page div.product .woocommerce-tabs ul.tabs::before,
    .woocommerce.sptpro-smart-tabs div.product .woocommerce-tabs ul.tabs li.active:before{
        background: none;
        border: none;
    }

    /* Default WooCommerce tabs content padding */
    html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tab_content #tab-description,
    html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tab_content .woocommerce-Tabs-panel#tab-additional_information,
    html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tab_content .woocommerce-Tabs-panel#tab-reviews,
    html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tab .sp-tab__tab-content {
        max-height: 100%;
        background-color: ' . $description_bg_color . ';
        border: ' . $desc_border_width . 'px ' . $desc_border_style . ' ' . $desc_border_color . ';
        border-radius: ' . $desc_border_radius . 'px;
        padding: ' . $desc_padding['top'] . 'px ' . $desc_padding['right'] . 'px ' . $desc_padding['bottom'] . 'px ' . $desc_padding['left'] . 'px !important;
}';

// Tabs style adjustments for non-vertical layouts only.
if ( $is_horizontal_layout ) {
	$dynamic_style .= '
        /* Active tab indicator using Divi\'s active class */
        html body.woocommerce #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls li.et_pb_tab_active::before {
            display: block;
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

	// Tabs alignments. This is now applied to .et_pb_tabs_controls above.
	// if ( 'horizontal' === $product_tabs_layout || 'below-summary' === $product_tabs_layout ) {
	// $dynamic_style .= '
	// html body.woocommerce ul.tabs.wc-tabs:not(.is-width-constrained) {
	// margin: 0;
	// display: flex;
	// flex-wrap: wrap;
	// gap: ' . $margin_between_tabs . 'px;
	// justify-content: ' . $product_tabs_alignment . ';
	// }';
	// }
	if ( 'top-line' === $tabs_style ) {
		$dynamic_style .= '
            html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls {
                border-top: ' . $tabs_bottom_line['all'] . 'px solid ' . $tabs_bottom_line['color'] . ' !important;  
            }
            /* Active tab top line */
            html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls li.et_pb_tab_active::before {
                bottom: unset;
                top: 0;
            }
            html body.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls li:hover {
                border: 0;
            }
            html body.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls li.et_pb_tab_active {
                border-bottom: 0;
            }';
	}
}

/**
 * Vertical layout.
 */
if ( ! $is_horizontal_layout ) {
	$dynamic_style .= '
        .sptpro-smart-tabs.woocommerce #et-boc .et-l .et_pb_wc_tabs {
            display: flex;
            margin-top: 30px;
        }
        
        /* --- Left tab menu --- */
        .sptpro-smart-tabs.woocommerce #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls {
            flex: 0 0 280px;
            display: flex;
            flex-direction: column;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: ' . $margin_between_tabs . 'px;
            overflow: hidden;
        }
        
        .sptpro-smart-tabs.woocommerce #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls::before {
            border-bottom: none;
        }
        
        /* ---- Tab items style ---- */
        .sptpro-smart-tabs.woocommerce #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls li {
            border: none !important;
            width: 100% !important;
            height: 0 !important;
            border-left: ' . $bottom_border_width . 'px solid transparent !important; 
        }
        .sptpro-smart-tabs.woocommerce #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls li.et_pb_tab_active,
        .woocommerce #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls li {
            margin: 0;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.3s ease;
        }
        .sptpro-smart-tabs.woocommerce #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls li:last-child {
            border-bottom: none;
        }
        .sptpro-smart-tabs.woocommerce #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls li.et_pb_tab_active {
            z-index: 999;
            position: relative;
            width: 100%;
        }
        .woocommerce #et-boc .et-l .et_pb_wc_tabs .et_pb_all_tabs {
            width: 100%;
        }
        /* Target Divi tab content wrappers that contain your sp-tab__tab-content */
        html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tab.et_pb_active_content .sp-tab__tab-content,
        html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tab_content #tab-description,
        html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tab_content .woocommerce-Tabs-panel#tab-additional_information,
        html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tab_content .woocommerce-Tabs-panel#tab-reviews,
        body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tab .sp-tab__tab-content {
            z-index: 999;
        }

        /* ----- Right content panel area ----- */
        body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tab {
            padding: 0;
            height: 100%;
        }
        body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tab_content {
            flex: 1 1 auto;
            min-width: 0;
            height: 100%;
            padding: 0;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
            /* padding: ' . $desc_padding['top'] . 'px ' . $desc_padding['right'] . 'px ' . $desc_padding['bottom'] . 'px ' . $desc_padding['left'] . 'px; */
        }
        
        /* Tabs vertical to horizontal for better responsiveness */
        @media (max-width: 768px) {
            .woocommerce #et-boc .et-l .et_pb_wc_tabs {
                flex-direction: column;
            }
            .woocommerce #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls {
                flex-direction: row;
                flex-wrap: wrap;
                flex: auto;
                overflow-x: auto;
                border-radius: 4px;
                border: none;
            }
            .woocommerce #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls li {
                white-space: nowrap;
                border-bottom: none;
            }
            .woocommerce #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls li.et_pb_tab_active {
                border-left: none;
                border-bottom: ' . $bottom_border_width . 'px solid ' . $bottom_border_color . ';
            }
            .woocommerce #et-boc .et-l .et_pb_wc_tabs .et_pb_all_tabs {
                margin-top: 15px;
                width: 100%;
            }
        }';

	$tab_indicator_style = $sp_woo_tabs_settings['active_tab_indicator_style'] ?? '';
	$indicator_arrow     = $tab_indicator_style['indicator_style'] ?? 'none';

	if ( 'vertical-right' === $product_tabs_layout ) {
		$dynamic_style .= '
            .woocommerce #et-boc .et-l .et_pb_wc_tabs {
                flex-direction: row-reverse;
            }
            .sptpro-smart-tabs.woocommerce #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls li {
                border: none !important;
                border-right: ' . $bottom_border_width . 'px solid  ' . $tab_name_bg_color . ' !important;
            }
            .sptpro-smart-tabs.woocommerce #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls li.et_pb_tab_active {
                border-right-color: ' . $bottom_border_color . ' !important;
                border-left: ' . $bottom_border_width . 'px solid ' . $tab_name_active_color . ';
            }
            /* Target Divi tab content wrappers */
            html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tab_content #tab-description,
            html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tab_content .woocommerce-Tabs-panel#tab-additional_information,
            html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tab_content .woocommerce-Tabs-panel#tab-reviews,
            body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tab .sp-tab__tab-content {
                margin-right: -' . $tabs_vertical_line['all'] . 'px !important;
                border-top-right-radius: 0;
                border-bottom-right-radius: 0;
                padding-right: ' . $space_between_title_and_desc . 'px;  /* Used as Vertical Gap */
                border-right: ' . $tabs_vertical_line['all'] . 'px solid ' . $tabs_vertical_line['color'] . ';
            }

            .sptpro-smart-tabs.woocommerce #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls li::after {
                left: -1em;
                transform: rotate(-180deg) translateY(50%);
            }

            .sptpro-smart-tabs.woocommerce #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls li.et_pb_tab_active::after {
                left: 24px;
                right: auto;
            }
            .sptpro-smart-tabs.woocommerce.et-db #et-boc .et-l ul.et_pb_tabs_controls:after {
                display: none;
            }';
	}

	// Full width Tabs on small screen for vertical layouts (Left & Right).
	if ( 'full_width' === $tabs_on_small_screen ) {
		$dynamic_style .= '
            /* Responsive Columns for the Image Grid. */
            @media (max-width: ' . intval( $set_small_screen_width ) . 'px) {
                .sptpro-smart-tabs.woocommerce #et-boc .et-l .et_pb_wc_tabs {
                    flex-direction: column;
                }         
                .sptpro-smart-tabs.woocommerce #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls {
                    flex-direction: row;
                    flex-wrap: wrap;
                    flex: auto;
                }
            }';
	}
}

/**
 * Typography Section Styling for Woo Product Tabs Area.
 */
$dynamic_style .= '
html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls li a,
html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .accordion-header .accordion-title a {
	padding: 0;
	box-sizing: border-box;
	text-align: ' . $product_tabs_title_typo['text-align'] . ';
	text-transform: ' . $product_tabs_title_typo['text-transform'] . ';
	font-size: ' . $product_tabs_title_typo['font-size'] . 'px;
	line-height: ' . $product_tabs_title_typo['line-height'] . 'px;
	letter-spacing: ' . $product_tabs_title_typo['letter-spacing'] . 'px;
	color: ' . $tab_name_color['color'] . ' !important;
	outline: 0;
	text-decoration: none;';

if ( ! empty( $product_tabs_title_typo['font-family'] ) ) {
	$font_style     = ! empty( $product_tabs_title_typo['font-style'] ) ? $product_tabs_title_typo['font-style'] : 'normal';
	$dynamic_style .= '
        font-family: ' . $product_tabs_title_typo['font-family'] . ';
        font-style: ' . $font_style . ';
        font-weight: ' . $product_tabs_title_typo['font-weight'] . ';
        ';
}

$dynamic_style .= '}
    html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls li.et_pb_tab_active a,
    html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .accordion-header.active .accordion-title a {
        color: ' . $tab_name_color['active-color'] . ' !important;
    }
    html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls li:not(.et_pb_tab_active) a:hover,
    html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .accordion-header:not(.active):hover .accordion-title a {
        color: ' . $tab_name_color['hover-color'] . ' !important;
    }
    body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tabs_controls span.tab_title_area {
        justify-content: ' . $product_tabs_title_typo['text-align'] . ';
    }';

/**
 * Typography Section Styling for Woo Description Area.
 */
$dynamic_style .= '
    html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tab_content:not(:has(.sp-tab__tab-content)),
	html body.woocommerce #et-boc .et-l .et_pb_wc_tabs .et_pb_tab .sp-tab__tab-content {
	text-align: ' . $product_desc_typo['text-align'] . ';
	text-transform: ' . $product_desc_typo['text-transform'] . ';
	font-size: ' . $product_desc_typo['font-size'] . 'px;
	line-height: ' . $product_desc_typo['line-height'] . 'px;
	letter-spacing: ' . $product_desc_typo['letter-spacing'] . 'px;
	color: ' . $description_font_color . ' !important;';

if ( ! empty( $product_desc_typo['font-family'] ) ) {
	$font_style     = ! empty( $product_desc_typo['font-style'] ) ? $product_desc_typo['font-style'] : 'normal';
	$dynamic_style .= '
        font-family: ' . $product_desc_typo['font-family'] . ';
        font-style: ' . $font_style . ';
        font-weight: ' . $product_desc_typo['font-weight'] . ';';
}
	$dynamic_style .= '}';


// Advanced Settings.
$product_tabs_advanced            = get_option( 'sp_products_tabs_advanced' ); // Main key of woo product tabs advanced.
$show_tabs_on_mobile              = isset( $product_tabs_advanced['show_product_tabs_on_mobile'] ) ? $product_tabs_advanced['show_product_tabs_on_mobile'] : false;
$show_tab_heading_inside_tab      = $product_tabs_advanced['show_tab_heading_inside_tab'] ?? true;
$show_wc_default_related_products = $product_tabs_advanced['show_wc_default_related_products'] ?? true;

// Show/Hide tabs on mobile.
if ( ! $show_tabs_on_mobile ) {
	$dynamic_style .= '
	@media (max-width: 480px) {
		/* Target Divi WooCommerce tabs module */
		.woocommerce .et_pb_wc_tabs {
			display: none !important;
		}
	}';
}

// Hide repeated tab headings (e.g., Description, Reviews, & Additional Information) displayed inside the tab content based on the option.
if ( ! $show_tab_heading_inside_tab ) {
	$dynamic_style .= '
	/* Target headings within Divi\'s tab content wrappers */
	html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tab_content > h2:first-of-type,
	html body.woocommerce.sptpro-smart-tabs #et-boc .et-l .et_pb_wc_tabs .et_pb_tab_content h2.woocommerce-Reviews-title {
		display: none;
	}';
}

// Hide WooCommerce’s default related products section based on the show/hide option.
if ( ! $show_wc_default_related_products ) {
	$dynamic_style .= '
	html body.woocommerce.sptpro-smart-tabs .wp-block-woocommerce-product-collection,
	html body.woocommerce.sptpro-smart-tabs .et_pb_wc_related_products,
    html body.woocommerce.sptpro-smart-tabs section.related.products {
		display: none;
	}';
}

// Product Tabs Preloader.
// if ( $preloader ) {
// $dynamic_style .= '
// #et-boc .et-l .et_pb_wc_tabs {
// opacity: 0;
// position: relative;
// }
// .sptpro-tabs-preloader {
// position: absolute;
// top: 0;
// left: 0;
// width: 100%;
// height: 100%;
// display: flex;
// align-items: center;
// justify-content: center;
// background: #fff;
// z-index: 10;
// }
// .sptpro-spinner {
// width: 35px;
// height: 35px;
// border: 4px solid #ccc;
// border-top-color: #0073aa;
// border-radius: 50%;
// animation: sptpro-spin 0.8s linear infinite;
// }
// @keyframes sptpro-spin {
// to {
// transform: rotate(360deg);
// }
// }
// body.sptpro-tabs-loaded .woocommerce-tabs {
// opacity: 1;
// }
// body.sptpro-tabs-loaded .sptpro-tabs-preloader {
// display: none;
// }';
// }


// Tabs on small screen.
if ( 'accordion_mode' === $tabs_on_small_screen ) {
	$dynamic_style .= '
        /* Responsive Columns for the Image Grid. */
        @media (max-width: ' . intval( $set_small_screen_width ) . 'px) {
            /* Accordion style for tabs on small screen */
            .woocommerce.sptpro-smart-tabs .accordion-header {
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

            html body.woocommerce .accordion-header, 
            html body.woocommerce ul.tabs.wc-tabs {
                border-bottom: 1px solid #9B9B9B;
            }

            html body.woocommerce.sptpro-smart-tabs #tab-description,
            html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs.wc-tabs-wrapper .woocommerce-Tabs-panel#tab-additional_information,
            html body.woocommerce.sptpro-smart-tabs .woocommerce-tabs.wc-tabs-wrapper .woocommerce-Tabs-panel#tab-reviews,
            body.woocommerce.sptpro-smart-tabs .woocommerce-tabs.wc-tabs-wrapper .woocommerce-Tabs-panel {
                border: 0;
                margin-left: 0;
            }

            .woocommerce .woocommerce-tabs {
                flex-direction: column;
            }
    ';

	// Conditional CSS inside the same @media block.
	if ( $expand_and_collapse_icon ) {
		$dynamic_style .= '
            /** Accordion style for mobile devices */
            html body.woocommerce.sptpro-smart-tabs .accordion-header {
                position: relative;
                display: block;
            }

            html body.woocommerce.sptpro-smart-tabs .accordion-header span::after {
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

            html body.woocommerce.sptpro-smart-tabs .accordion-header.active span::after {
                content: "\2212";
            }
        ';
	}

	// Close the @media block.
	$dynamic_style .= '
        }
    ';
}
