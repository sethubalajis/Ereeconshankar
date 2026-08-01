# Exhaustive Manual Testing Guide: POS & Retail Sales Flow

## 1. Overview & Architecture Scope
The Point of Sale (POS) flow governs front-end retail billing operations at branch locations. High performance, cashier shift integrity, barcode scanning speed, payment gateways, tax calculation accuracy, split payments, and shift cash reconciliation are critical.

### Target Components & Resources:
- **POS Infrastructure:** POS Terminals, POS Counters
- **Sales Cycle:** POS Sales, POS Sale Items, POS Returns
- **Cash Management:** Cash Registers, Cash Openings, Cash Closings, Cash Books
- **Transactions:** Payments, Receipts, Payment Modes, Quotations

---

## 2. Pre-requisites
1. Active Retail Branch with Product Variants and Barcodes (Flow 2).
2. Cashier user assigned to active branch (Flow 1).

---

## 3. Comprehensive Test Cases

### 3.1 Terminal Infrastructure & Shift Cash Control

#### Test Case 1.1: POS Terminal & Counter Mapping
- **Objective:** Set up physical hardware billing terminals.
- **Action:** Go to `POS Terminals` -> Create Terminal `POS-CBE-01`, Mac Address `00:1A:2B:3C:4D:5E`. Map to `Counter 1` at `Coimbatore Branch`.
- **Expected Result:** Terminal active and linked to branch.

#### Test Case 1.2: Shift Cash Opening & Register Guardrail
- **Objective:** Block sales until cashier explicitly opens register float.
- **Action 1 (Negative Checkout Block):** Log in as Cashier `Ravi`. Open POS Interface. Attempt to scan item and click Checkout WITHOUT opening cash register.
  - **Expected Result:** System blocks operation with modal alert: "Cash Register is CLOSED. Perform Cash Opening first."
- **Action 2 (Cash Opening):** Go to `Cash Openings` -> Enter Opening Float `2,000.00 INR`. Confirm.
  - **Expected Result:** Register status changes to `OPEN`. Timestamp and Cashier ID logged.

---

### 3.2 Core Billing & Multi-Payment Checkout

#### Test Case 2.1: High-Speed Barcode Scanning & Item Lookup
- **Objective:** Verify instant cart loading via barcode input.
- **Action:** In POS interface, input barcode `8901234567890`. Hit Enter.
- **Expected Result:** Product `Royal Cotton Formal Shirt (Size 40, Royal Blue)` added to cart instantly. Unit Price `1,999.00` fetched from price list.

#### Test Case 2.2: Customer Profile Attachment & Loyalty Lookup
- **Objective:** Link customer via mobile number search.
- **Action:** Search customer mobile `9840012345`. Select `Karthik Raja`.
- **Expected Result:** Customer linked to bill. Current loyalty point balance (`120 Points`) displayed on POS screen.

#### Test Case 2.3: Discount Floor Guardrail Enforcement
- **Objective:** Prevent cashiers from giving unauthorized excessive discounts.
- **Action:** Apply line discount of `50%` to item.
- **Expected Result:** System blocks discount: "Line discount exceeds maximum cashier limit (Max 20%). Manager override PIN required."

#### Test Case 2.4: Split Payment Processing (Cash + UPI + Credit Card)
- **Objective:** Accept multiple payment modes for a single invoice.
- **Action:** Grand Total = `3,500.00 INR`. Click `Split Payment`:
  - Payment Mode 1: Cash = `1,000.00`
  - Payment Mode 2: UPI / QR Code = `1,500.00` (Enter Ref: `UPI998877`)
  - Payment Mode 3: Credit Card = `1,000.00` (Enter Approval Code: `AUTH4455`)
  - Click `Complete Sale`.
- **Expected Result 1:** Invoice saved. POS receipt printed.
- **Expected Result 2 (Stock Ledger):** Branch stock reduced by cart items.
- **Expected Result 3 (Cash Book):** Cash Account credited `+1,000`, Bank Account credited `+2,500`.

---

### 3.3 Returns & End-of-Day (EOD) Reconciliation

#### Test Case 3.1: POS Return & Cash Refund Adjustment
- **Objective:** Process customer item exchange/return against original invoice.
- **Action:** Go to `POS Returns` -> Enter Invoice No `POS-2026-00412`. Select returned shirt (`1,999.00`). Reason: `Size Fit Issue`. Select Refund Mode `Cash`.
- **Expected Result:** Return slip issued. Branch stock incremented by `+1`. Cash drawer expected float reduced by `1,999.00`.

#### Test Case 3.2: Shift Cash Closing & Shortage Discrepancy Audit
- **Objective:** Audit physical cash vs system ledger totals at shift end.
- **Action:** At shift end, go to `Cash Closings`.
  - System Calculation: Opening (`2,000`) + Cash Sales (`1,000`) - Cash Refund (`1,999`) = Expected Cash `1,001.00`.
  - Enter Physical Cash Counted: `990.00` (Shortage of 11 INR).
- **Expected Result:** Shift closed. System logs variance `-11.00 INR` and posts discrepancy alert to Branch Manager dashboard.
