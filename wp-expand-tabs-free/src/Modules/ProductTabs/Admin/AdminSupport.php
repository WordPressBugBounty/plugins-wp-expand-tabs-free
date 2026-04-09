<?php
/**
 * The central class that registers all admin-side support functionality of Product Tabs module.
 *
 * Handles Product Tabs admin-side enhancements:
 * - Restricting capabilities for default WooCommerce tabs
 * - Ordering tabs by menu order
 * - Customizing admin post list pagination
 * - Customizing bulk action messages
 *
 * @since 3.2.1
 * @package ShapedPlugin\SmartTabsFree
 * @subpackage Modules\ProductTabs\Admin
 */

namespace ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use ShapedPlugin\SmartTabsFree\Core\Loader;
use ShapedPlugin\SmartTabsFree\Core\Constants;

/**
 * Class AdminSupport
 *
 * The central class that registers all admin-side support functionality.
 *
 * @since 3.2.1
 */
class AdminSupport {
	/**
	 * The loader that registers admin support hooks.
	 *
	 * @var Loader
	 */
	private $loader;

	/**
	 * Constructor.
	 *
	 * @param Loader $loader The loader instance responsible for managing hooks.
	 */
	public function __construct( Loader $loader ) {
		$this->loader = $loader;
	}

	/**
	 * Initialize and register all admin support components.
	 *
	 * @return void
	 */
	public function register() {
		$this->register_permission_hooks();
		$this->register_admin_query_hooks();
	}

	/**
	 * Register permission hooks .
	 */
	private function register_permission_hooks() {
		$this->loader->add_filter( 'user_has_cap', $this, 'sp_restrict_default_tab_caps', 10, 4 );
	}

	/**
	 * Register admin query and post-list behavior hooks.
	 *
	 * Ensures Product Tabs are ordered properly,
	 * sets custom per-page counts, and modifies bulk messages.
	 *
	 * @return void
	 */
	private function register_admin_query_hooks() {
		$this->loader->add_action( 'pre_get_posts', $this, 'sp_products_tabs_orderby_menu_order' );
		$this->loader->add_filter( 'edit_posts_per_page', $this, 'set_sp_products_tabs_admin_items_per_page', 10, 2 );
		$this->loader->add_filter( 'bulk_post_updated_messages', $this, 'sp_tabs_bulk_updated_messages', 10, 2 );
	}

	/**
	 * Restrict capabilities for default WooCommerce tabs.
	 *
	 * This function restricts the capabilities for editing and deleting
	 * default WooCommerce tabs (Description, Reviews, Additional Information).
	 * It prevents users from modifying these tabs directly.
	 *
	 * @param array   $allcaps The current capabilities of the user.
	 * @param string  $cap The capability being checked.
	 * @param array   $args Additional arguments passed to the capability check.
	 * @param WP_User $user The user object.
	 * @return array Modified capabilities.
	 */
	public function sp_restrict_default_tab_caps( $allcaps, $cap, $args, $user ) {
		$post_id = 0;

		// Validate expected args structure.
		if ( empty( $args[0] ) || empty( $args[2] ) ) {
			return $allcaps;
		}

		// Numeric post ID (classic).
		$post_id = is_numeric( $args[2] ) ? (int) $args[2] : '';

		// Bail if post ID is invalid.
		if ( $post_id <= 0 ) {
			return $allcaps;
		}

		$post_type = get_post_type( $post_id );

		if ( 'sp_products_tabs' !== $post_type ) {
			return $allcaps;
		}

		if ( $post_id && in_array( $args[0], array( 'delete_post', 'edit_post' ), true ) ) {
			$slug = get_post_meta( $post_id, '_wc_default_tab_slug', true );

			if ( in_array( $slug, array( 'description', 'additional_information', 'reviews' ), true ) ) {
				$allcaps[ $args[0] ] = false;
			}
		}
		return $allcaps;
	}

	/**
	 * Sets the orderby parameter for 'sp_products_tabs' to 'menu_order' in the main query.
	 *
	 * @param WP_Query $query The WP_Query instance.
	 */
	public function sp_products_tabs_orderby_menu_order( $query ) {
		if (
			is_admin() &&
			$query->is_main_query() &&
			$query->get( 'post_type' ) === 'sp_products_tabs' &&
			! $query->get( 'orderby' )
			) {
				$query->set( 'orderby', 'menu_order' );
				$query->set( 'order', 'ASC' );
		}
	}

	/**
	 * Filters the number of posts displayed per page in the admin list view
	 * for the custom post type 'sp_products_tabs'.
	 *
	 * @param int    $per_page  The number of posts to display per page.
	 * @param string $post_type The post type being queried.
	 *
	 * @return int The modified number of posts per page for 'sp_products_tabs'.
	 */
	public function set_sp_products_tabs_admin_items_per_page( $per_page, $post_type ) {
		if ( 'sp_products_tabs' === $post_type ) {
			// Apply filter to allow modification of the post limit.
			return apply_filters( 'sp_products_tabs_admin_items_per_page', 100, $post_type );
		}

		return $per_page;
	}

	/**
	 * Customize bulk action messages for the 'sp_products_tabs' custom post type.
	 *
	 * This function modifies the messages shown when performing bulk actions
	 * (e.g., update, delete, trash) on custom tabs in the admin area.
	 *
	 * @param array $bulk_messages Array of existing bulk messages.
	 * @param array $bulk_counts   Array of item counts for each action.
	 * @return array Modified array of bulk messages for the custom post type.
	 */
	public function sp_tabs_bulk_updated_messages( $bulk_messages, $bulk_counts ) {
		global $post_type;
		if ( 'sp_products_tabs' === $post_type ) {
			$bulk_messages['sp_products_tabs'] = array(
				// translators: %s: Number of tabs updated.
				'updated'   => _n( '%s tab updated.', '%s tabs updated.', $bulk_counts['updated'], 'wp-expand-tabs-free' ),
				// translators: %s: Number of tabs not updated because someone else is editing.
				'locked'    => _n( '%s tab not updated, someone is editing it.', '%s tabs not updated, someone is editing them.', $bulk_counts['locked'], 'wp-expand-tabs-free' ),
				// translators: %s: Number of tabs permanently deleted.
				'deleted'   => _n( '%s tab permanently deleted.', '%s tabs permanently deleted.', $bulk_counts['deleted'], 'wp-expand-tabs-free' ),
				// translators: %s: Number of tabs moved to the Trash.
				'trashed'   => _n( '%s tab moved to the Trash.', '%s tabs moved to the Trash.', $bulk_counts['trashed'], 'wp-expand-tabs-free' ),
				// translators: %s: Number of tabs restored from the Trash.
				'untrashed' => _n( '%s tab restored from the Trash.', '%s tabs restored from the Trash.', $bulk_counts['untrashed'], 'wp-expand-tabs-free' ),
			);
		}
		return $bulk_messages;
	}
}
