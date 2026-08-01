# Comprehensive Manual Testing Guide: POS & Retail Sales

## 1. Overview
The most critical module for daily branch operations. Speed, accuracy, and offline-capability (if applicable) are key.

## 2. Detailed Test Cases

### Test Case 1: Shift & Register Management
**Objective:** Ensure daily cash control is strictly enforced.
- **Action (Opening):** Go to `Cash Openings`. Enter opening float (e.g., 2000 Rs). Note the timestamp and user.
- **Validation:** Try to perform a POS Sale *without* opening a register. The system MUST block the sale and alert the user to open a register first.
- **Action (Closing):** At the end of the shift, go to `Cash Closings`. Enter the physical cash counted.
- **Expected Result:** System compares physical cash vs (Opening + Cash Sales - Cash Refunds). It should highlight any shortage/excess.

### Test Case 2: The Core Billing Process
**Objective:** Ensure barcode scanning and billing is flawless.
- **Action:** Open `POS Sales`.
- **Action (Item Entry):** Scan a barcode (or manually select a Product Variant). Change quantity to 3.
- **Action (Pricing Check):** Ensure the unit price matches the defined MRP or active Price List.
- **Action (Customer):** Attach a Customer profile to the bill via mobile number search.
- **Action (Payment):** Proceed to checkout. Do a split payment (e.g., Total 1500: 500 Cash, 1000 UPI).
- **Expected Result:** Sale completes. A receipt is generated.
- **Database Verification:** Check `Stock Ledgers` -> Stock for that branch must decrease by 3. Check `CashBooks` -> Cash must increase by 500, Bank by 1000.

### Test Case 3: Sales Returns (Exchange/Refund)
**Objective:** Process customer returns safely.
- **Action:** Go to `POS Returns`. Enter the invoice number from Test Case 2.
- **Action:** Select 1 item to return. Choose refund method (Cash).
- **Expected Result:** Stock increases by 1. Cash register total decreases by the refund amount.

### Test Case 4: Discount & Tax Verifications
**Objective:** Ensure math is 100% accurate.
- **Action:** Create a sale for a 1000 Rs item. Apply a flat 10% line-item discount.
- **Validation:** Check the tax calculation. GST should be calculated on the discounted value (900 Rs), NOT the MRP (1000 Rs). Verify the final grand total is correct down to the decimal.
