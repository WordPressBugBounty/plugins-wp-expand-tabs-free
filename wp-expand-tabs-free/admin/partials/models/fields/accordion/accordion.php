<?php
/**
 * The accordion field of the plugin.
 *
 * @link       https://shapedplugin.com/
 * @since      3.0.2
 *
 * @package wp-expand-tabs-free
 * @subpackage wp-expand-tabs-free/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SP_WP_TABS_Field_accordion' ) ) {
	/**
	 *
	 * Field: accordion
	 *
	 * @since 3.0.2
	 * @version 3.0.2
	 */
	class SP_WP_TABS_Field_accordion extends SP_WP_TABS_Fields {
		/**
		 * Field constructor.
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
		 * Render
		 *
		 * @return void
		 */
		public function render() {
			$unallows = array( 'accordion' );

			echo wp_kses_post( $this->field_before() );
			echo '<div class="wptabspro-accordion-items" data-depend-id="' . esc_attr( $this->field['id'] ) . '">';

			foreach ( $this->field['accordions'] as $key => $accordion ) {

				echo '<div class="wptabspro-accordion-item">';
				$icon = ( ! empty( $accordion['icon'] ) ) ? 'wptabspro--icon ' . $accordion['icon'] : 'wptabspro-accordion-icon fa fa-angle-right';

				echo '<h4 class="wptabspro-accordion-title">';
				echo esc_html( $accordion['title'] );
				echo '<i class="' . esc_attr( $icon ) . '"></i>';
				echo '</h4>';

				echo '<div class="wptabspro-accordion-content">';
				foreach ( $accordion['fields'] as $field ) {

					if ( in_array( $field['type'], $unallows ) ) {
						$field['_notice'] = true; }

					$field_id      = ( isset( $field['id'] ) ) ? $field['id'] : '';
					$field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
					$field_value   = ( isset( $this->value[ $field_id ] ) ) ? $this->value[ $field_id ] : $field_default;
					$unique_id     = ( ! empty( $this->unique ) ) ? $this->unique . '[' . $this->field['id'] . ']' : $this->field['id'];

					SP_WP_TABS::field( $field, $field_value, $unique_id, 'field/accordion' );
				}
				echo '</div>';
				echo '</div>';
			}

			echo '</div>';
			echo wp_kses_post( $this->field_after() );
		}
	}
}
