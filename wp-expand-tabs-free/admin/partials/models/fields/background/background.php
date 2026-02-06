<?php
/**
 * The background field of the plugin.
 *
 * @link https://shapedplugin.com
 * @since 2.0.11
 *
 * @package wp-expand-tabs-free
 * @subpackage wp-expand-tabs-free/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SP_WP_TABS_Field_background' ) ) {
	/**
	 *
	 * Field: background
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WP_TABS_Field_background extends SP_WP_TABS_Fields {

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

			$args = wp_parse_args(
				$this->field,
				array(
					'background_color'              => true,
					'background_image'              => true,
					'background_position'           => true,
					'background_repeat'             => true,
					'background_attachment'         => true,
					'background_size'               => true,
					'background_origin'             => false,
					'background_clip'               => false,
					'background_blend_mode'         => false,
					'background_gradient'           => false,
					'background_gradient_color'     => true,
					'background_gradient_direction' => true,
					'background_image_preview'      => true,
					'background_auto_attributes'    => false,
					'background_image_library'      => 'image',
					'background_image_placeholder'  => esc_html__( 'No background selected', 'wp-expand-tabs-free' ),
				)
			);

			$default_value = array(
				'background-color'              => '',
				'background-image'              => '',
				'background-position'           => '',
				'background-repeat'             => '',
				'background-attachment'         => '',
				'background-size'               => '',
				'background-origin'             => '',
				'background-clip'               => '',
				'background-blend-mode'         => '',
				'background-gradient-color'     => '',
				'background-gradient-direction' => '',
			);

			$default_value = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;

			$this->value = wp_parse_args( $this->value, $default_value );

			echo wp_kses_post( $this->field_before() );

			echo '<div class="wptabspro--background-colors">';

			//
			// Background Color.
			if ( ! empty( $args['background_color'] ) ) {

				echo '<div class="wptabspro--color">';

				echo ( ! empty( $args['background_gradient'] ) ) ? '<div class="wptabspro--title">' . esc_html__( 'From', 'wp-expand-tabs-free' ) . '</div>' : '';

				SP_WP_TABS::field(
					array(
						'id'      => 'background-color',
						'type'    => 'color',
						'default' => $default_value['background-color'],
					),
					$this->value['background-color'],
					$this->field_name(),
					'field/background'
				);

				echo '</div>';

			}

			//
			// Background Gradient Color.
			if ( ! empty( $args['background_gradient_color'] ) && ! empty( $args['background_gradient'] ) ) {

				echo '<div class="wptabspro--color">';

				echo ( ! empty( $args['background_gradient'] ) ) ? '<div class="wptabspro--title">' . esc_html__( 'To', 'wp-expand-tabs-free' ) . '</div>' : '';

				SP_WP_TABS::field(
					array(
						'id'      => 'background-gradient-color',
						'type'    => 'color',
						'default' => $default_value['background-gradient-color'],
					),
					$this->value['background-gradient-color'],
					$this->field_name(),
					'field/background'
				);

				echo '</div>';

			}

			//
			// Background Gradient Direction.
			if ( ! empty( $args['background_gradient_direction'] ) && ! empty( $args['background_gradient'] ) ) {

				echo '<div class="wptabspro--color">';

				echo ( ! empty( $args['background_gradient'] ) ) ? '<div class="wptabspro--title">' . esc_html__( 'Direction', 'wp-expand-tabs-free' ) . '</div>' : '';

				SP_WP_TABS::field(
					array(
						'id'      => 'background-gradient-direction',
						'type'    => 'select',
						'options' => array(
							// ''          => esc_html__( 'Select Gradient Direction', 'wp-expand-tabs-free' ),
							'to bottom' => esc_html__( '&#8659; top to bottom', 'wp-expand-tabs-free' ),
							'to right'  => esc_html__( '&#8658; left to right', 'wp-expand-tabs-free' ),
							'135deg'    => esc_html__( '&#8664; corner top to right', 'wp-expand-tabs-free' ),
							'-135deg'   => esc_html__( '&#8665; corner top to left', 'wp-expand-tabs-free' ),
						),
					),
					$this->value['background-gradient-direction'],
					$this->field_name(),
					'field/background'
				);

				echo '</div>';

			}

			echo '</div>';

			//
			// Background Image.
			if ( ! empty( $args['background_image'] ) ) {
				echo '<div class="wptabspro--background-image">';

				SP_WP_TABS::field(
					array(
						'id'          => 'background-image',
						'type'        => 'media',
						'class'       => 'wptabspro-assign-field-background',
						'library'     => $args['background_image_library'],
						'preview'     => $args['background_image_preview'],
						'placeholder' => $args['background_image_placeholder'],
						'attributes'  => array( 'data-depend-id' => $this->field['id'] ),
					),
					$this->value['background-image'],
					$this->field_name(),
					'field/background'
				);

				echo '</div>';
			}

			$auto_class   = ( ! empty( $args['background_auto_attributes'] ) ) ? ' wptabspro--auto-attributes' : '';
			$hidden_class = ( ! empty( $args['background_auto_attributes'] ) && empty( $this->value['background-image']['url'] ) ) ? ' wptabspro--attributes-hidden' : '';

			echo '<div class="wptabspro--background-attributes' . esc_attr( $auto_class . $hidden_class ) . '">';

			//
			// Background Position.
			if ( ! empty( $args['background_position'] ) ) {

				SP_WP_TABS::field(
					array(
						'id'      => 'background-position',
						'type'    => 'select',
						'options' => array(
							''              => esc_html__( 'Background Position', 'wp-expand-tabs-free' ),
							'left top'      => esc_html__( 'Left Top', 'wp-expand-tabs-free' ),
							'left center'   => esc_html__( 'Left Center', 'wp-expand-tabs-free' ),
							'left bottom'   => esc_html__( 'Left Bottom', 'wp-expand-tabs-free' ),
							'center top'    => esc_html__( 'Center Top', 'wp-expand-tabs-free' ),
							'center center' => esc_html__( 'Center Center', 'wp-expand-tabs-free' ),
							'center bottom' => esc_html__( 'Center Bottom', 'wp-expand-tabs-free' ),
							'right top'     => esc_html__( 'Right Top', 'wp-expand-tabs-free' ),
							'right center'  => esc_html__( 'Right Center', 'wp-expand-tabs-free' ),
							'right bottom'  => esc_html__( 'Right Bottom', 'wp-expand-tabs-free' ),
						),
					),
					$this->value['background-position'],
					$this->field_name(),
					'field/background'
				);

			}

			//
			// Background Repeat.
			if ( ! empty( $args['background_repeat'] ) ) {

				SP_WP_TABS::field(
					array(
						'id'      => 'background-repeat',
						'type'    => 'select',
						'options' => array(
							''          => esc_html__( 'Background Repeat', 'wp-expand-tabs-free' ),
							'repeat'    => esc_html__( 'Repeat', 'wp-expand-tabs-free' ),
							'no-repeat' => esc_html__( 'No Repeat', 'wp-expand-tabs-free' ),
							'repeat-x'  => esc_html__( 'Repeat Horizontally', 'wp-expand-tabs-free' ),
							'repeat-y'  => esc_html__( 'Repeat Vertically', 'wp-expand-tabs-free' ),
						),
					),
					$this->value['background-repeat'],
					$this->field_name(),
					'field/background'
				);

			}

			//
			// Background Attachment.
			if ( ! empty( $args['background_attachment'] ) ) {
				SP_WP_TABS::field(
					array(
						'id'      => 'background-attachment',
						'type'    => 'select',
						'options' => array(
							''       => esc_html__( 'Background Attachment', 'wp-expand-tabs-free' ),
							'scroll' => esc_html__( 'Scroll', 'wp-expand-tabs-free' ),
							'fixed'  => esc_html__( 'Fixed', 'wp-expand-tabs-free' ),
						),
					),
					$this->value['background-attachment'],
					$this->field_name(),
					'field/background'
				);
			}

			//
			// Background Size.
			if ( ! empty( $args['background_size'] ) ) {

				SP_WP_TABS::field(
					array(
						'id'      => 'background-size',
						'type'    => 'select',
						'options' => array(
							''        => esc_html__( 'Background Size', 'wp-expand-tabs-free' ),
							'cover'   => esc_html__( 'Cover', 'wp-expand-tabs-free' ),
							'contain' => esc_html__( 'Contain', 'wp-expand-tabs-free' ),
						),
					),
					$this->value['background-size'],
					$this->field_name(),
					'field/background'
				);
			}

			//
			// Background Origin.
			if ( ! empty( $args['background_origin'] ) ) {

				SP_WP_TABS::field(
					array(
						'id'      => 'background-origin',
						'type'    => 'select',
						'options' => array(
							''            => esc_html__( 'Background Origin', 'wp-expand-tabs-free' ),
							'padding-box' => esc_html__( 'Padding Box', 'wp-expand-tabs-free' ),
							'border-box'  => esc_html__( 'Border Box', 'wp-expand-tabs-free' ),
							'content-box' => esc_html__( 'Content Box', 'wp-expand-tabs-free' ),
						),
					),
					$this->value['background-origin'],
					$this->field_name(),
					'field/background'
				);

			}

			//
			// Background Clip.
			if ( ! empty( $args['background_clip'] ) ) {

				SP_WP_TABS::field(
					array(
						'id'      => 'background-clip',
						'type'    => 'select',
						'options' => array(
							''            => esc_html__( 'Background Clip', 'wp-expand-tabs-free' ),
							'border-box'  => esc_html__( 'Border Box', 'wp-expand-tabs-free' ),
							'padding-box' => esc_html__( 'Padding Box', 'wp-expand-tabs-free' ),
							'content-box' => esc_html__( 'Content Box', 'wp-expand-tabs-free' ),
						),
					),
					$this->value['background-clip'],
					$this->field_name(),
					'field/background'
				);

			}

			//
			// Background Blend Mode.
			if ( ! empty( $args['background_blend_mode'] ) ) {

				SP_WP_TABS::field(
					array(
						'id'      => 'background-blend-mode',
						'type'    => 'select',
						'options' => array(
							''            => esc_html__( 'Background Blend Mode', 'wp-expand-tabs-free' ),
							'normal'      => esc_html__( 'Normal', 'wp-expand-tabs-free' ),
							'multiply'    => esc_html__( 'Multiply', 'wp-expand-tabs-free' ),
							'screen'      => esc_html__( 'Screen', 'wp-expand-tabs-free' ),
							'overlay'     => esc_html__( 'Overlay', 'wp-expand-tabs-free' ),
							'darken'      => esc_html__( 'Darken', 'wp-expand-tabs-free' ),
							'lighten'     => esc_html__( 'Lighten', 'wp-expand-tabs-free' ),
							'color-dodge' => esc_html__( 'Color Dodge', 'wp-expand-tabs-free' ),
							'saturation'  => esc_html__( 'Saturation', 'wp-expand-tabs-free' ),
							'color'       => esc_html__( 'Color', 'wp-expand-tabs-free' ),
							'luminosity'  => esc_html__( 'Luminosity', 'wp-expand-tabs-free' ),
						),
					),
					$this->value['background-blend-mode'],
					$this->field_name(),
					'field/background'
				);

			}

			echo '</div>';
			echo wp_kses_post( $this->field_after() );
		}
	}
}
