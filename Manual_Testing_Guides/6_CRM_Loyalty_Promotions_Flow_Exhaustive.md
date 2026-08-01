# Exhaustive Manual Testing Guide: CRM, Loyalty & Promotions Flow

## 1. Overview & Architecture Scope
This module drives customer retention, loyalty rewards, promotional campaigns, discount coupon rules, combo/BOGO offers, and customer grievance management.

### Target Components & Resources:
- **Customers:** Customers, Customer Groups, Customer Wallets, Customer Feedback
- **Loyalty System:** Loyalty Programs, Loyalty Points, Loyalty Cards, Membership Plans
- **Promotions:** Promo Codes, Coupons, Gift Vouchers, Discount Rules, Promotion Types
- **Special Offers:** Buy-X-Get-Y Offers, Combo Offers, Festival Offers
- **Support:** Complaint Tickets, Complaint Categories

---

## 2. Pre-requisites
1. Active Customers and POS Sales module configured (Flow 5).

---

## 3. Comprehensive Test Cases

### 3.1 Customer Profile & Grouping

#### Test Case 1.1: Customer Onboarding & Credit Limit Enforcement
- **Objective:** Manage customer profiles and wholesale credit boundaries.
- **Action 1:** Go to `Customers` -> Create Customer `Lakshmi Silks`, Category `Wholesale Customer`, Credit Limit `1,00,000.00 INR`.
- **Action 2 (Credit Violation):** Attempt to process credit sale of `1,20,000.00 INR` for `Lakshmi Silks`.
  - **Expected Result:** System blocks transaction: "Credit Limit Exceeded. Max credit allowed: Rs. 100,000."

---

### 3.2 Loyalty Points & Wallet Engine

#### Test Case 2.1: Automated Loyalty Point Earning Formula
- **Objective:** Verify points accrue based on purchase threshold rules.
- **Pre-condition:** Loyalty Program set: "Earn 1 Point per Rs 100 spent".
- **Action:** Perform POS sale of `4,500.00 INR` for Customer `Karthik`.
- **Expected Result:** Check `Loyalty Points` / `Customer Wallets`. Customer earns `45 Points` (`45.00 INR` value). Total wallet balance updated.

#### Test Case 2.2: Loyalty Point Redemption at POS Checkout
- **Objective:** Redeem accumulated wallet points to pay bill portion.
- **Action:** Perform POS sale of `1,000.00 INR` for `Karthik`. Select `Redeem 45 Points` (`45 INR` discount).
- **Expected Result:** Net Bill Payable = `955.00 INR`. Customer's remaining wallet point balance becomes `0`.

---

### 3.3 Promotion & Coupon Engine

#### Test Case 3.1: Buy-1-Get-1 (BOGO) Offer Automation
- **Objective:** Apply automatic discount when promo condition is met.
- **Pre-condition:** Offer Rule: "Buy 2 Polo Shirts, Get 1 Leather Belt FREE".
- **Action 1 (Condition Not Met):** Add 1 Polo Shirt and 1 Leather Belt to POS cart.
  - **Expected Result:** Belt charged at full MRP (`999.00`). No BOGO discount applied.
- **Action 2 (Condition Met):** Add 2 Polo Shirts and 1 Leather Belt to POS cart.
  - **Expected Result:** Belt price automatically discounted to `0.00 INR`. Receipt displays "BOGO Offer Applied".

#### Test Case 3.2: Single-Use Coupon Limit Verification
- **Objective:** Prevent customers from reusing single-use promo codes.
- **Action 1 (First Use):** Apply Promo Code `WELCOME200` (Rs 200 off) on Customer `Anitha` invoice.
  - **Expected Result:** Discount applied successfully.
- **Action 2 (Second Use Check):** Apply `WELCOME200` again on `Anitha` second invoice.
  - **Expected Result:** System rejects coupon: "Promo code WELCOME200 has already been used by this customer."

---

### 3.4 Complaint Support Lifecycle

#### Test Case 4.1: Customer Complaint Ticket Workflow
- **Objective:** Track customer service tickets from logging to resolution.
- **Action:** Go to `Complaint Tickets` -> Create Ticket for `Anitha`. Category: `Fabric Color Fading`. Priority: `Medium`. Assign to `QA Officer`. Update Status: `Open` -> `In Progress` -> `Resolved` (Replacement Issued).
- **Expected Result:** Full resolution timeline and audit logs preserved.
