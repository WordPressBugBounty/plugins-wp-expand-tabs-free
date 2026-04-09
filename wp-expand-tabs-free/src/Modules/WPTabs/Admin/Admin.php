<?php
/**
 * The admin-specific functionality for the Shortcode-based Tabs.
 *
 * @link       https://shapedplugin.com/
 * @since      3.2.0
 *
 * @package    ShapedPlugin\SmartTabsFree
 * @subpackage Admin
 */

namespace ShapedPlugin\SmartTabsFree\Modules\WPTabs\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use ShapedPlugin\SmartTabsFree\Core\Constants;
use ShapedPlugin\SmartTabsFree\Modules\WPTabs\Admin\Configs\Metabox;
use ShapedPlugin\SmartTabsFree\Modules\WPTabs\Admin\Configs\Settings;
use ShapedPlugin\SmartTabsFree\Lib\CSFramework\Classes\SP_CSFramework;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 */
class Admin {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'init_meta_configs' ) );

		// Tabs Backend Preview.
		new TabsPreview();
	}

	/**
	 * Initialize meta box configurations.
	 *
	 * @since    3.2.0
	 */
	public function init_meta_configs() {
		Settings::option_settings( 'sp-tab__settings' );
		Metabox::preview_section( 'sp_tab_live_preview' );
		Metabox::tabs_source( 'sp_tab_source_options' );
		Metabox::tabs_metaboxes( 'sp_tab_shortcode_options' );
	}

	/**
	 * Register the widget for the public-facing side of the site.
	 *
	 * @param arr $widget The widget of the plugin.
	 * @since    2.0.1
	 */
	public function register_widget( $widget ) {
		register_widget( \ShapedPlugin\SmartTabsFree\Integration\TabsWidget::class );
		return $widget;
	}

	/**
	 * Function creates tabs duplicate as a draft.
	 */
	public function duplicate_tabs() {
		global $wpdb;
		if ( ! ( isset( $_GET['post'] ) || isset( $_POST['post'] ) || ( isset( $_REQUEST['action'] ) && 'sp_duplicate_tabs' === $_REQUEST['action'] ) ) ) {
			wp_die( esc_html__( 'No tabs to duplicate has been supplied!', 'wp-expand-tabs-free' ) );
		}

		/*
		* Nonce verification
		*/
		if ( ! isset( $_GET['sp_duplicate_tabs_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['sp_duplicate_tabs_nonce'] ) ), basename( __FILE__ ) ) ) {
			return;
		}

		/*
		* Get the original shortcode id
		*/
		$post_id    = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : absint( $_POST['post'] );
		$capability = apply_filters( 'sp_wp_tabs_ui_permission', 'manage_options' );
		$show_ui    = current_user_can( $capability ) ? true : false;
		if ( ! $show_ui || get_post_type( $post_id ) !== 'sp_wp_tabs' ) {
			wp_die( esc_html__( 'No shortcode to duplicate has been supplied!', 'wp-expand-tabs-free' ) );
		}

		/*
		* and all the original shortcode data then
		*/
		$post = get_post( $post_id );

		$current_user    = wp_get_current_user();
		$new_post_author = $current_user->ID;

		/*
		* if shortcode data exists, create the shortcode duplicate
		*/
		if ( isset( $post ) && null !== $post ) {
			/*
			* New shortcode data array.
			*/
			$args = array(
				'comment_status' => $post->comment_status,
				'ping_status'    => $post->ping_status,
				'post_author'    => $new_post_author,
				'post_content'   => $post->post_content,
				'post_excerpt'   => $post->post_excerpt,
				'post_name'      => $post->post_name,
				'post_parent'    => $post->post_parent,
				'post_password'  => $post->post_password,
				'post_status'    => 'draft',
				'post_title'     => $post->post_title,
				'post_type'      => $post->post_type,
				'to_ping'        => $post->to_ping,
				'menu_order'     => $post->menu_order,
			);

			/*
			* insert the shortcode by wp_insert_post() function
			*/
			$new_post_id = wp_insert_post( $args );

			/*
			* get all current post terms ad set them to the new post draft
			*/
			$taxonomies = get_object_taxonomies( $post->post_type ); // returns array of taxonomy names for post type, ex array("category", "post_tag").
			foreach ( $taxonomies as $taxonomy ) {
				$post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'slugs' ) );
				wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
			}

			/*
			* Duplicate all post meta.
			*/
			$post_meta_infos = get_post_custom( $post_id );
			// Duplicate all post meta just.
			foreach ( $post_meta_infos as $key => $values ) {
				foreach ( $values as $value ) {
					if ( is_serialized( $value ) ) {
						// If the value is serialized, we need to unserialize it.
						$value = unserialize( $value, array( 'allowed_classes' => false ) );
					}
					$value = wp_slash( $value );
					add_post_meta( $new_post_id, $key, $value );
				}
			}
			// Finally, redirect to the edit post screen for the new draft.
			wp_safe_redirect( admin_url( 'edit.php?post_type=' . $post->post_type ) );

			exit;
		} else {
			wp_die( esc_html__( 'Tabs creation failed, could not find original tabs: ', 'wp-expand-tabs-free' ) . esc_html( $post_id ) );
		}
	}

	/**
	 * Add the duplicate link to action list for post_row_actions
	 */
	/**
	 * Add the duplicate link to action list for post_row_actions
	 *
	 * @param  mixed $actions action.
	 * @param  mixed $post post type.
	 * @return statement
	 */
	public function duplicate_tabs_link( $actions, $post ) {
		$capability = apply_filters( 'sp_wp_tabs_ui_permission', 'manage_options' );
		$show_ui    = current_user_can( $capability ) ? true : false;
		if ( $show_ui && 'sp_wp_tabs' === $post->post_type ) {
			$actions['duplicate'] = '<a href="' . wp_nonce_url( 'admin.php?action=sp_duplicate_tabs&post=' . $post->ID, basename( __FILE__ ), 'sp_duplicate_tabs_nonce' ) . '" rel="permalink">' . esc_html__( 'Duplicate', 'wp-expand-tabs-free' ) . '</a>';
		}
		return $actions;
	}

	/**
	 * Declare the compatibility of WooCommerce High-Performance Order Storage (HPOS) feature.
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	public function declare_woo_hpos_compatibility() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', 'wp-expand-tabs-free/plugin-main.php', true );
		}
	}


	/**
	 * Check if we are on the tabs settings page.
	 *
	 * @return boolean
	 */
	private function is_tabs_settings_page() {
		$page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : ''; // phpcs:ignore
		return 'tabs_settings' === $page;
	}

	/**
	 * Redirect after activation.
	 *
	 * @param string $file Path to the plugin file, relative to the plugin.
	 * @return void
	 */
	public function sp_tabs_redirect_after_activation( $file ) {
		if ( Constants::basename() === $file && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) && ! ( defined( 'WP_CLI' ) && WP_CLI ) ) {
			exit( esc_url( wp_safe_redirect( admin_url( 'edit.php?post_type=sp_wp_tabs&page=tabs_help' ) ) ) );
		}
	}
}
