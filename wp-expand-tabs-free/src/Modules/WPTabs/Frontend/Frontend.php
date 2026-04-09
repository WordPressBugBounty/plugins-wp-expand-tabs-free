<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.0
 *
 * @package    WP_Tabs_Pro
 * @subpackage WP_Tabs_Pro/public
 * @author     ShapedPlugin <help@shapedplugin.com>
 */

namespace ShapedPlugin\SmartTabsFree\Modules\WPTabs\Frontend;

use ShapedPlugin\SmartTabsFree\Core\plugin;
use ShapedPlugin\SmartTabsFree\Core\Constants;
use ShapedPlugin\SmartTabsFree\Shared\SettingsHelper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 */
class Frontend {
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $minify scripts.
	 */
	private $min;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->min = ( apply_filters( 'smart_tabs_enqueue_dev_mode', false ) || WP_DEBUG ) ? '' : '.min';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function register_scripts() {
		/**
		 *  Register all the styles for the public-facing side of the site.
		 */
		wp_register_style( 'sptpro-accordion-style', esc_url( Constants::wp_tabs_url() . 'Frontend/Assets/css/sptpro-accordion' . $this->min . '.css' ), array(), Constants::VERSION, 'all' );
		wp_register_style( 'sptpro-style', esc_url( Constants::wp_tabs_url() . 'Frontend/Assets/css/wp-tabs-public' . $this->min . '.css' ), array(), Constants::VERSION, 'all' );
		wp_register_style( 'admin-sptpro-style', esc_url( Constants::wp_tabs_url() . 'Frontend/Assets/css/wp-tabs-public' . $this->min . '.css' ), array(), Constants::VERSION, 'all' );
		wp_register_style( 'sptpro-animate-css', Constants::wp_tabs_url() . 'Frontend/Assets/css/animate' . $this->min . '.css', array(), Constants::VERSION, 'all' );

		/**
		 * Register all the Scripts for the public-facing side of the site.
		 */
		wp_register_script( 'sptpro-tab', esc_url( Constants::wp_tabs_url() . 'Frontend/Assets/js/tab' . $this->min . '.js' ), array( 'jquery' ), Constants::VERSION, false );
		wp_register_script( 'sptpro-collapse', esc_url( Constants::wp_tabs_url() . 'Frontend/Assets/js/collapse' . $this->min . '.js' ), array( 'jquery' ), Constants::VERSION, false );
		wp_register_script( 'sptpro-script', esc_url( Constants::wp_tabs_url() . 'Frontend/Assets/js/wp-tabs-public' . $this->min . '.js' ), array( 'jquery' ), Constants::VERSION, true );
	}



	/**
	 * Enqueue the stylesheets and scripts for the live preview.
	 *
	 * @since    2.0.10
	 */
	public function front_enqueue_style() {
		/**
		* This function is provided for demonstration purposes only.
		*
		* An instance of this class should be passed to the run() function
		* defined in WP_Tabs_Loader as all of the hooks are defined
		* in that particular class.
		*
		* The WP_Tabs_Loader will then create the relationship
		* between the defined hooks and the functions defined in this
		* class.
		*/

		// Get the existing shortcode id from the current page.
		$get_page_data      = Helper::get_page_data();
		$found_shortcode_id = $get_page_data['generator_id'];

		if ( $found_shortcode_id ) {
			// Load dynamic style for the existing shordcodes.
			$dynamic_style = Helper::load_dynamic_style( $found_shortcode_id );

			$accordion_mode = $dynamic_style['accordion'];
			if ( $accordion_mode ) {
				wp_enqueue_style( 'sptpro-accordion-style' );
			}
			wp_enqueue_style( 'sptpro-style' );
			wp_enqueue_style( 'sptpro-animate-css' );
			wp_add_inline_style( 'sptpro-style', $dynamic_style['dynamic_css'] );
		}
	}

	/**
	 * Delete page shortcode ids array option on save
	 *
	 * @param  int $post_ID current post id.
	 * @return void
	 */
	public function delete_page_sp_tabs_option_on_save( int $post_ID ) {
		// Only act on singular pages.
		if ( ! is_singular() ) {
			return;
		}

		// Make sure this runs only for proper post types, optional safeguard.
		$post_type = get_post_type( $post_ID );

		if ( ! $post_type ) {
			return;
		}

		// Delete our meta tracking used tabs for this page.
		$meta_key = '_sp_tabs_used_generators';
		if ( get_post_meta( $post_ID, $meta_key, true ) ) {
			delete_post_meta( $post_ID, $meta_key );
		}
	}

	/**
	 * Enqueue the stylesheets and scripts for the live preview.
	 *
	 * @since    2.0.10
	 */
	public function admin_enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_Tabs_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_Tabs_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$current_screen        = get_current_screen();
		$the_current_post_type = $current_screen->post_type;
		if ( 'sp_wp_tabs' === $the_current_post_type ) {
			wp_enqueue_style( 'sptpro-accordion-style' );
			wp_enqueue_style( 'admin-sptpro-style' );
			wp_enqueue_style( 'sptpro-animate-css' );
		}
	}


	/**
	 * Converts Markdown content to HTML using Parsedown library.
	 *
	 * This function converts the given Markdown content to HTML by utilizing the Parsedown library.
	 * If the 'sp_tab_content_markdown_to_html' filter is disabled, the original HTML content is returned unchanged.
	 *
	 * @param string $html The Markdown content to be converted.
	 * @return string The converted HTML content.
	 */
	public static function sp_wp_tabs_markdown_to_html( $html ) {
		if ( ! apply_filters( 'sp_tab_content_markdown_to_html', false ) ) {
			return $html;
		}

		// Initialize Parsedown class.
		$markdown = new Parsedown();
		// Convert Markdown to HTML.
		$html = $markdown->text( $html );
		return $html;
	}
}
