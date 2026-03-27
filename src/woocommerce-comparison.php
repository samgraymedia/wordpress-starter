<?php
/**
 * Product Comparison Functionality
 *
 * Handles AJAX requests for adding/removing products to/from comparison
 * and managing the comparison session/transient.
 *
 * @package StarterTheme
 */

if ( ! class_exists( 'WooCommerce' ) ) {
	return; // Exit if WooCommerce is not active
}

/**
 * Initialize comparison functionality
 */
function theme_init_comparison() {
	// Enqueue comparison script
	wp_enqueue_script(
		'theme-comparison',
		get_template_directory_uri() . '/scripts/comparison.js',
		array(),
		filemtime( get_template_directory() . '/scripts/comparison.js' ),
		true
	);

	// Localize script data
	wp_localize_script(
		'theme-comparison',
		'themeComparison',
		array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'theme_comparison_nonce' ),
			'comparison_url' => theme_get_comparison_page_url(),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'theme_init_comparison' );

/**
 * Get comparison data from session/transient
 *
 * @return array Array of product IDs
 */
function theme_get_comparison() {
	if ( is_user_logged_in() ) {
		return get_user_meta( get_current_user_id(), 'theme_comparison', true ) ?: array();
	}

	// For non-logged-in users, use transient with session ID
	$session_id = isset( $_COOKIE[ COOKIEHASH . '_comparison' ] ) ? sanitize_text_field( $_COOKIE[ COOKIEHASH . '_comparison' ] ) : null;
	if ( ! $session_id ) {
		$session_id = wp_generate_password( 16, false );
		setcookie( COOKIEHASH . '_comparison', $session_id, time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
	}

	return get_transient( 'theme_comparison_' . $session_id ) ?: array();
}

/**
 * Save comparison data to session/transient
 *
 * @param array $comparison Array of product IDs
 */
function theme_set_comparison( $comparison ) {
	if ( is_user_logged_in() ) {
		update_user_meta( get_current_user_id(), 'theme_comparison', $comparison );
	} else {
		$session_id = isset( $_COOKIE[ COOKIEHASH . '_comparison' ] ) ? sanitize_text_field( $_COOKIE[ COOKIEHASH . '_comparison' ] ) : null;
		if ( ! $session_id ) {
			$session_id = wp_generate_password( 16, false );
			setcookie( COOKIEHASH . '_comparison', $session_id, time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
		}
		set_transient( 'theme_comparison_' . $session_id, $comparison, 30 * DAY_IN_SECONDS );
	}
}

/**
 * Get comparison page URL
 * Auto-creates the page if it doesn't exist
 *
 * @return string URL or empty string if creation fails
 */
function theme_get_comparison_page_url() {
	// First, check if page already exists
	$args = array(
		'post_type'      => 'page',
		'posts_per_page' => 1,
		'meta_query'     => array(
			array(
				'key'   => '_wp_page_template',
				'value' => 'comparison.php',
				'compare' => '=',
			),
		),
		'fields'         => 'ids',
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		$page_id = $query->posts[0];
		return get_permalink( $page_id );
	}

	// Auto-create the comparison page if it doesn't exist
	return theme_auto_create_comparison_page();
}

/**
 * Auto-create comparison page if it doesn't exist
 *
 * @return string URL of the comparison page or empty string
 */
function theme_auto_create_comparison_page() {
	// Check if page already exists by slug
	$existing_page = get_page_by_path( 'comparison', OBJECT, 'page' );
	
	if ( $existing_page ) {
		// Update template if it exists
		update_post_meta( $existing_page->ID, '_wp_page_template', 'comparison.php' );
		return get_permalink( $existing_page->ID );
	}

	// Create the page
	$page_id = wp_insert_post( array(
		'post_title'     => __( 'Product Comparison', 'starter-theme' ),
		'post_name'      => 'comparison',
		'post_content'   => '[comparison-page]',
		'post_type'      => 'page',
		'post_status'    => 'publish',
		'post_author'    => get_current_user_id() ? get_current_user_id() : 1,
		'comment_status' => 'closed',
		'ping_status'    => 'closed',
	) );

	if ( is_wp_error( $page_id ) || ! $page_id ) {
		return '';
	}

	// Set the template
	update_post_meta( $page_id, '_wp_page_template', 'comparison.php' );

	return get_permalink( $page_id );
}

/**
 * AJAX: Add product to comparison
 */
function theme_ajax_add_to_comparison() {
	check_ajax_referer( 'theme_comparison_nonce', 'nonce' );

	$product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;

	if ( ! $product_id || ! wc_get_product( $product_id ) ) {
		wp_send_json_error(
			array(
				'message' => __( 'Invalid product', 'starter-theme' ),
			)
		);
	}

	$comparison = theme_get_comparison();
	$max_items = apply_filters( 'theme_comparison_max_items', 6 );

	if ( count( $comparison ) >= $max_items ) {
		wp_send_json_error(
			array(
				'message' => sprintf( __( 'Maximum %d products allowed', 'starter-theme' ), $max_items ),
			)
		);
	}

	if ( in_array( $product_id, $comparison ) ) {
		wp_send_json_error(
			array(
				'message' => __( 'Product already in comparison', 'starter-theme' ),
			)
		);
	}

	$comparison[] = $product_id;
	theme_set_comparison( $comparison );

	wp_send_json_success(
		array(
			'message' => __( 'Product added to comparison', 'starter-theme' ),
			'count' => count( $comparison ),
			'url' => theme_get_comparison_page_url(),
		)
	);
}
add_action( 'wp_ajax_theme_add_to_comparison', 'theme_ajax_add_to_comparison' );
add_action( 'wp_ajax_nopriv_theme_add_to_comparison', 'theme_ajax_add_to_comparison' );

/**
 * AJAX: Remove product from comparison
 */
function theme_ajax_remove_from_comparison() {
	check_ajax_referer( 'theme_comparison_nonce', 'nonce' );

	$product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;

	if ( ! $product_id ) {
		wp_send_json_error(
			array(
				'message' => __( 'Invalid product', 'starter-theme' ),
			)
		);
	}

	$comparison = theme_get_comparison();
	$comparison = array_filter( $comparison, function( $id ) use ( $product_id ) {
		return $id !== $product_id;
	});
	$comparison = array_values( $comparison ); // Reindex array

	theme_set_comparison( $comparison );

	wp_send_json_success(
		array(
			'message' => __( 'Product removed from comparison', 'starter-theme' ),
			'count' => count( $comparison ),
		)
	);
}
add_action( 'wp_ajax_theme_remove_from_comparison', 'theme_ajax_remove_from_comparison' );
add_action( 'wp_ajax_nopriv_theme_remove_from_comparison', 'theme_ajax_remove_from_comparison' );

/**
 * AJAX: Get comparison data
 */
function theme_ajax_get_comparison() {
	check_ajax_referer( 'theme_comparison_nonce', 'nonce' );

	$comparison = theme_get_comparison();
	$products = array();

	foreach ( $comparison as $product_id ) {
		$product = wc_get_product( $product_id );
		if ( $product ) {
			$products[] = array(
				'product_id' => $product->get_id(),
				'name' => $product->get_name(),
				'price' => $product->get_price(),
				'image' => $product->get_image(),
			);
		}
	}

	wp_send_json_success(
		array(
			'products' => $products,
			'count' => count( $comparison ),
		)
	);
}
add_action( 'wp_ajax_theme_get_comparison', 'theme_ajax_get_comparison' );
add_action( 'wp_ajax_nopriv_theme_get_comparison', 'theme_ajax_get_comparison' );

/**
 * AJAX: Clear comparison
 */
function theme_ajax_clear_comparison() {
	check_ajax_referer( 'theme_comparison_nonce', 'nonce' );

	theme_set_comparison( array() );

	wp_send_json_success(
		array(
			'message' => __( 'Comparison cleared', 'starter-theme' ),
		)
	);
}
add_action( 'wp_ajax_theme_clear_comparison', 'theme_ajax_clear_comparison' );
add_action( 'wp_ajax_nopriv_theme_clear_comparison', 'theme_ajax_clear_comparison' );

/**
 * Add comparison button to product loop (shop & category pages)
 */
function theme_add_comparison_button() {
	if ( ! class_exists( 'WooCommerce' ) || ! ( is_shop() || is_product_taxonomy() ) ) {
		return;
	}

	global $product;

	if ( ! $product ) {
		return;
	}

	$comparison = theme_get_comparison();
	$is_in_comparison = in_array( $product->get_id(), $comparison );

	?>
	<button class="add-to-comparison-btn" 
		data-product-id="<?php echo esc_attr( $product->get_id() ); ?>"
		data-product-title="<?php echo esc_attr( $product->get_name() ); ?>"
		title="<?php echo esc_attr( $is_in_comparison ? __( 'Remove from comparison', 'starter-theme' ) : __( 'Add to comparison', 'starter-theme' ) ); ?>"
		<?php echo $is_in_comparison ? 'class="in-comparison"' : ''; ?>>
		<i class="icon-scale"></i> <?php echo $is_in_comparison ? __( 'In Comparison', 'starter-theme' ) : __( 'Compare', 'starter-theme' ); ?>
	</button>
	<?php
}
add_action( 'woocommerce_after_shop_loop_item', 'theme_add_comparison_button', 15 );

/**
 * Add comparison count badge to header
 */
function theme_add_comparison_badge() {
	$comparison = theme_get_comparison();
	$count = count( $comparison );
	
	if ( $count > 0 || ! is_admin() ) {
		?>
		<div class="comparison-badge-wrapper" style="position: relative; display: inline-block;">
			<a href="<?php echo esc_url( theme_get_comparison_page_url() ); ?>" class="comparison-link">
				<i class="icon-scale"></i>
				<span class="comparison-count <?php echo $count === 0 ? 'hidden' : ''; ?>"><?php echo esc_html( $count ); ?></span>
			</a>
		</div>
		<?php
	}
}
add_action( 'theme_header_icons', 'theme_add_comparison_badge' );

/**
 * Filter to get product specification for comparison
 *
 * Example: Add more specifications as needed
 */
add_filter( 'theme_product_comparison_specs', function( $specs, $product ) {
	// Add custom attributes/specifications as needed
	$specs['Weight'] = $product->get_attribute( 'pa_weight' ) ?: '—';
	$specs['Dimensions'] = $product->get_attribute( 'pa_dimensions' ) ?: '—';

	return $specs;
}, 10, 2 );
