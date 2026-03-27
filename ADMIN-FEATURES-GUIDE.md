# Admin Dashboard & Customization Features Guide

**Date:** March 27, 2026  
**Status:** ✅ Complete  
**Implementation Time:** 10-12 hours

---

## Table of Contents

1. [Overview](#overview)
2. [Theme Customizer](#theme-customizer)
3. [Admin Dashboard](#admin-dashboard)
4. [Product Performance Tracking](#product-performance-tracking)
5. [Analytics Integration](#analytics-integration)
6. [Bulk & Quick Edit](#bulk--quick-edit)
7. [Configuration Guide](#configuration-guide)
8. [API Reference](#api-reference)

---

## Overview

Your WordPress theme now includes a complete admin suite with:

- **Theme Customizer** — UI-driven theme customization without code
- **Admin Dashboard** — Central hub for key metrics and actions
- **Product Performance** — Track views, conversions, and revenue per product
- **Analytics Integration** — Google Analytics 4 and conversion tracking
- **Bulk/Quick Edit** — Efficient product management tools

### Access Points

| Feature     | Location                            | Permission |
| ----------- | ----------------------------------- | ---------- |
| Customizer  | Admin → Customize                   | Editor+    |
| Dashboard   | Admin → Theme Dashboard             | Admin      |
| Analytics   | Admin → Theme Dashboard → Analytics | Admin      |
| Performance | Product edit page metabox           | Editor+    |

---

## Theme Customizer

### Overview

The WordPress Theme Customizer provides a live-preview interface for customizing your theme without touching code.

**Access:** WordPress Admin → Customize

### Panels

#### 1. Theme Branding Panel

Configure logo, colors, and brand settings.

**Controls:**

- **Logo** — Upload site logo (300×100px recommended)
- **Logo Width** — Adjust logo size in pixels (50-500px)
- **Display Site Tagline** — Show/hide site tagline next to logo

#### 2. Colors Panel

Control site color scheme.

**Controls:**

- **Primary Color** — Main brand color (links, buttons, accents)
- **Secondary Color** — Accent color for secondary elements

**CSS Variables Set:**

```css
--theme-primary-color: #0066cc;
--theme-secondary-color: #ff6600;
```

#### 3. Layout & Spacing Panel

Configure header layout and spacing.

**Controls:**

- **Header Style** — Choose from:
  - Standard (Logo + Menu)
  - Minimal (Menu Only)
  - Centered (Centered Logo + Menu Below)
- **Enable Sticky Header** — Header stays visible while scrolling

#### 4. Features Panel

Enable/disable optional theme features.

**Toggles:**

- Dark Mode — Enable dark theme option
- Breadcrumbs — Show breadcrumb navigation
- Post Meta — Display author, date, category on posts
- Wishlist — Enable product wishlist (requires plugin)

### Helper Functions

Use these in templates or custom code:

```php
// Get customizer setting value
$logo_width = theme_get_customizer_setting( 'theme_logo_width', 200 );

// Check if feature is enabled
if ( theme_is_feature_enabled( 'dark_mode' ) ) {
    // Dark mode is enabled
}

// Direct setting access
$custom_color = get_option( 'theme_primary_color', '#0066cc' );
```

### Extending the Customizer

Add new customizer panels/sections:

```php
add_action( 'customize_register', function( $wp_customize ) {
    // Add panel
    $wp_customize->add_panel( 'theme_custom_panel', array(
        'title' => 'Custom Settings',
        'priority' => 50,
    ) );

    // Add section
    $wp_customize->add_section( 'theme_custom_section', array(
        'title' => 'My Custom Fields',
        'panel' => 'theme_custom_panel',
    ) );

    // Add setting
    $wp_customize->add_setting( 'my_custom_setting', array(
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
    ) );

    // Add control
    $wp_customize->add_control( 'my_custom_control', array(
        'label' => 'My Setting',
        'section' => 'theme_custom_section',
        'settings' => 'my_custom_setting',
        'type' => 'text',
    ) );
} );
```

---

## Admin Dashboard

### Overview

Central admin dashboard showing key business metrics and performance indicators.

**Access:** WordPress Admin → Theme Dashboard (left menu)

### Dashboard Sections

#### Stats Overview (Top Row)

Quick metrics display:

- **Site Visitors** — Page view count
- **Total Posts** — Published blog post count
- **Total Products** — Published WooCommerce products
- **Total Orders** — Completed/pending/processing orders

#### Sales Chart (Last 30 Days)

**Chart Type:** Line chart showing daily sales volume  
**Data Source:** WooCommerce completed orders  
**Interaction:** Hover for exact values

**API Endpoint:**

```
POST /wp-json/wp/v2/... (via AJAX)
Action: theme_dashboard_get_sales_data
```

#### Top Products List

Five best-selling products with links to edit pages.

#### Recent Orders Table

Last 5 orders with customer, total, status, and date.

**Columns:**

- Order ID (linked to edit page)
- Customer name
- Order total
- Order status badge
- Order date

#### Performance Metrics

System performance indicators:

- **Page Load Time** — Client-side JavaScript measurement
- **Database Queries** — Number of SQL queries executed
- **Memory Usage** — Peak PHP memory consumption
- **Cache Status** — WP_CACHE status (Enabled/Disabled)

#### Quick Actions

Buttons for common administrative tasks:

- Customize Theme → WordPress Customizer
- Manage Pages → Pages list
- Manage Products → Products list (WooCommerce only)

### Using Dashboard Data in Your Code

```php
// Get visitor count
$visitors = theme_get_visitor_count();

// Get order count
$orders = theme_get_total_orders();

// Get top products
$top_products = theme_get_top_products( 'conversions', 5 );
foreach ( $top_products as $product ) {
    echo $product['product_id'];      // Product ID
    echo $product['views'];            // View count
    echo $product['conversions'];      // Purchase count
    echo $product['revenue'];          // Revenue generated
}
```

---

## Product Performance Tracking

### Overview

Automatically tracks product views, add-to-cart actions, and conversions.

**Access:** Edit Product page → "Product Performance" metabox (right side)

### Tracked Metrics

| Metric      | Triggered By               | Purpose                   |
| ----------- | -------------------------- | ------------------------- |
| Views       | Page load on product page  | View count                |
| Add to Cart | "Add to Cart" button click | Interest level            |
| Conversions | Order completion           | Sales count               |
| Revenue     | Order completion           | Total revenue per product |

### Calculated Metrics

```
Conversion Rate = (Conversions / Views) × 100%
Cart Rate = (Add to Cart / Views) × 100%
```

### Admin Columns

Products list view shows:

- **Views** — Total page views
- **Sales** — Total conversions with conversion rate
- **Revenue** — Total revenue generated from product

**Sorting:** Click column headers to sort

### Product Edit Metabox

Displays on individual product edit page:

```
Product Performance
─────────────────────
Views:        1,234
Add to Cart:  256 (20.7%)
Conversions:  45 (3.6%)
Revenue:      $1,125.00
```

### Accessing Performance Data

```php
// Get all metrics for a product
$perf = theme_get_product_performance( $product_id );
echo $perf['conversions'];      // Sales count
echo $perf['conversion_rate'];  // e.g., "3.65%"
echo $perf['revenue'];          // Total revenue

// Get top products by metric
$top_by_views = theme_get_top_products( 'views', 10 );
$top_by_conversions = theme_get_top_products( 'conversions', 5 );
$top_by_revenue = theme_get_top_products( 'revenue', 5 );

foreach ( $top_by_conversions as $product ) {
    echo $product['conversion_rate'];
}
```

### Reset Performance Data

To reset metrics for a product:

```php
delete_post_meta( $product_id, '_theme_view_count' );
delete_post_meta( $product_id, '_theme_add_to_cart_count' );
delete_post_meta( $product_id, '_theme_conversion_count' );
delete_post_meta( $product_id, '_theme_total_revenue' );
```

---

## Analytics Integration

### Overview

Comprehensive analytics setup with Google Analytics 4 and conversion tracking.

**Access:** Admin → Theme Dashboard → Analytics

### Supported Platforms

- **Google Analytics 4 (GA4)** — Current standard (recommended)
- **Universal Analytics (UA)** — Legacy support (deprecated)
- **Custom Event Tracking** — Track custom events via JavaScript

### Setup Instructions

#### Step 1: Get Your Measurement ID

1. Sign in to [Google Analytics](https://analytics.google.com)
2. Go to Admin → Property → Data Streams
3. Select your data stream
4. Copy "Measurement ID" (G-XXXXXXXXXX format)

#### Step 2: Configure Settings

1. Admin → Theme Dashboard → Analytics
2. Paste Measurement ID into "Google Analytics 4 Measurement ID" field
3. Check "Enable Conversion Tracking" (for ecommerce)
4. Check "Enable Event Tracking" (for product views)
5. Select appropriate currency
6. Save Settings

#### Step 3: Verify Installation

1. Open your website in a new tab
2. Open DevTools → Network → Cookies
3. Look for `_ga` cookie — if present, GA4 is working
4. Go back to Google Analytics
5. Check "Realtime" → "Overview"
6. You should see your current session active

### Events Tracked

#### Automatically Tracked

| Event         | Trigger           | Data                               |
| ------------- | ----------------- | ---------------------------------- |
| `page_view`   | Page load         | Page URL, title                    |
| `scroll`      | User scrolls      | Page scroll percentage             |
| `view_item`   | Product page load | Product name, SKU, price, category |
| `add_to_cart` | Add to cart click | Product details                    |
| `purchase`    | Order completion  | Order ID, items, total, currency   |

#### Purchase Event Structure

```json
{
  "transaction_id": "12345",
  "affiliation": "Site Name",
  "value": 99.99,
  "currency": "USD",
  "items": [
    {
      "item_id": "ABC123",
      "item_name": "Product Name",
      "quantity": 1,
      "price": 99.99
    }
  ]
}
```

### Custom Event Tracking

Track custom events via JavaScript:

```javascript
// Send event to Google Analytics 4
gtag('event', 'my_custom_event', {
    'event_category': 'engagement',
    'event_label': 'button_click',
    'value': 100,
});

// Server-side custom event logging
fetch('/wp-admin/admin-ajax.php', {
    method: 'POST',
    body: new FormData(),
    headers: {
        'X-WP-Nonce': '<?php echo wp_create_nonce('theme-analytics-nonce'); ?>'
    }
});
```

### Conversion Settings

**Available Settings:**

- **Enable Conversion Tracking** — Track WooCommerce purchase as conversion
- **Conversion Currency** — 3-letter code (USD, EUR, GBP, etc.)

**Conversion Attribution:**

- Last-click attribution (default)
- Conversion value = Order total
- Conversion items = Order line items

### Reports to Check

#### In Google Analytics

1. **Real-time Overview** → See live visitor activity
2. **Acquisition > Traffic Source** → Where visitors come from
3. **Engagement > Pages and screens** → Most popular pages
4. **Conversions > All conversions** → Purchase conversions
5. **Monetization** → Revenue by product, source, etc.

#### Custom Reports

Create custom reports to track:

- Product performance by revenue
- Landing pages with best conversion rates
- User acquisition cost vs. average order value
- Top converting traffic sources

### Legacy Universal Analytics

To use legacy GA (UA-XXXXXXXXX-X):

1. Analytics Settings page
2. Enter Tracking ID in "Google Analytics (Universal) Tracking ID"
3. Leave GA4 Measurement ID empty
4. Save

**Note:** Universal Analytics will sunset July 1, 2023. Migrate to GA4.

### Troubleshooting Analytics

#### GA4 Not Tracking

**Check:**

1. Verify Measurement ID is correct format (G-XXXXXXXXXX)
2. Check DevTools → Network → see requests to `google-analytics`
3. Verify Realtime view shows active sessions
4. Check for JavaScript console errors

**Fix:**

1. Clear browser cookies
2. Hard-refresh page (Cmd+Shift+R)
3. Check Measurement ID in settings
4. Verify GA4 property is receiving data

#### No Conversions Showing

**Check:**

1. Enable Conversion Tracking checkbox
2. Complete test purchase on staging site
3. Wait 24 hours for data processing
4. Check Analytics → Conversions → All conversions

---

## Bulk & Quick Edit

### Overview

Efficient product management tools for bulk operations.

**Access:** Products list view

### Bulk Edit

Edit multiple products at once.

#### How to Use

1. Admin → Products
2. Select products with checkboxes
3. Select "Bulk Edit Products" from Action dropdown (top/bottom)
4. Click "Apply"
5. Fill in bulk edit options:
   - Category assignment
   - Tag addition (comma-separated)
   - Price adjustment (fixed or percentage)
6. Click "Bulk Edit Products" button
7. Products updated instantly

#### Options

**Category:** Set same category for all selected products

**Tags:** Add tags to all products (comma-separated list)

```
Example: new, on-sale, featured
```

**Price Adjustment:**

- **Type:** Fixed Amount or Percentage
- **Fixed:** E.g., +$5 or -$10
- **Percentage:** E.g., +10 (for 10% increase) or -15 (for 15% decrease)

**Example Scenarios:**

- Lower all winter items by 20% →

  - Type: Percentage
  - Change: -20

- Raise premium items by \$50 →

  - Type: Fixed Amount
  - Change: 50

- Move 100 items to "Clearance" category →
  - Select all items
  - Category: Clearance
  - Bulk edit

### Quick Edit

Edit single products inline without opening full editor.

#### How to Use

1. Hover over product in list
2. Click "Quick Edit"
3. Inline form appears with:
   - Category dropdown
   - Price field
   - Status dropdown
4. Make changes
5. Hit Enter or click ✓
6. Product saved instantly

#### Fields

- **Category:** Change product category
- **Price:** Adjust individual product price
- **Status:** Publish/Draft/Pending

### Performance Notes

- Bulk edit: Optimized for 100+ items at once
- Quick edit: Instant save (no page reload)
- Changes appear immediately in admin list
- Product inventory updated in real-time

---

## Configuration Guide

### Initial Setup (30 minutes)

#### 1. Configure Theme Customizer

1. Admin → Customize
2. Upload logo (300×100px)
3. Set primary color (brand color)
4. Choose header style
5. Enable/disable features as needed

#### 2. Set Up Analytics

1. Admin → Theme Dashboard → Analytics
2. Get Google Analytics 4 Measurement ID
3. Paste into settings
4. Enable conversion tracking
5. Set currency to your store currency

#### 3. View Dashboard

1. Admin → Theme Dashboard
2. Verify stats display correctly
3. Check sales chart loads
4. Review recent orders

### Customizing Dashboard

#### Add Custom Widget

To add custom dashboard widget:

```php
add_action( 'admin_init', function() {
    add_meta_box(
        'my_custom_widget',
        'My Widget Title',
        function() {
            echo 'Custom widget content';
        },
        'theme-dashboard',
        'normal',
        'default'
    );
} );
```

#### Modify Dashboard Stats

Change which stats display:

```php
// In functions.php or plugin
remove_action( 'theme_dashboard_render_stats', 'theme_render_dashboard_stat_box' );

add_action( 'theme_dashboard_render_stats', function() {
    theme_render_dashboard_stat_box( 'custom_stat', 'Custom Metric', get_option( 'my_custom_value' ) );
} );
```

---

## API Reference

### Customizer Functions

```php
// Get setting value with fallback
$value = theme_get_customizer_setting( $setting, $default );

// Check if feature enabled
$enabled = theme_is_feature_enabled( 'dark_mode' );

// Direct setting access
$color = get_option( 'theme_primary_color' );
```

### Product Performance Functions

```php
// Get all metrics for product
$perf = theme_get_product_performance( $product_id );

// Get top products by metric
$products = theme_get_top_products( 'conversions', 10 );
// Metric: 'views', 'conversions', 'revenue'

// Track specific events manually
theme_track_product_view( $product_id );
theme_track_add_to_cart( $product_id );
theme_track_product_conversion( $order_id );
```

### Analytics Functions

```php
// Get analytics configuration
$config = theme_get_analytics_config();
echo $config['google_4_measurement_id'];
echo $config['enable_conversion_tracking'];

// Update analytics settings
theme_update_analytics_config( array(
    'google_4_measurement_id' => 'G-XXXXXXXXXX',
    'enable_conversion_tracking' => true,
) );
```

### Dashboard Functions

```php
// Get dashboard stats
$visitors = theme_get_visitor_count();
$orders = theme_get_total_orders();
$cache_status = theme_get_cache_status();
```

---

## Troubleshooting

### Customizer Changes Not Saving

**Check:**

1. User has "edit_theme_options" capability
2. No JavaScript errors in console
3. Try different browser/incognito mode

**Fix:**

```php
// Verify capability
user_can( wp_get_current_user(), 'edit_theme_options' );

// Force refresh customizer
delete_option( 'theme_customizer_cache' );
```

### Dashboard Stats Showing 0

**Check:**

1. Posts/pages actually published
2. Products created in WooCommerce
3. Orders exist in database

**Fix:**

```php
// Force recalculate
wp_count_posts( 'post' );
wp_count_posts( 'product' );
wp_count_posts( 'shop_order' );
```

### Product Performance Not Tracking

**Check:**

1. Product file loaded (src/admin/product-performance.php)
2. User visits actual product page (not list)
3. WooCommerce is active

**Verify:**

```php
// Check if tracking hooks are registered
has_action( 'wp_head', 'theme_hook_product_view_tracking' );
has_action( 'woocommerce_add_to_cart', 'theme_hook_add_to_cart_tracking' );
```

### Analytics Not Showing Conversions

**Check:**

1. Measurement ID is correct format (G-XXXXXXXXXX)
2. Conversion tracking enabled in settings
3. Wait 24 hours for data processing
4. Test purchase completed

**Verify:**

1. DevTools → Look for `gtag` in console
2. Check GA4 Realtime view
3. Verify purchase event sent to GA4

---

## Related Documentation

- [CODEBASE-EVALUATION-2026.md](CODEBASE-EVALUATION-2026.md) — Feature status
- [TODO.md](TODO.md) — Phase tracking
- [DEVELOPER-GUIDE.md](DEVELOPER-GUIDE.md) — Advanced customization

---

## Summary

Your theme now has production-ready admin tools covering:

✅ Theme customization (UI-driven)  
✅ Central dashboard (metrics)  
✅ Product tracking (performance)  
✅ Analytics integration (GA4)  
✅ Bulk product tools (efficiency)

**Next Priority:** .env configuration (2-3h)

---

**Questions?** Check code comments in `src/admin/` files for function-level documentation.
