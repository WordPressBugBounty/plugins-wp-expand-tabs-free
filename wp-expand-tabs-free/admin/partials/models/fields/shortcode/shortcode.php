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
				echo ( ! empty( $post_id ) ) ? '<div class="sp-tab__scode-scode-wrap"><p>To display the Tabs, copy and paste this shortcode into your post, page, custom post, or block editor. <a href="https://wptabs.com/docs/how-to-insert-the-php-do-shortcode-template-include/" target="_blank">Learn how</a> to include it in your template file.</p><span class="sp-tab__shortcode-selectable">[wptabs id="' . esc_attr( $post_id ) . '"]</span></div><div class="sp_tab-after-copy-text"><i class="fa fa-check-circle"></i> Shortcode Copied to Clipboard! </div>' : '';
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
						esc_html__( 'Boost Engagement & Sales with Pro WordPress + WooCommerce Tabs.', 'wp-expand-tabs-free' ),
						'<b>',
						'</b>'
					) . '</p>';

					echo '<ul>';

					echo '<div class="sp-tab-list-heading">WordPress Tabs:</div>';
					echo '<li><i class="sp-tab-icon-check-icon"></i> ' . esc_html__( '20+ Tab Layouts & Positions', 'wp-expand-tabs-free' ) . '</li>';
					echo '<li><i class="sp-tab-icon-check-icon"></i> ' . esc_html__( 'Image Gallery Tabs (Lightbox)', 'wp-expand-tabs-free' ) . '</li>';
					echo '<li><i class="sp-tab-icon-check-icon"></i> ' . esc_html__( 'Video Gallery Tabs (Lightbox)', 'wp-expand-tabs-free' ) . '</li>';
					echo '<li><i class="sp-tab-icon-check-icon"></i> ' . esc_html__( 'Tabs from Post Type & Taxonomy', 'wp-expand-tabs-free' ) . '</li>';
					echo '<li><i class="sp-tab-icon-check-icon"></i> ' . esc_html__( 'Vertical & Multi-Level Tabs', 'wp-expand-tabs-free' ) . '</li>';
					echo '<li><i class="sp-tab-icon-check-icon"></i> ' . esc_html__( 'Scrollable Tabs, and more', 'wp-expand-tabs-free' ) . '</li>';

					echo '<div class="sp-tab-list-heading">WooCommerce Tabs:</div>';
					printf(
						'<li class="sp-tab-layouts-list"><i class="sp-tab-icon-check-icon"></i> %s</li>',
						sprintf(
						/* translators: 1: Horizontal demo link, 2: Vertical demo link, 3: Accordion demo link */
							esc_html__( '6+ Product Tab Layouts (%1$s, %2$s, %3$s)', 'wp-expand-tabs-free' ),
							'<a class="sp-tab" href="https://demo.wptabs.com/product/nike-sportswear-jdi/" target="_blank">' . esc_html__( 'Horizontal', 'wp-expand-tabs-free' ) . '</a>',
							'<a href="https://demo.wptabs.com/vertical-left-product-tabs/product/meta-quest-3-vr-headset/" target="_blank">' . esc_html__( 'Vertical', 'wp-expand-tabs-free' ) . '</a>',
							'<a href="https://demo.wptabs.com/accordion-product-tabs/product/hot-air-balloon-flight/" target="_blank">' . esc_html__( 'Accordion', 'wp-expand-tabs-free' ) . '</a>'
						)
					);
					echo '<li><i class="sp-tab-icon-check-icon"></i> ' . esc_html__( 'Override/Edit Product Tabs', 'wp-expand-tabs-free' ) . '</li>';
					echo '<li><i class="sp-tab-icon-check-icon"></i> ' . esc_html__( 'Global Tabs & Show Tabs by Product, Category, Tag, Brand...', 'wp-expand-tabs-free' ) . '</li>';
					echo '<li><i class="sp-tab-icon-check-icon"></i> ' . esc_html__( 'Product Image Gallery, Video, FAQs, Download, Map, Contact Tabs, etc.', 'wp-expand-tabs-free' ) . '</li>';
					echo '<li><i class="sp-tab-icon-check-icon"></i> ' . esc_html__( 'Tab Icons (Library/Upload Custom)', 'wp-expand-tabs-free' ) . '</li>';
					echo '<li><i class="sp-tab-icon-check-icon"></i> ' . esc_html__( 'Drag & Drop Tabs Reorder', 'wp-expand-tabs-free' ) . '</li>';
					echo '<li><i class="sp-tab-icon-check-icon"></i> ' . esc_html__( 'Rename Default Tabs, and more.', 'wp-expand-tabs-free' ) . '</li>';

					echo '</ul>';

					echo '<div class="sp-tab-notice-button">';
					echo '<a class="sp-tab-open-live-demo" href="' . esc_url( SP_SMART_TABS_PRO_LINK ) . '" target="_blank">';
					echo esc_html__( 'Upgrade to Pro Now', 'wp-expand-tabs-free' ) . ' <i class="sp-tab-icon-shuttle_2285485-1"></i>';
					echo '</a>';
					echo '</div>';
					echo '</div>';
				}
			} else {
				echo ( ! empty( $post_id ) ) ? '<div class="sp-tab__scode-scode-wrap"><p>Smart Tabs integrates seamlessly with Gutenberg, Classic Editor, <strong>Elementor,</strong> Divi, Bricks, Beaver, Oxygen, WPBakery Builder, etc.</p></div>' : '';
			}
		}
	}
}
