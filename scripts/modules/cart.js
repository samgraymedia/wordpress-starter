/**
 * Cart Module
 *
 * Handles WooCommerce shopping cart functionality.
 * Only loads if WooCommerce is active on the site.
 *
 * @module modules/cart
 */

import { querySelector, querySelectorAll } from "../utils/dom.js";
import { ajax } from "../utils/http.js";
import Logger from "../utils/logger.js";

/**
 * Initialize cart functionality
 */
export function initCart() {
  // Check if WooCommerce is active
  if (typeof wc === "undefined") {
    Logger.debug("[Cart] WooCommerce not active");
    return;
  }

  Logger.debug("[Cart] Initializing...");

  // Setup add to cart button handlers
  setupAddToCartButtons();

  // Setup cart page interactions
  setupCartPage();

  // Setup checkout interactions
  setupCheckout();
}

/**
 * Setup add to cart button handlers
 */
function setupAddToCartButtons() {
  querySelectorAll("[data-product-id]").forEach((button) => {
    button.addEventListener("click", handleAddToCart);
  });
}

/**
 * Handle add to cart click
 * @param {Event} e
 */
async function handleAddToCart(e) {
  const button = e.target.closest("[data-product-id]");
  if (!button) return;

  const productId = button.dataset.productId;
  const quantity = parseInt(button.dataset.quantity || 1);

  try {
    // Show loading state
    button.disabled = true;
    button.textContent = "Adding...";

    // Add to cart via AJAX
    const response = await ajax("wc_add_to_cart", {
      product_id: productId,
      quantity: quantity,
    });

    // Show success
    button.textContent = "Added ✓";
    setTimeout(() => {
      button.disabled = false;
      button.textContent = "Add to Cart";
    }, 2000);
  } catch (error) {
    Logger.error("[Cart] Error adding to cart", error, {
      productId,
      quantity,
      context: "add-to-cart",
    });
    button.disabled = false;
    button.textContent = "Add to Cart";
  }
}

/**
 * Setup cart page interactions
 */
function setupCartPage() {
  if (!document.querySelector(".woocommerce-cart-form")) {
    return;
  }

  // Handle quantity changes
  const quantityInputs = querySelectorAll('input[name*="cart"]');
  quantityInputs.forEach((input) => {
    input.addEventListener("change", () => {
      // Cart form will auto-submit or trigger update
    });
  });

  // Handle remove from cart
  querySelectorAll(".woocommerce-cart-table a.remove").forEach((link) => {
    link.addEventListener("click", (e) => {
      // Confirm removal
      if (!confirm("Remove this item from cart?")) {
        e.preventDefault();
      }
    });
  });
}

/**
 * Setup checkout interactions
 */
function setupCheckout() {
  if (!document.querySelector(".woocommerce-checkout-form")) {
    return;
  }

  // Handle form submission
  const form = querySelector("form.checkout");
  if (form) {
    form.addEventListener("submit", (e) => {
      // Any pre-submission validation can go here
    });
  }
}

/**
 * Update cart counter in header
 * Call this after cart changes
 * @param {number} count
 */
export function updateCartCounter(count) {
  const counter = querySelector("[data-cart-count]");
  if (counter) {
    counter.textContent = count;
  }
}

/**
 * Get current cart total
 * @returns {string}
 */
export function getCartTotal() {
  const total = querySelector(
    ".woocommerce-cart-total .woocommerce-Price-amount",
  );
  return total ? total.textContent : "";
}
