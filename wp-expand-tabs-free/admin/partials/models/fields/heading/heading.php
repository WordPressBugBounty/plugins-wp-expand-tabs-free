<?php
/**
 * Framework heading field file.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package wp-expand-tabs-free
 * @subpackage wp-expand-tabs-free/Framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WP_TABS_Field_heading' ) ) {
	/**
	 *
	 * Field: heading
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WP_TABS_Field_heading extends SP_WP_TABS_Fields {

		/**
		 *  Heading field constructor.
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
			$version = ! empty( $this->field['version'] ) ? $this->field['version'] : '';
			echo ( ! empty( $this->field['content'] ) ) ? wp_kses_post( $this->field['content'] ) : '';
			// echo ( ! empty( $this->field['image'] ) ) ? '<img src="' . esc_url( $this->field['image'] ) . '">' : '';
			echo ( ! empty( $this->field['image'] ) ) ? '<div class="heading-wrapper"><img src="' . esc_url( $this->field['image'] ) . '"><span class="sp-product-tabs-version">' . esc_html( $version ) . '</span></div>' : '';

			echo ( ! empty( $this->field['after'] ) && ! empty( $this->field['link'] ) ) ? '<span class="wptabspro-support-area"><span class="support">' . $this->field['after'] . '</span><div class="wptabspro-help-text wptabspro-support"><div class="wptabspro-info-label">Documentation</div>Check out our documentation and more information about what you can do with the Smart Tabs.<a class="wptabspro-open-docs browser-docs" href="https://wptabs.com/docs/" target="_blank">Browse Docs</a><div class="wptabspro-info-label">Need Help or Missing a Feature?</div>Feel free to get help from our friendly support team or request a new feature if needed. We appreciate your suggestions to make the plugin better.<a class="wptabspro-open-docs support" href="https://shapedplugin.com/create-new-ticket/" target="_blank">Get Help</a><a class="wptabspro-open-docs feature-request" href="https://shapedplugin.com/contact-us/" target="_blank">Request a Feature</a></div></span>' : '';//phpcs:ignore
		}
	}
}
