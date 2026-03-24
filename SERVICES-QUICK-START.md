# Services System Quick Start

**Fast reference for common service tasks**

---

## 🚀 First Time Setup

### 1. Build Assets

```bash
npm run build    # Compiles SCSS + JavaScript
# or for development:
npm run watch    # Watch mode
```

### 2. Create First Service

1. WordPress Admin → Services → Add New
2. Fill in: Title, Description, Featured Image
3. Scroll down to "Service Details" (ACF)
4. Add Price, Duration, Features
5. Publish

### 3. Add Service to Page

1. Edit/Create a page
2. Click "+" to add block
3. Search "Service"
4. Choose one: Showcase, Comparison, Pricing, or Testimonials
5. Configure block settings
6. Publish

---

## 📋 File Locations

| Task              | File                              |
| ----------------- | --------------------------------- |
| Service post type | `src/post-types/service.php`      |
| Service blocks    | `src/blocks/service-*.php`        |
| Service archive   | `templates/archive-services.twig` |
| Service single    | `templates/single-services.twig`  |
| Service styling   | `css/inc/blocks/service-*.scss`   |
| Complete guide    | `SERVICES-SYSTEM.md`              |

---

## 🎯 Common Tasks

### Adjust Service Price Format

**File:** `views/blocks/service-pricing.twig`

```twig
<span class="currency">{{ currency }}</span>
<span class="amount">{{ price|number_format(0) }}</span>
```

### Change Service Icon

**File:** `src/post-types/service.php`

```php
'field_service_icon' => [
    'instructions' => 'FontAwesome class or Dashicons',
    'placeholder' => 'fas fa-briefcase'
]
```

### Add New Service Block

1. Create `src/blocks/service-foo.php` with ACF fields
2. Create `views/blocks/service-foo.twig` with Twig headers
3. Create `css/inc/blocks/service-foo.scss` for styles
4. Done! Auto-discovered and registered

### Link Testimonials to Service

1. Edit Service post
2. Scroll to "Related Testimonials" (ACF)
3. Select testimonials
4. They display on single service page + in blocks

---

## 🔧 Block Configuration

### Service Showcase Block

```php
[
    'layout' => 'grid-3',      // Options: grid-2, grid-3, grid-4, list
    'orderby' => 'menu_order', // Options: date, title, menu_order
    'order' => 'ASC',          // Options: ASC, DESC
    'show_price' => true,
    'show_features' => true,
    'show_cta' => true,
]
```

### Service Comparison Block

```php
[
    'services' => [...],       // Required: 2-4 services
    'layout' => 'table',       // Options: table, cards, accordion
    'highlight' => true,
    'best_choice' => [...],    // Highlight one as "Most Popular"
]
```

### Service Pricing Block

```php
[
    'services' => [...],       // Required
    'columns' => '3',          // Options: 2, 3, 4
    'highlight' => true,
    'featured_service' => [...],
    'currency' => '$',
    'show_duration' => true,
    'show_features' => true,
]
```

### Service Testimonials Carousel Block

```php
[
    'service' => null,         // Optional: filter by service
    'limit' => 6,              // Number to show
    'layout' => 'slider',      // Options: slider, grid, single
    'columns' => '1',          // For grid layout
    'show_rating' => true,
    'show_author' => true,
    'show_location' => false,
    'autoplay' => true,        // For carousel
    'autoplay_delay' => 5,     // Seconds
]
```

---

## 🎨 Styling

### Edit Service Card Styling

**File:** `css/inc/blocks/service-showcase.scss`

```scss
.service-card {
  border: 1px solid #e0e0e0;
  border-radius: 0.5rem;
  padding: 1.5rem;

  &:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
  }
}
```

### Add Custom Color

Edit template files and add color utilities:

```twig
<div class="service-card bg-primary text-white">
    ...
</div>
```

### Customize Testimonial Stars

**File:** `views/blocks/service-testimonials-carousel.twig`

```twig
{% for i in 1..rating %}
    <span class="star">★</span>  {# Change to ⭐ or custom#}
{% endfor %}
```

---

## 📊 Database Queries

### Get All Services

```php
$services = Timber::get_posts([
    'post_type' => 'services',
    'posts_per_page' => -1,
]);
```

### Get Services by Category

```php
$services = Timber::get_posts([
    'post_type' => 'services',
    'tax_query' => [[
        'taxonomy' => 'service-category',
        'field' => 'slug',
        'terms' => 'web-development',
    ]],
]);
```

### Get Single Service Fields

```php
$service = Timber::get_post($id, 'services');
$price = $service->get_field('service_price');
$features = $service->get_field('service_features');
$testimonials = $service->get_field('related_testimonials');
```

---

## ❓ Troubleshooting

| Issue                         | Solution                                           |
| ----------------------------- | -------------------------------------------------- |
| Services not showing in admin | Run `npm run build`, check debug.log               |
| Blocks not appearing          | Clear cache, verify Twig file headers              |
| Styling looks wrong           | Rebuild CSS: `npm run build`, hard refresh browser |
| ACF fields missing            | Check ACF plugin active, field definitions saved   |
| Testimonials not linking      | Ensure testimonials exist, field configured        |

---

## 📚 Full Documentation

See **SERVICES-SYSTEM.md** for:

- Complete architecture overview
- All field specifications
- Workflow examples
- Performance optimization
- Future enhancements
- Database schema

---

**Version:** 1.0.0 | **Status:** Production Ready
