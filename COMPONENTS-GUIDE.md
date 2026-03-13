# Reusable Twig Components & Macros Guide

This theme uses reusable Twig components and macros to keep templates clean and reduce code duplication.

## Table of Contents

1. [Directory Structure](#directory-structure)
2. [Components vs Macros](#components-vs-macros)
3. [Using Components](#using-components)
4. [Using Macros](#using-macros)
5. [Components and BEM](#components-and-bem)
6. [Creating New Components](#creating-new-components)
7. [Creating New Macros](#creating-new-macros)
8. [Best Practices](#best-practices)
9. [Examples from the Theme](#examples-from-the-theme)

## Directory Structure

```
templates/
├── components/              # Reusable component blocks
│   ├── button.twig
│   ├── post-card.twig
│   ├── testimonial-card.twig
│   ├── heading.twig
│   └── alert.twig
├── macros.twig             # Twig macros/utilities
├── base.twig
├── index.twig
└── ...
```

## Components vs Macros

### Components

- Reusable `.twig` files in `templates/components/`
- Use `{% include %}` to render
- Best for: Complex UI blocks with HTML structure
- Examples: cards, buttons, alerts, modals

### Macros

- Functions defined in `templates/macros.twig`
- Use `{% import %}` to load, then call `{{ macros.name() }}`
- Best for: Small utilities, inline elements
- Examples: badges, icons, formatting

## Using Components

### Button Component

Renders a styled button or link element using the base `.btn` class. Renders as `<a>` if `url` is provided, otherwise `<button>`.

```twig
{% include "components/button.twig" with {
    text: "Click Me",
    url: "/path",
    classes: "btn-primary",
    icon: "arrow"
} %}
```

**Props:**

- `text` (required) - Button or link text
- `url` (optional, default: null) - Link URL; if provided, renders `<a>`
- `classes` (optional, default: '') - Additional CSS classes appended to `.btn`
- `type` (optional, default: 'button') - Button type: button, submit, or reset
- `icon` (optional, default: null) - Dashicon name without "dashicons-" prefix
- `disabled` (optional, default: false) - Disables button if true
- `target` (optional, default: null) - Link target: \_blank, \_self, etc.

### Post Card Component

Renders a card displaying post information including title, featured image, excerpt, and metadata. Base CSS class: `.post-card`.

```twig
{% include "components/post-card.twig" with {
    post: post_object,
    show_excerpt: true,
    show_meta: true,
    show_thumbnail: true
} %}
```

**Props:**

- `post` (required) - Post object to display
- `show_excerpt` (optional, default: true) - Display excerpt below title
- `show_meta` (optional, default: true) - Show author and date metadata
- `show_thumbnail` (optional, default: true) - Display featured image
- `classes` (optional, default: '') - Additional CSS classes appended to `.post-card`

### Testimonial Card Component

Renders a testimonial with author information and optional star rating. Base CSS class: `.testimonial-card`.

```twig
{% include "components/testimonial-card.twig" with {
    post: testimonial_post,
    show_rating: true
} %}
```

**Props:**

- `post` (required) - Testimonial post object
- `show_rating` (optional, default: true) - Display ACF star rating field
- `classes` (optional, default: '') - Additional CSS classes appended to `.testimonial-card`

### Heading Component

Renders a semantic heading with optional visual accent decoration. Renders `<h1>` through `<h6>` based on `level` prop.

```twig
{% include "components/heading.twig" with {
    level: 2,
    text: "Section Title",
    classes: "mb-4",
    accent: true
} %}
```

**Props:**

- `text` (required) - Heading text content
- `level` (optional, default: 2) - Heading level 1-6
- `classes` (optional, default: '') - Additional CSS classes
- `accent` (optional, default: false) - Add visual accent decoration
- `id` (optional, default: null) - HTML id attribute for anchor links

### Alert Component

Renders a contextual alert box with optional title and dismissible close button. Base CSS class: `.alert`. Modifier classes: `.alert--info`, `.alert--success`, `.alert--warning`, `.alert--error`.

```twig
{% include "components/alert.twig" with {
    type: "success",
    title: "Success!",
    message: "Operation completed",
    dismissible: true
} %}
```

**Props:**

- `message` (required) - Alert message text
- `type` (optional, default: 'info') - Alert type: info, success, warning, or error
- `title` (optional, default: null) - Optional title displayed above message
- `dismissible` (optional, default: false) - Show close button to dismiss alert
- `classes` (optional, default: '') - Additional CSS classes appended to `.alert`

## Using Macros

First, import the macros file at the top of your template:

```twig
{% import "macros.twig" as macros %}
```

Then use them throughout the template:

### Badge Macro

Returns an inline badge element (typically `<span class="badge">`) for short status labels.

```twig
{{ macros.badge('New', 'success') }}
{{ macros.badge('Featured', 'primary') }}
```

### Icon Macro

Returns a Dashicon element (typically `<span class="dashicon dashicon-{name}">`).

```twig
{{ macros.icon('heart', 'large') }}
{{ macros.icon('star-filled') }}
```

### Truncate Macro

Returns text truncated to specified character length with ellipsis.

```twig
{{ macros.truncate(long_text, 100) }}
```

### Price Macro

Returns formatted currency string with symbol and proper decimal places.

```twig
{{ macros.price(99.99, '$') }}
{{ macros.price(1500, '€') }}
```

### Rating Macro

Returns star rating display (typically HTML with filled/unfilled stars).

```twig
{{ macros.rating(4, 5) }}
{{ macros.rating(5, 5) }}
```

### Meta List Macro

Returns a formatted list of metadata items with icons and labels.

```twig
{% set meta_items = [
    { icon: 'clock', label: 'Published', value: post.date|date('M d, Y') },
    { icon: 'admin-users', label: 'By', value: post.author.name },
    { icon: 'folder', label: 'Category', value: post.category }
] %}

{{ macros.meta_list(meta_items) }}
```

### Read Time Macro

Returns estimated reading time based on word count (defaults to 200 words per minute).

```twig
{{ macros.read_time(post.content) }}
{{ macros.read_time(post.content, 250) }}
```

### Social Links Macro

Returns a list of social media links styled as icon buttons.

```twig
{% set socials = {
    'facebook': 'https://facebook.com/yourpage',
    'twitter': 'https://twitter.com/yourhandle',
    'linkedin': 'https://linkedin.com/company/yourcompany'
} %}

{{ macros.social_links(socials) }}
```

### Breadcrumbs Macro

Returns a navigation breadcrumb list. Last item is not linked (current page).

```twig
{% set breadcrumbs = [
    { label: 'Home', url: site.url },
    { label: 'Blog', url: '/blog' },
    { label: 'My Post' }
] %}

{{ macros.breadcrumbs(breadcrumbs) }}
```

## Components and BEM

Each Twig component maps directly to a single BEM block in your SCSS. This keeps template structure and styling organized together.

**File relationship:**

- Template: `templates/components/post-card.twig`
- Stylesheet: `css/inc/components/post-card.scss`

**BEM structure:**

```scss
.post-card {
  // Block (component)
  &__image {
  } // Element (part of component)
  &__title {
  } // Element
  &__meta {
  } // Element
  &--featured {
  } // Modifier (variant)
}
```

When creating a new component, always create the corresponding SCSS block. See [BEM-GUIDE.md](BEM-GUIDE.md) for detailed styling patterns.

---

## Creating New Components

### Step 1: Create the component file

Create a new file in `templates/components/my-component.twig`:

```twig
{#
    My Component

    Brief description of what this component does

    Usage:
    {% include "components/my-component.twig" with {
        prop1: "value",
        prop2: true
    } %}

    Available Props:
    - prop1: Description
    - prop2: Description
#}

<div class="my-component">
    {% if prop2 %}
        <span class="special">{{ prop1 }}</span>
    {% else %}
        <span>{{ prop1 }}</span>
    {% endif %}
</div>
```

### Step 2: Create CSS for your component

Create `css/inc/components/my-component.scss` with the BEM block structure:

```scss
// my-component.scss
.my-component {
  // Base styles
  padding: 1rem;

  // Elements
  &__title {
    font-weight: bold;
  }

  // Modifiers
  &--featured {
    background: highlight-color;
  }
}
```

Then import in `style.scss`:

```scss
@import "inc/components/my-component";
```

### Step 3: Use in your templates

```twig
{% include "components/my-component.twig" with {
    prop1: "Hello",
    prop2: true
} %}
```

---

## Best Practices

### For Components

✅ **Always document props** - Include usage examples in comments at top of template
✅ **Use meaningful class names** - BEM naming: `.component__element--modifier`
✅ **Provide sensible defaults** - Use Twig's `|default()` filter for optional props
✅ **Keep it simple** - One component = one responsibility
✅ **Make it flexible** - Allow customization via props and CSS classes
✅ **Map to BEM** - Create corresponding SCSS block for styling

### For Macros

✅ **Keep macros small** - Ideal for output formatting, not complex logic
✅ **Document return values** - Note what HTML/format each macro outputs
✅ **Use consistent styling** - Macros should render with predictable CSS classes
✅ **Keep logic simple** - Complex logic belongs in PHP, not Twig macros

## Creating New Macros

Add new macros to `templates/macros.twig`:

```twig
{% macro my_utility(param1, param2 = 'default') %}
    <span class="utility utility--{{ param2 }}">{{ param1 }}</span>
{% endmacro %}
```

Then use:

```twig
{% import "macros.twig" as macros %}
{{ macros.my_utility('Hello', 'primary') }}
```

## Benefits

✅ **DRY Principle** - No duplicate code
✅ **Consistency** - Same component renders identically everywhere
✅ **Maintainability** - Update once, used everywhere
✅ **Readability** - Cleaner, more semantic templates
✅ **Reusability** - Mix and match components
✅ **Testability** - Easier to test individual components

## Examples from the Theme

**Single Post Template:**

```twig
{% extends "base.twig" %}
{% import "macros.twig" as macros %}

{% block content %}
    {% include "components/heading.twig" with {
        level: 1,
        text: post.title
    } %}

    {{ macros.read_time(post.content) }}

    <article>{{ post.content }}</article>
{% endblock %}
```

**Grid of Posts (from pre-fetched context):**

```twig
{# Assumes posts were prepared in views/index.php #}
<div class="posts-grid">
    {% for post in posts %}
        {% include "components/post-card.twig" with { post: post } %}
    {% endfor %}
</div>
```

**Testimonials Section (from pre-fetched context):**

```twig
{# Assumes testimonials were fetched and passed in context via PHP #}
<section class="testimonials">
    {% include "components/heading.twig" with { text: 'What People Say' } %}

    <div class="testimonials-grid">
        {% for testimonial in testimonials %}
            {% include "components/testimonial-card.twig" with { post: testimonial } %}
        {% endfor %}
    </div>
</section>
```
