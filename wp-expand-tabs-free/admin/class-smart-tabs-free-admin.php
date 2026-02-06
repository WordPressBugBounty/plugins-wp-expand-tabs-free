<?php
/**
 * Smart Tabs - Admin Functionality
 *
 * Handles all admin-related functionality for the Smart Tabs plugin.
 *
 * @package SmartTabs
 * @subpackage Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Smart_Tabs_Free_Admin
 *
 * Handles the admin functionality for the Smart Tabs plugin.
 *
 * @since 2.2.3
 */
class Smart_Tabs_Free_Admin {
	/**
	 * Smart_Tabs_Free_Admin constructor.
	 *
	 * Initializes the admin functionality by adding necessary hooks and filters.
	 *
	 * @since 2.2.3
	 */
	public function __construct() {
		$this->register_admin_ui_hooks();
		$this->register_post_type_hooks();
		$this->register_ajax_hooks();
		$this->register_woocommerce_hooks();
		$this->register_permission_hooks();
		$this->register_includes();
	}

	/**
	 * Register Product Tabs Admin UI related admin hooks.
	 */
	private function register_admin_ui_hooks() {
		add_action( 'in_admin_header', array( $this, 'sp_tabs_woo_product_tabbed_interface' ) );
		add_filter( 'submenu_file', array( $this, 'sp_tabs_set_active_submenu' ) );
		add_filter( 'screen_options_show_screen', array( $this, 'disable_screen_options_panel' ), 10, 2 );
		// Popup HTML for "+x more" links in the "Show In" column.
		add_action( 'admin_footer', array( $this, 'sp_tabs_show_in_column_popup' ) );
	}

	/**
	 * Register Product Tabs post type column and post-related hooks.
	 */
	private function register_post_type_hooks() {
		add_filter( 'manage_sp_products_tabs_posts_columns', array( $this, 'add_product_tabs_columns' ) );
		add_filter( 'manage_sp_products_tabs_posts_columns', array( $this, 'sp_add_order_row_for_admin_table' ) );
		add_action( 'manage_sp_products_tabs_posts_custom_column', array( $this, 'add_product_tabs_columns_data' ), 10, 2 );
		add_action( 'manage_sp_products_tabs_posts_custom_column', array( $this, 'sp_add_sortable_icons_in_product_tabs_column' ), 10, 2 );

		add_action( 'add_meta_boxes_sp_products_tabs', array( $this, 'add_product_tabs_custom_save_button' ) );
		add_action( 'save_post_sp_products_tabs', array( $this, 'auto_publish_new_tab' ) );
		// Ensure the admin list and search use the tab name/title (from custom meta field) instead of the default WP post title.
		add_filter( 'wp_insert_post_data', array( $this, 'sp_override_tab_title_before_insert' ), 10, 2 );
		// Ensure newly created sp_products_tabs posts show the correct "published" admin notice.
		add_filter( 'redirect_post_location', array( $this, 'set_correct_publish_message' ), 10, 2 );
		add_filter( 'the_title', array( $this, 'modify_admin_table_title_name' ), 10, 2 );
		add_filter( 'post_row_actions', array( $this, 'sp_hide_row_actions_for_default_tabs' ), 10, 2 );
		// Remove "Edit" bulk action from sp_products_tabs list table.
		add_filter( 'bulk_actions-edit-sp_products_tabs', array( $this, 'remove_edit_bulk_action' ) );
		add_filter( 'post_class', array( $this, 'sp_add_custom_class_to_default_tabs' ), 10, 3 );
	}

	/**
	 * Handle AJAX-related hooks.
	 */
	private function register_ajax_hooks() {
		add_action( 'wp_ajax_save_tabs_custom_button_state', array( $this, 'save_tabs_custom_button_state' ) );
	}

	/**
	 * WooCommerce-specific hooks and integrations.
	 */
	private function register_woocommerce_hooks() {
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'add_woo_product_custom_tabs' ) );
		// Create default WooCommerce tabs 'Description', 'Additional Information', 'Reviews' and  in the admin post list.
		add_action( 'init', array( $this, 'sp_create_default_woocommerce_tabs' ) );
		add_action( 'woocommerce_product_data_panels', array( $this, 'render_product_tabs_panel' ) );
	}

	/**
	 * Permissions and capabilities for Product Tabs post.
	 */
	private function register_permission_hooks() {
		add_filter( 'user_has_cap', array( $this, 'sp_restrict_default_tab_caps' ), 10, 4 );
	}

	/**
	 * Includes and theme setup.
	 */
	private function register_includes() {
		add_action( 'after_setup_theme', array( $this, 'sp_smart_tab_settings' ) );
	}

	/**
	 * Load the Smart Tab settings partial.
	 */
	public function sp_smart_tab_settings() {
		if ( is_admin() ) {
			require_once WP_TABS_PATH . '/admin/partials/smart-tab-settings.php';
		}
	}

	/**
	 * Load the WooCommerce Custom product tabs panel with all-related overridblesds options.
	 */
	public function render_product_tabs_panel() {
		require_once WP_TABS_PATH . '/admin/partials/product-tabs-panel.php';
	}

	/**
	 * Add Woo product tabs admin menu.
	 */
	public function sp_tabs_woo_product_tabbed_interface() {
		$screen = get_current_screen();

		$target_bases    = array( 'sp_wp_tabs_page_tab_advanced', 'sp_wp_tabs_page_tab_styles', 'edit', 'post' );
		$is_target_base  = in_array( $screen->base, $target_bases, true );
		$is_product_tabs = 'sp_products_tabs' === $screen->post_type;

		// Remove admin notices for the tabs settings page and product tabs post type.
		if ( is_admin() && ( ( $is_target_base && $is_product_tabs ) || ( $is_target_base && 'sp_wp_tabs' === $screen->post_type ) ) ) {
			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'all_admin_notices' );
		}

		$allowed_screens = array( 'sp_wp_tabs_page_tab_advanced', 'sp_wp_tabs_page_tab_styles' );
		if (
			! in_array( $screen->base, $allowed_screens, true ) &&
			( 'sp_products_tabs' !== $screen->post_type || 'edit' !== $screen->base )
		) {
			return;
		}
		?>
		<div class="wrap sp_woo_product_settings_tabbed">
			<h2 class="nav-tab-wrapper">
				<!-- Tabs General -->
				<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=sp_products_tabs&tab=general' ) ); ?>" class="nav-tab <?php echo ( 'sp_products_tabs' === $screen->post_type && 'edit' === $screen->base ) ? 'nav-tab-active' : ''; ?>"><i class="wptabspro-tabbed-icon icon-woo-product-tabs-general"></i><?php echo esc_html_e( 'Product Tabs', 'wp-expand-tabs-free' ); ?></a>
				<!-- Tabs Style -->
				<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=sp_wp_tabs&page=tab_styles' ) ); ?>" class="nav-tab <?php echo ( 'sp_wp_tabs_page_tab_styles' === $screen->base ) ? 'nav-tab-active' : ''; ?>"><i class="wptabspro-tabbed-icon icon-woo-product-tab-style"></i><?php echo esc_html_e( 'Style', 'wp-expand-tabs-free' ); ?></a>
				<!-- Tabs Advanced -->
				<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=sp_wp_tabs&page=tab_advanced' ) ); ?>" class="nav-tab <?php echo ( 'sp_wp_tabs_page_tab_advanced' === $screen->base ) ? 'nav-tab-active' : ''; ?>"><i class="wptabspro-tabbed-icon icon-woo-product-advanced"></i><?php echo esc_html_e( 'Advanced', 'wp-expand-tabs-free' ); ?></a>
			</h2>
		</div>
		<?php
	}

	/**
	 * Set the active submenu for the tabs settings page.
	 *
	 * @param string $submenu_file The submenu file.
	 * @return string
	 */
	public function sp_tabs_set_active_submenu( $submenu_file ) {
		global $current_screen;
		if ( 'sp_wp_tabs_page_tab_styles' === $current_screen->id || 'sp_wp_tabs_page_tab_advanced' === $current_screen->id ) {
			$submenu_file = 'edit.php?post_type=sp_products_tabs';
		}

		return $submenu_file;
	}

	/**
	 * Product Tabs Column
	 *
	 * @return array
	 */
	public function add_product_tabs_columns() {
		$new_columns['cb']        = '<input type="checkbox" />';
		$new_columns['title']     = esc_html__( 'Tabs Name', 'wp-expand-tabs-free' );
		$new_columns['tab_type']  = esc_html__( 'Tab Content Type', 'wp-expand-tabs-free' );
		$new_columns['show_in']   = esc_html__( 'Show Tab In', 'wp-expand-tabs-free' );
		$new_columns['show_tabs'] = esc_html__( 'Enable/Disable', 'wp-expand-tabs-free' );

		return $new_columns;
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
			// Update post status to 'publish'.
			// wp_update_post(
			// array(
			// 'ID'          => $post_id,
			// 'post_status' => 'publish',
			// )
			// );

			// Mark it as just published to track the publising status for the redirect message.
			update_post_meta( $post_id, '_tab_was_just_published', 1 );
		}
	}

	/**
	 * Add custom tabs to WooCommerce product data.
	 *
	 * @param array $tabs Existing WooCommerce product data tabs.
	 * @return array Modified tabs with a custom tab.
	 */
	public function add_woo_product_custom_tabs( $tabs ) {
		$tabs['sp_products_table'] = array(
			'label'    => esc_html__( 'Product Tabs', 'wp-expand-tabs-free' ),
			'target'   => 'sp_product_tabs_panel', // This will be the ID of the div we create later.
			// 'class'    => array( 'show_if_simple', 'show_if_variable' ), // Show for simple and variable products.
			'priority' => 80, // Adjust position among tabs.
		);

		return $tabs;
	}

	/**
	 * Modify the title column in the admin list table for 'sp_products_tabs' CPT.
	 *
	 * @param string $title The post title.
	 * @param int    $post_id The post ID.
	 * @return string Modified title.
	 */
	public function modify_admin_table_title_name( $title, $post_id = null ) {
		if ( ! $post_id ) {
			return $title;
		}

		// Ensure the function exists before calling it.
		if ( ! function_exists( 'get_current_screen' ) ) {
			require_once ABSPATH . '/wp-admin/includes/screen.php';
		}
		// Only modify the title in the admin list table for 'sp_products_tabs' post type.
		$screen = get_current_screen();
		if ( is_admin() && $screen && 'sp_products_tabs' === $screen->post_type && 'edit' === $screen->base ) {

			$slug = get_post_meta( $post_id, '_wc_default_tab_slug', true );
			if ( in_array( $slug, array( 'description', 'additional_information', 'reviews' ), true ) ) {
				remove_filter( 'the_title', 'esc_html' ); // prevent double escaping.
				return esc_html( $title );
			}

			$product_tabs_meta   = get_post_meta( $post_id, 'sptpro_woo_product_tabs_settings', true );
			$tabs_title          = $product_tabs_meta['product_tab_title_section'] ?? '';
			$tabs_content_titles = ! empty( $tabs_title['tab_title'] ) ? $tabs_title['tab_title'] : '(No Title)';
			if ( $tabs_content_titles ) {
				return esc_html( $tabs_content_titles );
			}
		}

		return $title;
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
	 * Create default WooCommerce tabs if they do not exist.
	 *
	 * This function checks for the existence of default WooCommerce tabs
	 * and creates them if they are not found. The tabs include Description,
	 * Reviews, and Additional Information.
	 *
	 * @return void
	 */
	public function sp_create_default_woocommerce_tabs() {
		$default_tabs = array(
			'description'            => 'Description',
			'additional_information' => 'Additional Information',
			'reviews'                => 'Reviews',
		);

		$menu_order = 0;
		foreach ( $default_tabs as $slug => $title ) {
			$existing = get_posts(
				array(
					'post_type'      => 'sp_products_tabs',
					'meta_query'     => array( // phpcs:ignore -- WordPress.DB.SlowDBQuery.slow_meta_query -- Acceptable here because it runs only during plugin activation.
						array(
							'key'   => '_wc_default_tab_slug',
							'value' => $slug,
						),
					),
					'posts_per_page' => 1,
					'post_status'    => 'any',
					'fields'         => 'ids',
				)
			);

			if ( empty( $existing ) ) {
				wp_insert_post(
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
			}
		}
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
		if ( isset( $args[0], $args[2] ) && in_array( $args[0], array( 'delete_post', 'edit_post' ), true ) ) {
			$post_id = (int) $args[2];
			$slug    = get_post_meta( $post_id, '_wc_default_tab_slug', true );

			if ( in_array( $slug, array( 'description', 'additional_information', 'reviews' ), true ) ) {
				$allcaps[ $args[0] ] = false;
			}
		}
		return $allcaps;
	}

	/**
	 * Hide row actions ("Edit", "Quick Edit", "Trash") for default WooCommerce tabs.
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
	 * Adds custom classes to default WooCommerce tabs in the admin.
	 *
	 * @param array  $classes The existing classes for the post.
	 * @param string $single_class   The class being added.
	 * @param int    $post_id int The ID of the post.
	 */
	public function sp_add_custom_class_to_default_tabs( $classes, $single_class, $post_id ) {
		if ( is_admin() && get_post_type( $post_id ) === 'sp_products_tabs' ) {
			$slug = get_post_meta( $post_id, '_wc_default_tab_slug', true );
			if ( in_array( $slug, array( 'description', 'additional_information', 'reviews' ), true ) ) {
				$classes[] = 'sp-woo-default-tab'; // Generic class for styling all default tabs.
			}
		}
		return $classes;
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
	 * Removes 'Screen Option' panel from 'sp_products_tabs' post type.
	 *
	 * @param bool   $show_screen Whether to show the screen options panel.
	 * @param object $screen The current screen object.
	 * @return bool
	 */
	public function disable_screen_options_panel( $show_screen, $screen ) {
		if ( isset( $screen->post_type ) && ( 'sp_products_tabs' === $screen->post_type || 'sp_wp_tabs' === $screen->post_type ) ) {
			return false;
		}
		return $show_screen;
	}

	/**
	 * Popup HTML for the "+x more" link in the "Show In" column.
	 *
	 * This function outputs the HTML structure and necessary JavaScript
	 * to handle the popup display when clicking on the "+x more" link
	 * in the "Show In" column of the product tabs admin list.
	 */
	public function sp_tabs_show_in_column_popup() {
		?>
		<!-- Popup HTML Structure -->
		<div id="sp-tabs-popup">
			<div class="sp-tabs-popup-body"></div>
			<span class="sp-tabs-popup-close">&times;</span>
		</div>
		<!-- Popup CSS Styles -->
		<style>
			#sp-tabs-popup {
				position: absolute; /* absolute positioning to appear above button */
				display: none;
				z-index: 9999;
				background: #fff;
				border: 1px solid #ccc;
				padding: 10px;
				border-radius: 4px;
				box-shadow: 0 2px 8px rgba(0,0,0,0.2);
				max-width: 250px;
				width: auto;
				height: auto;
			}
			.sp-tabs-popup-overlay { 
				position:absolute;
				top:0;
				left:0;
				right:0;
				bottom:0; 
				background:rgba(0,0,0,0.4);
			}
			.sp-tabs-popup-content { 
				position: relative; 
				width: 400px; 
				max-width: 90%; 
				background:#fff; 
				margin:100px auto; 
				padding:20px; 
				border-radius:8px; 
				box-shadow:0 5px 20px rgba(0,0,0,0.2);
			}
			.sp-tabs-popup-close { 
				position: absolute;
				top: 0px;
				right: 10px;
				cursor: pointer;
				font-size: 18px;
				display:block;
				text-align:right;
				margin-top:5px;
			}
			.sp-tabs-popup-body {
				line-height: 1.4;
				margin-right: 30px;
			}
		</style>
		<!-- Popup JavaScript -->
		<script>
		jQuery(document).ready(function($) {
			$(document).on('click', '.sp-tabs-more-link', function(e) {
				e.preventDefault();
				var $button = $(this);
				var items   = $button.data('items');   // full list of terms
				var label   = $button.data('label');   // Category / Tags / Brand...
				var $popup  = $('#sp-tabs-popup');

				// Fill content
				$popup.find('.sp-tabs-popup-body').html(
					'<strong style="color: #8B9494;">' + label + '</strong>: ' + items
				);

				// Get button position
				var offset = $button.offset();
				var popupHeight = $popup.outerHeight();
				
				// Position above button
				$popup.css({
					top: offset.top - popupHeight - 50, // 50px above
					left: offset.left - 100,
					display: 'block'
				});
			});

			// Close popup when clicking overlay or close button.
			$(document).on('click', '.sp-tabs-popup-close', function() {
				$('#sp-tabs-popup').fadeOut(100);
			});

			// Click outside to close popup.
			$(document).on('click', function(e) {
				if (!$(e.target).closest('.sp-tabs-more-link, #sp-tabs-popup').length) {
					$('#sp-tabs-popup').fadeOut(100);
				}
			});
		});
		</script>
		<?php
	}
}
