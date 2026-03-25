# Breadcrumb Navigation Guide

## Overview

This theme includes automatic breadcrumb navigation with Schema.org markup support. Breadcrumbs improve user experience by showing the current page's position in site hierarchy and provide valuable SEO signals.

**Features:**

- Automatic generation for all post types and archives
- Mobile-responsive with abbreviated display
- Schema.org BreadcrumbList JSON-LD markup
- Gutenberg block for manual placement
- Fully accessible with ARIA labels
- Dark mode support

---

## Auto-Generated Breadcrumbs

Breadcrumbs display automatically on all pages except the homepage. They appear in the main content area, wrapped with the `wrapper` class for proper spacing.

### Supported Page Types

| Page Type                      | Breadcrumb Trail                | Example                                |
| ------------------------------ | ------------------------------- | -------------------------------------- |
| **Single Post**                | Home › Blog › Post Title        | Home › Blog › My First Post            |
| **Single Page (Hierarchical)** | Home › Parent › Child › Current | Home › Products › Widgets › Red Widget |
| **Single Custom Post Type**    | Home › Post Type › Title        | Home › Services › Web Design           |
| **Category Archive**           | Home › Blog › Category Name     | Home › Blog › Technology               |
| **Tag Archive**                | Home › Tag: Tag Name            | Home › Tag: WordPress                  |
| **Taxonomy Archive**           | Home › Taxonomy Term            | Home › Service Type › Consultation     |
| **Post Type Archive**          | Home › Post Type Name           | Home › Portfolio                       |
| **Author Archive**             | Home › Author: Author Name      | Home › Author: John Doe                |
| **Date Archive**               | Home › Year › Month › Day       | Home › 2024 › March › 25               |
| **Search Results**             | Home › Search: Query            | Home › Search: breadcrumbs             |
| **404 Page**                   | Home › Page Not Found           | Home › Page Not Found                  |

---

## PHP Breadcrumb Class

The breadcrumb logic lives in `src/helpers/breadcrumbs.php`:

```php
<?php
use StarterTheme\Helpers\Breadcrumb;

// Get complete breadcrumb trail
$trail = Breadcrumb::get_trail();
// Returns: array of items with 'url', 'label', 'current' keys

// Get abbreviated trail for mobile (first + last 2 items)
$mobile_trail = Breadcrumb::get_abbreviated_trail($trail);

// Get Schema.org JSON-LD markup
$schema = Breadcrumb::get_schema($trail);

// Check if breadcrumbs should display
if (Breadcrumb::should_display()) {
    // Render breadcrumbs
}
```

### Breadcrumb Item Structure

Each breadcrumb item is an array:

```php
array(
    'url'     => 'https://example.com/page/',  // Empty string for current page
    'label'   => 'Page Title',                  // Display text
    'current' => false,                         // true for current page
)
```

---

## Twig Integration

Breadcrumbs are automatically added to the Timber context via `add_to_context()` in functions.php:

```php
$context['breadcrumb_trail']  // Full breadcrumb trail
$context['breadcrumb_mobile'] // Abbreviated for mobile
$context['breadcrumb_schema'] // JSON-LD markup
```

### Manual Inclusion in Templates

Breadcrumbs display automatically in `base.twig`, but you can include them manually:

```twig
{% include 'components/breadcrumb.twig' %}
```

With custom separator:

```twig
{% include 'components/breadcrumb.twig' with {
    separator: '→',
    trail: custom_trail
} %}
```

---

## Using the Breadcrumb Block

The optional **Breadcrumb Navigation** Gutenberg block allows placement anywhere on your page:

1. Edit any page/post in WordPress editor
2. Click the **+** button to add block
3. Search for "**Breadcrumb**"
4. Configure options:
   - **Separator**: Choose between `/`, `>`, `→`, `•`, `|`
   - **Show Home Link**: Toggle to hide/show home at the start

### Block Settings Example

```twig
{# Block renders with custom separator #}
Home → Blog → My Post
```

---

## Styling

Breadcrumb styles are in `css/inc/components/breadcrumb.scss`:

### CSS Classes

| Class                        | Purpose                              |
| ---------------------------- | ------------------------------------ |
| `.breadcrumb`                | Main wrapper container               |
| `.breadcrumb__list`          | List element (ol)                    |
| `.breadcrumb__list--desktop` | Desktop breadcrumbs (visible ≥768px) |
| `.breadcrumb__list--mobile`  | Mobile breadcrumbs (visible <768px)  |
| `.breadcrumb__item`          | Individual breadcrumb item (li)      |
| `.breadcrumb__item--current` | Current/active page                  |
| `.breadcrumb__link`          | Breadcrumb link (a tag)              |
| `.breadcrumb__text`          | Non-linked text (current page)       |
| `.breadcrumb__separator`     | Separator between items              |

### Customizing Appearance

**Change separator style:**

```scss
.breadcrumb__separator {
  color: var(--color-primary);
  font-weight: $font-bold;
}
```

**Modify link color:**

```scss
.breadcrumb__link {
  color: var(--color-accent);

  &:hover {
    color: var(--color-accent-dark);
  }
}
```

**Adjust spacing:**

```scss
.breadcrumb {
  padding: $spacing-3 0;
  margin-bottom: $spacing-6;
}
```

### Mobile Behavior

- Desktop (768px+): Shows full breadcrumb trail
- Mobile (<768px): Shows abbreviated trail with ellipsis
  - Format: Home / ... / Parent / Current

The mobile display automatically collapses intermediate items to save space.

---

## Schema.org Markup

Breadcrumbs include automatic Schema.org **BreadcrumbList** JSON-LD markup:

```json
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Home",
      "item": "https://example.com"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "Blog",
      "item": "https://example.com/blog"
    },
    {
      "@type": "ListItem",
      "position": 3,
      "name": "My Post",
      "item": "https://example.com/blog/my-post"
    }
  ]
}
```

This markup:

- Helps Google understand site structure
- Enables breadcrumb display in search results
- Improves SEO signals
- Validates with [schema.org validator](https://validator.schema.org/)

---

## Accessibility

Breadcrumb implementation meets WCAG 2.1 AA standards:

- **ARIA attributes:**

  - `<nav aria-label="Breadcrumb">` — Identifies navigation purpose
  - Links use standard `<a>` tags with proper href

- **Keyboard navigation:**

  - Fully keyboard accessible
  - Links have focus indicators
  - Tab order follows document order

- **Screen readers:**

  - Announce as "Breadcrumb" navigation
  - Current page marked without link
  - Separators hidden with `aria-hidden="true"`

- **Reduced motion:**
  - Transitions disabled when `prefers-reduced-motion` is set

---

## Performance Considerations

- **Lightweight:** Breadcrumbs add minimal performance overhead
- **Single query:** Uses only existing WordPress queries
- **Optional block:** Gutenberg block not required (auto-display works fine)
- **Schema size:** Typical JSON-LD markup is <500 bytes
- **CSS impact:** Breadcrumb styles are ~1KB minified

---

## Customization Examples

### Change Separator from Frontend

```php
add_filter('the_content', function($content) {
    if (is_single() && $sep = get_field('breadcrumb_separator')) {
        // Custom logic to change separator
    }
    return $content;
}, 1);
```

### Add Custom Breadcrumb Items

```php
// Extend the Breadcrumb class
class CustomBreadcrumb extends \StarterTheme\Helpers\Breadcrumb {
    public static function get_trail() {
        $trail = parent::get_trail();

        // Add custom item
        if (is_singular('product')) {
            array_splice($trail, -1, 0, array(
                array(
                    'url' => get_term_link(get_the_terms(get_the_ID(), 'product_cat')[0]),
                    'label' => 'Product Category'
                )
            ));
        }

        return $trail;
    }
}
```

### Filter Home Breadcrumb Label

```php
add_filter('wp_nav_menu_items', function($items) {
    // Change "Home" label via WordPress option
    update_option('breadcrumb_home_label', 'Start');
    return $items;
});
```

---

## Testing

### Manual Testing Checklist

- [ ] View breadcrumbs on blog post page
- [ ] View breadcrumbs on category archive
- [ ] View breadcrumbs on static page
- [ ] Check mobile view on phone (abbreviated display)
- [ ] Verify links work correctly
- [ ] Click breadcrumb links to navigate back
- [ ] Check 404 page still shows breadcrumbs
- [ ] Verify homepage doesn't show breadcrumbs
- [ ] View page source, confirm JSON-LD present

### Browser DevTools Testing

```javascript
// Check breadcrumb data in console
console.log(document.querySelector(".breadcrumb__list"));

// Verify Schema.org markup
const schema = JSON.parse(
  document.querySelector('script[type="application/ld+json"]').textContent,
);
console.log(schema);
```

### SEO Testing

1. **Google Search Console:**

   - Rich results → Breadcrumbs section
   - Should show "Valid" breadcrumbs

2. **Schema.org Validator:**

   - https://validator.schema.org/
   - Paste page URL, confirm BreadcrumbList valid

3. **Structured Data Testing Tool:**
   - https://developers.google.com/search/docs/appearance/structured-data
   - Select page and verify hierarchy

---

## Common Issues & Solutions

### Issue: Breadcrumbs not showing

**Check:**

- Is page not the homepage? (Breadcrumbs hidden on homepage by design)
- Are you on a singular post/page? (Breadcrumbs only on single and archive pages)
- Is `breadcrumb_trail` in Timber context? Check functions.php

**Solution:**

```php
// Verify in functions.php add_to_context
if (! isset($context['breadcrumb_trail'])) {
    require_once 'src/helpers/breadcrumbs.php';
    $context['breadcrumb_trail'] = \StarterTheme\Helpers\Breadcrumb::get_trail();
}
```

### Issue: Mobile ellipsis always showing

**Check:**

- Using custom post types with long hierarchies?
- Mobile breakpoint might be different

**Solution:**

```php
// Adjust abbreviation threshold
public static function get_abbreviated_trail($trail) {
    if (count($trail) <= 4) { // Changed from 3 to 4
        return $trail;
    }
    // ... rest of logic
}
```

### Issue: Schema.org validation failing

**Check:**

- URL structure is valid
- No special characters in labels
- JSON escaping correct

**Solution:**

```php
// Ensure proper JSON encoding
$schema = wp_json_encode($data, JSON_UNESCAPED_SLASHES);
```

---

## Size & Performance

**Files:**

- PHP utility: 250 lines
- Twig template: 40 lines
- SCSS styling: 100 lines
- Gutenberg block: 60 lines

**Bundle Impact:**

- No JS impact (breadcrumbs are server-rendered)
- CSS: ~1 KB minified
- Total theme JS: 15.78 kB (unchanged)

---

## Browser Support

| Browser             | Support    | Notes                      |
| ------------------- | ---------- | -------------------------- |
| **Chrome/Edge**     | ✅ Full    | Works on all versions      |
| **Firefox**         | ✅ Full    | Works on all versions      |
| **Safari**          | ✅ Full    | iOS 12+                    |
| **IE 11**           | ⚠️ Partial | No JSON-LD (not critical)  |
| **Mobile Browsers** | ✅ Full    | Mobile abbreviated display |

---

## Resources

- [Schema.org BreadcrumbList](https://schema.org/BreadcrumbList)
- [Google Search Breadcrumbs](https://developers.google.com/search/docs/appearance/breadcrumbs)
- [WCAG Breadcrumb Pattern](https://www.w3.org/WAI/ARIA/apg/patterns/breadcrumb/)
- [MDN Navigation Element](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/nav)

---

## Status

✅ **Feature Complete** - Ready for production use  
Last Updated: March 25, 2026  
Version: 1.0
