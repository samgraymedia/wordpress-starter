# Style Sheet & Design System

Complete reference for the theme's design system variables, colors, typography, spacing, and styling conventions.

---

## Colors

### Primary & Secondary

| Variable     | Hex       | Usage                   |
| ------------ | --------- | ----------------------- |
| `$primary`   | `#0066cc` | Links, CTAs, highlights |
| `$secondary` | `#6c757d` | Secondary actions       |
| Base Colors  | —         | —                       |
| `$black`     | `black`   | Text, dark elements     |
| `$white`     | `white`   | Backgrounds, text       |

### Status & Semantic Colors

| Variable   | Hex       | Usage                 |
| ---------- | --------- | --------------------- |
| `$success` | `#28a745` | Success states        |
| `$warning` | `#ffc107` | Warning states        |
| `$danger`  | `#dc3545` | Error/danger states   |
| `$info`    | `#17a2b8` | Informational content |

### Validation States

```scss
// Success
$success-color: #155724; // Text
$success-bg: #d4edda; // Background
$success-border: #c3e6cb; // Border

// Error
$error-color: #dc3545; // Text
$error-bg: #f8d7da; // Background
$error-border: #f5c6cb; // Border

// Warning
$warning-color: #856404; // Text
$warning-bg: #fff3cd; // Background
$warning-border: #ffeaa7; // Border
```

### Gray Scale

Used for text, dividers, and neutral elements.

| Variable    | Hex       | Usage                  |
| ----------- | --------- | ---------------------- |
| `$gray-900` | `#212529` | Body text, headings    |
| `$gray-800` | `#343a40` | Strong text            |
| `$gray-700` | `#495057` | Secondary text         |
| `$gray-600` | `#6c757d` | Muted/secondary text   |
| `$gray-500` | `#adb5bd` | Disabled elements      |
| `$gray-400` | `#ced4da` | Form borders           |
| `$gray-300` | `#dee2e6` | Dividers, rules        |
| `$gray-200` | `#e9ecef` | Light backgrounds      |
| `$gray-100` | `#f8f9fa` | Very light backgrounds |
| `$gray-50`  | `#f9fafb` | Lightest backgrounds   |

### Color Aliases

Convenient shortcuts for common use cases:

```scss
$text-color: $gray-900; // Default text
$text-muted: $gray-600; // Secondary/muted text
$border-color: $gray-300; // Default borders
$bg-light: $gray-50; // Light backgrounds
```

---

## Typography

### Font Sizes

Modular scale based on 1rem (16px) base.

| Variable     | Size     | px   | Usage            |
| ------------ | -------- | ---- | ---------------- |
| `$font-xs`   | 0.875rem | 14px | Labels, captions |
| `$font-sm`   | 0.875rem | 14px | Body small       |
| `$font-base` | 1rem     | 16px | Body regular     |
| `$font-md`   | 1.125rem | 18px | Body large       |
| `$font-lg`   | 1.25rem  | 20px | Larger text      |
| `$font-xl`   | 1.5rem   | 24px | Heading 3        |
| `$font-2xl`  | 1.875rem | 30px | —                |
| `$font-3xl`  | 2.25rem  | 36px | Heading 2        |
| `$font-4xl`  | 3rem     | 48px | Large heading    |
| `$font-5xl`  | 3.75rem  | 60px | Heading 1        |
| `$font-6xl`  | 4.5rem   | 72px | Hero/jumbo       |

### Font Families

```scss
$font-family-base: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
  "Helvetica Neue", Arial, sans-serif;
$font-family-heading: $font-family-base; // Same as body
```

### Font Weights

| Variable          | Weight | Usage          |
| ----------------- | ------ | -------------- |
| `$font-thin`      | 100    | Thin (rare)    |
| `$font-light`     | 300    | Light text     |
| `$font-regular`   | 400    | Default/body   |
| `$font-medium`    | 500    | Medium/strong  |
| `$font-semibold`  | 600    | Semi bold      |
| `$font-bold`      | 700    | Bold           |
| `$font-extrabold` | 800    | Extra bold     |
| `$font-black`     | 900    | Black (rarely) |

### Line Heights

```scss
$line-height-base: 1.5; // Normal body text
$line-height-heading: 1.2; // Compact for headings
```

---

## Spacing Scale

Consistent spacing using rem units (divisible by 16px base).

| Variable     | rem  | px   | Usage                   |
| ------------ | ---- | ---- | ----------------------- |
| `$spacing-1` | 0.25 | 4px  | Minimal whitespace      |
| `$spacing-2` | 0.5  | 8px  | Small gaps              |
| `$spacing-3` | 1    | 16px | Standard spacing (base) |
| `$spacing-4` | 1.5  | 24px | Medium gaps             |
| `$spacing-5` | 2    | 32px | Large gaps              |
| `$spacing-6` | 3    | 48px | Extra large gaps        |

### Common Uses

- **Padding**: `$spacing-3` for standard, `$spacing-4` for larger elements
- **Margins**: `$spacing-3` between blocks, `$spacing-5` for major sections
- **Gaps**: `$grid-gap: 2rem` for grid layouts

---

## Layout

```scss
$site-max-width: 1700px; // Primary container max width
$block-padding: 2rem; // Standard block padding
$grid-gap: 2rem; // Grid row/column gap
```

---

## Breakpoints

Mobile-first responsive design breakpoints.

| Variable          | px     | Usage                        |
| ----------------- | ------ | ---------------------------- |
| `$breakpoint-xs`  | 320px  | Extra small (phones)         |
| `$breakpoint-sm`  | 480px  | Small devices (phones)       |
| `$breakpoint-md`  | 768px  | Medium devices (tablets)     |
| `$breakpoint-lg`  | 992px  | Large devices (desktops)     |
| `$breakpoint-xl`  | 1200px | Extra large (large desktops) |
| `$breakpoint-2xl` | 1400px | 2X Extra large (max desktop) |

### Usage Examples

```scss
// Mobile-first approach
.element {
  // Default: mobile styles
}

// Tablet and up
@media (min-width: $breakpoint-md) {
  .element {
    // Tablet and larger
  }
}

// Desktop and up
@media (min-width: $breakpoint-lg) {
  .element {
    // Desktop and larger
  }
}
```

---

## Border Radius

Rounded corner sizes for consistency.

| Variable            | px  | Usage                 |
| ------------------- | --- | --------------------- |
| `$border-radius-sm` | 4px | Small buttons, inputs |
| `$border-radius`    | 6px | Standard elements     |
| `$border-radius-lg` | 8px | Larger components     |

---

## Icon Sizes

Standardized icon dimensions.

| Variable        | Size | Usage          |
| --------------- | ---- | -------------- |
| `$icon-size-sm` | 16px | Small icons    |
| `$icon-size-md` | 20px | Standard icons |
| `$icon-size-lg` | 24px | Large icons    |
| `$icon-size-xl` | 32px | Hero/featured  |

---

## Transitions & Animations

```scss
$transition-fast: 0.15s ease-in-out; // Quick feedback (hovers)
$transition-normal: 0.3s ease-in-out; // Standard transitions
$transition-slow: 0.5s ease-in-out; // Longer animations
```

### Usage

```scss
// Button hover
.button {
  transition: background-color $transition-fast;
}

// Modal appear
.modal {
  transition: opacity $transition-normal;
}
```

---

## Shadows

Elevation/depth through shadows.

| Variable     | CSS                              | Usage             |
| ------------ | -------------------------------- | ----------------- |
| `$shadow-sm` | `0 1px 3px rgba(0, 0, 0, 0.1)`   | Subtle lift       |
| `$shadow-md` | `0 4px 6px rgba(0, 0, 0, 0.1)`   | Cards, buttons    |
| `$shadow-lg` | `0 10px 15px rgba(0, 0, 0, 0.1)` | Floating elements |
| `$shadow-xl` | `0 20px 25px rgba(0, 0, 0, 0.1)` | Modals, popovers  |

### Usage

```scss
.card {
  box-shadow: $shadow-md;
}

.card:hover {
  box-shadow: $shadow-lg;
}

.modal {
  box-shadow: $shadow-xl;
}
```

---

## Z-Index Scale

Stacking context for layered elements.

| Variable            | Value | Usage                    |
| ------------------- | ----- | ------------------------ |
| `$z-dropdown`       | 1000  | Dropdowns, tooltips      |
| `$z-sticky`         | 1020  | Sticky headers           |
| `$z-fixed`          | 1030  | Fixed positioning        |
| `$z-modal-backdrop` | 1040  | Modal backgrounds        |
| `$z-modal`          | 1050  | Modal windows            |
| `$z-popover`        | 1060  | Popovers                 |
| `$z-tooltip`        | 1070  | Tooltips (always on top) |

### Usage

```scss
.modal {
  z-index: $z-modal;
}

.modal-backdrop {
  z-index: $z-modal-backdrop;
}

.tooltip {
  z-index: $z-tooltip;
}
```

---

## BEM Naming Convention

All styles follow BEM (Block-Element-Modifier) methodology:

```scss
// Block: standalone component
.button {
}

// Element: part of a block
.button__text {
}

// Modifier: variation of block/element
.button--primary {
}
.button--large {
}
.button__text--bold {
}
```

### Guidelines

- Use BEM for all custom components
- Avoid deep nesting (max 2-3 levels)
- Use variables for all design values
- Reference this sheet for standard values

---

## Practical Examples

### Button with Hover Effect

```scss
.button {
  background-color: $primary;
  color: $white;
  padding: $spacing-3 $spacing-4;
  border-radius: $border-radius;
  font-weight: $font-semibold;
  transition: background-color $transition-fast;
  box-shadow: $shadow-md;

  &:hover {
    background-color: darken($primary, 10%);
    box-shadow: $shadow-lg;
  }
}

.button--secondary {
  background-color: $secondary;
}
```

### Card Component

```scss
.card {
  background-color: $white;
  border: 1px solid $border-color;
  border-radius: $border-radius-lg;
  padding: $spacing-4;
  box-shadow: $shadow-sm;
  transition: box-shadow $transition-normal;

  &:hover {
    box-shadow: $shadow-md;
  }
}

.card__title {
  font-size: $font-xl;
  font-weight: $font-bold;
  color: $text-color;
  margin-bottom: $spacing-2;
}

.card__text {
  font-size: $font-base;
  color: $text-muted;
  line-height: $line-height-base;
}
```

### Responsive Layout

```scss
.grid {
  display: grid;
  grid-gap: $grid-gap;
  grid-template-columns: 1fr; // Mobile: 1 column

  @media (min-width: $breakpoint-md) {
    grid-template-columns: repeat(2, 1fr); // Tablet: 2 columns
  }

  @media (min-width: $breakpoint-lg) {
    grid-template-columns: repeat(3, 1fr); // Desktop: 3 columns
  }
}
```

---

## File Location

All variables are defined in: `css/inc/base/vars.scss`

Import in your SCSS files:

```scss
@import "inc/base/vars";

.my-component {
  color: $primary;
  padding: $spacing-3;
}
```

## Support

For questions about the design system or styling, refer to:

1. [BEM-GUIDE.md](./BEM-GUIDE.md) - CSS architecture and naming conventions
2. [CONFIG-GUIDE.md](./CONFIG-GUIDE.md) - Theme configuration
3. [COMPONENTS-GUIDE.md](./COMPONENTS-GUIDE.md) - Twig component usage
