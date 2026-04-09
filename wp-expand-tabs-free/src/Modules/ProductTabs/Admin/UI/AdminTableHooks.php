<?php
/**
 * Registers 'Admin Table' hooks for the Product Tabs.
 *
 * Loads and defines the custom post type for this plugin
 * so that it is ready for admin menu under a different post type.
 *
 * @link       https://shapedplugin.com/
 * @since      3.2.1
 *
 * @package    ShapedPlugin\Modules\WPTabs
 * @subpackage Admin
 */

namespace ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin\UI;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use ShapedPlugin\SmartTabsFree\Modules\ProductTabs\TabHelper;

/**
 * Class to register Admin Table related hooks of Product Tabs.
 */
class AdminTableHooks {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that
	 *
	 * @var $loader
	 */
	protected $loader;

	/**
	 * Initialize the Admin Table Hooks for Product Tabs module.
	 *
	 * @param object $loader The admin loader object.
	 */
	public function __construct( $loader ) {
		$this->loader = $loader;
	}

	/**
	 * Register all of the hooks related to the ShortCode-based Tabs.
	 *
	 * @return void
	 */
	public function register() {
		// Tab title related hooks.
		$this->loader->add_filter( 'wp_insert_post_data', $this, 'sp_override_tab_title_before_insert', 10, 2 );
		$this->loader->add_filter( 'redirect_post_location', $this, 'set_correct_publish_message', 10, 2 );
		$this->loader->add_filter( 'the_title', $this, 'modify_admin_table_title_name', 10, 2 );
		$this->loader->add_filter( 'post_row_actions', $this, 'sp_hide_row_actions_for_default_tabs', 10, 2 );
		$this->loader->add_filter( 'post_class', $this, 'sp_add_custom_class_to_default_tabs', 10, 3 );
		$this->loader->add_filter( 'bulk_actions-edit-sp_products_tabs', $this, 'remove_edit_bulk_action' );

		// Admin columns related hooks.
		$this->loader->add_filter( 'manage_sp_products_tabs_posts_columns', $this, 'add_product_tabs_columns' );
		$this->loader->add_filter( 'manage_sp_products_tabs_posts_columns', $this, 'sp_add_order_row_for_admin_table' );

		$this->loader->add_action( 'manage_sp_products_tabs_posts_custom_column', $this, 'add_product_tabs_columns_data', 10, 2 );
		$this->loader->add_action( 'manage_sp_products_tabs_posts_custom_column', $this, 'sp_add_sortable_icons_in_product_tabs_column', 10, 2 );
	}

	/**
	 * Override the post title for 'sp_products_tabs' post type from a custom meta field before it's saved.
	 *
	 * This sets the post_title from a custom meta field ('tab_title') submitted via the form,
	 * ensuring the admin list and search use the user-friendly tab title(from custom meta field) instead of the default post title.
	 *
	 * @param array $data    An array of slashed, sanitized, and processed post data.
	 * @param array $postarr An array of sanitized (and slashed) but otherwise unmodified post data.
	 * @return array Modified post data (Title).
	 */
	public function sp_override_tab_title_before_insert( $data, $postarr ) {
		// Only target the custom post type.
		if ( empty( $data['post_type'] ) || 'sp_products_tabs' !== $data['post_type'] ) {
			return $data;
		}

		// Verify nonce to ensure secure processing.
		if (
			! isset( $_POST['sptpro_product_tabs_nonce'] ) ||
			! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sptpro_product_tabs_nonce'] ) ), 'sptpro_save_product_tabs_data' )
		) {
			return $data;
		}

		// Get the custom tab title from the POST data.
		$tab_title = $_POST['sptpro_woo_product_tabs_settings']['product_tab_title_section']['tab_title'] ?? ''; // phpcs:ignore -- This is sanitized later.
		// Sanitize and validate.
		$custom_title = sanitize_text_field( wp_unslash( $tab_title ) );

		if ( ! empty( trim( $custom_title ) ) ) {
			$data['post_title'] = $custom_title;
		}

		return $data;
	}

	/**
	 * Adjusts the redirect location after saving a 'sp_products_tabs' post to show the correct admin message.
	 *
	 * Hooked into 'redirect_post_location' to customize the post-save behavior.
	 *
	 * @param string $location The original redirect location URL.
	 * @param int    $post_id  The ID of the post being saved.
	 *
	 * @return string Modified redirect location with adjusted message ID (if applicable).
	 */
	public function set_correct_publish_message( $location, $post_id ) {
		// Check if the post type is 'sp_products_tabs' otherwise return the original location.
		if ( get_post_type( $post_id ) !== 'sp_products_tabs' ) {
			return $location;
		}
		// Check if the post was just published and set the message accordingly.
		if ( get_post_meta( $post_id, '_tab_was_just_published', true ) ) {
			delete_post_meta( $post_id, '_tab_was_just_published' ); // clean up the meta flag.
			$location = add_query_arg( 'message', 6, $location ); // set published message.
		}

		return $location;
	}

	/**
	 * Modify the title column in the admin list table for 'sp_products_tabs' CPT.
	 *
	 * @param string $title The post title.
	 * @param int    $post_id The post ID.
	 * @return string Modified title.
	 */
	public function modify_admin_table_title_name( $title, $post_id = null ) {
		// Return early if no post ID is provided.
		if ( ! $post_id ) {
			return $title;
		}

		if ( ! function_exists( 'get_current_screen' ) ) {
			require_once ABSPATH . '/wp-admin/includes/screen.php';
		}

		$screen = get_current_screen();
		if ( is_admin() && $screen && 'sp_products_tabs' === $screen->post_type && 'edit' === $screen->base ) {

			$slug = get_post_meta( $post_id, '_wc_default_tab_slug', true );
			if ( in_array( $slug, array( 'description', 'additional_information', 'reviews' ), true ) ) {
				remove_filter( 'the_title', 'esc_html' ); // prevent double escaping.
				return esc_html( $title );
			}

			$product_tabs_meta = TabHelper::get_product_tab_meta_options( $post_id );

			$tabs_title          = $product_tabs_meta['product_tab_title_section'] ?? '';
			$tabs_content_titles = ! empty( $tabs_title['tab_title'] ) ? $tabs_title['tab_title'] : '(No Title)';
			if ( $tabs_content_titles ) {
				return esc_html( $tabs_content_titles );
			}
		}

		return $title;
	}

	/**
	 * Hide row actions ( "Quick Edit", "Trash" ) for default WooCommerce tabs.
	 *
	 * @param array   $actions The row actions for the post.
	 * @param WP_Post $post The post object.
	 */
	public function sp_hide_row_actions_for_default_tabs( $actions, $post ) {
		if ( 'sp_products_tabs' === $post->post_type ) {
			$slug = get_post_meta( $post->ID, '_wc_default_tab_slug', true );
			if ( in_array( $slug, array( 'description', 'additional_information', 'reviews' ), true ) ) {
				unset( $actions['edit'], $actions['inline hide-if-no-js'], $actions['trash'] );
			}

			// Remove 'Quick Edit' actions for each WC tabs under the 'sp_products_tabs' post type.
			unset( $actions['inline hide-if-no-js'] );
		}
		return $actions;
	}

	/**
	 * Adds custom classes to default WooCommerce tabs in the admin.
	 *
	 * @param array  $classes The existing classes for the post.
	 * @param string $class   The class being added.
	 * @param int    $post_id int The ID of the post.
	 */
	public function sp_add_custom_class_to_default_tabs( $classes, $class, $post_id ) {
		if ( is_admin() && get_post_type( $post_id ) === 'sp_products_tabs' ) {
			$slug = get_post_meta( $post_id, '_wc_default_tab_slug', true );
			if ( in_array( $slug, array( 'description', 'additional_information', 'reviews' ), true ) ) {
				$classes[] = 'sp-woo-default-tab'; // Generic class for styling all default tabs.
			}
		}
		return $classes;
	}

	/**
	 * Remove "Edit" bulk action from sp_products_tabs list table.
	 *
	 * @param array $actions List of bulk actions.
	 * @return array Filtered list of bulk actions.
	 */
	public function remove_edit_bulk_action( $actions ) {
		unset( $actions['edit'] );
		return $actions;
	}

	/**
	 * Product Tabs Column
	 *
	 * @return array
	 */
	public function add_product_tabs_columns() {
		$new_columns['cb']        = '<input type="checkbox" />';
		$new_columns['title']     = __( 'Tabs Name', 'wp-expand-tabs-free' );
		$new_columns['tab_type']  = __( 'Tab Content Type', 'wp-expand-tabs-free' );
		$new_columns['show_in']   = __( 'Show Tab In', 'wp-expand-tabs-free' );
		$new_columns['show_tabs'] = __( 'Enable/Disable', 'wp-expand-tabs-free' );

		return $new_columns;
	}

	/**
	 * Adds new table row in the 'sp_products_tabs' custom post type
	 * to add a sortable handle/icon column for drag-and-drop ordering.
	 *
	 * @param array $columns Existing columns.
	 */
	public function sp_add_order_row_for_admin_table( $columns ) {
		$columns = array( 'order' => 'Order' ) + $columns;
		return $columns;
	}

	/**
	 * Add product tabs column data.
	 *
	 * @param mixed  $column plugin backend column.
	 * @param string $post_id post id.
	 * @return void
	 */
	public function add_product_tabs_columns_data( $column, $post_id ) {
		$product_tabs_meta = get_post_meta( $post_id, 'sptpro_woo_product_tabs_settings', true );
		$tabs_content_type = isset( $product_tabs_meta['tabs_content_type'] ) ? $product_tabs_meta['tabs_content_type'] : 'WooCommerce Default';
		$tabs_show_in      = isset( $product_tabs_meta['tabs_show_in'] ) ? $product_tabs_meta['tabs_show_in'] : '-';
		$show_in_label     = ucwords( str_replace( '_', ' ', $tabs_show_in ) );
		// Brands.
		$tabs_show_by_brands = isset( $product_tabs_meta['tabs_show_by_brands'] ) ? $product_tabs_meta['tabs_show_by_brands'] : '';

		switch ( $column ) {
			case 'tab_type':
				echo '<p class="product-tabs-table-data">' . esc_html( ucfirst( $tabs_content_type ) ) . '</p>';
				break;
			case 'show_in':
				// Callback to format list with "+x more" link.
				$output = $this->format_show_in_column( $tabs_show_in, (array) $tabs_show_by_brands );

				echo '<p class="product-tabs-table-data">' . wp_kses_post( $output ) . '</p>';
				break;
			case 'show_tabs':
				echo '<div class="wptabspro-field wptabspro-field-switcher sp-tabs-custom-switcher" data-product_tabs_id="' . esc_attr( $post_id ) . '">
					<div class="wptabspro--switcher wptabspro--active" style="width: 50px;">
						<span class="wptabspro--ball"></span>
						<input type="hidden" name="show_product_tabs_' . esc_attr( $post_id ) . '" value="0">
					</div>
				<div class="clear"></div>
				</div>';
				break;
			default:
				break;
		} // end switch
	}

	/**
	 * Outputs a sortable icon/handle in the custom column
	 *
	 * @param string $column  The current column name.
	 */
	public function sp_add_sortable_icons_in_product_tabs_column( $column ) {
		if ( 'order' === $column ) {
			echo '<span class="drag-handle">
				<i class="icon-drag-1"></i>
			</span>';
		}
	}

	/**
	 * Get the label text for the "Show In" column based on the selected option.
	 *
	 * @param string $tabs_show_in The value of the 'tabs_show_in' field.
	 * @return string The label text corresponding to the selected option.
	 */
	private function show_in_label_text( $tabs_show_in ) {
		$_label_text = '-';
		switch ( $tabs_show_in ) {
			case 'all_product':
				$_label_text = __( 'All Products', 'wp-expand-tabs-free' );
				break;
			case 'categories':
				$_label_text = __( 'Categories', 'wp-expand-tabs-free' );
				break;
			case 'product_tags':
				$_label_text = __( 'Tags', 'wp-expand-tabs-free' );
				break;
			case 'brand':
				$_label_text = __( 'Brands', 'wp-expand-tabs-free' );
				break;
			case 'product_sku':
				$_label_text = __( 'SKU', 'wp-expand-tabs-free' );
				break;
			case 'specific_product':
				$_label_text = __( 'Specific Products', 'wp-expand-tabs-free' );
				break;
		}
		return $_label_text;
	}



	/**
	 * Format the "Show In" column output for product tabs.
	 *
	 * @param string $tabs_show_in          The show_in type (categories, tags, brand, sku, product).
	 * @param array  $tabs_show_by_brands Brand Terms.
	 *
	 * @return string Formatted HTML for the column.
	 */
	private function format_show_in_column( string $tabs_show_in, array $tabs_show_by_brands ) {
		$output = '';

		switch ( $tabs_show_in ) {
			case 'all_product':
				$output = '<span class="sp-tabs-label">' . esc_html__( 'All Products', 'wp-expand-tabs-free' ) . '</span>';
				break;

			case 'brand':
				$brand_names = array();
				foreach ( (array) $tabs_show_by_brands as $brand_id ) {
					$term = get_term( $brand_id, 'product_brand' ); // Adjust taxonomy if needed.
					if ( $term && ! is_wp_error( $term ) ) {
						$brand_names[] = $term->name;
					}
				}

				$output = '<span class="sp-tabs-label">' . esc_html__( 'Brand', 'wp-expand-tabs-free' ) . '</span>: ' .
				( ! empty( $brand_names ) ? $this->format_list_with_more_btn( $brand_names, 'Brand' ) : '' );

				break;
			default:
				$output = '-';
				break;
		}
		return $output;
	}

	/**
	 * Format a list of items with "+x more" exandable button.
	 *
	 * @param array  $items  The list of items (e.g. category names, tags).
	 * @param string $label  The label (used in popup, e.g. "Category").
	 * @param int    $limit  Number of items to show before truncation.
	 *
	 * @return string HTML output.
	 */
	private function format_list_with_more_btn( array $items, string $label = '', int $limit = 2 ) {
		$items = array_filter( $items );

		if ( count( $items ) > $limit ) {
			$visible   = array_slice( $items, 0, $limit );
			$remaining = array_slice( $items, $limit );
			$truncated = implode( ', ', $visible );

			// Output truncated list + clickable "more" link with full data.
			return sprintf(
				'%s, <a href="#" class="sp-tabs-more-link" data-label="%s" data-items="%s">+%d more</a>',
				esc_html( $truncated ),
				esc_attr( $label ),
				esc_attr( implode( ', ', $items ) ), // full list passed to popup.
				count( $remaining )
			);
		}

		return implode( ', ', $items );
	}
}
