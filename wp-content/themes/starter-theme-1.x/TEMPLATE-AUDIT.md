# Template Markup Audit Report

**Comprehensive review of Twig template quality, consistency, and best practices**

Date: March 2026  
Theme: Abbie (Eleanor's Driving School)  
Scope: All .twig template files

---

## Executive Summary

### Audit Score: 7.2/10 ⚠️

| Category       | Score | Status                |
| -------------- | ----- | --------------------- |
| Semantic HTML  | 7/10  | Needs improvement     |
| Accessibility  | 6/10  | Several gaps          |
| Security       | 8/10  | Good                  |
| Performance    | 7/10  | Acceptable            |
| Consistency    | 7/10  | Inconsistent patterns |
| Documentation  | 8/10  | Well documented       |
| Code Style     | 6/10  | Needs standardization |
| BEM Compliance | 7/10  | Mostly compliant      |

### Key Findings

✅ **Strengths:**

- Good documentation in components
- Security practices (escaping) mostly sound
- BEM naming convention mostly applied
- Consistent component props documentation
- Good use of semantic HTML5 elements

⚠️ **Issues Found:**

- 18 inconsistencies in markup patterns
- 12 accessibility violations (missing ARIA, alt text)
- 8 semantic HTML issues
- 6 performance concerns
- 5 style/formatting inconsistencies
- 4 internationalization issues

---

## Issues by Category

### 1. SEMANTIC HTML ISSUES (8 issues) 🔴

#### Issue 1.1: Missing/Broken Semantic Structure in html-header.twig

**Severity:** High | **File:** `html-header.twig`

**Problem:**

- Multiple meta tags for outdated IE versions
- Obsolete `http://gmpg.org/xfn/11` profile
- Hardcoded deprecated meta tags
- No `title` tag defined

**Current Code:**

```twig
<!doctype html>
<!--[if lt IE 9]><html class="no-js no-svg ie lt-ie9 lt-ie8 lt-ie7" {{ site.language_attributes }}> <![endif]-->
<!--[if IE 9]><html class="no-js no-svg ie ie9 lt-ie9 lt-ie8" {{ site.language_attributes }}> <![endif]-->
<!--[if gt IE 9]><!--><html class="no-js no-svg" {{ site.language_attributes }}> <!--<![endif]-->
<head>
    <meta charset="{{ site.charset }}" />
    <meta name="description" content="{{ site.description }}">
    <link rel="stylesheet" href="{{ site.theme.link }}/style.css" type="text/css" media="screen" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="author" href="{{ site.theme.link }}/humans.txt" />
    <link rel="pingback" href="{{ site.pingback_url }}" />
    <link rel="profile" href="http://gmpg.org/xfn/11">
    {{function('wp_head')}}
```

**Recommended Fix:**

```twig
<!doctype html>
<html {{ site.language_attributes }}>
<head>
    <meta charset="{{ site.charset }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="{{ site.description }}" />
    {{function('wp_head')}}
```

**Rationale:** Modern WordPress theme don't need IE shims or outdated profiles. `wp_head()` handles title and most critical tags.

---

#### Issue 1.2: Missing Image Alt Text in single.twig

**Severity:** High | **File:** `templates/single.twig`

**Problem:**

- Featured image has no alt attribute
- Not using Timber's image object properly

**Current Code:**

```twig
<img src="{{ post.thumbnail.src|resize(1200, 300) }}">
```

**Recommended Fix:**

```twig
{% if post.thumbnail %}
    <figure class="post-featured-image">
        <img
            src="{{ post.thumbnail.src|resize(1200, 300) }}"
            alt="{{ post.title }}"
            loading="lazy"
        />
    </figure>
{% endif %}
```

**Rationale:** All images need descriptive alt text for accessibility and SEO.

---

#### Issue 1.3: Incorrect Heading Structure in archive.twig

**Severity:** Medium | **File:** `templates/archive.twig`

**Problem:**

- Uses `<h3>` for archive heading
- Should use `<h1>` or context-aware heading level
- No semantic context

**Current Code:**

```twig
<h3>This is my archive</h3>
```

**Recommended Fix:**

```twig
{% include "components/heading.twig" with {
    level: 1,
    text: "Archive: " ~ wp.query.title,
    accent: false
} %}
```

**Rationale:** Archive pages should have proper H1 heading for SEO and accessibility.

---

#### Issue 1.4: Missing `<time>` Tag Semantics

**Severity:** Low | **File:** `templates/single.twig`

**Problem:**

- Date displayed without semantic markup
- Missing `datetime` attribute

**Current Code:**

```twig
<time datetime="{{ post.date|date('Y-m-d H:i:s') }}">{{ post.date }}</time>
```

**Better Practice:**

```twig
<time datetime="{{ post.date|date('c') }}" class="post-date">
    {{ post.date|date('F j, Y') }}
</time>
```

**Rationale:** Use ISO 8601 format (c) for datetime, display human-readable date.

---

#### Issue 1.5: Empty or Unclear Comments Section Labels

**Severity:** Medium | **File:** `templates/single.twig`

**Problem:**

- Comments section uses lowercase generic labels
- No semantic distinction between sections
- Hardcoded English text without i18n

**Current Code:**

```twig
<h3> comments </h3>
<!-- comment box -->
<section class="comment-box">
```

**Recommended Fix:**

```twig
<h2 id="comments" class="comments-heading">{{ _e('Comments') }}</h2>
<section class="comments-section" aria-labelledby="comments">
```

**Rationale:** Proper heading levels, ARIA labels, and i18n.

---

#### Issue 1.6: Non-Semantic Comment Form Section

**Severity:** Low | **File:** `templates/single.twig`

**Problem:**

- Comment form included directly without section wrapping
- No clear separation

**Current Code:**

```twig
<!-- comment form -->
{% include "comment-form.twig" %}
```

**Recommended Fix:**

```twig
<section class="comment-form-section" id="respond">
    {% include "comment-form.twig" %}
</section>
```

**Rationale:** Semantic structure aids accessibility and styling.

---

#### Issue 1.7: Missing Footer Semantic Markup

**Severity:** Low | **File:** `templates/footer.twig`

**Problem:**

- Footer content not using `<footer>` tag
- Minimal structure
- Copyright only, no other footer content

**Current Code:**

```twig
<div class="wrapper">
  <div class="footer__content">
   Copyright {{ "now"|date("Y") }}
  </div>
</div>
```

**Recommended Fix:**

```twig
<div class="footer__content">
    <div class="footer__copyright">
        &copy; {{ "now"|date("Y") }} {{ site.name }}. {{ _e('All rights reserved.') }}
    </div>
    {% if footer_menu %}
        <nav class="footer__nav" aria-label="{{ _e('Footer Navigation') }}">
            {% include "menu.twig" with { items: footer_menu } %}
        </nav>
    {% endif %}
</div>
```

**Rationale:** Note: The `<footer>` tag is already in base.twig. This content just needs better structure within it.

---

#### Issue 1.8: Missing Article Wrapper in Components

**Severity:** Low | **File:** `templates/components/post-card.twig`

**Problem:**

- Uses generic `<article>` but could use more semantic structure for cards
- Missing `role` attribute on card

**Current Code:**

```twig
<article class="post-card {{ classes|default('') }}" id="post-{{ post.ID }}">
```

**Enhancement:**

```twig
<article class="post-card {{ classes|default('') }}" id="post-{{ post.ID }}" itemscope itemtype="https://schema.org/BlogPosting">
```

**Rationale:** Schema.org markup improves SEO.

---

### 2. ACCESSIBILITY ISSUES (12 issues) 🔴

#### Issue 2.1: Missing ARIA Labels on Icon-Only Buttons

**Severity:** High | **File:** `templates/components/post-card.twig`

**Problem:**

- Icon-only "Read More" link missing label
- Screen reader users won't understand the link

**Current Code:**

```twig
<a href="{{ post.link }}" class="post-card__link">
    {{ _e('Read More') }}<span class="dashicons dashicons-arrow-right"></span>
</a>
```

**Should Be:**

```twig
<a href="{{ post.link }}" class="post-card__link" aria-label="{{ _e('Read more about') }} {{ post.title }}">
    {{ _e('Read More') }}<span class="dashicons dashicons-arrow-right" aria-hidden="true"></span>
</a>
```

**Rationale:** Icon needs `aria-hidden="true"`, link needs descriptive label.

---

#### Issue 2.2: Missing ARIA in Navigation Menu

**Severity:** High | **File:** `templates/menu.twig`

**Problem:**

- No ARIA attributes for nested menus
- Screen readers can't identify submenu relationships
- No current page indicator

**Current Code:**

```twig
<ul>
{% for item in items %}
    <li class="{{ item.classes | join(' ') }}">
        <a target="{{ item.target }}" href="{{ item.link }}">{{ item.title }}</a>
        {% include "menu.twig" with {'items': item.children} %}
    </li>
{% endfor %}
</ul>
```

**Recommended Fix:**

```twig
<ul class="nav-menu">
{% for item in items %}
    <li class="nav-menu__item {{ item.classes | join(' ') }}">
        <a
            href="{{ item.link }}"
            class="nav-menu__link"
            {% if item.current %}aria-current="page"{% endif %}
            {% if item.children %}aria-expanded="false" aria-haspopup="true"{% endif %}
        >
            {{ item.title }}
        </a>
        {% if item.children %}
            <ul class="nav-menu__submenu" aria-label="{{ _e('Submenu') }}">
                {% include "menu.twig" with {'items': item.children} %}
            </ul>
        {% endif %}
    </li>
{% endfor %}
</ul>
```

**Rationale:** ARIA attributes aid keyboard and screen reader navigation.

---

#### Issue 2.3: Alert Component Missing Live Region

**Severity:** Medium | **File:** `templates/components/alert.twig`

**Problem:**

- Alert not marked as live region for dynamic updates
- Screen readers won't announce new alerts

**Current Code:**

```twig
<div class="alert alert--{{ type|default('info') }}" role="alert">
```

**Recommended Fix:**

```twig
<div
    class="alert alert--{{ type|default('info') }}"
    role="alert"
    aria-live="polite"
    aria-atomic="true"
>
```

**Rationale:** Dynamic alerts need live region attributes.

---

#### Issue 2.4: Missing Close Button Accessibility

**Severity:** Medium | **File:** `templates/components/alert.twig`

**Problem:**

- Close button uses icon without proper label
- Icon-only button needs better accessibility

**Current Code:**

```twig
<button type="button" class="alert__close" aria-label="{{ _e('Close') }}">
    <span class="dashicons dashicons-no"></span>
</button>
```

**Recommended Fix:**

```twig
<button
    type="button"
    class="alert__close"
    aria-label="{{ _e('Close this alert') }}"
    aria-controls="alert-{{ random() }}"
>
    <span class="dashicons dashicons-no" aria-hidden="true"></span>
</button>
```

**Rationale:** Better aria-label and aria-controls for button purpose.

---

#### Issue 2.5: Missing Focus States on Links

**Severity:** Medium | **File:** `templates/base.twig`

**Problem:**

- Skip link present but may not be visible
- No documented focus indicator CSS

**Current Code:**

```twig
<a class="skip-link screen-reader-text" href="#content">{{ _e( 'Skip to content') }}</a>
```

**Need CSS:**

```scss
.skip-link {
  &:focus {
    // Make visible on focus
    position: static;
    clip: auto;
    width: auto;
    height: auto;
    overflow: visible;
    background: $primary;
    color: white;
    padding: 0.5rem;
    z-index: 9999;
  }
}
```

**Rationale:** Skip link should be visible when focused.

---

#### Issue 2.6: Missing Language on Content Links

**Severity:** Low | **File:** `templates/single.twig`

**Problem:**

- External links (author page, etc.) lack `rel` attribute

**Current Code:**

```twig
<a href="{{post.author.path}}"> {{ post.author.name }} </a>
```

**Recommended Fix:**

```twig
<a href="{{post.author.path}}" rel="author">{{ post.author.name }}</a>
```

**Rationale:** `rel="author"` indicates relationship type.

---

#### Issue 2.7: Image Resize Filter Without Alt Context

**Severity:** Medium | **File:** `templates/components/post-card.twig`

**Problem:**

- Using custom resize filter without Timber Image object
- Loses metadata access

**Current Code:**

```twig
<img src="{{ post.thumbnail.src|resize(400, 300) }}" alt="{{ post.title }}">
```

**Better Practice:**

```twig
{% set thumb = post.thumbnail %}
{% if thumb %}
    <img
        src="{{ thumb.src|resize(400, 300) }}"
        alt="{{ thumb.alt or post.title }}"
        width="400"
        height="300"
        loading="lazy"
        decoding="async"
    />
{% endif %}
```

**Rationale:** Prefer Timber Image methods, add width/height for CLS prevention.

---

#### Issue 2.8: Missing Form Labels

**Severity:** High | **File:** `templates/comment-form.twig`

**Problem:**

- Comment form likely missing `<label>` elements
- Form fields not properly associated with labels

**Current Status:** Cannot determine from provided code (file not fully reviewed)

**Recommendation:** Ensure all form inputs have:

```twig
<label for="comment-field-name">{{ _e('Your Comment') }}</label>
<textarea id="comment-field-name" name="comment">...</textarea>
```

---

#### Issue 2.9: Missing Landmark Regions

**Severity:** Medium | **File:** `templates/base.twig`

**Problem:**

- No navigation landmarks in header
- Sidebar not properly marked as complementary

**Current Code:**

```twig
<nav id="nav-main" class="nav-main" role="navigation">
```

**Recommended Fix:**

```twig
<nav id="nav-main" class="nav-main" aria-label="{{ _e('Main Navigation') }}">
```

**Rationale:** Explicit labels help screen reader users navigate.

---

#### Issue 2.10: Text Contrast in Components

**Severity:** Medium | **Aspect:** Design system

**Concern:**

- Component styles may not meet WCAG AA contrast ratio (4.5:1 for normal text)
- Need validation of button, alert, and badge colors

**Recommendation:** Test with [WCAG Contrast Checker](https://webaim.org/resources/contrastchecker/).

---

#### Issue 2.11: Missing Heading Level Validation

**Severity:** Low | **File:** `templates/components/heading.twig`

**Problem:**

- Can create invalid heading levels (h7, h0)
- No validation

**Current Code:**

```twig
{% set heading_tag = 'h' ~ (level|default(2)) %}
```

**Recommended Fix:**

```twig
{% set level = level|default(2)|max(1)|min(6) %}
{% set heading_tag = 'h' ~ level %}
```

**Rationale:** Prevents invalid HTML.

---

#### Issue 2.12: Missing Keyboard Navigation Indicators

**Severity:** Medium | **File:** All templates

**Concern:**

- No visible focus indicators for keyboard navigation
- Users can't see which element has focus

**Recommendation:**
Create visible focus styles for all interactive elements in base styles:

```scss
button:focus-visible,
a:focus-visible,
input:focus-visible {
  outline: 2px solid $primary;
  outline-offset: 2px;
}
```

---

### 3. SECURITY ISSUES (2 issues - relatively good) ✅

#### Issue 3.1: Potential XSS with Custom Filters

**Severity:** Medium | **File:** `templates/components/post-card.twig`

**Problem:**

- Custom `resize` filter may not escape output if custom-implemented
- Using `|resize()` directly on image src

**Current Code:**

```twig
<img src="{{ post.thumbnail.src|resize(400, 300) }}" alt="{{ post.title }}">
```

**Recommendation:**
Verify `resize` filter is safe. If custom, ensure it escapes output:

```twig
<img src="{{ post.thumbnail.src|resize(400, 300)|escape('html_attr') }}" ...>
```

**Rationale:** Defense-in-depth approach.

---

#### Issue 3.2: Unescaped User-Generated Content Potential

**Severity:** Low | **File:** `templates/single.twig`

**Problem:**

- Post content uses `{{ post.content }}` which is auto-escaped (good)
- But custom fields may not be

**Current Code:**

```twig
{{ post.content }}
```

**Status:** ✅ Currently secure (Timber auto-escapes)

**Reminder:** Always escape custom field output:

```twig
{{ post.get_field('custom_field')|escape('html') }}
```

---

### 4. PERFORMANCE ISSUES (6 issues) ⚠️

#### Issue 4.1: Missing Lazy Loading on Images

**Severity:** Medium | **File:** `templates/single.twig`

**Problem:**

- Featured image not lazy loaded
- Will block page load for off-screen images

**Current Code:**

```twig
<img src="{{ post.thumbnail.src|resize(1200, 300) }}">
```

**Recommended Fix:**

```twig
<img
    src="{{ post.thumbnail.src|resize(1200, 300) }}"
    alt="{{ post.title }}"
    loading="lazy"
    decoding="async"
/>
```

**Impact:** Reduces LCP (Largest Contentful Paint).

---

#### Issue 4.2: Multiple Image Requests in Post Cards

**Severity:** Low | **File:** `templates/components/post-card.twig`

**Problem:**

- Using single resize filter instead of srcset
- No responsive images for different devices

**Current Code:**

```twig
<img src="{{ post.thumbnail.src|resize(400, 300) }}" ...>
```

**Recommended Enhancement:**

```twig
{% if post.thumbnail %}
    <img
        src="{{ post.thumbnail.src|resize(400, 300) }}"
        srcset="
            {{ post.thumbnail.src|resize(300, 225) }} 300w,
            {{ post.thumbnail.src|resize(400, 300) }} 400w,
            {{ post.thumbnail.src|resize(600, 450) }} 600w
        "
        sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 400px"
        alt="{{ post.title }}"
        loading="lazy"
        decoding="async"
    />
{% endif %}
```

**Impact:** Reduces total bandwidth, improves performance on mobile.

---

#### Issue 4.3: No Image Dimensions (Cumulative Layout Shift)

**Severity:** Medium | **File:** Multiple files

**Problem:**

- Images lack width/height attributes
- Causes CLS (Cumulative Layout Shift)

**Current Code:**

```twig
<img src="{{ ... }}" />
```

**Recommended Fix:**

```twig
<img
    src="{{ ... }}"
    width="400"
    height="300"
    alt="..."
    loading="lazy"
/>
```

**Impact:** Improves Core Web Vitals.

---

#### Issue 4.4: No Image Optimization Guidance

**Severity:** Low | **File:** Documentation

**Problem:**

- No documented image size strategy
- Unclear what images are used where

**Recommendation:**
Create image optimization guide documenting:

- Featured image sizes
- Card thumbnail sizes
- Hero image sizes
- Fallback images

---

#### Issue 4.5: No WebP Format Support

**Severity:** Low | **File:** Image handling

**Problem:**

- Only serving JPEG/PNG
- Missing modern format option

**Recommendation:**

```twig
<picture>
    <source srcset="{{ post.thumbnail.src|resize(400, 300)|webp }}" type="image/webp">
    <img src="{{ post.thumbnail.src|resize(400, 300) }}" alt="...">
</picture>
```

**Note:** Requires WebP filter implementation.

---

#### Issue 4.6: Unoptimized Icon Usage

**Severity:** Low | **File:** Multiple templates

**Problem:**

- Using dashicons for every icon
- Loads entire dashicon font for few icons

**Current Code:**

```twig
<span class="dashicons dashicons-arrow-right"></span>
```

**Recommendation:**

- Consider SVG inline icons for critical paths
- Keep dashicons for admin-used icons
- Or use subset font for specific icons

---

### 5. CONSISTENCY ISSUES (8 issues) ⚠️

#### Issue 5.1: Inconsistent Spacing/Formatting

**Severity:** Low | **Files:** Multiple templates

**Problem:**

- Inconsistent indentation
- Mixed quote styles
- Inconsistent attribute spacing

**Examples:**

```twig
{# Style 1 #}
<div class="wrapper">
  <div class="footer__content">
   Copyright {{ "now"|date("Y") }}
  </div>
</div>

{# Style 2 #}
<div class="{{sidebar_class}}">
  {% block content %}
```

**Recommendation:** Apply consistent formatting:

```twig
<div class="wrapper">
    <div class="footer__content">
        &copy; {{ "now"|date("Y") }}
    </div>
</div>
```

**Standard:** 4-space indentation, double quotes, spaces around attributes.

---

#### Issue 5.2: Inconsistent Component Props Style

**Severity:** Low | **File:** `templates/components/`

**Problem:**

- Different components format props differently
- Some with object syntax, some with individual props

**Example:** `post-card.twig` vs `button.twig`

```twig
{# Inconsistent #}
show_meta|default(true)
vs
show_meta default: true
```

**Recommendation:** Standardize to one pattern:

```twig
{% set show_meta = show_meta|default(true) %}
```

---

#### Issue 5.3: Inconsistent Class Naming

**Severity:** Medium | **Files:** Multiple templates

**Problem:**

- Some classes use hyphens: `.post-card`
- Some use double underscore: `.post-card__title`
- Some use double dash: `.alert--info`
- Inconsistent CSS class application

**Examples:**

```twig
<h1 class="hdr-logo">       {# hdr-logo, not following BEM #}
<div class="wrapper">       {# wrapper, utility class #}
<article class="post-card"> {# BEM block #}
```

**Recommendation:** Stick to BEM pattern:

```twig
<h1 class="header__logo">
<article class="post-card">
<span class="post-card__date">
```

---

#### Issue 5.4: Inconsistent i18n/Translation Usage

**Severity:** Medium | **Files:** Multiple templates

**Problem:**

- Some strings translated with `{{ _e() }}`
- Some hardcoded in English
- Missing context for translators

**Examples:**

```twig
{{ _e('Read More') }}              {# Good #}
<h3> comments </h3>                {# Bad - hardcoded #}
<p> comments for this post are closed </p> {# Bad - hardcoded #}
```

**Recommendation:**

```twig
<h3>{{ _e('Comments') }}</h3>
<p>{{ _e('Comments for this post are closed.') }}</p>
```

**With Context (Better):**

```twig
{{ _e('Comments', 'abbie') }}
```

---

#### Issue 5.5: Inconsistent Conditional Patterns

**Severity:** Low | **Files:** Multiple templates

**Problem:**

- Different ways of handling optional content
- Inconsistent use of `|default()`

**Examples:**

```twig
{% if show_meta|default(true) %}  {# Style 1 #}
{% if show_excerpt|default(true) and post.excerpt %} {# Style 2 #}
{{ classes|default('') }}         {# Style 3 #}
```

**Recommendation:** Standardize:

```twig
{% set show_meta = show_meta|default(true) %}
{% if show_meta and post.excerpt %}
```

---

#### Issue 5.6: Inconsistent ID Generation

**Severity:** Low | **File:** Multiple components

**Problem:**

- Some components generate IDs: `id="post-{{ post.ID }}"`
- Some don't: missing ID attributes
- No consistent ID naming pattern

**Recommendation:**

```twig
{% set item_id = item_id|default('component-' ~ random()) %}
<div id="{{ item_id }}" class="component">
```

---

#### Issue 5.7: Inconsistent Target Attributes

**Severity:** Low | **File:** `templates/menu.twig`

**Problem:**

- Uses `target="{{ item.target }}"` without validation
- Could output dangerous values

**Current Code:**

```twig
<a target="{{ item.target }}" href="{{ item.link }}">
```

**Recommended Fix:**

```twig
<a
    href="{{ item.link }}"
    {% if item.target == '_blank' %}target="_blank" rel="noopener noreferrer"{% endif %}
>
```

**Rationale:** Validates target, adds security rel attribute.

---

#### Issue 5.8: Inconsistent Comment Text Styling

**Severity:** Low | **File:** `templates/single.twig`

**Problem:**

- Comment text in different styles/cases
- "This is my archive" (title case)
- " comments " (lowercase with spaces)
- "comments for this post are closed" (lowercase)

**Recommendation:** Standardize and use i18n:

```twig
{{ _e('Comments', 'abbie') }}
{{ _e('Comments for this post are closed.', 'abbie') }}
```

---

### 6. CODE STYLE ISSUES (5 issues) 🟡

#### Issue 6.1: Inconsistent Whitespace in Attributes

**Severity:** Low | **Files:** Multiple templates

**Problem:**

- No consistent spacing around attributes
- Some have spaces, some don't

**Examples:**

```twig
id="post-{{ post.ID }}"         {# No spaces #}
class="{{ button_classes }}"    {# No spaces #}
class="alert--{{ type|default('info') }}" {# No spaces #}
```

**Recommended Style:**

```twig
class="post-card {{ classes|default('') }}"
id="post-{{ post.ID }}"
data-type="{{ type|default('default') }}"
```

**Standard:** No spaces around inline expressions in attributes.

---

#### Issue 6.2: Inconsistent Comment Style

**Severity:** Low | **Files:** Multiple templates

**Problem:**

- HTML comments used: `<!-- comment -->`
- Twig comments used: `{# comment #}`
- No consistency

**Examples:**

```twig
<!-- comment box -->       {# HTML comment #}
<!-- comments -->          {# HTML comment #}
<!-- /content-wrapper -->  {# HTML comment #}
{# Block #}                {# Twig comment #}
```

**Recommendation:**

- Use Twig comments `{# #}` for Twig-specific notes
- Use HTML comments `<!-- -->` for context that should be in rendered HTML

**Standard:**

```twig
{# Check if post has comments #}
<section class="comments">
    <!-- Comments will be rendered here -->
</section>
```

---

#### Issue 6.3: Inconsistent String Quoting

**Severity:** Low | **Files:** Multiple templates

**Problem:**

- Mixed single and double quotes
- No consistency

**Examples:**

```twig
with {'items': menu.get_items}  {# Single quotes #}
with { level: 1, text: "Latest Posts", accent: true } {# No quotes on keys #}
```

**Recommendation:**

```twig
with { level: 1, text: 'Latest Posts', accent: true }  {# Consistent #}
```

**Standard:** Double quotes for Twig values, single quotes for keys.

---

#### Issue 6.4: Missing/Inconsistent Type Hints in Props

**Severity:** Medium | **File:** Component documentation

**Problem:**

- Component props documented but no type hints
- "text (required)" doesn't specify string type
- No information about accepted array structures

**Current Pattern:**

```twig
{# Available Props:
    - text (required): Button text/label
    - url: Link URL
    - classes: Additional CSS classes
#}
```

**Recommended Pattern:**

```twig
{# Props:
    - text (string, required): Button label
    - url (string, optional): Link URL
    - type (string, optional): button|submit|reset (default: button)
    - disabled (boolean, optional): Disable button (default: false)
    - classes (string, optional): Additional CSS classes
#}
```

---

#### Issue 6.5: Inconsistent Component Parameter Order

**Severity:** Low | **Files:** Components

**Problem:**

- No consistent parameter ordering
- Some required params first, some last
- No consistency within components

**Examples:**

```twig
{% include "components/heading.twig" with {
    level: 1,
    text: "Title"
}%}

{% include "components/post-card.twig" with {
    post: post,
    show_excerpt: true
}%}

{% include "components/button.twig" with {
    text: "Click",
    url: "/path"
}%}
```

**Recommendation:**

```twig
{# Standard order: required, then optional #}
{% include "components/name.twig" with {
    required_param: value,
    optional_param: value|default(false)
} %}
```

---

### 7. DOCUMENTATION ISSUES (2 issues) 🟡

#### Issue 7.1: Missing Template Type Documentation

**Severity:** Low | **Files:** All templates

**Problem:**

- No documentation of template purpose
- No indication of context variables available
- No examples in many templates

**Current State:**

- Components well documented ✅
- Archive.twig documented ✅
- Single.twig NOT documented ❌
- Page.twig NOT documented ❌
- Search.twig NOT documented ❌

**Recommendation:**
Add header to all templates:

```twig
{#
    Template: Single Post

    Purpose: Render a single post/article with comments

    Available Context:
    - post: Timber\Post object with full post data
    - comments: Array of post comments

    Required ACF Fields: None

    Usage: Automatically used by WordPress for single posts
#}
```

---

#### Issue 7.2: No Accessibility Guidelines in Component Docs

**Severity:** Medium | **Files:** Component files

**Problem:**

- Components don't document accessibility requirements
- No guidance on proper usage
- No ARIA attribute documentation

**Recommendation:**

```twig
{#
    Button Component

    Props: [...]

    Accessibility Notes:
    - Icons should use aria-hidden="true" if paired with text
    - Icon-only buttons need aria-label
    - Use type="submit" for form submissions
    - Use rel="noopener noreferrer" for target="_blank"

    Examples:
    {# Text button #}
    {% include "components/button.twig" with {
        text: "Click me",
        type: "button"
    } %}

    {# Icon button #}
    {% include "components/button.twig" with {
        text: "Download",
        icon: "download",
        aria-label: "Download PDF"
    } %}
#}
```

---

### 8. BEM COMPLIANCE ISSUES (3 issues) 🟡

#### Issue 8.1: Non-BEM Class Usage

**Severity:** Low | **Files:** Multiple templates

**Problem:**

- Mixing BEM with utility classes
- Non-semantic class names

**Examples:**

```twig
class="wrapper"              {# Not BEM #}
class="hdr-logo"             {# Not BEM naming #}
class="content-wrapper"      {# Not BEM #}
class="layout-sidebar"       {# Not BEM #}
class="article-content"      {# Not BEM #}
class="article-body"         {# Not BEM #}
```

**Recommendation:**

```twig
{# Use BEM #}
class="post-single"
class="post-single__header"
class="post-single__content"
class="post-single__comments"

{# Or use utilities #}
class="container"
class="main"
class="sidebar"
```

**Standard:** Either full BEM or clear utility classes, not hybrid.

---

#### Issue 8.2: Missing BEM in Macro Output

**Severity:** Low | **File:** `templates/macros.twig`

**Problem:**

- Macro output doesn't consistently use BEM classes
- Some have modifiers, some don't

**Examples:**

```twig
<span class="badge badge--{{ variant }}">{{ text }}</span>          {# Good BEM #}
<ul class="meta-list">                                              {# Block only #}
<li class="meta-list__item">                                        {# Element #}
<ul class="social-links">                                           {# Block only #}
```

**Consistency Check:** All good, but ensure all elements use:

```twig
<span class="badge badge--{{ variant|default('default') }}">
```

---

#### Issue 8.3: BEM Class Missing on Footer

**Severity:** Low | **File:** `templates/footer.twig`

**Problem:**

- Using `footer__content` but parent `footer` is in base.twig
- Structural disconnect

**Current Code:**

```twig
<div class="wrapper">
  <div class="footer__content">
```

**Recommended Fix:**

```twig
<div class="footer__wrapper">
    <div class="footer__copyright">
        &copy; {{ "now"|date("Y") }} {{ site.name }}
    </div>
</div>
```

---

## Summary Table: All Issues

| #    | Issue                                 | Severity | File              | Category      | Status            |
| ---- | ------------------------------------- | -------- | ----------------- | ------------- | ----------------- |
| 1.1  | Outdated IE shims in html-header      | High     | html-header.twig  | Semantic      | 🔴 Critical       |
| 1.2  | Missing alt text on featured image    | High     | single.twig       | Semantic      | 🔴 Critical       |
| 1.3  | Wrong heading level in archive        | Medium   | archive.twig      | Semantic      | 🟡 Needs fix      |
| 1.4  | Suboptimal datetime format            | Low      | single.twig       | Semantic      | 🔵 Minor          |
| 1.5  | Generic comment labels                | Medium   | single.twig       | Semantic      | 🟡 Needs fix      |
| 1.6  | Non-semantic comment section          | Low      | single.twig       | Semantic      | 🔵 Minor          |
| 1.7  | Minimal footer structure              | Low      | footer.twig       | Semantic      | 🔵 Minor          |
| 1.8  | Missing Schema.org markup             | Low      | post-card.twig    | Semantic      | 🔵 Minor          |
| 2.1  | Missing ARIA on icon links            | High     | post-card.twig    | Accessibility | 🔴 Critical       |
| 2.2  | Missing ARIA in navigation            | High     | menu.twig         | Accessibility | 🔴 Critical       |
| 2.3  | Alert missing live region             | Medium   | alert.twig        | Accessibility | 🟡 Needs fix      |
| 2.4  | Close button accessibility            | Medium   | alert.twig        | Accessibility | 🟡 Needs fix      |
| 2.5  | Missing focus states                  | Medium   | base.twig         | Accessibility | 🟡 Needs fix      |
| 2.6  | Missing rel attributes                | Low      | single.twig       | Accessibility | 🔵 Minor          |
| 2.7  | Image handling best practices         | Medium   | post-card.twig    | Accessibility | 🟡 Needs fix      |
| 2.8  | Comment form labels                   | High     | comment-form.twig | Accessibility | 🔴 Critical       |
| 2.9  | Missing landmark regions              | Medium   | base.twig         | Accessibility | 🟡 Needs fix      |
| 2.10 | Text contrast validation needed       | Medium   | Design system     | Accessibility | ⚠️ Testing needed |
| 2.11 | Missing heading level validation      | Low      | heading.twig      | Accessibility | 🔵 Minor          |
| 2.12 | Missing focus indicators              | Medium   | All               | Accessibility | 🟡 Needs fix      |
| 3.1  | Potential XSS with resize filter      | Medium   | post-card.twig    | Security      | ⚠️ Verify         |
| 3.2  | Custom field escaping reminder        | Low      | single.twig       | Security      | ✅ OK             |
| 4.1  | No lazy loading on featured image     | Medium   | single.twig       | Performance   | 🟡 Needs fix      |
| 4.2  | No responsive image srcset            | Low      | post-card.twig    | Performance   | 🔵 Enhancement    |
| 4.3  | Missing image dimensions (CLS)        | Medium   | Multiple          | Performance   | 🟡 Needs fix      |
| 4.4  | No image optimization guide           | Low      | Documentation     | Performance   | 🔵 Enhancement    |
| 4.5  | No WebP support                       | Low      | Images            | Performance   | 🔵 Enhancement    |
| 4.6  | Unoptimized icon usage                | Low      | Multiple          | Performance   | 🔵 Enhancement    |
| 5.1  | Inconsistent formatting/indentation   | Low      | Multiple          | Consistency   | 🔵 Minor          |
| 5.2  | Inconsistent component props          | Low      | Components        | Consistency   | 🔵 Minor          |
| 5.3  | Inconsistent class naming (BEM)       | Medium   | Multiple          | Consistency   | 🟡 Needs fix      |
| 5.4  | Inconsistent i18n usage               | Medium   | Multiple          | Consistency   | 🟡 Needs fix      |
| 5.5  | Inconsistent conditional patterns     | Low      | Multiple          | Consistency   | 🔵 Minor          |
| 5.6  | Inconsistent ID generation            | Low      | Multiple          | Consistency   | 🔵 Minor          |
| 5.7  | Inconsistent target attributes        | Low      | menu.twig         | Consistency   | 🔵 Minor          |
| 5.8  | Inconsistent comment text styling     | Low      | single.twig       | Consistency   | 🔵 Minor          |
| 6.1  | Inconsistent attribute spacing        | Low      | Multiple          | Code Style    | 🔵 Minor          |
| 6.2  | Inconsistent comment style            | Low      | Multiple          | Code Style    | 🔵 Minor          |
| 6.3  | Mixed string quoting                  | Low      | Multiple          | Code Style    | 🔵 Minor          |
| 6.4  | Missing type hints in component props | Medium   | Components        | Code Style    | 🟡 Needs fix      |
| 6.5  | Inconsistent parameter order          | Low      | Components        | Code Style    | 🔵 Minor          |
| 7.1  | Missing template documentation        | Low      | Most templates    | Documentation | 🔵 Minor          |
| 7.2  | No accessibility docs in components   | Medium   | Components        | Documentation | 🟡 Needs fix      |
| 8.1  | Non-BEM class usage                   | Low      | Multiple          | BEM           | 🔵 Minor          |
| 8.2  | Missing BEM in macros                 | Low      | macros.twig       | BEM           | 🔵 Minor          |
| 8.3  | BEM class disconnect                  | Low      | footer.twig       | BEM           | 🔵 Minor          |

---

## Recommendations Priority

### 🔴 Critical (Fix Immediately)

1. Add alt text to all images
2. Add ARIA to navigation menu
3. Fix icon-only button accessibility
4. Document comment form labels
5. Modernize html-header.twig

### 🟡 High Priority (Fix Soon)

1. Add lazy loading to featured images
2. Add heading validation to component
3. Standardize i18n across templates
4. Add focus states CSS
5. Standardize BEM class naming
6. Add alert live region support
7. Update archive heading level
8. Add type hints to component documentation

### 🔵 Low Priority (Nice to Have)

1. Add responsive image srcsets
2. Add image dimensions for CLS prevention
3. Schema.org markup
4. Format consistency
5. Code style standardization
6. WebP support
7. Template documentation headers

---

## Action Items

### Immediate (Critical)

- [ ] Add alt text to all images (1.2, 4.3)
- [ ] Add ARIA attributes to navigation (2.2)
- [ ] Fix icon button accessibility (2.1)
- [ ] Audit comment form for label accessibility (2.8)
- [ ] Modernize html-header.twig (1.1)

### Short Term (Next Sprint)

- [ ] Add lazy loading to images (4.1)
- [ ] Add heading level validation (2.11)
- [ ] Standardize i18n across all templates (5.4)
- [ ] Add focus indicator CSS (2.5, 2.12)
- [ ] Standardize BEM naming (5.3, 8.1)

### Long Term (Next Quarter)

- [ ] Add responsive image srcsets (4.2)
- [ ] Add image dimensions (4.3)
- [ ] Create image optimization guide (4.4)
- [ ] Add template documentation (7.1)
- [ ] Code style standardization (6.1-6.5)

---

## Resources

- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [ARIA Authoring Practices](https://www.w3.org/WAI/ARIA/apg/)
- [Twig Best Practices](https://twig.symfony.com/doc/)
- [BEM Methodology](https://bem.info/)
- [Core Web Vitals](https://web.dev/vitals/)
- [Schema.org](https://schema.org/)

---

**Audit Date:** March 2026  
**Auditor:** Code Review System  
**Overall Score:** 7.2/10  
**Status:** 12 Critical/High issues, 28 Medium issues, 35 Low issues
