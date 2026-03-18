<?php
/**
 * Custom Login Page
 * Displays a custom login form styled with the theme
 *
 * @package starter-theme-1.x
 */

use Timber\Timber;

// Redirect if already logged in
if ( is_user_logged_in() ) {
	wp_redirect( home_url( '/shop' ) );
	exit;
}

$context = Timber::context();
$context['page_title'] = __( 'Login', 'starter-theme-1.x' );

// Handle login form submission
if ( isset( $_POST['log'] ) && isset( $_POST['pwd'] ) ) {
	$user = wp_authenticate( $_POST['log'], $_POST['pwd'] );
	
	if ( is_wp_error( $user ) ) {
		$context['error_message'] = $user->get_error_message();
	} else {
		wp_set_current_user( $user->ID );
		wp_set_auth_cookie( $user->ID );
		
		$redirect = isset( $_POST['redirect_to'] ) ? $_POST['redirect_to'] : home_url( '/shop' );
		wp_redirect( $redirect );
		exit;
	}
}

Timber::render( 'login.twig', $context );
