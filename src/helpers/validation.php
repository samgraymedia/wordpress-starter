<?php
/**
 * Validation Helpers
 * 
 * Security-focused validation utilities for ACF fields, post meta, and form submissions
 * All functions return array with 'valid' boolean and optional 'error' message
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Validate email address format
 * 
 * @param string $email Email address to validate
 * @return array { valid: boolean, error: string|null }
 */
function validate_email( $email ) {
	$email = sanitize_email( $email );
	
	if ( empty( $email ) ) {
		return array(
			'valid' => false,
			'error' => 'Email is required',
		);
	}
	
	if ( ! is_email( $email ) ) {
		return array(
			'valid' => false,
			'error' => 'Invalid email address format',
		);
	}
	
	return array( 'valid' => true );
}

/**
 * Validate URL format
 * 
 * @param string $url URL to validate
 * @param boolean $allow_local Allow local/internal URLs
 * @return array { valid: boolean, error: string|null }
 */
function validate_url( $url, $allow_local = true ) {
	if ( empty( $url ) ) {
		return array(
			'valid' => false,
			'error' => 'URL is required',
		);
	}
	
	// Use WordPress esc_url_raw which validates
	$validated = esc_url_raw( $url );
	
	if ( empty( $validated ) ) {
		return array(
			'valid' => false,
			'error' => 'Invalid URL format',
		);
	}
	
	if ( ! $allow_local ) {
		$parsed = wp_parse_url( $validated );
		if ( ! isset( $parsed['host'] ) || in_array( $parsed['host'], array( 'localhost', '127.0.0.1' ), true ) ) {
			return array(
				'valid' => false,
				'error' => 'Local URLs are not allowed',
			);
		}
	}
	
	return array( 'valid' => true );
}

/**
 * Validate phone number format (basic)
 * Accepts: digits, spaces, dashes, parentheses, plus sign
 * 
 * @param string $phone Phone number to validate
 * @return array { valid: boolean, error: string|null }
 */
function validate_phone( $phone ) {
	if ( empty( $phone ) ) {
		return array(
			'valid' => false,
			'error' => 'Phone number is required',
		);
	}
	
	// Remove common formatting characters
	$cleaned = preg_replace( '/[^\d+\-\s\(\)]/', '', $phone );
	
	// Check minimum length (7 digits)
	$digits_only = preg_replace( '/\D/', '', $cleaned );
	
	if ( strlen( $digits_only ) < 7 ) {
		return array(
			'valid' => false,
			'error' => 'Phone number must contain at least 7 digits',
		);
	}
	
	return array( 'valid' => true );
}

/**
 * Validate integer value
 * 
 * @param mixed $value Value to validate
 * @param integer|null $min Minimum allowed value
 * @param integer|null $max Maximum allowed value
 * @return array { valid: boolean, error: string|null }
 */
function validate_integer( $value, $min = null, $max = null ) {
	if ( ! is_numeric( $value ) || intval( $value ) != $value ) {
		return array(
			'valid' => false,
			'error' => 'Value must be a whole number',
		);
	}
	
	$int_value = intval( $value );
	
	if ( null !== $min && $int_value < $min ) {
		return array(
			'valid' => false,
			'error' => sprintf( 'Value must be at least %d', $min ),
		);
	}
	
	if ( null !== $max && $int_value > $max ) {
		return array(
			'valid' => false,
			'error' => sprintf( 'Value must not exceed %d', $max ),
		);
	}
	
	return array( 'valid' => true );
}

/**
 * Validate required field (non-empty)
 * 
 * @param mixed $value Field value
 * @param string $field_name Name of field for error message
 * @return array { valid: boolean, error: string|null }
 */
function validate_required( $value, $field_name = 'This field' ) {
	if ( empty( $value ) ) {
		return array(
			'valid' => false,
			'error' => sprintf( '%s is required', $field_name ),
		);
	}
	
	return array( 'valid' => true );
}

/**
 * Validate string length
 * 
 * @param string $value String value
 * @param integer|null $min_length Minimum length
 * @param integer|null $max_length Maximum length
 * @return array { valid: boolean, error: string|null }
 */
function validate_string_length( $value, $min_length = null, $max_length = null ) {
	$length = strlen( (string) $value );
	
	if ( null !== $min_length && $length < $min_length ) {
		return array(
			'valid' => false,
			'error' => sprintf( 'Must be at least %d character%s', $min_length, $min_length !== 1 ? 's' : '' ),
		);
	}
	
	if ( null !== $max_length && $length > $max_length ) {
		return array(
			'valid' => false,
			'error' => sprintf( 'Must not exceed %d character%s', $max_length, $max_length !== 1 ? 's' : '' ),
		);
	}
	
	return array( 'valid' => true );
}

/**
 * Validate ACF field value based on field configuration
 * 
 * @param mixed $value Field value
 * @param array $field ACF field array
 * @return array { valid: boolean, error: string|null }
 */
function validate_acf_field( $value, $field ) {
	if ( ! is_array( $field ) ) {
		return array(
			'valid' => false,
			'error' => 'Invalid field configuration',
		);
	}
	
	$type = isset( $field['type'] ) ? $field['type'] : '';
	$required = isset( $field['required'] ) ? $field['required'] : false;
	
	// Check required
	if ( $required && empty( $value ) ) {
		$label = isset( $field['label'] ) ? $field['label'] : 'This field';
		return array(
			'valid' => false,
			'error' => sprintf( '%s is required', $label ),
		);
	}
	
	// Type-specific validation
	switch ( $type ) {
		case 'email':
			return validate_email( $value );
		
		case 'url':
			return validate_url( $value );
		
		case 'number':
		case 'range':
			$result = validate_integer( $value );
			if ( ! $result['valid'] ) {
				return $result;
			}
			
			// Check min value
			if ( isset( $field['min'] ) && intval( $value ) < intval( $field['min'] ) ) {
				return array(
					'valid' => false,
					'error' => sprintf( 'Minimum value is %d', $field['min'] ),
				);
			}
			
			// Check max value
			if ( isset( $field['max'] ) && intval( $value ) > intval( $field['max'] ) ) {
				return array(
					'valid' => false,
					'error' => sprintf( 'Maximum value is %d', $field['max'] ),
				);
			}
			
			return array( 'valid' => true );
		
		case 'text':
		case 'textarea':
			if ( isset( $field['maxlength'] ) ) {
				return validate_string_length( 
					$value, 
					null, 
					intval( $field['maxlength'] ) 
				);
			}
			return array( 'valid' => true );
		
		case 'file':
		case 'image':
			// Files handled separately via validate_file_upload
			return array( 'valid' => true );
		
		default:
			return array( 'valid' => true );
	}
}

/**
 * Validate file upload
 * 
 * @param array $file File array (from $_FILES)
 * @param integer $max_size_mb Maximum file size in MB
 * @param array $allowed_types Allowed MIME types
 * @return array { valid: boolean, error: string|null }
 */
function validate_file_upload( $file, $max_size_mb = 5, $allowed_types = array() ) {
	if ( ! isset( $file['name'] ) || empty( $file['name'] ) ) {
		return array(
			'valid' => false,
			'error' => 'File is required',
		);
	}
	
	// Check for upload errors
	if ( isset( $file['error'] ) && UPLOAD_ERR_OK !== $file['error'] ) {
		$error_messages = array(
			UPLOAD_ERR_INI_SIZE   => 'File exceeds upload_max_filesize',
			UPLOAD_ERR_FORM_SIZE  => 'File exceeds MAX_FILE_SIZE',
			UPLOAD_ERR_PARTIAL    => 'File was partially uploaded',
			UPLOAD_ERR_NO_FILE    => 'No file was uploaded',
			UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
			UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
		);
		
		$error = isset( $error_messages[ $file['error'] ] ) 
			? $error_messages[ $file['error'] ] 
			: 'Unknown upload error';
		
		return array(
			'valid' => false,
			'error' => $error,
		);
	}
	
	// Check file size
	$file_size_mb = $file['size'] / ( 1024 * 1024 );
	if ( $file_size_mb > $max_size_mb ) {
		return array(
			'valid' => false,
			'error' => sprintf( 'File size exceeds %dMB limit', $max_size_mb ),
		);
	}
	
	// Check MIME type if specified
	if ( ! empty( $allowed_types ) ) {
		$file_type = wp_check_filetype( $file['name'] );
		$mime_type = $file_type['type'] ? $file_type['type'] : mime_content_type( $file['tmp_name'] );
		
		if ( ! in_array( $mime_type, $allowed_types, true ) ) {
			return array(
				'valid' => false,
				'error' => sprintf( 'File type not allowed. Accepted: %s', implode( ', ', $allowed_types ) ),
			);
		}
	}
	
	return array( 'valid' => true );
}

/**
 * Validate CSRF token
 * 
 * @param string $nonce Nonce to verify
 * @param string $action Action name for nonce verification
 * @return array { valid: boolean, error: string|null }
 */
function validate_csrf_token( $nonce, $action = 'security_check' ) {
	if ( empty( $nonce ) ) {
		return array(
			'valid' => false,
			'error' => 'Security token missing',
		);
	}
	
	if ( ! wp_verify_nonce( $nonce, $action ) ) {
		return array(
			'valid' => false,
			'error' => 'Security token verification failed',
		);
	}
	
	return array( 'valid' => true );
}

/**
 * Validate post meta value before saving
 * 
 * @param mixed $value Meta value
 * @param string $meta_key Meta key
 * @param string|integer $post_id Post ID
 * @return array { valid: boolean, error: string|null }
 */
function validate_post_meta( $value, $meta_key, $post_id = null ) {
	if ( empty( $meta_key ) ) {
		return array(
			'valid' => false,
			'error' => 'Meta key is required',
		);
	}
	
	// Prevent reserved meta keys
	if ( 0 === strpos( $meta_key, '_' ) ) {
		return array(
			'valid' => false,
			'error' => 'Meta key cannot start with underscore (reserved)',
		);
	}
	
	// Check value is serializable
	if ( is_array( $value ) || is_object( $value ) ) {
		$serialized = serialize( $value );
		if ( false === $serialized ) {
			return array(
				'valid' => false,
				'error' => 'Value cannot be serialized',
			);
		}
	} else {
		// Check string length (WordPress limit is 191 chars for meta_key + value combo)
		$value_str = (string) $value;
		if ( strlen( $value_str ) > 65535 ) { // Reasonable max for LONGTEXT
			return array(
				'valid' => false,
				'error' => 'Meta value exceeds maximum length',
			);
		}
	}
	
	return array( 'valid' => true );
}

/**
 * Validate array of values against ruleset
 * 
 * @param array $data Data to validate
 * @param array $rules Validation rules: [ 'field_name' => [ 'required' => true, 'type' => 'email' ] ]
 * @return array { valid: boolean, errors: array }
 */
function validate_data_array( $data, $rules ) {
	$errors = array();
	
	foreach ( $rules as $field => $rule ) {
		$value = isset( $data[ $field ] ) ? $data[ $field ] : null;
		
		// Check required
		if ( isset( $rule['required'] ) && $rule['required'] && empty( $value ) ) {
			$errors[ $field ] = sprintf( '%s is required', $field );
			continue;
		}
		
		if ( empty( $value ) ) {
			continue;
		}
		
		// Check type
		if ( isset( $rule['type'] ) ) {
			switch ( $rule['type'] ) {
				case 'email':
					$result = validate_email( $value );
					if ( ! $result['valid'] ) {
						$errors[ $field ] = $result['error'];
					}
					break;
				
				case 'url':
					$result = validate_url( $value );
					if ( ! $result['valid'] ) {
						$errors[ $field ] = $result['error'];
					}
					break;
				
				case 'phone':
					$result = validate_phone( $value );
					if ( ! $result['valid'] ) {
						$errors[ $field ] = $result['error'];
					}
					break;
				
				case 'integer':
					$result = validate_integer( $value );
					if ( ! $result['valid'] ) {
						$errors[ $field ] = $result['error'];
					}
					break;
			}
		}
		
		// Check min/max length
		if ( isset( $rule['min_length'] ) || isset( $rule['max_length'] ) ) {
			$min = isset( $rule['min_length'] ) ? $rule['min_length'] : null;
			$max = isset( $rule['max_length'] ) ? $rule['max_length'] : null;
			$result = validate_string_length( $value, $min, $max );
			if ( ! $result['valid'] ) {
				$errors[ $field ] = $result['error'];
			}
		}
	}
	
	return array(
		'valid'  => empty( $errors ),
		'errors' => $errors,
	);
}
