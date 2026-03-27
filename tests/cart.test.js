/**
 * JavaScript Unit Tests: Cart Module
 *
 * Tests core cart functionality (add to cart, update quantity, remove items)
 */

const Cart = require("../../scripts/modules/cart");

describe("Cart Module", () => {
  let cart;

  beforeEach(() => {
    // Clear localStorage before each test
    localStorage.clear();
    sessionStorage.clear();

    // Initialize fresh cart instance
    cart = new Cart();

    // Reset fetch mock
    fetch.mockClear();
  });

  describe("Initialization", () => {
    test("should initialize with empty cart", () => {
      const newCart = new Cart();
      expect(newCart.getItems()).toEqual([]);
    });

    test("should load cart from localStorage if exists", () => {
      const mockCart = [
        { id: 1, name: "Product 1", quantity: 2, price: 29.99 },
      ];
      localStorage.setItem("cart", JSON.stringify(mockCart));

      const loadedCart = new Cart();
      expect(loadedCart.getItems()).toEqual(mockCart);
    });

    test("should return cart total", () => {
      const mockCart = [
        { id: 1, name: "Product 1", quantity: 2, price: 10.0 },
        { id: 2, name: "Product 2", quantity: 1, price: 20.0 },
      ];
      localStorage.setItem("cart", JSON.stringify(mockCart));

      const loadedCart = new Cart();
      expect(loadedCart.getTotal()).toBe(40.0);
    });
  });

  describe("Add to Cart", () => {
    test("should add product to empty cart", () => {
      cart.addItem({
        id: 1,
        name: "Test Product",
        price: 29.99,
        quantity: 1,
      });

      const items = cart.getItems();
      expect(items).toHaveLength(1);
      expect(items[0].id).toBe(1);
      expect(items[0].quantity).toBe(1);
    });

    test("should increment quantity if product already in cart", () => {
      const product = {
        id: 1,
        name: "Test Product",
        price: 29.99,
        quantity: 1,
      };

      cart.addItem(product);
      cart.addItem(product);

      const items = cart.getItems();
      expect(items).toHaveLength(1);
      expect(items[0].quantity).toBe(2);
    });

    test("should add multiple different products", () => {
      cart.addItem({ id: 1, name: "Product 1", price: 10.0, quantity: 1 });
      cart.addItem({ id: 2, name: "Product 2", price: 20.0, quantity: 1 });

      const items = cart.getItems();
      expect(items).toHaveLength(2);
    });

    test("should persist to localStorage after adding item", () => {
      cart.addItem({
        id: 1,
        name: "Test Product",
        price: 29.99,
        quantity: 1,
      });

      const stored = JSON.parse(localStorage.getItem("cart"));
      expect(stored).toHaveLength(1);
      expect(stored[0].id).toBe(1);
    });
  });

  describe("Update Cart", () => {
    beforeEach(() => {
      cart.addItem({ id: 1, name: "Product 1", price: 10.0, quantity: 1 });
    });

    test("should update item quantity", () => {
      cart.updateQuantity(1, 5);

      const item = cart.getItems().find((i) => i.id === 1);
      expect(item.quantity).toBe(5);
    });

    test("should remove item if quantity set to 0", () => {
      cart.updateQuantity(1, 0);

      const items = cart.getItems();
      expect(items).toHaveLength(0);
    });

    test("should calculate correct total after update", () => {
      cart.addItem({ id: 2, name: "Product 2", price: 20.0, quantity: 1 });
      cart.updateQuantity(1, 3); // Update first product quantity to 3

      const total = cart.getTotal();
      expect(total).toBe(30.0 + 20.0); // (10.00 * 3) + (20.00 * 1)
    });
  });

  describe("Remove from Cart", () => {
    beforeEach(() => {
      cart.addItem({ id: 1, name: "Product 1", price: 10.0, quantity: 2 });
      cart.addItem({ id: 2, name: "Product 2", price: 20.0, quantity: 1 });
    });

    test("should remove item from cart", () => {
      cart.removeItem(1);

      const items = cart.getItems();
      expect(items).toHaveLength(1);
      expect(items[0].id).toBe(2);
    });

    test("should return false if removing non-existent item", () => {
      const result = cart.removeItem(999);
      expect(result).toBe(false);
    });

    test("should update total after removal", () => {
      const totalBefore = cart.getTotal();
      expect(totalBefore).toBe(40.0);

      cart.removeItem(1);
      const totalAfter = cart.getTotal();
      expect(totalAfter).toBe(20.0);
    });
  });

  describe("Clear Cart", () => {
    test("should remove all items", () => {
      cart.addItem({ id: 1, name: "Product 1", price: 10.0, quantity: 1 });
      cart.addItem({ id: 2, name: "Product 2", price: 20.0, quantity: 1 });

      expect(cart.getItems()).toHaveLength(2);

      cart.clear();

      expect(cart.getItems()).toHaveLength(0);
      expect(cart.getTotal()).toBe(0);
    });
  });

  describe("Cart API Integration", () => {
    test("should sync cart with server", async () => {
      fetch.mockResolvedValueOnce({
        ok: true,
        json: async () => ({ success: true, cart: [] }),
      });

      cart.addItem({ id: 1, name: "Product 1", price: 10.0, quantity: 1 });
      await cart.sync();

      expect(fetch).toHaveBeenCalled();
      const call = fetch.mock.calls[0];
      expect(call[0]).toContain("cart");
    });

    test("should handle sync errors gracefully", async () => {
      fetch.mockRejectedValueOnce(new Error("Network error"));

      cart.addItem({ id: 1, name: "Product 1", price: 10.0, quantity: 1 });
      const result = await cart.sync();

      expect(result).toBe(false);
    });
  });
});
