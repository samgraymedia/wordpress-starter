<?php
/**
 * Custom Registration Page
 * Displays a custom registration form styled with the theme
 *
 * @package starter-theme-1.x
 */

use Timber\Timber;

// Redirect if already logged in
if ( is_user_logged_in() ) {
	wp_redirect( home_url( '/shop' ) );
	exit;
}

// Check if registration is enabled
if ( ! get_option( 'users_can_register' ) ) {
	wp_redirect( home_url( '/login' ) );
	exit;
}

$context = Timber::context();
$context['page_title'] = __( 'Create Account', 'starter-theme-1.x' );

// Handle registration form submission
if ( isset( $_POST['register'] ) ) {
	// Verify nonce
	if ( ! isset( $_POST['_register_nonce'] ) || ! wp_verify_nonce( $_POST['_register_nonce'], 'register_nonce' ) ) {
		$context['error_message'] = __( 'Security check failed. Please try again.', 'starter-theme-1.x' );
	} else {
		$email = sanitize_email( $_POST['email'] ?? '' );
		$first_name = sanitize_text_field( $_POST['first_name'] ?? '' );
		$last_name = sanitize_text_field( $_POST['last_name'] ?? '' );
		$password = $_POST['password'] ?? '';
		$password_confirm = $_POST['password_confirm'] ?? '';
		
		// Validate inputs
		$errors = array();
		
		if ( empty( $email ) ) {
			$errors[] = __( 'Email is required.', 'starter-theme-1.x' );
		} elseif ( ! is_email( $email ) ) {
			$errors[] = __( 'Please enter a valid email address.', 'starter-theme-1.x' );
		} elseif ( email_exists( $email ) ) {
			$errors[] = __( 'This email is already registered.', 'starter-theme-1.x' );
		}
		
		if ( empty( $password ) || strlen( $password ) < 8 ) {
			$errors[] = __( 'Password must be at least 8 characters long.', 'starter-theme-1.x' );
		}
		
		if ( $password !== $password_confirm ) {
			$errors[] = __( 'Passwords do not match.', 'starter-theme-1.x' );
		}
		
		if ( ! isset( $_POST['agree_terms'] ) ) {
			$errors[] = __( 'You must agree to the Terms of Service.', 'starter-theme-1.x' );
		}
		
		if ( ! empty( $errors ) ) {
			$context['error_message'] = implode( '<br/>', $errors );
		} else {
			// Create new user
			$user_id = wp_create_user( $email, $password, $email );
			
			if ( is_wp_error( $user_id ) ) {
				$context['error_message'] = $user_id->get_error_message();
			} else {
				// Update user metadata
				wp_update_user( array(
					'ID' => $user_id,
					'first_name' => $first_name,
					'last_name' => $last_name,
					'display_name' => trim( "$first_name $last_name" ) ?: $email,
				) );
				
				// Log the user in
				wp_set_current_user( $user_id );
				wp_set_auth_cookie( $user_id );
				
				// Redirect to shop
				wp_redirect( home_url( '/shop' ) );
				exit;
			}
		}
	}
}

Timber::render( 'register.twig', $context );
