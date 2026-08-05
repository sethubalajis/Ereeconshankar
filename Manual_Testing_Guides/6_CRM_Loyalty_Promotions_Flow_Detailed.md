# Comprehensive Manual Testing Guide: CRM & Promotions

## 1. Overview
Manages marketing, retention, and customer satisfaction.

## 2. Detailed Test Cases

### Test Case 1: Customer Wallet & Loyalty Mechanics
**Objective:** Ensure points accrue and can be redeemed accurately.
- **Pre-requisite:** Create a `Loyalty Program` (Rule: Earn 1 point per 100 Rs spent, 1 point = 1 Rs value).
- **Action (Earn):** Perform a POS sale for 550 Rs for "Customer A".
- **Expected Result:** Check `Customer Wallets` or `Loyalty Points` table. Customer A should now have 5 points.
- **Action (Redeem):** Perform another POS sale for 200 Rs for Customer A. During payment, choose to redeem 5 loyalty points.
- **Expected Result:** Customer pays 195 Rs via Cash/Card. The 5 points are deducted from their wallet.

### Test Case 2: Complex Promotions (BOGO)
**Objective:** Ensure promotional engines trigger only when conditions are met.
- **Action:** Create a `Buy-X-Get-Y Offer`. Rule: Buy 2 'Classic Polo Shirts', Get 1 'Socks' Free. Valid only for today.
- **Action (Negative):** Add 1 Polo Shirt and 1 Socks to POS.
- **Expected Result:** No discount applied. Socks charged at full price.
- **Action (Positive):** Add 2 Polo Shirts and 1 Socks to POS.
- **Expected Result:** The price of the Socks line-item should instantly drop to 0.00. The invoice should state "BOGO Discount Applied".

### Test Case 3: Coupon Code Validation
**Objective:** Prevent coupon abuse.
- **Action:** Create a `Promo Code` (Code: 'WELCOME10', 10% off, Usage Limit: 1 per customer).
- **Action:** Customer A uses 'WELCOME10' on an invoice. Works perfectly.
- **Validation:** Customer A attempts to use 'WELCOME10' on a second invoice.
- **Expected Result:** System MUST throw an error: "Coupon usage limit exceeded for this customer".

### Test Case 4: Complaint Ticketing Workflow
**Objective:** Track customer grievances to resolution.
- **Action:** Go to `Complaint Tickets`. Create a ticket regarding 'Defective zipper'. Status = 'Open'.
- **Action:** Assign it to a staff member. Add a resolution note. Change status to 'Resolved'.
- **Expected Result:** Timeline of ticket states should be preserved.
