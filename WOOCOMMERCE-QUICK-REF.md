# WooCommerce Quick Reference

⚠️ **This guide applies only if the WooCommerce plugin is active.**

The theme works normally without WooCommerce. These features are completely optional.

**Fast lookup for WooCommerce tasks in your theme**

---

## Key Files

| Task                 | File                                     |
| -------------------- | ---------------------------------------- |
| WooCommerce settings | `src/config.php`                         |
| WooCommerce hooks    | `src/woocommerce-integration.php`        |
| Shop page template   | `templates/shop.twig`                    |
| Single product       | `templates/single-product.twig`          |
| Product card         | `templates/components/product-card.twig` |
| Cart summary         | `templates/components/cart-summary.twig` |
| Shop styles          | `css/inc/global/shop.scss`               |
| Product styles       | `css/inc/global/product-single.scss`     |
| Card styles          | `css/inc/components/product-card.scss`   |

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

⚠️ **Theme auto-detects WooCommerce.** No configuration needed—just install and activate the plugin.

See [WOOCOMMERCE-GUIDE.md](./WOOCOMMERCE-GUIDE.md) > Configuration section for details.

---

## Common Code Snippets

```twig
{{ product.get_price_html()|raw }}        {# Price with sale badge #}
{{ product.get_image('woocommerce_thumbnail')|raw }}  {# Product image #}
{{ product.get_rating_html()|raw }}       {# Star rating #}
{{ product.get_sku() }}                   {# SKU #}
{{ product|wc_is_on_sale }}               {# Check if on sale #}
```

---

## Display Variations

### In Templates

```twig
{# Product card #}
{% include "components/product-card.twig" with { product: product } %}

{# Cart summary #}
{% include "components/cart-summary.twig" %}

{# Product grid #}
<div class="shop__products">
  {% for product in products %}
    {% include "components/product-card.twig" with { product: product } %}
  {% endfor %}
</div>
```

---

## For Complete Details

See [WOOCOMMERCE-GUIDE.md](./WOOCOMMERCE-GUIDE.md) for:

- Full product data methods reference
- All BEM CSS classes
- Context variables
- Performance tips
- Troubleshooting
