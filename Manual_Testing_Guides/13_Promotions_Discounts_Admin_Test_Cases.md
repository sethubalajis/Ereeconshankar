# Manual Test Case Document: Module 13 - Promotions & Discounts

## 1. Purpose
This document provides detailed manual test cases for all features under the admin module "13. Promotions & Discounts" available at http://127.0.0.1:8000/admin.

It covers end-to-end testing of:
- Coupons
- Promo Codes
- Discount Rules
- Buy X Get Y Offers
- Combo Offers
- Festival Offers
- Price Lists
- Promotion-related validation and workflow behavior

---

## 2. Test Objective
Verify that each promotion feature can be created, edited, viewed, disabled, and used correctly from the admin side and that the workflow behaves properly for manual business validation.

---

## 3. Test Environment / Pre-requisites
1. Application is running at http://127.0.0.1:8000/admin.
2. Admin user is logged in.
3. At least one product, product variant, and customer exist.
4. Promotion-related master data such as customer groups, membership plans, and products are available.
5. Browser is ready and test data is prepared.

---

## 4. General Test Instructions
- Use a fresh test record for each test case where possible.
- Record the date, time, and result for every test.
- Capture screenshots of successful creation, validation errors, and edit/delete actions.
- Verify both UI behavior and stored data after each action.

---

## 5. Detailed Test Cases

### 5.1 Coupons

#### TC-COU-001: Create Coupon with Percentage Discount
- Objective: Verify a coupon can be created successfully with a percentage discount.
- Steps:
  1. Go to Module 13 -> Coupons.
  2. Click Create.
  3. Enter code: `WELCOME10`.
  4. Enter discount percentage: `10`.
  5. Enter min purchase amount: `1000`.
  6. Enter max usage: `5`.
  7. Set status to Active.
  8. Save.
- Expected Result:
  - Coupon is created successfully.
  - The record appears in the coupon list.
  - The created coupon shows correct values.

#### TC-COU-002: Create Coupon with Fixed Amount Discount
- Objective: Verify fixed amount coupon creation.
- Steps:
  1. Open Coupons.
  2. Create a coupon with code `FLAT200`.
  3. Enter discount amount `200`.
  4. Set min purchase amount `1500`.
  5. Save.
- Expected Result:
  - The coupon is saved successfully.
  - The amount-based discount is displayed correctly.

#### TC-COU-003: Edit Existing Coupon
- Objective: Verify editing works without data loss.
- Steps:
  1. Open an existing coupon.
  2. Change the discount percentage or amount.
  3. Save.
- Expected Result:
  - The updated values are saved.
  - The list view reflects the changes.

#### TC-COU-004: Disable Coupon
- Objective: Verify a coupon can be disabled.
- Steps:
  1. Open an existing coupon.
  2. Change Status to Inactive/Disabled.
  3. Save.
- Expected Result:
  - The coupon is disabled successfully.
  - The record remains visible but inactive.

#### TC-COU-005: Validate Coupon Required Fields
- Objective: Verify validation for missing required values.
- Steps:
  1. Attempt to create a coupon without code or minimum purchase.
  2. Save.
- Expected Result:
  - Validation errors are shown.
  - Record is not created.

---

### 5.2 Promo Codes

#### TC-PC-001: Create Promo Code
- Objective: Verify promo code creation.
- Steps:
  1. Go to Promo Codes.
  2. Click Create.
  3. Verify the code field is prefilled or generated.
  4. Enter discount percentage or amount.
  5. Set valid from and valid to dates.
  6. Save.
- Expected Result:
  - Promo code is created successfully.
  - The new record is listed in the table.

#### TC-PC-002: Edit Promo Code Validity Dates
- Objective: Verify date-based validity can be modified.
- Steps:
  1. Open an existing promo code.
  2. Change valid dates.
  3. Save.
- Expected Result:
  - Dates update correctly.
  - The record remains available or unavailable based on the new date range.

#### TC-PC-003: Disable Promo Code
- Objective: Verify promo code deactivation.
- Steps:
  1. Open a promo code.
  2. Set status to inactive.
  3. Save.
- Expected Result:
  - The promo code is deactivated successfully.

#### TC-PC-004: Validate Promo Code Form Rules
- Objective: Confirm invalid values are prevented.
- Steps:
  1. Try saving without required fields.
  2. Save with invalid numeric values.
- Expected Result:
  - Validation messages appear.
  - Record is not saved.

---

### 5.3 Discount Rules

#### TC-DR-001: Create Percentage-Based Discount Rule
- Objective: Verify discount rule creation with a percentage discount.
- Steps:
  1. Go to Discount Rules.
  2. Click Create.
  3. Select a promotion type.
  4. Enter name and code.
  5. Enter percentage discount.
  6. Enter min purchase amount.
  7. Save.
- Expected Result:
  - Rule is created successfully.
  - Percentage field remains visible and is saved.

#### TC-DR-002: Create Value-Based Discount Rule
- Objective: Verify discount rule creation with a fixed value discount.
- Steps:
  1. Create a new discount rule.
  2. Select a promotion type configured for fixed value.
  3. Enter a fixed amount discount.
  4. Save.
- Expected Result:
  - Fixed amount field is shown and saved.

#### TC-DR-003: Edit Discount Rule
- Objective: Ensure discount rules can be updated.
- Steps:
  1. Open an existing discount rule.
  2. Change the name or discount value.
  3. Save.
- Expected Result:
  - Updated values are recorded.

#### TC-DR-004: Disable Discount Rule
- Objective: Confirm a discount rule can be disabled.
- Steps:
  1. Open a discount rule.
  2. Set status to inactive.
  3. Save.
- Expected Result:
  - Rule becomes inactive and is no longer active for use.

---

### 5.4 Buy X Get Y Offers

#### TC-BXY-001: Create Buy X Get Y Offer
- Objective: Verify BOGO-style offer creation.
- Steps:
  1. Go to Buy X Get Y Offers.
  2. Click Create.
  3. Select a buy product.
  4. Select a get product.
  5. Set buy quantity and get quantity.
  6. Save.
- Expected Result:
  - Offer is created successfully.
  - It appears in the list with the selected products and quantities.

#### TC-BXY-002: Edit Buy X Get Y Offer
- Objective: Verify quantity changes are saved.
- Steps:
  1. Open an existing BXY offer.
  2. Change the buy/get quantities.
  3. Save.
- Expected Result:
  - Updated values are stored correctly.

#### TC-BXY-003: Disable Buy X Get Y Offer
- Objective: Verify deactivation of the offer.
- Steps:
  1. Open a BXY offer.
  2. Set status to inactive.
  3. Save.
- Expected Result:
  - Offer is disabled successfully.

---

### 5.5 Combo Offers

#### TC-CO-001: Create Combo Offer
- Objective: Verify combo offer creation.
- Steps:
  1. Go to Combo Offers.
  2. Click Create.
  3. Enter name and code.
  4. Enter total combo price.
  5. Save.
- Expected Result:
  - Combo offer is created and listed.
  - The total price is displayed correctly.

#### TC-CO-002: Edit Combo Offer
- Objective: Verify price changes are saved.
- Steps:
  1. Open an existing combo offer.
  2. Change the total price.
  3. Save.
- Expected Result:
  - Updated price is saved.

#### TC-CO-003: Disable Combo Offer
- Objective: Verify combo offer deactivation.
- Steps:
  1. Open a combo offer.
  2. Change status to inactive.
  3. Save.
- Expected Result:
  - The combo offer is disabled successfully.

---

### 5.6 Festival Offers

#### TC-FO-001: Create Festival Offer
- Objective: Verify festival offer creation.
- Steps:
  1. Go to Festival Offers.
  2. Click Create.
  3. Enter name.
  4. Set start and end dates.
  5. Enter discount percentage.
  6. Save.
- Expected Result:
  - The festival offer is created successfully.
  - The discount percentage and date range are stored correctly.

#### TC-FO-002: Edit Festival Offer Date Range
- Objective: Ensure date range updates work.
- Steps:
  1. Open an existing festival offer.
  2. Change the dates.
  3. Save.
- Expected Result:
  - Updated date values are saved.

#### TC-FO-003: Disable Festival Offer
- Objective: Verify festival promotion deactivation.
- Steps:
  1. Open an existing festival offer.
  2. Set status to inactive.
  3. Save.
- Expected Result:
  - The offer is disabled successfully.

---

### 5.7 Price Lists

#### TC-PL-001: Create Price List
- Objective: Verify price list creation.
- Steps:
  1. Go to Price Lists.
  2. Click Create.
  3. Enter name and code.
  4. Set valid from and valid to dates.
  5. Save.
- Expected Result:
  - Price list is created successfully.
  - The record is listed with correct validity dates.

#### TC-PL-002: Edit Price List
- Objective: Verify updates to price list dates and name.
- Steps:
  1. Open an existing price list.
  2. Change the valid dates or name.
  3. Save.
- Expected Result:
  - Changes are saved and displayed.

#### TC-PL-003: Disable Price List
- Objective: Verify deactivation works.
- Steps:
  1. Open a price list.
  2. Set status to inactive.
  3. Save.
- Expected Result:
  - The price list is disabled successfully.

---

## 6. End-to-End Business Flow Testing

### TC-E2E-001: Create and Apply a Coupon in a Promotion Workflow
- Objective: Validate the complete flow from admin configuration to promotion availability.
- Steps:
  1. Create a coupon in the admin module.
  2. Make sure it is active and has a valid date range.
  3. Verify the coupon appears in the promotion selection area during a sales workflow.
  4. Apply it and confirm the discount is reflected.
- Expected Result:
  - The promotion is available and works as designed.

### TC-E2E-002: Create and Apply a Promo Code in a Promotion Workflow
- Objective: Validate promo code availability from admin setup to checkout.
- Steps:
  1. Create a promo code.
  2. Activate it.
  3. Verify it can be selected and applied.
- Expected Result:
  - Promo code behaves correctly in the workflow.

### TC-E2E-003: Create and Validate Combo / BOGO Promotions
- Objective: Check combined promotional logic.
- Steps:
  1. Create a BXY offer and a combo offer.
  2. Ensure the conditions match the cart scenario.
  3. Validate the promotion applies only when the conditions are met.
- Expected Result:
  - Offers appear and apply correctly based on business rules.

---

## 7. Negative / Error Handling Tests

### TC-NEG-001: Save Form with Missing Required Values
- Objective: Ensure validation is enforced.
- Steps:
  1. Try to save a coupon/promo code/discount rule without required values.
- Expected Result:
  - Validation errors appear.
  - No invalid record is saved.

### TC-NEG-002: Use Expired or Inactive Promotion
- Objective: Verify expired or disabled promotions are not used.
- Steps:
  1. Create a promotion with an expired date or inactive status.
  2. Attempt to use it.
- Expected Result:
  - The promotion is not applied.

### TC-NEG-003: Invalid Numeric Values
- Objective: Validate numeric input handling.
- Steps:
  1. Enter negative or invalid values in discount fields.
- Expected Result:
  - The system rejects invalid input.

---

## 8. Exit Criteria
The module is considered fully validated when:
- All promotion resources can be created and edited successfully.
- Active promotions behave correctly.
- Inactive/expired promotions are handled properly.
- Validation and error handling work as expected.
- End-to-end promotion workflow is verified manually.
