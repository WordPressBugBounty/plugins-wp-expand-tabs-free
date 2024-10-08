<?php
/**
 * Framework image_select field file.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package wp-expand-tabs-free
 * @subpackage wp-expand-tabs-free/Framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WP_TABS_Field_image_select' ) ) {
	/**
	 *
	 * Field: image_select
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WP_TABS_Field_image_select extends SP_WP_TABS_Fields {

		/**
		 *  Image select field constructor.
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

			$args = wp_parse_args(
				$this->field,
				array(
					'multiple' => false,
					'options'  => array(),
				)
			);

			$value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $this->field_before();

			if ( ! empty( $args['options'] ) ) {

				echo '<div class="wptabspro-siblings wptabspro--image-group" data-multiple="' . esc_attr( $args['multiple'] ) . '">';

				$num = 1;

				foreach ( $args['options'] as $key => $option ) {
					$type            = ( $args['multiple'] ) ? 'checkbox' : 'radio';
					$extra           = ( $args['multiple'] ) ? '[]' : '';
					$active          = ( in_array( $key, $value, true ) ) ? ' wptabspro--active' : '';
					$checked         = ( in_array( $key, $value, true ) ) ? ' checked' : '';
					$is_pro_features = isset( $option['class'] ) ? $option['class'] : '';

					echo '<div class="wptabspro--sibling wptabspro--image' . esc_attr( $active ) . ' ' . esc_attr( $is_pro_features ) . '">';
					if ( ! empty( $option['image'] ) ) {
						echo '<img src="' . esc_url( $option['image'] ) . '" alt="img-' . esc_attr( $num++ ) . '" />';
					} else {
						echo '<img src="' . esc_url( $option ) . '" alt="img-' . esc_attr( $num++ ) . '" />';
					}
					echo '<input type="' . esc_attr( $type ) . '" name="' . esc_attr( $this->field_name( $extra ) ) . '" value="' . esc_attr( $key ) . '"' . $this->field_attributes() . esc_attr( $checked ) . '/>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					if ( ! empty( $option['option_name'] ) && ! empty( $option['option_demo_url'] ) ) {
						echo '<p class="wptabs-image-name"><b>' . esc_html( $option['option_name'] ) . '</b> <a href="' . esc_url( $option['option_demo_url'] ) . '" tooltip="Demo" class="wptabs-demo-icon" target="_blank"><span class="fa fa-external-link"></span></a></p>';
					} elseif ( ! empty( $option['option_name'] ) ) {
						echo '<p class="wptabs-image-name"><b>' . esc_html( $option['option_name'] ) . '</b></p>';
					}

					echo '</div>';

				}

				echo '</div>';

			}

			echo '<div class="clear"></div>';

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $this->field_after();
		}
	}
}
