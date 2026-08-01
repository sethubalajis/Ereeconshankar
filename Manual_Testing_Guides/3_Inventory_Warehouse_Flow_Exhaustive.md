# Exhaustive Manual Testing Guide: Inventory & Warehouse Flow (WMS)

## 1. Overview & Architecture Scope
The Warehouse Management System (WMS) tracks physical inventory across warehouses and retail branches. It handles multi-tier bin storage mapping, inward stock receipts (GRN), stock adjustments, real-time stock ledger entries, branch transfers, transit stock tracking, picking/packing dispatches, inventory counting, and reconciliation.

### Target Components & Resources:
- **Warehouse Structure:** Warehouses, Warehouse Zones, Racks, Bins, Bin Stocks
- **Stock Management:** Stocks, Stock Ledgers, Stock Adjustments, Stock Transactions, Stock Valuations, Stock Reservations
- **Transfers & Transit:** Branch Inventories, Transit Inventories, Stock Transfers, Warehouse Transfers, Transfer Requests/Approvals/Receipts/Items
- **Operations:** Goods Receipt Notes (GRNs), Picking Lists, Packing Lists, Inventory Counts, Inventory Reconciliations

---

## 2. Pre-requisites
1. Product Variants and Barcodes created (Flow 2).
2. Vendors configured (Flow 4).

---

## 3. Comprehensive Test Cases

### 3.1 Physical Storage Infrastructure

#### Test Case 1.1: 4-Level Storage Hierarchy Configuration
- **Objective:** Map physical location down to exact shelf bin.
- **Action 1:** Go to `Warehouses` -> Create `Central Distribution Center (CDC)`.
- **Action 2:** Go to `Warehouse Zones` -> Create `Zone A (Apparel Storage)`.
- **Action 3:** Go to `Racks` -> Create `Rack A1` in `Zone A`.
- **Action 4:** Go to `Bins` -> Create `Bin A1-01` in `Rack A1`.
- **Expected Result:** Full hierarchy created cleanly: CDC -> Zone A -> Rack A1 -> Bin A1-01.

---

### 3.2 Inward Stock & Stock Ledger

#### Test Case 2.1: Goods Receipt Note (GRN) Inward Stock Processing
- **Objective:** Receive inward supplier shipment and verify stock ledger increment.
- **Action:** Go to `Goods Receipt Notes (GRNs)` -> `Create`. Select Vendor `V001`, Destination `Central Distribution Center`. Add Item `RCS-RBLU-40`, Received Quantity `500 Pcs`, Unit Cost `1200.00`. Set Status `Approved`.
- **Expected Result 1 (Stock Ledger):** Navigate to `Stock Ledgers`. A new immutable entry is posted: Type `GRN`, Qty `+500`, Balance `500`.
- **Expected Result 2 (Bin Stock):** Navigate to `Bin Stocks`. `Bin A1-01` shows current stock `500`.

#### Test Case 2.2: Stock Adjustment (Damage / Defect Write-off)
- **Objective:** Manually adjust physical inventory with audit reason.
- **Action:** Go to `Stock Adjustments` -> `Create`. Select Warehouse `CDC`, Product `RCS-RBLU-40`. Adjustment Type `Decrease`, Qty `-10`, Reason `Water Damage during transit`. Save & Approve.
- **Expected Result:** Total stock for SKU reduces to `490`. Stock Ledger records entry `-10`.

---

### 3.3 Inter-Branch Transfer & Transit Lifecycle

#### Test Case 3.1: Transfer Workflow (Request -> Approval -> Dispatch -> Receipt)
- **Objective:** Safely transfer inventory from Central Warehouse to Retail Branch.
- **Step 1 (Request):** Log in as Branch Manager `Coimbatore`. Go to `Transfer Requests` -> Request `50 Pcs` of `RCS-RBLU-40` from `CDC`. Status = `Submitted`.
- **Step 2 (Approval):** Log in as Warehouse Supervisor. Go to `Transfer Approvals` -> Approve Request `TR-00045`.
- **Step 3 (Picking & Packing):** Go to `Picking Lists` -> Generate picking sheet. Go to `Packing Lists` -> Mark Packed & Dispatched. Status = `In Transit`.
  - **Expected Result (Transit Check):** Stock at `CDC` drops from `490` to `440`. Stock at `Coimbatore Branch` is NOT YET increased. `Transit Inventories` table displays `50 Pcs`.
- **Step 4 (Receipt):** Log in as Branch Storekeeper `Coimbatore`. Go to `Transfer Receipts` -> Receive shipment. Acknowledge `50 Pcs`.
  - **Expected Result (Final Realization):** Transit Inventory clears (`0 Pcs`). Branch Inventory at `Coimbatore Branch` increases by `+50 Pcs`.

---

### 3.4 Physical Inventory Audit & Reconciliation

#### Test Case 4.1: Inventory Count & Discrepancy Reconciliation
- **Objective:** Perform physical stock-taking and auto-adjust system variance.
- **Action 1 (Count):** Go to `Inventory Counts` -> Start physical audit for `Coimbatore Branch`. System expects `50 Pcs`. Physical Count entered as `48 Pcs` (2 units missing).
- **Action 2 (Reconcile):** Go to `Inventory Reconciliations` -> Review variance `-2 Pcs`. Supervisor approves reconciliation.
- **Expected Result:** System generates automatic stock ledger adjustment for `-2 Pcs`. Final verified branch stock updated to `48 Pcs`.
