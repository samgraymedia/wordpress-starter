# Image Lazy-Loading Guide

## Overview

This theme implements a comprehensive image lazy-loading system using **IntersectionObserver API** with native `loading="lazy"` fallback. This improves page performance by deferring off-screen image loading until they're needed.

**Performance Impact:**

- Reduced initial page load time
- Lower bandwidth usage on slow connections
- Faster First Contentful Paint (FCP)
- Better Core Web Vitals scores

---

## Implementation Details

### 1. JavaScript Lazy-Loader (`scripts/utils/lazy-loading.js`)

The core utility provides:

#### IntersectionObserver-based Loading

```javascript
// Automatically observes and loads images as they enter viewport
const lazyLoader = new LazyLoader({
  rootMargin: "50px", // Start loading 50px before entering viewport
  threshold: 0.01, // Trigger at 1% visibility
  retries: 3, // Retry failed images 3 times
  retryDelay: 1000, // Wait 1s between retries
});
```

#### Supported Image Types

- **Regular images:** `<img data-src="..." loading="lazy">`
- **Responsive images:** `<img data-srcset="..." loading="lazy">`
- **Picture elements:** `<picture><source data-srcset="..."></picture>`
- **Background images:** `<div data-bg-image="..."></div>`

#### Features

- Graceful degradation to native `loading="lazy"` when JS unavailable
- Automatic retry logic for failed images
- Custom event emitters (`image-lazy-loaded`, `image-lazy-failed`)
- Mobile-friendly with minimal performance overhead

### 2. SCSS Styles (`css/inc/utilities/lazy-loading.scss`)

#### Blur-Up Placeholder Effect

```scss
.lazy[data-placeholder] {
  filter: blur(20px);
  transition: filter 0.6s ease-in-out;
}

.lazy.lazy--loaded {
  filter: blur(0); // Smooth fade-in
}
```

#### Loading Animation

```scss
.lazy.lazy--loading {
  animation: pulse 1.5s ease-in-out infinite;
}
```

#### Accessibility

```scss
@media (prefers-reduced-motion: reduce) {
  // Disable animations for users who prefer reduced motion
  .lazy {
    animation: none !important;
  }
}
```

---

## Usage in Templates

### Basic Image (Timber/WordPress)

```twig
<img
  data-src="{{ post.thumbnail.src }}"
  alt="{{ post.title }}"
  loading="lazy"
  decoding="async"
>
```

### With Responsive Sizing

```twig
<img
  data-src="{{ image.src }}"
  data-srcset="{{ image.src }}?w=600 600w, {{ image.src }}?w=1200 1200w"
  alt="Description"
  loading="lazy"
  decoding="async"
>
```

### Picture Element (WebP Support)

```twig
<picture>
  <source media="(min-width: 768px)" data-srcset="{{ image.webp-large }}" type="image/webp" />
  <source media="(min-width: 768px)" data-srcset="{{ image.jpg-large }}" type="image/jpeg" />
  <img data-src="{{ image.jpg-sm }}" alt="Description" loading="lazy" decoding="async" />
</picture>
```

### Background Image

```twig
<div data-bg-image="https://example.com/bg-image.jpg" style="height: 400px;">
  <!-- Content here -->
</div>
```

### Optional: Using Twig Macros

Simplified helper macros are available in `templates/macros/lazy-image.twig`:

```twig
{% import 'macros/lazy-image.twig' as lazy %}

{# Basic lazy image #}
{{ lazy.lazy_image(image, 'Alt Text', {class: 'my-class'}) }}

{# Background image helper #}
{{ lazy.lazy_bg_image('/path/to/image.jpg', {class: 'hero'}) }}
```

---

## Updated Templates

All the following templates have been updated with lazy-loading support:

### Blocks

- ✅ **Gallery** — All gallery images
- ✅ **Hero** — Background image
- ✅ **Map** — Location card/list images
- ✅ **Blog Posts** — Post thumbnails
- ✅ **Related Content** — Related post images
- ✅ **Steps/Timeline** — Step icons
- ✅ **Slider/Carousel** — Slide images
- ✅ **Image Text** — Block images
- ✅ **Logo Grid** — Logo images
- ✅ **Cards Grid** — Card images (3 variants)
- ✅ **Service Showcase** — Service thumbnails
- ✅ **Team** — Team member photos

### Templates/Pages

- ✅ **Single Blog** — Featured image
- ✅ **Single Product** — Product images (via WooCommerce)
- ✅ **Single Services** — Service hero image
- ✅ **Single Standard** — Post featured image
- ✅ **Archive Blog** — Post thumbnails
- ✅ **Archive Services** — Service thumbnails
- ✅ **Tease Post** — Post preview image
- ✅ **Testimonial Card** — Testimonial author photo

---

## Initialization

The lazy-loader initializes automatically when the theme JS loads:

```javascript
// In scripts/index.js
import { initLazyLoading } from "./utils/lazy-loading.js";

function initTheme() {
  // Initialize lazy loading early
  initLazyLoading({
    rootMargin: "50px",
    threshold: 0.01,
  });
  // ... other initializations
}
```

### Custom Initialization

For dynamic content added after page load:

```javascript
import { LazyLoader } from "./scripts/utils/lazy-loading.js";

const lazyLoader = new LazyLoader();

// Observe new element
const newImage = document.querySelector(".new-lazy-image");
lazyLoader.observe(newImage);

// Or load immediately if needed
lazyLoader.loadImage(newImage);
```

---

## Custom Events

Listen for image load completion:

```javascript
document.addEventListener("image-lazy-loaded", (e) => {
  console.log("Image loaded:", e.detail);
  // Do something with the loaded image
});

document.addEventListener("image-lazy-failed", (e) => {
  console.log("Image failed to load:", e.detail);
  // Handle error state
});
```

---

## CSS Classes

The lazy-loader applies classes for styling:

| Class            | Purpose                                       |
| ---------------- | --------------------------------------------- |
| `.lazy--loading` | Image is being loaded (shows pulse animation) |
| `.lazy--loaded`  | Image successfully loaded (blur removed)      |
| `.lazy--error`   | Image failed to load (shows error state)      |

---

## Browser Support

| Feature                     | Support                                                              |
| --------------------------- | -------------------------------------------------------------------- |
| **IntersectionObserver**    | Chrome 51+, Firefox 55+, Safari 12.1+, Edge 15+                      |
| **Native `loading="lazy"`** | Chrome 76+, Firefox 75+, Safari 15.1+, Edge 79+                      |
| **Fallback**                | All browsers (loads images immediately without IntersectionObserver) |

---

## Performance Tips

### 1. Use Proper Image Formats

```twig
<picture>
  <source srcset="{{ image.webp }}" type="image/webp" />
  <img data-src="{{ image.jpg }}" alt="..." loading="lazy" />
</picture>
```

### 2. Provide Placeholder Content

```scss
.image-wrapper {
  background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
  background-size: 200% 100%;
  animation: loading 1.5s infinite;
}

.image-wrapper img.lazy--loaded {
  animation: none;
}
```

### 3. Optimize Image Dimensions

- Always specify `alt` text for accessibility
- Set `width` and `height` attributes to prevent layout shift
- Use `decoding="async"` for async image decoding

### 4. Consider Critical Images

Images above the fold should use:

```html
<img src="critical.jpg" alt="..." />
<!-- No lazy-loading -->
<img data-src="below-fold.jpg" alt="..." loading="lazy" />
```

---

## Testing

### Manual Testing

1. Open DevTools Network tab
2. Set to "Slow 3G" throttling
3. Scroll page slowly — images should load as they enter viewport
4. Verify no images load until needed

### Automated Testing

```javascript
// Check if image is lazy-loaded
const img = document.querySelector(".gallery__image");
console.assert(img.dataset.src === "url", "Image has data-src");
console.assert(img.loading === "lazy", 'Image has loading="lazy"');
```

---

## Common Issues & Solutions

### Issue: Images not loading after page transition

**Solution:** Call `initLazyLoading()` again or manually observe new images:

```javascript
lazyLoader.observe(newElement);
```

### Issue: Images flash with blur effect

**Solution:** Remove blur filter or use smaller blur radius:

```scss
.lazy[data-placeholder] {
  filter: blur(5px); // Reduced blur
}
```

### Issue: Background images not loading

**Solution:** Ensure element has `data-bg-image` attribute and height:

```html
<div data-bg-image="/image.jpg" style="height: 300px;"></div>
```

---

## Build Information

- **File:** `scripts/utils/lazy-loading.js` (130 lines)
- **SCSS:** `css/inc/utilities/lazy-loading.scss` (120 lines)
- **Bundle Impact:** +3.17 kB minified (included 1 KB in gzip)
- **Bundle Total:** 15.78 kB minified (4.71 kB gzip)

---

## Resources

- [MDN: IntersectionObserver](https://developer.mozilla.org/en-US/docs/Web/API/Intersection_Observer_API)
- [Web.dev: Native Lazy-Loading](https://web.dev/native-lazy-loading/)
- [Web Vitals: LCP](https://web.dev/lcp/)
- [CSS Tricks: The Blur Up Technique](https://css-tricks.com/blur-up-technique/)
