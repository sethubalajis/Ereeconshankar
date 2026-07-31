# Comprehensive Manual Testing Guide: Procurement & Vendor Flow

## 1. Overview
Manages the supplier ecosystem and purchasing lifecycle.

## 2. Detailed Test Cases

### Test Case 1: Vendor Onboarding
**Objective:** Ensure supplier details and agreements are captured.
- **Action:** Go to `Vendors` -> Create a new Vendor. Fill GST details, address, and link to a `Vendor Category`.
- **Action:** Go to `Vendor Contacts` -> Add 2 contact persons for this vendor.
- **Validation:** Attempt to create another vendor with the same GST number. The system should throw a unique validation error.

### Test Case 2: The Procure-to-Pay Lifecycle
**Objective:** Test the full strict purchasing flow.
- **Step 1 (Requisition):** Log in as a Branch Manager. Go to `Purchase Requisitions`. Request 50 units of Fabric. Status = 'Submitted'.
- **Step 2 (RFQ):** Log in as Procurement Officer. Go to `RFQs`. Convert the requisition into an RFQ to 2 different vendors.
- **Step 3 (PO):** Vendor A replies with a better rate. Go to `Purchase Orders`. Create a PO linking to Vendor A. Add the 50 units and specific unit prices. 
- **Validation (PO Approval):** Ensure the PO cannot be 'Sent' to the vendor until authorized by a manager (status workflow).
- **Step 4 (GRN linkage):** (Cross-module check) Ensure the warehouse staff can create a GRN against this specific PO number, and the system pulls the items automatically.

### Test Case 3: Vendor Payments
**Objective:** Ensure financial tracking against vendors is accurate.
- **Action:** Go to `Vendor Payments`. Create a payment record for the PO above. Select payment mode (Bank Transfer), enter UTR number.
- **Expected Result:** The vendor's outstanding ledger balance should decrease by the payment amount.

### Test Case 4: Purchase Returns (Debit Notes)
**Objective:** Handle defective goods returned to suppliers.
- **Action:** Go to `Purchase Returns`. Select the Vendor and the specific PO. Return 5 units due to damage.
- **Expected Result 1:** Stock decreases by 5 units.
- **Expected Result 2:** A 'Debit Note' should be generated or the vendor's payable balance should be adjusted to reflect that we owe them less money.
