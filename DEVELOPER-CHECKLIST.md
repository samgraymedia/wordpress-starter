# Developer Onboarding Checklist

**Quick checklist for developers starting a new page/feature or joining the project**

Use this guide to ensure consistency, quality, and best practices across all development work.

## Table of Contents

1. [Before You Start](#before-you-start)
2. [Creating a New Page](#creating-a-new-page)
3. [Building a New Block](#building-a-new-block)
4. [Building a New Component](#building-a-new-component)
5. [Code Quality](#code-quality)
6. [Testing](#testing)
7. [Deployment](#deployment)

---

## Before You Start

### Familiarize Yourself with the Theme

- [ ] Read [README.md](README.md) - Installation and quick start
- [ ] Review [QUICK-REFERENCE.md](QUICK-REFERENCE.md) - Common commands and patterns
- [ ] Skim [ARCHITECTURE.md](ARCHITECTURE.md) - Understand theme organization
- [ ] Check [DEVELOPER-GUIDE.md](DEVELOPER-GUIDE.md) - Comprehensive reference

### Understand the Stack

- [ ] **Templating:** Timber + Twig (not plain PHP templates)
- [ ] **Styling:** BEM methodology + SCSS (see [BEM-GUIDE.md](BEM-GUIDE.md))
- [ ] **JavaScript:** ES6 modules via Vite (see [JAVASCRIPT-GUIDE.md](JAVASCRIPT-GUIDE.md))
- [ ] **Blocks:** Text block for rich content with ACF Pro (see [BLOCKS-GUIDE.md](BLOCKS-GUIDE.md))
- [ ] **Database:** WordPress post types, ACF field groups, custom taxonomies (see [CONFIG-GUIDE.md](CONFIG-GUIDE.md))

### Setup Your Local Environment

- [ ] Run `npm install` to install dependencies
- [ ] Start dev server: `npm run dev`
- [ ] Verify compilation: `npm run build`
- [ ] Check browser: Visit `http://localhost:3000` (or your dev URL)

### Common npm Commands

```bash
npm run dev      # Start development server
npm run build    # Production build
npm run format   # Format code with Prettier
npm run lint     # ESLint JavaScript check
```

---

## Creating a New Page

### Step 1: Understanding Page Types

**WordPress Template Hierarchy (in order):**

1. `template-name.php` - Custom page template
2. `page-{slug}.php` - Specific page by slug
3. `page.php` - Generic page template
4. `singular.php` - Any single content
5. `index.php` - Fallback

**This theme uses Timber + Twig, so:**

- Routing happens in `page.php` (and `single.php`, `archive.php`, etc.)
- Templates are in `templates/` folder (`.twig` files)
- Logic stays in PHP, views in Twig

### Step 2: Choose Your Approach

**Approach A: Use the Text Block (Recommended)**

- Admin builds page in visual editor with Text block for content
- Use rich text formatting in the block
- No developer customization needed! ✨

**Approach B: Create Custom Twig Template**

- Create new file: `templates/my-custom-page.twig`
- Compose layout with Twig components
- Bind to page in WordPress (Custom Field or PHP condition)

**Approach C: Add PHP Logic**

- Create `page-{slug}.php` at theme root
- Use Timber to pass data to Twig template
- Keep business logic in PHP

### Step 3: Building the Page Structure

**Example: About Page with Text Block**

```
1. Page Header (Twig template or custom PHP)
   - Title: "About Us"
   - Subtitle: "Our Story"

2. Text Block (Content)
   - Use rich text editor for formatted content
   - Support for headings, lists, links, quotes

3. Optional: Footer Section (Twig template)
   - Call-to-action or additional information
```

Most pages can be built in WordPress editor—no coding needed!

### Step 4: Checklist

Creating a new page?

- [ ] Chose between Text block vs custom template
- [ ] Documented the page purpose and intended audience
- [ ] Used Text block where possible (avoid custom code)
- [ ] If custom code needed: created `.twig` template following naming convention
- [ ] Tested responsive design (mobile, tablet, desktop)
- [ ] Added SEO meta title, description, and Open Graph image
- [ ] Verified all links work
- [ ] Tested on actual device (not just browser inspector)

---

## Adding Content with the Text Block

### Overview

The theme includes a **Text Block** for rich text content. This is the primary block for adding formatted content:

**Features:**

- Rich text editing (WYSIWYG)
- Support for headings, lists, quotes, links
- No developer coding required—fully editable in WordPress

### Using the Text Block

1. Open page in editor
2. Click "+" to add a block
3. Search for "Text"
4. Add and format your content
5. Publish

No code needed!

### Creating Additional Blocks

If you need to extend the theme with new blocks, refer to [BLOCKS-GUIDE.md](BLOCKS-GUIDE.md) for the block architecture and creation pattern.

---

## Building a New Component

### Step 1: Create Component File

Create `templates/components/my-component.twig`:

```twig
{#
My Component

Brief description of the component

Props:
- title (string) - Component title
- variant (string) - Styling variant (e.g., "primary", "secondary")

Usage:
{% include "components/my-component.twig" with {
    title: "Hello",
    variant: "primary"
} %}
#}

<div class="my-component my-component--{{ variant }}">
    <h3 class="my-component__title">{{ title }}</h3>
</div>
```

### Step 2: Create Component Styles

Create `css/inc/components/my-component.scss`:

```scss
@import "../base/vars";

.my-component {
  display: flex;
  gap: $spacing-2;

  &__title {
    font-size: $font-lg;
    color: $text-color;
  }

  // Variants
  &--primary {
    background-color: $primary;
    color: $white;
  }

  &--secondary {
    background-color: $secondary;
    color: $white;
  }
}
```

### Step 3: Use in Templates

```twig
{% include "components/my-component.twig" with {
    title: "Welcome",
    variant: "primary"
} %}
```

### Step 4: When to Create Components

Use components for:

- ✅ Small reusable UI bits (button, badge, alert)
- ✅ Repeated patterns in templates
- ✅ Shared styling across pages

Don't use components for:

- ❌ Full page layouts (use blocks or Twig templates)
- ❌ Admin-editable content (use blocks instead)
- ❌ Single-use UI (inline in template is fine)

---

## Code Quality

### BEM Naming Convention

All CSS uses **BEM (Block-Element-Modifier)**:

```scss
.my-block {
  // Block—standalone component
}

.my-block__title {
  // Element—part of block
}

.my-block__title--dark {
  // Modifier—variant
}
```

See [BEM-GUIDE.md](BEM-GUIDE.md) for full reference.

**Quick rules:**

- Blocks: `.block-name`
- Elements: `.block-name__element-name`
- Modifiers: `.block-name--modifier` or `.block-name__element--modifier`
- Never nest beyond 1 level deep

### Twig Best Practices

- Use `|default()` filter for optional variables
- Check existence with `{% if field %}`
- Use `|raw` sparingly (only for trusted content)
- Add comments explaining complex logic
- Use meaningful variable names

```twig
{# Good #}
{% set title = post.title|default('Untitled') %}
{% if show_excerpt %}
  <p>{{ post.excerpt }}</p>
{% endif %}

{# Avoid #}
{% set t = post.title %}
{{ content|raw }} {# Dangerous without validation #}
```

### JavaScript Standards

- ES6 modules only (no `var`, use `const`/`let`)
- Meaningful function names
- Prefer `for...of` over `forEach` when possible
- Use template literals over string concatenation

```javascript
// Good
const blocks = document.querySelectorAll(".my-block");
blocks.forEach((block) => {
  block.addEventListener("click", () => {
    toggleState(block);
  });
});

// Avoid
var blocks = document.querySelectorAll(".my-block");
for (var i = 0; i < blocks.length; i++) {
  // Don't do this
}
```

### PHP Standards

- Use modern PHP 7.4+ syntax
- Type hints for function parameters
- Clear variable names
- Add PHPDoc comments for custom functions

```php
// Good
function get_featured_posts(int $limit = 5): array {
  // Get featured posts...
}

// Avoid
function getPosts($x) {
  // unclear parameter
}
```

---

## Testing

### Before Committing

- [ ] **Visual:** Does it look right in browser?
- [ ] **Responsive:** Test at sm, md, lg breakpoints
- [ ] **Accessibility:** Can keyboard users navigate?
- [ ] **Functionality:** Do all interactions work?
- [ ] **Performance:** Does page load quickly?

### Browser Testing

Test in:

- Chrome (desktop & mobile)
- Firefox (desktop & mobile)
- Safari (desktop & mobile if possible)
- Edge (Windows)

### Accessibility Testing

- [ ] Use keyboard only (no mouse) to navigate
- [ ] Tab focus indicators visible
- [ ] Screen reader (use VoiceOver on Mac, Narrator on Windows)
- [ ] Check color contrast with [WebAIM Browser](https://webaim.org/resources/contrastchecker/)

### Mobile Responsiveness

- [ ] Test at: 375px, 768px, 1024px, 1440px+
- [ ] Images load at correct sizes
- [ ] Text readable (not too small)
- [ ] Touch targets 44px+ (especially buttons)
- [ ] No horizontal scroll on mobile

### Schema Markup (for blocks that need it)

Test with [Google Rich Results Tester](https://search.google.com/test/rich-results):

- Pricing blocks → Product schema
- FAQ blocks → FAQPage schema
- Blog posts → Article schema

---

## Deployment

### Pre-Deployment Checklist

- [ ] Code reviewed by another developer (or self-review)
- [ ] All tests pass locally
- [ ] No console errors (F12 → Console tab)
- [ ] `npm run build` completes without warnings
- [ ] Staging environment tested
- [ ] SEO checked (meta tags, schema markup)
- [ ] Links verified (no 404s)
- [ ] Commit message is clear and descriptive

### git Workflow

```bash
# Create feature branch
git checkout -b feature/my-new-block

# Make changes and test
npm run build

# Stage changes
git add .

# Commit with clear message
git commit -m "Add: My new block with fields and styling"

# Push to remote
git push origin feature/my-new-block

# Create pull request on GitHub
# Get review, then merge to main
```

### Deployment Process

1. **Code review** - Another dev reviews pull request
2. **Automated tests** - CI/CD pipeline runs (if configured)
3. **Staging test** - Deploy to staging environment
4. **Manual QA** - Test on staging (all browsers, devices)
5. **Production deploy** - Merge to main and deploy
6. **Monitor** - Check for console errors, 404s, broken links

---

## Quick Reference

### Common Folders

```
src/blocks/           # Block ACF field definitions
views/blocks/         # Block Twig templates
templates/components/ # Reusable Twig components
css/inc/blocks/       # Block styles
css/inc/components/   # Component styles
scripts/              # JavaScript modules
```

### Common Commands

```bash
npm run dev      # Development (watch mode)
npm run build    # Production build
npm run format   # Format all code
npm run lint     # ESLint check
```

### Key Files to Know

| File                   | Purpose                           |
| ---------------------- | --------------------------------- |
| `functions.php`        | Theme setup, hooks, registrations |
| `src/config.php`       | Centralized configuration         |
| `src/theme-config.php` | Theme options and colors          |
| `templates/base.twig`  | Main template wrapper             |
| `style.scss`           | Main stylesheet imports           |
| `scripts/index.js`     | JavaScript entry point            |

---

## Need Help?

1. **Documentation:** Check [QUICK-REFERENCE.md](QUICK-REFERENCE.md) first
2. **Blocks:** See [BLOCKS-GUIDE.md](BLOCKS-GUIDE.md)
3. **Components:** See [COMPONENTS-GUIDE.md](COMPONENTS-GUIDE.md)
4. **Styling:** See [BEM-GUIDE.md](BEM-GUIDE.md)
5. **Configuration:** See [CONFIG-GUIDE.md](CONFIG-GUIDE.md)
6. **Full Reference:** See [DEVELOPER-GUIDE.md](DEVELOPER-GUIDE.md)

---

**Last Updated:** March 24, 2026
