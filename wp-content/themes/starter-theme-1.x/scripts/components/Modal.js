/**
 * Modal Component
 *
 * Wrapper around Bootstrap Modal with custom enhancements.
 * Handles Bootstrap modal with optional custom callbacks.
 *
 * @module components/Modal
 */

import {
  querySelector,
  addClass,
  removeClass,
  setAttribute,
  removeAttribute,
} from "../utils/dom.js";

/**
 * Modal class for managing Bootstrap modals with custom logic
 *
 * @example
 * // In Twig:
 * <div class="modal fade" id="myModal" data-js-component="modal">
 *     <div class="modal-dialog">
 *         <div class="modal-content">...</div>
 *     </div>
 * </div>
 *
 * // Auto-initialized on page load
 * // Or manually:
 * const modal = new Modal(document.getElementById('myModal'));
 * modal.open();
 *
 * @class Modal
 */
export class Modal {
  /**
   * Initialize modal
   * @param {Element} element - Modal element
   * @param {Object} options - Configuration options
   */
  constructor(element, options = {}) {
    this.element = element;
    this.options = {
      backdrop: "static",
      keyboard: true,
      ...options,
    };

    // Check if Bootstrap is available
    if (typeof bootstrap === "undefined") {
      console.warn("[Modal] Bootstrap JS not loaded");
      return;
    }

    // Create Bootstrap modal instance
    this.bootstrap = new bootstrap.Modal(this.element, this.options);

    // Setup event listeners
    this.setupListeners();
  }

  /**
   * Setup modal event listeners
   */
  setupListeners() {
    // Bootstrap modal events
    this.element.addEventListener("show.bs.modal", (e) => {
      this.onBeforeOpen(e);
    });

    this.element.addEventListener("shown.bs.modal", (e) => {
      this.onAfterOpen(e);
    });

    this.element.addEventListener("hide.bs.modal", (e) => {
      this.onBeforeClose(e);
    });

    this.element.addEventListener("hidden.bs.modal", (e) => {
      this.onAfterClose(e);
    });

    // Setup close button
    const closeBtn = querySelector('[data-bs-dismiss="modal"]', this.element);
    if (closeBtn) {
      closeBtn.addEventListener("click", () => this.close());
    }

    // Close on backdrop click if not static
    if (this.options.backdrop !== "static") {
      this.element.addEventListener("click", (e) => {
        if (e.target === this.element) {
          this.close();
        }
      });
    }
  }

  /**
   * Open the modal
   * Emits 'modal:open' custom event
   */
  open() {
    if (this.bootstrap) {
      this.bootstrap.show();
    }
  }

  /**
   * Close the modal
   * Emits 'modal:close' custom event
   */
  close() {
    if (this.bootstrap) {
      this.bootstrap.hide();
    }
  }

  /**
   * Toggle modal open/closed
   */
  toggle() {
    if (this.bootstrap) {
      this.bootstrap.toggle();
    }
  }

  /**
   * Check if modal is visible
   * @returns {boolean}
   */
  isOpen() {
    return this.element.classList.contains("show");
  }

  /**
   * Called before modal opens
   * Override to add custom logic
   *
   * @param {Event} event - Bootstrap event
   */
  onBeforeOpen(event) {
    // Custom hook - override in subclass
  }

  /**
   * Called after modal opens
   * Override to add custom logic
   *
   * @param {Event} event - Bootstrap event
   */
  onAfterOpen(event) {
    // Custom hook - override in subclass
    // Auto-focus first input
    const firstInput = querySelector("input, textarea", this.element);
    if (firstInput) {
      firstInput.focus();
    }
  }

  /**
   * Called before modal closes
   * Override to add custom logic
   *
   * @param {Event} event - Bootstrap event
   */
  onBeforeClose(event) {
    // Custom hook - override in subclass
  }

  /**
   * Called after modal closes
   * Override to add custom logic
   *
   * @param {Event} event - Bootstrap event
   */
  onAfterClose(event) {
    // Custom hook - override in subclass
  }

  /**
   * Destroy modal instance and cleanup
   */
  destroy() {
    if (this.bootstrap) {
      this.bootstrap.dispose();
      this.bootstrap = null;
    }
  }
}
