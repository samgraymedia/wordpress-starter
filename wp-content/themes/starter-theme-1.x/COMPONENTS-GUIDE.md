# Reusable Twig Components & Macros Guide

This theme uses reusable Twig components and macros to keep templates clean and reduce code duplication.

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

```twig
{% include "components/button.twig" with {
    text: "Click Me",
    url: "/path",
    classes: "btn-primary",
    icon: "arrow"
} %}
```

**Props:**

- `text` (required) - Button text
- `url` - Link URL (renders as `<a>` if provided)
- `classes` - Additional CSS classes
- `type` - Button type: button|submit|reset (default: button)
- `icon` - Icon name without "dashicons-" prefix
- `disabled` - Boolean
- `target` - Link target: \_blank|\_self|etc

### Post Card Component

```twig
{% include "components/post-card.twig" with {
    post: post_object,
    show_excerpt: true,
    show_meta: true,
    show_thumbnail: true
} %}
```

**Props:**

- `post` (required) - Post object
- `show_excerpt` - Display excerpt (default: true)
- `show_meta` - Show author/date (default: true)
- `show_thumbnail` - Show featured image (default: true)
- `classes` - Additional CSS classes

### Testimonial Card Component

```twig
{% include "components/testimonial-card.twig" with {
    post: testimonial_post,
    show_rating: true
} %}
```

**Props:**

- `post` (required) - Testimonial post object
- `show_rating` - Display star rating (default: true)
- `classes` - Additional CSS classes

### Heading Component

```twig
{% include "components/heading.twig" with {
    level: 2,
    text: "Section Title",
    classes: "mb-4",
    accent: true
} %}
```

**Props:**

- `text` (required) - Heading text
- `level` - Heading level 1-6 (default: 2)
- `classes` - Additional CSS classes
- `accent` - Add accent decoration (default: false)
- `id` - HTML id attribute

### Alert Component

```twig
{% include "components/alert.twig" with {
    type: "success",
    title: "Success!",
    message: "Operation completed",
    dismissible: true
} %}
```

**Props:**

- `message` (required) - Alert message
- `type` - Type: info|success|warning|error (default: info)
- `title` - Optional title
- `dismissible` - Show close button (default: false)
- `classes` - Additional CSS classes

## Using Macros

First, import the macros file at the top of your template:

```twig
{% import "macros.twig" as macros %}
```

Then use them throughout the template:

### Badge Macro

```twig
{{ macros.badge('New', 'success') }}
{{ macros.badge('Featured', 'primary') }}
```

### Icon Macro

```twig
{{ macros.icon('heart', 'large') }}
{{ macros.icon('star-filled') }}
```

### Truncate Macro

```twig
{{ macros.truncate(long_text, 100) }}
```

### Price Macro

```twig
{{ macros.price(99.99, '$') }}
{{ macros.price(1500, '€') }}
```

### Rating Macro

```twig
{{ macros.rating(4, 5) }}
{{ macros.rating(5, 5) }}
```

### Meta List Macro

```twig
{% set meta_items = [
    { icon: 'clock', label: 'Published', value: post.date|date('M d, Y') },
    { icon: 'admin-users', label: 'By', value: post.author.name },
    { icon: 'folder', label: 'Category', value: post.category }
] %}

{{ macros.meta_list(meta_items) }}
```

### Read Time Macro

```twig
{{ macros.read_time(post.content) }}
{{ macros.read_time(post.content, 250) }}
```

### Social Links Macro

```twig
{% set socials = {
    'facebook': 'https://facebook.com/yourpage',
    'twitter': 'https://twitter.com/yourhandle',
    'linkedin': 'https://linkedin.com/company/yourcompany'
} %}

{{ macros.social_links(socials) }}
```

### Breadcrumbs Macro

```twig
{% set breadcrumbs = [
    { label: 'Home', url: site.url },
    { label: 'Blog', url: '/blog' },
    { label: 'My Post' }
] %}

{{ macros.breadcrumbs(breadcrumbs) }}
```

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

### Step 2: Use in your templates

```twig
{% include "components/my-component.twig" with {
    prop1: "Hello",
    prop2: true
} %}
```

### Best Practices

✅ **Always document props** - Include usage examples in comments
✅ **Use meaningful class names** - Prefix with component name (e.g., `my-component__title`)
✅ **Provide sensible defaults** - Use Twig's `|default()` filter
✅ **Keep it simple** - One component = one responsibility
✅ **Make it flexible** - Allow customization via props/classes
✅ **Think about styling** - Components need corresponding CSS/SCSS

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

**Grid of Posts:**

```twig
<div class="posts-grid">
    {% for post in posts %}
        {% include "components/post-card.twig" with { post: post } %}
    {% endfor %}
</div>
```

**Testimonials Section:**

```twig
{% set testimonials = get_posts({ post_type: 'testimonials' }) %}

<section class="testimonials">
    {% include "components/heading.twig" with { text: 'What People Say' } %}

    <div class="testimonials-grid">
        {% for testimonial in testimonials %}
            {% include "components/testimonial-card.twig" with { post: testimonial } %}
        {% endfor %}
    </div>
</section>
```
