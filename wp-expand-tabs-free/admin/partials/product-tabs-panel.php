<?php
/**
 * The product custom overriding settings in WooCommerce Product Tabs Panel.
 *
 * @link       https://shapedplugin.com/
 * @since      2.2.3
 *
 * @package wp-expand-tabs-free
 * @subpackage wp-expand-tabs-free/admin
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

	if ( ! WP_Tabs_Product_Tab::is_default_tab_globally_enabled( $key ) ) {
		continue;
	}

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

$product_id = $post->ID;
$product    = wc_get_product( $product_id );

foreach ( $all_tabs as $tab_id ) {
	$settings      = get_post_meta( $tab_id, 'sptpro_woo_product_tabs_settings', true );
	$tab_type      = $settings['tabs_content_type'] ?? 'content';
	$tab_name      = $settings['product_tab_title_section']['tab_title'] ?? __( '(No Title)', 'wp-expand-tabs-free' );
	$check_exclude = $settings['tabs_exclude'] ?? false;
	$excluded      = $settings['exclude_specific'] ?? array();

	// Do not show tab in the list if excluded for specific product.
	if ( $check_exclude && ! empty( $excluded ) && in_array( $product_id, $excluded, false ) ) {
		continue;
	}

	// Skip if no settings found and not a content/products tab.
	if ( empty( $settings ) && ( 'content' !== $tab_type || 'products' !== $tab_type ) ) {
		continue;
	}

	// Checkbox values (override + hide).
	$hide_checked     = checked( get_post_meta( $post->ID, "sp_tab_{$tab_id}_hide", true ), 'yes', false );
	$override_enabled = get_post_meta( $tab_id, 'sp_override_tab_enabled', true ) === 'yes';
	$override_checked = checked( get_post_meta( $product_id, "sp_tab_{$tab_id}_override", true ), 'yes', false );

	?>
	<div class="sp-tab-item">
		<div class="sp-tab-header">
			<strong><?php echo esc_html( $tab_name ); ?></strong>
			<div class="sp-tab-controls">
				<?php if ( $override_enabled ) : ?>
					<label>
						<input type="checkbox" name="sp_tab_<?php echo esc_attr( $tab_id ); ?>_override" value="yes" <?php echo esc_attr( $override_checked ); ?> class="sp-tab-toggle">
						<?php esc_html_e( 'Override', 'wp-expand-tabs-free' ); ?>
					</label>
				<?php endif; ?>
				<label>
					<input type="checkbox" name="sp_tab_<?php echo esc_attr( $tab_id ); ?>_hide" value="yes" <?php echo esc_attr( $hide_checked ); ?>>
					<?php esc_html_e( 'Hide', 'wp-expand-tabs-free' ); ?>
				</label>
			</div>
		</div>

		<div class="sp-tab-content" style="<?php echo ( $override_enabled && $override_checked ) ? '' : 'display:none;'; ?>">
			<?php
			switch ( $tab_type ) {
				case 'content':
					$content = get_post_meta( $post->ID, "sp_tab_{$tab_id}_content", true );
					wp_editor(
						$content,
						"sp_tab_{$tab_id}_content",
						array(
							'textarea_name' => "sp_tab_{$tab_id}_content",
							'textarea_rows' => 5,
						)
					);
					break;
			}
			?>
		</div>

	</div>
	<?php
}
	echo '</div>';
	echo '<div class="sp-product-tabs-notice">
	<div class="wptabspro-notice wptabspro-notice-normal">
	Want to add custom tab content types, such as Image Gallery, Videos, FAQs, Downloads, Map, or Forms — and override/edit them per product?
	<a href="https://wptabs.com/pricing/?ref=1" target="_blank"><b>Upgrade to Pro!</b></a>
	</div>
	<div class="clear"></div>
	</div>';
echo '</div>';
