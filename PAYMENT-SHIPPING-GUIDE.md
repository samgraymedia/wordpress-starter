# Payment & Shipping Integration Guide

**Status:** ✅ Complete | **Version:** 1.0 | **Last Updated:** March 2026

---

## Overview

This guide documents the complete payment gateway and shipping configuration system integrated into the WooCommerce theme. The system supports multiple payment methods (Stripe, PayPal) and flexible shipping options (flat-rate, weight-based calculated shipping).

---

## Table of Contents

1. [Payment Gateway Integration](#payment-gateway-integration)
2. [Shipping Configuration](#shipping-configuration)
3. [Checkout Flow](#checkout-flow)
4. [Configuration & Setup](#configuration--setup)
5. [Testing Guide](#testing-guide)
6. [API Reference](#api-reference)
7. [Troubleshooting](#troubleshooting)
8. [Security Considerations](#security-considerations)

---

## Payment Gateway Integration

### Supported Payment Methods

#### 1. **Stripe Integration**

**Description:** Accept credit card payments directly on your checkout page using Stripe's hosted card element.

**Features:**

- PCI-compliant card handling
- SSL encryption
- Test mode support
- Fraud detection
- 3D Secure support (ready)

**File Location:** `src/woocommerce-integration.php`

**Configuration Fields:**

- **Enable/Disable:** Toggle Stripe payments on/off
- **Publishable Key:** Your Stripe publishable key (pk*live*...)
- **Secret Key:** Your Stripe secret key (sk*live*...)
- **Test Mode:** Enable/disable test mode (recommended for development)

**Setup Instructions:**

1. Create a Stripe account at https://stripe.com
2. Navigate to Dashboard → API Keys
3. Copy your Publishable Key and Secret Key
4. Go to WordPress Admin → WooCommerce → Settings → Payments
5. Click "Stripe" and enter your keys
6. Save settings

**Files Modified:**

- `src/woocommerce-integration.php` - Gateway class registration
- `templates/checkout.twig` - Stripe card element display
- `css/inc/pages/woocommerce.scss` - Stripe field styling

**Stripe Card Element:**

```twig
<div id="stripe-card-element"></div>
<div id="stripe-card-errors" role="alert"></div>
```

The card element is rendered via Stripe.js v3, which:

- Validates card format in real-time
- Displays validation errors
- Formats card number with spaces
- Securely handles sensitive data

#### 2. **PayPal Integration**

**Description:** Redirect customers to PayPal for payment processing with express checkout option.

**Features:**

- PayPal Express Checkout
- Sandbox/live mode
- IPN (Instant Payment Notification) support
- Buyer protection
- Mobile-optimized

**Configuration Fields:**

- **Enable/Disable:** Toggle PayPal payments on/off
- **Client ID:** Your PayPal Client ID
- **Test Mode:** Enable sandbox mode for testing

**Setup Instructions:**

1. Create a PayPal Business Account at https://www.paypal.com
2. Go to Developer Sandbox → Apps & Credentials
3. Create an application and get your Client ID
4. Go to WordPress Admin → WooCommerce → Settings → Payments
5. Click "PayPal" and enter your Client ID
6. Save settings

**Customer Experience:**

- Customer selects PayPal at checkout
- System redirects to PayPal login/confirmation
- PayPal redirects back to order confirmation page
- Order status updates automatically

---

### Payment Security Badges

The checkout displays security indicators to build customer trust:

```
🔒 SSL Secure - Indicates encrypted connection
✅ PCI Compliant - Indicates PCI DSS compliance
```

**CSS Classes:**

- `.checkout-page__security-badge` - Badge container
- `.checkout-page__security-badge.ssl-secure` - SSL badge
- `.checkout-page__security-badge.pci-compliant` - PCI badge

**Customization:**
Edit badge text in `templates/checkout.twig`:

```twig
<span class="checkout-page__security-badge ssl-secure">
    {{ _e('SSL Secure', 'starter-theme-1.x') }}
</span>
```

---

## Shipping Configuration

### Shipping Methods

#### 1. **Flat Rate Shipping**

**Description:** Simple fixed shipping cost with optional free shipping threshold.

**Features:**

- Fixed cost per order
- Free shipping threshold based on cart total
- Multiple rate options supported
- Easy bulk configuration

**Configuration Fields:**

- **Enable/Disable:** Toggle flat rate shipping
- **Title:** Display name (e.g., "Standard Shipping")
- **Flat Shipping Cost:** Cost in dollars (e.g., 9.99)
- **Free Shipping Over:** Cart total threshold for free shipping (e.g., 100.00)

**Logic:**

```php
if (cart_total >= free_shipping_threshold) {
    shipping_cost = $0;
} else {
    shipping_cost = flat_shipping_cost;
}
```

**Example Configuration:**

- Standard Shipping: $9.99 (free over $100)
- Expedited Shipping: $19.99 (free over $150)
- Overnight Shipping: \$39.99 (no free threshold)

**File Location:** `src/woocommerce-integration.php` - `WC_Flat_Rate_Custom` class

#### 2. **Calculated (Weight-Based) Shipping**

**Description:** Dynamic shipping cost calculated based on order weight and base rate.

**Features:**

- Weight-based calculations
- Base cost plus per-kg rate
- Accurate cost modeling
- Physical product support

**Configuration Fields:**

- **Enable/Disable:** Toggle calculated shipping
- **Title:** Display name (e.g., "Weight-Based Shipping")
- **Base Shipping Cost:** Starting cost (e.g., 5.00)
- **Cost Per Kilogram:** Per-kg rate (e.g., 0.50)

**Formula:**

```
Total Shipping Cost = Base Cost + (Total Weight in KG × Cost Per KG)
```

**Example:**

- Base: \$5.00
- Cost per KG: \$0.50
- Order weight: 10 KG
- Total: $5.00 + (10 × $0.50) = \$10.00

**File Location:** `src/woocommerce-integration.php` - `WC_Calculated_Shipping` class

**Requirements:**

- Products must have weight configured
- Product units must be in kilograms

---

### Shipping Zones

Shipping zones allow different rates for different geographical areas.

**Default Zones Setup:**

1. **Domestic (US)** - Standard rates
2. **International** - Higher rates or restrictions

**Creating Zones in WordPress Admin:**

1. Go to WooCommerce → Settings → Shipping
2. Click "Shipping zones" tab
3. Click "Add shipping zone"
4. Enter zone name (e.g., "United States")
5. Add regions (countries, states)
6. Assign shipping methods
7. Set shipping costs
8. Save zone

**Each Zone Can Have:**

- Multiple shipping methods
- Different delivery times
- Different costs
- Restrictions (min/max weight, value)

---

### Shipping Calculator

The checkout page includes a shipping calculator for real-time cost estimation.

**Features:**

- Estimate shipping before payment
- Location-based calculations
- Updates based on address
- AJAX-powered (no page reload)

**Display:**
Located in Step 2 (Shipping Address) of checkout

**Form Fields:**

- Postal Code
- Country
- State/Province

**Button:** "Calculate Shipping"

**Results Display:**

- Shows available methods for location
- Displays costs for each method
- Allows selection before checkout

**CSS Classes:**

- `.checkout-page__shipping-calculator` - Container
- `.checkout-page__calculator-title` - Title
- `.checkout-page__calc-results` - Results container

---

## Checkout Flow

### 4-Step Checkout Process

```
Step 1: Billing
    ↓
Step 2: Shipping
    ↓
Step 3: Payment
    ↓
Step 4: Review & Place Order
```

### Payment Selection Flow

```
Payment Methods Available
    ├── Stripe Card
    │   ├── Card Element displays if enabled
    │   ├── Real-time validation
    │   └── Submit with card details
    │
    ├── PayPal
    │   ├── Redirects to PayPal
    │   ├── Return with token
    │   └── Auto-confirm order
    │
    └── [Custom Methods]
```

### Shipping Selection Flow

```
Address Entry
    ↓
Select Shipping Method
    ↓
Optional: Calculate Shipping for location
    ↓
View costs
    ↓
Select method
    ↓
Proceed to payment
```

---

## Configuration & Setup

### Initial Setup Steps

#### Step 1: Enable Payment Gateways

1. Go to WordPress Admin Dashboard
2. Navigate to WooCommerce → Settings → Payments
3. For each payment method:
   - Click the method name
   - Fill in API credentials
   - Toggle Enable/Disable
   - Save Changes

#### Step 2: Configure Shipping Methods

1. Go to WooCommerce → Settings → Shipping
2. For Flat Rate Shipping:
   - Select "Flat Rate" method
   - Enter shipping cost
   - Set free shipping threshold
   - Save
3. For Calculated Shipping (optional):
   - Add zone
   - Select "Weight-Based" method
   - Set base cost and per-kg rate
   - Save

#### Step 3: Set Shipping Zones

1. Go to Shipping → Shipping zones
2. Create zones for each region
3. Add countries/states to each zone
4. Assign shipping methods
5. Save all zones

#### Step 4: Test Configuration

1. Add product to cart
2. Go to checkout
3. Verify payment methods display
4. Verify shipping calculator works
5. Complete test order

### Environment Variables

Store sensitive API keys in your environment (not in database):

```php
// In functions.php or config file
define( 'STRIPE_PUBLIC_KEY', getenv( 'STRIPE_PUBLIC_KEY' ) );
define( 'STRIPE_SECRET_KEY', getenv( 'STRIPE_SECRET_KEY' ) );
define( 'PAYPAL_CLIENT_ID', getenv( 'PAYPAL_CLIENT_ID' ) );
```

### Database Configuration

Settings stored in WooCommerce options:

- `woocommerce_stripe_settings` - Stripe config
- `woocommerce_paypal_settings` - PayPal config
- `woocommerce_flat_rate_custom_settings` - Flat rate config

---

## Testing Guide

### Payment Gateway Testing

#### Stripe Test Cards

| Card Number         | Status    | 3D Secure |
| ------------------- | --------- | --------- |
| 4242 4242 4242 4242 | Success   | Yes       |
| 4000 0000 0000 0002 | Declined  | N/A       |
| 4000 0025 0000 3155 | CVC Error | No        |
| 4000 0000 0000 0010 | N/A       | Yes       |

**Test CVC:** Any 3 digits  
**Test Expiry:** Any future date

#### PayPal Sandbox Testing

1. Go to paypal.com → Developer Sandbox
2. Use sandbox buyer account
3. Login in test mode
4. Complete payment flow

#### Testing Checklist

- [ ] Stripe payment form displays on checkout
- [ ] Card validation works in real-time
- [ ] Test card processes successfully
- [ ] Invalid card shows error message
- [ ] PayPal button redirects correctly
- [ ] Order created after payment
- [ ] Order confirmation email sent
- [ ] Order appears in WooCommerce Dashboard

### Shipping Configuration Testing

#### Test Cases

1. **Flat Rate Shipping:**

   - [ ] Correct cost displays
   - [ ] Free shipping triggers over threshold
   - [ ] Multiple methods available

2. **Weight-Based Shipping:**

   - [ ] Cost calculated correctly
   - [ ] Weight from products used
   - [ ] Formula: base + (weight × rate)

3. **Shipping Calculator:**

   - [ ] Calculator displays on checkout
   - [ ] Location input fields work
   - [ ] Calculate button updates costs
   - [ ] Results show available methods

4. **Zones:**
   - [ ] Different zones show different rates
   - [ ] Country selection affects rates
   - [ ] Invalid zones handled gracefully

#### Manual Testing

```bash
# Test in browser console
curl -X POST http://localhost/wp-json/wc/v3/orders \
  -H "Content-Type: application/json" \
  -d '{
    "payment_method": "stripe",
    "total": "99.99"
  }'
```

---

## API Reference

### Stripe Gateway Class

**File:** `src/woocommerce-integration.php`

**Class: `WC_Stripe_Gateway extends WC_Payment_Gateway`**

**Key Methods:**

- `process_payment( $order_id )` - Process payment
- `payment_scripts()` - Load Stripe.js
- `payment_fields()` - Render card element
- `is_available()` - Check if enabled

**Settings:**

```php
$this->get_option( 'stripe_publishable_key' )
$this->get_option( 'stripe_secret_key' )
$this->get_option( 'test_mode' )
```

### PayPal Gateway Class

**Class: `WC_PayPal_Gateway extends WC_Payment_Gateway`**

**Key Methods:**

- `process_payment( $order_id )` - Redirect to PayPal
- `is_available()` - Check if enabled

**Settings:**

```php
$this->get_option( 'paypal_client_id' )
$this->get_option( 'test_mode' )
```

### Shipping Methods

**Flat Rate Class:**

```php
class WC_Flat_Rate_Custom extends WC_Shipping_Method {
    public function calculate_shipping( $package = array() )
}
```

**Calculated Class:**

```php
class WC_Calculated_Shipping extends WC_Shipping_Method {
    public function calculate_shipping( $package = array() )
}
```

### Twig Functions

**Available in Checkout Template:**

```twig
{# Get payment gateways #}
{% set gateways = function('WC')->payment_gateways().get_available_payment_gateways() %}

{# Get shipping methods #}
{% set methods = function('WC')->shipping().get_shipping_methods() %}

{# Get cart total #}
{{ function('WC')->cart.get_total() }}
```

---

## Troubleshooting

### Payment Issues

**Q: Stripe form not displaying**

- A: Check if Stripe is enabled and keys are entered
- Verify publishable key is correct format (pk\_...)
- Check browser console for JavaScript errors

**Q: "Invalid API key" error**

- A: Verify keys are correct and not mixed up
- Check test mode toggle matches key type (test/live)
- Regenerate keys if needed

**Q: PayPal redirect not working**

- A: Check PayPal Client ID is entered
- Verify sandbox/live mode matches environment
- Check PayPal account status (live account needed for live mode)

### Shipping Issues

**Q: Shipping methods not showing**

- A: Verify shipping is enabled in WooCommerce
- Check at least one method is enabled
- Verify shipping zones are configured

**Q: Calculated shipping shows \$0**

- A: Verify products have weight set
- Check base cost and per-kg rate is > 0
- Verify calculated shipping is enabled

**Q: Calculator doesn't calculate costs**

- A: Check if JavaScript is enabled
- Verify nonce is present
- Check browser console for errors

### Performance

**Q: Checkout page loads slowly**

- A: Stripe.js loading slowly
  - Solution: Load in background
  - Use async loading
  - Check CDN status

**Q: Shipping calculation takes time**

- A: Optimize zone queries
  - Use query caching
  - Limit methods per zone

---

## Security Considerations

### PCI Compliance

✅ **Secure:**

- Stripe handles card data securely
- PayPal processes payments externally
- No card data stored on server
- SSL encryption enabled

❌ **Never Do:**

- Store card numbers
- Store CVC codes
- Log sensitive data
- Use HTTP (not HTTPS)

### API Key Security

**Best Practices:**

- Store keys in environment variables
- Never commit keys to version control
- Rotate keys periodically
- Use strong passwords for admin accounts
- Enable 2FA on payment accounts

**.env Example:**

```
STRIPE_PUBLIC_KEY=pk_live_...
STRIPE_SECRET_KEY=sk_live_...
PAYPAL_CLIENT_ID=...
```

### CSRF Protection

All forms include WordPress nonce:

```twig
{{ function('wp_nonce_field', 'wc_checkout_nonce', '_wpnonce', false, false) }}
```

Verify nonce on submission:

```php
check_ajax_referer( 'wc_nonce', 'nonce' );
```

### Data Validation

All user inputs validated:

- Email format checked
- Phone format validated
- Postal codes sanitized
- Payment method verified against available options

---

## File Modifications Summary

| File                              | Changes                                                                | Lines |
| --------------------------------- | ---------------------------------------------------------------------- | ----- |
| `src/woocommerce-integration.php` | Added Stripe/PayPal gateway classes, shipping methods, security badges | +350  |
| `templates/checkout.twig`         | Updated payment/shipping sections to use WooCommerce gateways          | +80   |
| `css/inc/pages/woocommerce.scss`  | Added payment/shipping UI styles                                       | +250  |

---

## Next Steps

1. **Get API Keys:**

   - Stripe: https://dashboard.stripe.com
   - PayPal: https://developer.paypal.com

2. **Configure in WordPress:**

   - WooCommerce → Settings → Payments
   - WooCommerce → Settings → Shipping

3. **Test Thoroughly:**

   - Test all payment methods
   - Test all shipping scenarios
   - Test on mobile devices

4. **Go Live:**

   - Switch to live API keys
   - Disable test mode
   - Enable SSL certificate
   - Enable email notifications

5. **Monitor:**
   - Check failed payment logs
   - Monitor shipping accuracy
   - Review customer feedback
   - Update rates as needed

---

## Support & Resources

- **Stripe Docs:** https://stripe.com/docs
- **PayPal Docs:** https://developer.paypal.com/docs
- **WooCommerce Docs:** https://docs.woocommerce.com/
- **WordPress Docs:** https://developer.wordpress.org/

---

**Last Updated:** March 26, 2026  
**Version:** 1.0  
**Maintainer:** Theme Development Team
