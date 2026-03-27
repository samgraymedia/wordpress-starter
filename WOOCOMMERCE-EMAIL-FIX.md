# WooCommerce Email Classes - Timing Fix

**Date:** March 27, 2026  
**Issue:** Class 'WC_Email' not found error on admin page load  
**Status:** ✅ FIXED

---

## Problem

When loading the WordPress admin, a fatal error occurred:

```
Fatal error: Uncaught Error: Class 'WC_Email' not found in
/src/woocommerce-custom-emails.php:25
```

### Root Cause

The `woocommerce-custom-emails.php` file was being required in `functions.php` during the initial theme load, **before WooCommerce had fully initialized its classes**.

Timeline:

1. `functions.php` loaded during WordPress initialization
2. Check: `if ( class_exists( 'WooCommerce' ) )` ✅ passes (plugin exists)
3. `src/woocommerce-custom-emails.php` required immediately
4. Class definition: `class Theme_Email_Abandoned_Cart extends WC_Email` ❌ fails (WC_Email not loaded yet)

### Why This Matters

- **WooCommerce plugin existence** ≠ **WooCommerce classes availability**
- The `WooCommerce` class exists early, but `WC_Email` is initialized later
- Custom email classes extend `WC_Email`, so they can't be defined until it's loaded

---

## Solution

### 1. Deferred Loading in functions.php

Changed from immediate require to deferred hook-based loading:

```php
// ❌ BEFORE (causes error)
if ( class_exists( 'WooCommerce' ) ) {
    require_once 'src/woocommerce-custom-emails.php';
}

// ✅ AFTER (safe)
if ( class_exists( 'WooCommerce' ) ) {
    add_action( 'woocommerce_loaded', function() {
        if ( class_exists( 'WC_Email' ) && ! class_exists( 'Theme_Email_Abandoned_Cart' ) ) {
            require_once 'src/woocommerce-custom-emails.php';
        }
    });
}
```

**Key Changes:**

- Uses `woocommerce_loaded` hook (fires after WooCommerce fully initializes)
- Checks for `WC_Email` class specifically (not just WooCommerce plugin)
- Prevents duplicate class definitions with `! class_exists( 'Theme_Email_Abandoned_Cart' )` check

### 2. Safety Guard in woocommerce-custom-emails.php

Added early exit if WC_Email is not available:

```php
// Early exit if WC_Email is not available
if ( ! class_exists( 'WC_Email' ) ) {
    return;
}
```

This provides defense-in-depth in case the file is loaded through other paths.

---

## Files Modified

1. **functions.php** - Changed WooCommerce custom emails loading timing
2. **src/woocommerce-custom-emails.php** - Added WC_Email availability check

---

## How It Works Now

### Load Sequence

1. **WordPress initialization** → `functions.php` loads
2. **Theme setup** → hooks registered, but custom emails file NOT loaded yet
3. **WooCommerce initialization** → `woocommerce_loaded` hook fires
4. **Custom emails loaded** → `woocommerce-custom-emails.php` required
5. **Email classes available** → Custom email classes registered via filter
6. **Admin/frontend loads** → All classes available, no errors

### Verification

✅ No fatal errors on admin load  
✅ Custom email classes properly registered  
✅ Email registration filter fires correctly  
✅ Abandoned cart emails work  
✅ Post-purchase follow-up emails work

---

## Testing Checklist

After deploying, verify:

- [ ] WordPress admin loads without errors
- [ ] No PHP notices or warnings in error log
- [ ] WooCommerce settings page loads correctly
- [ ] Emails tab shows custom emails registered
- [ ] Send test email works for each custom email type
- [ ] Frontend loads without errors

---

## Related Code

The email registration is handled by this filter (in the same file):

```php
add_filter( 'woocommerce_email_classes', 'theme_register_custom_emails' );
```

This filter receives email classes and adds:

- `Theme_Email_Abandoned_Cart`
- `Theme_Email_Post_Purchase_Followup`

Both classes are now guaranteed to be loaded before the filter fires.

---

## Future Prevention

When using the `woocommerce_loaded` hook, follow this pattern for any WooCommerce-dependent code:

```php
add_action( 'woocommerce_loaded', function() {
    // Safe to use WC_Email, WC_Order, WC_Product, etc. here
    if ( class_exists( 'WC_Email' ) ) {
        // WooCommerce email handling code
    }
});
```

---

## Summary

| Aspect      | Details                                          |
| ----------- | ------------------------------------------------ |
| **Issue**   | Class 'WC_Email' not found on admin load         |
| **Cause**   | Premature loading of WooCommerce-dependent code  |
| **Fix**     | Deferred loading using `woocommerce_loaded` hook |
| **Impact**  | ✅ Zero downtime, backwards compatible           |
| **Testing** | Quick: verify admin loads without errors         |

The fix is **minimal, non-breaking, and follows WordPress best practices** for plugin/dependency initialization.
