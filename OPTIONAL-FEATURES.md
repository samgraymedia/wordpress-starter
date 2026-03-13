# Optional Features

This theme supports optional plugins and features. The theme automatically detects them and enables support when available.

## WooCommerce (Optional E-Commerce)

### Status

- **Active**: Theme automatically detects WooCommerce and enables integration
- **Inactive**: Theme works perfectly without any errors, with negligible performance impact
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
- `templates/single-product.twig` - Individual product page (rendered by views/single-product.php when WooCommerce posts route here)
- `templates/components/product-card.twig` - Reusable product card

**Twig Filters:**

```twig
{{ product|wc_is_on_sale }}        {# Check if product is on sale #}
{{ product|wc_product_image('size') }} {# Get product image #}
{{ price|wc_price }}               {# Format price with currency #}
```

Note: Filters use `wc_` prefix to avoid naming conflicts in larger projects.

**Styling:**

- BEM CSS for shop pages
- BEM CSS for product cards
- Bootstrap 5 integration

**Configuration:**

```php
// src/config.php - WooCommerce settings only used when plugin is active
'woocommerce' => array(
    'remove_defaults'  => true,        // Remove WooCommerce default styles
    'disable_cart_fragments' => true,  // Disable AJAX cart for performance
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
├── WOOCOMMERCE-GUIDE.md             {# Complete WooCommerce integration guide #}
└── WOOCOMMERCE-QUICK-REF.md         {# Quick lookup for WooCommerce tasks #}
```

### Detection Method

The theme uses this simple detection method:

```php
private function is_woocommerce_active(): bool {
    return class_exists( 'WooCommerce' );
}
```

This approach is preferred over checking active_plugins option because:

- **Simpler code** – WordPress provides the class when the plugin is active
- **No database lookups** – Avoids option queries
- **Works with multisite** – Handles site-wide and per-site activations automatically
- **Standard pattern** – Most WordPress themes use this method

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

## Optional Integration Pattern

The theme architecture supports adding new optional integrations following this pattern:

**Steps to add optional plugin support:**

1. **Detect plugin availability**

   ```php
   if ( class_exists( 'Plugin_Class' ) ) {
       // Plugin is active
   }
   ```

2. **Load integration file conditionally**

   ```php
   // In functions.php or setup hook
   if ( class_exists( 'Plugin_Class' ) ) {
       require_once 'src/plugin-integration.php';
   }
   ```

3. **Register hooks only inside integration file**
   Wrap all plugin-specific functionality in the conditional file. This ensures zero runtime overhead when the plugin is inactive.

4. **Provide templates and styles**

   - Twig templates in `templates/plugin-name/`
   - Styles in `scss/inc/integrations/`
   - Components in `templates/components/plugin-name/`

5. **Document integration**
   Create `PLUGIN-NAME-GUIDE.md` following your existing documentation pattern

**Example integrations using this pattern:**

- WooCommerce (e-commerce)
- Advanced Custom Fields (custom field management)
- Booking System (appointment scheduling)
- Membership Plugin (user management)

Each integration loads only when its plugin is active, maintaining theme performance and simplicity.
