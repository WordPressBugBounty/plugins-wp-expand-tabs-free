<?php
/**
 * The Map field of the plugin.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.0
 *
 * @package    WP_Tabs_Pro
 * @subpackage WP_Tabs_Pro/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; // Cannot access directly.
}

if ( ! class_exists( 'SP_WP_TABS_Field_map' ) ) {
	/**
	 *
	 * Field: map
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WP_TABS_Field_map extends SP_WP_TABS_Fields {

		/**
		 * Version Number.
		 *
		 * @var string version.
		 */
		public $version = '1.9.4';

		/**
		 * The CDN URL of leaflet library.
		 *
		 * @var string The CDN URL.
		 */
		public $cdn_url = 'https://cdn.jsdelivr.net/npm/leaflet@';

		/**
		 * The class constructor.
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
		 */
		public function render() {
			$args = wp_parse_args(
				$this->field,
				array(
					'placeholder'    => esc_html__( 'Search...', 'wp-expand-tabs-free' ),
					'latitude_text'  => esc_html__( 'Latitude', 'wp-expand-tabs-free' ),
					'longitude_text' => esc_html__( 'Longitude', 'wp-expand-tabs-free' ),
					'address_field'  => '',
					'height'         => '',
				)
			);

			$value = wp_parse_args(
				$this->value,
				array(
					'address'   => '',
					'latitude'  => '20',
					'longitude' => '0',
					'zoom'      => '2',
				)
			);

			$default_settings = array(
				'center'          => array( $value['latitude'], $value['longitude'] ),
				'zoom'            => $value['zoom'],
				'scrollWheelZoom' => false,
			);

			$settings = ( ! empty( $this->field['settings'] ) ) ? $this->field['settings'] : array();
			$settings = wp_parse_args( $settings, $default_settings );

			$style_attr  = ( ! empty( $args['height'] ) ) ? ' style="min-height:' . esc_attr( $args['height'] ) . ';"' : '';
			$placeholder = ( ! empty( $args['placeholder'] ) ) ? array( 'placeholder' => $args['placeholder'] ) : '';

			echo wp_kses_post( $this->field_before() );
			echo '<div class="wptabspro--map-container">';
			if ( empty( $args['address_field'] ) ) {
				echo '<h4 class="wptabspro-search-label">' . esc_html__( 'Address', 'wp-expand-tabs-free' ) . '</h4>';
				echo '<div class="wptabspro--map-search">';
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[address]' ) ) . '" value="' . esc_attr( $value['address'] ) . '"' . wp_kses_post( $this->field_attributes( $placeholder ) ) . ' />';
				echo '</div>';
			} else {
				echo '<div class="wptabspro--address-field" data-address-field="' . esc_attr( $args['address_field'] ) . '"></div>';
			}

			echo '<div class="wptabspro--map-osm-wrap"><div class="wptabspro--map-osm" data-map="' . esc_attr( json_encode( $settings ) ) . '"' . $style_attr . '></div></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</div>';
			echo '<div class="wptabspro--map-inputs">';
			// echo '<div class="wptabspro--map-input">';
			// echo '<label>' . esc_attr( $args['latitude_text'] ) . '</label>';
			// echo '<input type="text" name="' . esc_attr( $this->field_name( '[latitude]' ) ) . '" value="' . esc_attr( $value['latitude'] ) . '" class="wptabspro--latitude" />';
			// echo '</div>';
			// echo '<div class="wptabspro--map-input">';
			// echo '<label>' . esc_attr( $args['longitude_text'] ) . '</label>';
			// echo '<input type="text" name="' . esc_attr( $this->field_name( '[longitude]' ) ) . '" value="' . esc_attr( $value['longitude'] ) . '" class="wptabspro--longitude" />';
			// echo '</div>';
			echo '</div>';
			echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[zoom]' ) ) . '" value="' . esc_attr( $value['zoom'] ) . '" class="wptabspro--zoom" />';

			echo wp_kses_post( $this->field_after() );
		}

		/**
		 * Enqueue scripts and styles.
		 */
		public function enqueue() {
			if ( ! wp_script_is( 'sp_tabs-leaflet' ) ) {
				wp_enqueue_script( 'sp_tabs-leaflet', esc_url( $this->cdn_url . $this->version . '/dist/leaflet.js' ), array( 'jquery' ), $this->version, true );
			}

			if ( ! wp_style_is( 'sp_tabs-leaflet' ) ) {
				wp_enqueue_style( 'sp_tabs-leaflet', esc_url( $this->cdn_url . $this->version . '/dist/leaflet.css' ), array(), $this->version );
			}

			if ( ! wp_script_is( 'jquery-ui-autocomplete' ) ) {
				wp_enqueue_script( 'jquery-ui-autocomplete' );
			}
		}
	}
}
