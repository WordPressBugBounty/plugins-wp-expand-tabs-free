<?php
/**
 * The plugin gutenberg block Initializer.
 *
 * @link       https://shapedplugin.com/
 * @since      2.1.5
 *
 * @package    WP_Tabs
 * @subpackage WP_Tabs/admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\SmartTabsFree\Integration\GutenbergBlock;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use ShapedPlugin\SmartTabsFree\Modules\WPTabs\Frontend\Helper;
use ShapedPlugin\SmartTabsFree\Core\Constants;

if ( ! class_exists( 'ShapedPlugin\SmartTabsFree\Integration\GutenbergBlock\Gutenberg_Block_Init' ) ) {
	/**
	 * Gutenberg_Block_Init class.
	 */
	class Gutenberg_Block_Init {
		/**
		 * Custom Gutenberg Block Initializer.
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'gutenberg_shortcode_block' ) );
			add_action( 'enqueue_block_editor_assets', array( $this, 'block_editor_assets' ) );
			add_action( 'enqueue_block_assets', array( $this, 'block_canvas_assets' ) );
		}

		/**
		 * Register block editor script for backend.
		 */
		public function block_editor_assets() {
			$asset_file = plugin_dir_path( __FILE__ ) . 'build/index.asset.php';
			$asset      = file_exists( $asset_file ) ? require $asset_file : array(
				'dependencies' => array( 'wp-block-editor', 'wp-blocks', 'wp-components', 'wp-element', 'wp-escape-html', 'wp-i18n', 'wp-server-side-render' ),
				'version'      => Constants::VERSION,
			);

			wp_enqueue_script(
				'wp-tabs-free-shortcode-block',
				plugins_url( '/GutenbergBlock/build/index.js', __DIR__ ),
				array_merge( $asset['dependencies'], array( 'jquery' ) ),
				$asset['version'],
				true
			);

			wp_localize_script(
				'wp-tabs-free-shortcode-block',
				'sp_tab_free_gb_block',
				array(
					'url'           => Constants::url() . 'src/Integration/GutenbergBlock/',
					'loadPublic'    => Constants::wp_tabs_url() . 'Frontend/Assets/js/wp-tabs-public.min.js',
					'link'          => admin_url( 'post-new.php?post_type=sp_wp_tabs' ),
					'shortCodeList' => $this->post_list(),
				)
			);
		}

		/**
		 * Enqueue the tabs runtime assets inside the block editor canvas.
		 *
		 * Since WordPress 7.1 the post editor canvas is always an iframe and that
		 * iframe document is built only from the assets collected on
		 * `enqueue_block_assets`. Styles and scripts hooked to
		 * `enqueue_block_editor_assets` only reach the outer admin document, so the
		 * server side rendered tabs preview stayed unstyled and uninitialized.
		 */
		public function block_canvas_assets() {
			if ( ! is_admin() ) {
				return;
			}

			// Get the existing shortcode id from the current page.
			$get_page_data      = Helper::get_page_data();
			$found_shortcode_id = $get_page_data['generator_id'];
			// Load dynamic style for the existing shordcodes.
			$dynamic_style = Helper::load_dynamic_style( $found_shortcode_id );

			$accordion_mode = $dynamic_style['accordion'];
			if ( $accordion_mode ) {
				wp_enqueue_style( 'sptpro-accordion-style' );
			}
			wp_enqueue_style( 'sptpro-style' );
			wp_add_inline_style( 'sptpro-style', $dynamic_style['dynamic_css'] );

			wp_enqueue_script( 'sptpro-tab' );
			wp_enqueue_script( 'sptpro-collapse' );
			wp_enqueue_script( 'sptpro-script' );

			// The editor chrome styles live in the admin stylesheet, which only loads
			// on the outer document, so the canvas needs its own copy.
			wp_add_inline_style(
				'sptpro-style',
				'.sptabfree-gutenberg-shortcode{padding:0;line-height:24px;max-width:100%}div.sptabfree-gutenberg-shortcode:after{display:none}select.sptabfree-shortcode-selector{max-width:100%;padding: 6px 12px;}a.sp_wp_tabs_block_edit_button{display:inline-block;font-size:16px;margin:10px 0}'
			);
		}

		/**
		 * Shortcode list.
		 *
		 * @return array
		 */
		public function post_list() {
			$shortcodes = get_posts(
				array(
					'post_type'      => 'sp_wp_tabs',
					'post_status'    => 'publish',
					'posts_per_page' => 9999,
				)
			);

			if ( count( $shortcodes ) < 1 ) {
				return array();
			}

			return array_map(
				function ( $shortcode ) {
						return (object) array(
							'id'    => absint( $shortcode->ID ),
							'title' => esc_html( $shortcode->post_title ),
						);
				},
				$shortcodes
			);
		}

		/**
		 * Register Gutenberg shortcode block.
		 */
		public function gutenberg_shortcode_block() {
			/**
			 * Register Gutenberg block on server-side.
			 */
			register_block_type(
				'sp-wp-tabs-pro/shortcode',
				array(
					'api_version'     => 3,
					'attributes'      => array(
						'shortcode'          => array(
							'type'    => 'string',
							'default' => '',
						),
						'showInputShortcode' => array(
							'type'    => 'boolean',
							'default' => true,
						),
						'preview'            => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'is_admin'           => array(
							'type'    => 'boolean',
							'default' => is_admin(),
						),
					),
					'example'         => array(
						'attributes' => array(
							'preview' => true,
						),
					),
					'render_callback' => array( $this, 'render_shortcode' ),
				)
			);
		}

		/**
		 * Render callback.
		 *
		 * @param string $attributes Shortcode.
		 * @return string
		 */
		public function render_shortcode( $attributes ) {

			$class_name = '';
			if ( ! empty( $attributes['className'] ) ) {
				$class_name = 'class="' . esc_attr( $attributes['className'] ) . '"';
			}
			if ( empty( $attributes['is_admin'] ) ) {
				return '<div ' . $class_name . '>' . do_shortcode( '[wptabs id="' . sanitize_text_field( $attributes['shortcode'] ) . '"]' ) . '</div>';
			}
			$edit_page_link = get_edit_post_link( sanitize_text_field( $attributes['shortcode'] ) );
			return '<div id="' . uniqid() . '" ' . $class_name . ' ><a href="' . esc_url( $edit_page_link ) . '" target="_blank" class="sp_wp_tabs_block_edit_button">Edit View</a>' . do_shortcode( '[wptabs id="' . sanitize_text_field( $attributes['shortcode'] ) . '"]' ) . '</div>';
		}
	}
}
