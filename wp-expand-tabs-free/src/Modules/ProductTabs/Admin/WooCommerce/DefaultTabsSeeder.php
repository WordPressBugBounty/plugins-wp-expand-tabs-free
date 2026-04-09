<?php
/**
 * Class DefaultTabsSeeder.
 *
 * Responsible for creating and managing default WooCommerce product tabs
 * (Description, Additional Information, and Reviews) within Smart Tabs Pro.
 *
 * @since      3.2.1
 * @package    ShapedPlugin\SmartTabsFree
 * @subpackage Modules\ProductTabs\WooCommerce
 */

namespace ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin\WooCommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles creation of default WooCommerce tabs (Description, Reviews, etc.).
 *
 * @since 3.2.1
 */
class DefaultTabsSeeder {
	/**
	 * Initialize hooks.
	 *
	 * @param object $loader The loader instance to register hooks with.
	 */
	public function register( $loader ) {
		$loader->add_action( 'admin_init', $this, 'create_default_tabs' );
	}

	/**
	 * Create default WooCommerce tabs if they do not exist.
	 *
	 * This function checks for the existence of default WooCommerce tabs
	 * and creates them if they are not found. The tabs include Description,
	 * Reviews, and Additional Information.
	 *
	 * @return void
	 */
	public function create_default_tabs() {
		$is_created_default_tabs = 'sp_default_woocommerce_tabs_created';

		// Check if tabs have already been created in the database.
		if ( get_option( $is_created_default_tabs, false ) ) {
			return;
		}

		global $wpdb;

		$default_tabs = array(
			'description'            => 'Description',
			'additional_information' => 'Additional Information',
			'reviews'                => 'Reviews',
		);

		// Get all existing slugs in one query.
		$existing_slugs = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT meta_value 
				FROM {$wpdb->postmeta} pm
				INNER JOIN {$wpdb->posts} p 
					ON pm.post_id = p.ID
				WHERE pm.meta_key = %s
				AND p.post_type = %s
				AND p.post_status != 'trash'",
				'_wc_default_tab_slug',
				'sp_products_tabs'
			)
		);

		$menu_order = 0;
		foreach ( $default_tabs as $slug => $title ) {
			if ( in_array( $slug, $existing_slugs, true ) ) {
				continue;
			}

			$post_id = wp_insert_post(
				array(
					'post_title'  => $title,
					'post_type'   => 'sp_products_tabs',
					'post_status' => 'publish',
					'menu_order'  => $menu_order++,
					'meta_input'  => array(
						'_wc_default_tab_slug' => $slug,
						'show_product_tabs'    => true,
					),
				)
			);

			if ( $post_id && ! is_wp_error( $post_id ) ) {
				$existing_slugs[] = $slug; // Track locally to avoid duplicates in same run.
			}
		}

		// Mark that default tabs are created.
		update_option( $is_created_default_tabs, true );
	}
}
