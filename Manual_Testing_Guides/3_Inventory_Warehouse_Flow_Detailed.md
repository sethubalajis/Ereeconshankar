# Comprehensive Manual Testing Guide: Inventory & Warehouse Flow (WMS)

## 1. Overview
This flow manages physical stock movement, storage layouts, and stock reconciliation. Precision is critical as this dictates the valuation of the company.

## 2. Detailed Test Cases

### Test Case 1: Physical Infrastructure Configuration
**Objective:** Ensure the exact physical location of a product can be mapped.
- **Action:** Go to `Warehouses` -> Create 'Main Central Warehouse'.
- **Action:** Go to `Warehouse Zones` -> Create 'Zone A' linked to Main Central Warehouse.
- **Action:** Go to `Racks` -> Create 'Rack 1' linked to Zone A.
- **Action:** Go to `Bins` -> Create 'Bin 1A' linked to Rack 1.
- **Validation:** Look at the Bin list view. Ensure the relation chain (Warehouse -> Zone -> Rack -> Bin) displays correctly without N+1 query slowdowns.

### Test Case 2: Goods Receipt Note (GRN) - Inward Stock
**Objective:** Verify stock increments when goods arrive.
- **Action:** Go to `GRNs` -> `Create`.
- **Action:** Select a vendor, destination warehouse, and add items (select the Product Variants created in Flow 2). Enter a quantity of 100.
- **Action:** Save and 'Approve' or 'Submit' the GRN (if there is a status workflow).
- **Expected Result 1 (Stock Ledger):** Navigate to `Stock Ledgers`. You must see a new entry with Transaction Type 'GRN' for +100 quantity.
- **Expected Result 2 (Current Stock):** Navigate to `Stocks`. The current stock for this variant in the selected warehouse must be exactly 100.

### Test Case 3: Stock Adjustments (Loss/Damage)
**Objective:** Verify manual corrections to inventory.
- **Action:** Go to `Stock Adjustments` -> `Create`.
- **Action:** Select the same warehouse and variant. Set Adjustment Type to 'Decrease' (or enter -5 quantity) and Reason to 'Damaged'.
- **Expected Result:** The overall stock in `Stocks` should now be 95. A ledger entry for -5 should be visible.

### Test Case 4: Branch to Branch Transfer (Crucial Workflow)
**Objective:** Verify stock safely moves from a warehouse to a retail branch.
- **Action 1 (Initiate):** Go to `Warehouse Transfers` -> Create a transfer from 'Main Central Warehouse' to 'Retail Branch 1'. Add 20 units of the variant. Status = 'Pending'.
- **Action 2 (Approval):** Go to `Transfer Approvals` -> Approve the request.
- **Action 3 (Dispatch):** Go to `Picking Lists` and `Packing Lists` to simulate packing. Change transfer status to 'In Transit'.
- **Expected Result (Transit):** Stock at Warehouse decreases by 20. Stock at Branch is NOT YET increased. The 20 units must appear in `Transit Inventories`.
- **Action 4 (Receipt):** Go to `Transfer Receipts` (log in as a Branch User). Acknowledge receipt of the 20 units.
- **Expected Result (Final):** Transit inventory clears. Branch inventory increases by +20. Warehouse stock remains at 75.

### Test Case 5: Inventory Reconciliation
**Objective:** Verify physical stock-taking operations.
- **Action:** Go to `Inventory Counts` -> Start a stock take for the warehouse.
- **Action:** System shows expected stock is 75. Enter 'Physical Count' as 74.
- **Expected Result:** Go to `Inventory Reconciliations`. System should flag a discrepancy of -1 and automatically generate an adjustment entry to fix the system stock to 74 upon supervisor approval.
