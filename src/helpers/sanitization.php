<?php
/**
 * Sanitization Helpers
 * 
 * Security-focused sanitization utilities for form input, post meta, and user data
 * All functions follow WordPress sanitization standards
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sanitize and validate email
 * 
 * @param string $email Email address
 * @return string Sanitized email or empty string if invalid
 */
function sanitize_email_field( $email ) {
	$email = sanitize_email( $email );
	
	if ( ! is_email( $email ) ) {
		return '';
	}
	
	return $email;
}

/**
 * Sanitize URL for database storage
 * 
 * @param string $url URL to sanitize
 * @return string Sanitized URL or empty string if invalid
 */
function sanitize_url_field( $url ) {
	$url = esc_url_raw( $url );
	
	if ( empty( $url ) ) {
		return '';
	}
	
	return $url;
}

/**
 * Sanitize phone number - remove invalid characters
 * 
 * @param string $phone Phone number
 * @return string Sanitized phone number
 */
function sanitize_phone_field( $phone ) {
	// Allow only digits, spaces, dashes, parentheses, and plus sign
	return preg_replace( '/[^\d+\-\s\(\)]/', '', $phone );
}

/**
 * Sanitize text field with allowed HTML
 * 
 * @param string $text Text to sanitize
 * @param array $allowed_tags Allowed HTML tags (default: basic formatting)
 * @return string Sanitized text
 */
function sanitize_text_field_with_html( $text, $allowed_tags = array() ) {
	if ( empty( $allowed_tags ) ) {
		$allowed_tags = array(
			'b'      => array(),
			'strong' => array(),
			'i'      => array(),
			'em'     => array(),
			'u'      => array(),
			'a'      => array(
				'href'   => array(),
				'title'  => array(),
				'target' => array(),
				'rel'    => array(),
			),
			'br'     => array(),
			'p'      => array(),
		);
	}
	
	// First sanitize the text
	$text = wp_kses_post( $text );
	
	// Then apply allowed tags
	return wp_kses( $text, $allowed_tags );
}

/**
 * Sanitize ACF field value based on field type
 * 
 * @param mixed $value Field value
 * @param array $field ACF field configuration
 * @return mixed Sanitized value
 */
function sanitize_acf_field_value( $value, $field ) {
	if ( ! is_array( $field ) ) {
		return $value;
	}
	
	$type = isset( $field['type'] ) ? $field['type'] : '';
	
	switch ( $type ) {
		case 'email':
			return sanitize_email_field( $value );
		
		case 'url':
			return sanitize_url_field( $value );
		
		case 'text':
			return sanitize_text_field( $value );
		
		case 'textarea':
			return sanitize_textarea_field( $value );
		
		case 'number':
		case 'range':
			return intval( $value );
		
		case 'true_false':
			return (bool) $value;
		
		case 'select':
		case 'radio':
			// Sanitize text but don't allow HTML
			return sanitize_text_field( $value );
		
		case 'checkbox':
			if ( is_array( $value ) ) {
				return array_map( 'sanitize_text_field', $value );
			}
			return sanitize_text_field( $value );
		
		case 'repeater':
			if ( is_array( $value ) ) {
				return array_map( function( $row ) use ( $field ) {
					$sub_fields = isset( $field['sub_fields'] ) ? $field['sub_fields'] : array();
					return sanitize_repeater_row( $row, $sub_fields );
				}, $value );
			}
			return $value;
		
		case 'file':
			// File already validated elsewhere, just ensure safe structure
			if ( is_array( $value ) && isset( $value['ID'] ) ) {
				return array( 'ID' => intval( $value['ID'] ) );
			}
			return $value;
		
		case 'image':
			// Image already validated elsewhere
			if ( is_array( $value ) && isset( $value['ID'] ) ) {
				return array( 'ID' => intval( $value['ID'] ) );
			}
			return $value;
		
		default:
			return sanitize_text_field( $value );
	}
}

/**
 * Sanitize repeater row based on sub-field definitions
 * 
 * @param array $row Repeater row data
 * @param array $sub_fields Sub-field definitions
 * @return array Sanitized row
 */
function sanitize_repeater_row( $row, $sub_fields ) {
	if ( ! is_array( $row ) ) {
		return $row;
	}
	
	$sanitized = array();
	
	foreach ( $row as $key => $value ) {
		// Find matching sub-field definition
		$sub_field = null;
		foreach ( $sub_fields as $field ) {
			if ( isset( $field['name'] ) && $field['name'] === $key ) {
				$sub_field = $field;
				break;
			}
		}
		
		if ( $sub_field ) {
			$sanitized[ $key ] = sanitize_acf_field_value( $value, $sub_field );
		} else {
			$sanitized[ $key ] = sanitize_text_field( $value );
		}
	}
	
	return $sanitized;
}

/**
 * Sanitize post content/meta before saving
 * 
 * @param mixed $value Meta value
 * @param string $meta_key Meta key (used to determine sanitization)
 * @param string $type Data type: 'text', 'textarea', 'html', 'json'
 * @return mixed Sanitized value
 */
function sanitize_post_meta( $value, $meta_key = '', $type = 'text' ) {
	// Prevent saving to reserved meta keys
	if ( ! empty( $meta_key ) && 0 === strpos( $meta_key, '_' ) ) {
		return null;
	}
	
	switch ( $type ) {
		case 'html':
			return wp_kses_post( $value );
		
		case 'json':
			if ( is_string( $value ) ) {
				// Validate JSON
				json_decode( $value );
				return json_last_error() === JSON_ERROR_NONE ? $value : '';
			}
			return wp_json_encode( $value );
		
		case 'textarea':
			return sanitize_textarea_field( $value );
		
		case 'array':
			return is_array( $value ) ? array_map( 'sanitize_text_field', $value ) : $value;
		
		case 'text':
		default:
			return sanitize_text_field( $value );
	}
}

/**
 * Sanitize form submission data
 * Handles both simple $_POST arrays and complex nested structures
 * 
 * @param array $data Form data to sanitize
 * @param array $rules Sanitization rules: [ 'field_name' => 'text'|'email'|'url'|'html'|'json' ]
 * @return array Sanitized form data
 */
function sanitize_form_data( $data, $rules = array() ) {
	if ( ! is_array( $data ) ) {
		return array();
	}
	
	$sanitized = array();
	
	foreach ( $data as $key => $value ) {
		// Security: Only allow alphanumeric + underscore for key
		if ( ! preg_match( '/^[a-zA-Z0-9_\-\[\]]+$/', $key ) ) {
			continue;
		}
		
		// Determine sanitization type
		$type = isset( $rules[ $key ] ) ? $rules[ $key ] : 'text';
		
		if ( is_array( $value ) ) {
			// Nested array
			$sanitized[ $key ] = array_map( function( $v ) use ( $type ) {
				return sanitize_form_field( $v, $type );
			}, $value );
		} else {
			$sanitized[ $key ] = sanitize_form_field( $value, $type );
		}
	}
	
	return $sanitized;
}

/**
 * Sanitize individual form field value
 * 
 * @param mixed $value Field value
 * @param string $type Sanitization type
 * @return mixed Sanitized value
 */
function sanitize_form_field( $value, $type = 'text' ) {
	switch ( $type ) {
		case 'email':
			return sanitize_email_field( $value );
		
		case 'url':
			return sanitize_url_field( $value );
		
		case 'phone':
			return sanitize_phone_field( $value );
		
		case 'html':
			return wp_kses_post( $value );
		
		case 'json':
			if ( is_string( $value ) ) {
				json_decode( $value );
				return json_last_error() === JSON_ERROR_NONE ? $value : '';
			}
			return wp_json_encode( $value );
		
		case 'textarea':
			return sanitize_textarea_field( $value );
		
		case 'integer':
		case 'number':
			return intval( $value );
		
		case 'float':
			return floatval( $value );
		
		case 'boolean':
		case 'bool':
			return ! empty( $value );
		
		case 'text':
		default:
			return sanitize_text_field( $value );
	}
}

/**
 * Strip all HTML tags from string
 * 
 * @param string $text Text to process
 * @return string Text without HTML tags
 */
function strip_all_html( $text ) {
	return wp_strip_all_tags( $text );
}

/**
 * Sanitize file name for safe use
 * 
 * @param string $filename Original filename
 * @return string Sanitized filename
 */
function sanitize_filename_safe( $filename ) {
	return sanitize_file_name( $filename );
}

/**
 * Sanitize array of file data from $_FILES
 * 
 * @param array $file File array from $_FILES
 * @return array Sanitized file data
 */
function sanitize_file_upload_data( $file ) {
	if ( ! is_array( $file ) ) {
		return array();
	}
	
	$sanitized = array();
	
	if ( isset( $file['name'] ) ) {
		$sanitized['name'] = sanitize_file_name( $file['name'] );
	}
	
	if ( isset( $file['type'] ) ) {
		$sanitized['type'] = sanitize_mime_type( $file['type'] );
	}
	
	if ( isset( $file['size'] ) ) {
		$sanitized['size'] = intval( $file['size'] );
	}
	
	if ( isset( $file['tmp_name'] ) ) {
		// Don't sanitize tmp_name - it's a system path
		$sanitized['tmp_name'] = $file['tmp_name'];
	}
	
	if ( isset( $file['error'] ) ) {
		$sanitized['error'] = intval( $file['error'] );
	}
	
	return $sanitized;
}

/**
 * Sanitize MIME type
 * 
 * @param string $mime_type MIME type to sanitize
 * @return string Sanitized MIME type or empty string
 */
function sanitize_mime_type( $mime_type ) {
	// Basic MIME type validation: type/subtype
	if ( ! preg_match( '/^[a-zA-Z0-9\-\.]+\/[a-zA-Z0-9\-\.\+]+$/', $mime_type ) ) {
		return '';
	}
	
	return sanitize_text_field( $mime_type );
}

/**
 * Sanitize array and ensure required keys exist
 * 
 * @param array $data Array to sanitize
 * @param array $required_keys Required keys
 * @param string $type Sanitization type for all fields
 * @return array Sanitized array or empty array if missing required keys
 */
function sanitize_array_with_requirements( $data, $required_keys = array(), $type = 'text' ) {
	if ( ! is_array( $data ) ) {
		return array();
	}
	
	// Check required keys
	foreach ( $required_keys as $key ) {
		if ( ! isset( $data[ $key ] ) || empty( $data[ $key ] ) ) {
			return array(); // Fail if required key missing
		}
	}
	
	return sanitize_form_data( $data, array_fill_keys( array_keys( $data ), $type ) );
}

/**
 * Prepare option value for safe storage in wp_options
 * 
 * @param mixed $value Option value
 * @param string $option Option name (used to determine type)
 * @return mixed Sanitized value
 */
function sanitize_option_value( $value, $option = '' ) {
	// Common option name patterns
	if ( strpos( $option, 'email' ) !== false ) {
		return sanitize_email_field( $value );
	}
	
	if ( strpos( $option, 'url' ) !== false || strpos( $option, 'link' ) !== false ) {
		return sanitize_url_field( $value );
	}
	
	if ( strpos( $option, 'color' ) !== false ) {
		// Validate hex color
		return sanitize_hex_color( $value );
	}
	
	if ( is_array( $value ) ) {
		return array_map( 'sanitize_text_field', $value );
	}
	
	return sanitize_text_field( $value );
}

/**
 * Sanitize hex color code
 * 
 * @param string $color Hex color code
 * @return string Sanitized hex color or empty string
 */
function sanitize_hex_color_safe( $color ) {
	// Remove spaces
	$color = trim( $color );
	
	// Check if it's a valid hex color
	if ( ! preg_match( '/^#?[a-fA-F0-9]{6}$|^#?[a-fA-F0-9]{3}$/', $color ) ) {
		return '';
	}
	
	// Ensure # prefix
	if ( 0 !== strpos( $color, '#' ) ) {
		$color = '#' . $color;
	}
	
	return strtolower( $color );
}
