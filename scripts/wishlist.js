/**
 * Wishlist Module
 *
 * Handles user interactions with wishlist:
 * - Add/remove buttons on products
 * - Wishlist page functionality
 * - Toast notifications
 * - Heart icon toggling
 *
 * @package StarterTheme
 */

(function () {
  "use strict";

  const Wishlist = {
    // Configuration
    config: {
      get ajaxUrl() {
        return typeof themeWishlist !== "undefined"
          ? themeWishlist.ajax_url
          : "";
      },
      get nonce() {
        return typeof themeWishlist !== "undefined" ? themeWishlist.nonce : "";
      },
    },

    /**
     * Initialize wishlist functionality
     */
    init() {
      this.bindEvents();
      this.updateWishlistButtons();
    },

    /**
     * Bind event listeners
     */
    bindEvents() {
      const self = this;

      // Add to wishlist button click
      document.addEventListener("click", function (e) {
        const btn = e.target.closest(".wishlist-btn, .add-to-wishlist-btn");
        if (btn) {
          e.preventDefault();
          const productId =
            btn.dataset.product_id || btn.getAttribute("data-product-id");
          self.toggleWishlist(productId, btn);
        }
      });

      // Remove from wishlist (on wishlist page)
      document.addEventListener("click", function (e) {
        const btn = e.target.closest(".remove-from-wishlist-btn");
        if (btn) {
          e.preventDefault();
          const productId =
            btn.dataset.product_id || btn.getAttribute("data-product-id");
          self.removeFromWishlist(productId, btn);
        }
      });

      // Move to cart from wishlist
      document.addEventListener("click", function (e) {
        const btn = e.target.closest(".move-to-cart-btn");
        if (btn) {
          e.preventDefault();
          const productId =
            btn.dataset.product_id || btn.getAttribute("data-product-id");
          self.moveToCart(productId, btn);
        }
      });
    },

    /**
     * Toggle product in wishlist
     *
     * @param {number} productId Product ID
     * @param {element} button Button element
     */
    toggleWishlist(productId, button) {
      const self = this;
      const isInWishlist = button.classList.contains("in-wishlist");

      if (isInWishlist) {
        this.removeFromWishlist(productId, button);
      } else {
        this.addToWishlist(productId, button);
      }
    },

    /**
     * Add product to wishlist
     *
     * @param {number} productId Product ID
     * @param {element} button Button element
     */
    addToWishlist(productId, button) {
      const self = this;

      fetch(this.config.ajaxUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
          action: "theme_add_to_wishlist",
          nonce: this.config.nonce,
          product_id: productId,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            button.classList.add("in-wishlist");
            button.setAttribute("aria-pressed", "true");
            button.setAttribute("title", "Remove from wishlist");

            this.showNotification(data.data.message, "success", data.data.url);
            this.updateWishlistCount(data.data.count);
          } else {
            this.showNotification(data.data.message, "error");
          }
        })
        .catch((error) => {
          console.error("Wishlist error:", error);
          this.showNotification("Error adding to wishlist", "error");
        });
    },

    /**
     * Remove product from wishlist
     *
     * @param {number} productId Product ID
     * @param {element} button Button element
     */
    removeFromWishlist(productId, button) {
      const self = this;

      fetch(this.config.ajaxUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
          action: "theme_remove_from_wishlist",
          nonce: this.config.nonce,
          product_id: productId,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            button.classList.remove("in-wishlist");
            button.setAttribute("aria-pressed", "false");
            button.setAttribute("title", "Add to wishlist");

            this.showNotification(data.data.message, "success");
            this.updateWishlistCount(data.data.count);

            // If on wishlist page, reload it to reflect changes
            if (window.location.pathname.includes("/wishlist")) {
              setTimeout(() => window.location.reload(), 500);
            } else {
              // On product pages, animate out the button container
              const productRow = button.closest(".wishlist-item");
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
          console.error("Wishlist error:", error);
          this.showNotification("Error removing from wishlist", "error");
        });
    },

    /**
     * Move product to cart from wishlist
     *
     * @param {number} productId Product ID
     * @param {element} button Button element
     */
    moveToCart(productId, button) {
      // Add to cart
      fetch(
        "https://" + window.location.host + "/wp-json/wc/store/cart/add-item",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            id: productId,
            quantity: 1,
          }),
        },
      )
        .then((response) => response.json())
        .then((data) => {
          if (data.success || data !== null) {
            // Remove from wishlist
            this.removeFromWishlist(productId, button);
            this.showNotification("Moved to cart", "success");
          }
        })
        .catch((error) => {
          console.error("Cart error:", error);
          this.showNotification("Error moving to cart", "error");
        });
    },

    /**
     * Update wishlist button states based on current wishlist
     */
    updateWishlistButtons() {
      const self = this;

      fetch(this.config.ajaxUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
          action: "theme_get_wishlist",
          nonce: this.config.nonce,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success && data.data.products) {
            const productIds = data.data.products.map((p) => p.product_id);

            // Update all wishlist buttons
            document
              .querySelectorAll(".wishlist-btn, .add-to-wishlist-btn")
              .forEach((btn) => {
                const productId =
                  btn.dataset.product_id || btn.getAttribute("data-product-id");

                if (productIds.includes(parseInt(productId))) {
                  btn.classList.add("in-wishlist");
                  btn.setAttribute("aria-pressed", "true");
                  btn.setAttribute("title", "Remove from wishlist");
                }
              });

            self.updateWishlistCount(data.data.count);
          }
        })
        .catch((error) => console.error("Wishlist fetch error:", error));
    },

    /**
     * Update wishlist count display
     *
     * @param {number} count Wishlist item count
     */
    updateWishlistCount(count) {
      const countElements = document.querySelectorAll(".wishlist-count");
      countElements.forEach((el) => {
        el.textContent = count;
        el.classList.toggle("hidden", count === 0);
      });
    },

    /**
     * Show toast notification
     *
     * @param {string} message Message to display
     * @param {string} type Notification type (success, error, info)
     * @param {string} url Optional URL to link to
     */
    showNotification(message, type = "info", url = "") {
      const toast = document.createElement("div");
      toast.className = "wishlist-toast " + type;
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

      // Trigger animation
      setTimeout(() => toast.classList.add("show"), 10);

      // Remove after 3 seconds
      setTimeout(() => {
        toast.classList.remove("show");
        setTimeout(() => toast.remove(), 300);
      }, 3000);
    },
  };

  // Initialize when DOM is ready
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => Wishlist.init());
  } else {
    Wishlist.init();
  }

  // Export for use in other modules
  window.themeWishlistModule = Wishlist;
})();
