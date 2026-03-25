/**
 * Dropdown Component
 *
 * Wrapper around Bootstrap Dropdown with custom enhancements.
 *
 * @module components/Dropdown
 */

import { querySelector, addClass, removeClass } from "../utils/dom.js";
import Logger from "../utils/logger.js";

/**
 * Dropdown class for managing Bootstrap dropdowns with custom logic
 *
 * @example
 * // In Twig:
 * <div class="dropdown" data-js-component="dropdown">
 *     <button class="dropdown-toggle" data-bs-toggle="dropdown">Menu</button>
 *     <ul class="dropdown-menu">
 *         <li><a class="dropdown-item" href="#">Item 1</a></li>
 *     </ul>
 * </div>
 *
 * // Auto-initialized on page load
 * // Or manually:
 * const dropdown = new Dropdown(element);
 * dropdown.open();
 *
 * @class Dropdown
 */
export class Dropdown {
  /**
   * Initialize dropdown
   * @param {Element} element - Dropdown container element
   * @param {Object} options - Configuration options
   */
  constructor(element, options = {}) {
    this.element = element;
    this.options = {
      autoClose: true,
      ...options,
    };

    Logger.debug("[Dropdown] Initializing");

    // Check if Bootstrap is available
    if (typeof bootstrap === "undefined") {
      Logger.warn("[Dropdown] Bootstrap JS not loaded");
      return;
    }

    // Find the toggle button
    this.toggle = querySelector('[data-bs-toggle="dropdown"]', this.element);
    if (!this.toggle) {
      Logger.warn("[Dropdown] No toggle button found");
      return;
    }

    // Create Bootstrap dropdown instance
    this.bootstrap = new bootstrap.Dropdown(this.toggle, this.options);

    // Setup event listeners
    this.setupListeners();
  }

  /**
   * Setup dropdown event listeners
   */
  setupListeners() {
    Logger.debug("[Dropdown] Setting up event listeners");

    if (!this.toggle) return;

    // Bootstrap dropdown events
    this.toggle.addEventListener("show.bs.dropdown", (e) => {
      Logger.debug("[Dropdown] Opening");
      this.onBeforeOpen(e);
    });

    this.toggle.addEventListener("shown.bs.dropdown", (e) => {
      Logger.debug("[Dropdown] Opened");
      this.onAfterOpen(e);
    });

    this.toggle.addEventListener("hide.bs.dropdown", (e) => {
      Logger.debug("[Dropdown] Closing");
      this.onBeforeClose(e);
    });

    this.toggle.addEventListener("hidden.bs.dropdown", (e) => {
      this.onAfterClose(e);
    });
  }

  /**
   * Open the dropdown
   */
  open() {
    if (this.bootstrap) {
      this.bootstrap.show();
    }
  }

  /**
   * Close the dropdown
   */
  close() {
    if (this.bootstrap) {
      this.bootstrap.hide();
    }
  }

  /**
   * Toggle dropdown open/closed
   */
  toggle() {
    if (this.bootstrap) {
      this.bootstrap.toggle();
    }
  }

  /**
   * Check if dropdown is visible
   * @returns {boolean}
   */
  isOpen() {
    return this.toggle?.classList.contains("show") || false;
  }

  /**
   * Called before dropdown opens
   * Override to add custom logic
   *
   * @param {Event} event - Bootstrap event
   */
  onBeforeOpen(event) {
    // Custom hook - override in subclass
  }

  /**
   * Called after dropdown opens
   * Override to add custom logic
   *
   * @param {Event} event - Bootstrap event
   */
  onAfterOpen(event) {
    // Custom hook - override in subclass
  }

  /**
   * Called before dropdown closes
   * Override to add custom logic
   *
   * @param {Event} event - Bootstrap event
   */
  onBeforeClose(event) {
    // Custom hook - override in subclass
  }

  /**
   * Called after dropdown closes
   * Override to add custom logic
   *
   * @param {Event} event - Bootstrap event
   */
  onAfterClose(event) {
    // Custom hook - override in subclass
  }

  /**
   * Destroy dropdown instance and cleanup
   */
  destroy() {
    if (this.bootstrap) {
      this.bootstrap.dispose();
      this.bootstrap = null;
    }
  }
}
