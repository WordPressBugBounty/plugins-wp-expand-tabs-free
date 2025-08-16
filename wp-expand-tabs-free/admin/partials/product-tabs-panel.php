<?php
/**
 * The product custom overriding settings in WooCommerce Product Tabs Panel.
 *
 * @link       https://shapedplugin.com/
 * @since      2.2.3
 *
 * @package    WP_Tabs_Pro
 * @subpackage WP_Tabs_Pro/admin
 */

defined( 'ABSPATH' ) || exit;
global $post;

echo '<div id="sp_product_tabs_panel" class="panel woocommerce_options_panel">';
$default_tabs = array(
	'description'            => __( 'Description', 'wp-expand-tabs-free' ),
	'additional_information' => __( 'Additional Information', 'wp-expand-tabs-free' ),
	'reviews'                => __( 'Reviews', 'wp-expand-tabs-free' ),
);

echo '<div class="sp-tab-accordion-wrapper">';
foreach ( $default_tabs as $key => $label ) {
	$hide_meta_key = "sp_wc_tab_{$key}_hide";
	$hide_checked  = checked( get_post_meta( $post->ID, $hide_meta_key, true ), 'yes', false );

	echo '<div class="sp-tab-item">';
	echo '<div class="sp-tab-header">';
	echo '<strong>' . esc_html( $label ) . '</strong>';
	echo '<div class="sp-tab-controls">';
	echo '<label>';
	echo '<input type="checkbox" name="' . esc_attr( $hide_meta_key ) . '" value="yes" ' . esc_attr( $hide_checked ) . '>';
	echo esc_html__( 'Hide', 'wp-expand-tabs-free' );
	echo '</label>';
	echo '</div></div></div>';
}

$all_tabs = get_posts(
	array(
		'post_type'      => 'sp_products_tabs',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'fields'         => 'ids',
		'no_found_rows'  => true,
	)
);

// If there are no custom tabs, return early.
if ( empty( $all_tabs ) ) {
	return;
}

foreach ( $all_tabs as $tab_id ) {
	$settings = get_post_meta( $tab_id, 'sptpro_woo_product_tabs_settings', true );
	$tab_type = $settings['tabs_content_type'] ?? 'content';
	$tab_name = $settings['product_tab_title_section']['tab_title'] ?? __( '(No Title)', 'wp-expand-tabs-free' );

	if ( empty( $settings ) && ( 'content' !== $tab_type || 'products' !== $tab_type ) ) {
		continue;
	}
	?>
	<div class="sp-tab-item">
		<div class="sp-tab-header">
			<strong><?php echo esc_html( $tab_name ); ?></strong>
		</div>
	</div>
	<?php
}
	echo '</div>';
	echo '<div class="sp-product-tabs-notice">
	<div class="wptabspro-notice wptabspro-notice-normal">
	Need unique content for each product? Override tab content individually —
	<a href="https://wptabs.com/pricing/?ref=1" target="_blank"><b>Upgrade to Pro!</b></a>
	</div>
	<div class="clear"></div>
	</div>';
echo '</div>';
