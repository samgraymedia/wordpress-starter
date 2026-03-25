# Dark Mode Support Guide

Complete guide to the dark mode implementation using CSS custom properties and system preference detection.

---

## Overview

The Starter Theme includes full dark mode support with:

- **Automatic system preference detection** – Respects macOS/Windows dark mode settings
- **Manual theme toggle** – Users can override system preference
- **Persistent preferences** – User choice saved in localStorage (and server-side for logged-in users)
- **Runtime switching** – No page reload needed; smooth color transitions
- **CSS variables** – All colors use CSS custom properties for easy theming
- **Accessibility** – Proper ARIA labels, keyboard navigation, respects `prefers-reduced-motion`
- **Print friendly** – Always uses light mode for printing to save ink

---

## How It Works

### 1. **System Preference Detection**

By default, the theme respects the user's operating system dark mode setting:

```javascript
// Automatically detected via:
window.matchMedia("(prefers-color-scheme: dark)");

// Light mode: Windows/macOS with system light mode
// Dark mode:  Windows/macOS with system dark mode
```

**Browser Support:**

- Chrome 76+
- Firefox 67+
- Safari 12.1+
- Edge 79+

### 2. **Manual Theme Toggle**

Users can click the toggle button (top-right by default) to manually override system preference:

- **Light mode** → Click button → **Dark mode**
- **Dark mode** → Click button → **System preference**
- **System mode** → Click button → **Light mode**

### 3. **Preference Storage**

**Anonymous users:**

- Theme preference stored in `localStorage` only
- Persists across browser sessions
- Lost if user clears browser data

**Logged-in users:**

- Preference saved to WordPress user meta
- REST API: `POST /wp-json/starter-theme/v1/theme-preference`
- Preference syncs across all logged-in browsers

### 4. **Colors & CSS Variables**

All colors defined as CSS custom properties in `:root` selector:

```scss
:root {
  --color-primary: #0066cc;
  --color-text: #212529;
  --color-bg-default: white;
  --color-border: #dee2e6;
  // ... and 30+ more variables
}

// Dark mode overrides
[data-theme="dark"] {
  --color-primary: #4da6ff;
  --color-text: #f0f0f0;
  --color-bg-default: #1a1a1a;
  --color-border: #404040;
}
```

**Usage in SCSS:**

```scss
.my-component {
  color: $text-color; // resolves to: var(--color-text)
  background: $bg-default; // resolves to: var(--color-bg-default)
  border: 1px solid $border-color; // etc.
}
```

**Direct CSS variable usage:**

```scss
.component {
  background-color: var(--color-bg-default);
}

// Or in JavaScript
element.style.backgroundColor = 'var(--color-primary)';
```

---

## Configuration

### Default Theme Behavior

The default behavior is "System preference" – the theme automatically detects and follows the user's OS setting.

To change the default, edit `scripts/modules/theme-toggle.js`:

```javascript
// Line 124 - setTheme('system', false)
// Change 'system' to 'light' or 'dark' for a different default
setTheme("system", false);
```

### Forcing a Theme (for testing)

Add attribute to `<html>` tag:

```html
<!-- Force light mode -->
<html data-theme="light">
  <!-- Force dark mode -->
  <html data-theme="dark">
    <!-- Auto (system preference) - default -->
    <html></html>
  </html>
</html>
```

Or via JavaScript:

```javascript
// Set to dark mode
document.documentElement.setAttribute("data-theme", "dark");

// Set to light mode
document.documentElement.setAttribute("data-theme", "light");

// Remove attribute to use system preference
document.documentElement.removeAttribute("data-theme");
```

### Toggle Button Placement

In `templates/base.twig`, change the `placement` parameter:

```twig
{# Top-right (default) #}
{% include 'components/theme-toggle.twig' with {'placement': 'top-right'} %}

{# Other options: top-left, bottom-left, bottom-right #}
```

Or change to position: static (part of page flow):

```twig
{% include 'components/theme-toggle.twig' with {'position': 'static'} %}
```

---

## JavaScript API

### Module: `scripts/modules/theme-toggle.js`

```javascript
import themeToggle from "./modules/theme-toggle.js";

// Initialize (runs automatically in main index.js)
themeToggle.init();

// Cycle through: light → dark → system → light ...
themeToggle.toggle();

// Set specific theme
themeToggle.setTheme("dark"); // 'light', 'dark', or 'system'
themeToggle.setTheme("light");

// Get current setting (what user selected)
const setting = themeToggle.getCurrent(); // 'light', 'dark', or 'system'

// Get what's actually active (accounts for system preference)
const active = themeToggle.getReflected(); // 'light' or 'dark'

// Fetch from WordPress (for logged-in users)
const pref = await themeToggle.getWordPressPreference(); // Returns setting

// Save to WordPress (for logged-in users)
await themeToggle.setWordPressPreference("dark"); // Saves to DB + localStorage
```

### Events

Listen for theme changes:

```javascript
window.addEventListener("theme-change", (event) => {
  console.log("Theme changed!");
  console.log(event.detail.theme); // The setting ('light', 'dark', 'system')
  console.log(event.detail.reflected); // What's actually active ('light' or 'dark')
});
```

### Example: Custom Theme Behavior

```javascript
import themeToggle from "./modules/theme-toggle.js";

function setupCustomTheme() {
  // Set dark mode by default (instead of system preference)
  themeToggle.setTheme("dark");

  // Listen for changes
  window.addEventListener("theme-change", (e) => {
    console.log(`Switched to ${e.detail.reflected} mode`);

    // Do something when theme changes
    updateCustomElements(e.detail.reflected);
  });
}

setupCustomTheme();
```

---

## REST API

### Get User's Theme Preference

**Endpoint:** `GET /wp-json/starter-theme/v1/theme-preference`

**No authentication required** – Returns empty preference for anonymous users.

```bash
curl https://example.com/wp-json/starter-theme/v1/theme-preference
```

**Response:**

```json
{
  "preference": "system",
  "is_user": false
}
```

**For logged-in users:**

```json
{
  "preference": "dark",
  "is_user": true
}
```

### Save User's Theme Preference

**Endpoint:** `POST /wp-json/starter-theme/v1/theme-preference`

**Authentication:** WordPress nonce required (or logged-in session)

**Request:**

```bash
curl -X POST https://example.com/wp-json/starter-theme/v1/theme-preference \
  -H "Content-Type: application/json" \
  -H "X-WP-Nonce: YOUR_NONCE" \
  -d '{"preference":"dark"}'
```

**Response:**

```json
{
  "success": true,
  "preference": "dark",
  "message": "Theme preference saved"
}
```

**JavaScript example using fetch:**

```javascript
async function saveDarkMode() {
  const response = await fetch("/wp-json/starter-theme/v1/theme-preference", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-WP-Nonce": window.themeToggleNonce, // Available globally
    },
    body: JSON.stringify({ preference: "dark" }),
  });

  const data = await response.json();
  if (data.success) {
    console.log("Saved:", data.preference);
  }
}
```

---

## Adding Colors to Dark Mode

### 1. Add to CSS Custom Properties

Edit `css/inc/base/vars.scss`:

```scss
:root {
  // Light mode
  --color-my-custom: #2196f3;
}

[data-theme="dark"] {
  // Dark mode
  --color-my-custom: #64b5f6;
}

@media (prefers-color-scheme: dark) {
  // Optional: also set in dark mode media query
  :root {
    --color-my-custom: #64b5f6;
  }
}
```

### 2. Use in SCSS

Define SCSS variable alias:

```scss
// In vars.scss
$my-custom: var(--color-my-custom);

// In components
.my-component {
  background-color: $my-custom;
}
```

### 3. Or use directly

```scss
.my-component {
  background-color: var(--color-my-custom);
}
```

---

## Styling for Dark Mode

### Using @media queries

```scss
// Light mode styles (default)
.component {
  background: white;
  color: black;
}

// Dark mode styles
@media (prefers-color-scheme: dark) {
  .component {
    background: #1a1a1a;
    color: white;
  }
}
```

### Using [data-theme] selector

```scss
// Target dark mode regardless of media query
[data-theme="dark"] .component {
  background: #1a1a1a;
}

// Target light mode (overrides system dark mode)
@media (prefers-color-scheme: dark) {
  [data-theme="light"] .component {
    background: white;
  }
}
```

### Best Practice: Use CSS Variables

```scss
// Define colors as variables
--color-component-bg: white; // Light mode
--color-component-text: black;

// Override in dark mode
[data-theme="dark"] {
  --color-component-bg: #1a1a1a;
  --color-component-text: white;
}

// Use variables consistently
.component {
  background-color: var(--color-component-bg);
  color: var(--color-component-text);
}
```

---

## Accessibility Considerations

### 1. Respects Preferences

The implementation respects:

- `prefers-color-scheme` – System dark mode preference
- `prefers-reduced-motion` – No animations for users who prefer reduced motion
- Keyboard navigation – Tab through button, Space/Enter to toggle

### 2. Icon Contrast

Theme toggle icons use `currentColor` to adopt text color, ensuring WCAG AA contrast standards.

### 3. Focus States

Button has clear focus rings for keyboard users:

```scss
&:focus-visible {
  box-shadow: 0 0 0 3px var(--color-primary), $shadow-lg;
}
```

### 4. ARIA Labels

Toggle button includes:

```html
<button
  aria-label="Toggle light and dark mode"
  aria-pressed="false"
  title="Toggle dark mode"
></button>
```

---

## Testing Dark Mode

### 1. **Manual Testing**

- **macOS**: System Preferences → General → Appearance → Dark
- **Windows 10/11**: Settings → Personalization → Colors → Dark
- **Chrome DevTools**: F12 → ESC → Rendering tab → Emulate CSS media feature prefers-color-scheme

### 2. **Automated Testing**

```javascript
// Check if dark mode is active
if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
  console.log("System is in dark mode");
}

// Listen for system theme changes
window
  .matchMedia("(prefers-color-scheme: dark)")
  .addEventListener("change", (e) => {
    console.log("System theme changed to:", e.matches ? "dark" : "light");
  });
```

### 3. **Test Scenarios**

- [ ] Light mode on light system
- [ ] Dark mode on dark system
- [ ] Light mode on dark system (manual override)
- [ ] Dark mode on light system (manual override)
- [ ] Preference persists on page reload
- [ ] Toggle button works across all pages
- [ ] Print preview shows light mode
- [ ] Colors have sufficient contrast (WCAG AA)
- [ ] No layout shifts when switching themes
- [ ] Keyboard navigation works (Tab, Space/Enter)

---

## Troubleshooting

### Theme Not Applying

**Issue:** Page loads with wrong theme color flash

**Solution:** Check that inline script runs early in `<head>`:

- Verify `theme-toggle-options.php` is included in `functions.php`
- Check browser DevTools → Network → blocked scripts
- Clear browser cache and localStorage

### CSS Variables Not Working

**Issue:** Colors not changing in certain browser

**Troubleshoot:**

```javascript
// Check if CSS variables supported
if (CSS && CSS.supports && CSS.supports('--test', '0')) {
  console.log('CSS variables supported');
}

// Try using fallback
color: #333; // Fallback
color: var(--color-text, #333); // CSS variable with fallback
```

### localStorage Errors

**Issue:** "QuotaExceededError" when saving preference

**Solution:**

- User might have disabled localStorage
- Browser storage full
- Check private/incognito mode (may have restrictions)

Fallback handled automatically – theme still works without localStorage.

### System Preference Not Detected

**Issue:** Dark mode button doesn't appear or doesn't work

**Verify:**

```javascript
// Check media query support
const darkRequest = window.matchMedia("(prefers-color-scheme: dark)");
console.log("Dark mode detected:", darkRequest.matches);

// Check browser support
console.log("Browser:", navigator.userAgent);
```

**Supported browsers:** Chrome 76+, Firefox 67+, Safari 12.1+, Edge 79+

---

## Files Overview

| File                                     | Purpose                                       |
| ---------------------------------------- | --------------------------------------------- |
| `css/inc/base/vars.scss`                 | CSS custom properties (light mode defaults)   |
| `css/inc/global/dark-mode.scss`          | Dark mode color overrides, media queries      |
| `templates/components/theme-toggle.twig` | Toggle button HTML (sun, moon, monitor icons) |
| `scripts/modules/theme-toggle.js`        | Toggle logic, localStorage, system detection  |
| `css/inc/components/theme-toggle.scss`   | Toggle button styling and animations          |
| `src/theme-toggle-options.php`           | WordPress REST API, options, nonce support    |

---

## Performance

- **Bundle size:** +2.29 kB (0.65 kB gzipped)
- **Build time:** <200 ms
- **Runtime:** Inline script runs in <1ms (no perceived delay)
- **No external dependencies:** Pure JavaScript, CSS, PHP

---

## Future Enhancements

Potential improvements for future versions:

- [ ] Admin settings page to configure default theme
- [ ] Per-page theme override
- [ ] Custom color palette editor
- [ ] Theme export/import
- [ ] High contrast mode support
- [ ] Automatic theme based on time of day
- [ ] Theme preference in user profile (non-admin)

---

## Resources

- [MDN: prefers-color-scheme](https://developer.mozilla.org/en-US/docs/Web/CSS/@media/prefers-color-scheme)
- [Web Fundamentals: Dark Mode](https://web.dev/prefers-color-scheme/)
- [WCAG Dark Mode Guidance](https://www.w3.org/WAI/WCAG21/Understanding/use-of-color)
- [CSS Variables (Custom Properties)](https://developer.mozilla.org/en-US/docs/Web/CSS/--*)
