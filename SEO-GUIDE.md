# SEO Implementation Guide

A comprehensive guide to SEO best practices and implementation strategies for the Starter Theme. This document covers on-page SEO, technical SEO, structured data (schema markup), accessibility, and performance optimization.

---

## Table of Contents

1. [SEO Audit Summary](#seo-audit-summary)
2. [On-Page SEO](#on-page-seo)
3. [Technical SEO](#technical-seo)
4. [Structured Data (Schema Markup)](#structured-data-schema-markup)
5. [Accessibility (A11y) & SEO](#accessibility--seo)
6. [Performance Optimization](#performance-optimization)
7. [Implementation Checklist](#implementation-checklist)
8. [Resources & Tools](#resources--tools)

---

## SEO Audit Summary

### ✅ What's Working

- **Semantic HTML**: Proper use of `<header>`, `<main>`, `<footer>`, `<nav>`, `<section>`, `<aside>`
- **Accessibility**: Skip-to-content links, ARIA labels, heading hierarchy
- **Mobile-Responsive Design**: Viewport meta tag, responsive grid system
- **Performance Foundation**: Vite build optimization, lazy-loading support
- **Core Web Vitals Ready**: BEM CSS for fast rendering, minimal JS
- **Clean URL Structure**: WordPress-native permalink management
- **Site Architecture**: Logical post types, taxonomies, and page templates

### ⚠️ Needs Attention

- **Meta Tags**: No Open Graph (OG) or Twitter Card implementation
- **Canonical Tags**: Not explicitly set in templates
- **Structured Data**: No JSON-LD schema markup in blocks or pages
- **Favicon/Branding**: No documented favicon/apple-touch-icon strategy
- **Robots & Sitemap**: No documented XML sitemap or robots.txt setup
- **Image Alt Text**: No ACF guidance for enforcing alt text on images
- **Internal Linking**: No documentation for cross-linking strategy

### 🎯 Low Priority (Non-Blocking)

- Breadcrumb schema markup (nice-to-have)
- Heading structure on some components (mostly good)
- Social metadata consistency

---

## On-Page SEO

### Title Tags

**Current State**: Handled by WordPress and SEO plugins (Yoast, Rank Math)

**Best Practice**: 50-60 characters, keyword-first or natural phrasing

**Implementation**:

```php
// In functions.php or via SEO plugin
function set_title_format() {
    add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'set_title_format' );
```

**For Theme Development**: Ensure `wp_title()` is called properly in templates:

```twig
{% if post and post.post_title %}
    <title>{{ post.post_title }} - {{ site.name }}</title>
{% else %}
    <title>{{ site.name }} - {{ site.description }}</title>
{% endif %}
```

### Meta Descriptions

**Current State**: Set via theme to site description; posts handled by SEO plugins

**Best Practice**: 150-160 characters, compelling Call-to-Action language

**Enhancement**: Update [html-header.twig](templates/html-header.twig) to use post/page-specific descriptions:

```twig
<meta name="description" content="{% if post and post.meta.meta_description %}
    {{ post.meta.meta_description|slice(0, 160) }}
{% else %}
    {{ site.description|slice(0, 160) }}
{% endif %}" />
```

**For Custom Post Types**: Create ACF field group for meta description on all public post types.

### Open Graph Tags

**Current State**: ❌ Not implemented

**Impact**: Improves social media previews when content is shared

**Implementation** — Add to `html-header.twig`:

```twig
{# Open Graph - Social Sharing #}
{% if post %}
    <meta property="og:type" content="{% if post.post_type == 'product' %}product{% elseif post.post_type == 'blog' %}article{% else %}website{% endif %}" />
    <meta property="og:title" content="{{ post.post_title }}" />
    <meta property="og:description" content="{{ post.excerpt|striptags|slice(0, 160) }}" />
    <meta property="og:url" content="{{ post.link }}" />
    {% if post.thumbnail %}
        <meta property="og:image" content="{{ post.thumbnail.src }}" />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
    {% endif %}
{% else %}
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ site.name }}" />
    <meta property="og:description" content="{{ site.description }}" />
    <meta property="og:url" content="{{ site.home }}" />
{% endif %}
<meta property="og:site_name" content="{{ site.name }}" />
<meta property="og:locale" content="en_US" />
```

### Twitter Card Tags

**Current State**: ❌ Not implemented

**Impact**: Optimizes Twitter/X preview cards

**Implementation** — Add to `html-header.twig`:

```twig
{# Twitter Cards #}
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{% if post %}{{ post.post_title }}{% else %}{{ site.name }}{% endif %}" />
<meta name="twitter:description" content="{% if post %}{{ post.excerpt|striptags|slice(0, 160) }}{% else %}{{ site.description|slice(0, 160) }}{% endif %}" />
{% if post and post.thumbnail %}
    <meta name="twitter:image" content="{{ post.thumbnail.src }}" />
{% endif %}
{# Optional: Set your Twitter handle #}
{# <meta name="twitter:creator" content="@yourhandle" /> #}
```

### Canonical Tags

**Current State**: ⚠️ Partial (WordPress handles it, but not explicitly set in theme)

**Best Practice**: Always explicitly set, especially for similar content

**Implementation** — Add to `html-header.twig`:

```twig
{% if post %}
    <link rel="canonical" href="{{ post.link }}" />
{% else %}
    <link rel="canonical" href="{{ site.url }}" />
{% endif %}
```

### Image Alt Text Strategy

**Current State**: ⚠️ Dependent on editor compliance

**Best Practice**: Every image should have descriptive alt text

**ACF Implementation** — Enforce alt text in image field groups:

```php
array(
    'key' => 'field_image_alt',
    'label' => 'Image',
    'name' => 'image',
    'type' => 'image',
    'return_format' => 'array',
    'preview_size' => 'medium',
    'required' => 1, // Must fill image field
    // If building custom repeater, add sub-field:
    'sub_fields' => array(
        array(
            'key' => 'field_alt_text',
            'label' => 'Alt Text',
            'name' => 'alt_text',
            'type' => 'text',
            'required' => 1,
            'instructions' => 'Describe the image for accessibility and SEO',
        ),
    ),
),
```

**Twig Usage**:

```twig
<img src="{{ image.url }}" alt="{{ image.alt }}" />
```

---

## Technical SEO

### Robots.txt

**Location**: `/robots.txt` at site root

**Modern WordPress Practice**: Usually auto-generated by WordPress

**For Custom Configuration**:

```
User-agent: *
Allow: /
Disallow: /wp-admin/
Disallow: /wp-login.php
Disallow: /wp-includes/
Disallow: /?s=  # Disable search parameter crawling

# Block bad bots
User-agent: AhrefsBot
User-agent: SemrushBot
Disallow: /

# Sitemap location
Sitemap: https://yoursite.com/sitemap.xml
```

### XML Sitemap

**Current State**: Generated by WordPress if SEO plugin is active

**For WordPress Native Sitemaps** (WordPress 5.5+):

1. Ensure `register_rest_field()` is used for custom post types
2. Use WordPress Settings → Reading to configure exclusions
3. Test at `/wp-sitemap.xml`

**For SEO Plugin Integration** (Yoast/Rank Math):

- Both plugins auto-generate sitemaps
- Submit to Google Search Console
- Verify in robots.txt

### Page Speed (Core Web Vitals)

**Current Optimizations**:

- Vite for efficient JS/CSS bundling
- BEM CSS for minimal specificity (faster rendering)
- Lazy-loading ready (use `loading="lazy"` on images)
- Accessible Gutenberg blocks

**Recommended Enhancements**:

1. **Image Optimization**:

```scss
// In css/inc/base/vars.scss or blocks
img {
  max-width: 100%;
  height: auto;
  display: block;
}

// Lazy-load images
<img src="..." loading="lazy" alt="..." />
```

2. **Font Loading Strategy**:

```css
/* Preload critical fonts */
<link rel="preload" href="/fonts/your-font.woff2" as="font" type="font/woff2" crossorigin>

/* Use font-display: swap */
@font-face {
  font-family: "YourFont";
  src: url("...") format("woff2");
  font-display: swap; /* Show fallback, swap when ready */
}
```

3. **CSS Optimization**:

- Vite development with hot module reload
- Production builds with CSS minification
- Critical CSS already inlined via Vite

**Testing Tools**:

- [PageSpeed Insights](https://pagespeed.web.dev/)
- [WebPageTest](https://www.webpagetest.org/)
- Chrome DevTools (Lighthouse tab)

---

## Structured Data (Schema Markup)

### Why Schema Matters

Schema markup helps search engines understand:

- What content is (article, product, person, organization)
- Relationships between content
- Rich snippets in search results (ratings, pricing, etc.)

### JSON-LD (Best Practice)

JSON-LD is recommended over microdata/RDFa. Output in `<head>` before `wp_head()`.

### Organization Schema

**Location**: Global, add once per site

**File to create**: `src/schema-organization.php`

```php
<?php
/**
 * Organization Schema Markup
 * Outputs JSON-LD for site-wide organization metadata
 */

if ( ! function_exists( 'render_organization_schema' ) ) {
    function render_organization_schema() {
        $organization = array(
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => get_bloginfo( 'name' ),
            'url' => get_home_url(),
            'logo' => get_theme_mod( 'custom_logo_url' ) ?? get_stylesheet_directory_uri() . '/images/logo.png',
            'description' => get_bloginfo( 'description' ),
            'sameAs' => array(
                'https://www.facebook.com/yourpage',
                'https://twitter.com/yourhandle',
                'https://www.linkedin.com/company/yourcompany',
            ),
            'contactPoint' => array(
                '@type' => 'ContactPoint',
                'contactType' => 'Customer Service',
                'telephone' => '+1-XXX-XXX-XXXX',
                'email' => get_option( 'admin_email' ),
            ),
        );

        echo '<script type="application/ld+json">';
        echo wp_json_encode( $organization );
        echo '</script>';
    }
}
add_action( 'wp_head', 'render_organization_schema', 5 );
```

### Article Schema (Blog Posts)

**For Single Blog Posts** — Add to `templates/single-blog.twig`:

```twig
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BlogPosting",
    "headline": "{{ post.title }}",
    "description": "{{ post.excerpt|striptags|slice(0, 160) }}",
    "image": "{% if post.thumbnail %}{{ post.thumbnail.src }}{% endif %}",
    "datePublished": "{{ post.post_date|date('Y-m-d') }}",
    "dateModified": "{{ post.post_modified|date('Y-m-d') }}",
    "author": {
        "@type": "Person",
        "name": "{{ post.post_author.first_name }} {{ post.post_author.last_name }}"
    },
    "publisher": {
        "@type": "Organization",
        "name": "{{ site.name }}",
        "logo": {
            "@type": "ImageObject",
            "url": "{{ site.logo }}"
        }
    }
}
</script>
```

### Product Schema (E-commerce)

**For WooCommerce Products** — Add to `templates/single-product.twig`:

```twig
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "{{ post.title }}",
    "description": "{{ post.description|striptags|slice(0, 300) }}",
    "image": "{{ post.thumbnail.src }}",
    "brand": {
        "@type": "Brand",
        "name": "{{ site.name }}"
    },
    "offers": {
        "@type": "Offer",
        "url": "{{ post.link }}",
        "priceCurrency": "USD",
        "price": "{{ post.get_price() }}",
        "availability": "{{ post.is_in_stock() ? 'InStock' : 'OutOfStock' }}"
    },
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "{{ post.average_rating }}",
        "reviewCount": "{{ post.review_count }}"
    }
}
</script>
```

### LocalBusiness Schema

**For Service-Based Businesses** — Add to functions.php:

```php
function render_local_business_schema() {
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'LocalBusiness',
        'name' => get_bloginfo( 'name' ),
        'url' => get_home_url(),
        'address' => array(
            '@type' => 'PostalAddress',
            'streetAddress' => get_option( 'business_street' ),
            'addressLocality' => get_option( 'business_city' ),
            'addressRegion' => get_option( 'business_state' ),
            'postalCode' => get_option( 'business_zip' ),
        ),
        'telephone' => get_option( 'business_phone' ),
    );

    echo '<script type="application/ld+json">';
    echo wp_json_encode( $schema );
    echo '</script>';
}
add_action( 'wp_head', 'render_local_business_schema', 5 );
```

### FAQPage Schema

**For FAQ/Accordion Blocks** — Enhance `views/blocks/accordion.twig`:

```twig
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        {% for item in fields.items %}
        {
            "@type": "Question",
            "name": "{{ item.title }}",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "{{ item.content|striptags }}"
            }
        }{{ not loop.last ? ',' : '' }}
        {% endfor %}
    ]
}
</script>
```

---

## Accessibility & SEO

Accessibility and SEO go hand-in-hand. Both require semantic HTML, clear structure, and usability.

### Current Accessibility ✅

The theme includes:

- **Skip-to-Content Link**: Allows keyboard users to bypass navigation
- **ARIA Labels**: Navigation, landmarks identified for screen readers
- **Semantic HTML**: Proper heading hierarchy, landmark elements
- **Keyboard Navigation**: Focus states in CSS

### Recommended Enhancements

1. **Color Contrast**:

   - Ensure 4.5:1 ratio for normal text
   - 3:1 ratio for large text (18pt+)
   - Test with [WebAIM Contrast Checker](https://webaim.org/resources/contrastchecker/)

2. **Heading Hierarchy**:

   - One `<h1>` per page (usually page title)
   - Sequential hierarchy: h1 → h2 → h3 (no jumps)
   - Use in templates consistently

   **In Templates**:

   ```twig
   {% block page_title %}
       <h1>{{ post.title }}</h1>
   {% endblock %}

   {% block content %}
       <h2>Section Title</h2>
       <!-- Content -->
   {% endblock %}
   ```

3. **Form Accessibility**:

   ```twig
   <label for="email-field">Email Address</label>
   <input id="email-field" type="email" name="email" required aria-required="true" />
   ```

4. **Button vs. Link**:

   ```twig
   {# Navigation #}
   <a href="{{ url }}">Go to page</a>

   {# Action #}
   <button onclick="handleAction()">Submit Form</button>
   ```

---

## Performance Optimization

### Build Optimization

**Current Setup** via Vite:

```bash
# Development (hot reload, unminified)
npm run watch

# Production (minified, optimized)
npm run build
```

### Specific Optimizations

1. **Code Splitting**:

   - Vite auto-splits vendor code from app code
   - JS modules load only when needed

2. **CSS Optimization**:

   ```scss
   // Use utilities instead of duplicating styles
   .component {
     @extend .text-center; // BEM + utilities
   }
   ```

3. **Image Optimization**:

   ```html
   <img
     src="image.jpg"
     srcset="image-small.jpg 480w, image-large.jpg 1200w"
     sizes="(max-width: 600px) 100vw, 50vw"
     loading="lazy"
     alt="Descriptive alt text"
   />
   ```

4. **Font Loading**:
   ```css
   @font-face {
     font-family: "Roboto";
     src: url("/fonts/roboto.woff2") format("woff2");
     font-display: swap; /* Show fallback immediately */
   }
   ```

### Performance Budgets

**Recommended Targets** (per page load):

| Metric                             | Target  |
| ---------------------------------- | ------- |
| **First Contentful Paint (FCP)**   | < 1.8s  |
| **Largest Contentful Paint (LCP)** | < 2.5s  |
| **Cumulative Layout Shift (CLS)**  | < 0.1   |
| **JavaScript (all)**               | < 170kb |
| **CSS (all)**                      | < 50kb  |
| **Images (total)**                 | < 500kb |

### Monitoring

Use [Google Search Console](https://search.google.com/search-console/) to monitor:

- Core Web Vitals performance
- Crawl errors
- Structured data issues
- Security alerts

---

## Implementation Checklist

### Phase 1: Foundation (Immediate)

- [ ] Add canonical tags to html-header.twig
- [ ] Add Open Graph tags to html-header.twig
- [ ] Add Twitter Card tags to html-header.twig
- [ ] Enhance meta description to use post-specific value
- [ ] Create Organization schema JSON-LD
- [ ] Add favicon/apple-touch-icon references

### Phase 2: Content Optimization (Week 1)

- [ ] Add Article schema to blog post template
- [ ] Add Product schema to WooCommerce template (if applicable)
- [ ] Add FAQ schema to accordion blocks
- [ ] Create ACF guidance doc for image alt text
- [ ] Audit all images and add alt text

### Phase 3: Technical (Week 2)

- [ ] Configure robots.txt
- [ ] Verify XML sitemap generation
- [ ] Set up Google Search Console
- [ ] Set up Bing Webmaster Tools
- [ ] Run Lighthouse audit and address issues

### Phase 4: Monitoring (Ongoing)

- [ ] Monitor Core Web Vitals
- [ ] Review structured data in Search Console
- [ ] Check for crawl errors weekly
- [ ] Monitor rankings for target keywords

---

## Resources & Tools

### SEO Best Practices

- [Google Search Central](https://developers.google.com/search)
- [Yoast SEO Guide](https://yoast.com/seo/)
- [Moz SEO Guide](https://moz.com/beginners-guide-to-seo)

### Schema Markup

- [Schema.org Documentation](https://schema.org/)
- [Google Structured Data Testing Tool](https://search.google.com/test/rich-results)
- [JSON-LD Playground](https://json-ld.org/playground/)

### Performance Testing

- [Google PageSpeed Insights](https://pagespeed.web.dev/)
- [WebPageTest](https://www.webpagetest.org/)
- [GTmetrix](https://gtmetrix.com/)
- [Chrome Lighthouse](https://github.com/GoogleChrome/lighthouse)

### Accessibility

- [WebAIM](https://webaim.org/)
- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [Axe DevTools](https://www.deque.com/axe/devtools/)

### WordPress SEO

- [WordPress.org SEO](https://wordpress.org/support/article/search-engine-optimization/)
- Popular Plugins:
  - Yoast SEO
  - Rank Math
  - All in One SEO

### Content Strategy

- [Google Search Console Training](https://support.google.com/webmasters/answer/9128668)
- [Content Audit Best Practices](https://moz.com/blog/content-audit-seo)
- [Keyword Research](https://moz.com/blog/keyword-research-checklists)

---

## Quick Reference: What to Check Before Launch

✅ **Critical**:

- [ ] robots.txt configured (allow indexing of content pages)
- [ ] XML sitemap generated and submitted
- [ ] Title tags optimized (50-60 chars)
- [ ] Meta descriptions present and unique (150-160 chars)
- [ ] Canonical tags in place
- [ ] Images have alt text
- [ ] Internal links working (no 404s)
- [ ] Mobile responsive
- [ ] Page speed acceptable (Lighthouse > 80)
- [ ] Schema markup valid (Organization + content type)

✅ **Important**:

- [ ] Open Graph tags for social sharing
- [ ] Twitter Card tags
- [ ] Favicon/apple-touch-icon
- [ ] Heading hierarchy correct (one H1 per page)
- [ ] ARIA labels on interactive elements
- [ ] Color contrast sufficient (4.5:1 normal text)
- [ ] Form labels properly associated
- [ ] 404 page user-friendly

---

**Document Version**: 1.0  
**Last Updated**: March 2026  
**Maintainer**: Theme Development Team
