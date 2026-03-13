# Quick Reference Guide

**Fast lookup for common development tasks**

This is a condensed reference guide. For detailed information, see [DEVELOPER-GUIDE.md](DEVELOPER-GUIDE.md).

**Note:** This guide covers core theme features. For WooCommerce-specific tasks (only when WooCommerce plugin is active), see [WOOCOMMERCE-QUICK-REF.md](WOOCOMMERCE-QUICK-REF.md).

---

## 📍 File Locations

| Task              | File                                                            |
| ----------------- | --------------------------------------------------------------- |
| Theme settings    | `src/config.php`                                                |
| Access config     | `src/theme-config.php`                                          |
| Post types        | `src/post-types/*.php`                                          |
| Blocks            | `src/blocks/*.php` (fields) + `views/blocks/*.twig` (templates) |
| Components        | `templates/components/*.twig`                                   |
| Macros            | `templates/macros.twig`                                         |
| **JavaScript**    | **`scripts/index.js`**                                          |
| **JS Utils**      | **`scripts/utils/*.js`**                                        |
| **JS Modules**    | **`scripts/modules/*.js`** (features)                           |
| **JS Hamburger**  | **`scripts/hamburger.js`** (mobile nav)                         |
| SCSS variables    | `css/inc/base/vars.scss`                                        |
| Button styles     | `css/inc/base/btns.scss`                                        |
| Navigation styles | `css/inc/global/nav.scss`                                       |
| Block styles      | `css/inc/blocks/*.scss`                                         |
| Component styles  | `css/inc/components/*.scss`                                     |
| Utilities         | `css/inc/utilities/helpers.scss`                                |
| Main styles       | `style.scss`                                                    |
| **Build config**  | **`vite.config.js`**                                            |
| **Lint config**   | **`.eslintrc.json`**                                            |
| Asset enqueue     | `functions.php` (theme_enqueue_assets)                          |
| Homepage          | `templates/index.twig`                                          |
| Base layout       | `templates/base.twig`                                           |

---

## 🚀 Quick Commands

```bash
# Start development (builds JS + CSS with hot reload)
cd wp-content/themes/starter-theme
npm run watch

# Or run individual tasks:
npm run dev          # Vite dev server
npm run watch:css    # Watch SCSS changes

# Build for production
npm run build

# Check JavaScript for issues
npm run lint
npm run lint:fix     # Auto-fix issues

# Check for errors
# Look in wp-content/debug.log

# Git commands
git status
git add .
git commit -m "Your message"
git push
```

---

## 🔧 Configuration

All theme configuration in `src/config.php`. For detailed examples and explanations, see [CONFIG-GUIDE.md](CONFIG-GUIDE.md).

**Common tasks:**

| Task           | File             | Key/Property          |
| -------------- | ---------------- | --------------------- |
| Add menu       | `src/config.php` | `menus`               |
| Add image size | `src/config.php` | `image_sizes`         |
| Add setting    | `src/config.php` | Custom key            |
| Access config  | Code             | `Theme_Config::get()` |

---

## 🎨 Template Usage

### Include Component

```twig
{% include "components/button.twig" with {
    text: "Click me",
    url: "#"
} %}
```

### Use Macro

```twig
{% import "macros.twig" as m %}

{{ m.badge("New", "primary") }}
{{ m.price(99.99, "USD") }}
{{ m.icon("star", "my-class") }}
```

### Loop Posts

```twig
{% for post in posts %}
    <h2>{{ post.title }}</h2>
    <p>{{ post.excerpt }}</p>
{% endfor %}
```

### Get ACF Field

```twig
{{ post.get_field( 'field_key' ) }}
```

### Conditional

```twig
{% if post.thumbnail %}
    <img src="{{ post.thumbnail.src }}" alt="{{ post.title }}" />
{% endif %}
```

### Debug

```twig
{# Print specific variable #}
<pre>{{ dump(post) }}</pre>

{# Print all context #}
<pre>{{ dump() }}</pre>
```

---

## 🎭 CSS/BEM

### BEM Structure

```scss
// Block
.component {
}

// Element (__)
.component__title {
}
.component__content {
}

// Modifier (--)
.component--large {
}
.component--featured {
}
```

### Create Component

**File:** `css/inc/components/my-component.scss`

```scss
.my-component {
  padding: 1rem;

  &__title {
    font-size: 1.5rem;
  }

  &--featured {
    border: 2px solid $primary;
  }
}
```

**Update imports in `style.scss`:**

```scss
@import "inc/components/my-component";
```

### Common Utilities

```html
<!-- Spacing (Bootstrap) -->
<div class="mt-4 mb-2 p-4">Content</div>

<!-- Typography (Bootstrap) -->
<p class="text-center fw-bold text-primary">Text</p>

<!-- Display (Bootstrap) -->
<div class="d-flex justify-content-center align-items-center">Centered</div>

<!-- Background (Bootstrap) -->
<div class="bg-primary text-white rounded">Card</div>
```

---

## 📝 Post Types

### Create Post Type

**File:** `src/post-types/my-type.php`

```php
<?php
// PostType: My Type
// Name: My Type Name

return [
    'args' => [
        'label' => 'My Types',
        'public' => true,
        'supports' => ['title', 'editor', 'thumbnail'],
        'menu_position' => 5,
    ],
];
```

Post type auto-registered on next page load.

---

## 🎯 Common Tasks

### Access Theme Name

```php
$name = Theme_Config::get_theme_meta()['name'];
```

### Get All Post Types

```php
$types = Theme_Config::get_post_types();
```

### Get Menu Locations

```php
$menus = Theme_Config::get_menus();
```

### Loop Through Posts

```twig
{% for post in posts %}
    {% include "components/post-card.twig" with {post: post} %}
{% endfor %}
```

### Check User Permission

```php
if ( current_user_can( 'edit_post', $post_id ) ) {
    // User can edit
}
```

```twig
{% if current_user and current_user.can( 'manage_options' ) %}
    {# Admin content #}
{% endif %}
```

### Get ACF Field

```php
$value = get_field( 'field_key', $post_id );
```

```twig
{{ post.get_field( 'field_key' ) }}
```

### Output URL Safely

```twig
<a href="{{ post.link }}">{{ post.title }}</a>
```

### Output HTML Safely

```twig
{{ post.content }}  {# Output depends on Twig context and WordPress content filters #}
```

---

## 🐛 Debugging

### Enable Debug Mode

**File:** `wp-config.php`

```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
```

Check logs: `wp-content/debug.log`

### Debug in PHP

```php
error_log( 'Value: ' . print_r( $var, true ) );
```

### Debug in Twig

```twig
{{ dump() }}        {# All variables #}
{{ dump(post) }}    {# Specific variable #}
```

### Check What's Registered

```twig
{{ dump(wp) }}  {# WordPress object with registered items #}
```

---

## 🔐 Security

### Sanitize Input

```php
$email = sanitize_email( $_POST['email'] );
$text = sanitize_text_field( $_POST['text'] );
```

### Escape Output

```php
echo esc_html( $post->title );
echo esc_url( $link );
echo esc_attr( $attr );
```

### Check Nonce

```php
wp_verify_nonce( $_POST['nonce'], 'action_name' );
```

### Check Capability

```php
current_user_can( 'edit_post', $post_id );
```

---

## 📊 Components Reference

### Heading Component

```twig
{% include "components/heading.twig" with {
    level: 2,
    text: "My Heading",
    accent: true
} %}
```

### Button Component

```twig
{% include "components/button.twig" with {
    text: "Click",
    url: "#",
    classes: "btn-primary",
    icon: "arrow-right"
} %}
```

### Post Card Component

```twig
{% include "components/post-card.twig" with {
    post: post,
    show_excerpt: true,
    show_meta: true,
    show_thumbnail: true
} %}
```

### Alert Component

```twig
{% include "components/alert.twig" with {
    message: "Success!",
    type: "success",
    title: "Done",
    dismissible: true
} %}
```

### Testimonial Card Component

```twig
{% include "components/testimonial-card.twig" with {
    post: testimonial,
    show_rating: true
} %}
```

---

## 🎨 Macros Reference

### Badge

```twig
{% import "macros.twig" as m %}
{{ m.badge("New", "primary") }}
```

### Icon

```twig
{{ m.icon("star", "my-class") }}
```

### Price

```twig
{{ m.price(99.99, "USD") }}
```

### Rating

```twig
{{ m.rating(4, 5) }}  {# 4 out of 5 stars #}
```

### Truncate

```twig
{{ m.truncate(long_text, 100) }}
```

### Meta List

```twig
{{ m.meta_list(["By John", "March 1", "5 min read"]) }}
```

### Read Time

```twig
{{ m.read_time(post.content, 200) }}  {# words per minute #}
```

### Breadcrumbs

```twig
{% import "macros.twig" as m %}
{{ m.breadcrumbs([
    {label: "Home", url: site.url},
    {label: "Blog", url: "/blog"},
    {label: "Post Title"}
]) }}
```

---

## 🔄 SCSS Variables

```scss
// Colors
$primary, $secondary, $black, $white, $gray-light

// Typography
$font-light, $font-normal, $font-bold
$font-xs, $font-sm, $font-base, $font-lg, $font-xl, $font-2xl

// Spacing
$spacing-1 through $spacing-6

// Breakpoints
$breakpoint-xs, $breakpoint-sm, $breakpoint-md, $breakpoint-lg, $breakpoint-xl

// Transitions
$transition-fast, $transition-normal, $transition-slow

// Shadows
$shadow-sm, $shadow-md, $shadow-lg
```

---

## 📚 Documentation Files

| File                                       | Purpose                        |
| ------------------------------------------ | ------------------------------ |
| [README.md](README.md)                     | Installation & quick start     |
| [DEVELOPER-GUIDE.md](DEVELOPER-GUIDE.md)   | Complete development reference |
| [ARCHITECTURE.md](ARCHITECTURE.md)         | System design & patterns       |
| [CONFIG-GUIDE.md](CONFIG-GUIDE.md)         | Configuration system           |
| [COMPONENTS-GUIDE.md](COMPONENTS-GUIDE.md) | Twig components                |
| [BEM-GUIDE.md](BEM-GUIDE.md)               | CSS architecture               |
| [QUICK-REFERENCE.md](QUICK-REFERENCE.md)   | This file                      |

---

## 🆘 Common Issues

### SCSS Won't Compile

```bash
npm run sass  # Check error message
npm install   # Reinstall dependencies
```

### Post Type Not Showing

- Check file location: `src/post-types/my-type.php`
- Check file header format (must match template)
- Clear cache and refresh

### Component Not Rendering

- Check file path in `{% include %}`
- Check prop names match template
- Use `{{ dump() }}` to debug

### Styles Not Updating

- Ensure `npm run watch` is running
- Hard refresh browser (Cmd+Shift+R)
- Check console for errors

---

## Registry System

The theme auto-registers content definitions and templates from dedicated folders:

| Item           | File Location              | Auto-registered |
| -------------- | -------------------------- | --------------- |
| Post types     | `src/post-types/*.php`     | Yes             |
| Taxonomies     | `src/taxonomies/*.php`     | Yes             |
| Page templates | `src/page-templates/*.php` | Yes             |
| Image sizes    | `src/config.php`           | Yes             |

**To add a new item:** Create file in folder → Auto-registered on next page load. No hooks needed.

---

## 🔍 Advanced Debug Tips

### Dump ACF Field Data

```twig
{# See all ACF fields on post #}
{{ dump(post.get_fields()) }}
```

### Dump Post Meta

```twig
{# See all custom post meta #}
{{ dump(post.meta()) }}
```

### Check Template Variables

```twig
{# Print WordPress global #}
{{ dump(wp) }}

{# Print specific post property #}
{{ dump(post.thumbnail) }}
```

---

## Useful Links

- [Timber Docs](https://timber.github.io/docs/)
- [Twig Docs](https://twig.symfony.com/)
- [ACF Docs](https://www.advancedcustomfields.com/resources/)
- [WordPress Docs](https://developer.wordpress.org/)
- [Bootstrap Docs](https://getbootstrap.com/docs/5.3/)
- [BEM Docs](https://bem.info/)

---

## 💡 Tips & Tricks

### View all template variables

```twig
<pre>{{ dump() }}</pre>
```

### Check component props

Look at component file comments:

```twig
{# Props: title, content, variant #}
```

### Search for function usage

Use IDE search: `Cmd+F` or `Ctrl+F`

### Find SCSS variable

Open `css/inc/base/vars.scss` for all variables

### Test responsive

Right-click → Inspect → Toggle device toolbar

### Clear Cache

Flush permalinks: Settings → Permalinks → Save (any change then save)
Browser: Hard refresh (Cmd+Shift+R or Ctrl+Shift+R)
Cache plugin: Deactivate temporarily if needed

---

**Version 1.0** | March 2026 | Theme v1.0.0
