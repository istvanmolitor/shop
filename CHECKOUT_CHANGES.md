# Checkout Flow Changes - Separate Payment and Billing Steps

## Summary
The checkout flow has been updated to separate payment method selection and billing address into two distinct steps.

## New Checkout Flow
1. **Kosár (Cart)** - Step 1/4
2. **Szállítási mód (Shipping)** - Step 2/4  
3. **Fizetési mód (Payment)** - Step 3/4 - NEW: Only payment method selection
4. **Számlázás (Billing)** - Step 4/4 - NEW: Billing address form
5. **Véglegesítés (Finalize)** - Step 5/4

## Files Created

### Controllers
- `packages/shop/src/Http/Controllers/ShopBillingController.php`
  - Handles billing address step
  - `show()` - Display billing form
  - `store()` - Save billing data to session

### Request Validation
- `packages/shop/src/Http/Requests/BillingStepRequest.php`
  - Validates billing address fields
  - Supports "same as shipping" option

### Views
- `packages/shop/resources/views/checkout/billing.blade.php`
  - Billing address form
  - Option to use shipping address
  - Step 3/4 in the wizard

## Files Modified

### Controllers
- `packages/shop/src/Http/Controllers/ShopPaymentController.php`
  - Removed billing address handling
  - Now only handles payment method selection
  - Redirects to billing step instead of finalize

- `packages/shop/src/Http/Controllers/ShopCheckoutController.php`
  - Updated `showFinalize()` to check for billing data

### Request Validation
- `packages/shop/src/Http/Requests/PaymentStepRequest.php`
  - Removed billing address validation rules
  - Now only validates payment method selection

### Routes
- `packages/shop/src/routes/web.php`
  - Added billing routes:
    - GET `/shop/checkout/billing` → `ShopBillingController@show`
    - POST `/shop/checkout/billing` → `ShopBillingController@store`

### Views
- `packages/shop/resources/views/checkout/payment.blade.php`
  - Removed billing address form
  - Now only displays payment method selection
  - Updated to redirect to billing step
  - Step number changed from 2/3 to 2/4

- `packages/shop/resources/views/checkout/finalize.blade.php`
  - Updated step number from 3/3 to 4/4
  - Updated back button to point to billing step

### Components
- `packages/shop/src/View/Components/CheckoutSteps.php`
  - Added 'billing' step to baseSteps array
  - Renumbered all steps (now 5 steps instead of 4)
  - Added billing route to default links

- `packages/shop/resources/views/components/checkout-steps.blade.php`
  - Changed grid from 4 columns to 5 columns

### Language Files
Updated step numbers and added billing step label in:
- `packages/shop/resources/lang/hu/common.php`
- `packages/shop/resources/lang/en/common.php`
- `packages/shop/resources/lang/de/common.php`

## Session Data Structure
The CheckoutService maintains the following in session:
```php
[
    'order_shipping_id' => int,
    'shipping_data' => array,
    'order_payment_id' => int,        // Set in payment step
    'billing' => array,                // Set in billing step
    'billing_same_as_shipping' => bool // Set in billing step
]
```

## Backward Compatibility
- Old single-page checkout route still exists: `/shop/checkout`
- Redirects to new wizard flow: `/shop/checkout/shipping`
- Old payment view saved as: `payment_old.blade.php`

## Testing Checklist
- [ ] Navigate through all 4 steps of checkout
- [ ] Payment method selection saves correctly
- [ ] Billing address form validates properly
- [ ] "Same as shipping" checkbox works on billing page
- [ ] Finalize page displays all data correctly
- [ ] Order placement works from finalize page
- [ ] Back navigation works correctly between steps
- [ ] Step indicator highlights current step
- [ ] Translations work in Hungarian, English, and German

## Notes
- Each step has its own controller for better separation of concerns
- All steps validate that previous steps are completed before displaying
- Session is used to maintain checkout state across steps
- All validation rules properly check for required fields

