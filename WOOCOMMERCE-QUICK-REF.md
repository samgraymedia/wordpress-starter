# WooCommerce Quick Reference

⚠️ **This guide applies only if the WooCommerce plugin is active.**

The theme works normally without WooCommerce. These features are completely optional.

**Fast lookup for WooCommerce tasks in your theme**

---

## Key Files

| Task                 | File                                     | Purpose                       |
| -------------------- | ---------------------------------------- | ----------------------------- |
| WooCommerce settings | `src/config.php`                         | Enable/disable, remove styles |
| WooCommerce hooks    | `src/woocommerce-integration.php`        | All integrations              |
| Shop page template   | `templates/shop.twig`                    | All products display          |
| Single product       | `templates/single-product.twig`          | Single product page           |
| Product card         | `templates/components/product-card.twig` | Product in grid               |
| Cart summary         | `templates/components/cart-summary.twig` | Header cart                   |
| Shop styles          | `scss/inc/global/shop.scss`              | Shop page CSS                 |
| Product styles       | `scss/inc/global/product-single.scss`    | Single product CSS            |
| Card styles          | `scss/inc/components/product-card.scss`  | Card component CSS            |
| Full guide           | `WOOCOMMERCE-GUIDE.md`                   | Complete documentation        |

---

## Common Tasks

### Display All Products in Custom Template

```twig
{% for product in posts %}
  {% include "components/product-card.twig" with { product: product } %}
{% endfor %}
```

### Show Product Price

```twig
{{ product.get_price_html()|raw }}
```

### Check if On Sale

```twig
{% if product|wc_is_on_sale %}
  <span class="badge badge--sale">Sale!</span>
{% endif %}
```

### Get Product Image

```twig
{{ product.get_image('woocommerce_thumbnail')|raw }}
```

### Display Cart Link & Count

```twig
{% include "components/cart-summary.twig" %}
```

### Add Rating Stars

```twig
{{ product.get_rating_html()|raw }}
```

### Show SKU

```twig
{{ product.get_sku() }}
```

### List Categories

```twig
{{ product.get_categories()|raw }}
```

Or if using WooCommerce terms:

```twig
{% for term in product.get_terms('product_cat') %}
  <a href="{{ term.link }}">{{ term.name }}</a>
{% endfor %}
```

### Product Gallery

```twig
{% if product.get_gallery_image_ids() %}
  {% for image_id in product.get_gallery_image_ids() %}
    {{ image_id|wc_product_image }}
  {% endfor %}
{% endif %}
```

---

## Configuration

### Enable WooCommerce

⚠️ **Theme auto-detects WooCommerce.** No configuration needed.

1. Install the WooCommerce plugin
2. Activate it in WordPress admin
3. Theme automatically enables support

### Theme Includes

When WooCommerce is active, theme automatically:

- Removes default WooCommerce styles (uses yours instead)
- Disables cart fragment AJAX for better performance
- Renders shop/product pages with Timber templates
- Adds WooCommerce Twig filters (`wc_is_on_sale`, `wc_product_image`, `wc_price`)

When WooCommerce is not active:

- Theme functions normally without e-commerce
- Shop templates exist but won't be used
- Configuration in `src/config.php` is ignored
- No runtime overhead when WooCommerce is inactive

---

## Product Data Methods

```twig
{# Info #}
{{ product.get_title() }}
{{ product.get_id() }}
{{ product.get_permalink() }}

{# Pricing #}
{{ product.get_price_html()|raw }}
{{ product.get_regular_price() }}
{{ product.get_sale_price() }}

{# Description #}
{{ product.get_short_description()|raw }}
{{ product.get_description()|raw }}

{# Images #}
{{ product.get_image('woocommerce_thumbnail')|raw }}
{{ product.get_gallery_image_ids() }}

{# Details #}
{{ product.get_sku() }}
{{ product.get_stock_status() }}
{{ product.get_stock_quantity() }}

{# Ratings #}
{{ product.get_rating_html()|raw }}
{{ product.get_average_rating() }}

{# Taxonomy #}
{{ product.get_categories()|raw }}
{{ product.get_tags() }}

{# Sale #}
{{ product|wc_is_on_sale }}
```

---

## BEM Classes for Styling

```
.shop               # Shop page wrapper
.shop__header       # Shop header
.shop__products     # Product grid
.shop__pagination   # Pagination

.product-card       # Product card
.product-card__image
.product-card__title
.product-card__price
.product-card__excerpt

.product-single     # Single product page
.product-single__images
.product-single__details
.product-single__price
.product-single__gallery

.cart-summary       # Cart component
.cart-summary__count
.cart-summary__total
```

---

## Context Variables

Available in all templates:

```twig
{{ woocommerce.cart_url }}        # Cart page URL
{{ woocommerce.checkout_url }}    # Checkout page URL
{{ woocommerce.myaccount_url }}   # My account page URL
{{ woocommerce.shop_url }}        # Shop page URL
{{ woocommerce.cart_count }}      # Number of items in cart
{{ woocommerce.cart_total }}      # Cart total (HTML)
```

---

## Display Variations

### Product Card - Compact

```twig
<div class="product-card--compact">
  <a href="{{ product.get_permalink() }}">
    {{ product.get_image('woocommerce_thumbnail')|raw }}
  </a>
  <h4>{{ product.get_title() }}</h4>
  <p class="price">{{ product.get_price_html()|raw }}</p>
</div>
```

### Product Card - With Description

```twig
{% include "components/product-card.twig" %}
```

### Product Grid

```twig
<div class="shop__products">
  {% for product in products %}
    {% include "components/product-card.twig" with { product: product } %}
  {% endfor %}
</div>
```

---

## Performance Tips

1. **Use image sizes** - Always specify WooCommerce image sizes:

   ```twig
   {{ product.get_image('woocommerce_thumbnail')|raw }}
   ```

2. **Cache queries** - Use WordPress transients for complex queries

3. **Lazy load images** - Use WordPress lazy loading (built-in)

4. **Disable AJAX cart** - Already done in config (improves speed)

5. **Compile SCSS once** - `npm run sass` before production

---

## Common Issues

**Products not showing:**

- Check WooCommerce is activated
- Verify products are published
- Clear browser cache or caching plugin

**Styles not applying:**

- Run `npm run sass` to compile
- Clear browser cache
- Check BEM class names

**Cart not updating:**

- Change `disable_cart_fragments` to `false` if needed
- Check for JavaScript errors
- Verify WooCommerce hooks

---

## Full Documentation

See [WOOCOMMERCE-GUIDE.md](./WOOCOMMERCE-GUIDE.md) for complete details.
