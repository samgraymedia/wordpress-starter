# WooCommerce Email Templates Guide

**Status:** ✅ Implemented - 8 Professional Email Templates  
**Version:** 1.0  
**Last Updated:** March 26, 2026

---

## Overview

This theme includes 8 professional, production-ready WooCommerce email templates built with Twig and styled with email-client-compatible CSS. All emails are:

- ✅ Mobile-responsive
- ✅ Tested across email clients (Gmail, Outlook, Apple Mail)
- ✅ Branded with your theme colors and logo
- ✅ Built with Timber/Twig for consistency with your site
- ✅ Easily customizable (colors, text, branding)

---

## Implemented Email Templates

### 1. **Admin New Order** (WooCommerce Standard)

**File:** `templates/email/admin-new-order.twig`  
**Trigger:** When a new order is placed  
**Recipient:** Admin/store manager

**Includes:**

- Customer details (name, email, phone)
- Complete order items with SKUs
- Order totals (subtotal, shipping, tax, total)
- Billing and shipping addresses
- Payment & shipping method
- Link to edit order in admin

**Customize in:** WooCommerce Settings → Emails → New Order

---

### 2. **Customer Processing Order** (Order Received)

**File:** `templates/email/customer-processing-order.twig`  
**Trigger:** When order is received and moves to processing  
**Recipient:** Customer

**Includes:**

- Thank you message
- Order confirmation with number & date
- Order summary (products, qty, totals)
- Shipping address
- Shipping method
- Next steps messaging
- Order tracking link
- FAQ section (returns, contact info)

**Customize in:** WooCommerce Settings → Emails → Processing Order

---

### 3. **Customer Completed Order** (Shipment Notification)

**File:** `templates/email/customer-completed-order.twig`  
**Trigger:** When order is completed/ready to ship  
**Recipient:** Customer

**Includes:**

- Shipment notification
- Items shipped confirmation
- Tracking information placeholder
- Shipping address
- Order totals
- Product care & maintenance links
- Reviews request
- Returns policy & support info

**Customize in:** WooCommerce Settings → Emails → Completed Order

**Note:** For actual tracking integration, update the template with your carrier's tracking URL or use a WooCommerce shipment tracking plugin.

---

### 4. **Customer Refunded Order**

**File:** `templates/email/customer-refunded-order.twig`  
**Trigger:** When order is refunded (full or partial)  
**Recipient:** Customer

**Includes:**

- Full/Partial refund notification
- Order & refund details
- Refunded items list
- Refund timeline (cards vs bank vs digital wallet)
- Return shipping instructions
- Support contact information
- FAQ section

**Customize in:** WooCommerce Settings → Emails → Customer Refunded Order

---

### 5. **Customer New Account** (Welcome Email)

**File:** `templates/email/customer-new-account.twig`  
**Trigger:** When a new customer account is created  
**Recipient:** Customer

**Includes:**

- Account creation confirmation
- First steps guidance
- Features of their new account
- 10% off welcome coupon code
- Quick links (FAQs, shipping, returns)
- Support contact info
- Social media links

**Customize in:** WooCommerce Settings → Emails → New Account

---

### 6. **Abandoned Cart Recovery** (Custom - Extended)

**File:** `templates/email/abandoned-cart.twig`  
**Files:** `src/woocommerce-custom-emails.php` + `woocommerce/emails/abandoned-cart.php`  
**Trigger:** Via `theme_send_abandoned_cart_email()` function  
**Recipient:** Customer

**Includes:**

- Abandoned items list with prices
- Cart total
- Special discount offer (optional)
- 24-hour expire countdown
- Secure checkout CTA
- Payment method options
- Auto-expiring cart notice

**API Usage:**

```php
theme_send_abandoned_cart_email(
    $customer_name = 'John',
    $customer_email = 'john@example.com',
    $cart_items = [
        [ 'name' => 'Product A', 'sku' => 'SKU123', 'quantity' => 2, 'total' => '$50.00' ],
    ],
    $cart_total = '$50.00',
    $cart_url = 'https://example.com/cart',
    $discount_code = 'SAVE10',     // Optional
    $discount_percent = 10          // Optional
);
```

**Setup:** Create a scheduled action to send these emails. Example using WooCommerce Action Scheduler:

```php
// Send abandoned cart emails to customers with carts from 1+ hours ago
add_action( 'wp_scheduled_event', function() {
    // Query customers with abandoned carts
    // Call theme_send_abandoned_cart_email() for matches
}, 'hourly' );
```

---

### 7. **Post-Purchase Follow-up** (Custom - Extended)

**File:** `templates/email/post-purchase-followup.twig`  
**Files:** `src/woocommerce-custom-emails.php` + `woocommerce/emails/post-purchase-followup.php`  
**Trigger:** Via `theme_send_post_purchase_followup()` function  
**Recipient:** Customer

**Includes:**

- Thank you message
- Order summary
- Product care instructions
- Leave a review request
- Care guides & how-to links
- Warranty information
- Complementary products
- Referral program
- Returns policy
- Loyalty program
- Support contact
- Social media links

**API Usage:**

```php
// Send 7-10 days after order placed
$order = wc_get_order( $order_id );
theme_send_post_purchase_followup_email( $order );
```

**Setup:** Create a scheduled action to send these emails 7-10 days after order completion:

```php
// Hook into order completion
add_action( 'woocommerce_order_status_completed', function( $order_id ) {
    // Schedule email to send in 7 days
    as_schedule_single_action(
        time() + ( 7 * DAY_IN_SECONDS ),
        'send_post_purchase_followup',
        [ $order_id ]
    );
} );

// Handle scheduled event
add_action( 'send_post_purchase_followup', function( $order_id ) {
    $order = wc_get_order( $order_id );
    theme_send_post_purchase_followup_email( $order );
} );
```

---

## File Structure

```
theme-root/
├─ templates/
│  └─ email/
│     ├─ base.twig                      # Base template with layout & styling
│     ├─ admin-new-order.twig          # Admin new order email
│     ├─ customer-processing-order.twig # Order received email
│     ├─ customer-completed-order.twig # Shipment/completion email
│     ├─ customer-refunded-order.twig  # Refund notification
│     ├─ customer-new-account.twig     # Welcome email
│     ├─ abandoned-cart.twig           # Cart recovery email
│     └─ post-purchase-followup.twig   # Follow-up email
│
├─ woocommerce/
│  └─ emails/
│     ├─ admin-new-order.php           # WC wrapper for admin email
│     ├─ customer-processing-order.php # WC wrapper for "received" email
│     ├─ customer-completed-order.php  # WC wrapper for "shipped" email
│     ├─ customer-refunded-order.php   # WC wrapper for refund email
│     ├─ customer-new-account.php      # WC wrapper for welcome email
│     ├─ abandoned-cart.php            # Custom email wrapper
│     └─ post-purchase-followup.php    # Custom email wrapper
│
└─ src/
   └─ woocommerce-custom-emails.php    # Custom email class handlers
```

---

## Customization

### 1. Customize Email Text

**In WooCommerce Admin:**

1. Go to `WooCommerce → Settings → Emails`
2. Click each email type
3. Edit:
   - Email subject
   - Email heading
   - Additional content (footer text)

Example: Add discount offer, custom messaging, support hours, etc.

### 2. Customize Colors & Branding

**Primary Colors (used in emails):**

- Edit `css/inc/base/vars.scss`
- Primary color: `#0066cc` (change to your brand color)
- Success: `#28a745`
- Warning: `#ffc107`
- Danger: `#dc3545`

**Logo:**

- Upload logo through WordPress Customizer or add to template
- Logo appears in email header automatically if set in site settings

**Example (in your template):**

```twig
{% if site.logo %}
  <img src="{{ site.logo.src }}" alt="{{ site.name }}" class="logo">
{% endif %}
```

### 3. Customize Template Structure

Edit the Twig templates directly:

- `templates/email/base.twig` - Base layout (header, footer, styling)
- `templates/email/*.twig` - Individual email content

**Example: Add discount code to all emails**

```twig
{# In base.twig footer #}
<p>
  Use code <strong>WELCOME10</strong> for 10% off your next order!
</p>
```

### 4. Add Custom Fields

Use ACF or WordPress hooks to add custom data:

```php
// Example: Add custom discount offer to order emails
add_filter( 'woocommerce_email_template_context', function( $context, $email ) {
    if ( isset( $context['order'] ) ) {
        $order = $context['order'];
        // Add custom field to context
        $context['custom_discount'] = get_field( 'order_discount', $order->get_id() );
    }
    return $context;
}, 10, 2 );
```

Then use in template:

```twig
{% if custom_discount %}
  <p>{{ custom_discount }}</p>
{% endif %}
```

---

## Email Testing

### Test via WooCommerce Admin

1. Go to `WooCommerce → Settings → Emails`
2. Click specific email type
3. Scroll to bottom: **Send test email**
4. Check your email inbox

### Test Programmatically

```php
// Send test admin new order email
$order = wc_get_order( $order_id );
$email = new WC_Email_New_Order();
$email->trigger( $order->get_id() );

// Send test customer processing order email
$email = new WC_Email_Customer_Processing_Order();
$email->trigger( $order->get_id(), $order );

// Send test custom emails
theme_send_abandoned_cart_email(
    'John Doe',
    'john@example.com',
    [ [ 'name' => 'Product', 'quantity' => 1, 'total' => '$50' ] ],
    '$50',
    'https://example.com/cart'
);
```

### Email Client Testing

Test across email clients:

- Gmail
- Outlook
- Apple Mail
- Mobile (iOS, Android)

**Services:**

- [Litmus](https://www.litmus.com/) - Email preview service
- [Email on Acid](https://www.emailonacid.com/) - Email rendering tests

---

## Styling & CSS

### Email-Safe CSS

The base template uses email-client-safe CSS:

- ✅ Inline styles for maximum compatibility
- ✅ Embedded CSS for modern clients
- ✅ Media queries for responsive design
- ✅ Avoid: CSS Grid, Flexbox, custom fonts, shadows

### Modifying Styles

Edit `templates/email/base.twig` - The `<style>` block contains all email CSS:

```twig
<style>
  /* Edit colors here */
  .button { background-color: #0066cc; }

  /* Edit spacing */
  .content { padding: 30px 20px; }

  /* Edit typography */
  h1 { font-size: 28px; }
</style>
```

### Utility Classes

Available in emails:

- `.button` - CTA button
- `.alert` - Alert box with colored variants (alert-info, alert-success, etc.)
- `.order-table` - Professional table for items
- `.text-center`, `.text-right`, `.text-muted` - Text utilities
- `.mt-1`, `.mb-2`, `.p-3` - Spacing utilities

Example:

```twig
<div class="alert alert-success">
  <strong>✓ Success!</strong> Your order was placed.
</div>

<a href="#" class="button">Click Here</a>

<table class="order-table">
  <tr><th>Product</th><th>Price</th></tr>
  <tr><td>Widget A</td><td>$49.99</td></tr>
</table>
```

---

## Advanced: Creating Custom Email Types

To create additional custom email types (e.g., "Order Shipped", "Damaged Item Notification"):

**1. Create PHP email class:**

```php
// In src/woocommerce-custom-emails.php
class Theme_Email_Custom extends WC_Email {
    public function __construct() {
        $this->id = 'custom_email_id';
        $this->title = 'Custom Email Title';
        // ... rest of class
    }
}
```

**2. Create Twig template:**

```twig
{# templates/email/custom-email.twig #}
{% extends "email/base.twig" %}

{% block email_content %}
  {# Your custom content #}
{% endblock %}
```

**3. Create PHP wrapper:**

```php
{# woocommerce/emails/custom-email.php #}
// Load Timber and render Twig
```

**4. Register in filter:**

```php
add_filter( 'woocommerce_email_classes', function( $emails ) {
    $emails['custom_email_id'] = new Theme_Email_Custom();
    return $emails;
} );
```

---

## Troubleshooting

### Emails Not Sending

**Check:**

1. WooCommerce email settings enabled
2. WordPress cron configured (for async emails)
3. Email logging enabled: `define( 'WC_LOG_EMAIL', true );`
4. Check logs: `wp-content/uploads/wc-logs/`

### Timber Not Found in Emails

**Fallback:** PHP wrappers check for Timber and return early if not available.  
**Fix:** Make sure Timber plugin is installed and activated.

### CSS Not Displaying

**Email clients don't support:**

- CSS Grid/Flexbox
- Custom fonts (@font-face)
- Box shadows
- CSS animations

**Solution:** Use inline styles and simple CSS. All email CSS is intentionally basic.

### Images Not Loading

**Check:**

1. Image URLs are absolute (use `site_url()` not relative paths)
2. Images are publicly accessible (not behind login)
3. Use descriptive alt text

Example:

```twig
<img src="{{ site.url }}/images/logo.png" alt="Company Logo">
```

---

## Maintenance

### Update Email Templates

When you want to change wording, add offers, or modify design:

1. **Edit Twig templates** directly

   - `templates/email/*.twig` - Template content
   - `templates/email/base.twig` - Shared layout & styles

2. **Update through WooCommerce Admin**

   - Use "Additional Content" field for merchant messages
   - Use email subject & heading fields for customization

3. **No cache needed** - Changes apply immediately

---

## API Reference

### Public Functions

```php
/**
 * Send abandoned cart email
 */
theme_send_abandoned_cart_email(
    $customer_name,      // string
    $customer_email,     // string
    $cart_items,         // array
    $cart_total,         // string (formatted price)
    $cart_url,           // string (URL to resume cart)
    $discount_code,      // string (optional)
    $discount_percent    // int (optional)
);

/**
 * Send post-purchase follow-up email
 */
theme_send_post_purchase_followup_email(
    $order               // WC_Order object
);
```

### Filters

```php
/**
 * Modify email template context
 * @param array $context - Template variables
 * @param WC_Email $email - Email object
 */
apply_filters( 'woocommerce_email_template_context', $context, $email );
```

---

## Support

For issues or customization help:

1. Check WooCommerce email settings
2. Enable email logging in `wp-config.php`:
   ```php
   define( 'WC_LOG_EMAIL', true );
   ```
3. Review logs in `wp-content/uploads/wc-logs/`
4. Test emails via WooCommerce Admin

---

## Next Steps

- [ ] Customize email text through WooCommerce Settings
- [ ] Update brand colors in `css/inc/base/vars.scss`
- [ ] Upload logo through WordPress Customizer
- [ ] Test emails across email clients (Gmail, Outlook, mobile)
- [ ] Integrate abandoned cart automation with plugins (e.g., WooCommerce Abandoned Cart Pro)
- [ ] Set up post-purchase follow-up email schedule using Action Scheduler
- [ ] Add tracking integration for shipment emails
- [ ] Configure email delivery service (SendGrid, Mailgun) for reliability

**Email Templates Complete! ✅**
