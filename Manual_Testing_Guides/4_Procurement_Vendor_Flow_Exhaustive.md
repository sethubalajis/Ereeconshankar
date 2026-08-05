# Exhaustive Manual Testing Guide: Procurement & Vendor Flow

## 1. Overview & Architecture Scope
The Procurement Flow manages the purchase lifecycle from vendor onboarding to bill settlement. It handles supplier ratings, contact roles, purchase requisitions, Request for Quotations (RFQs), purchase orders, GRN verification, purchase returns (debit notes), vendor payments, and performance scorecards.

### Target Components & Resources:
- **Suppliers:** Vendors, Vendor Categories, Vendor Contacts, Vendor Agreements, Vendor Price Lists
- **Purchasing Cycle:** Purchase Requisitions, Request for Quotations (RFQs), Purchase Orders, Purchase Order Items, Purchase Returns
- **Vendor Management:** Vendor Payments, Vendor Performances

---

## 2. Pre-requisites
1. Organization setup complete (Flow 1).
2. Financial Chart of Accounts configured (Flow 7).

---

## 3. Comprehensive Test Cases

### 3.1 Supplier Master & Onboarding

#### Test Case 1.1: Vendor Creation & Duplicate GSTIN Validation
- **Objective:** Verify onboarding of suppliers and unique GSTIN constraints.
- **Action 1 (Positive):** Go to `Vendors` -> `Create`. Name: `Southern Cotton Mills Ltd`. Category: `Fabric Manufacturers`. GSTIN: `33AAACS1234F1Z2`. PAN: `AAACS1234F`. Payment Terms: `Net 30 Days`. Save.
  - **Expected Result:** Vendor saved cleanly.
- **Action 2 (Duplicate Check):** Create another vendor with duplicate GSTIN `33AAACS1234F1Z2`.
  - **Expected Result:** System blocks save: "GSTIN already registered with Southern Cotton Mills Ltd."

#### Test Case 1.2: Vendor Contacts & Agreement Attachments
- **Objective:** Link key personnel and legal agreement contracts.
- **Action:** Go to `Vendor Contacts` -> Add Sales Manager `Mr. S. Raman`, Phone `9876543210`. Go to `Vendor Agreements` -> Upload signed NDA & Supply Agreement PDF.
- **Expected Result:** Contact and documents stored and accessible from vendor profile.

---

### 3.2 Procure-to-Pay (P2P) Lifecycle

#### Test Case 2.1: Purchase Requisition (PR) Creation
- **Objective:** Initiate internal purchasing demand.
- **Action:** Go to `Purchase Requisitions` -> `Create`. Requested By: `Production Head`. Add Item `Egyptian Cotton Yarn`, Quantity `1000 Kg`. Priority: `High`. Status: `Submitted`.
- **Expected Result:** Requisition logged and routed for procurement review.

#### Test Case 2.2: RFQ Bidding & Quote Comparison
- **Objective:** Solicit quotes from multiple vendors.
- **Action:** Go to `Request for Quotations (RFQs)` -> Convert PR to RFQ. Send to `Southern Cotton Mills` and `Western Yarns Ltd`. Log responses:
  - Vendor A Quote: `350.00 / Kg`
  - Vendor B Quote: `340.00 / Kg`
- **Expected Result:** System highlights Vendor B as lowest bidder (L1).

#### Test Case 2.3: Purchase Order (PO) Approval Workflow
- **Objective:** Generate binding PO and enforce authorization limits.
- **Action 1 (Create PO):** Go to `Purchase Orders` -> Create PO for Vendor B (`Western Yarns Ltd`). Total Amount: `3,40,000.00`. Status = `Draft`.
- **Action 2 (Approval Guardrail):** Try to send PO to vendor without manager signature.
  - **Expected Result:** System blocks action: "Purchase Orders above Rs. 1,000,000 require Purchase Manager approval."
- **Action 3 (Manager Approve):** Log in as Purchase Manager -> Click `Approve`. Status updates to `Issued`.

---

### 3.3 Goods Receipt Linkage & Vendor Settlement

#### Test Case 3.1: GRN Verification against PO
- **Objective:** Ensure incoming warehouse GRN matches PO quantities.
- **Action:** Go to `GRNs` -> Create GRN referencing PO `PO-2026-0089`. Auto-fill line items.
- **Expected Result:** Items and quantities pre-populated automatically from PO.

#### Test Case 3.2: Purchase Return & Debit Note Generation
- **Objective:** Handle rejected supplier materials.
- **Action:** Go to `Purchase Returns` -> Select PO `PO-2026-0089`. Return `50 Kg` defective yarn.
- **Expected Result 1:** Stock reduced by `50 Kg`.
- **Expected Result 2:** Automated `Debit Note` generated in Accounts Payable reducing balance by `17,000.00`.

#### Test Case 3.3: Vendor Payment Settlement
- **Objective:** Process bank payment against supplier invoice.
- **Action:** Go to `Vendor Payments` -> Select Vendor `Western Yarns Ltd`. Payment Amount: `3,23,000.00`. Mode: `NEFT/Bank Transfer`. Enter UTR `HDFCN26109988`.
- **Expected Result:** Vendor outstanding balance updated to `0.00`. Payment voucher posted to general ledger.
