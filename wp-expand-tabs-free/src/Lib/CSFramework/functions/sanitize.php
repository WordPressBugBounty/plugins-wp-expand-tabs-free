<?php
/**
 * The admin sanitize functions.
 *
 * @since        2.0.1
 *
 * @package    WP_Tabs
 * @subpackage WP_Tabs/admin/partials
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! function_exists( 'wptabspro_sanitize_replace_a_to_b' ) ) {
	/**
	 * Sanitize
	 * Replace letter a to letter b
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @param string $value the value.
	 * @return mixed
	 */
	function wptabspro_sanitize_replace_a_to_b( $value ) {

		return str_replace( 'a', 'b', $value );
	}
}

if ( ! function_exists( 'wptabspro_sanitize_title' ) ) {
	/**
	 * Sanitize title
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @param string $value The title.
	 * @return string
	 */
	function wptabspro_sanitize_title( $value ) {

		return sanitize_title( $value );
	}
}

if ( ! function_exists( 'wptabspro_sanitize_number_array_field' ) ) {
	/**
	 *
	 * Sanitize number array
	 *
	 * @param  mixed $array value.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function wptabspro_sanitize_number_array_field( $array ) {
		if ( empty( $array ) || ! is_array( $array ) ) {
			return array();
		}

		$new_array = array();
		foreach ( $array as $key => $value ) {
			$sanitize_key = sanitize_key( $key );
			if ( 'unit' === $key || 'units' === $key ) {
				$new_array[ $sanitize_key ] = wp_filter_nohtml_kses( $value );
			} else {
				$new_array[ $sanitize_key ] = intval( $value );
			}
		}
		return $new_array;
	}
}

if ( ! function_exists( 'wptabspro_sanitize_number_field' ) ) {
	/**
	 *
	 * Sanitize number
	 *
	 * @param  mixed $value value.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function wptabspro_sanitize_number_field( $value ) {
		if ( empty( $value ) ) {
			return 0;
		} else {
			return intval( $value );
		}
	}
}

if ( ! function_exists( 'wptabspro_sanitize_border_field' ) ) {
	/**
	 *
	 * Sanitize border field
	 *
	 * @param  mixed $array value.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function wptabspro_sanitize_border_field( $array ) {
		if ( empty( $array ) || ! is_array( $array ) ) {
			return array();
		}

		$new_array = array();
		foreach ( $array as $key => $value ) {
			$sanitize_key = sanitize_key( $key );
			if ( 'style' === $key || strpos( $key, 'color' ) !== false ) {
				$new_array[ $sanitize_key ] = sanitize_text_field( $value );
			} else {
				$new_array[ $sanitize_key ] = intval( $value );
			}
		}
		return $new_array;
	}
}

if ( ! function_exists( 'wptabspro_sanitize_color_group_field' ) ) {
	/**
	 *
	 * Sanitize color group field
	 *
	 * @param  mixed $array value.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function wptabspro_sanitize_color_group_field( $array ) {
		if ( empty( $array ) || ! is_array( $array ) ) {
			return array();
		}

		$new_array = array();
		foreach ( $array as $key => $value ) {
			$sanitize_key               = sanitize_key( $key );
			$new_array[ $sanitize_key ] = sanitize_text_field( $value );
		}
		return $new_array;
	}
}

if ( ! function_exists( 'wptabspro_allowed_title_tags' ) ) {
	/**
	 *
	 * Sanitize allowed html tags.
	 */
	function wptabspro_allowed_title_tags() {

		$allowed_tags = array(
			'b'      => array(),
			'strong' => array(),
			'i'      => array(),
			'u'      => array(),
		);

		return apply_filters( 'sp_wp_tabs_title_allowed_tags', $allowed_tags );
	}
}

if ( ! function_exists( 'wptabspro_allowed_description_tags' ) ) {
	/**
	 *
	 * Sanitize allowed html tags.
	 */
	function wptabspro_allowed_description_tags() {

		$allowed_tags           = wp_kses_allowed_html( 'post' );
		$allowed_tags['iframe'] = array(
			'src'             => array(),
			'height'          => array(),
			'width'           => array(),
			'frameborder'     => array(),
			'allowfullscreen' => array(),
			'title'           => array(),
			'alt'             => array(),
			'class'           => array(),
			'id'              => array(),
		);
		$allowed_tags['style']  = array(
			'type'  => array(),
			'media' => array(),
		);

		// Add attributes for the 'audio' tag.
		$allowed_tags['audio'] = array(
			'controls' => true,
			'src'      => array(),
			'autoplay' => array(),
			'loop'     => array(),
			'preload'  => array(),
			'muted'    => array(),
		);

		// Add attributes for the 'source' tag.
		$allowed_tags['source'] = array(
			'src'    => array(),
			'type'   => array(),
			'media'  => array(),
			'sizes'  => array(),
			'srcset' => array(),
		);
		// Add attributes for the 'video' tag.
		$allowed_tags['video'] = array(
			'src'      => array(),
			'autoplay' => array(),
			'controls' => array(),
			'width'    => array(),
			'height'   => array(),
			'loop'     => array(),
			'preload'  => array(),
			'poster'   => array(),
			'muted'    => array(),
		);

		return apply_filters( 'sp_wp_tabs_desc_allowed_tags', $allowed_tags );
	}
}

if ( ! function_exists( 'wptabspro_sanitize_css_color' ) ) {
	/**
	 * Sanitize a CSS color value.
	 *
	 * Allows hex colors (#rgb, #rgba, #rrggbb, #rrggbbaa), rgb()/rgba()/hsl()/hsla()
	 * functional notations and a few safe keywords. Everything else is discarded
	 * so no CSS declaration or selector injection is possible through color fields.
	 *
	 * @param  mixed $color The color value.
	 * @return string
	 */
	function wptabspro_sanitize_css_color( $color ) {
		$color = trim( (string) $color );

		if ( '' === $color ) {
			return '';
		}

		// Hex notation.
		if ( preg_match( '/^#([0-9a-f]{3}|[0-9a-f]{4}|[0-9a-f]{6}|[0-9a-f]{8})$/i', $color ) ) {
			return $color;
		}

		// Functional notation, e.g. rgba( 255, 255, 255, 0.5 ), hsl( 0, 100%, 50% ) or rgb( 0 0 0 / 50% ).
		if ( preg_match( '/^(rgba?|hsla?)\(\s*[-\d.,%\s\/]{1,64}\)$/i', $color ) ) {
			return $color;
		}

		// Safe keywords.
		if ( in_array( strtolower( $color ), array( 'transparent', 'inherit', 'initial', 'currentcolor', 'none' ), true ) ) {
			return $color;
		}

		return '';
	}
}

if ( ! function_exists( 'wptabspro_sanitize_css_number' ) ) {
	/**
	 * Sanitize a numeric CSS value (unitless).
	 *
	 * Keeps an optional minus sign and decimal point only, e.g. "-10.5".
	 *
	 * @param  mixed $value The numeric value.
	 * @return string
	 */
	function wptabspro_sanitize_css_number( $value ) {
		$value = trim( (string) $value );

		if ( '' === $value ) {
			return '';
		}

		$number = filter_var( $value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );

		// Discard values that carried anything else besides the number itself.
		return ( (string) $number === $value ) ? $value : '';
	}
}

if ( ! function_exists( 'wptabspro_sanitize_font_family' ) ) {
	/**
	 * Sanitize a CSS font-family value.
	 *
	 * @param  mixed $value The font family.
	 * @return string
	 */
	function wptabspro_sanitize_font_family( $value ) {
		return preg_replace( '/[^a-zA-Z0-9 _,\-\']/', '', (string) $value );
	}
}

if ( ! function_exists( 'wptabspro_sanitize_css_bound_array' ) ) {
	/**
	 * Recursively sanitize an array of CSS-bound values by key name.
	 *
	 * @param  array $value The value array.
	 * @return array
	 */
	function wptabspro_sanitize_css_bound_array( $value ) {
		foreach ( $value as $key => $item ) {
			if ( is_array( $item ) ) {
				$value[ $key ] = wptabspro_sanitize_css_bound_array( $item );
				continue;
			}

			$stripped_key = (string) $key;

			if ( false !== stripos( $stripped_key, 'color' ) ) {
				$value[ $key ] = wptabspro_sanitize_css_color( $item );
			} elseif ( 'font-family' === $stripped_key ) {
				$value[ $key ] = wptabspro_sanitize_font_family( $item );
			} elseif ( 'font-weight' === $stripped_key ) {
				$weight        = wptabspro_sanitize_css_number( $item );
				$value[ $key ] = ( '' === $weight && in_array( strtolower( (string) $item ), array( 'normal', 'bold', 'bolder', 'lighter' ), true ) ) ? strtolower( (string) $item ) : $weight;
			} elseif ( 'font-style' === $stripped_key ) {
				$value[ $key ] = ( in_array( strtolower( (string) $item ), array( 'normal', 'italic', 'oblique' ), true ) ) ? strtolower( (string) $item ) : '';
			} elseif ( 'style' === $stripped_key ) {
				// Border style select value.
				$value[ $key ] = ( in_array( strtolower( (string) $item ), array( 'solid', 'dashed', 'dotted', 'double', 'groove', 'ridge', 'inset', 'outset', 'none', 'hidden' ), true ) ) ? strtolower( (string) $item ) : '';
			} elseif ( 'unit' === $stripped_key ) {
				$value[ $key ] = ( in_array( (string) $item, array( 'px', '%', 'em', 'rem', 'pt' ), true ) ) ? (string) $item : '';
			} elseif ( preg_match( '/(size|height|width|spacing|margin|padding|radius|top|right|bottom|left|^all$|time|duration|delay)/i', $stripped_key ) ) {
				// Purely numeric values pass through the number sanitizer; keyword values (e.g. `cover`) keep the conservative strip below.
				$value[ $key ] = preg_match( '/^-?\d*\.?\d+$/', trim( (string) $item ) ) ? wptabspro_sanitize_css_number( $item ) : preg_replace( '/[{};<>]/', '', (string) $item );
			} else {
				// Unclassified scalar: remove characters that could break out into new CSS declarations or selectors.
				$value[ $key ] = preg_replace( '/[{};<>]/', '', (string) $item );
			}
		}

		return $value;
	}
}

if ( ! function_exists( 'wptabspro_sanitize_css_bound_value' ) ) {
	/**
	 * Sanitize a field value whose value is interpolated into dynamic CSS output.
	 *
	 * Runs after the default kses sanitization for the CSS-bound field types so a
	 * forged value such as `red; } body { display:none }` can never reach the
	 * generated stylesheet.
	 *
	 * @param  mixed $value The sanitized field value.
	 * @param  array $field The field configuration.
	 * @return mixed
	 */
	function wptabspro_sanitize_css_bound_value( $value, $field ) {
		$type = isset( $field['type'] ) ? $field['type'] : '';

		switch ( $type ) {
			case 'color':
				return is_array( $value ) ? $value : wptabspro_sanitize_css_color( $value );

			case 'color_group':
			case 'spacing':
			case 'border':
			case 'background':
			case 'typography':
			case 'google':
				return is_array( $value ) ? wptabspro_sanitize_css_bound_array( $value ) : wptabspro_sanitize_css_number( $value );

			case 'spinner':
			case 'slider':
			case 'number':
				return is_array( $value ) ? $value : wptabspro_sanitize_css_number( $value );

			case 'select':
			case 'button_set':
			case 'image_select':
			case 'radio':
				// Enforce the configured options when they are a plain flat array.
				if ( empty( $field['options'] ) || ! is_array( $field['options'] ) ) {
					return $value;
				}
				$first_option = reset( $field['options'] );
				if ( ! is_scalar( $first_option ) && ! is_null( $first_option ) ) {
					return $value;
				}
				$allowed = array_merge( array_keys( $field['options'] ), array_filter( $field['options'], 'is_scalar' ) );
				if ( is_array( $value ) ) {
					return $value; // Multi-selects whose options come from callbacks keep the kses-passed value.
				}
				if ( in_array( (string) $value, array_map( 'strval', $allowed ), true ) ) {
					return $value;
				}
				// Fall back to the field default, otherwise discard the forged value.
				return isset( $field['default'] ) && is_scalar( $field['default'] ) ? $field['default'] : '';

			default:
				return $value;
		}
	}
}

if ( ! function_exists( 'wptabspro_sanitize_inline_css' ) ) {
	/**
	 * Harden a CSS string before it is printed inside a <style> element.
	 *
	 * Every "<" is replaced with its CSS escape "\3c " so no markup (such as
	 * a closing </style> tag or an HTML comment) can be formed. Unlike stripping
	 * matched sequences, this cannot be bypassed by nesting, e.g.
	 * "</sty</stylele>". Valid CSS never needs a literal "<" outside strings,
	 * and inside strings "\3c " still renders as "<".
	 *
	 * @param  string $css The CSS string.
	 * @return string
	 */
	function wptabspro_sanitize_inline_css( $css ) {
		return str_replace( '<', '\3c ', (string) $css );
	}
}

if ( ! function_exists( 'wptabspro_sanitize_tab_title_content' ) ) {
	/**
	 *
	 * Sanitize the tab title and content in group field only.
	 *
	 * @param  array $value array.
	 */
	function wptabspro_sanitize_tab_title_content( $value ) {
		if ( empty( $value ) ) {
			return $value;
		}

		$sanitized_data                     = array();
		$wptabspro_allowed_title_tags       = wptabspro_allowed_title_tags();
		$wptabspro_allowed_description_tags = wptabspro_allowed_description_tags();

		if ( is_array( $value ) ) {
			// $value is an multi-dimensional array of a group field.
			foreach ( $value as $key => $fields_value ) {

				if ( ! empty( $fields_value ) && is_array( $fields_value ) ) {

					$data = array(); // This variable stores single tab group data.
					foreach ( $fields_value as $k => $field_value ) {

						if ( ! empty( $field_value ) && 'tabs_content_title' === $k ) {
							// Sanitize Tab Item Title.
							$field_value = wp_kses( $field_value, $wptabspro_allowed_title_tags );
						} elseif ( ! empty( $field_value ) && 'tabs_content_description' === $k ) {
							// Sanitize Tab Item Content.
							$field_value = wp_kses( $field_value, $wptabspro_allowed_description_tags );
						} elseif ( ! empty( $field_value ) && is_array( $field_value ) ) {
							$field_value = wp_kses_post_deep( $field_value );
						} elseif ( ! empty( $field_value ) ) {
							$field_value = wp_kses_post( $field_value );
						}

						$data[ sanitize_key( $k ) ] = $field_value;
					}
				}
				$sanitized_data[ sanitize_key( $key ) ] = $data;
			}
		}

		return $sanitized_data;
	}
}
