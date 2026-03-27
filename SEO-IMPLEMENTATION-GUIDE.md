# SEO & Social Media Implementation Guide

**Date:** March 27, 2026  
**Status:** Production-Ready  
**Coverage:** Open Graph, Twitter Cards, Canonical Tags, JSON-LD Schema, Robots.txt, Sitemaps, Alt Text

---

## Table of Contents

1. [Overview](#overview)
2. [Quick Start](#quick-start)
3. [Open Graph Tags](#open-graph-tags)
4. [Twitter Cards](#twitter-cards)
5. [Canonical Tags](#canonical-tags)
6. [JSON-LD Schema Markup](#json-ld-schema-markup)
7. [Image Alt Text (ACF Integration)](#image-alt-text-acf-integration)
8. [Robots.txt Configuration](#robotstxt-configuration)
9. [XML Sitemap Setup](#xml-sitemap-setup)
10. [Implementation Checklist](#implementation-checklist)
11. [Testing & Validation](#testing--validation)

---

## Overview

### What This Solves

- 📱 **Social Sharing:** Beautiful preview cards when sharing on Facebook, Twitter, LinkedIn
- 🔍 **Search Visibility:** Rich snippets in Google results (products, articles, breadcrumbs)
- 🖼️ **Image Attribution:** Proper alt text for accessibility and SEO
- 🌐 **Duplicate Prevention:** Canonical tags prevent duplicate content penalties
- 📋 **Search Engine Crawling:** Robots.txt and sitemaps guide Google/Bing

### Impact on SEO

| Feature        | SEO Impact | Social Impact |
| -------------- | ---------- | ------------- |
| Open Graph     | Medium     | High          |
| Twitter Cards  | Low        | High          |
| Canonical Tags | **High**   | Low           |
| JSON-LD Schema | **High**   | Low           |
| Alt Text       | Medium     | Low           |
| Robots.txt     | High       | None          |
| Sitemaps       | Medium     | None          |

---

## Quick Start

### 1. Load SEO Helpers

In `functions.php`, ensure the helpers are loaded early:

```php
// Load SEO helpers
require_once __DIR__ . '/src/seo-helpers.php';

// Register SEO meta tags in head
add_action( 'wp_head', function() {
    // Open Graph tags
    theme_output_open_graph();

    // Twitter Cards
    theme_output_twitter_card( array(
        'handle' => '@YourTwitterHandle', // Set your Twitter handle
    ) );

    // Canonical tag
    theme_output_canonical();
}, 5 ); // Priority 5 ensures early output

// Organization schema (once per site)
add_action( 'wp_head', function() {
    theme_output_json_ld( theme_get_organization_schema() );
}, 5 );

// Article schema for posts
add_action( 'wp_head', function() {
    if ( is_singular( 'post' ) || is_singular( 'page' ) ) {
        theme_output_json_ld( theme_get_article_schema() );
    }
}, 5 );

// Article schema for products
add_action( 'wp_head', function() {
    if ( is_singular( 'product' ) ) {
        theme_output_json_ld( theme_get_product_schema() );
    }
}, 5 );
```

### 2. Configure Twitter Handle

Find this line in your hooks and replace with your actual Twitter handle:

```php
'handle' => '@YourTwitterHandle',
```

---

## Open Graph Tags

### Purpose

Open Graph (OG) tags control how your website appears when shared on Facebook, LinkedIn, Pinterest, and WhatsApp.

### Implements

```html
<meta property="og:title" content="Page Title" />
<meta property="og:description" content="Page description..." />
<meta property="og:image" content="https://example.com/image.jpg" />
<meta property="og:url" content="https://example.com/page" />
<meta property="og:type" content="website" />
<meta property="og:locale" content="en_US" />
<meta property="og:site_name" content="Site Name" />
```

### Usage

#### Basic Page

```php
theme_output_open_graph();
```

Automatically uses:

- Page/post title
- Site description
- Featured image (if post)
- Current page URL
- Locale from WordPress

#### Custom Social Card

```php
theme_output_open_graph( array(
    'title'       => 'Custom Title',
    'description' => 'Custom description for social sharing',
    'image'       => 'https://example.com/custom-image.jpg',
    'type'        => 'article',
) );
```

#### Product Page (WooCommerce)

```php
// Automatically detects product pages and includes price
if ( is_singular( 'product' ) ) {
    theme_output_open_graph();
    // Outputs additional tags:
    // <meta property="product:price:amount" content="29.99">
    // <meta property="product:price:currency" content="USD">
}
```

### Image Optimization

- **Recommended size:** 1200 × 630 px (optimal for all platforms)
- **Min size:** 800 × 420 px
- **Format:** JPG or PNG
- **Quality:** Optimized (~100KB max)

**How it selects images:**

1. ✅ Explicitly passed `image` parameter
2. ✅ Featured image (large size)
3. ❌ Falls back to none if unavailable

---

## Twitter Cards

### Purpose

Twitter Cards control appearance when sharing on Twitter/X. Similar to Open Graph but Twitter-specific.

### Implements

```html
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="Page Title" />
<meta name="twitter:description" content="Description..." />
<meta name="twitter:image" content="https://example.com/image.jpg" />
<meta name="twitter:creator" content="@username" />
```

### Card Types

| Type                  | Use Case                          | Image Size  |
| --------------------- | --------------------------------- | ----------- |
| `summary`             | Links with headline + small image | 120x120 min |
| `summary_large_image` | Prominent image sharing (default) | 506x506 min |
| `player`              | Video/audio embeds                | 506x506 min |

### Usage

```php
theme_output_twitter_card( array(
    'card'   => 'summary_large_image',
    'handle' => '@YourHandle',
) );
```

### Testing

Validate with [Twitter Card Validator](https://cards-dev.twitter.com/validator):

1. Copy your page URL
2. Paste into validator
3. Check preview render
4. Fix any warnings

---

## Canonical Tags

### Purpose

Canonical tags tell search engines which version of a page is the "primary" one. Prevents duplicate content penalties.

### When to Use

- ✅ Category pages with pagination → point to base category URL
- ✅ Filtered product pages → point to unfiltered version
- ✅ Syndicated content → point to original
- ✅ Mobile/desktop versions → point to desktop
- ✅ HTTPS version → point to HTTPS

### Implements

```html
<link rel="canonical" href="https://example.com/page" />
```

### Usage

```php
// Automatically uses current page URL
theme_output_canonical();

// Explicit URL
theme_output_canonical( 'https://example.com/primary-version' );
```

### Behavior

| Page Type           | Canonical Points To                   |
| ------------------- | ------------------------------------- |
| Single post         | Post permalink                        |
| Homepage            | Site home URL                         |
| Archive             | Archive base URL (removes pagination) |
| WooCommerce product | Product permalink                     |

### For Custom Scenarios

```php
// Category page with filters - point to unfiltered
if ( is_category() ) {
    $unfiltered_url = get_category_link( get_queried_object_id() );
    theme_output_canonical( $unfiltered_url );
}
```

---

## JSON-LD Schema Markup

### Purpose

JSON-LD provides structured data to search engines for rich snippets, knowledge panels, and enhanced search results.

### Schema Types Implemented

#### 1. Organization (Site-Wide)

**Implements:** Company/org knowledge panel, logo, contact info

```php
theme_output_json_ld( theme_get_organization_schema() );
```

**Output Example:**

```json
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Your Company",
  "url": "https://example.com",
  "logo": "https://example.com/logo.png"
}
```

**Best Practice:** Add once in `header.php` or early hook

#### 2. Article (Blog Posts)

**Implements:** Article cards in search, knowledge panels

```php
theme_output_json_ld( theme_get_article_schema() );
```

**Output Example:**

```json
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": "Article Title",
  "description": "Article excerpt...",
  "image": "https://example.com/featured.jpg",
  "datePublished": "2026-03-27T10:00:00Z",
  "dateModified": "2026-03-27T15:30:00Z",
  "author": {
    "@type": "Person",
    "name": "Author Name"
  }
}
```

**Place in:** Single post template

#### 3. Product (WooCommerce)

**Implements:** Product rich snippets, reviews, pricing

```php
theme_output_json_ld( theme_get_product_schema() );
```

**Output Example:**

```json
{
  "@context": "https://schema.org",
  "@type": "Product",
  "name": "Product Name",
  "description": "Product description...",
  "image": "https://example.com/product.jpg",
  "brand": {
    "@type": "Brand",
    "name": "Your Brand"
  },
  "offers": {
    "@type": "Offer",
    "price": "29.99",
    "priceCurrency": "USD",
    "availability": "https://schema.org/InStock"
  },
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "4.5",
    "reviewCount": "28"
  }
}
```

**Place in:** Single product template

#### 4. LocalBusiness

**Implements:** Local business knowledge panel, map listings

```php
theme_output_json_ld( theme_get_local_business_schema( array(
    'street_address'   => '123 Main St',
    'address_locality' => 'New York',
    'address_region'   => 'NY',
    'postal_code'      => '10001',
    'telephone'        => '+1-555-123-4567',
    'email'            => 'contact@example.com',
) ) );
```

**Place in:** Header or contact page

#### 5. BreadcrumbList

**Implements:** Breadcrumb navigation in search results

```php
$breadcrumbs = array(
    array( 'title' => 'Home', 'url' => home_url() ),
    array( 'title' => 'Products', 'url' => home_url( '/shop' ) ),
    array( 'title' => 'Current Product', 'url' => get_permalink() ),
);

theme_output_json_ld( theme_get_breadcrumb_schema( $breadcrumbs ) );
```

**Place in:** Single product or post template

### For Custom Blocks

Add schema to custom Gutenberg blocks:

**Example: Service Block**

```php
// In block registration or template
$schema = array(
    '@context' => 'https://schema.org',
    '@type'    => 'Service',
    'name'     => get_field( 'service_name' ),
    'description' => get_field( 'service_description' ),
    'provider' => array(
        '@type' => 'Organization',
        'name'  => get_bloginfo( 'name' ),
    ),
);

theme_output_json_ld( $schema );
```

---

## Image Alt Text (ACF Integration)

### Purpose

Alt text serves two purposes:

1. **Accessibility:** Describes images for screen readers
2. **SEO:** Helps Google understand image content

### ACF Field Configuration

#### 1. Create Alt Text Fields

In ACF, create these field groups:

**Post Images:**

```
Field Group: Post/Page Media
├─ featured_image (Image)
├─ featured_image_alt (Text) → Instructions: "Describe the image for screen readers"
```

**Product Images:**

```
Field Group: Product Details
├─ product_gallery (Repeater)
│  ├─ gallery_image (Image)
│  └─ gallery_image_alt (Text)
├─ product_thumbnail_alt (Text)
```

**Generic Content:**

```
Field Group: Content Images
├─ inline_images (Repeater)
│  ├─ image (Image)
│  └─ image_alt_text (Text)
│  └─ image_caption (Text)
```

#### 2. Enforce Alt Text in Templates

**For Featured Images:**

```twig
{# base.twig #}
<img
    src="{{ post.thumbnail.src('large') }}"
    alt="{{ post.meta('featured_image_alt') or post.title }}"
    loading="lazy"
>
```

**For Product Gallery:**

```twig
{# single-product.twig #}
{% for item in product_images %}
    <img
        src="{{ item.gallery_image }}"
        alt="{{ item.gallery_image_alt or product.title }}"
        class="product-image"
    >
{% endfor %}
```

**For Inline Images:**

```twig
{# archive.twig #}
{% for image_block in post.meta('inline_images') %}
    <figure>
        <img
            src="{{ image_block.image.url }}"
            alt="{{ image_block.image_alt_text }}"
            title="{{ image_block.image_caption }}"
            loading="lazy"
        >
        {% if image_block.image_caption %}
            <figcaption>{{ image_block.image_caption }}</figcaption>
        {% endif %}
    </figure>
{% endfor %}
```

#### 3. WordPress Image Helper

Add helper for automatic alt text fallbacks:

```php
/**
 * Get image alt text with fallbacks
 */
function theme_get_image_alt( $image_id, $fallback = '' ) {
    // Try custom ACF alt field first
    $alt = get_post_meta( $image_id, '_custom_alt_text', true );

    if ( ! empty( $alt ) ) {
        return $alt;
    }

    // Try WordPress built-in alt
    $alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );

    if ( ! empty( $alt ) ) {
        return $alt;
    }

    // Fall back to provided default
    return $fallback;
}

// Usage in Twig
{{ featured_image.src | img_alt( 'Fallback text' ) }}
```

#### 4. Validation & Enforcement

**ACF Field Instructions Template:**

```
📝 Alt Text Guidelines

✅ DO:
- Describe what the image shows (5-10 words)
- Include keywords naturally if relevant
- Be specific and descriptive
- E.g., "Blue ceramic coffee mug with latte"

❌ DON'T:
- Repeat image filename
- Say "image of" or "picture of"
- Stuff keywords (looks like spam)
- Leave blank unless decorative

🔍 SEO Examples:

POOR: "photo.jpg"
BETTER: "Handmade ceramic coffee mug"

POOR: "image of blue mug"
BETTER: "Blue handmade ceramic coffee mug with latte"
```

### Alt Text Audit Report

Create a helper to audit missing alt text:

```php
/**
 * Audit alt text coverage
 *
 * @return array {
 *     'total' => total images,
 *     'with_alt' => images with alt text,
 *     'missing_alt' => images without alt,
 *     'coverage' => percentage
 * }
 */
function theme_audit_alt_text() {
    $images = get_posts( array(
        'post_type'      => 'attachment',
        'post_mime_type' => 'image',
        'numberposts'    => -1,
        'fields'         => 'ids',
    ) );

    $with_alt = 0;
    foreach ( $images as $id ) {
        $alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
        if ( ! empty( $alt ) ) {
            $with_alt++;
        }
    }

    $total = count( $images );
    $coverage = $total > 0 ? round( ( $with_alt / $total ) * 100 ) : 0;

    return array(
        'total'        => $total,
        'with_alt'     => $with_alt,
        'missing_alt'  => $total - $with_alt,
        'coverage'     => $coverage,
    );
}
```

---

## Robots.txt Configuration

### Purpose

Robots.txt tells search engines which parts of your site to crawl and which to ignore.

### Setup

Create `/robots.txt` in the WordPress root directory:

```text
# robots.txt
# Generated: March 27, 2026

User-agent: *
Allow: /
Disallow: /wp-admin/
Disallow: /wp-includes/
Disallow: /wp-content/uploads/*/
Disallow: */?s=
Disallow: *?replytocom=
Disallow: /comments
Disallow: /trackback
Disallow: /feed
Disallow: /feeds
Disallow: /?p=

# Slow down aggressive crawlers
User-agent: AhrefsBot
Crawl-delay: 10
User-agent: SemrushBot
Crawl-delay: 10

# WooCommerce cart/checkout (no indexing)
Disallow: /cart/
Disallow: /checkout/
Disallow: /my-account/

# Archive pagination (keep base, disallow pages 2+)
Allow: /$
Allow: /blog/$
Allow: /shop/$
Disallow: /page/*

# Sitemaps
Sitemap: https://example.com/sitemap.xml
Sitemap: https://example.com/sitemap_index.xml
```

### Testing

1. Visit `https://yoursite.com/robots.txt`
2. Verify syntax in [MX Toolbox Robots Tester](https://mxtoolbox.com/robotstxt.aspx)
3. Check in [Google Search Console](https://search.google.com/search-console) → Coverage → Coverage report

---

## XML Sitemap Setup

### For WordPress (Built-in)

WordPress generates sitemaps automatically via REST API (WP 5.5+):

- **Main sitemap:** `https://yoursite.com/wp-sitemap.xml`
- **Post sitemap:** `https://yoursite.com/wp-sitemap-posts.xml`
- **Taxonomies:** `https://yoursite.com/wp-sitemap-taxonomies.xml`
- **Users:** `https://yoursite.com/wp-sitemap-users.xml`

### For WooCommerce Products

WooCommerce includes products in default sitemaps automatically.

### Verifying Sitemaps

1. **Visit your sitemap:** `https://yoursite.com/wp-sitemap.xml`
2. **Check for errors:** XML syntax should be valid
3. **Submit to Google Search Console:**
   - Go to Sitemaps section
   - Add: `https://yoursite.com/wp-sitemap.xml`
   - Monitor status

### Yoast SEO Integration (Optional)

If using Yoast SEO plugin, it provides advanced sitemap features:

- Separate product sitemaps
- Image sitemap
- Video sitemap
- Custom priority/frequency

### Custom Sitemap Plugin

For advanced needs, use [Google XML Sitemaps](https://wordpress.org/plugins/google-sitemap-generator/):

**Configuration:**

- Change frequency: Post (weekly), Pages (monthly), Archives (never)
- Priority: Post (0.6), Pages (0.5), Archives (0.3)
- Include: Images, Video (if applicable)

---

## Integration Template Example

### Complete Head Implementation

Add to your `templates/html-header.twig`:

```twig
{# HTML Head #}
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {% block title %}
        <title>{{ wp_title('|', true, 'right') }}</title>
    {% endblock %}

    {# WordPress Hooks #}
    {{ wp_head() }}

    {# SEO Meta Tags (generated by functions.php hooks) #}
    {# - Open Graph #}
    {# - Twitter Cards #}
    {# - Canonical #}
    {# - JSON-LD Schema #}

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">

    {# CSS Files #}
    <link rel="stylesheet" href="{{ theme.link }}/style.css">

    {# Favicon #}
    <link rel="icon" href="{{ site.icon }}">
    <link rel="apple-touch-icon" href="{{ site.icon }}">
</head>
```

### Functions.php Integration

```php
<?php
// Near top of functions.php

// Load SEO helpers early
require_once __DIR__ . '/src/seo-helpers.php';

// ... other requires ...

/**
 * Output SEO meta tags in head
 */
add_action( 'wp_head', function() {
    // Site organization schema (global)
    theme_output_json_ld( theme_get_organization_schema() );

    // Open Graph tags
    theme_output_open_graph();

    // Twitter Cards
    theme_output_twitter_card( array(
        'handle' => '@YourTwitterHandle', // Replace
    ) );

    // Canonical tag
    theme_output_canonical();
}, 5 );

/**
 * Schema markup for singular posts
 */
add_action( 'wp_head', function() {
    if ( ! is_singular() ) {
        return;
    }

    if ( is_singular( 'post' ) || is_singular( 'page' ) ) {
        theme_output_json_ld( theme_get_article_schema() );
    }

    if ( is_singular( 'product' ) && function_exists( 'wc_get_product' ) ) {
        theme_output_json_ld( theme_get_product_schema() );
    }
}, 5 );

/**
 * Breadcrumb schema (if using breadcrumb component)
 */
add_action( 'wp_head', function() {
    if ( ! function_exists( 'theme_get_breadcrumbs' ) ) {
        return;
    }

    $breadcrumbs = theme_get_breadcrumbs();
    if ( ! empty( $breadcrumbs ) ) {
        theme_output_json_ld( theme_get_breadcrumb_schema( $breadcrumbs ) );
    }
}, 5 );
```

---

## Implementation Checklist

### Phase 1: Core Setup (Week 1)

- [ ] Require `src/seo-helpers.php` in `functions.php`
- [ ] Add Open Graph hooks to `wp_head` action
- [ ] Add Twitter Card hooks (set your handle)
- [ ] Add Canonical link hooks
- [ ] Add Organization schema hook
- [ ] Create `/robots.txt` file
- [ ] Verify default XML sitemaps work
- [ ] Test with Facebook & Twitter debuggers

### Phase 2: Content Schema (Week 2)

- [ ] Add Article schema for posts
- [ ] Add Product schema for WooCommerce
- [ ] Add Breadcrumb schema (if using breadcrumbs)
- [ ] Test in Search Console
- [ ] Verify rich snippets appear in test results

### Phase 3: ACF Alt Text (Week 2-3)

- [ ] Create ACF field groups for alt text
- [ ] Update all image templates to use helper
- [ ] Add fallback pattern to templates
- [ ] Add alt text guidelines to ACF instructions
- [ ] Create alt text audit report
- [ ] Review/fix missing alt text in existing images

### Phase 4: Validation & Monitoring (Ongoing)

- [ ] Use Schema.org validator on all schema
- [ ] Check Google Search Console → Coverage
- [ ] Monitor search console for new indexing
- [ ] Track rich snippet appearance
- [ ] Maintain alt text quality on new images

---

## Testing & Validation

### Tool #1: Google Rich Results Test

Test whether Google can display rich snippets:

1. Visit [Rich Results Test](https://search.google.com/test/rich-results)
2. Paste your page URL
3. Check for warnings/errors
4. Aim for: "Eligible for rich results"

**What it validates:**

- Organization schema
- Article schema
- Product schema
- Breadcrumbs

### Tool #2: Schema.org Validator

Validate JSON-LD syntax:

1. Visit [JSON-LD Playground](https://json-ld.org/playground/)
2. Paste your page's JSON-LD code
3. Check for syntax errors
4. Monitor warnings

### Tool #3: Facebook Sharing Debugger

Test Open Graph rendering:

1. Visit [Facebook Debugger](https://developers.facebook.com/tools/debug/)
2. Enter your page URL
3. Review preview card
4. Click "Scrape Again" if changes made
5. Fix any warnings

### Tool #4: Twitter Card Validator

Test Twitter Card rendering:

1. Visit [Twitter Card Validator](https://cards-dev.twitter.com/validator)
2. Paste your page URL
3. Check preview
4. Fix any warnings

### Tool #5: Google Search Console

Monitor indexing & search appearance:

1. Go to [Google Search Console](https://search.google.com/search-console)
2. Check Coverage → All → Index (brand new pages appear here)
3. Monitor Search Appearance → Rich Results (by type)
4. Track CTR, Position, Impressions over time

### Automated Test Script

Add to your testing suite:

```bash
#!/bin/bash
# seo-test.sh - Validate SEO meta tags

# Check Open Graph tags
echo "Checking Open Graph tags..."
curl -s https://yoursite.com/page | grep -o 'og:' | wc -l

# Check Twitter Cards
echo "Checking Twitter Cards..."
curl -s https://yoursite.com/page | grep -o 'twitter:' | wc -l

# Check Canonical tag
echo "Checking Canonical tag..."
curl -s https://yoursite.com/page | grep 'rel="canonical"'

# Check JSON-LD
echo "Checking JSON-LD schema..."
curl -s https://yoursite.com/page | grep -o 'application/ld+json' | wc -l
```

---

## Performance Impact

### Impact on Page Load

All SEO features are lightweight:

| Feature   | Size       | Impact                         |
| --------- | ---------- | ------------------------------ |
| Meta tags | <5KB       | Negligible                     |
| JSON-LD   | <10KB avg  | Minimal (parsed at crawl time) |
| Functions | <20KB code | Minimal (server-side)          |

**Recommendation:** Keep all enabled; no performance concerns.

### Caching Considerations

SEO meta tags change per-page, so:

- ✅ Cache entire page (meta tags included)
- ✅ Purge cache when: post published, featured image updated, metadata changed
- ✅ Use WordPress cache plugins (WP Super Cache, W3 Total Cache)

---

## Troubleshooting

### Meta Tags Not Appearing

**Problem:** Open Graph tags missing from page

**Check:**

1. Verify `wp_head()` called in template
2. Verify hook priority (should be < 10)
3. Check for output buffering issues
4. Validate with `curl https://yoursite.com/page | grep og:`

**Fix:**

```php
// Ensure hook is set correctly
var_dump( has_action( 'wp_head', 'theme_output_open_graph' ) ); // Should be > 0
```

### Schema Not Validating

**Problem:** JSON-LD syntax errors reported

**Check:**

1. Verify no mixed languages (PHP vs JSON)
2. Use `wp_json_encode()` not `json_encode()`
3. Validate special characters (quotes, accents)

**Fix:**

```php
// WRONG - PHP array mixed with JSON
$schema = '{
    "@type": "Product",
    "name": "' . $product_name . '"
}';

// RIGHT - Pure array encoded
$schema = array(
    '@type' => 'Product',
    'name'  => $product_name,
);
echo '<script type="application/ld+json">';
echo wp_json_encode( $schema );
echo '</script>';
```

### Image Not Showing in Social Preview

**Problem:** Facebook/Twitter showing broken image

**Check:**

1. Verify image URL works directly
2. Ensure image is public (not behind paywall)
3. Check image size (min 1200x630)
4. Verify image doesn't have WAF/bot blocking

**Fix:**

```php
// Debug featured image
$featured = get_the_post_thumbnail_url( null, 'large' );
var_dump( $featured );

// Manually specify
theme_output_open_graph( array(
    'image' => 'https://yoursite.com/verified-public-image.jpg'
) );
```

### Alt Text Not Displaying

**Problem:** Images showing without alt text

**Check:**

1. Verify ACF field name matches template
2. Check field returns value (admin edit page)
3. Verify Twig fallback syntax

**Fix:**

```twig
{# Debug alt text #}
{{ item.image_alt_text }}
{{ dump(item) }} {# Show all fields #}

{# Safe fallback #}
alt="{{ item.image_alt_text ?: 'Default alt text' }}"
```

---

## Related Resources

- [Open Graph Protocol](https://ogp.me/)
- [Twitter Card Tags](https://developer.twitter.com/en/docs/twitter-for-websites/cards)
- [Schema.org Schemas](https://schema.org/)
- [Google Search Central](https://developers.google.com/search)
- [WordPress Sitemaps Docs](https://wordpress.org/support/article/sitemaps/)
- [WooCommerce SEO Guide](https://woocommerce.com/document/search-engine-optimization/)

---

## Version History

| Version | Date         | Changes                |
| ------- | ------------ | ---------------------- |
| 1.0     | Mar 27, 2026 | Initial implementation |

**Next Review:** After 2 weeks of live testing
