# Optional Features

This theme supports optional plugins and features. The theme automatically detects them and enables support when available.

## WooCommerce (Optional E-Commerce)

The theme automatically detects WooCommerce and enables integration when the plugin is active. No configuration needed.

**When Active:**

- Templates: `templates/shop.twig`, `templates/single-product.twig`
- Components: Product card and cart summary
- Styles: Shop and product styling
- Twig filters for pricing, images, and product data

**When Inactive:**

- Zero runtime overhead
- Theme functions perfectly without any errors
- Shop templates are never used

**Installation:** Install the WooCommerce plugin and activate. That's it!

See [WOOCOMMERCE-GUIDE.md](./WOOCOMMERCE-GUIDE.md) for complete documentation and [WOOCOMMERCE-QUICK-REF.md](./WOOCOMMERCE-QUICK-REF.md) for quick lookups.

### How Auto-Detection Works

**Theme Initialization:**

```
functions.php
├─ Check: is WooCommerce/woocommerce.php plugin active?
├─ YES → Load src/woocommerce-integration.php
│  └─ Register hooks, templates, and filters
│
└─ NO → Skip integration entirely
   └─ Zero runtime overhead
```

The theme uses:

```php
if ( class_exists( 'WooCommerce' ) ) {
    // Plugin is active
}
```

This is preferred over checking active_plugins option because it's simpler, avoids database lookups, and works automatically with multisite.

---

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
   - Styles in `css/inc/integrations/`
   - Components in `templates/components/plugin-name/`

5. **Document integration**
   Create `PLUGIN-NAME-GUIDE.md` following your existing documentation pattern

**Example integrations using this pattern:**

- WooCommerce (e-commerce)
- Advanced Custom Fields (custom field management)
- Booking System (appointment scheduling)
- Membership Plugin (user management)

Each integration loads only when its plugin is active, maintaining theme performance and simplicity.
