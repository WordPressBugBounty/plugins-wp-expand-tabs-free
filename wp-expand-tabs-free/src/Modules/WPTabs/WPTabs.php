<?php
/**
 * The ShortCode-based Tabs module class.
 *
 * @since 3.2.1
 *
 * @package ShapedPlugin\SmartTabsFree
 * @subpackage Modules\WPTabs
 */

namespace ShapedPlugin\SmartTabsFree\Modules\WPTabs;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use ShapedPlugin\SmartTabsFree\Core\Loader;
use ShapedPlugin\SmartTabsFree\Modules\WPTabs\Admin\Admin;
use ShapedPlugin\SmartTabsFree\Modules\WPTabs\Admin\TabsPostType;
use ShapedPlugin\SmartTabsFree\Modules\WPTabs\Frontend\Frontend;
use ShapedPlugin\SmartTabsFree\Modules\WPTabs\Frontend\Parsedown;
use ShapedPlugin\SmartTabsFree\Modules\WPTabs\Frontend\Shortcode;

/**
 * WPTabs class for the ShortCode-based Tabs functionality.
 *
 * @since 3.2.0
 */
class WPTabs {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the ShortCode-based Tabs module.
	 *
	 * @var Loader
	 */
	private $loader;

	/**
	 * Register all of the hooks related to the ShortCode-based Tabs.
	 *
	 * @param Loader $loader The loader that's responsible for maintaining and registering all hooks.
	 * @return void
	 */
	public function register( Loader $loader ): void {
		$admin     = new Admin();
		$cpt       = new TabsPostType();
		$frontend  = new Frontend();
		$shortcode = new Shortcode();
		$parsedown = new Parsedown();

		// Register Shortcode Tabs Hooks.
		$this->register_admin_hooks( $loader, $admin );
		$this->register_cpt_hooks( $loader, $cpt );
		$this->register_frontend_hooks( $loader, $frontend );
		$this->register_shortcode_hooks( $loader, $shortcode );
	}

	/**
	 * Register all of the hooks related to the admin functionality
	 * of the ShortCode-based Tabs.
	 *
	 * @since    3.2.0
	 * @param Loader $loader The loader that's responsible for maintaining and registering all hooks.
	 * @param Admin  $admin  The admin controller instance.
	 */
	private function register_admin_hooks( Loader $loader, Admin $admin ): void {
		$loader->add_action( 'widgets_init', $admin, 'register_widget' );
		$loader->add_action( 'admin_action_sp_duplicate_tabs', $admin, 'duplicate_tabs' );
		$loader->add_filter( 'post_row_actions', $admin, 'duplicate_tabs_link', 10, 2 );
		$loader->add_action( 'before_woocommerce_init', $admin, 'declare_woo_hpos_compatibility' );
		$loader->add_action( 'activated_plugin', $admin, 'sp_tabs_redirect_after_activation', 10, 2 );
	}

	/**
	 * Register all of the hooks related to the custom post type functionality
	 * of the ShortCode-based Tabs.
	 *
	 * @since    3.2.0
	 * @param Loader       $loader The loader that's responsible for maintaining and registering all hooks.
	 * @param TabsPostType $cpt    The custom post type instance.
	 */
	private function register_cpt_hooks( Loader $loader, TabsPostType $cpt ): void {
		$loader->add_action( 'init', $cpt, 'register' );
		$loader->add_filter( 'post_updated_messages', $cpt, 'updated_messages', 10, 2 );
		$loader->add_filter( 'manage_sp_wp_tabs_posts_columns', $cpt, 'columns' );
		$loader->add_action( 'manage_sp_wp_tabs_posts_custom_column', $cpt, 'custom_column', 10, 2 );
	}

	/**
	 * Register all of the hooks related to the frontend functionality
	 * of the ShortCode-based Tabs.
	 *
	 * @since    3.2.0
	 * @param Loader   $loader   The loader that's responsible for maintaining and registering all hooks.
	 * @param Frontend $frontend The frontend instance.
	 */
	private function register_frontend_hooks( Loader $loader, Frontend $frontend ): void {
		$loader->add_action( 'wp_loaded', $frontend, 'register_scripts' );
		$loader->add_action( 'wp_enqueue_scripts', $frontend, 'front_enqueue_style' );
		$loader->add_action( 'admin_enqueue_scripts', $frontend, 'admin_enqueue_scripts' );
		$loader->add_action( 'save_post', $frontend, 'delete_page_sp_tabs_option_on_save' );
		$loader->add_filter( 'sp_wp_tabs_content', $frontend, 'sp_wp_tabs_markdown_to_html' );
	}

	/**
	 * Register all of the hooks related to the shortcode functionality
	 * of the ShortCode-based Tabs.
	 *
	 * @since    3.2.0
	 * @param Loader    $loader   The loader that's responsible for maintaining and registering all hooks.
	 * @param Shortcode $shortcode The shortcode instance.
	 */
	private function register_shortcode_hooks( Loader $loader, Shortcode $shortcode ): void {
		$loader->add_action( 'sptpro_action_tag_for_shortcode', $shortcode, 'execute_tabs_shortcode' );
		add_shortcode( 'wptabs', array( $shortcode, 'execute_tabs_shortcode' ) );
	}
}
