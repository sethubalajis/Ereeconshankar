# Comprehensive Manual Testing Guide: Asset & Service Management

## 1. Overview
Tracks internal machinery (e.g., sewing machines, laptops) and customer service repairs.

## 2. Detailed Test Cases

### Test Case 1: Asset Registration and Allocation
**Objective:** Ensure hardware is tracked to physical locations.
- **Action:** Go to `Assets`. Register a 'Brother Sewing Machine S-7200'. Enter Serial Number, Purchase Price (50,000), Purchase Date.
- **Action:** Go to `Asset Allocations`. Allocate this machine to 'Branch 2'.
- **Validation:** Attempt to allocate the same machine to 'Branch 3' on the same date without returning it. System should prevent double-allocation.

### Test Case 2: Asset Depreciation
**Objective:** Verify automated financial write-downs.
- **Action:** Go to `Asset Depreciations`. Set rule: Straight Line, 10% per year. Run depreciation for the financial year end.
- **Expected Result:** The current book value of the machine drops to 45,000. An automated journal entry debits Depreciation Expense and credits Accumulated Depreciation.

### Test Case 3: Customer Service Request (Ticketing)
**Objective:** Verify repair tracking workflows.
- **Action:** Go to `Service Requests`. Create a ticket for a customer (e.g., 'Custom tailoring alteration'). Status = 'Logged'.
- **Action:** Go to `Service Assignments`. Assign the ticket to Technician A.
- **Action:** Log in as Technician A. Update the ticket status to 'In Progress'. Add notes regarding fabric required.
- **Action:** Once done, technician marks it as 'Completed'.
- **Expected Result:** The customer (if integrated via SMS/Email) is notified. The operations manager can see turnaround times in reports.

### Test Case 4: Warranty Claims
**Objective:** Track replacement processes with vendors.
- **Action:** Go to `Warranty Claims`. Select a broken asset that is still within the warranty date range.
- **Action:** Log the claim sent to the OEM/Vendor. Track status from 'Sent' to 'Replaced/Repaired'.
- **Expected Result:** Asset status updates to 'In Repair' preventing it from being allocated to branches until the claim is resolved.
