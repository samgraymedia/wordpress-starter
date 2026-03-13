# BEM CSS Architecture & Utilities Guide

This guide documents the Block-Element-Modifier (BEM) methodology implemented in this theme's SCSS organization.

## Table of Contents

1. [BEM Methodology Overview](#bem-methodology-overview)
2. [Import Order & Organization](#import-order--organization)
3. [Component Categories](#component-categories)
4. [BEM Naming Conventions](#bem-naming-conventions)
5. [BEM and Twig Components](#bem-and-twig-components)
6. [Utilities Reference](#utilities-reference)
7. [Examples](#examples)
8. [Best Practices](#best-practices)

---

## BEM Methodology Overview

BEM stands for **Block-Element-Modifier**—a CSS naming convention that makes code more maintainable and predictable.

### Core Concepts

- **Block**: An independent, meaningful component that can exist on its own

  - Example: `button`, `card`, `navigation`
  - Class: `.button`, `.card`, `.nav-main`

- **Element**: Part of a block that has no standalone meaning

  - Connected to block with double underscore `__`
  - Example: `.button__text`, `.card__image`, `.nav-main__item`

- **Modifier**: A variation of a block or element that changes its appearance or behavior
  - Connected with double dash `--`
  - Example: `.button--primary`, `.card--featured`, `.alert--warning`

### Why BEM?

✅ **Predictable naming** - Easy to understand code organization  
✅ **Modular & scalable** - Add new components without affecting existing ones  
✅ **Low specificity** - Avoid !important and specificity wars  
✅ **Reusable** - Components work in different contexts  
✅ **Maintainable** - Changes are isolated and tracked

---

## Import Order & Organization

The SCSS is organized in layers following SMACSS principles:

```scss
// 1. Framework (Bootstrap)
@import "./node_modules/bootstrap/scss/bootstrap";

// 2. Base (Variables, functions, resets)
@import "inc/base/vars";
@import "inc/base/reset";
@import "inc/base/layout";
@import "inc/base/font";

// 3. Base components
@import "inc/base/btns";

// 4. BEM Components
@import "inc/components/card";
@import "inc/components/alert";
@import "inc/components/badge";

// 5. Global sections
@import "inc/global/nav";
@import "inc/global/footer";

// 6. Block-specific
@import "inc/blocks/text";

// 7. Utilities
@import "inc/utilities/helpers";
```

**Import Order Matters:**

1. Variables must come before they're used
2. Base styles before components
3. Components before utilities (so utilities can override if needed)
4. Global sections after components (they aggregate components)

---

## Component Categories

### Base Components (`css/inc/base/`)

**Purpose:** Core styling for fundamental HTML elements

- `btns.scss` - Button component with multiple variants

**Example:**

```scss
// Block: .btn
.btn {
  // Elements: .btn__text, .btn__icon
  &__text {
  }
  &__icon {
  }

  // Modifiers: .btn--primary, .btn--lg
  &--primary {
  }
  &--lg {
  }
}
```

### Reusable Components (`css/inc/components/`)

**Purpose:** Packaged UI components using BEM

- `card.scss` - Card/post preview component
- `alert.scss` - Alert/notification component
- `badge.scss` - Badge/label component
- Add new components here as they're created

**Example:**

```scss
// card.scss
.card {
  // Elements
  &__image {
  }
  &__content {
  }
  &__title {
  }

  // Modifiers for variants
  &--featured {
  }
  &--minimal {
  }
  &--bordered {
  }
}
```

### Global Sections (`css/inc/global/`)

**Purpose:** Large layout sections (nav, footer, header)

- `nav.scss` - Main navigation
- `footer.scss` - Footer section

These sections often contain or wrap multiple components.

### Block Styles (`css/inc/blocks/`)

**Purpose:** Styles specific to custom Gutenberg blocks registered by the theme

- `text.scss` - Custom text block styling

### Utilities (`css/inc/utilities/`)

**Purpose:** Single-purpose helper classes

- `helpers.scss` - Bootstrap utilities + custom extensions for spacing, typography, display

---

## BEM Naming Conventions

### Block Naming

Block names should describe the component's purpose:

```scss
// ✅ Good
.button {
}
.card {
}
.nav-main {
}
.alert {
}

// ❌ Avoid
.blue-box {
}
.left-sidebar {
}
.item {
}
```

### Element Naming

Elements are part of a block's structure:

```scss
// ✅ Good structure
.card {
  &__image {
  } // .card__image
  &__content {
  } // .card__content
  &__title {
  } // .card__title
}

// ❌ Avoid deeply nested BEM
.card {
  &__content {
    &__title {
      // Don't do .card__content__title
      &__text {
      }
    }
  }
}

// Better: Keep elements flat
.card {
  &__title {
  }
  &__text {
  }
}
```

### Modifier Naming

Modifiers describe a variant:

```scss
// ✅ Good modifiers
.button--primary {
}
.button--lg {
}
.card--featured {
}
.alert--warning {
}

// ❌ Avoid vague modifiers
.button--fancy {
}
.card--special {
}
.alert--alert2 {
}
```

### Usage in HTML

```html
<!-- Block -->
<div class="card">
  <!-- Elements -->
  <img class="card__image" src="..." />
  <div class="card__content">
    <h3 class="card__title">Title</h3>
    <p class="card__text">Description</p>
  </div>

  <!-- Modifiers on blocks -->
  <div class="card card--featured">
    ...
  </div>

  <!-- Modifiers on elements -->
  <img class="card__image card__image--large" src="..." />
</div>
```

---

## Utilities Reference

**Bootstrap Foundation:** This theme uses Bootstrap 5 utility classes where available. The examples below show Bootstrap utilities. Custom utilities extend Bootstrap for additional helpers.
Utilities are low-level helper classes for single CSS properties.

### Spacing Utilities

**Margin:**

```html
<!-- Margin all sides -->
<div class="m-4">Content</div>

<!-- Margin specific sides -->
<div class="mt-4 mb-2">Content</div>
<div class="ml-3 mr-1">Content</div>

<!-- Margin x/y axis -->
<div class="mx-auto">Centered horizontally</div>
<div class="py-3">Padding top/bottom</div>
```

**Scale:** `0, 1, 2, 3, 4, 5, 6, 7, 8, 9`

- Maps to: `0, 0.25rem, 0.5rem, 0.75rem, 1rem, 1.5rem, 2rem, 3rem, 4rem, 5rem`

### Typography Utilities

```html
<!-- Text alignment -->
<p class="text-center">Centered text</p>
<p class="text-right">Right-aligned</p>

<!-- Font weight -->
<p class="font-light">Light text</p>
<p class="font-bold">Bold text</p>

<!-- Text color -->
<p class="text-primary">Primary color</p>
<p class="text-muted">Muted gray</p>
<p class="text-error">Error red</p>

<!-- Font size -->
<p class="text-xs">Extra small</p>
<p class="text-lg">Large</p>
<p class="text-2xl">Extra large</p>

<!-- Text transformation -->
<p class="text-uppercase">UPPERCASE</p>
<p class="text-capitalize">Capitalized</p>
```

### Display & Flexbox Utilities

```html
<!-- Display -->
<div class="d-flex">Flexbox container</div>
<div class="d-grid">Grid container</div>
<div class="d-none">Hidden</div>

<!-- Flexbox alignment -->
<div class="d-flex justify-center align-center">Centered content</div>
<div class="d-flex justify-between">Space between items</div>

<!-- Flex direction -->
<div class="d-flex flex-column">Vertical stack</div>
```

### Background & Border Utilities

```html
<!-- Background colors -->
<div class="bg-primary">Primary background</div>
<div class="bg-dark">Dark background</div>

<!-- Borders -->
<div class="border">All borders</div>
<div class="border-bottom">Bottom border only</div>
<div class="rounded">Rounded corners</div>
<div class="rounded-full">Circular</div>
```

### Accessibility Utilities

```html
<!-- Screen reader only (hide visually, keep for accessibility) -->
<span class="sr-only">Skipped by visual users</span>

<!-- Visually hidden (alternative name) -->
<span class="visually-hidden">Screen reader text</span>
```

### Responsive Utilities

Bootstrap uses responsive breakpoint suffixes:

```html
<!-- Hide on mobile, show on tablet+ -->
<div class="d-none d-md-block">
  Visible only on medium screens and larger
</div>

<!-- Center text on mobile, left-align on desktop -->
<p class="text-center text-lg-start">Responsive alignment</p>
```

**Breakpoints:**

- `xs` - Extra small (default, no prefix needed)
- `sm` - Small (≥576px)
- `md` - Medium (≥768px)
- `lg` - Large (≥992px)
- `xl` - Extra large (≥1200px)
- `xxl` - Extra extra large (≥1400px)

---

## BEM and Twig Components

**Key Relationship:** Each Twig component in this theme maps directly to a BEM block.

### How It Works

The connection between template architecture and CSS architecture:

```
templates/components/card.twig    →    css/inc/components/card.scss

Block: .card
├── Elements: .card__image, .card__content, .card__title
└── Modifiers: .card--featured, .card--minimal
```

### Example: Card Component Architecture

**1. Twig Template** (`templates/components/card.twig`)

```twig
{# Component variables: card, featured #}
<div class="card {% if featured %}card--featured{% endif %}">
    <img class="card__image" src="{{ card.image }}" />
    <div class="card__content">
        <h3 class="card__title">{{ card.title }}</h3>
        <p class="card__text">{{ card.description }}</p>
    </div>
</div>
```

**2. SCSS Component** (`css/inc/components/card.scss`)

```scss
.card {
  // Matches Twig structure
  &__image {
  } // <img class="card__image">
  &__content {
  } // <div class="card__content">
  &__title {
  } // <h3 class="card__title">
  &__text {
  } // <p class="card__text">

  &--featured {
  } // {% if featured %}card--featured{% endif %}
}
```

**3. Usage in Templates**

```twig
{% include 'components/card.twig' with {
    card: post,
    featured: true
} %}
```

Output:

```html
<div class="card card--featured">
  <img class="card__image" ... />
  <div class="card__content">
    <h3 class="card__title">...</h3>
    <p class="card__text">...</p>
  </div>
</div>
```

### Design Pattern

1. **Template files** define HTML structure and classes
2. **SCSS files** follow the exact same BEM block/element structure
3. **Variables** control modifiers (✓ Featured, ✓ Large, etc.)
4. **Consistency** makes CSS changes when updating components

### Benefits

✅ **Single Responsibility** - Component logic in Twig, styling in SCSS  
✅ **Easy to Find Code** - Need to update card styling? Look in `card.scss`  
✅ **Reusable** - Use same component with different modifiers  
✅ **Maintainable** - Changes are isolated to one component  
✅ **Scalable** - Add components without affecting others

---

## Examples

### Example 1: Building a Card Component

**HTML:**

```html
<div class="card card--featured">
  <img class="card__image" src="image.jpg" alt="Card image" />
  <div class="card__content">
    <h3 class="card__title">Card Title</h3>
    <p class="card__text">Description of the card content goes here.</p>
    <div class="card__meta">
      <span class="text-muted">Author name</span>
    </div>
  </div>
</div>
```

**SCSS (`css/inc/components/card.scss`):**

```scss
.card {
  // Default styles
  padding: 1rem;
  border-radius: 4px;
  background: white;

  // Elements
  &__image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 4px;
  }

  &__content {
    margin-top: 1rem;
  }

  &__title {
    margin: 0 0 0.5rem;
    font-size: 1.25rem;
  }

  // Modifiers
  &--featured {
    border: 2px solid $primary;

    .card__image {
      height: 250px;
    }
  }
}
```

### Example 2: Button with Multiple Modifiers

**HTML:**

```html
<!-- Primary button -->
<button class="btn btn--primary">
  <span class="btn__text">Click me</span>
</button>

<!-- Secondary, large button -->
<button class="btn btn--secondary btn--lg">
  <span class="btn__text">Click me</span>
  <span class="btn__icon">→</span>
</button>

<!-- Outlined button -->
<a href="#" class="btn btn--outline btn--full">
  <span class="btn__text">Full width link button</span>
</a>
```

**SCSS (`css/inc/base/btns.scss`):**

```scss
.btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;

  &__text {
  }
  &__icon {
  }

  // Modifier: primary variant
  &--primary {
    background: $primary;
    color: white;

    &:hover {
      background: darken($primary, 10%);
    }
  }

  // Modifier: size large
  &--lg {
    padding: 1rem 2rem;
    font-size: 1.125rem;
  }

  // Modifier: outline variant
  &--outline {
    background: transparent;
    border: 2px solid $primary;
    color: $primary;
  }

  // Modifier: full width
  &--full {
    width: 100%;
  }
}
```

### Example 3: Alert Component with Multiple Types

**HTML:**

```html
<!-- Success alert -->
<div class="alert alert--success" role="alert">
  <p class="alert__content">Operation completed successfully!</p>
</div>

<!-- Error alert with title -->
<div class="alert alert--error">
  <h4 class="alert__title">Error</h4>
  <p class="alert__content">Something went wrong. Please try again.</p>
</div>

<!-- Dismissible warning -->
<div class="alert alert--warning" role="alert">
  <p class="alert__content">Please review before proceeding.</p>
  <button class="alert__close" aria-label="Close alert">×</button>
</div>
```

**SCSS (`css/inc/components/alert.scss`):**

```scss
.alert {
  padding: 1rem;
  border-left: 4px solid currentColor;
  border-radius: 4px;

  &__title {
    margin: 0 0 0.5rem;
    font-weight: bold;
  }

  &__content {
    margin: 0;
  }

  &__close {
    float: right;
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
  }

  // Type modifier: success
  &--success {
    background: #d4edda;
    color: #155724;
    border-color: #28a745;
  }

  // Type modifier: error
  &--error {
    background: #f8d7da;
    color: #721c24;
    border-color: #dc3545;
  }

  // Type modifier: warning
  &--warning {
    background: #fff3cd;
    color: #856404;
    border-color: #ffc107;
  }

  // Type modifier: info
  &--info {
    background: #d1ecf1;
    color: #0c5460;
    border-color: #17a2b8;
  }
}
```

---

## Best Practices

### ✅ DO

1. **Keep elements flat in BEM**

   ```scss
   .card {
     &__image {
     }
     &__title {
     }
     &__text {
     } // Not .card__content__text
   }
   ```

2. **Use modifiers for variations**

   ```scss
   // Good: Clear variant
   .button--primary { }
   .button--disabled { }

   // Not: Multiple modifiers can stack
   <div class="button button--primary button--lg"></div>
   ```

3. **Keep specificity low**

   ```scss
   // Good: One class selector
   .card {
   }

   // Avoid: High specificity
   .container .card {
   }
   div.card {
   }
   ```

4. **Use utilities for one-off needs**

   ```html
   <!-- Quick spacing adjustment -->
   <div class="card mt-4">
     <!-- Quick alignment -->
     <div class="d-flex justify-center"></div>
   </div>
   ```

5. **Document your components**
   ```scss
   /**
    * Card Component - BEM Methodology
    *
    * Block: .card
    * Elements: .card__image, .card__title, .card__content
    * Modifiers: .card--featured, .card--minimal
    */
   ```

### ❌ DON'T

1. **Don't create elements of elements**

   ```scss
   // ❌ Bad
   .card__content__title {
   }

   // ✅ Good
   .card__title {
   }
   ```

2. **Don't use BEM for utility classes**

   ```scss
   // ❌ Don't BEM utilities
   .margin__top--large {
   }

   // ✅ Just make them simple
   .mt-4 {
   }
   ```

3. **Don't chain multiple modifiers for the same property**

   ```scss
   // ❌ Avoid
   .button--primary {
     color: white;
   }
   .button--secondary {
     color: white;
   }

   // ✅ Better
   .button {
     color: white;
   }
   .button--primary {
     background: blue;
   }
   .button--secondary {
     background: gray;
   }
   ```

4. **Don't use !important with BEM**

   ```scss
   // ❌ Defeats purpose of BEM
   .card--featured {
     color: red !important;
   }

   // ✅ BEM manages specificity
   .card--featured {
     color: red;
   }
   ```

5. **Don't nest more than 2 levels**

   ```scss
   // ❌ Too much nesting
   .card {
     &__content {
       &__title {
         &:hover {
           // Deep nesting hurts readability
         }
       }
     }
   }

   // ✅ Flat or 1-level deep
   .card {
     &__title:hover {
       // Clear and readable
     }
   }
   ```

---

## File Structure

```
wp-content/themes/starter-theme-1.x/
├── css/inc/
│   ├── base/              # Foundation styles
│   │   ├── vars.scss      # Variables, fonts, breakpoints
│   │   ├── reset.scss     # HTML element resets
│   │   ├── layout.scss    # Page layout grid
│   │   ├── font.scss      # Typography styles
│   │   └── btns.scss      # Button component (BEM)
│   │
│   ├── components/        # Reusable BEM components
│   │   ├── card.scss      # Card component
│   │   ├── alert.scss     # Alert component
│   │   └── badge.scss     # Badge component
│   │
│   ├── global/            # Large sections
│   │   ├── nav.scss       # Navigation (BEM)
│   │   └── footer.scss    # Footer (BEM)
│   │
│   ├── blocks/            # Gutenberg block styles
│   │   └── text.scss      # Text block
│   │
│   └── utilities/         # Helper utilities
│       └── helpers.scss   # Bootstrap utilities + custom extensions
│
└── style.scss             # Main import file (organized in layers)
```

---

## Creating New Components

When creating a new component, follow this template:

**File:** `css/inc/components/my-component.scss`

```scss
/**
 * My Component - BEM Methodology
 *
 * Block: .my-component
 * Elements: .my-component__element1, .my-component__element2
 * Modifiers: .my-component--variant1, .my-component--variant2
 *
 * Usage:
 * <div class="my-component my-component--variant1">
 *     <div class="my-component__element1">Content</div>
 *     <div class="my-component__element2">More content</div>
 * </div>
 */

.my-component {
  // Base styles for the block
  padding: 1rem;
  background: white;
  border-radius: 4px;

  // Elements
  &__element1 {
    margin-bottom: 1rem;
  }

  &__element2 {
    font-size: 0.875rem;
  }

  // Modifiers
  &--variant1 {
    background: $primary;
    color: white;
  }

  &--variant2 {
    border: 2px solid $secondary;
  }
}
```

Then add the import to `style.scss`:

```scss
@import "inc/components/my-component";
```

---

## Resources

- [BEM Official Documentation](https://bem.info/)
- [SMACSS (Scalable Modular Architecture for CSS)](http://smacss.com/)
- [Sass Documentation](https://sass-lang.com/documentation)
- [Bootstrap 5 Documentation](https://getbootstrap.com/docs/5.0/)

---

## Support

For questions about this CSS architecture, refer to:

1. Component comments in individual `.scss` files
2. HTML examples in the Twig template files
3. [STYLE-SHEET.md](./STYLE-SHEET.md) for design system variables, colors, typography, and spacing
4. [CONFIG-GUIDE.md](./CONFIG-GUIDE.md) for theme configuration
5. [COMPONENTS-GUIDE.md](./COMPONENTS-GUIDE.md) for Twig component usage
