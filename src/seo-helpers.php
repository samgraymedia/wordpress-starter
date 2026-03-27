<?php
/**
 * SEO Helpers & Meta Tags
 *
 * Handles generation of SEO-critical meta tags:
 * - Open Graph (Facebook/LinkedIn sharing)
 * - Twitter Cards (Twitter/X sharing)
 * - Canonical tags (duplicate content prevention)
 * - JSON-LD schema markup (Google rich results)
 *
 * @package Starter_Theme
 * @subpackage SEO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Generate Open Graph meta tags
 *
 * @param array $args {
 *     @type string $title       Page title
 *     @type string $description Page description
 *     @type string $image       Image URL
 *     @type string $url         Page URL
 *     @type string $type        OG type (website, article, product)
 *     @type string $locale      Locale (en_US, etc.)
 * }
 * @return void
 */
function theme_output_open_graph( $args = array() ) {
	$defaults = array(
		'title'       => wp_get_document_title(),
		'description' => get_bloginfo( 'description' ),
		'image'       => '',
		'url'         => home_url( add_query_arg( null, null ) ),
		'type'        => is_singular( 'product' ) ? 'product' : 'website',
		'locale'      => str_replace( '-', '_', get_locale() ),
	);

	$og = wp_parse_args( $args, $defaults );

	// Sanitize and validate
	$og['title']       = sanitize_text_field( $og['title'] );
	$og['description'] = sanitize_text_field( $og['description'] );
	$og['url']         = esc_url( $og['url'] );
	$og['type']        = sanitize_text_field( $og['type'] );
	$og['locale']      = sanitize_text_field( $og['locale'] );

	// Title
	echo '<meta property="og:title" content="' . esc_attr( $og['title'] ) . '">' . "\n";

	// Description
	if ( ! empty( $og['description'] ) ) {
		echo '<meta property="og:description" content="' . esc_attr( $og['description'] ) . '">' . "\n";
	}

	// URL
	echo '<meta property="og:url" content="' . esc_attr( $og['url'] ) . '">' . "\n";

	// Type
	echo '<meta property="og:type" content="' . esc_attr( $og['type'] ) . '">' . "\n";

	// Site name
	echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";

	// Image
	if ( ! empty( $og['image'] ) ) {
		echo '<meta property="og:image" content="' . esc_url( $og['image'] ) . '">' . "\n";
		echo '<meta property="og:image:width" content="1200">' . "\n";
		echo '<meta property="og:image:height" content="630">' . "\n";
	} elseif ( is_singular() ) {
		// Try to get featured image
		$featured_image = get_the_post_thumbnail_url( null, 'large' );
		if ( $featured_image ) {
			echo '<meta property="og:image" content="' . esc_url( $featured_image ) . '">' . "\n";
		}
	}

	// Locale
	echo '<meta property="og:locale" content="' . esc_attr( $og['locale'] ) . '">' . "\n";

	// Product-specific
	if ( is_singular( 'product' ) && function_exists( 'wc_get_product' ) ) {
		$product = wc_get_product( get_the_ID() );
		if ( $product ) {
			echo '<meta property="product:price:amount" content="' . esc_attr( $product->get_price() ) . '">' . "\n";
			echo '<meta property="product:price:currency" content="' . esc_attr( get_woocommerce_currency() ) . '">' . "\n";
		}
	}
}

/**
 * Generate Twitter Card meta tags
 *
 * @param array $args {
 *     @type string $card        Card type (summary, summary_large_image, player)
 *     @type string $title       Card title
 *     @type string $description Card description
 *     @type string $image       Card image URL
 *     @type string $handle      Twitter handle (@username)
 * }
 * @return void
 */
function theme_output_twitter_card( $args = array() ) {
	$defaults = array(
		'card'        => 'summary_large_image',
		'title'       => wp_get_document_title(),
		'description' => get_bloginfo( 'description' ),
		'image'       => '',
		'handle'      => '',
	);

	$twitter = wp_parse_args( $args, $defaults );

	// Sanitize
	$twitter['card']        = sanitize_text_field( $twitter['card'] );
	$twitter['title']       = sanitize_text_field( $twitter['title'] );
	$twitter['description'] = sanitize_text_field( $twitter['description'] );
	$twitter['handle']      = sanitize_text_field( $twitter['handle'] );

	// Card type
	echo '<meta name="twitter:card" content="' . esc_attr( $twitter['card'] ) . '">' . "\n";

	// Title
	echo '<meta name="twitter:title" content="' . esc_attr( $twitter['title'] ) . '">' . "\n";

	// Description
	if ( ! empty( $twitter['description'] ) ) {
		echo '<meta name="twitter:description" content="' . esc_attr( $twitter['description'] ) . '">' . "\n";
	}

	// Image
	if ( ! empty( $twitter['image'] ) ) {
		echo '<meta name="twitter:image" content="' . esc_url( $twitter['image'] ) . '">' . "\n";
	} elseif ( is_singular() ) {
		$featured_image = get_the_post_thumbnail_url( null, 'large' );
		if ( $featured_image ) {
			echo '<meta name="twitter:image" content="' . esc_url( $featured_image ) . '">' . "\n";
		}
	}

	// Twitter handle
	if ( ! empty( $twitter['handle'] ) ) {
		echo '<meta name="twitter:creator" content="' . esc_attr( $twitter['handle'] ) . '">' . "\n";
	}
}

/**
 * Generate canonical tag
 *
 * @param string $url Optional canonical URL. If not provided, uses current page URL.
 * @return void
 */
function theme_output_canonical( $url = '' ) {
	if ( empty( $url ) ) {
		if ( is_front_page() ) {
			$url = home_url();
		} elseif ( is_singular() ) {
			$url = get_permalink();
		} else {
			$url = home_url( add_query_arg( null, null ) );
		}
	}

	$url = esc_url( $url );
	echo '<link rel="canonical" href="' . esc_url( $url ) . '">' . "\n";
}

/**
 * Generate JSON-LD schema markup
 *
 * @param array $schema Schema array structured as JSON-LD
 * @return void
 */
function theme_output_json_ld( $schema = array() ) {
	if ( empty( $schema ) ) {
		return;
	}

	// Ensure @context is set
	if ( ! isset( $schema['@context'] ) ) {
		$schema['@context'] = 'https://schema.org';
	}

	// Ensure @type is set
	if ( ! isset( $schema['@type'] ) ) {
		$schema['@type'] = 'WebPage';
	}

	echo '<script type="application/ld+json">' . "\n";
	echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "\n";
	echo '</script>' . "\n";
}

/**
 * Generate Organization schema
 * Usually placed in header
 *
 * @return array
 */
function theme_get_organization_schema() {
	return array(
		'@context' => 'https://schema.org',
		'@type'    => 'Organization',
		'name'     => get_bloginfo( 'name' ),
		'url'      => home_url(),
		'logo'     => get_site_icon_url(),
	);
}

/**
 * Generate Article schema for posts
 *
 * @param int $post_id Optional post ID. Uses current post if not provided.
 * @return array
 */
function theme_get_article_schema( $post_id = 0 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$post = get_post( $post_id );
	if ( ! $post ) {
		return array();
	}

	$schema = array(
		'@context'     => 'https://schema.org',
		'@type'        => 'Article',
		'headline'     => get_the_title( $post_id ),
		'description'  => get_the_excerpt( $post_id ),
		'image'        => get_the_post_thumbnail_url( $post_id, 'large' ),
		'datePublished' => get_the_date( 'c', $post_id ),
		'dateModified'  => get_the_modified_date( 'c', $post_id ),
		'author'       => array(
			'@type' => 'Person',
			'name'  => get_the_author_meta( 'display_name', $post->post_author ),
		),
	);

	// Remove empty image if no featured image
	if ( empty( $schema['image'] ) ) {
		unset( $schema['image'] );
	}

	return $schema;
}

/**
 * Generate Product schema for WooCommerce products
 *
 * @param int $product_id Optional product ID. Uses current post if not provided.
 * @return array
 */
function theme_get_product_schema( $product_id = 0 ) {
	if ( ! function_exists( 'wc_get_product' ) ) {
		return array();
	}

	if ( ! $product_id ) {
		$product_id = get_the_ID();
	}

	$product = wc_get_product( $product_id );
	if ( ! $product ) {
		return array();
	}

	$schema = array(
		'@context'      => 'https://schema.org/',
		'@type'         => 'Product',
		'name'          => $product->get_title(),
		'description'   => $product->get_short_description(),
		'image'         => get_the_post_thumbnail_url( $product_id, 'large' ),
		'url'           => get_permalink( $product_id ),
		'brand'         => array(
			'@type' => 'Brand',
			'name'  => get_bloginfo( 'name' ),
		),
		'offers'        => array(
			'@type'         => 'Offer',
			'url'           => get_permalink( $product_id ),
			'priceCurrency' => get_woocommerce_currency(),
			'price'         => $product->get_price(),
			'availability'  => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
		),
	);

	// Add rating if available
	if ( $product->get_rating_count() > 0 ) {
		$schema['aggregateRating'] = array(
			'@type'       => 'AggregateRating',
			'ratingValue' => $product->get_average_rating(),
			'reviewCount' => $product->get_review_count(),
		);
	}

	// Remove empty image
	if ( empty( $schema['image'] ) ) {
		unset( $schema['image'] );
	}

	return $schema;
}

/**
 * Generate LocalBusiness schema
 * Useful for local business websites
 *
 * @param array $args {
 *     @type string $street_address Street address
 *     @type string $address_locality City
 *     @type string $address_region State/Province
 *     @type string $postal_code ZIP code
 *     @type string $telephone Phone number
 *     @type string $email Email address
 * }
 * @return array
 */
function theme_get_local_business_schema( $args = array() ) {
	$defaults = array(
		'street_address'   => '',
		'address_locality' => '',
		'address_region'   => '',
		'postal_code'      => '',
		'telephone'        => '',
		'email'            => '',
	);

	$business = wp_parse_args( $args, $defaults );

	return array(
		'@context' => 'https://schema.org',
		'@type'    => 'LocalBusiness',
		'name'     => get_bloginfo( 'name' ),
		'url'      => home_url(),
		'logo'     => get_site_icon_url(),
		'address'  => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => $business['street_address'],
			'addressLocality' => $business['address_locality'],
			'addressRegion'   => $business['address_region'],
			'postalCode'      => $business['postal_code'],
		),
		'telephone' => $business['telephone'],
		'email'     => $business['email'],
	);
}

/**
 * Generate BreadcrumbList schema
 *
 * @param array $breadcrumbs Array of breadcrumb items
 * @return array
 */
function theme_get_breadcrumb_schema( $breadcrumbs = array() ) {
	if ( empty( $breadcrumbs ) ) {
		return array();
	}

	$items = array();
	foreach ( $breadcrumbs as $index => $breadcrumb ) {
		$items[] = array(
			'@type'    => 'ListItem',
			'position' => $index + 1,
			'name'     => $breadcrumb['title'],
			'item'     => $breadcrumb['url'],
		);
	}

	return array(
		'@context'       => 'https://schema.org',
		'@type'          => 'BreadcrumbList',
		'itemListElement' => $items,
	);
}

/**
 * Get featured image for SEO (Open Graph, Twitter Cards)
 *
 * Prioritizes:
 * 1. Featured image
 * 2. First image in post content
 * 3. Site logo/icon
 *
 * @param int $post_id Optional post ID. Uses current post if not provided.
 * @return string Image URL or empty string
 */
function theme_get_seo_image( $post_id = 0 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	// Try featured image first
	$featured = get_the_post_thumbnail_url( $post_id, 'large' );
	if ( ! empty( $featured ) ) {
		return $featured;
	}

	// Try first image in content
	$post = get_post( $post_id );
	if ( $post && ! empty( $post->post_content ) ) {
		if ( preg_match( '/<img[^>]+src=["\']([^"\']+)["\']/', $post->post_content, $matches ) ) {
			return $matches[1];
		}
	}

	// Fall back to site icon
	$icon = get_site_icon_url();
	return ! empty( $icon ) ? $icon : '';
}

/**
 * Get description for SEO meta tags
 *
 * Prioritizes:
 * 1. Custom meta description (if set)
 * 2. Post excerpt
 * 3. First 160 chars of content
 * 4. Tagline
 *
 * @param int $post_id Optional post ID. Uses current post if not provided.
 * @return string Description (max 160 chars)
 */
function theme_get_seo_description( $post_id = 0 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	// Try custom meta description first
	$meta_desc = get_post_meta( $post_id, '_meta_description', true );
	if ( ! empty( $meta_desc ) ) {
		return substr( $meta_desc, 0, 160 );
	}

	// Try excerpt
	$excerpt = get_the_excerpt( $post_id );
	if ( ! empty( $excerpt ) ) {
		return substr( $excerpt, 0, 160 );
	}

	// Try content
	$post = get_post( $post_id );
	if ( $post && ! empty( $post->post_content ) ) {
		$content = wp_strip_all_tags( $post->post_content );
		return substr( $content, 0, 160 );
	}

	// Fall back to tagline
	return get_bloginfo( 'description' );
}
