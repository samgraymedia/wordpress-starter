/**
 * Theme Main Entry Point
 *
 * This is the primary JavaScript file for the Starter Theme.
 * All component initialization and module setup happens here.
 *
 * @module index
 */

// Import utilities
import { querySelectorAll, addClass, removeClass } from "./utils/dom.js";
import { throttle, debounce } from "./utils/events.js";

// Import components
import { Modal } from "./components/Modal.js";
import { Dropdown } from "./components/Dropdown.js";

// Import modules
import { initCart } from "./modules/cart.js";
import { initSearch } from "./modules/search.js";
import { initForms } from "./modules/forms.js";
import { initCookieConsent } from "./modules/cookie-consent.js";
import themeToggle from "./modules/theme-toggle.js";

/**
 * Initialize all theme components and modules
 * Called when DOM is ready
 */
function initTheme() {
  console.log("[Theme] Initializing components...");

  // Initialize Bootstrap components that might be on the page
  initBootstrapComponents();

  // Initialize custom components
  initModalComponents();
  initDropdownComponents();

  // Initialize feature modules
  initCart();
  initSearch();
  initForms();
  initCookieConsent();

  // Initialize theme toggle (must run early for proper theme application)
  themeToggle.init();

  // Setup theme toggle button click handler
  setupThemeToggle();

  // Add any global event listeners
  setupGlobalListeners();

  console.log("[Theme] Initialization complete");
}

/**
 * Initialize Bootstrap components
 * Bootstrap is available globally from the enqueued bundle
 * @see https://getbootstrap.com/docs/5.3/
 */
function initBootstrapComponents() {
  if (typeof bootstrap === "undefined") {
    console.warn("[Theme] Bootstrap not loaded");
    return;
  }

  // Document tooltips
  document
    .querySelectorAll('[data-bs-toggle="tooltip"]')
    .forEach((tooltipTriggerEl) => {
      new bootstrap.Tooltip(tooltipTriggerEl);
    });

  // Document popovers
  document
    .querySelectorAll('[data-bs-toggle="popover"]')
    .forEach((popoverTriggerEl) => {
      new bootstrap.Popover(popoverTriggerEl);
    });
}

/**
 * Initialize custom modal components
 * Finds elements with data-js-component="modal"
 */
function initModalComponents() {
  querySelectorAll('[data-js-component="modal"]').forEach((element) => {
    const modal = new Modal(element);
    // Store instance on element for external access
    element.__modal = modal;
  });
}

/**
 * Initialize custom dropdown components
 * Finds elements with data-js-component="dropdown"
 */
function initDropdownComponents() {
  querySelectorAll('[data-js-component="dropdown"]').forEach((element) => {
    const dropdown = new Dropdown(element);
    element.__dropdown = dropdown;
  });
}

/**
 * Setup global event listeners
 */
function setupGlobalListeners() {
  // Example: smooth scroll for anchor links
  document.addEventListener("click", (e) => {
    if (
      e.target.matches('a[href^="#"]') &&
      e.target.getAttribute("href") !== "#"
    ) {
      const target = document.querySelector(e.target.getAttribute("href"));
      if (target) {
        target.scrollIntoView({ behavior: "smooth" });
        e.preventDefault();
      }
    }
  });

  // Handle window resize with throttle
  window.addEventListener("resize", throttle(handleWindowResize, 250));
}

/**
 * Setup theme toggle button
 */
function setupThemeToggle() {
  const toggleBtn = document.getElementById("theme-toggle-btn");
  if (!toggleBtn) {
    return;
  }

  // Click handler
  toggleBtn.addEventListener("click", () => {
    themeToggle.toggle();
    updateThemeToggleUI();
  });

  // Listen for theme changes (from other tabs, external changes, etc.)
  window.addEventListener("theme-change", () => {
    updateThemeToggleUI();
  });

  // Initial UI update
  updateThemeToggleUI();
}

/**
 * Update theme toggle button UI based on current theme
 */
function updateThemeToggleUI() {
  const toggleBtn = document.getElementById("theme-toggle-btn");
  if (!toggleBtn) {
    return;
  }

  const current = themeToggle.getCurrent();
  const reflected = themeToggle.getReflected();

  // Update aria-label based on next theme
  const labels = {
    light: "Switch to dark mode",
    dark: "Switch to system preference",
    system: "Switch to light mode",
  };

  toggleBtn.setAttribute("aria-label", labels[current] || labels.system);

  // Update data attribute (for CSS to show correct icon)
  if (current === "system") {
    document.documentElement.removeAttribute("data-current-theme");
  } else {
    document.documentElement.setAttribute("data-current-theme", current);
  }
}

/**
 * Handle window resize events
 */
function handleWindowResize() {
  // Add responsive behavior here if needed
}

// Initialize when DOM is ready
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initTheme);
} else {
  initTheme();
}

// Export for external use if needed
export { initTheme, Modal, Dropdown };
