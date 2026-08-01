# Exhaustive Manual Testing Guide: GST Taxation & Compliance Flow (India)

## 1. Overview & Architecture Scope
The GST Taxation & Compliance module enforces compliance with Indian Statutory Tax laws. It governs HSN/SAC code mapping, GST tax slab masters (0%, 5%, 12%, 18%, 28%), intra-state (CGST + SGST split) vs inter-state (IGST) tax calculation engines, B2B Tax Invoices, E-Invoicing (IRN), E-Way Bill thresholds, TDS under GST/Income Tax, and statutory return filing summaries (GSTR-1, GSTR-3B).

### Target Components & Resources:
- **GST Core:** GST Categories, GST Rates, HSN Codes, HSN/SAC Codes
- **Filing & Docs:** GST Returns, Tax Invoices, Tax Groups, E-Invoices, E-Way Bills
- **Deductions:** TDS Records, TCS Records

---

## 2. Pre-requisites
1. Company state location and customer/vendor GSTINs registered (Flow 1, Flow 4, Flow 6).

---

## 3. Comprehensive Test Cases

### 3.1 GST Master & HSN Mapping

#### Test Case 1.1: HSN Code & Tax Slab Linkage
- **Objective:** Map textile goods to legal statutory tax rates.
- **Action:** Go to `HSN/SAC Codes` -> Create HSN `6105` (Men's Woven Shirts). Map to `GST Rate = 5%` (CGST 2.5%, SGST 2.5%, IGST 5%). Assign HSN `6105` to product `Royal Cotton Formal Shirt`.
- **Expected Result:** Product linked to 5% tax bracket.

---

### 3.2 Tax Engine: Intra-State vs Inter-State Calculation

#### Test Case 2.1: Intra-State Tax Calculation (CGST + SGST Equal Split)
- **Objective:** Verify equal tax split when supplier and buyer are in the SAME state.
- **Pre-condition:** Company Branch located in `Tamil Nadu (State Code: 33)`.
- **Action:** Create B2B Sales Invoice for Customer located in `Tamil Nadu (State Code: 33)`. Add item `1,000.00 INR` (5% GST rate).
- **Expected Result Tax Engine Computation:**
  - Taxable Value = `1,000.00 INR`
  - CGST @ 2.5% = `25.00 INR`
  - SGST @ 2.5% = `25.00 INR`
  - IGST = `0.00 INR`
  - Total Invoice Grand Total = `1,050.00 INR`.

#### Test Case 2.2: Inter-State Tax Calculation (IGST Single Rate)
- **Objective:** Verify single IGST calculation when buyer is in a DIFFERENT state.
- **Action:** Create B2B Sales Invoice for Customer located in `Kerala (State Code: 32)`. Add item `1,000.00 INR` (5% GST rate).
- **Expected Result Tax Engine Computation:**
  - Taxable Value = `1,000.00 INR`
  - CGST = `0.00 INR`
  - SGST = `0.00 INR`
  - IGST @ 5.0% = `50.00 INR`
  - Total Invoice Grand Total = `1,050.00 INR`.

---

### 3.3 Statutory Documents & E-Invoicing / E-Way Bills

#### Test Case 3.1: Tax Invoice PDF Legal Compliance Format
- **Objective:** Verify mandatory statutory fields print on generated Tax Invoice PDFs.
- **Action:** Generate Tax Invoice PDF for B2B Sale.
- **Validation Checklist:** PDF MUST visually include:
  1. Title clearly stating "TAX INVOICE".
  2. Supplier Name, Address, State Code `33`, and GSTIN.
  3. Buyer Name, Address, State Code, and GSTIN.
  4. HSN Code column for each line item.
  5. Taxable value, CGST amount, SGST amount, IGST amount breakdown table.
  6. Amount in words.

#### Test Case 3.2: E-Way Bill Threshold Trigger Check
- **Objective:** Automatically flag transport invoices exceeding legal threshold.
- **Action 1 (Below Threshold):** Create out-of-state invoice of value `40,000.00 INR`.
  - **Expected Result:** E-Way Bill requirement status = `Not Required`.
- **Action 2 (Above Threshold):** Create out-of-state invoice of value `75,000.00 INR` (> Rs 50,000 threshold).
  - **Expected Result:** System flags `E-Way Bill Required`. Prompts for Transporter ID and Vehicle Number before finalizing dispatch.

---

### 3.4 Statutory Deductions (TDS / TCS)

#### Test Case 4.1: Vendor Service TDS Deduction (Section 194C / 194J)
- **Objective:** Deduct tax at source on vendor service bills.
- **Action:** Go to `Purchase Invoices` -> Enter Vendor Invoice for `Security Services` = `50,000.00 INR`. Apply `TDS Section 194C @ 2%`.
- **Expected Result:**
  - Gross Payable = `50,000.00 INR`
  - TDS Deducted (2%) = `1,000.00 INR`
  - Net Payable to Vendor = `49,000.00 INR`
  - `TDS Payable Ledger` credited `1,000.00 INR`.
