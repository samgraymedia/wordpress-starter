# Checkout Flow Testing Guide

This document provides comprehensive instructions for testing the checkout flow implementation, including guest checkout, account creation, and shipping method selection.

---

## 📋 Test Scenarios

### 1. Guest Checkout Flow

**Objective:** Verify that customers can complete checkout without creating an account.

**Steps:**

1. Navigate to `/checkout/`
2. Verify the checkout page loads with:

   - ✓ Login/Guest tabs at the top (visible only if not logged in)
   - ✓ "Guest Checkout" tab is selected by default
   - ✓ Multi-step progress indicator (1-4)
   - ✓ Order summary sidebar on the right

3. Complete billing information:

   - First Name: "John"
   - Last Name: "Doe"
   - Company: "Acme Corp"
   - Email: "john@example.com"
   - Phone: "555-1234"
   - Address: "123 Main St"
   - City: "Portland"
   - State: "OR"
   - Postal Code: "97201"
   - Country: "US"

4. Verify account creation section:

   - ✓ "Create an account?" checkbox is unchecked
   - ✓ Password fields are hidden

5. Select a shipping method:

   - Click on "Standard Shipping (\$5.00)" radio button
   - Verify order total updates with shipping cost

6. Select a payment method:

   - Choose "Credit Card"
   - Card details section should appear

7. Accept terms:

   - Check "I accept the terms and conditions"
   - Verify checkbox is checked

8. Place order:
   - Click "Place Order" button
   - Verify AJAX request is sent
   - Expect redirect to order confirmation page

**Expected Results:**

- ✓ Order created in WooCommerce
- ✓ No user account created
- ✓ Customer receives order confirmation email
- ✓ Order appears in WooCommerce admin

---

### 2. Account Creation During Checkout

**Objective:** Verify customers can create an account during guest checkout.

**Steps:**

1. Navigate to `/checkout/`
2. Fill in billing information (same as test 1)
3. In "Account Creation" section:

   - Check "Create an account?" checkbox
   - Verify password fields appear
   - Password field shows strength indicator

4. Enter password:

   - Type "WeakPass" → Strength indicator shows "Weak"
   - Delete and type "MySecurePass123!" → Strength indicator shows "Strong"

5. Enter password confirmation:

   - Type matching password

6. Complete checkout process (steps 5-8 from test 1)

**Expected Results:**

- ✓ New WordPress user created with email address
- ✓ User is automatically logged in after order placement
- ✓ Order is associated with the new user account
- ✓ User can log in with email/password on future checkouts

---

### 3. Address Validation

**Objective:** Verify client-side address validation works correctly.

**Steps:**

1. Navigate to `/checkout/`
2. Leave fields blank and blur (click away):

   - First Name field → Error: "First name required"
   - City field → Error: "City required"

3. Enter invalid data and blur:

   - City: "LA" → Error: "City must be at least 3 characters"
   - Postal Code: "ABC" → Error: "Invalid postal code format"

4. Enter valid data:
   - All fields turn green
   - No error messages

**Expected Results:**

- ✓ Fields with BEM class `.checkout-page__form-input--error` display red border
- ✓ Error messages appear below invalid fields
- ✓ Errors clear when valid data is entered
- ✓ Form submission is blocked if errors exist

---

### 4. Shipping Method Selection

**Objective:** Verify shipping method selection updates cart totals.

**Steps:**

1. Navigate to `/checkout/`
2. Add product to cart, proceed to checkout
3. Note the current cart total

4. In shipping methods section:

   - Select "Standard Shipping (\$5.00)"
   - Wait for AJAX update
   - Verify order total increases by \$5.00

5. Switch to "Express Shipping (\$15.00)":

   - Click Express option
   - Wait for AJAX update
   - Verify order total increases by \$10.00 more

6. Switch to "Overnight (\$25.00)":
   - Click Overnight option
   - Verify order total increases by \$10.00 more

**Expected Results:**

- ✓ AJAX request sent immediately on radio button change
- ✓ Shipping total updates in real-time
- ✓ Cart total updates reflecting shipping cost
- ✓ No page reload required

---

### 5. Login During Checkout

**Objective:** Verify existing customers can login during checkout.

**Steps:**

1. Create a test user in WordPress admin or via registration

   - Email: "existing@example.com"
   - Password: "TestPassword123"

2. Navigate to `/checkout/`
3. Click on "Login" tab
4. Enter credentials:
   - Username/Email: "existing@example.com"
   - Password: "TestPassword123"
5. Click "Login" button
6. Verify:
   - User is authenticated
   - Billing fields pre-populate (if saved from previous order)
   - Login form disappears
   - Checkout continues as logged-in user

**Expected Results:**

- ✓ User is authenticated via AJAX
- ✓ User session is set
- ✓ Login form is replaced with checkout information
- ✓ Previous billing addresses are available

---

### 6. Password Strength Indicator

**Objective:** Verify password strength validation works correctly.

**Steps:**

1. Navigate to `/checkout/`
2. Check "Create an account?" checkbox
3. Enter passwords and observe strength indicator:

   | Password           | Expected Strength | Color  |
   | ------------------ | ----------------- | ------ |
   | "Pass"             | Weak              | Red    |
   | "Password"         | Fair              | Orange |
   | "Password123"      | Medium            | Yellow |
   | "MySecurePass123!" | Strong            | Green  |

4. Verify feedback text updates

**Expected Results:**

- ✓ Weak: length < 8 or single character type
- ✓ Fair: length 8-10 with 1-2 character types
- ✓ Medium: length 10-12 with 2-3 character types
- ✓ Strong: length 12+ with 3+ character types

---

## 🔍 AJAX Endpoint Testing (with cURL)

### Test 1: Checkout Login

```bash
curl -X POST http://localhost/wordpress-starter-store/wp-admin/admin-ajax.php \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "action=checkout_login&username=testuser&password=TestPassword123&_wpnonce=YOUR_NONCE"
```

**Expected Response:**

```json
{
  "success": true,
  "data": {
    "message": "Login successful"
  }
}
```

---

### Test 2: Update Shipping Method

```bash
curl -X POST http://localhost/wordpress-starter-store/wp-admin/admin-ajax.php \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "action=update_shipping_method&method=flat_rate:1&_wpnonce=YOUR_NONCE"
```

**Expected Response:**

```json
{
  "success": true,
  "data": {
    "shipping_total": "$5.00",
    "cart_total": "$25.00"
  }
}
```

---

### Test 3: Process Checkout

```bash
curl -X POST http://localhost/wordpress-starter-store/wp-admin/admin-ajax.php \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "action=process_checkout&billing_first_name=John&billing_last_name=Doe&billing_email=john@example.com&billing_address_1=123%20Main%20St&billing_city=Portland&billing_state=OR&billing_postcode=97201&billing_country=US&_wpnonce=YOUR_NONCE"
```

**Expected Response:**

```json
{
  "success": true,
  "data": {
    "message": "Order placed"
  }
}
```

---

## 📊 Manual Testing Checklist

### Visual Elements

- [ ] Progress indicator displays all 4 steps
- [ ] Active step is highlighted in blue
- [ ] Login/guest tabs are properly styled
- [ ] Form inputs have proper spacing and alignment
- [ ] Sidebar sticky positioning works on scroll
- [ ] Mobile layout is responsive on phones/tablets

### Functionality

- [ ] Tab switching works without page reload
- [ ] Form validation shows/hides errors correctly
- [ ] Password strength indicator updates in real-time
- [ ] Shipping method selection updates totals
- [ ] Account creation checkbox toggles password fields
- [ ] Terms checkbox can be checked/unchecked

### Security

- [ ] Nonce field is present in form
- [ ] AJAX requests include nonce in headers
- [ ] Sensitive data (passwords) is not logged
- [ ] SQL injection attempts are sanitized
- [ ] Password is not echoed in responses

### Browser Compatibility

- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)
- [ ] Mobile Chrome (iOS/Android)
- [ ] Mobile Safari (iOS)

### Error Handling

- [ ] Missing required fields show errors
- [ ] Invalid email format shows error
- [ ] Duplicate email on account creation shows error
- [ ] Password too weak shows error
- [ ] AJAX failures show user-friendly messages
- [ ] Server errors are logged without exposing details

---

## 🛠️ Debugging

### Check Frontend Console

```javascript
// In browser DevTools Console tab:
Logger.debug("[Checkout] Initializing...");
// Should see checkout initialization logs
```

### Check Network Tab

1. Open DevTools → Network tab
2. Perform checkout action
3. Look for AJAX requests:
   - `admin-ajax.php?action=update_shipping_method`
   - `admin-ajax.php?action=process_checkout`
   - Verify requests include nonce
   - Verify responses are valid JSON

### Check Server Logs

```bash
# Check WordPress error log
tail -f /Applications/MAMP/htdocs/wordpress-starter-store/wp-content/debug.log

# Check PHP error log
tail -f /Applications/MAMP/logs/php_error.log
```

### Common Issues

| Issue                 | Solution                                                          |
| --------------------- | ----------------------------------------------------------------- |
| "Invalid nonce" error | Verify nonce field is in form, AJAX includes it                   |
| Form won't submit     | Check browser console for errors, verify field validation         |
| AJAX returns 0        | Action handler may not be registered, check hook name             |
| Shipping not updating | Verify WooCommerce session is active, check shipping zones config |
| Account not created   | Check email/password validation, verify wp_create_user permission |

---

## ✅ Sign-off Checklist

- [ ] All 6 test scenarios pass
- [ ] AJAX endpoints respond correctly
- [ ] Visual layout matches design
- [ ] Mobile responsive design works
- [ ] Form validation works as expected
- [ ] Password strength indicator works
- [ ] Account creation works
- [ ] Shipping selection updates totals
- [ ] No console errors
- [ ] No PHP errors in log
- [ ] Security checks pass (nonce, sanitization, escaping)
- [ ] Cross-browser testing complete

---

## 📝 Notes for QA

- Testing requires MAMP running with WordPress and WooCommerce active
- Test with existing products in cart before checkout
- Test both as guest and logged-in user
- Test on mobile devices for responsive design
- Create multiple test accounts to verify unique email validation
- Verify order appears in WooCommerce admin after successful checkout
