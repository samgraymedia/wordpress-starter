# Optional Features

This theme supports optional plugins and features. The theme automatically detects them and enables support when available.

## WooCommerce (Optional E-Commerce)

### Status

- **Active**: Theme auto-detects with zero configuration needed
- **Inactive**: Theme works perfectly without any errors or performance impact
- **Default**: Not required (theme focuses on content sites by default)

### How It Works

**Theme Initialization:**

```
functions.php
├─ Check: is WooCommerce/woocommerce.php plugin in active_plugins?
├─ YES → Load src/woocommerce-integration.php
│  ├─ Register hooks for shop/product pages
│  ├─ Add Timber templates for WooCommerce
│  ├─ Add Twig filters (is_on_sale, product_image, price)
│  └─ Theme provides product components
│
└─ NO → Skip integration entirely
   ├─ Zero hooks registered
   ├─ Zero functions run
   ├─ Zero performance impact
   └─ Theme continues normally
```

### Installation

#### To Use WooCommerce

1. Install WooCommerce plugin via WordPress admin
2. Activate it
3. Theme automatically enables support
4. Features:
   - Shop page: `/shop` (uses `templates/shop.twig`)
   - Product pages: individual product (uses Timber templates)
   - Product card component: `templates/components/product-card.twig`
   - Twig filters: `is_on_sale`, `product_image`, `price`
   - Styling: included in `style.css`

#### To Disable WooCommerce

1. Deactivate WooCommerce plugin
2. Theme continues functioning normally
3. Shop templates exist but are never used
4. Configuration in `src/config.php` is ignored
5. Zero performance overhead

### Features Provided

When WooCommerce is active:

**Templates:**

- `templates/shop.twig` - Shop listing with products grid
- `templates/single-product.twig` - Individual product page (inherits from index.php routing)
- `templates/components/product-card.twig` - Reusable product card

**Twig Filters:**

```twig
{{ product|is_on_sale }}           {# Check if product is on sale #}
{{ product|product_image('size') }} {# Get product image #}
{{ price|price }}                  {# Format price with currency #}
```

**Styling:**

- BEM CSS for shop pages
- BEM CSS for product cards
- Bootstrap 5 integration

**Configuration:**

```php
// src/config.php - only used when WooCommerce is active
'woocommerce' => array(
    'remove_defaults'  => true, // Remove WooCommerce default styles
    'disable_cart_fragments' => true, // Disable AJAX cart for performance
),
```

### File Structure

```
starter-theme/
├── src/
│   ├── woocommerce-integration.php  {# Only loaded if WooCommerce plugin is active #}
│   └── config.php                   {# WooCommerce section only used when active #}
│
├── templates/
│   ├── shop.twig                    {# Only used if WooCommerce active #}
│   ├── single-product.twig          {# Only used if WooCommerce active #}
│   └── components/product-card.twig {# Only used if WooCommerce active #}
│
├── scss/inc/global/
│   ├── shop.scss                    {# Compiled regardless; styles only load when active #}
│   └── product-single.scss          {# Compiled regardless; styles only load when active #}
│
└── Documentation/
    ├── WOOCOMMERCE-GUIDE.md         {# Complete WooCommerce integration guide #}
    └── WOOCOMMERCE-QUICK-REF.md     {# Quick lookup for WooCommerce tasks #}
```

### Detection Method

The theme uses this detection logic:

```php
private function is_woocommerce_active(): bool {
    // Check if WooCommerce is in active plugins list
    return in_array(
        'woocommerce/woocommerce.php',
        get_option( 'active_plugins', array() ),
        true
    ) || (
        // Also check for multisite active plugins
        is_multisite() && array_key_exists(
            'woocommerce/woocommerce.php',
            get_site_option( 'active_sitewide_plugins', array() )
        )
    );
}
```

### Development

**Add New WooCommerce Feature:**

1. Add template or component to `templates/`
2. Add style to `scss/inc/global/` or `scss/inc/components/`
3. Add filter/hook in `src/woocommerce-integration.php`
4. All wrapped in `class_exists('WooCommerce')` check

**Example:**

```php
// In src/woocommerce-integration.php
function theme_custom_woocommerce_feature(): void {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }

    // Your WooCommerce feature here
}
add_action( 'wp', 'theme_custom_woocommerce_feature' );
```

### Troubleshooting

**Shop page shows 404 error:**

- Make sure WooCommerce is activated
- Check Settings > Permalinks (try resaving)
- Shop page should be created automatically

**Product images not showing:**

- Verify WooCommerce thumbnails are set
- Check image sizes registered (verify in config.php)
- Clear image cache if using cache plugin

**Cart not updating:**

- Disable cache plugin temporarily to test
- Verify `disable_cart_fragments` setting in config.php

**Styling issues:**

- Check `scss/inc/global/shop.scss` and `product-single.scss`
- Run `npm run sass` to compile
- Clear browser cache

## Future Optional Features

The theme is designed to support additional optional plugins following this same pattern:

- Advanced Custom Fields Pro (currently required, could be optional)
- Custom e-commerce systems
- Booking/appointment plugins
- Membership plugins

All follow the same auto-detection pattern with zero overhead when not active.
