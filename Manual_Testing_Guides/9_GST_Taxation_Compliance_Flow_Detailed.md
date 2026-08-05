# Comprehensive Manual Testing Guide: GST Taxation & Compliance (India)

## 1. Overview
Ensures legal compliance with Indian tax laws. Miscalculations here result in severe penalties.

## 2. Detailed Test Cases

### Test Case 1: HSN and Rate Integrity
**Objective:** Ensure master tax data cannot be entered incorrectly.
- **Action:** Go to `GST Rates`. Ensure fixed slabs (0, 5, 12, 18, 28) exist.
- **Action:** Go to `HSN/SAC Codes`. Create code '6105' (Men's Shirts) and map to 5% GST rate.
- **Action:** Go to `Products` and assign HSN '6105' to the Classic Polo Shirt.
- **Expected Result:** Changes cascade properly. The product is now officially a 5% tax item.

### Test Case 2: Intra-State vs Inter-State Tax Split (Crucial Test)
**Objective:** Verify the core engine that splits IGST vs CGST/SGST based on addresses.
- **Pre-requisite:** Ensure the Company HQ is set to State: 'Tamil Nadu' (Code 33).
- **Action (Intra-State):** Create a B2B Sale Invoice to a Customer whose address is also in 'Tamil Nadu'. Add the Polo Shirt (1000 Rs).
- **Expected Result:** The invoice MUST display: CGST 2.5% (25 Rs) and SGST 2.5% (25 Rs). Total = 1050 Rs. IGST must be 0.
- **Action (Inter-State):** Change the Customer's address to 'Kerala' (Code 32). Recalculate the invoice.
- **Expected Result:** The invoice MUST display: IGST 5% (50 Rs). CGST and SGST must be 0. Total = 1050 Rs.

### Test Case 3: Tax Invoice Generation (B2B)
**Objective:** Verify the legal document output.
- **Action:** Generate the PDF for the `Tax Invoice` created above.
- **Validation:** Visually inspect the PDF. It MUST contain:
  - Supplier GSTIN
  - Customer GSTIN
  - HSN Codes column for items
  - Distinct tax breakdown table (Taxable Value, CGST amount, SGST amount).
  - "Tax Invoice" written at the top.

### Test Case 4: TDS Calculation (If applicable)
**Objective:** Verify Tax Deducted at Source on vendor services.
- **Action:** Create a Purchase Invoice for 'Consulting Services' from a Vendor for 50,000 Rs.
- **Action:** Apply a 10% TDS rule in `TDS Records` against this transaction.
- **Expected Result:** The payable amount to the vendor reduces to 45,000. 5,000 is moved to a TDS Payable liability ledger automatically.
