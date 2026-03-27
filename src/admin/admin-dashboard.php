<?php
/**
 * Admin Dashboard Page
 *
 * Custom admin dashboard with sales metrics, product performance,
 * and analytics overview.
 *
 * @package Starter_Theme
 * @subpackage Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register admin dashboard page
 *
 * @return void
 */
function theme_register_dashboard_page() {
	add_menu_page(
		__( 'Theme Dashboard', 'starter-theme-1.x' ),
		__( 'Theme Dashboard', 'starter-theme-1.x' ),
		'manage_options',
		'theme-dashboard',
		'theme_render_dashboard_page',
		'dashicons-dashboard',
		2
	);
}
add_action( 'admin_menu', 'theme_register_dashboard_page' );

/**
 * Enqueue scripts and styles for dashboard page
 *
 * @return void
 */
function theme_enqueue_dashboard_assets( $hook ) {
	if ( 'toplevel_page_theme-dashboard' !== $hook ) {
		return;
	}

	wp_enqueue_style( 'theme-dashboard', get_template_directory_uri() . '/dist/admin-dashboard.css', array(), '1.0', 'all' );
	wp_enqueue_script( 'theme-dashboard-chart', 'https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js', array(), '3.9.1', true );
	wp_enqueue_script( 'theme-dashboard', get_template_directory_uri() . '/dist/admin-dashboard.js', array( 'theme-dashboard-chart', 'wp-api-fetch' ), '1.0', true );

	// Pass data to JavaScript
	wp_localize_script( 'theme-dashboard', 'themeDashboard', array(
		'nonce'      => wp_create_nonce( 'theme-dashboard-nonce' ),
		'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
		'restUrl'   => rest_url(),
		'wooActive' => class_exists( 'WooCommerce' ) ? true : false,
	) );
}
add_action( 'admin_enqueue_scripts', 'theme_enqueue_dashboard_assets' );

/**
 * Render dashboard page
 *
 * @return void
 */
function theme_render_dashboard_page() {
	?>
	<div class="wrap theme-dashboard-wrap">
		<h1><?php esc_html_e( 'Theme Dashboard', 'starter-theme-1.x' ); ?></h1>
		
		<div class="theme-dashboard-container">
			<!-- Stats Row -->
			<div class="theme-dashboard-stats">
				<?php theme_render_dashboard_stat_box( 'site-visitors', __( 'Site Visitors', 'starter-theme-1.x' ), theme_get_visitor_count() ); ?>
				<?php theme_render_dashboard_stat_box( 'total-posts', __( 'Total Posts', 'starter-theme-1.x' ), wp_count_posts()->publish ); ?>
				
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<?php theme_render_dashboard_stat_box( 'total-products', __( 'Total Products', 'starter-theme-1.x' ), wp_count_posts( 'product' )->publish ); ?>
					<?php theme_render_dashboard_stat_box( 'total-orders', __( 'Total Orders', 'starter-theme-1.x' ), theme_get_total_orders() ); ?>
				<?php endif; ?>
			</div>

			<!-- Charts Row -->
			<div class="theme-dashboard-charts">
				<div class="theme-dashboard-card">
					<h2><?php esc_html_e( 'Sales Overview (Last 30 Days)', 'starter-theme-1.x' ); ?></h2>
					<canvas id="salesChart"></canvas>
				</div>

				<div class="theme-dashboard-card">
					<h2><?php esc_html_e( 'Top Products', 'starter-theme-1.x' ); ?></h2>
					<div id="topProductsContainer">
						<?php theme_render_top_products(); ?>
					</div>
				</div>
			</div>

			<!-- Recent Orders -->
			<?php if ( function_exists( 'wc_get_orders' ) ) : ?>
			<div class="theme-dashboard-card">
				<h2><?php esc_html_e( 'Recent Orders', 'starter-theme-1.x' ); ?></h2>
				<table class="theme-dashboard-table">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Order ID', 'starter-theme-1.x' ); ?></th>
							<th><?php esc_html_e( 'Customer', 'starter-theme-1.x' ); ?></th>
							<th><?php esc_html_e( 'Total', 'starter-theme-1.x' ); ?></th>
							<th><?php esc_html_e( 'Status', 'starter-theme-1.x' ); ?></th>
							<th><?php esc_html_e( 'Date', 'starter-theme-1.x' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php theme_render_recent_orders(); ?>
					</tbody>
				</table>
			</div>
			<?php endif; ?>

			<!-- Performance Metrics -->
			<div class="theme-dashboard-card">
				<h2><?php esc_html_e( 'Performance Metrics', 'starter-theme-1.x' ); ?></h2>
				<div class="theme-dashboard-metrics">
					<div class="metric">
						<label><?php esc_html_e( 'Page Load Time', 'starter-theme-1.x' ); ?></label>
						<span id="pageLoadTime">–</span>
					</div>
					<div class="metric">
						<label><?php esc_html_e( 'Database Queries', 'starter-theme-1.x' ); ?></label>
						<span id="dbQueries"><?php echo esc_html( get_num_queries() ); ?></span>
					</div>
					<div class="metric">
						<label><?php esc_html_e( 'Memory Usage', 'starter-theme-1.x' ); ?></label>
						<span id="memoryUsage"><?php echo esc_html( size_format( memory_get_peak_usage( true ) ) ); ?></span>
					</div>
					<div class="metric">
						<label><?php esc_html_e( 'Cache Status', 'starter-theme-1.x' ); ?></label>
						<span id="cacheStatus"><?php echo esc_html( theme_get_cache_status() ); ?></span>
					</div>
				</div>
			</div>

			<!-- Actions -->
			<div class="theme-dashboard-actions">
				<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button button-primary">
					<?php esc_html_e( 'Customize Theme', 'starter-theme-1.x' ); ?>
				</a>
				<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=page' ) ); ?>" class="button">
					<?php esc_html_e( 'Manage Pages', 'starter-theme-1.x' ); ?>
				</a>
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=product' ) ); ?>" class="button">
						<?php esc_html_e( 'Manage Products', 'starter-theme-1.x' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Render dashboard stat box
 *
 * @param string $id    Box ID
 * @param string $label Label text
 * @param mixed  $value Stat value
 * @return void
 */
function theme_render_dashboard_stat_box( $id, $label, $value ) {
	?>
	<div class="theme-dashboard-stat-box" id="stat-<?php echo esc_attr( $id ); ?>">
		<div class="stat-label"><?php echo esc_html( $label ); ?></div>
		<div class="stat-value"><?php echo esc_html( $value ); ?></div>
	</div>
	<?php
}

/**
 * Get visitor count (placeholder)
 *
 * @return int
 */
function theme_get_visitor_count() {
	$count = get_option( 'theme_visitor_count', 0 );
	return intval( $count );
}

/**
 * Get total WooCommerce orders
 *
 * @return int
 */
function theme_get_total_orders() {
	if ( ! function_exists( 'wc_get_orders' ) ) {
		return 0;
	}

	$orders = wc_get_orders( array(
		'status' => array( 'completed', 'pending', 'processing' ),
		'return' => 'ids',
		'limit'  => 1,
	) );

	// Get accurate count from wp_posts table
	$count = $GLOBALS['wpdb']->get_var( "
		SELECT COUNT(*) FROM {$GLOBALS['wpdb']->posts}
		WHERE post_type = 'shop_order'
		AND post_status IN ('wc-completed', 'wc-pending', 'wc-processing')
	" );

	return intval( $count );
}

/**
 * Render top products list
 *
 * @return void
 */
function theme_render_top_products() {
	if ( ! function_exists( 'wc_get_products' ) ) {
		echo '<p>' . esc_html__( 'WooCommerce not active', 'starter-theme-1.x' ) . '</p>';
		return;
	}

	$products = wc_get_products( array(
		'orderby' => 'title',
		'order'   => 'DESC',
		'limit'   => 5,
	) );

	if ( empty( $products ) ) {
		echo '<p>' . esc_html__( 'No products found', 'starter-theme-1.x' ) . '</p>';
		return;
	}

	echo '<ul class="theme-products-list">';
	foreach ( $products as $product ) {
		?>
		<li>
			<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
				<?php echo esc_html( $product->get_title() ); ?>
			</a>
			<span class="price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
		</li>
		<?php
	}
	echo '</ul>';
}

/**
 * Render recent orders
 *
 * @return void
 */
function theme_render_recent_orders() {
	if ( ! function_exists( 'wc_get_orders' ) ) {
		return;
	}

	$orders = wc_get_orders( array(
		'limit' => 5,
		'orderby' => 'date',
		'order'   => 'DESC',
	) );

	if ( empty( $orders ) ) {
		echo '<tr><td colspan="5">' . esc_html__( 'No orders yet', 'starter-theme-1.x' ) . '</td></tr>';
		return;
	}

	foreach ( $orders as $order ) {
		?>
		<tr>
			<td><a href="<?php echo esc_url( $order->get_edit_order_url() ); ?>">#<?php echo esc_html( $order->get_id() ); ?></a></td>
			<td><?php echo esc_html( $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ); ?></td>
			<td><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></td>
			<td><?php echo wp_kses_post( wc_get_order_status_name( $order->get_status() ) ); ?></td>
			<td><?php echo esc_html( $order->get_date_created()->format( 'M d, Y' ) ); ?></td>
		</tr>
		<?php
	}
}

/**
 * Get cache status
 *
 * @return string
 */
function theme_get_cache_status() {
	if ( defined( 'WP_CACHE' ) && WP_CACHE ) {
		return __( 'Enabled', 'starter-theme-1.x' );
	}
	return __( 'Disabled', 'starter-theme-1.x' );
}

/**
 * AJAX endpoint for sales data
 *
 * @return void
 */
function theme_dashboard_get_sales_data() {
	check_ajax_referer( 'theme-dashboard-nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => __( 'Insufficient permissions', 'starter-theme-1.x' ) ) );
	}

	if ( ! function_exists( 'wc_get_orders' ) ) {
		wp_send_json_error( array( 'message' => __( 'WooCommerce not active', 'starter-theme-1.x' ) ) );
	}

	// Get sales data for last 30 days
	$days = 30;
	$data = array(
		'labels' => array(),
		'sales'  => array(),
	);

	for ( $i = $days; $i >= 0; $i-- ) {
		$date = gmdate( 'Y-m-d', strtotime( "-{$i} days" ) );
		$date_formatted = gmdate( 'M d', strtotime( $date ) );

		$data['labels'][] = $date_formatted;

		// Get orders for this date
		$orders = wc_get_orders( array(
			'status'  => array( 'completed' ),
			'date_query' => array(
				array(
					'after'     => "{$date} 00:00:00",
					'before'    => "{$date} 23:59:59",
					'inclusive' => true,
				),
			),
			'return' => 'ids',
		) );

		$total = 0;
		foreach ( $orders as $order_id ) {
			$order = wc_get_order( $order_id );
			$total += $order->get_total();
		}

		$data['sales'][] = round( $total, 2 );
	}

	wp_send_json_success( $data );
}
add_action( 'wp_ajax_theme_dashboard_get_sales_data', 'theme_dashboard_get_sales_data' );
