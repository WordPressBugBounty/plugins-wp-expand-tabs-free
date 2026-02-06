<?php
/**
 * The file that defines the Smart Tabs Woo Feature
 *
 * @link http://shapedplugin.com
 * @since 2.0.2
 *
 * @package    WP_Tabs
 * @subpackage WP_Tabs/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Custom post class to register the Product tab.
 */
class WP_Tabs_Product_Tab {

	/**
	 * The single instance of the class.
	 *
	 * @var self
	 * @since 2.0.2
	 */
	private static $instance;

	/**
	 * Holds tab post IDs mapped by tab keys.
	 *
	 * @var array
	 */
	private $tab_post_ids = array();

	/**
	 * Path to the file.
	 *
	 * @since 2.0.2
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 2.0.2
	 *
	 * @var object
	 */
	public $base;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.2.13
	 * @access   private
	 * @var      string    $minify scripts.
	 */
	private $min;

	/**
	 * Stores the advanced tab settings loaded from the WordPress options table.
	 *
	 * @var array
	 */
	protected static $advanced_tab_settings = array();

	/**
	 * Flag indicating whether to skip loading the default product tab styles.
	 *
	 * @var bool
	 */
	protected static $is_skip_product_style = false;

	/**
	 * Flag indicating whether the Divi Builder is being used.
	 *
	 * @since 3.2.0
	 * @var bool
	 */
	protected static $use_divi_builder = null;

	/**
	 * WooCommerce product tab construct function.
	 */
	public function __construct() {
		$enable_woo_product_tabs = SP_WP_Tabs_Free::get_general_setting( 'enable_product_tabs', true );
		// Check if WooCommerce product tabs is enabled or not.
		if ( ! $enable_woo_product_tabs ) {
			return; // Product Tab disabled — don't hook anything.
		}

		// Load the advanced tab settings from the database and store them statically for reuse.
		self::$advanced_tab_settings = get_option( 'sp_products_tabs_advanced' );

		add_action( 'wp_ajax_sp_save_tabs_order', array( $this, 'save_product_tabs_ordered_value' ) );
		add_action( 'save_post_sp_products_tabs', array( $this, 'sp_handle_tab_meta_and_order' ), 5, 3 );
		add_action( 'pre_get_posts', array( $this, 'sp_products_tabs_orderby_menu_order' ) ); // Ensure tabs are ordered by menu order in queries.
		add_action( 'woocommerce_process_product_meta', array( $this, 'sp_save_custom_product_tab_fields' ) );
		// Admin and public assets for product tabs.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_product_admin_tabs_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_product_tabs_assets' ) );

		// Use minified files in production unless in development mode.
		$this->min = ( apply_filters( 'enqueue_dev_mode', false ) || WP_DEBUG ) ? '' : '.min';

		add_filter( 'bulk_post_updated_messages', array( $this, 'sp_tabs_bulk_updated_messages' ), 10, 2 );
		add_filter( 'edit_posts_per_page', array( $this, 'set_sp_products_tabs_admin_items_per_page' ), 10, 2 );

		self::$is_skip_product_style = self::get_advanced_setting( 'skip_product_tab_style', false );

		if ( ! self::$is_skip_product_style ) {
			add_filter( 'body_class', array( $this, 'sptpro_add_tabs_body_class' ) );
		}
	}

	/**
	 * Get Advanced Tab Settings.
	 *
	 * @param string $key The setting key to retrieve.
	 * @param mixed  $settings_default The default value to return if the key does not exist.
	 * @return mixed The value of the setting if it exists, otherwise the default value.
	 */
	protected static function get_advanced_setting( $key, $settings_default = null ) {
		return self::$advanced_tab_settings[ $key ] ?? $settings_default;
	}

	/**
	 * Get product tab settings for a specific product.
	 *
	 * @param int $post_id Product ID.
	 * @return array The tab settings.
	 */
	protected static function get_product_tab_meta_options( $post_id ) {
		$tab_meta_options = get_post_meta( $post_id, 'sptpro_woo_product_tabs_settings', true );

		return ! empty( $tab_meta_options ) ? $tab_meta_options : array();
	}

	/**
	 * Check whether a product tab is globally enabled.
	 *
	 * @param int $tab_id The post ID of the product tab.
	 * @return bool True if the tab is enabled, false otherwise.
	 */
	public static function is_product_tab_globally_enabled( $tab_id ) {
		$is_enabled = get_post_meta( $tab_id, 'show_product_tabs', true );

		// Consider it enabled only if meta exists and is not false or empty.
		return ! empty( $is_enabled ) && false !== $is_enabled;
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
				'meta_key'       => 'sp_override_tab_enabled', // phpcs:ignore -- WordPress.VIP.SlowDBQuery.meta_value Only fetch tabs with override enabled.
				'meta_value'     => 'yes', // phpcs:ignore -- WordPress.VIP.SlowDBQuery.meta_value
				'fields'         => 'ids',
			)
		);

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

			// Save content based on tab type.
			$content_key = "sp_tab_{$tab_id}_{$tab_type}";

			if ( isset( $_POST[ $content_key ] ) ) {  // phpcs:ignore -- Nonce verification handled by WooCommerce's 'woocommerce_process_product_meta' hook which includes its own security checks.
				$raw_value = wp_unslash( $_POST[ $content_key ] ); // phpcs:ignore -- Nonce verification is not necessary here.

				switch ( $tab_type ) {
					case 'content':
						$save_content_meta = wp_kses_post( $raw_value );
						update_post_meta( $post_id, $content_key, $save_content_meta );
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
	 * AJAX handler to save the new order of 'sp_products_tabs' posts.
	 *
	 * @return void Sends a JSON success or error response.
	 */
	public function save_product_tabs_ordered_value() {

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

	/**
	 * Handles saving of custom tab metadata and sets the correct menu order for new tabs.
	 *
	 * @param int    $post_id  The ID of the post being saved.
	 * @param object $post The post object being saved.
	 * @param bool   $update  Whether this is an update to an existing post.
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

			$last = new WP_Query(
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
	 *
	 * WooCommerce faq tab.
	 *
	 * @return array $tabs
	 * @param array $tabs woo tab.
	 */
	public function sptpro_woo_tab( $tabs ) {
		$product_id = $this->get_woo_product_id();

		if ( $product_id > 0 ) {
			$product = wc_get_product( $product_id );
		}
		if ( ! $product instanceof WC_Product ) {
			return $tabs;
		}

		$tabs = $this->filter_hidden_default_tabs( $tabs, $product_id );

		// Assign menu_order priority for default tabs linked to sp_products_tabs.
		foreach ( $tabs as $slug => &$tab ) {
			if ( isset( $tab['custom_tab_id'] ) ) {
				$tab['priority'] = (int) $tab['menu_order'];
			}
		}
		unset( $tab ); // avoid reference issues.

		$product_brands = wp_get_post_terms( $product_id, 'product_brand', array( 'fields' => 'ids' ) );

		$args = array(
			'post_type'              => 'sp_products_tabs',
			'post_status'            => 'publish',
			'ignore_sticky_posts'    => true,
			'posts_per_page'         => -1,
			'orderby'                => 'menu_order',
			'order'                  => 'ASC',
			'no_found_rows'          => true, // No need for pagination (Performance optimization).
			'update_post_term_cache' => false, // Avoid loading terms for better performance.
		);

		$query      = new WP_Query( $args );
		$smart_tabs = array();

		if ( $query->have_posts() ) {
			foreach ( $query->posts as $product_tab ) {
				$settings = self::get_product_tab_meta_options( $product_tab->ID );
				// Skip if product tab settings are empty or not an array.
				if ( empty( $settings ) || ! is_array( $settings ) ) {
					continue;
				}

				// Check if the tab is enabled globally (AJAX-saved meta).
				if ( ! self::is_product_tab_globally_enabled( $product_tab->ID ) ) {
					continue;
				}

				// Check Tabs visibility.
				if ( ! $this->is_tab_visible_for_product( $product_id, $settings, $product_brands ) ) {
					continue;
				}

				// Tabs Name HTML.
				$tab_name_html = $this->get_tab_name_html( $settings );

				$tab_key                        = 'sptpro_tab_' . $product_tab->ID;
				$this->tab_post_ids[ $tab_key ] = $product_tab->ID;
				$hide_tab_for_specific_product  = get_post_meta( $product_id, "sp_tab_{$product_tab->ID}_hide", true );

				if ( 'yes' === $hide_tab_for_specific_product ) {
					continue; // If the tab is hidden for this product, do not render it.
				}

				$smart_tabs[ $tab_key ] = array(
					'title'    => $tab_name_html,
					'priority' => (int) $product_tab->menu_order,
					'callback' => array( $this, 'sptpro_product_tabs_panel_content' ),
					'content'  => $product_tab->post_content,
				);
			}
			wp_reset_postdata();
		}

		// Merge default WooCommerce tabs with our custom smart tabs.
		$_smart_tabs = array_merge( $tabs, $smart_tabs );

		/**
		 * Sort all tabs by priority for display order
		 * Lower priority numbers appear first (e.g., priority 10 before priority 20)
		 * Tabs without priority default to 99 (appear last)
		 */
		uasort(
			$_smart_tabs,
			static function ( $tab_a, $tab_b ) {
				$priority_a = isset( $tab_a['priority'] ) ? absint( $tab_a['priority'] ) : 99;
				$priority_b = isset( $tab_b['priority'] ) ? absint( $tab_b['priority'] ) : 99;
				return $priority_a <=> $priority_b;
			}
		);

		return $_smart_tabs;
	}

	/**
	 * Filters out default WooCommerce tabs that are hidden either per-product or globally.
	 *
	 * @param array $tabs      The current list of WooCommerce tabs.
	 * @param int   $product_id The current product ID.
	 *
	 * @return array Filtered list of tabs with hidden defaults removed.
	 */
	private function filter_hidden_default_tabs( array $tabs, int $product_id ) {
		$default_tab_slugs = array( 'description', 'additional_information', 'reviews' );

		foreach ( $default_tab_slugs as $tab_slug ) {
			$hide_meta_key         = "sp_wc_tab_{$tab_slug}_hide";
			$is_hidden_for_product = get_post_meta( $product_id, $hide_meta_key, true );

			// Hide tab if explicitly hidden for this product.
			if ( 'yes' === $is_hidden_for_product ) {
				unset( $tabs[ $tab_slug ] );
				continue;
			}

			// Hide tab if globally disabled via custom tab mapping.
			if ( ! self::is_default_tab_globally_enabled( $tab_slug ) ) {
				unset( $tabs[ $tab_slug ] );
				continue;
			}

			// If globally enabled, attach meta info (custom tab ID and menu order).
			$custom_tab_data = self::get_default_tab_mapping_data( $tab_slug );

			if ( $custom_tab_data ) {
				$tabs[ $tab_slug ]['custom_tab_id'] = $custom_tab_data['ID'];
				$tabs[ $tab_slug ]['menu_order']    = $custom_tab_data['menu_order'];
			}
		}

		return $this->validate_tabs( $tabs );
	}

	/**
	 * Retrieves the mapped custom tab post for a given default WooCommerce tab slug.
	 *
	 * @param string $tab_slug The WooCommerce default tab slug (e.g., 'description', 'reviews').
	 * @return array|null Returns an array with 'ID' and 'menu_order' if found, otherwise null.
	 */
	public static function get_default_tab_mapping_data( $tab_slug ) {
		$custom_tab_query = get_posts(
			array(
				'post_type'      => 'sp_products_tabs',
				'meta_key'       => '_wc_default_tab_slug',  // phpcs:ignore -- WordPress.DB.SlowDBQuery.slow_meta_query -- Acceptable here because it runs only during plugin activation.
				'meta_value'     => $tab_slug,  // phpcs:ignore -- WordPress.DB.SlowDBQuery.slow_meta_query -- Acceptable here because it runs only during plugin activation.
				'posts_per_page' => 1,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
				'post_status'    => 'publish',
			)
		);

		if ( empty( $custom_tab_query ) ) {
			return null;
		}

		return array(
			'ID'         => $custom_tab_query[0]->ID,
			'menu_order' => $custom_tab_query[0]->menu_order,
		);
	}

	/**
	 * Check if a default WooCommerce tab is globally enabled.
	 *
	 * @param string $tab_slug The slug of the default tab (e.g., 'description').
	 * @return bool True if enabled, false if globally disabled.
	 */
	public static function is_default_tab_globally_enabled( $tab_slug ) {
		$custom_tab_query = get_posts(
			array(
				'post_type'      => 'sp_products_tabs',
				'meta_key'       => '_wc_default_tab_slug', // phpcs:ignore -- WordPress.DB.SlowDBQuery.slow_meta_query -- Acceptable here because it runs only during plugin activation.
				'meta_value'     => $tab_slug, // phpcs:ignore -- WordPress.DB.SlowDBQuery.slow_meta_query -- Acceptable here because it runs only during plugin activation.
				'posts_per_page' => 1,
				'post_status'    => 'publish',
			)
		);

		if ( ! empty( $custom_tab_query ) ) {
			$custom_tab_id = $custom_tab_query[0]->ID;

			// check global enabled/disabe status.
			return self::is_product_tab_globally_enabled( $custom_tab_id );
		}

		return true; // Default to enabled.
	}

	/**
	 * Validates the tabs array to ensure each tab has a title and callback.
	 *
	 * @param array $tabs The array of tabs to validate.
	 * @return array The validated tabs array, with any invalid tabs removed.
	 * */
	private function validate_tabs( array $tabs ) {
		foreach ( $tabs as $key => $tab ) {
			if ( ! isset( $tab['title'], $tab['callback'] ) ) {
				unset( $tabs[ $key ] );
			}
		}
		return $tabs;
	}

	/**
	 * Determine whether a tab should be visible for a specific product.
	 *
	 * @param int   $product_id         The WooCommerce product ID.
	 * @param array $settings           Tab settings from post meta.
	 * @param array $product_brands     Array of product brand term IDs.
	 *
	 * @return bool True if the tab should be shown, false otherwise.
	 */
	public static function is_tab_visible_for_product( $product_id, $settings, $product_brands ) {
		$show_in = $settings['tabs_show_in'] ?? 'all_product';

		$check_exclude = $settings['tabs_exclude'] ?? false;
		$excluded      = $settings['exclude_specific'] ?? array();
		$show_brands   = $settings['tabs_show_by_brands'] ?? array();

		if ( 'specific_product' !== $show_in && $check_exclude && ! empty( $excluded ) && in_array( $product_id, $excluded, false ) ) {
			return false;
		}

		switch ( $show_in ) {
			case 'brand':
				return ! empty( array_intersect( array_map( 'absint', (array) $show_brands ), $product_brands ) );

			case 'all_product':
			default:
				return true;
		}
	}

	/**
	 * Returns the current WooCommerce product ID.
	 *
	 * If the product object is not available, it tries to get the product ID from the current post object.
	 * If the post object is not a product, it will return 0.
	 * If the product object is not available and in the Divi builder, it will return the first product ID for preview.
	 *
	 * @return int The WooCommerce product ID.
	 */
	public function get_woo_product_id() {
		global $product, $post;
		$product_id = 0;

		if ( $product instanceof WC_Product ) {
			$product_id = $product->get_id();
		} elseif ( $post && 'product' === $post->post_type ) {
			$product_id = $post->ID;
		}

		// If no product but in Divi builder, load first product for preview.
		if ( ( isset( $_GET['et_fb'] ) || isset( $_GET['et_pb_preview'] ) ) && ! $product_id ) {

			$preview_product = get_posts(
				array(
					'post_type'      => 'product',
					'posts_per_page' => 1,
					'post_status'    => 'publish',
				)
			);

			if ( ! empty( $preview_product ) ) {
				$product_id = $preview_product[0]->ID; // Get only the first product ID.
				$product    = wc_get_product( $product_id );
			}
		}

		return intval( $product_id );
	}

	/**
	 * WooCommerce Tab Content.
	 *
	 * @param array $key tab key.
	 * @return void
	 */
	public function sptpro_product_tabs_panel_content( $key ) {
		if ( empty( $this->tab_post_ids[ $key ] ) ) {
			echo esc_html__( 'Tab content unavailable.', 'wp-expand-tabs-free' );
			return;
		}

		$tab_id     = $this->tab_post_ids[ $key ];
		$_tabs_data = get_post_meta( $tab_id, 'sptpro_woo_product_tabs_settings', true );
		$tab_type   = isset( $_tabs_data['tabs_content_type'] ) ? $_tabs_data['tabs_content_type'] : 'content';

		// Get the current product ID.
		$product_id = $this->get_woo_product_id();

		// Check if this tab is overridden for this product.
		$override_tab = get_post_meta( $product_id, "sp_tab_{$tab_id}_override", true );

		echo '<div class="sp-tab__tab-content">';
		if ( 'yes' === $override_tab ) {
			// Render overridable tabs content.
			$this->render_overridable_tab_content( $product_id, $tab_id, $tab_type, $_tabs_data );
		} else {
			$tabs_content = $_tabs_data['tabs_content_description'] ?? '';
			// Render tab content based on type.
			$this->render_tab_content_by_type( $tab_type, $_tabs_data, $tabs_content );
		}
		echo '</div>';
	}

	/**
	 * Renders overridden tab content for a specific WooCommerce product.
	 *
	 * This method checks if a specific tab has custom (overridden) data for the current product.
	 * If so, it renders the appropriate content depending on the tab type—content, image, video, FAQ, or downloadable files.
	 *
	 * @param int    $product_id The ID of the WooCommerce product.
	 * @param int    $tab_id     The ID of the tab being rendered.
	 * @param string $tab_type   The type of the tab (e.g., 'content', 'image', 'video', 'faqs', 'download').
	 * @param array  $_tabs_data Additional tab configuration data passed from meta or tab settings.
	 *
	 * @return void Outputs the tab content directly.
	 */
	protected function render_overridable_tab_content( $product_id, $tab_id, $tab_type, $_tabs_data ) {
		// Overridable tab content rendering based on tab type.
		switch ( $tab_type ) {
			case 'content':
				$override_content = get_post_meta( $product_id, "sp_tab_{$tab_id}_content", true );
				$this->sp_render_tab_content( $override_content );
				break;

			default:
				echo esc_html__( 'Unsupported override tab type.', 'wp-expand-tabs-free' );
		}
	}

	/**
	 * Renders the tab content based on the tab type.
	 *
	 * @param string $tab_type The type of the tab (content, image, video, faqs, download, map, contact-form, products).
	 * @param array  $_tabs_data Array of tabs data.
	 * @param string $tabs_content The common content of the tabs.
	 */
	protected function render_tab_content_by_type( $tab_type, $_tabs_data, $tabs_content = '' ) {
		switch ( $tab_type ) {
			case 'content':
				// Display tabs description.
				$this->sp_render_tab_content( $tabs_content );
				break;
			case 'products':
				// Display tabs description.
				$this->sp_render_tab_content( $tabs_content );
				// Display tabs products.
				$this->sp_render_product_tabs_section( $_tabs_data );
				break;
			default:
				echo esc_html__( 'No content available.', 'wp-expand-tabs-free' );
				break;
		}
	}

	/**
	 * Outputs content for WC product tab if content exists.
	 *
	 * @access private
	 * @param string $content Tab Content.
	 * @return void Outputs HTML directly
	 */
	private function sp_render_tab_content( $content ) {
		if ( ! empty( $content ) ) {
			global $wp_embed;
			if ( apply_filters( 'sp_wc_tabs_content_wpautop_remove', true ) ) {
				$content = wpautop( trim( $content ) );
			}
			$content = $wp_embed->autoembed( $content );
			$content = do_shortcode( shortcode_unautop( $content ) );
			echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Renders the product tabs section based on provided settings.
	 *
	 * @param array $_tabs_data Array containing product tabs settings and data.
	 * @return void Outputs the product tabs section with products.
	 * @access private
	 */
	private function sp_render_product_tabs_section( $_tabs_data ) {
		// Product Settings.
		$filter_products       = $_tabs_data['filter_products'] ?? 'latest';
		$specific_products     = $_tabs_data['specific_products'] ?? '';
		$products_order_by     = $_tabs_data['products_order_by'] ?? '';
		$_total_products_limit = $_tabs_data['number_of_total_products'] ?? '';
		$products_order        = $_tabs_data['products_order'] ?? '';
		$_total_products       = ( empty( $_total_products_limit ) ? 8 : absint( $_total_products_limit ) );

		$product_ids = $this->sp_get_tabbed_products(
			array(
				'query_type'      => $filter_products,
				'limit'           => $_total_products,
				'orderby'         => $products_order_by,
				'order'           => $products_order,
				'current_post_id' => get_the_ID(),
			),
			$specific_products,
			$_total_products
		);

		if ( $product_ids ) {
			$_column_value = 4;
			if ( function_exists( 'wc_get_loop_prop' ) ) {
				wc_set_loop_prop( 'columns', $_column_value );
			}
			// Display products.
			echo '<div id="sp-smart-product-tabs" class="woocommerce">';
			woocommerce_product_loop_start();

			global $post, $product;
			foreach ( $product_ids as $product_id ) {
				$post    = get_post( $product_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				$product = wc_get_product( $product_id ); // assign to global $product.

				setup_postdata( $post ); // setup postdata after assigning to $post.
				wc_get_template_part( 'content', 'product' );
			}
			wp_reset_postdata();
			woocommerce_product_loop_end();
			echo '</div>';
		} else {
			echo esc_html__( 'No products found.', 'wp-expand-tabs-free' );
		}
	}

	/**
	 * Retrieves WooCommerce products based on different query types for tabbed display.
	 *
	 * Supported query types:
	 * - 'latest'   : Fetches the latest published products.
	 * - 'specific'  : Fetches specific products by ID.
	 * - 'taxonomy' : Fetches products matching given taxonomy terms (e.g., category, tag).
	 * - 'related'  : Fetches related products for a given reference product ID.
	 *
	 * @param array $args An array of arguments to control the product query.
	 * @param array $specific_products Specific Product IDs.
	 * @param array $limit The total number of displaying products.
	 *
	 * @return array List of product IDs matching the given query type.
	 */
	private function sp_get_tabbed_products( $args = array(), $specific_products = array(), $limit = 6 ) {
		// Defaults & Sanitization.
		$defaults = array(
			'query_type'      => 'latest',
			'product_ids'     => $specific_products,
			'orderby'         => 'date',
			'order'           => 'DESC',
			'limit'           => $limit,
			'taxonomy'        => 'product_cat',
			'current_post_id' => get_the_ID(),
		);

		$args = wp_parse_args( $args, $defaults );

		$query_args = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => (int) $args['limit'],
			'orderby'        => sanitize_text_field( $args['orderby'] ),
			'order'          => sanitize_text_field( $args['order'] ),
			'fields'         => 'ids',
		);

		switch ( $args['query_type'] ) {
			case 'related':
				$product_id = absint( $args['current_post_id'] );

				// Bail early if no valid product ID or taxonomy doesn't exist.
				if ( ! $product_id ) {
					break;
				}

				$term_ids_cat = wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'ids' ) );
				$term_ids_tag = wp_get_post_terms( $product_id, 'product_tag', array( 'fields' => 'ids' ) );

				// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				$query_args['tax_query'] = array(
					'relation' => 'OR',
				);

				if ( ! empty( $term_ids_cat ) ) {
					$query_args['tax_query'][] = array(
						'taxonomy' => 'product_cat',
						'field'    => 'id',
						'terms'    => $term_ids_cat,
					);
				}

				if ( ! empty( $term_ids_tag ) ) {
					$query_args['tax_query'][] = array(
						'taxonomy' => 'product_tag',
						'field'    => 'id',
						'terms'    => $term_ids_tag,
					);
				}

				// @codingStandardsIgnoreLine
				$query_args['post__not_in'] = array( $product_id );
				break;

			case 'latest':
			default:
				break;
		}
		$query = new WP_Query( $query_args );
		return $query->posts;
	}

	/**
	 * WooCommerce faq tab.
	 *
	 * @return array $tabs
	 * @param array $tabs woo tab.
	 */
	public function sptpro_woo_tab_group( $tabs ) {
		global $product;
		$sptpro_woo_set_tabs = SP_WP_Tabs_Free::get_general_setting( 'sptpro_woo_set_tab', array() );

		if ( empty( $sptpro_woo_set_tabs ) && ! is_array( $sptpro_woo_set_tabs ) ) {
			return $tabs;
		}

		foreach ( $sptpro_woo_set_tabs as $sptpro_woo_set_tab ) {
			$sptpro_display_tab_for = $sptpro_woo_set_tab['sptpro_display_tab_for'];

			// Get the current product ID.
			$product_id = $this->get_woo_product_id();
			$cat_ids    = get_the_terms( $product_id, 'product_cat' );
			$cat_ids    = wp_list_pluck( $cat_ids, 'term_id' );

			if ( 'all' === $sptpro_display_tab_for ) {
				$sptpro_tab_priority          = isset( $sptpro_woo_set_tab['sptpro_woo_tab_label_priority'] ) ? $sptpro_woo_set_tab['sptpro_woo_tab_label_priority'] : '35';
				$sptpro_woo_tab_shortcode_ids = isset( $sptpro_woo_set_tab['sptpro_woo_tab_shortcode'] ) ? $sptpro_woo_set_tab['sptpro_woo_tab_shortcode'] : array();

				if ( ! empty( $sptpro_woo_tab_shortcode_ids ) ) {
					// If the shortcode IDs are not an array, convert it to an array.
					if ( ! is_array( $sptpro_woo_tab_shortcode_ids ) ) {
						$sptpro_woo_tab_shortcode_ids = explode( ',', $sptpro_woo_tab_shortcode_ids );
					}

					foreach ( $sptpro_woo_tab_shortcode_ids as $key => $sptpro_woo_tab_shortcode_id ) {
						$sptpro_data_src = get_post_meta( $sptpro_woo_tab_shortcode_id, 'sp_tab_source_options', true );
						$sptpro_tab_type = ( isset( $sptpro_data_src['sptpro_tab_type'] ) ? $sptpro_data_src['sptpro_tab_type'] : '' );
						$sptpro_data_src = isset( $sptpro_data_src['sptpro_content_source'] ) ? $sptpro_data_src['sptpro_content_source'] : '';

						if ( 'content-tabs' === $sptpro_tab_type && ! empty( $sptpro_data_src ) ) {
							foreach ( $sptpro_data_src as $key => $sptpro_tab ) {
								if ( ! is_array( $sptpro_tab ) ) {
									$sptpro_tab = array();
								}
								++$sptpro_tab_priority;

								// Check if WPML is active.
								if ( function_exists( 'icl_object_id' ) ) {
									// Get the language details for this product ID.
									$language_product_info   = apply_filters( 'wpml_post_language_details', null, $product_id );
									$language_shortcode_info = apply_filters( 'wpml_post_language_details', null, $sptpro_woo_tab_shortcode_id );

									$product_lang   = $language_product_info['language_code'];
									$shortcode_lang = $language_shortcode_info['language_code'];
								}

								if ( ! empty( $sptpro_tab ) && is_array( $sptpro_tab ) ) {
									if ( ! function_exists( 'icl_object_id' ) || $product_lang === $shortcode_lang ) {
										$tabs[ 'sptpro_tab_' . $sptpro_tab_priority . $key ] = array(
											'title'    => $sptpro_tab['tabs_content_title'],
											'priority' => '50',
											'callback' => array( $this, 'sptpro_product_tabs_group_panel_content' ),
											'content'  => $sptpro_tab['tabs_content_description'],
										);
									}
								}
							}
						}
					}
				}
			}
		}
		return $tabs;
	}

	/**
	 * Build the HTML for the tab title, including icon or custom image.
	 *
	 * @param array $settings The tab's meta settings containing title and icon info.
	 *
	 * @return string HTML string for the tab's display title.
	 */
	private function get_tab_name_html( $settings ) {
		$title_section = $settings['product_tab_title_section'] ?? array();
		$tab_name      = $title_section['tab_title'] ?? '';

		return $tab_name;
	}

	/**
	 * WooCommerce Tab Content.
	 *
	 * @param array $key tab key.
	 * @param array $tab woo tab content.
	 * @return void
	 */
	public function sptpro_product_tabs_group_panel_content( $key, $tab ) {
		$content = '';

		$sptpro_the_content_filter = apply_filters( 'sptpro_the_content_filter', true );

		if ( $sptpro_the_content_filter ) {
			$content = apply_filters( 'the_content', $tab['content'] );
		} else {
			$content = apply_filters( 'sptpro_woo_tab_content', $tab['content'] );
		}

		$tab_title_html = '<h2 class="sptpro-woo-tab-title sptpro-woo-tab-title-' . urldecode( sanitize_title( $tab['title'] ) ) . '">' . $tab['title'] . '</h2>';
		echo '<div class="sp-tab__tab-content">';
		echo wp_kses_post( apply_filters( 'sptpro_repeatable_product_tabs_heading', $tab_title_html, $tab ) );
		echo apply_filters( 'sptpro_repeatable_product_tabs_content', $content, $tab ); // @codingStandardsIgnoreLine
		echo '</div>';
	}

	/**
	 * Enqueue product admin tabs assets.
	 */
	public function enqueue_product_admin_tabs_assets() {
		$current_screen = get_current_screen();
		$_post_type     = $current_screen->post_type;
		// Check if we are on the product tabs admin screen, otherwise return early.
		$allowed_screens = array( 'sp_products_tabs', 'product' );
		// If we are in the admin area, check if the current screen is one of the allowed screens otherwise return early.
		if ( ! is_admin() && ! in_array( $_post_type, $allowed_screens, true ) ) {
			return;
		}

		wp_enqueue_style( 'sptpro-product-tabs-style', WP_TABS_URL . 'admin/css/product-admin-tabs' . $this->min . '.css', array(), WP_TABS_VERSION, 'all' );
		wp_enqueue_script( 'sptpro-product-tabs-script', WP_TABS_URL . 'admin/partials/models/assets/js/product-admin-tabs' . $this->min . '.js', array(), WP_TABS_VERSION, true );
		wp_enqueue_script( 'jquery-ui-sortable' );
	}

	/**
	 * Enqueue product tabs assets.
	 */
	public function enqueue_product_tabs_assets() {
		$is_elementor_preview = class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->preview->is_preview_mode();

		if ( ! is_product() && ! $this->is_divi_builder_active() && ! $is_elementor_preview ) {
			return;
		}

		$post_count_obj = wp_count_posts( 'sp_products_tabs' );
		$tab_post_count = isset( $post_count_obj->publish ) ? (int) $post_count_obj->publish : 0;
		if ( $tab_post_count <= 3 && version_compare( WP_TABS_FIRST_VERSION, '3.0.0', '<' ) ) {
			return;
		}

		wp_enqueue_style( 'sptpro-product-tabs-style', WP_TABS_URL . 'public/assets/css/tabs-style' . $this->min . '.css', array(), WP_TABS_VERSION, 'all' );

		if ( self::$is_skip_product_style ) {
			return;
		}

		$dynamic_style = '';
		// Load common configuration variables and settings that define the product tabs appearance.
		include SP_TABS_DYNAMIC_STYLES_DIR . '/woo-tabs-config.php';
		$is_divi_theme = 'divi' === strtolower( wp_get_theme()->get( 'Name' ) );

		// Check if the Divi Builder (visual or classic) has been used for this specific product page.
		if ( $is_divi_theme && $this->is_divi_builder_active() ) {
			// Divi-specific compatibility styles to correctly target Divi's generated HTML markup.
			include SP_TABS_DYNAMIC_STYLES_DIR . '/woo-tabs-divi-comatibility-styles.php';
		} else {
			// default styling designed for standard WooCommerce tab markup.
			include SP_TABS_DYNAMIC_STYLES_DIR . '/woo-tabs-style.php';
		}

		$load_dynamic_styles = WP_Tabs_Shortcode::minify_output( $dynamic_style );
		wp_add_inline_style( 'sptpro-product-tabs-style', $load_dynamic_styles );

		wp_enqueue_script( 'sptpro-tabs-type' );
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
			return 100; // Show 100 items per page in admin list.
		}

		return $per_page;
	}

	/**
	 * Check if the current product/page is being rendered or edited with Divi Builder.
	 *
	 * Handles:
	 * - Classic Builder
	 * - Visual Builder (Frontend)
	 * - Theme Builder Templates
	 *
	 * @return bool
	 */
	public function is_divi_builder_active() {
		if ( is_null( self::$use_divi_builder ) ) {
			self::$use_divi_builder = false;

			// Detect if Divi Theme Builder is rendering a layout.
			if ( function_exists( 'et_theme_builder_overrides_layout' ) && et_theme_builder_overrides_layout( ET_THEME_BUILDER_BODY_LAYOUT_POST_TYPE ) ) {
				self::$use_divi_builder = true;
			}

			// Detect Divi Frontend Builder (Visual Builder).
			if ( ! self::$use_divi_builder && function_exists( 'et_core_is_fb_enabled' ) && et_core_is_fb_enabled() ) {
				self::$use_divi_builder = true;
			}

			// Detect product-specific builder usage (Classic or Visual).
			if ( ! self::$use_divi_builder ) {
				$product_id = $this->get_woo_product_id();

				if ( $product_id ) {
					$use_divi_visual_builder  = get_post_meta( $product_id, '_et_pb_use_builder', true );
					$use_divi_classic_builder = get_post_meta( $product_id, 'et_pb_use_builder', true );

					if ( 'on' === $use_divi_visual_builder || 'on' === $use_divi_classic_builder ) {
						self::$use_divi_builder = true;
					}
				}
			}
		}

		return self::$use_divi_builder;
	}


	/**
	 * Add the main tabs class to the body for product tabs.
	 *
	 * @since 2.0.2
	 *
	 * @param array $classes The existing body classes.
	 * @return array
	 */
	public function sptpro_add_tabs_body_class( $classes ) {
		$is_elementor_preview = class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->preview->is_preview_mode();

		if ( is_product() || $this->is_divi_builder_active() || $is_elementor_preview ) {
			// Add the main tabs class.
			$classes[] = 'sptpro-smart-tabs';
		}

		return $classes;
	}
}
