<?php
/**
 * Framework shortcode field file.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package wp-expand-tabs-free
 * @subpackage wp-expand-tabs-free/Framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WP_TABS_Field_shortcode' ) ) {
	/**
	 *
	 * Field: shortcode
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WP_TABS_Field_shortcode extends SP_WP_TABS_Fields {

		/**
		 * Shortcode field constructor.
		 *
		 * @param array  $field The field type.
		 * @param string $value The values of the field.
		 * @param string $unique The unique ID for the field.
		 * @param string $where To where show the output CSS.
		 * @param string $parent The parent args.
		 */
		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		/**
		 * Render field
		 *
		 * @return void
		 */
		public function render() {
			// Get the Post ID.
			$type    = ( ! empty( $this->field['attributes']['type'] ) ) ? $this->field['attributes']['type'] : 'text';
			$post_id = get_the_ID();
			if ( ! empty( $this->field['shortcode'] ) && 'manage_view' === $this->field['shortcode'] ) {
				echo ( ! empty( $post_id ) ) ? '<div class="sp-tab__scode-scode-wrap"><p>To display the Tabs, copy and paste this shortcode into your post, page, custom post, or block editor. <a href="https://docs.shapedplugin.com/docs/wp-tabs-pro/configurations/how-to-show-the-tabs-on-your-homepage-or-header-php-or-other-php-files/" target="_blank">Learn how</a> to include it in your template file.</p><span class="sp-tab__shortcode-selectable">[wptabs id="' . esc_attr( $post_id ) . '"]</span></div><div class="sp_tab-after-copy-text"><i class="fa fa-check-circle"></i> Shortcode Copied to Clipboard! </div>' : '';
			} elseif ( ! empty( $this->field['shortcode'] ) && 'pro_notice' === $this->field['shortcode'] ) {
				if ( ! empty( $post_id ) ) {
					echo '<div class="sp-tab-shortcode-area sp-tab-notice-wrapper">';
					echo '<div class="sp-tab-notice-heading">' . sprintf(
						/* translators: 1: start span tag, 2: close tag. */
						esc_html__( 'Unlock Full Potential with %1$sPRO%2$s', 'wp-expand-tabs-free' ),
						'<span>',
						'</span>'
					) . '</div>';

					echo '<p class="sp-tab-notice-desc">' . sprintf(
						/* translators: 1: start bold tag, 2: close tag. */
						esc_html__( 'Boost Engagement and Sales with Premium Tabs by Pro.', 'wp-expand-tabs-free' ),
						'<b>',
						'</b>'
					) . '</p>';

					echo '<ul>';
					echo '<li><i class="sp-tab-icon-check-icon"></i> ' . esc_html__( '23+ Beautiful Tab Layouts', 'wp-expand-tabs-free' ) . '</li>';
					echo '<li><i class="sp-tab-icon-check-icon"></i> ' . esc_html__( 'WooCommerce Product Tabs', 'wp-expand-tabs-free' ) . '</li>';
					echo '<li><i class="sp-tab-icon-check-icon"></i> ' . esc_html__( 'Global & Individual Tab Editing', 'wp-expand-tabs-free' ) . '</li>';
					echo '<li><i class="sp-tab-icon-check-icon"></i> ' . esc_html__( 'Image, Video, Shortcode Tabs', 'wp-expand-tabs-free' ) . '</li>';
					echo '<li><i class="sp-tab-icon-check-icon"></i> ' . esc_html__( 'Post, Map, Download, Form Tabs', 'wp-expand-tabs-free' ) . '</li>';
					echo '<li><i class="sp-tab-icon-check-icon"></i> ' . esc_html__( ' Vertical & Multi-Level Tabs', 'wp-expand-tabs-free' ) . '</li>';
					echo '<li><i class="sp-tab-icon-check-icon"></i> ' . esc_html__( 'Scrollable Tabs', 'wp-expand-tabs-free' ) . '</li>';
					echo '<li><i class="sp-tab-icon-check-icon"></i> ' . esc_html__( '200+ Customizations and More', 'wp-expand-tabs-free' ) . '</li>';
					echo '</ul>';

					echo '<div class="sp-tab-notice-button">';
					echo '<a class="sp-tab-open-live-demo" href="https://wptabs.com/pricing/?ref=1" target="_blank">';
					echo esc_html__( 'Upgrade to Pro Now', 'wp-expand-tabs-free' ) . ' <i class="sp-tab-icon-shuttle_2285485-1"></i>';
					echo '</a>';
					echo '</div>';
					echo '</div>';
				}
			} else {
				echo ( ! empty( $post_id ) ) ? '<div class="sp-tab__scode-scode-wrap"><p>WP Tabs integrates seamlessly with Gutenberg, Classic Editor, <strong>Elementor,</strong> Divi, Bricks, Beaver, Oxygen, WPBakery Builder, and more.</p></div>' : '';
			}
		}
	}
}
