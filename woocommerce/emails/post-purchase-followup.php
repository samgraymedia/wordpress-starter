<?php
/**
 * Post-Purchase Follow-up Email - Timber/Twig Wrapper
 *
 * This template loads the Twig template for post-purchase follow-up emails.
 * Passed variables: $email_heading, $order, $customer_name, $order_id, $additional_content, $site_name, $site_url
 *
 * @package WooCommerce/Templates/Emails
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Timber' ) ) {
	return;  // Fallback to default if Timber not available
}

use Timber\Timber;

// Prepare context for Twig template
$context = array(
	'heading'            => isset( $email_heading ) ? $email_heading : 'Thank You for Your Purchase!',
	'customer_name'      => isset( $customer_name ) ? $customer_name : 'Valued Customer',
	'order'              => isset( $order ) ? $order : null,
	'order_id'           => isset( $order_id ) ? $order_id : 0,
	'additional_content' => isset( $additional_content ) ? $additional_content : '',
	'site_name'          => isset( $site_name ) ? $site_name : get_bloginfo( 'name' ),
	'site_url'           => isset( $site_url ) ? $site_url : site_url(),
	'site'               => Timber::get_context()['site'] ?? [],
	'wc_get_page_permalink' => function( $page ) {
		return wc_get_page_permalink( $page );
	},
	'bloginfo'           => function( $show = '' ) {
		return get_bloginfo( $show );
	},
);

// Render the Twig template
echo Timber::compile(
	'email/post-purchase-followup.twig',
	$context
);
