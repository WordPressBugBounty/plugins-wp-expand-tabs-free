<?php
/**
 * Define the custom post type functionality for the Product Tabs.
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

namespace ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Define the custom post type functionality.
 */
class ProductTabsCPT {
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
		$this->loader->add_action( 'init', $this, 'register_cpt' );
		$this->loader->add_action( 'edit_form_top', $this, 'back_to_all_product_tabs' );
		$this->loader->add_filter( 'post_updated_messages', $this, 'updated_messages', 10, 2 );
	}

	/**
	 * Custom Post Type of the Plugin.
	 *
	 * @since    1.0.0
	 */
	public function register_cpt() {
		$capability = apply_filters( 'sp_wp_tab_pro_ui_permission', 'manage_options' );
		$show_ui    = current_user_can( $capability ) ? true : false;

		$labels = array(
			'name'               => esc_html__( 'Product Tabs', 'wp-expand-tabs-free' ),
			'singular_name'      => esc_html__( 'Tabs', 'wp-expand-tabs-free' ),
			'add_new'            => esc_html__( 'Add New', 'wp-expand-tabs-free' ),
			'add_new_item'       => esc_html__( '+ Add New Tab', 'wp-expand-tabs-free' ),
			'edit_item'          => esc_html__( 'Edit Tab', 'wp-expand-tabs-free' ),
			'new_item'           => esc_html__( 'New Tab', 'wp-expand-tabs-free' ),
			'view_item'          => esc_html__( 'View Tab', 'wp-expand-tabs-free' ),
			'search_items'       => esc_html__( 'Search', 'wp-expand-tabs-free' ),
			'not_found'          => esc_html__( 'No tabs found.', 'wp-expand-tabs-free' ),
			'not_found_in_trash' => esc_html__( 'No tabs found in trash.', 'wp-expand-tabs-free' ),
			'parent_item_colon'  => esc_html__( 'Parent Item:', 'wp-expand-tabs-free' ),
			'menu_name'          => esc_html__( 'Product Tabs', 'wp-expand-tabs-free' ),
			'all_items'          => esc_html__( 'Product Tabs', 'wp-expand-tabs-free' ) . '<span class="sp-tabs-menu-new-indicator" style="color: #f18200;font-size: 9px; padding-left: 3px;">' . esc_html__( ' NEW!', 'wp-expand-tabs-free' ) . '</span>',
		);

		$args = array(
			'labels'              => $labels,
			'public'              => false,
			'hierarchical'        => false,
			'exclude_from_search' => true,
			'show_ui'             => $show_ui,
			'show_in_menu'        => 'edit.php?post_type=sp_wp_tabs', // This is the key line!
			'show_in_admin_bar'   => false,
			'capability_type'     => 'post',
			'rewrite'             => false,
			'query_var'           => false,
			'supports'            => array( 'page-attributes' ),
		);

		register_post_type( 'sp_products_tabs', $args );
	}


	/**
	 * Change Tabs updated messages.
	 *
	 * @param string $messages The Update messages.
	 * @return statement
	 */
	public function updated_messages( $messages ) {
		global $post;
		$revision_id = isset( $_GET['revision'] ) ? absint( $_GET['revision'] ) : false; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe read-only access

		// Messages for 'sp_products_tabs'.
		$messages['sp_products_tabs'] = array(
			0  => '',
			1  => '',
			2  => '',
			3  => '',
			4  => esc_html__( 'Product tab updated.', 'wp-expand-tabs-free' ),
			// translators: %s: Revision date and time.
			5  => $revision_id ? sprintf( esc_html__( 'Product tab restored to revision from %s.', 'wp-expand-tabs-free' ), wp_post_revision_title( $revision_id, false ) ) : false,
			6  => esc_html__( 'Product tab published.', 'wp-expand-tabs-free' ),
			7  => esc_html__( 'Product tab saved.', 'wp-expand-tabs-free' ),
			8  => esc_html__( 'Product tab submitted.', 'wp-expand-tabs-free' ),
			// translators: %s: Scheduled date and time.
			9  => sprintf( esc_html__( 'Product tab scheduled for: <strong>%1$s</strong>.', 'wp-expand-tabs-free' ), date_i18n( esc_html__( 'M j, Y @ G:i', 'wp-expand-tabs-free' ), strtotime( $post->post_date ) ) ),
			10 => esc_html__( 'Product tab draft updated.', 'wp-expand-tabs-free' ),
			11 => esc_html__( 'tab moved to the Trash.', 'wp-expand-tabs-free' ),
			12 => esc_html__( 'tab restored from the Trash.', 'wp-expand-tabs-free' ),
		);

		return $messages;
	}

	/**
	 * Outputs a "Back to All Tabs" button at the top of the admin edit screen for 'sp_products_tabs' post type.
	 *
	 * @param WP_Post $post The current post object being edited.
	 */
	public function back_to_all_product_tabs( $post ) {
		if ( 'sp_products_tabs' !== $post->post_type ) {
			return;
		}

		$back_url = admin_url( 'edit.php?post_type=sp_products_tabs' );
		echo '<a href="' . esc_url( $back_url ) . '" class="sptpro-back-top-product-tabs button" style="margin-bottom: 10px;display:none;">❮ Back to "Product Tabs"</a>';
	}
}
