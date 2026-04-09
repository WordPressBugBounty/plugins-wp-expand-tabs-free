<?php
/**
 * Registers Save hooks for the Product Tabs.
 *
 * @link       https://shapedplugin.com/
 * @since      3.2.1
 *
 * @package    ShapedPlugin\Modules\ProductTabs
 * @subpackage Admin
 */

namespace ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use ShapedPlugin\SmartTabsFree\Modules\ProductTabs\TabHelper;

/**
 * Class SaveHooks
 *
 * @since 3.2.1
 */
class SaveHooks {

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
	 * Register hooks.
	 */
	public function register() {
		$this->loader->add_action( 'add_meta_boxes_sp_products_tabs', $this, 'add_product_tabs_custom_save_button' );
		$this->loader->add_action( 'save_post_sp_products_tabs', $this, 'auto_publish_new_tab' );
		// WooCommerce product meta save hook.
		$this->loader->add_action( 'woocommerce_process_product_meta', $this, 'sp_save_custom_product_tab_fields' );

		$this->loader->add_action( 'wp_ajax_save_tabs_custom_button_state', $this, 'save_tabs_custom_button_state' );
		$this->loader->add_action( 'save_post_sp_products_tabs', $this, 'sp_handle_tab_meta_and_order', 5, 3 );
		$this->loader->add_action( 'wp_ajax_sp_save_tabs_order', $this, 'save_product_tabs_ordered_value' );
	}

	/**
	 * Add custom save changes button after the metabox.
	 */
	public function add_product_tabs_custom_save_button() {
		add_meta_box(
			'sp_product_tabs_save_button',
			'Save Tab',
			function ( $post ) { // phpcs:ignore
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Nonce field is properly escaped by WordPress core
				echo wp_nonce_field( 'sptpro_save_product_tabs_data', 'sptpro_product_tabs_nonce', true, false );
				echo '<input type="hidden" name="post_status" value="publish">';
				echo '<button type="submit" class="sp-product-tabs-save-button" id="sp-save-tab-button">
				<span class="sp_tab-spinner-icon" style="display:none;"></span>
				<span class="sp_tab-button-label">Save Tab</span>
				</button>';
			},
			'sp_products_tabs',
			'normal',
			'low'
		);
	}

	/**
	 * Auto-publish new 'sp_products_tabs' posts on initial save.
	 *
	 * This function ensures that when a new custom post of type 'sp_products_tabs' is created,
	 * it is immediately published instead of remaining in 'auto-draft' status.
	 *
	 * Hooked into 'save_post_sp_products_tabs'.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function auto_publish_new_tab( $post_id ) {
		// Prevent auto-save from triggering this logic.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// This check is only used to detect bulk or inline edit requests, no data is saved.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_REQUEST['bulk_edit'] ) || isset( $_REQUEST['_inline_edit'] ) ) {
			return;
		}
		// Get the post object.
		$post = get_post( $post_id );

		// Ensure it's a valid post object and still in 'auto-draft' status.
		if ( $post && 'auto-draft' === $post->post_status ) {
			// Mark it as just published to track the publising status for the redirect message.
			update_post_meta( $post_id, '_tab_was_just_published', 1 );
		}
	}

	/**
	 * Save custom product tab fields.
	 *
	 * @param int $post_id The post ID of the product.
	 */
	public function sp_save_custom_product_tab_fields( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Handle hide functionality for ALL tabs.
		$this->sp_save_tab_custom_hide_fields( $post_id );

		// Handle override-enabled tabs for content based on the override setting and tab type.
		$this->sp_save_tab_custom_override_fields( $post_id );
	}

	/**
	 * Save hide fields for all product tabs independently.
	 *
	 * @param int $post_id The post ID of the product.
	 */
	private function sp_save_tab_custom_hide_fields( $post_id ) {
		// Combined all meta keys to be updated.
		$meta_keys = array();

		// Collect product custom tabs from 'sp_products_tabs' post type and use them to hide meta keys.
		$product_custom_tabs = get_posts(
			array(
				'post_type'      => 'sp_products_tabs',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'fields'         => 'ids',
			)
		);

		// Add custom product tab hide meta keys.
		foreach ( $product_custom_tabs as $tab_id ) {
			$meta_keys[] = "sp_tab_{$tab_id}_hide";
		}

		// Add hide meta keys of default WooCommerce tab.
		$default_tab_keys = array( 'description', 'additional_information', 'reviews' );
		foreach ( $default_tab_keys as $key ) {
			$meta_keys[] = "sp_wc_tab_{$key}_hide";
		}

		// Update all hide meta keys in a single loop.
		foreach ( $meta_keys as $meta_key ) {
			update_post_meta( $post_id, $meta_key, isset( $_POST[ $meta_key ] ) ? 'yes' : 'no' ); // phpcs:ignore -- Nonce verification handled by WooCommerce's 'woocommerce_process_product_meta' hook which includes its own security checks.
		}
	}

	/**
	 * Save override-related fields if the tab is enabled for override.
	 *
	 * @param int $post_id The post ID of the product.
	 */
	private function sp_save_tab_custom_override_fields( $post_id ) {
		// Fetch override-enabled tabs only for content processing.
		$override_tabs = get_posts(
			array(
				'post_type'      => 'sp_products_tabs',
				'posts_per_page' => -1,
				'meta_key'       => 'sp_override_tab_enabled', // phpcs:ignore -- WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				'meta_value'     => 'yes', // phpcs:ignore -- WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				'fields'         => 'ids',
			)
		);

		// If no override-enabled tabs, return early.
		if ( empty( $override_tabs ) ) {
			return;
		}

		foreach ( $override_tabs as $tab_id ) {
			$settings = get_post_meta( $tab_id, 'sptpro_woo_product_tabs_settings', true );
			if ( empty( $settings['tabs_content_type'] ) ) {
				continue;
			}

			$tab_type     = sanitize_key( $settings['tabs_content_type'] );
			$override_key = "sp_tab_{$tab_id}_override";

			// Save override checkbox.
			update_post_meta( $post_id, $override_key, isset( $_POST[ $override_key ] ) ? 'yes' : 'no' ); // phpcs:ignore -- Nonce verification is not necessary here.

			// Only save content if override is checked.
			if ( ! isset( $_POST[ $override_key ] ) ) { // phpcs:ignore -- Nonce verification is not necessary here.
				continue;
			}

			// Save repeater tabs meta data.
			if ( in_array( $tab_type, array( 'video', 'faqs', 'download' ), true ) ) {
				self::sp_save_repeater_tab_data( $post_id, $tab_id, $tab_type );
			}

			// Save content based on tab type.
			$content_key = "sp_tab_{$tab_id}_{$tab_type}";

			if ( isset( $_POST[ $content_key ] ) ) {  // phpcs:ignore -- Nonce verification handled by WooCommerce's 'woocommerce_process_product_meta' hook which includes its own security checks.
				$raw_value = wp_unslash( $_POST[ $content_key ] ); // phpcs:ignore -- Nonce verification is not necessary here.

				switch ( $tab_type ) {
					case 'content':
						$save_content_meta = wp_kses_post( $raw_value );
						update_post_meta( $post_id, $content_key, $save_content_meta );
						break;
					case 'image':
						$sanitized = sanitize_text_field( $raw_value );
						update_post_meta( $post_id, $content_key, $sanitized );
						break;
					default:
						$sanitized = sanitize_text_field( $raw_value );
						update_post_meta( $post_id, $content_key, $sanitized );
						break;
				}
			}
		}
	}

	/**
	 * Handle repeater tab data saving for video, faqs, and download tab types.
	 *
	 * @param int    $post_id  The ID of the product.
	 * @param string $tab_id   Unique tab identifier.
	 * @param string $tab_type Tab content type: 'video', 'faqs', 'download'.
	 */
	public static function sp_save_repeater_tab_data( $post_id, $tab_id, $tab_type ) {
		$meta_key = "sp_tab_{$tab_id}_{$tab_type}_repeater";
		$raw_data = isset( $_POST[ $meta_key ] ) ? wp_unslash( $_POST[ $meta_key ] ) : null; // phpcs:ignore -- Nonce verification is handled by WooCommerce's 'woocommerce_process_product_meta' hook which includes its own security checks.

		// If not an array, remove existing meta and bail early.
		if ( ! is_array( $raw_data ) ) {
			delete_post_meta( $post_id, $meta_key );
			return;
		}

		$save_repeater_meta_data = array();

		foreach ( $raw_data as $item ) {
			switch ( $tab_type ) {
				case 'video':
					$save_repeater_meta_data[] = array(
						'video_types' => sanitize_text_field( $item['video_types'] ?? '' ),
						'video_links' => esc_url_raw( $item['video_links'] ?? '' ),
					);
					break;

				case 'faqs':
					$save_repeater_meta_data[] = array(
						'question' => sanitize_text_field( $item['question'] ?? '' ),
						'answer'   => sanitize_textarea_field( $item['answer'] ?? '' ),
					);
					break;

				case 'download':
					$save_repeater_meta_data[] = array(
						'file_name'   => sanitize_text_field( $item['file_name'] ?? '' ),
						'upload_file' => esc_url_raw( $item['upload_file'] ?? '' ),
					);
					break;
			}
		}

		// Compare before updating to prevent unnecessary updates.
		$existing_data = get_post_meta( $post_id, $meta_key, true );

		if ( $save_repeater_meta_data !== $existing_data ) {
			update_post_meta( $post_id, $meta_key, $save_repeater_meta_data );
		}
	}

	/**
	 * Save Custom Tab repeater data.
	 *
	 * @param int $post_id The ID of the product.
	 * @return void
	 */
	public static function sp_save_custom_tabs_repeater_fields( $post_id ) {
		$meta_key = 'sp_product_per_custom_tab';
		$post_key = 'sp_product_custom_tabs';

		// phpcs:ignore -- WooCommerce handles nonce & capability checks.
		$raw_data = isset( $_POST[ $post_key ] ) ? wp_unslash( $_POST[ $post_key ] ) : null;

		// No data submitted — clear existing.
		if ( ! is_array( $raw_data ) ) {
			delete_post_meta( $post_id, $meta_key );
			return;
		}

		$save_data = array();

		foreach ( $raw_data as $item ) {
			$tab_name    = isset( $item['tab_name'] ) ? sanitize_text_field( $item['tab_name'] ) : '';
			$tab_content = isset( $item['tab_content'] ) ? wp_kses_post( $item['tab_content'] ) : '';

			// Skip empty tabs completely.
			if ( '' === $tab_name && '' === $tab_content ) {
				continue;
			}

			$save_data[] = array(
				'tab_name'    => $tab_name,
				'tab_content' => $tab_content,
			);
		}

		// Avoid unnecessary DB writes.
		$existing = get_post_meta( $post_id, $meta_key, true );

		if ( $existing !== $save_data ) {
			update_post_meta( $post_id, $meta_key, $save_data );
		}
	}

	/**
	 * Save the state of a custom button for showing or hiding tabs.
	 *
	 * This function processes and saves the visibility state of a custom tabs show/hide button.
	 * It ensures that the request is secure using a nonce verification and then updates
	 * the state of the button based on the received data.
	 *
	 * @return void
	 */
	public function save_tabs_custom_button_state() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Verify nonce for security.
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'sp-product-tabs-nonce-verification' ) ) {
			return;
		}

		// Get the unique switcher ID.
		$switcher_id = isset( $_POST['switcher_id'] ) ? sanitize_text_field( wp_unslash( $_POST['switcher_id'] ) ) : '';

		// Determine the state.
		$state = isset( $_POST['state'] ) && 'enabled' === $_POST['state'] ? true : false;

		if ( $switcher_id ) {
			// Save the state using a meta key specific to the switcher ID.
			update_post_meta( $switcher_id, 'show_product_tabs', $state );
		}

		// Respond to the AJAX request.
		wp_send_json_success( $switcher_id );
	}

	/**
	 * Handles saving of custom tab metadata and sets the correct menu order for new tabs.
	 *
	 * @param int    $post_id  The ID of the post being saved.
	 * @param object $post The post object being saved.
	 * @return $update True if this is an update, false if it's a new post.
	 */
	public function sp_handle_tab_meta_and_order( $post_id, $post, $update ) {
		// Prevent autosave or revisions from triggering this.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		// Check if the 'override_tab' setting is present in the submitted form data.
		if ( isset( $_POST['sptpro_woo_product_tabs_settings']['override_tab'] ) ) {
			// Verify nonce when override checkbox exists.
			if (
				! isset( $_POST['sptpro_product_tabs_nonce'] ) ||
				! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sptpro_product_tabs_nonce'] ) ), 'sptpro_save_product_tabs_data' )
			) {
				return;
			}

			// If the override checkbox is checked (value is '1'), enable the override by saving a post meta flag.
			if ( '1' === $_POST['sptpro_woo_product_tabs_settings']['override_tab'] ) {
				update_post_meta( $post_id, 'sp_override_tab_enabled', 'yes' );
			} else {
				// If not checked, remove the override flag to disable it.
				delete_post_meta( $post_id, 'sp_override_tab_enabled' );
			}
		}

		// Assign last menu_order if it's a NEW post.
		if ( ! $update ) {
			// Add a meta key to indicate that product tabs are enabled for this post by default.
			add_post_meta( $post_id, 'show_product_tabs', true, true );

			$last = new \WP_Query(
				array(
					'post_type'      => 'sp_products_tabs',
					'posts_per_page' => 1,
					'orderby'        => 'menu_order',
					'order'          => 'DESC',
					'post_status'    => 'any',
					'fields'         => 'ids',
				)
			);

			$highest_order = 0;
			if ( $last->have_posts() ) {
				$highest_order = (int) get_post_field( 'menu_order', $last->posts[0] );
			}

			wp_update_post(
				array(
					'ID'         => $post_id,
					'menu_order' => $highest_order + 1,
				)
			);
		}
	}

	/**
	 * AJAX handler to save the new order of 'sp_products_tabs' posts.
	 *
	 * @return void Sends a JSON success or error response.
	 */
	public function save_product_tabs_ordered_value() {
		$_capability = apply_filters( 'wptabspro_product_tabs_order_capability', 'manage_options' );
		if ( ! current_user_can( $_capability ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'You do not have permission to order tabs.', 'wp-expand-tabs-free' ) ) );
		}

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'sp-product-tabs-order-nonce-verification' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		$order = isset( $_POST['order'] ) && is_array( $_POST['order'] ) ? array_map( 'intval', $_POST['order'] ) : array();
		if ( ! is_array( $order ) ) {
			wp_send_json_error( 'Invalid order' );
		}

		foreach ( $order as $position => $post_id ) {
			wp_update_post(
				array(
					'ID'         => (int) $post_id,
					'menu_order' => $position,
				)
			);
		}

		wp_send_json_success( 'Order saved' );
	}
}
