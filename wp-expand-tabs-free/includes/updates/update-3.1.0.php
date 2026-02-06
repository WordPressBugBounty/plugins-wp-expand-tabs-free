<?php
/**
 * Update version.
 *
 * @since      3.1.0
 * @package wp-expand-tabs-free
 * @subpackage wp-expand-tabs-free/admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

update_option( 'wp_tabs_version', '3.1.0' );
update_option( 'wp_tabs_db_version', '3.1.0' );

$product_tabs_settings = get_option( 'sptabs_product_tabs_settings', array() ); // Main key of woo product tabs settings.

$tabs_typo_properties = $product_tabs_settings['product_tabs_title_typo'] ?? array(
	'font-family'    => '',
	'font-weight'    => '600',
	'font-style'     => 'normal',
	'font-size'      => '16',
	'line-height'    => '22',
	'letter-spacing' => '0',
	'text-align'     => 'center',
	'text-transform' => 'none',
	'color'          => '#444444',
	'hover_color'    => '#595959',
	'active_color'   => '#111111',
	'type'           => 'google',
);
$tabs_line_position   = isset( $product_tabs_settings['tabs_bottom_line_position'] ) ? $product_tabs_settings['tabs_bottom_line_position'] : '';

if ( isset( $product_tabs_settings['product_tabs_title_typo'] ) ) {
	$product_tabs_settings['tab_name_color'] = array(
		'color'        => $tabs_typo_properties['color'] ?? '#444444',
		'active-color' => $tabs_typo_properties['active_color'] ?? '#111111',
		'hover-color'  => $tabs_typo_properties['hover_color'] ?? '#595959',
	);
}

if ( 'bottom-line' === $tabs_line_position ) {
	$product_tabs_settings['sptpro_tab_style'] = 'default';
}

if ( 'top-line' === $tabs_line_position ) {
	$product_tabs_settings['sptpro_tab_style'] = 'top-line';
}

// Initialize and update product description color settings.
$old_desc_background_color = $product_tabs_settings['desc_background_color'] ?? '#ffffff';
$product_desc_typo         = $product_tabs_settings['product_desc_typo'] ?? array();

$product_tabs_settings['description_color'] = array(
	'color'    => $product_desc_typo['color'] ?? '#444',
	'bg_color' => $old_desc_background_color,
);

// Update product Tabs Layout.
update_option( 'sptabs_product_tabs_settings', $product_tabs_settings );
