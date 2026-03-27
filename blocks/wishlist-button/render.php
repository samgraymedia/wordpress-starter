<?php
/**
 * Wishlist Button Block - Render Template
 *
 * @var array  $block      The block settings and attributes.
 * @var string $content    The block inner HTML (empty).
 * @var bool   $is_preview True during backend rendering, false on frontend.
 * @var int    $post_id    The post ID this block is rendering in.
 * @var array  $context    The context passed to the block.
 *
 * @package StarterTheme
 */

if ( is_admin() && ! wp_doing_ajax() ) {
	echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; border-radius: 4px; text-align: center; color: #666;">';
	echo 'Wishlist Button - Select your product below';
	echo '</div>';
	return;
}

global $product;

// Get current product
if ( is_null( $product ) ) {
	$product = wc_get_product( get_the_ID() );
}

if ( ! $product ) {
	return;
}

$product_id = $product->get_id();
$in_wishlist = theme_is_in_wishlist( $product_id );
?>

<div class="wishlist-button-wrapper">
	<button
		class="wishlist-btn <?php echo $in_wishlist ? 'in-wishlist' : ''; ?>"
		data-product-id="<?php echo esc_attr( $product_id ); ?>"
		title="<?php echo $in_wishlist ? esc_attr( __( 'Remove from wishlist', 'starter-theme-1.x' ) ) : esc_attr( __( 'Add to wishlist', 'starter-theme-1.x' ) ); ?>"
		aria-label="<?php echo $in_wishlist ? esc_attr( __( 'Remove from wishlist', 'starter-theme-1.x' ) ) : esc_attr( __( 'Add to wishlist', 'starter-theme-1.x' ) ); ?>"
		aria-pressed="<?php echo $in_wishlist ? 'true' : 'false'; ?>"
	>
		<svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
			<path d="M10 18.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-6.55 8.53L10 18.35z" />
		</svg>
		<span class="wishlist-btn__text">
			<?php echo $in_wishlist ? esc_html__( 'In Wishlist', 'starter-theme-1.x' ) : esc_html__( 'Add to Wishlist', 'starter-theme-1.x' ); ?>
		</span>
	</button>
</div>

<style>
	.wishlist-button-wrapper {
		display: inline-block;
	}

	.wishlist-btn {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 8px 16px;
		background: none;
		border: 2px solid #ccc;
		border-radius: 4px;
		cursor: pointer;
		color: #333;
		font-size: 14px;
		font-weight: 500;
		transition: all 0.3s ease;
		position: relative;
		min-height: 40px;
	}

	.wishlist-btn:hover {
		border-color: #ff6b6b;
		background-color: #ffe0e0;
		color: #ff6b6b;
	}

	.wishlist-btn.in-wishlist {
		background-color: #ff6b6b;
		border-color: #ff6b6b;
		color: white;
	}

	.wishlist-btn.in-wishlist:hover {
		background-color: #ff5252;
		border-color: #ff5252;
	}

	.wishlist-btn svg {
		width: 20px;
		height: 20px;
		transition: transform 0.2s ease;
	}

	.wishlist-btn:hover svg {
		transform: scale(1.1);
	}

	.wishlist-btn.in-wishlist svg {
		fill: currentColor;
	}

	.wishlist-btn__text {
		font-weight: 600;
	}

	/* Responsive */
	@media (max-width: 768px) {
		.wishlist-btn {
			padding: 6px 12px;
			font-size: 13px;
		}

		.wishlist-btn svg {
			width: 18px;
			height: 18px;
		}
	}
</style>
