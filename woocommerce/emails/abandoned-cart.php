<?php
/**
 * Abandoned Cart Email - Timber/Twig Wrapper
 *
 * This template loads the Twig template for abandoned cart recovery emails.
 * Passed variables: $email_heading, $customer_name, $customer_email, $cart_items, $cart_total, $cart_url, 
 *                   $discount_code, $discount_percent, $additional_content, $site_name, $site_url
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
	'heading'           => $email_heading,
	'customer_name'     => $customer_name,
	'customer_email'    => $customer_email,
	'cart_items'        => $cart_items,
	'cart_total'        => $cart_total,
	'cart_url'          => $cart_url,
	'discount_code'     => $discount_code,
	'discount_percent'  => $discount_percent,
	'additional_content' => $additional_content,
	'site_name'         => $site_name,
	'site_url'          => $site_url,
	'site'              => Timber::get_context()['site'] ?? [],
);

// Render the Twig template
echo Timber::compile(
	'email/abandoned-cart.twig',
	$context
);
