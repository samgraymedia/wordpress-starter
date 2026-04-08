<?php
/**
 * Product Performance Tracking
 *
 * Tracks product views, clicks, and conversion metrics
 *
 * @package Starter_Theme
 * @subpackage Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Track product view
 *
 * @param int $product_id Product ID
 * @return void
 */
function theme_track_product_view( $product_id ) {
	if ( ! function_exists( 'wc_get_product' ) ) {
		return;
	}

	$product = wc_get_product( $product_id );
	if ( ! $product ) {
		return;
	}

	// Increment view count
	$views = get_post_meta( $product_id, '_theme_view_count', true );
	$views = intval( $views ) + 1;
	update_post_meta( $product_id, '_theme_view_count', $views );

	// Update last viewed timestamp
	update_post_meta( $product_id, '_theme_last_viewed', current_time( 'mysql', true ) );
}

/**
 * Track product add to cart
 *
 * @param int $product_id Product ID
 * @return void
 */
function theme_track_add_to_cart( $product_id ) {
	if ( ! function_exists( 'wc_get_product' ) ) {
		return;
	}

	$adds = get_post_meta( $product_id, '_theme_add_to_cart_count', true );
	$adds = intval( $adds ) + 1;
	update_post_meta( $product_id, '_theme_add_to_cart_count', $adds );
}

/**
 * Track product purchase/conversion
 *
 * @param int $order_id WooCommerce order ID
 * @return void
 */
function theme_track_product_conversion( $order_id ) {
	if ( ! function_exists( 'wc_get_order' ) ) {
		return;
	}

	$order = wc_get_order( $order_id );
	if ( ! $order ) {
		return;
	}

	foreach ( $order->get_items() as $item ) {
		$product_id = $item->get_product_id();
		if ( ! $product_id ) {
			continue;
		}

		// Update product conversion count
		$conversions = get_post_meta( $product_id, '_theme_conversion_count', true );
		$conversions = intval( $conversions ) + intval( $item->get_quantity() );
		update_post_meta( $product_id, '_theme_conversion_count', $conversions );

		// Update revenue
		$revenue = get_post_meta( $product_id, '_theme_total_revenue', true );
		$item_total = floatval( $item->get_total() );
		$revenue = floatval( $revenue ) + $item_total;
		update_post_meta( $product_id, '_theme_total_revenue', $revenue );
	}
}

/**
 * Hook product view tracking to single product page
 *
 * @return void
 */
function theme_hook_product_view_tracking() {
	// Only track if WooCommerce is active
	if ( ! function_exists( 'is_product' ) ) {
		return;
	}

	if ( is_singular( 'product' ) || is_product() ) {
		$product_id = get_the_ID();
		theme_track_product_view( $product_id );
	}
}
add_action( 'wp_head', 'theme_hook_product_view_tracking', 1 );

/**
 * Hook add to cart tracking
 *
 * @return void
 */
function theme_hook_add_to_cart_tracking( $product_id ) {
	theme_track_add_to_cart( $product_id );
}
add_action( 'woocommerce_add_to_cart', 'theme_hook_add_to_cart_tracking', 10, 1 );

/**
 * Hook conversion/purchase tracking
 *
 * @return void
 */
function theme_hook_conversion_tracking( $order_id ) {
	theme_track_product_conversion( $order_id );
}
add_action( 'woocommerce_order_status_completed', 'theme_hook_conversion_tracking', 10, 1 );

/**
 * Get product performance stats
 *
 * @param int $product_id Product ID
 * @return array
 */
function theme_get_product_performance( $product_id ) {
	$views = intval( get_post_meta( $product_id, '_theme_view_count', true ) );
	$adds = intval( get_post_meta( $product_id, '_theme_add_to_cart_count', true ) );
	$conversions = intval( get_post_meta( $product_id, '_theme_conversion_count', true ) );
	$revenue = floatval( get_post_meta( $product_id, '_theme_total_revenue', true ) );

	$conversion_rate = $views > 0 ? round( ( $conversions / $views ) * 100, 2 ) : 0;
	$cart_rate = $views > 0 ? round( ( $adds / $views ) * 100, 2 ) : 0;

	return array(
		'product_id'      => $product_id,
		'views'           => $views,
		'add_to_cart'     => $adds,
		'conversions'     => $conversions,
		'cart_rate'       => $cart_rate . '%',
		'conversion_rate' => $conversion_rate . '%',
		'revenue'         => $revenue,
	);
}

/**
 * Get top performing products
 *
 * @param string $metric Metric to sort by (views, conversions, revenue)
 * @param int    $limit  Number of products to return
 * @return array
 */
function theme_get_top_products( $metric = 'conversions', $limit = 10 ) {
	global $wpdb;

	$meta_key = '_theme_' . $metric . '_count';
	if ( 'revenue' === $metric ) {
		$meta_key = '_theme_total_revenue';
	}

	$query = $wpdb->prepare( "
		SELECT p.ID, MAX(CAST(pm.meta_value AS DECIMAL)) as metric_value
		FROM {$wpdb->posts} p
		INNER JOIN {$wpdb->postmeta} pm ON (p.ID = pm.post_id)
		WHERE p.post_type = 'product'
		AND p.post_status = 'publish'
		AND pm.meta_key = %s
		GROUP BY p.ID
		ORDER BY metric_value DESC
		LIMIT %d
	", $meta_key, $limit );

	$results = $wpdb->get_results( $query );

	$products = array();
	foreach ( $results as $result ) {
		$products[] = theme_get_product_performance( $result->ID );
	}

	return $products;
}

/**
 * Admin column for product performance
 *
 * @return void
 */
function theme_add_product_performance_columns( $columns ) {
	// Only add if WooCommerce is active
	if ( ! function_exists( 'wc_get_product' ) ) {
		return $columns;
	}

	$columns['product_views'] = __( 'Views', 'starter-theme-1.x' );
	$columns['product_conversions'] = __( 'Sales', 'starter-theme-1.x' );
	$columns['product_revenue'] = __( 'Revenue', 'starter-theme-1.x' );
	return $columns;
}
add_filter( 'manage_product_posts_columns', 'theme_add_product_performance_columns' );

/**
 * Display product performance column values
 *
 * @param string $column Column name
 * @param int    $post_id Post ID
 * @return void
 */
function theme_render_product_performance_column( $column, $post_id ) {
	// Only render if WooCommerce is active
	if ( ! function_exists( 'wc_price' ) ) {
		return;
	}

	if ( 'product_views' === $column ) {
		$views = intval( get_post_meta( $post_id, '_theme_view_count', true ) );
		echo esc_html( $views );
	} elseif ( 'product_conversions' === $column ) {
		$conversions = intval( get_post_meta( $post_id, '_theme_conversion_count', true ) );
		echo esc_html( $conversions );
	} elseif ( 'product_revenue' === $column ) {
		$revenue = floatval( get_post_meta( $post_id, '_theme_total_revenue', true ) );
		echo wp_kses_post( wc_price( $revenue ) );
	}
}
add_action( 'manage_product_posts_custom_column', 'theme_render_product_performance_column', 10, 2 );

/**
 * Make performance columns sortable
 *
 * @param array $columns Sortable columns
 * @return array
 */
function theme_make_product_performance_sortable( $columns ) {
	// Only add if WooCommerce is active
	if ( ! function_exists( 'wc_get_product' ) ) {
		return $columns;
	}

	$columns['product_views'] = 'product_views';
	$columns['product_conversions'] = 'product_conversions';
	$columns['product_revenue'] = 'product_revenue';
	return $columns;
}
add_filter( 'manage_edit-product_sortable_columns', 'theme_make_product_performance_sortable' );

/**
 * Handle product performance column sorting
 *
 * @return void
 */
function theme_product_performance_sortable_query( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	// Only sort if WooCommerce is active
	if ( ! function_exists( 'wc_get_product' ) ) {
		return;
	}

	$orderby = $query->get( 'orderby' );

	if ( 'product_views' === $orderby ) {
		$query->set( 'meta_key', '_theme_view_count' );
		$query->set( 'orderby', 'meta_value_num' );
	} elseif ( 'product_conversions' === $orderby ) {
		$query->set( 'meta_key', '_theme_conversion_count' );
		$query->set( 'orderby', 'meta_value_num' );
	} elseif ( 'product_revenue' === $orderby ) {
		$query->set( 'meta_key', '_theme_total_revenue' );
		$query->set( 'orderby', 'meta_value_num' );
	}
}
add_action( 'pre_get_posts', 'theme_product_performance_sortable_query' );

/**
 * Add performance metabox to product edit page
 *
 * @return void
 */
function theme_add_product_performance_metabox() {
	// Only add if WooCommerce is active
	if ( ! function_exists( 'wc_get_product' ) ) {
		return;
	}

	add_meta_box(
		'theme_product_performance',
		__( 'Product Performance', 'starter-theme-1.x' ),
		'theme_render_product_performance_metabox',
		'product',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'theme_add_product_performance_metabox' );

/**
 * Render product performance metabox
 *
 * @param WP_Post $post Post object
 * @return void
 */
function theme_render_product_performance_metabox( $post ) {
	// Only render if WooCommerce is active
	if ( ! function_exists( 'wc_price' ) ) {
		return;
	}

	$perf = theme_get_product_performance( $post->ID );
	?>
	<div class="theme-performance-metabox">
		<div class="perf-row">
			<label><?php esc_html_e( 'Views:', 'starter-theme-1.x' ); ?></label>
			<strong><?php echo esc_html( $perf['views'] ); ?></strong>
		</div>
		<div class="perf-row">
			<label><?php esc_html_e( 'Add to Cart:', 'starter-theme-1.x' ); ?></label>
			<strong><?php echo esc_html( $perf['add_to_cart'] ); ?> (<?php echo esc_html( $perf['cart_rate'] ); ?>)</strong>
		</div>
		<div class="perf-row">
			<label><?php esc_html_e( 'Conversions:', 'starter-theme-1.x' ); ?></label>
			<strong><?php echo esc_html( $perf['conversions'] ); ?> (<?php echo esc_html( $perf['conversion_rate'] ); ?>)</strong>
		</div>
		<div class="perf-row">
			<label><?php esc_html_e( 'Revenue:', 'starter-theme-1.x' ); ?></label>
			<strong><?php echo wp_kses_post( wc_price( $perf['revenue'] ) ); ?></strong>
		</div>
	</div>
	<?php
}
