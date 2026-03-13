# WooCommerce Integration Guide

⚠️ **This guide applies only if the WooCommerce plugin is active.**

The theme works normally without WooCommerce. This integration is completely optional.

**Complete guide to using WooCommerce with the Starter Theme**

This guide explains how WooCommerce is integrated into your theme following the same patterns and architecture as the rest of your site.

---

## Overview

**When WooCommerce plugin is active**, it is fully integrated with:

- **Timber templating** for shop and product pages
- **Theme configuration** system
- **BEM CSS** styling
- **Custom components** for products
- **Twig filters** for product data
- **ACF integration** support

All WooCommerce pages can be rendered using Timber/Twig instead of default WooCommerce PHP templates.

**When WooCommerce plugin is not active**, all shop templates are ignored and there's zero overhead.

---

## Configuration

### Auto-Detection

The theme automatically detects WooCommerce:

1. Check if `class_exists('WooCommerce')`
2. If true, load `src/woocommerce-integration.php`
3. If false, skip WooCommerce integration entirely

**No configuration needed.** Install and activate WooCommerce plugin, that's it.

### Performance

When WooCommerce is not active:

- `src/woocommerce-integration.php` is not loaded
- Zero filters or hooks are registered
- Zero performance impact
- Theme runs at full speed

---

## Templates

### Shop Page

**Template:** `templates/shop.twig`

Displays all products in a grid with:

- Product cards
- Sorting toolbar
- Pagination
- Optional sidebar with filters

**Usage:**

```twig
{% include "components/product-card.twig" with {
  product: product
} %}
```

### Single Product Page

**Template:** `templates/single-product.twig`

Displays a single product with:

- Product gallery (main image + thumbnails)
- Price and sale badge
- Short description
- Rating
- Add to cart form
- Product metadata (SKU, categories, tags)
- Related products
- Product tabs (description, reviews, etc.)

**Example accessing product data:**

```twig
<h1>{{ product.get_title() }}</h1>
<p>{{ product.get_short_description()|raw }}</p>
<div>{{ product.get_price_html()|raw }}</div>
```

---

## Components

### Product Card Component

**File:** `templates/components/product-card.twig`

Displays a product in grid/list view.

**Parameters:**

- `product` - WooCommerce product object

**Example:**

```twig
{% include "components/product-card.twig" with {
  product: product
} %}
```

**Features:**

- Product image with hover zoom
- Sale badge
- Price (with sale price if applicable)
- Rating (if available)
- Short description
- View product button

**Styles:** `css/inc/components/product-card.scss` (BEM)

### Cart Summary Component

**File:** `templates/components/cart-summary.twig`

Displays cart in header with count and total.

**Example:**

```twig
{% include "components/cart-summary.twig" %}
```

**Features:**

- Cart count badge
- Cart total
- Link to cart page
- Responsive design

**Styles:** `css/inc/components/cart-summary.scss` (BEM)

---

## Styling

### BEM Structure

All WooCommerce styles follow BEM naming:

- `.shop` - Shop page wrapper
- `.shop__header` - Shop header section
- `.shop__products` - Product grid
- `.product-card` - Individual product card
- `.product-single` - Single product page
- `.cart-summary` - Cart component

### SCSS Files

```
css/inc/
├── components/
│   ├── product-card.scss    # Product card component
│   └── cart-summary.scss    # Cart summary component
├── global/
│   ├── shop.scss           # Shop page styles
│   └── product-single.scss # Single product page
```

### Customizing Styles

Edit SCSS files directly. They use theme variables:

```scss
// From css/inc/base/vars.scss
$primary: #0066cc;
$white: #ffffff;
$border-color: #e0e0e0;
$spacing-3: 1rem;
```

Compile with:

```bash
npm run sass
```

---

## PHP Integration

### WooCommerce Integration File

**File:** `src/woocommerce-integration.php`

Handles:

- Theme support setup
- Default style removal
- Context data for Twig
- Twig filters
- Hook management

**Loaded in:** `functions.php`

### Functions

#### Add WooCommerce to Context

WooCommerce data is automatically added to Timber context:

```twig
{{ woocommerce.cart_url }}
{{ woocommerce.checkout_url }}
{{ woocommerce.myaccount_url }}
{{ woocommerce.shop_url }}
{{ woocommerce.cart_count }}
{{ woocommerce.cart_total }}
```

**In PHP:**

```php
$context = \Timber\Timber::context();
echo $context['woocommerce']['cart_count'];
```

---

## Twig Filters

Custom Twig filters for WooCommerce:

### Price Filter

Formats a number as price with currency.

**Usage:**

```twig
{{ 99.99|wc_price }}
{# Output: <span class="woocommerce-Price-amount"><span class="woocommerce-Price-currencySymbol">$</span>99.99</span> #}
```

### Product Image Filter

Gets product image by ID or product object.

**Usage:**

```twig
{{ product|wc_product_image('woocommerce_thumbnail') }}
{{ product_id|wc_product_image }}
```

### Is On Sale Filter

Checks if product is on sale.

**Usage:**

```twig
{% if product|wc_is_on_sale %}
  <span class="sale-badge">On Sale!</span>
{% endif %}
```

---

## 📊 Product Data in Templates

Access product data with methods:

```twig
{# Basic info #}
{{ product.get_title() }}
{{ product.get_permalink() }}
{{ product.get_id() }}

{# Images #}
{{ product.get_image('woocommerce_thumbnail')|raw }}
{{ product.get_gallery_image_ids() }}

{# Pricing #}
{{ product.get_price_html()|raw }}
{{ product.get_regular_price() }}
{{ product.get_sale_price() }}

{# Details #}
{{ product.get_short_description()|raw }}
{{ product.get_description()|raw }}
{{ product.get_sku() }}
{{ product.get_stock_status() }}
{{ product.get_stock_quantity() }}

{# Ratings #}
{{ product.get_average_rating() }}
{{ product.get_rating_count() }}
{{ product.get_rating_html()|raw }}

{# Categories & Tags #}
{{ product.get_categories() }}
{{ product.get_tags() }}

{# Sale info #}
{{ product|wc_is_on_sale }}
{{ product.get_date_on_sale_from() }}
{{ product.get_date_on_sale_to() }}
```

---

## 🔗 Common Use Cases

### Display Product in Custom Loop

```php
// Get products
$args = array(
    'post_type' => 'product',
    'posts_per_page' => 12,
    'orderby' => 'date',
    'order' => 'DESC'
);
$products = new WP_Query( $args );

$context = Timber::context();
$context['products'] = $products->get_posts();

Timber::render( 'shop.twig', $context );
```

### Add to Cart Form

WooCommerce handles this via hooks. Just use the action:

```twig
<div class="product-cart">
  {% do action('woocommerce_single_product_add_to_cart') %}
</div>
```

### Product Reviews

```twig
<div class="product-reviews">
  {% do action('woocommerce_product_reviews', product.get_id() ) %}
</div>
```

### Filter Products by Category

```twig
{# In a custom template #}
{% set cat = 'electronics' %}
{% for product in posts %}
  {% if product.has_category(cat) %}
    {% include "components/product-card.twig" with {
      product: product
    } %}
  {% endif %}
{% endfor %}
```

---

## Cart & Checkout

### Cart Page

WooCommerce provides default cart templates. You can create custom Twig templates and render WooCommerce hooks inside them if desired:

```
templates/cart.twig
```

Access cart data:

```twig
{% set cart_items = woocommerce.cart_items %}
{% for item in cart_items %}
  <p>{{ item.get_name() }} x {{ item.quantity }}</p>
{% endfor %}
```

### Checkout Page

Create custom checkout template:

```
templates/checkout.twig
```

Use WooCommerce hooks:

```twig
{% do action('woocommerce_before_checkout_form') %}
{% do action('woocommerce_checkout_process') %}
{% do action('woocommerce_after_checkout_form') %}
```

---

## 🎨 Customizing Product Cards

Edit component:

```
templates/components/product-card.twig
```

Example: Add rating stars:

```twig
<div class="product-card__rating">
  {% if product.get_average_rating() > 0 %}
    {{ product.get_rating_html()|raw }}
  {% endif %}
</div>
```

---

## Debugging

### Display Product Object

```twig
<pre>{{ dump(product) }}</pre>
```

### Check Available Methods

```php
$product = wc_get_product( $product_id );
var_dump( get_class_methods( $product ) );
```

### Debug Hooks

Add to `functions.php`:

```php
add_action( 'woocommerce_before_main_content', function() {
    if ( WP_DEBUG ) {
        error_log( 'WooCommerce before main content hook' );
    }
});
```

---

## 📝 Common Issues & Solutions

### Default Styles Still Showing

**Solution:** Ensure `remove_defaults` is `true` in config:

```php
'woocommerce' => array(
    'remove_defaults' => true,
),
```

### Products Not Displaying

**Check:**

1. WooCommerce plugin is activated
2. Products are published and not in trash
3. Template files exist (`shop.twig`, `single-product.twig`)
4. Permalink structure is correct

### Cart Not Updating

**Solution:** Check if `disable_cart_fragments` should be `false`:

```php
'disable_cart_fragments' => false, // If you need AJAX cart updates
```

### Custom Fields Not Showing

Use ACF filters in template:

```twig
{{ product.get_field('field_name') }}
```

---

## 🚀 Next Steps

1. **Activate WooCommerce plugin** in WordPress admin
2. **Create products** with images, prices, descriptions
3. **Customize templates** in `templates/` directory
4. **Update styles** in `css/inc/` files
5. **Add ACF fields** to products for extra data
6. **Test cart/checkout** flow
7. **Compile SCSS** with `npm run sass`

---

## 📚 Resources

- [WooCommerce Developer Docs](https://developer.woocommerce.com/)
- [WooCommerce Product Class](https://github.com/woocommerce/woocommerce/wiki/Product-Class)
- [Timber Docs](https://timber.github.io/docs/)
- [Theme Architecture](./ARCHITECTURE.md)
- [Developer Guide](./DEVELOPER-GUIDE.md)

---

_WooCommerce integration follows the same professional patterns and standards as the rest of your theme._
