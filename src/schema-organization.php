<?php

/**
 * Organization Schema Markup - SEO Enhancement
 * Outputs JSON-LD schema for site-wide organization metadata
 * Helps search engines understand your organization and improves SERP presentation
 */

if ( ! function_exists( 'render_organization_schema' ) ) {
	/**
	 * Render organization schema markup
	 * Hook: wp_head (priority 5 - outputs before other head content)
	 */
	function render_organization_schema() {
		$organization = array(
			'@context'    => 'https://schema.org',
			'@type'       => 'Organization',
			'name'        => get_bloginfo( 'name' ),
			'url'         => get_home_url(),
			'logo'        => get_theme_mod( 'custom_logo_url' ) ?? get_stylesheet_directory_uri() . '/images/logo.png',
			'description' => get_bloginfo( 'description' ),
			'sameAs'      => array(
				// Add your social media profiles here
				// 'https://www.facebook.com/yourpage',
				// 'https://twitter.com/yourhandle',
				// 'https://www.linkedin.com/company/yourcompany',
			),
			'contactPoint' => array(
				'@type'       => 'ContactPoint',
				'contactType' => 'Customer Service',
				'telephone'   => get_option( 'admin_email' ), // Replace with actual phone number
				'email'       => get_option( 'admin_email' ),
			),
		);

		// Only output if on front-end (not admin)
		if ( ! is_admin() ) {
			echo '<script type="application/ld+json">';
			echo wp_json_encode( $organization );
			echo '</script>';
		}
	}
	add_action( 'wp_head', 'render_organization_schema', 5 );
}
