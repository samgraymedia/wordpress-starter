# Theme Component & Block Library

**Complete visual reference and quick-lookup guide for all available Gutenberg blocks and Twig components**

This guide helps you find the right component for your layout needs and provides quick copy-paste code examples.

## Table of Contents

1. [Block Quick Finder](#block-quick-finder)
2. [Component Quick Finder](#component-quick-finder)
3. [When to Use Each Block](#when-to-use-each-block)
4. [When to Use Each Component](#when-to-use-each-component)
5. [Code Examples](#code-examples)
6. [Accessibility Checklist](#accessibility-checklist)

---

## Block Quick Finder

### By Purpose

**I need to display pricing plans...**
→ Use **[Pricing/Plans Block](#pricing-plans-block)**

**I need a flexible grid of items...**
→ Use **[Cards Grid Block](#cards-grid-block)**

**I need to collect email signups...**
→ Use **[Newsletter Block](#newsletter-block)**

**I need a hero section...**
→ Use **[Hero Block](#hero-block)**

**I need an FAQ section...**
→ Use **[FAQ / Search Block](#faq--search-block)**

**I need a large image with text...**
→ Use **[Image + Text Block](#image--text-block)**

**I need a call-to-action...**
→ Use **[CTA Block](#cta-block)** or **[Hero Block](#hero-block)**

**I need to display blog posts...**
→ Use **[Blog Posts Block](#blog-posts-block)**

**I need expandable content sections...**
→ Use **[Accordion Block](#accordion-block)**

**I need tabs for organizing content...**
→ Use **[Tabs Block](#tabs-block)**

---

## Component Quick Finder

### By Purpose

**I need a button...**
→ Use **[Button Component](#button-component)**

**I need to display a post preview...**
→ Use **[Post Card Component](#post-card-component)**

**I need an alert/notification...**
→ Use **[Alert Component](#alert-component)**

**I need a testimonial display...**
→ Use **[Testimonial Card Component](#testimonial-card-component)**

**I need a heading with styling...**
→ Use **[Heading Component](#heading-component)**

**I need a badge/label...**
→ Use **[Badge Component](#badge-component)**

---

## When to Use Each Block

### Layout & Structure Blocks

#### Hero Block

- **Use when:** You need a large, eye-catching opening section
- **Features:** Background image, title, subtitle, buttons
- **Examples:** Homepage intro, landing page top

#### Banner Block

- **Use when:** You need a promotional bar with optional overlay
- **Features:** Title, subtitle, optional background image, overlay color
- **Examples:** Sale announcement, feature highlight

#### Image + Text Block

- **Use when:** You need side-by-side image and text layout
- **Features:** 2-column layout (image left or right), optional CTA
- **Examples:** Feature showcase, team member highlight

#### Gallery Block

- **Use when:** You need to display multiple images in a grid
- **Features:** Lightbox, responsive columns, image hover effects
- **Examples:** Portfolio, product gallery, team photos

#### Video Block

- **Use when:** You need to embed a video player
- **Features:** Responsive player, keyboard controls, optional captions
- **Examples:** Tutorial video, testimonial video

---

### Content Organization Blocks

#### Text Block

- **Use when:** You need rich-text content (paragraphs, lists, formatting)
- **Features:** WYSIWYG editor, formatting options
- **Examples:** Article content, page copy

#### Tabs Block

- **Use when:** You need to organize content in tabs
- **Features:** Multiple tabs, keyboard navigation (Tab key)
- **Examples:** Feature comparison, product specifications

#### Accordion Block

- **Use when:** You need expandable content sections
- **Features:** Collapse/expand, optional first-open, FAQPage schema
- **Examples:** FAQ, feature list, terms & conditions

#### Blog Posts Block

- **Use when:** You need to display blog posts in a grid
- **Features:** Post selection, excerpt display, pagination
- **Examples:** Blog archive, latest posts widget

#### Cards Grid Block ⭐ **NEW**

- **Use when:** You need flexible cards for any repeating content
- **Features:** 3 styles (standard/overlay/horizontal), highlight option, image height control
- **Examples:** Service showcase, team grid, product highlights, feature list

#### FAQ / Search Block ⭐ **NEW**

- **Use when:** You need searchable, filterable FAQ section
- **Features:** Real-time search, category filter, expandable items, schema markup
- **Examples:** Help/support section, knowledge base, product FAQs

---

### Call-to-Action & Commerce Blocks

#### CTA Block

- **Use when:** You need a prominent call-to-action
- **Features:** Headline, subtitle, multiple button options
- **Examples:** "Sign up now", "Get started", "Learn more"

#### Pricing/Plans Block ⭐ **NEW**

- **Use when:** You need to display pricing tiers
- **Features:** 2 layouts (cards/table), featured tier, feature comparison, currency selector
- **Examples:** Subscription pricing, service packages, plan comparison

---

### Interactive & Engagement Blocks

#### Newsletter Block

- **Use when:** You need email signup form
- **Features:** Email validation, success message, spam protection
- **Examples:** Email newsletter signup, lead capture

#### Contact Block

- **Use when:** You need contact information display
- **Features:** Phone, email, address, optional map embed
- **Examples:** Contact page, footer contact info

#### Share Buttons Block

- **Use when:** You need social sharing buttons
- **Features:** Multiple platform options, icon styling
- **Examples:** Blog post sharing, content promotion

---

### Utility Blocks

#### Announcement Block

- **Use when:** You need an important banner message
- **Features:** Color-coded types (info/success/warning/error), dismissible, persistent state
- **Examples:** Site announcement, maintenance notice, promotion banner

---

## When to Use Each Component

### Button Component

```twig
{% include "components/button.twig" with {
    text: "Click Me",
    url: "#",
    variant: "primary",
    size: "md",
    icon: "arrow-right"
} %}
```

**Use when:** You need a CTA button in a template
**Variants:** primary, secondary, danger, ghost
**Sizes:** sm, md, lg

---

### Heading Component

```twig
{% include "components/heading.twig" with {
    level: 2,
    text: "Section Title",
    accent: true
} %}
```

**Use when:** You need consistently styled headings
**Levels:** 1-6 (h1-h6)
**Features:** Optional accent styling

---

### Post Card Component

```twig
{% include "components/post-card.twig" with {
    post: post,
    show_excerpt: true,
    show_meta: true,
    show_thumbnail: true
} %}
```

**Use when:** You need to display a post preview
**Features:** Thumbnail, title, excerpt, date, author
**Example:** Blog archive list, related posts widget

---

### Alert Component

```twig
{% include "components/alert.twig" with {
    message: "Success!",
    type: "success",
    title: "Complete",
    dismissible: true
} %}
```

**Use when:** You need to display an alert/notification
**Types:** info, success, warning, danger
**Features:** Icon, title, message, dismissible option

---

### Testimonial Card Component

```twig
{% include "components/testimonial-card.twig" with {
    post: testimonial,
    show_rating: true
} %}
```

**Use when:** You need to display customer testimonials
**Features:** Avatar, name, company, quote, rating
**Example:** Testimonials grid, client quotes

---

### Badge Component

```twig
{% include "components/badge.twig" with {
    text: "New",
    variant: "success"
} %}
```

**Use when:** You need a small label or tag
**Variants:** primary, secondary, success, warning, danger

---

## Code Examples

### Example 1: Service Showcase Page

Use **Cards Grid Block** with 3 columns, standard layout:

```twig
{# In page template #}
{# Admin inserts Cards Grid block in editor #}
{# Each card: Image (service icon), Title, Description, Link #}
{# Renders as 3-column grid on desktop, 1 column on mobile #}
```

**Alternative with Components:**

```twig
<div class="services-grid">
  {% for service in services %}
    {% include "components/post-card.twig" with { post: service } %}
  {% endfor %}
</div>
```

---

### Example 2: Pricing Page

Use **Pricing/Plans Block** with comparison table layout:

1. Admin adds block to page
2. Sets layout to "comparison table"
3. Adds 3-4 plans with features
4. Marks one as "Featured"
5. Result: Professional pricing comparison table

---

### Example 3: Product FAQs

Use **FAQ / Search Block** with category filter:

1. Admin adds block to product page
2. Enables search + category filter
3. Adds 8-10 FAQ items with categories
4. Sets "Expand First Item" = true
5. Result: Searchable FAQ with Google schema markup

---

### Example 4: Team/About Page

Combine **Hero Block** + **Cards Grid Block** (horizontal layout):

1. Hero section at top (large image + intro)
2. Cards Grid below with team members
3. Each card: Photo, name, role, bio, social links
4. Uses horizontal layout for larger displays

---

### Example 5: Homepage

Multi-block composition:

```
1. Hero Block (banner + CTA)
2. Blog Posts Block (latest articles)
3. Cards Grid Block (services/features)
4. Testimonials (using Post Card components)
5. FAQ / Search Block
6. CTA Block (final call-to-action)
7. Newsletter Block (email signup)
```

---

## Accessibility Checklist

When building pages, ensure:

### For All Blocks

- ✅ Images have descriptive alt text
- ✅ Headings use proper hierarchy (h2 → h3 → h4, not jumps)
- ✅ Color not the only way to convey information
- ✅ Sufficient contrast (4.5:1 ratio for text)

### For Interactive Blocks

- ✅ Accordion/Tabs work with keyboard (Tab, Enter, Arrow keys)
- ✅ Focus indicators visible on all interactive elements
- ✅ ARIA labels on buttons and form controls
- ✅ Expandable sections announce state (expanded/collapsed)

### For FAQ / Search Block

- ✅ Search results clearly indicate which items match
- ✅ Category filter options accessible via keyboard
- ✅ Screen readers announce filtered results
- ✅ Schema markup validates (test with Google Schema Validator)

### For Cards Grid Block

- ✅ Images have alt text (required field)
- ✅ Links have descriptive text (not "click here")
- ✅ Hover effects have non-hover keyboard alternative
- ✅ Overlay text readable (sufficient contrast)

### For Pricing/Plans Block

- ✅ Currency and price clearly displayed
- ✅ Feature inclusion visible (checkmarks/X icons)
- ✅ CTA buttons clearly distinguishable
- ✅ Featured plan marked with text (not just visual)

---

## Performance Considerations

### Image Optimization

- Cards Grid, Gallery, Hero blocks all use lazy-loading
- Always include ALT text (improves SEO)
- Use appropriately sized images (avoid oversized uploads)

### Search/Filter Performance

- FAQ Search block filters on client-side (instant)
- No server requests needed for search/filter
- Scales well up to 200+ FAQ items

### Schema Markup

- Pricing block outputs Product schema
- FAQ block outputs FAQPage schema
- Accordion block outputs FAQPage schema
- All improve SEO and enable rich snippets

---

## Migration Guide

### Converting Old Components to New Blocks

**Old Way:** Manual Post Card components in templates

```twig
{% for item in items %}
  {% include "components/post-card.twig" with { post: item } %}
{% endfor %}
```

**New Way:** Use Cards Grid Block in editor

- No template coding needed
- Easy for non-technical admins
- Multiple layout options
- Visual builder interface

---

## Related Documentation

- **[BLOCKS-GUIDE.md](BLOCKS-GUIDE.md)** - Detailed block field reference
- **[COMPONENTS-GUIDE.md](COMPONENTS-GUIDE.md)** - Twig components reference
- **[BEM-GUIDE.md](BEM-GUIDE.md)** - CSS class naming (understand styling)
- **[QUICK-REFERENCE.md](QUICK-REFERENCE.md)** - Common commands and tips

---

## Support

**Can't find what you need?**

1. Check [BLOCKS-GUIDE.md](BLOCKS-GUIDE.md) for detailed field documentation
2. Review [QUICK-REFERENCE.md](QUICK-REFERENCE.md) for common patterns
3. See [DEVELOPER-GUIDE.md](DEVELOPER-GUIDE.md#debugging--troubleshooting) for debugging
4. Consult [Creating New Blocks](BLOCKS-GUIDE.md#creating-new-blocks) to build custom blocks

---

**Last Updated:** March 24, 2026
