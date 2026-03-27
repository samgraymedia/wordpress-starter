/**
 * Product Comparison Module
 *
 * Handles comparison interactions:
 * - Add/remove products to comparison
 * - Comparison table rendering
 * - Toast notifications
 * - State management
 *
 * @package StarterTheme
 */

(function () {
  "use strict";

  const Comparison = {
    config: {
      get ajaxUrl() {
        return typeof themeComparison !== "undefined"
          ? themeComparison.ajax_url
          : "";
      },
      get nonce() {
        return typeof themeComparison !== "undefined"
          ? themeComparison.nonce
          : "";
      },
      maxItems: 5,
    },

    /**
     * Initialize comparison
     */
    init() {
      this.bindEvents();
      this.updateComparisonButtons();
    },

    /**
     * Bind event listeners
     */
    bindEvents() {
      const self = this;

      document.addEventListener("click", function (e) {
        const btn = e.target.closest(".comparison-btn, .add-to-comparison-btn");
        if (btn) {
          e.preventDefault();
          const productId =
            btn.dataset.product_id || btn.getAttribute("data-product-id");
          self.toggleComparison(productId, btn);
        }
      });

      document.addEventListener("click", function (e) {
        const btn = e.target.closest(".remove-from-comparison-btn");
        if (btn) {
          e.preventDefault();
          const productId =
            btn.dataset.product_id || btn.getAttribute("data-product-id");
          self.removeFromComparison(productId, btn);
        }
      });

      document.addEventListener("click", function (e) {
        const btn = e.target.closest("#clear-comparison-btn");
        if (btn) {
          e.preventDefault();
          self.clearComparison();
        }
      });
    },

    /**
     * Toggle product in comparison
     *
     * @param {number} productId Product ID
     * @param {element} button Button element
     */
    toggleComparison(productId, button) {
      const isInComparison = button.classList.contains("in-comparison");

      if (isInComparison) {
        this.removeFromComparison(productId, button);
      } else {
        this.addToComparison(productId, button);
      }
    },

    /**
     * Add product to comparison
     *
     * @param {number} productId Product ID
     * @param {element} button Button element
     */
    addToComparison(productId, button) {
      const self = this;

      fetch(this.config.ajaxUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
          action: "theme_add_to_comparison",
          nonce: this.config.nonce,
          product_id: productId,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            button.classList.add("in-comparison");
            button.setAttribute("aria-pressed", "true");
            button.setAttribute("title", "Remove from comparison");

            this.showNotification(data.data.message, "success", data.data.url);
            this.updateComparisonCount(data.data.count);
          } else {
            this.showNotification(data.data.message, "error");
          }
        })
        .catch((error) => {
          console.error("Comparison error:", error);
          this.showNotification("Error adding to comparison", "error");
        });
    },

    /**
     * Remove product from comparison
     *
     * @param {number} productId Product ID
     * @param {element} button Button element
     */
    removeFromComparison(productId, button) {
      const self = this;

      fetch(this.config.ajaxUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
          action: "theme_remove_from_comparison",
          nonce: this.config.nonce,
          product_id: productId,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            button.classList.remove("in-comparison");
            button.setAttribute("aria-pressed", "false");
            button.setAttribute("title", "Add to comparison");

            this.showNotification(data.data.message, "success");
            this.updateComparisonCount(data.data.count);

            // If on comparison page, reload it to reflect changes
            if (window.location.pathname.includes("/comparison")) {
              setTimeout(() => window.location.reload(), 500);
            } else {
              // On product pages, animate out the button container
              const productRow = button.closest(".comparison-item");
              if (productRow) {
                productRow.style.opacity = "0";
                setTimeout(() => productRow.remove(), 300);
              }
            }
          } else {
            this.showNotification(data.data.message, "error");
          }
        })
        .catch((error) => {
          console.error("Comparison error:", error);
          this.showNotification("Error removing from comparison", "error");
        });
    },

    /**
     * Clear comparison
     */
    clearComparison() {
      const self = this;

      fetch(this.config.ajaxUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
          action: "theme_clear_comparison",
          nonce: this.config.nonce,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            window.location.reload();
          }
        })
        .catch((error) => {
          console.error("Comparison error:", error);
          this.showNotification("Error clearing comparison", "error");
        });
    },

    /**
     * Update comparison button states
     */
    updateComparisonButtons() {
      const self = this;

      fetch(this.config.ajaxUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
          action: "theme_get_comparison",
          nonce: this.config.nonce,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success && data.data.products) {
            const productIds = data.data.products.map((p) => p.product_id);

            document
              .querySelectorAll(".comparison-btn, .add-to-comparison-btn")
              .forEach((btn) => {
                const productId =
                  btn.dataset.product_id || btn.getAttribute("data-product-id");

                if (productIds.includes(parseInt(productId))) {
                  btn.classList.add("in-comparison");
                  btn.setAttribute("aria-pressed", "true");
                  btn.setAttribute("title", "Remove from comparison");
                }
              });

            self.updateComparisonCount(data.data.count);
          }
        })
        .catch((error) => console.error("Comparison fetch error:", error));
    },

    /**
     * Update comparison count
     *
     * @param {number} count Count
     */
    updateComparisonCount(count) {
      const countElements = document.querySelectorAll(".comparison-count");
      countElements.forEach((el) => {
        el.textContent = count;
        el.classList.toggle("hidden", count === 0);
      });
    },

    /**
     * Show toast notification
     *
     * @param {string} message Message
     * @param {string} type Type (success, error, info)
     * @param {string} url Optional URL to link to
     */
    showNotification(message, type = "info", url = "") {
      const toast = document.createElement("div");
      toast.className = "comparison-toast " + type;
      toast.setAttribute("role", "status");
      toast.setAttribute("aria-live", "polite");

      if (url) {
        const link = document.createElement("a");
        link.href = url;
        link.textContent = message;
        link.className = "toast-link";
        toast.appendChild(link);
      } else {
        toast.textContent = message;
      }

      document.body.appendChild(toast);

      setTimeout(() => toast.classList.add("show"), 10);

      setTimeout(() => {
        toast.classList.remove("show");
        setTimeout(() => toast.remove(), 300);
      }, 3000);
    },
  };

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => Comparison.init());
  } else {
    Comparison.init();
  }

  window.themeComparisonModule = Comparison;
})();
