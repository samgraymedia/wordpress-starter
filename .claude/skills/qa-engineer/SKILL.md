---
name: qa-engineer
description: QA expertise for the WordPress Starter Theme 1.x codebase. Use when testing, verifying, debugging, or auditing the theme's blocks, templates, SCSS, JavaScript, WooCommerce integration, performance, accessibility, or security. Covers test strategy, auto-discovery registry testing, component validation, E2E verification, visual regression, and common failure modes.
---

## Overview

This skill teaches expert-level QA for the **WordPress Starter Theme 1.x**—a modern, component-based theme architecture with Timber/Twig, ACF Gutenberg blocks, Vite, SCSS, and WooCommerce integration.

**Repository:** `/Applications/MAMP/htdocs/wordpress-starter-store`  
**Theme Location:** `/wp-content/themes/starter-theme-1.x`  
**Test Stack:** PHPUnit, Jest, Playwright, Axe-core, Lighthouse  
**Architecture:** Layered (Presentation → Application → Service → Data)

---

## QA Philosophy

1. **Test by Layer** — Unit test registries & services; integration test templates; E2E test user flows
2. **Prevent Auto-Discovery Failures** — Test registry loading and auto-discovered file validation first
3. **Verify Block Contracts** — Ensure ACF field groups match block templates and match Twig rendering
4. **Component Isolation** — Test Twig components in isolation before integration
5. **Progressive Validation** — Test from fast (unit) → medium (integration) → slow (E2E) → visual → performance
6. **Real WordPress Context** — Never mock WordPress core; run against live test database

---

## Test Categories & Strategies

### 1. AUTO-DISCOVERY REGISTRY TESTING
**What to verify:**
- Post types/taxonomies/blocks load correctly from directories
- No naming conflicts or duplicate registrations
- Field groups attach to correct post types
- Missing or malformed files cause graceful errors
- Registry runs at correct WordPress hook priority

**Test approach:**
```php
// Example: Test post type auto-discovery
public function test_blog_post_type_registered() {
    $post_types = get_post_types(['_builtin' => false], 'names');
    $this->assertContains('blog', $post_types);
}

public function test_post_type_supports_correct_features() {
    $supports = get_all_post_type_supports('blog');
    $this->assertContains('title', $supports);
    $this->assertContains('editor', $supports);
    $this->assertContains('thumbnail', $supports);
}

public function test_acf_field_group_attached_to_post_type() {
    // Verify field group is registered for this post type
    $field_group = acf_get_field_group('group_blog');
    $this->assertNotNull($field_group);
    // Check locations
    $locations = $field_group['location'];
    $this->assertTrue($this->has_location($locations, 'post_type', 'blog'));
}
```

**Common failures:**
- Registry files not named correctly (should be `{slug}.php`)
- Missing return statement or malformed array in registry file
- ACF field group key mismatch between registry and Twig template
- Taxonomy post_types mismatch causing attachment failure

---

### 2. ACF GUTENBERG BLOCK TESTING
**What to verify:**
- Block registers correctly in editor
- ACF fields display with correct UI types
- Block saves data to correct meta keys
- Twig template receives all expected field values
- Block renders correctly on frontend
- Block preview shows in editor

**Test approach:**
```php
// Test block registration
public function test_block_registers_in_gutenberg() {
    $block = WP_Block_Type_Registry::get_instance()->get_registered('acf/my-block');
    $this->assertNotNull($block);
}

// Test ACF field group for block
public function test_block_field_group_exists() {
    $field_group = acf_get_field_group('group_my_block');
    $this->assertNotNull($field_group);
    $this->assertEquals('My Block', $field_group['title']);
}

// Test field values render in Twig
public function test_block_twig_template_receives_fields() {
    $post = $this->factory->post->create();
    add_post_meta($post, 'field_my_block_title', 'Test Title');
    
    // Get block context
    $block_data = get_field('field_my_block_title', $post);
    $this->assertEquals('Test Title', $block_data);
}
```

**Playwright test for block editor:**
```typescript
test('block appears in inserter and saves correctly', async ({ page }) => {
  await page.goto('/wp-admin/post-new.php?post_type=page');
  
  // Open inserter
  await page.click('[aria-label="Toggle block inserter"]');
  
  // Search for block
  await page.fill('[placeholder="Search"]', 'my block');
  await page.click('text="My Block"');
  
  // Block should be inserted
  const block = await page.locator('[data-type="acf/my-block"]');
  await expect(block).toBeVisible();
  
  // Fill fields
  await page.fill('[data-name="title"]', 'Test Title');
  
  // Save post
  await page.click('text="Publish"');
  
  // Verify on frontend
  const pageTitle = await page.locator('h1').first();
  await expect(pageTitle).toContainText('Test Title');
});
```

**Common failures:**
- ACF field group location doesn't match block slug
- Twig template variable names don't match ACF field names
- Block doesn't appear in inserter (check registration priority)
- Field changes don't persist (meta key mismatch)

---

### 3. TWIG TEMPLATE & COMPONENT TESTING
**What to verify:**
- Component renders with all expected HTML structure
- Variables pass through correctly from PHP
- Loops and conditionals work
- Filters/escaping don't break output
- Props validation catches missing required data

**Test approach:**
```php
// Test Twig component renders correctly
public function test_button_component_renders() {
    $timber_context = [
        'text' => 'Click Me',
        'url'  => 'https://example.com',
        'modifier' => 'primary'
    ];
    
    $html = Timber::compile('components/button.twig', $timber_context);
    
    $this->assertStringContainsString('Click Me', $html);
    $this->assertStringContainsString('https://example.com', $html);
    $this->assertStringContainsString('btn--primary', $html);
}

// Test required props throw error
public function test_component_validates_required_props() {
    $timber_context = [
        'text' => 'Click Me'
        // Missing 'url' required prop
    ];
    
    $this->expectException(\Exception::class);
    Timber::compile('components/button.twig', $timber_context);
}
```

**Playwright test for component HTML:**
```typescript
test('button component has correct HTML structure', async ({ page }) => {
  await page.goto('/test-component?component=button&text=Click&url=/');
  
  const button = await page.locator('a.btn');
  await expect(button).toHaveClass(/btn--primary/);
  await expect(button).toHaveAttribute('href', '/');
});
```

**Common failures:**
- Twig variable names case-sensitive mismatch
- Escaping filters removing required HTML attributes
- Missing null checks causing "undefined" in output
- Component assumes parent context

---

### 4. JAVASCRIPT MODULE TESTING (Jest)
**What to verify:**
- Module initialization works
- Event handlers attach correctly
- DOM mutations are correct
- Module cleanup doesn't leak listeners

**Test approach:**
```javascript
// Test JavaScript module
describe('Hamburger menu', () => {
  let hamburger;
  
  beforeEach(() => {
    document.body.innerHTML = `
      <button class="hamburger" aria-label="Menu">
        <span></span>
      </button>
      <nav class="nav" hidden>
        <a href="#">Home</a>
      </nav>
    `;
  });
  
  test('toggles nav visibility on click', () => {
    hamburger = document.querySelector('.hamburger');
    const nav = document.querySelector('.nav');
    
    // Initial state
    expect(nav.hasAttribute('hidden')).toBe(true);
    
    // Click
    hamburger.click();
    expect(nav.hasAttribute('hidden')).toBe(false);
    
    // Click again
    hamburger.click();
    expect(nav.hasAttribute('hidden')).toBe(true);
  });
  
  test('updates aria-expanded attribute', () => {
    hamburger = document.querySelector('.hamburger');
    expect(hamburger.getAttribute('aria-expanded')).toBe('false');
    
    hamburger.click();
    expect(hamburger.getAttribute('aria-expanded')).toBe('true');
  });
});
```

**Common failures:**
- Module assumes specific DOM structure
- Event listeners not cleaned up (memory leaks)
- Async operations not awaited in tests
- Module doesn't work without specific CSS classes

---

### 5. SCSS/CSS TESTING
**What to verify:**
- No CSS syntax errors (valid Sass)
- No unused variables/mixins
- BEM naming consistency
- Color contrast meets WCAG AA
- Responsive breakpoints work

**Test approach:**
```bash
# Check SCSS syntax
sass --check css/style.scss

# Run CSS linter
stylelint css/**/*.scss

# Build and check file size
sass css/style.scss > css/style-compiled.css
wc -c css/style-compiled.css

# Test CSS is valid (purifycss)
purifycss 'templates/**/*.twig' 'css/style-compiled.css'
```

**Playwright test for responsive:**
```typescript
test('layout responds correctly at mobile breakpoint', async ({ page }) => {
  await page.setViewportSize({ width: 375, height: 667 }); // iPhone SE
  await page.goto('/');
  
  const nav = await page.locator('.nav');
  const hamburger = await page.locator('.hamburger');
  
  // Mobile: hamburger visible, nav hidden
  await expect(hamburger).toBeVisible();
  await expect(nav).toHaveCSS('display', 'none');
  
  // Desktop
  await page.setViewportSize({ width: 1280, height: 1024 });
  
  // Desktop: hamburger hidden, nav visible
  await expect(hamburger).toHaveCSS('display', 'none');
  await expect(nav).toHaveCSS('display', 'flex');
});
```

**Common failures:**
- Undefined Sass variables in CSS output
- Missing vendor prefixes for older browsers
- Responsive images don't load at right sizes
- Dark mode CSS variables not defined

---

### 6. ACCESSIBILITY TESTING (Axe-core)
**What to verify:**
- No color contrast violations
- All images have alt text
- Form labels properly associated
- Interactive elements keyboard accessible
- ARIA attributes correct
- Heading hierarchy valid

**Test approach:**
```typescript
import { injectAxe, checkA11y } from 'axe-playwright';

test('page has no accessibility violations', async ({ page }) => {
  await page.goto('/');
  
  // Inject axe
  await injectAxe(page);
  
  // Check entire page
  await checkA11y(page, null, {
    detailedReport: true,
    detailedReportOptions: {
      html: true
    }
  });
});

test('product card accessible', async ({ page }) => {
  await page.goto('/shop');
  
  // Check specific component
  const productCard = page.locator('[data-testid="product-card"]').first();
  await injectAxe(page);
  await checkA11y(page, '[data-testid="product-card"]');
});
```

**Manual checks:**
- Keyboard navigation: Tab through entire page
- Screen reader: Test with VoiceOver/NVDA
- Color contrast: Use Lighthouse or WebAIM
- Focus visible: Press Tab, all interactive elements should have clear focus ring

**Common failures:**
- Images missing alt text (especially in Twig loops)
- Form inputs missing `<label>` elements
- Color contrast < 4.5:1 for normal text
- `onclick` handlers on `<div>` instead of `<button>`
- Modals trap focus incorrectly

---

### 7. WooCommerce INTEGRATION TESTING
**What to verify:**
- Product queries return correct data
- Cart/checkout pages render correctly
- Hooks/filters execute in correct order
- Custom email templates render
- Payment gateway integrates
- Order management flows work

**Test approach:**
```php
// Test product query
public function test_query_products_by_category() {
    $products = wc_get_products([
        'category' => 'electronics',
        'limit' => 10
    ]);
    
    $this->assertGreaterThan(0, count($products));
    $this->assertInstanceOf('WC_Product', $products[0]);
}

// Test cart operations
public function test_add_product_to_cart() {
    WC()->cart->empty_cart();
    $product_id = $this->factory->product->create();
    
    WC()->cart->add_to_cart($product_id, 1);
    
    $cart_contents = WC()->cart->get_cart_contents();
    $this->assertCount(1, $cart_contents);
}

// Test custom email template
public function test_order_email_template_renders() {
    $order = $this->factory->order->create();
    $mailer = WC()->mailer();
    
    $email = new WC_Email_Customer_Completed_Order($order);
    $html = $email->get_content_html();
    
    $this->assertStringContainsString($order->get_order_number(), $html);
}
```

**Playwright test for checkout:**
```typescript
test('checkout flow completes successfully', async ({ page }) => {
  await page.goto('/shop');
  
  // Add to cart
  await page.click('[data-product-id="123"] .add-to-cart');
  
  // Go to cart
  await page.goto('/cart');
  await expect(page.locator('.cart-item')).toBeVisible();
  
  // Proceed to checkout
  await page.click('text="Proceed to Checkout"');
  
  // Fill billing
  await page.fill('[name="billing_first_name"]', 'John');
  await page.fill('[name="billing_last_name"]', 'Doe');
  await page.fill('[name="billing_address_1"]', '123 Main St');
  await page.fill('[name="billing_city"]', 'Springfield');
  
  // Place order
  await page.click('[name="place_order"]');
  
  // Verify order confirmation
  await expect(page).toHaveURL(/\/order-received/);
});
```

**Common failures:**
- Product images not loading on shop page
- Cart quantity updates don't persist
- Checkout form validation too strict
- Shipping calculations wrong for tax rate
- Email templates show HTML tags instead of rendered content

---

### 8. PERFORMANCE TESTING (Lighthouse)
**What to verify:**
- Page Load Time < 3s (goal: < 1.5s)
- Largest Contentful Paint < 2.5s
- Cumulative Layout Shift < 0.1
- First Input Delay < 100ms
- Image optimization (WebP, lazy loading)
- JavaScript bundle size

**Test approach:**
```bash
# Run Lighthouse via CLI
npm install -g @lhci/cli @lhci/server

# Single audit
lighthouse https://site.local --output html --output-path report.html

# Continuous monitoring (CI)
lhci autorun --config lighthouserc.json
```

**lighthouserc.json:**
```json
{
  "upload": {
    "target": "filesystem",
    "outputDir": "./lh-reports"
  },
  "assert": {
    "preset": "lighthouse:recommended",
    "assertions": {
      "first-contentful-paint": ["error", { "maxNumericValue": 2000 }],
      "largest-contentful-paint": ["error", { "maxNumericValue": 2500 }],
      "cumulative-layout-shift": ["error", { "maxNumericValue": 0.1 }],
      "speed-index": ["error", { "maxNumericValue": 3000 }]
    }
  }
}
```

**Playwright performance capture:**
```typescript
test('measure page load performance', async ({ page }) => {
  const startTime = Date.now();
  
  await page.goto('/');
  
  const metrics = await page.evaluate(() => ({
    time: performance.now(),
    memory: (performance as any).memory?.usedJSHeapSize,
    resources: performance.getEntriesByType('resource').length
  }));
  
  console.log(`Load time: ${metrics.time}ms`);
  console.log(`Resources: ${metrics.resources}`);
  expect(metrics.time).toBeLessThan(3000);
});
```

**Common failures:**
- Large unoptimized images (use WebP, lazy load)
- Render-blocking CSS/JS
- Missing image dimensions (layout shift)
- Unminified bundles
- No compression on server

---

## Testing Workflow (Priority Order)

### Priority 1: Smoke Tests (Run First)
```bash
# 1. Can the site load at all?
wp --path=/path/to/wp eval 'echo "OK";'

# 2. Are registries working?
wp --path=/path/to/wp eval 'echo count(get_post_types(["_builtin" => false]));'

# 3. Can admin page load?
# Manual: Open wp-admin, check for PHP errors
```

### Priority 2: Unit Tests (Fast)
```bash
phpunit --filter Registry      # Auto-discovery tests
phpunit --filter Block         # ACF block tests
npm test -- Hamburger          # JS module tests
```

### Priority 3: Integration Tests (Medium)
```bash
phpunit --filter Template      # Twig rendering
phpunit --filter WooCommerce   # E-commerce
```

### Priority 4: E2E Tests (Slow)
```bash
npx playwright test             # User flow tests
```

### Priority 5: Visual & Performance (Optional)
```bash
npx playwright test --update-snapshots  # Visual regression
lighthouse https://site.local          # Performance audit
```

---

## Common Failure Modes

| Failure Mode | Symptom | Root Cause | Fix |
|---|---|---|---|
| Registry doesn't load | Post type not in `get_post_types()` | File not named `{slug}.php` or missing `return` | Verify file naming and array structure |
| Block doesn't appear | Block not in inserter | ACF field group location doesn't match block slug | Check `group_` prefix matches registration |
| Twig renders `{{ undefined }}` | Variable shows in output | PHP passed wrong key or typo in template | Check variable names case-sensitive match |
| CSS not applying | Styles missing on page | Vite dev server not running or CSS not imported | Run `npm run dev` and check `@import` statements |
| JS module doesn't work | Event handlers not firing | DOM structure assumption wrong | Check `data-testid` or class names in template |
| Images missing on production | Blank image placeholders | Image paths incorrect in Twig (relative vs absolute) | Use `site.url ~ '/path/'` in Twig |
| Cart functions undefined | WooCommerce errors | Theme loaded before WooCommerce hook | Check hook priority; use `plugins_loaded` |
| Accessibility violations | Axe reports color contrast fail | CSS variables not defined or fallback missing | Add fallback colors to `[data-theme="light"]` |

---

## Test Fixtures & Factories

**Create test post with ACF fields:**
```php
$post_id = $this->factory->post->create([
    'post_type' => 'blog',
    'post_title' => 'Test Post'
]);

// Add ACF field values
update_field('featured_image', 123, $post_id);
update_field('excerpt', 'Test excerpt', $post_id);
```

**Create test WooCommerce product:**
```php
$product_id = $this->factory->product->create_simple([
    'name' => 'Test Widget',
    'regular_price' => 29.99
]);

update_post_meta($product_id, '_stock', 100);
```

**Create test user with role:**
```php
$user_id = $this->factory->user->create([
    'role' => 'administrator',
    'user_login' => 'testadmin'
]);
```

---

## Debugging Workflow

### 1. Isolate the Layer
- **Admin page broken?** → Service layer issue (config, registry)
- **Content not showing?** → Application or presentation layer
- **Styling wrong?** → Presentation layer (SCSS or Twig)
- **Interaction broken?** → JavaScript module issue

### 2. Enable Debug
```php
// In wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

// Check /wp-content/debug.log
tail -f wp-content/debug.log
```

### 3. Inspect Data
```php
// In template or test
error_log(print_r($context, true));
error_log(json_encode($timber_post->get_field('my_field')));
```

### 4. Verify Hook Execution
```php
// Check what hooked to which priority
do_action('debug_hooks', 'my_custom_hook');
```

---

## CI/CD Integration

**GitHub Actions example (.github/workflows/test.yml):**
```yaml
name: Test
on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_PASSWORD: password
          MYSQL_ROOT_PASSWORD: password
          MYSQL_DATABASE: wordpress_test
    steps:
      - uses: actions/checkout@v2
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
      
      - name: Setup Node
        uses: actions/setup-node@v3
        with:
          node-version: '18'
      
      - name: Install dependencies
        run: |
          composer install
          npm ci
      
      - name: Run unit tests
        run: npm run test:unit
      
      - name: Run integration tests
        run: npm run test:integration
      
      - name: Run E2E tests
        run: npm run test:e2e
      
      - name: Lighthouse
        run: npm run test:performance
```

---

## Quick Reference

| Test Type | Tool | Command | Time |
|---|---|---|---|
| PHP Syntax | PHPLint | `php -l file.php` | < 1s |
| Unit Tests | PHPUnit | `npm run test:unit` | 10-30s |
| SCSS Syntax | Sass | `sass --check css/` | < 1s |
| JS Tests | Jest | `npm test` | 5-15s |
| Integration | PHPUnit + Timber | `npm run test:integration` | 30-60s |
| E2E | Playwright | `npx playwright test` | 2-5m |
| A11y | Axe + Playwright | `npx playwright test --grep a11y` | 1-2m |
| Performance | Lighthouse | `lighthouse https://site.local` | 1-2m |
| All | CI Pipeline | GitHub Actions | 5-10m |

---

## Resources & Links

- [PHPUnit Documentation](https://phpunit.de/)
- [Jest Documentation](https://jestjs.io/)
- [Playwright Documentation](https://playwright.dev/)
- [Axe Accessibility](https://www.deque.com/axe/)
- [Lighthouse](https://developers.google.com/web/tools/lighthouse)
- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [WordPress Testing Handbook](https://developer.wordpress.org/plugins/testing/)
- [Timber Testing](https://timber.github.io/docs/guides/testing/)
- [WooCommerce Testing](https://github.com/woocommerce/woocommerce/wiki/Writing-Tests)
