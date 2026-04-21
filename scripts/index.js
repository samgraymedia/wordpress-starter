/**
 * Theme Main Entry Point
 *
 * This is the primary JavaScript file for the Starter Theme.
 * All component initialization and module setup happens here.
 *
 * @module index
 */

// Primitive console.log that cannot fail
window.addEventListener("DOMContentLoaded", () => {
  console.log("[SITE] DOMContentLoaded fired - checking script status");
});

// Check readyState immediately
console.log("[SITE] Site.js module loaded, readyState:", document.readyState);

// Ensure themeConfig exists before imports (to support early Logger initialization)
if (typeof window !== "undefined" && !window.themeConfig) {
  window.themeConfig = {
    siteUrl: "/",
    ajaxUrl: "/wp-admin/admin-ajax.php",
    nonce: "",
    isLoggedIn: false,
    currentUser: 0,
    debug: true, // Default to true for development; WordPress overrides with WP_DEBUG
  };
}

// Import utilities
import { querySelectorAll, addClass, removeClass } from "./utils/dom.js";
import { throttle, debounce } from "./utils/events.js";
import { initLazyLoading } from "./utils/lazy-loading.js";
import Logger from "./utils/logger.js";
import ErrorReporter from "./utils/error-reporter.js";

// Import components
import { Modal } from "./components/Modal.js";
import { Dropdown } from "./components/Dropdown.js";

// Import modules
import { initCart } from "./modules/cart.js";
import { initCheckout } from "./modules/checkout.js";
import { initSearch } from "./modules/search.js";
import { initForms } from "./modules/forms.js";
import { initCookieConsent } from "./modules/cookie-consent.js";
import themeToggle from "./modules/theme-toggle.js";

// Import feature scripts (side-effect modules that auto-initialize)
import "./gallery.js";
import "./product-gallery.js";
import "./slider.js";
import "./video.js";
import "./accordion.js";
import "./announcement.js";
import "./share-buttons.js";
import "./tabs.js";
import "./hamburger.js";
import "./expertise-scroll.js";
import "./features.js";
import "./hero.js";
import "./stats.js";

/**
 * Initialize all theme components and modules
 * Called when DOM is ready
 */
function initTheme() {
  console.log("[SITE] initTheme() executing...");

  try {
    // Initialize error logging system first (before anything else that might error)
    console.log("[SITE] Getting ErrorReporter...", typeof ErrorReporter);
    const errorReporter = ErrorReporter;

    console.log("[SITE] Getting Logger...", typeof Logger);
    Logger.setErrorReporter(errorReporter);
    Logger.debug("[Theme] Logger initialized");

    Logger.info("[Theme] Initializing components...");

    // Initialize lazy loading early
    Logger.debug("[Theme] Initializing lazy loading...");
    initLazyLoading({
      rootMargin: "50px",
      threshold: 0.01,
    });
    Logger.debug("[Theme] Lazy loading initialized");

    // Initialize Bootstrap components that might be on the page
    Logger.debug("[Theme] Initializing Bootstrap components...");
    initBootstrapComponents();
    Logger.debug("[Theme] Bootstrap components initialized");

    // Initialize custom components
    Logger.debug("[Theme] Initializing Modal components...");
    initModalComponents();
    Logger.debug("[Theme] Modal components initialized");

    Logger.debug("[Theme] Initializing Dropdown components...");
    initDropdownComponents();
    Logger.debug("[Theme] Dropdown components initialized");

    // Initialize feature modules
    Logger.debug("[Theme] Initializing Cart module...");
    initCart();
    Logger.debug("[Theme] Cart module initialized");

    Logger.debug("[Theme] Initializing Checkout module...");
    initCheckout();
    Logger.debug("[Theme] Checkout module initialized");

    Logger.debug("[Theme] Initializing Search module...");
    initSearch();
    Logger.debug("[Theme] Search module initialized");

    Logger.debug("[Theme] Initializing Forms module...");
    initForms();
    Logger.debug("[Theme] Forms module initialized");

    Logger.debug("[Theme] Initializing Cookie Consent module...");
    initCookieConsent();
    Logger.debug("[Theme] Cookie Consent module initialized");

    // Initialize theme toggle (must run early for proper theme application)
    Logger.debug("[Theme] Initializing Theme Toggle...");
    themeToggle.init();
    Logger.debug("[Theme] Theme Toggle initialized");

    // Setup theme toggle button click handler
    Logger.debug("[Theme] Setting up Theme Toggle UI...");
    setupThemeToggle();
    Logger.debug("[Theme] Theme Toggle UI setup complete");

    // Add any global event listeners
    Logger.debug("[Theme] Setting up global event listeners...");
    setupGlobalListeners();
    Logger.debug("[Theme] Global event listeners setup complete");

    Logger.info("[Theme] Initialization complete");
  } catch (error) {
    console.error("[SITE] Fatal error in initTheme():", error);
    console.error("[SITE] Stack:", error.stack);
  }
}

/**
 * Initialize Bootstrap components
 * Bootstrap is available globally from the enqueued bundle
 * @see https://getbootstrap.com/docs/5.3/
 */
function initBootstrapComponents() {
  if (typeof bootstrap === "undefined") {
    Logger.warn("[Theme] Bootstrap not loaded");
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
