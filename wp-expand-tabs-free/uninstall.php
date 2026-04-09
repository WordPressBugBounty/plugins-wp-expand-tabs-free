<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://shapedplugin.com/
 * @since      2.0.0
 *
 * @package    WP_Tabs
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Delete plugin data function.
 *
 * @return void
 */
function sp_wptabspro_delete_plugin_data() {

	// Delete plugin option settings.
	$option_name = 'sp-tab__settings';
	delete_option( $option_name );
	delete_site_option( $option_name ); // For site options in Multisite.

	// Delete product tab style option settings.
	$product_tabs_style_options = 'sptabs_product_tabs_settings';
	delete_option( $product_tabs_style_options );
	delete_site_option( $product_tabs_style_options ); // For site options in Multisite.
	// Delete product tab style option settings.
	$product_tabs_advanced_tab_options = 'sp_products_tabs_advanced';
	delete_option( $product_tabs_advanced_tab_options );
	delete_site_option( $product_tabs_advanced_tab_options ); // For site options in Multisite.

	// Delete plugin custom post types data on plugin uninstallation.
	sp_tabs_delete_posts_by_type(
		array(
			'sp_wp_tabs',
			'sp_products_tabs',
		)
	);

	// Delete Team post meta.
	delete_post_meta_by_key( 'sp_tab_source_options' );
	delete_post_meta_by_key( 'sp_tab_shortcode_options' );
	delete_post_meta_by_key( 'sp_tab_display_shortcode_sidebar' );

	// Delete offer banner related option keys.
	delete_option( 'shapedplugin_offer_banner_dismissed_black_friday_2025' );
	delete_option( 'shapedplugin_offer_banner_dismissed_new_year_2026' );
}

/**
 * Delete all posts of given post types in batches.
 *
 * @param array $post_types Post types to delete.
 * @param int   $batch_size Number of posts per batch (default: 200).
 */
function sp_tabs_delete_posts_by_type( array $post_types, int $batch_size = 200 ) {
	do {
		$post_ids = get_posts(
			array(
				'post_type'      => $post_types,
				'post_status'    => 'any',
				'posts_per_page' => $batch_size,
				'fields'         => 'ids',
				'orderby'        => 'ID',
				'order'          => 'ASC',
				'no_found_rows'  => true,
			)
		);

		foreach ( $post_ids as $post_id ) {
			wp_delete_post( $post_id, true );
		}
	} while ( ! empty( $post_ids ) ); // Continue until no more tab posts are found.
}

// Load Smart Tabs file.
require_once plugin_dir_path( __FILE__ ) . '/plugin-main.php';
$spwptabspro_plugin_settings = get_option( 'sp-tab__settings' );
$spwptabspro_data_delete     = $spwptabspro_plugin_settings['sptpro_data_remove'];

if ( $spwptabspro_data_delete ) {
	sp_wptabspro_delete_plugin_data();
}
