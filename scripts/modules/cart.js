/**
 * Cart Module
 *
 * Handles WooCommerce shopping cart functionality with AJAX updates,
 * persistent storage, and interactive quantity adjustment.
 * Only loads if WooCommerce is active on the site.
 *
 * Features:
 * - AJAX cart updates without page reload
 * - Real-time subtotal calculation
 * - Quantity adjustment with +/- buttons
 * - Remove item confirmation modal
 * - Cart persistence with localStorage backup
 *
 * @module modules/cart
 */

import {
  querySelector,
  querySelectorAll,
  addClass,
  removeClass,
} from "../utils/dom.js";
import Logger from "../utils/logger.js";
import { Modal } from "../components/Modal.js";

let removeConfirmationModal = null;
let currentRemoveData = null;

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

  // Setup cart page interactions (on cart page)
  setupCartPageInteractions();

  // Setup checkout interactions
  setupCheckout();

  // Initialize modal for remove confirmation
  initializeRemoveConfirmationModal();
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
 * Handle add to cart click with AJAX
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
    const originalText = button.textContent;
    button.textContent = "Adding...";

    // Add to cart via AJAX
    const response = await fetch(wc.site_url, {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
        "X-Requested-With": "XMLHttpRequest",
      },
      body: new URLSearchParams({
        action: "wc_add_to_cart",
        product_id: productId,
        quantity: quantity,
        nonce: wc.nonce || "",
      }),
    });

    if (response.ok) {
      // Show success
      button.textContent = "✓ Added";
      updateCartCounter();

      setTimeout(() => {
        button.disabled = false;
        button.textContent = originalText;
      }, 2000);
    } else {
      throw new Error("Failed to add to cart");
    }
  } catch (error) {
    Logger.error("[Cart] Error adding to cart", error, {
      productId,
      quantity,
    });
    button.disabled = false;
    button.textContent = originalText;
  }
}

/**
 * Initialize remove confirmation modal
 */
function initializeRemoveConfirmationModal() {
  const modalElement = querySelector("#cart-remove-confirmation-modal");
  if (modalElement && typeof Modal !== "undefined") {
    removeConfirmationModal = new Modal(modalElement);
  }
}

/**
 * Show remove confirmation modal
 * @param {string} itemKey - Cart item key
 * @param {string} productName - Product name for display
 * @returns {Promise<boolean>} - True if user confirms removal
 */
function showRemoveConfirmation(itemKey, productName) {
  return new Promise((resolve) => {
    const modal = querySelector("#cart-remove-confirmation-modal");
    if (!modal) {
      // Fallback to browser confirm
      resolve(confirm(`Remove ${productName} from cart?`));
      return;
    }

    // Update modal content
    const title = querySelector("#cart-remove-product-name");
    if (title) {
      title.textContent = productName;
    }

    // Setup confirm button
    const confirmBtn = querySelector("#cart-remove-confirm");
    if (confirmBtn) {
      const confirmHandler = async () => {
        confirmBtn.disabled = true;
        confirmBtn.textContent = "Removing...";

        try {
          const url = querySelector(`[data-remove-key="${itemKey}"]`)?.href;
          if (url) {
            await performRemoveItem(itemKey, url);
          }
        } finally {
          if (removeConfirmationModal) {
            removeConfirmationModal.close();
          }
          resolve(true);
        }
      };

      // Remove old listeners
      const newBtn = confirmBtn.cloneNode(true);
      confirmBtn.parentNode.replaceChild(newBtn, confirmBtn);
      newBtn.addEventListener("click", confirmHandler);
    }

    // Open modal
    if (removeConfirmationModal) {
      removeConfirmationModal.open();
    } else {
      // Fallback
      resolve(true);
    }
  });
}

/**
 * Perform actual item removal via AJAX
 * @param {string} itemKey - Cart item key
 * @param {string} url - Remove URL
 */
async function performRemoveItem(itemKey, url) {
  try {
    const response = await fetch(url, {
      method: "GET",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    });

    if (response.ok) {
      // Remove row from DOM with animation
      const row = querySelector(`[data-cart-row="${itemKey}"]`);
      if (row) {
        addClass(row, "cart-page__row--removing");
        setTimeout(() => {
          row.remove();
          updateCartTotals();
          checkEmptyCart();
        }, 300);
      }
    }
  } catch (error) {
    Logger.error("[Cart] Error removing item", error);
  }
}

/**
 * Setup cart page interactions (quantity, removal, updates)
 */
function setupCartPageInteractions() {
  const cartPageItems = querySelector(".cart-page__items");
  if (!cartPageItems) {
    return;
  }

  Logger.debug("[Cart] Setting up cart page interactions");

  // Setup quantity adjustment buttons
  setupQuantityAdjustment();

  // Setup quantity input changes
  setupQuantityInputs();

  // Setup remove buttons
  setupRemoveButtons();

  // Setup update cart button
  setupUpdateCartButton();
}

/**
 * Setup +/- quantity adjustment buttons
 */
function setupQuantityAdjustment() {
  querySelectorAll(".cart-page__qty-btn").forEach((btn) => {
    btn.addEventListener("click", handleQuantityButtonClick);
  });
}

/**
 * Handle +/- button clicks
 * @param {Event} e
 */
function handleQuantityButtonClick(e) {
  const btn = e.currentTarget;
  const isPlus = btn.classList.contains("cart-page__qty-btn--plus");
  const itemKey = btn.dataset.key;
  const input = querySelector(`#cart-quantity-${itemKey}`);

  if (!input) return;

  let quantity = parseInt(input.value) || 1;
  quantity = isPlus ? quantity + 1 : Math.max(0, quantity - 1);

  input.value = quantity;
  input.dispatchEvent(new Event("change", { bubbles: true }));
}

/**
 * Setup quantity input field changes
 */
function setupQuantityInputs() {
  querySelectorAll(".cart-page__quantity-input").forEach((input) => {
    input.addEventListener("change", handleQuantityChange);
  });
}

/**
 * Handle quantity input change
 * @param {Event} e
 */
async function handleQuantityChange(e) {
  const input = e.currentTarget;
  const itemKey = input.name.match(/\[([^\]]+)\]/)?.[1];

  if (!itemKey) return;

  let quantity = parseInt(input.value) || 0;
  const row = input.closest(".cart-page__row--item");

  if (!row) return;

  // Add loading state
  addClass(row, "cart-page__row--updating");

  try {
    // Update via AJAX
    await updateCartItemQuantity(itemKey, quantity);

    // Update subtotal for this item
    updateItemSubtotal(itemKey, row);

    // Update totals
    updateCartTotals();

    // Remove loading state
    removeClass(row, "cart-page__row--updating");

    if (quantity === 0) {
      // Remove row with animation
      addClass(row, "cart-page__row--removing");
      setTimeout(() => {
        row.remove();
        checkEmptyCart();
      }, 300);
    }
  } catch (error) {
    Logger.error("[Cart] Error updating quantity", error);
    removeClass(row, "cart-page__row--updating");
  }
}

/**
 * Update cart item quantity via AJAX
 * @param {string} itemKey - Cart item key
 * @param {number} quantity - New quantity
 */
async function updateCartItemQuantity(itemKey, quantity) {
  try {
    const formData = new FormData();
    formData.append("action", "wc_update_cart_quantity");
    formData.append("item_key", itemKey);
    formData.append("quantity", quantity);
    formData.append(
      "nonce",
      document.querySelector('input[name="_wpnonce"]')?.value || "",
    );

    const response = await fetch(window.ajaxurl || "/wp-admin/admin-ajax.php", {
      method: "POST",
      body: formData,
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    });

    if (!response.ok) {
      throw new Error("Failed to update quantity");
    }

    const data = await response.json();
    return data;
  } catch (error) {
    throw error;
  }
}

/**
 * Update item subtotal display
 * @param {string} itemKey - Cart item key
 * @param {Element} row - Row element
 */
function updateItemSubtotal(itemKey, row) {
  const quantityInput = querySelector(`#cart-quantity-${itemKey}`, row);
  const priceElement = querySelector(".cart-page__price", row);
  const subtotalElement = querySelector(".cart-page__subtotal", row);

  if (!quantityInput || !subtotalElement) return;

  const quantity = parseInt(quantityInput.value) || 0;

  // Extract price from formatted string
  const priceText = priceElement?.textContent || "0";
  const priceMatch = priceText.match(/[\d.,]+\.?\d*/);
  const price = parseFloat(priceMatch?.[0]?.replace(/,/g, "") || 0);

  const subtotal = price * quantity;

  // Format as currency (simplified - ideally use WooCommerce formatting)
  const formatted = new Intl.NumberFormat("en-US", {
    style: "currency",
    currency: "USD",
  }).format(subtotal);

  subtotalElement.textContent = formatted;
}

/**
 * Update total cart amounts
 */
async function updateCartTotals() {
  const totalsSection = querySelector(".cart-page__totals");
  if (!totalsSection) return;

  try {
    const response = await fetch(window.location.href, {
      method: "GET",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
      cache: "no-store",
    });

    if (response.ok) {
      const html = await response.text();
      const parser = new DOMParser();
      const doc = parser.parseFromString(html, "text/html");
      const newTotals = doc.querySelector(".cart-page__totals");

      if (newTotals) {
        totalsSection.innerHTML = newTotals.innerHTML;
      }
    }
  } catch (error) {
    Logger.error("[Cart] Error updating totals", error);
  }
}

/**
 * Setup remove buttons
 */
function setupRemoveButtons() {
  querySelectorAll(".cart-page__remove-btn").forEach((btn) => {
    btn.addEventListener("click", handleRemoveClick);
  });
}

/**
 * Handle remove button click
 * @param {Event} e
 */
async function handleRemoveClick(e) {
  e.preventDefault();

  const btn = e.currentTarget;
  const row = btn.closest(".cart-page__row--item");
  const itemKey = row?.dataset.productId;
  const productName =
    row?.querySelector(".cart-page__product-title")?.textContent || "Item";

  // Show confirmation and perform removal
  await showRemoveConfirmation(itemKey, productName);
}

/**
 * Setup update cart button
 */
function setupUpdateCartButton() {
  const updateBtn = querySelector(".cart-page__btn--update");
  if (updateBtn) {
    updateBtn.addEventListener("click", handleUpdateCart);
  }
}

/**
 * Handle update cart button
 * @param {Event} e
 */
async function handleUpdateCart(e) {
  e.preventDefault();

  const btn = e.currentTarget;
  const originalText = btn.textContent;
  btn.disabled = true;
  btn.textContent = "Updating...";

  try {
    // Collect all quantities
    const quantities = {};
    querySelectorAll(".cart-page__quantity-input").forEach((input) => {
      const match = input.name.match(/\[([^\]]+)\]/);
      if (match) {
        quantities[match[1]] = parseInt(input.value) || 0;
      }
    });

    // Send update
    const formData = new FormData();
    formData.append("action", "wc_update_cart_items");
    formData.append("quantities", JSON.stringify(quantities));
    formData.append(
      "nonce",
      document.querySelector('input[name="_wpnonce"]')?.value || "",
    );

    const response = await fetch(window.ajaxurl || "/wp-admin/admin-ajax.php", {
      method: "POST",
      body: formData,
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    });

    if (response.ok) {
      updateCartTotals();
      btn.textContent = "✓ Updated";
      setTimeout(() => {
        btn.disabled = false;
        btn.textContent = originalText;
      }, 2000);
    }
  } catch (error) {
    Logger.error("[Cart] Error updating cart", error);
    btn.disabled = false;
    btn.textContent = originalText;
  }
}

/**
 * Check if cart is empty and show empty state
 */
function checkEmptyCart() {
  const rows = querySelectorAll(".cart-page__row--item");
  if (rows.length === 0) {
    const itemsSection = querySelector(".cart-page__items");
    const emptySection = querySelector(".cart-page__empty");
    if (itemsSection && emptySection) {
      itemsSection.style.display = "none";
      emptySection.style.display = "block";
    }
  }
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
    if (count !== undefined) {
      counter.textContent = count;
    } else {
      // Fetch current cart count
      fetch(window.location.href, {
        headers: { "X-Requested-With": "XMLHttpRequest" },
      })
        .then((r) => r.text())
        .then((html) => {
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, "text/html");
          const newCounter = doc.querySelector("[data-cart-count]");
          if (newCounter) {
            counter.textContent = newCounter.textContent;
          }
        });
    }
  }
}

/**
 * Get current cart total
 * @returns {string}
 */
export function getCartTotal() {
  const total = querySelector(".cart-page__total-value--final");
  return total ? total.textContent : "";
}

/**
 * Get cart item count
 * @returns {number}
 */
export function getCartItemCount() {
  const rows = querySelectorAll(".cart-page__row--item");
  return rows.length;
}
