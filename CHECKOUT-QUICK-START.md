# Checkout Flow - Quick Start Testing Guide

## ⚡ 5-Minute Quick Test

### Prerequisites

1. MAMP running with WordPress active
2. WooCommerce installed and activated
3. At least one product in your cart (or add one before checkout)

### Quick Test Steps

1. **Navigate to Checkout**

   ```
   http://localhost/wordpress-starter-store/checkout/
   ```

2. **Test Guest Checkout (No Login)**

   - Page should show "Guest Checkout" tab selected
   - Fill in billing info:
     - First Name: John
     - Last Name: Doe
     - Email: john@test.com
     - Address: 123 Main St
     - City: Portland
     - State: OR
     - Postal: 97201
   - Select "Standard Shipping"
   - Check terms checkbox
   - Click "Place Order"
   - Expected: Order created, redirect to confirmation

3. **Test Account Creation**

   - Go back to checkout (cart page → proceed to checkout)
   - Check "Create an account?" checkbox
   - Password field appears
   - Enter password: "MySecurePass123!"
   - Watch strength indicator turn green
   - Complete checkout as above
   - Expected: New user account created, auto-logged in

4. **Test Address Validation**

   - Go back to checkout
   - Leave "First Name" blank, click away
   - Red error border appears with "First name required"
   - Type valid first name
   - Error clears, border turns green
   - Test other fields similarly

5. **Test Shipping Updates**
   - Select different shipping methods
   - Total updates in real-time
   - No page reload

---

## 🧪 Full Test Suite (30 minutes)

See **CHECKOUT-TEST-GUIDE.md** for complete test scenarios:

- 6 comprehensive test scenarios
- Visual/functional checklists
- Security verification
- Browser compatibility matrix
- Debugging tips

---

## 🤖 Automated Testing

### Run AJAX Endpoint Tests

```bash
cd /Applications/MAMP/htdocs/wordpress-starter-store/wp-content/themes/starter-theme-1.x

chmod +x test-checkout.sh
./test-checkout.sh http://localhost/wordpress-starter-store
```

Expected output:

- 9 tests running
- Color-coded results (✓ PASS / ✗ FAIL)
- Summary: Passed X, Failed 0

---

## 🔍 What to Verify

### Visual

- [ ] Theme colors and styling applied
- [ ] Progress indicator shows 4 steps
- [ ] Login/guest tabs visible
- [ ] Sidebar is sticky on scroll
- [ ] Mobile responsive layout works

### Functional

- [ ] Guest tab shows guest form
- [ ] Login tab shows login form
- [ ] Account checkbox toggles password fields
- [ ] Password strength indicator updates real-time
- [ ] Shipping method selection updates total
- [ ] Form validation shows errors
- [ ] Terms checkbox can be checked

### Data

- [ ] Order appears in WooCommerce admin
- [ ] Customer info is saved
- [ ] Shipping cost is applied
- [ ] Order total is correct
- [ ] Email notification sent

### Security

- [ ] Browser console has no errors
- [ ] No sensitive data exposed
- [ ] Nonce is verified

---

## 📋 Browser Console Check

Open DevTools (F12) → Console tab:

**Good:**

```
[Theme] Cart module initialized
[Theme] Initializing Checkout module...
[Theme] Checkout module initialized
[Checkout] Initializing...
```

**Bad:**

```
Uncaught TypeError: initCheckout is not a function
Invalid nonce
CORS error
```

---

## 🐛 Troubleshooting Quick Links

| Problem             | Check                                               |
| ------------------- | --------------------------------------------------- |
| Page blank          | Is MAMP/WordPress running? Check `/` loads?         |
| Nonce error         | Is form rendering? Check: `wp_nonce_field()`        |
| AJAX fails          | Open Network tab in DevTools, check requests        |
| No styling          | Check CSS file at `/css/inc/pages/woocommerce.scss` |
| Account not created | Password >= 8 chars? Email unique?                  |

---

## 📍 Key Files

| File                                | Purpose            | Size |
| ----------------------------------- | ------------------ | ---- |
| `templates/checkout.twig`           | HTML template      | 26KB |
| `scripts/modules/checkout.js`       | JavaScript logic   | 14KB |
| `src/woocommerce-integration.php`   | AJAX handlers      | 15KB |
| `css/inc/pages/woocommerce.scss`    | Styling            | 17KB |
| `CHECKOUT-TEST-GUIDE.md`            | Full testing guide | 10KB |
| `CHECKOUT-IMPLEMENTATION-REPORT.md` | Complete report    | 12KB |

---

## ✅ Sign-Off

After completing quick test, verify:

- [ ] All guest checkout steps work
- [ ] Account creation with validation works
- [ ] Shipping selection updates totals
- [ ] Order appears in admin
- [ ] No console errors
- [ ] Mobile layout responsive

If all boxes checked ✅ → **READY FOR DEPLOYMENT**

---

## 📞 Need Help?

1. Check `CHECKOUT-TEST-GUIDE.md` for full scenarios
2. Check `CHECKOUT-IMPLEMENTATION-REPORT.md` for technical details
3. Run `./test-checkout.sh` for automated validation
4. Check browser console (F12) for error messages
5. Check WordPress error log: `/wp-content/debug.log`
6. Check PHP error log: `/Applications/MAMP/logs/php_error.log`

---

**Last Updated:** March 26, 2025  
**Status:** ✅ Ready for Testing
