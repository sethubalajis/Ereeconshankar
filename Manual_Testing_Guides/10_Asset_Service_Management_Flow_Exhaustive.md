# Exhaustive Manual Testing Guide: Asset & Service Management Flow

## 1. Overview & Architecture Scope
The Asset & Service Management flow tracks internal physical assets (weaving machines, computers, generators, vehicles), asset allocation to branches, inter-branch asset transfers, periodic depreciation accounting write-downs, maintenance logs, asset disposals, customer service tickets, technician assignments, and OEM warranty claims.

### Target Components & Resources:
- **Assets:** Assets, Asset Categories, Asset Allocations, Asset Transfers
- **Asset Lifecycle:** Asset Depreciations, Asset Maintenances, Asset Disposals
- **Services:** Service Requests, Service Assignments, Technician Visits, Warranty Claims

---

## 2. Pre-requisites
1. Organization branches active (Flow 1).
2. Depreciation expense accounts set up in Chart of Accounts (Flow 7).

---

## 3. Comprehensive Test Cases

### 3.1 Asset Master & Branch Allocation

#### Test Case 1.1: Asset Registration & Serial Uniqueness
- **Objective:** Onboard physical machinery with asset Tag tracking.
- **Action 1:** Go to `Assets` -> `Create`. Name `Juki Industrial Sewing Machine Model X`, Serial No `JK-998877`, Purchase Price `65,000.00 INR`, Purchase Date `2026-04-01`. Save.
  - **Expected Result:** Asset logged with unique Asset Tag `AST-00109`.
- **Action 2 (Duplicate Serial Check):** Create another asset with duplicate serial `JK-998877`.
  - **Expected Result:** System blocks creation: "Serial number JK-998877 is already registered."

#### Test Case 1.2: Asset Branch Allocation & Double-Allocation Prevention
- **Objective:** Allocate hardware to a specific physical location.
- **Action 1:** Go to `Asset Allocations` -> Allocate `AST-00109` to `Coimbatore Branch`.
  - **Expected Result:** Asset status changes to `Allocated`.
- **Action 2 (Double Allocation Check):** Attempt to create another active allocation for `AST-00109` to `Chennai Branch` without returning it first.
  - **Expected Result:** System blocks operation: "Asset AST-00109 is currently allocated to Coimbatore Branch."

---

### 3.2 Asset Depreciation Engine & Disposal

#### Test Case 2.1: Automated Straight-Line Depreciation Engine
- **Objective:** Execute periodic financial asset value write-down.
- **Pre-condition:** Asset `AST-00109` value `60,000 INR`, Depreciation Rule: `Straight Line @ 10% per year`.
- **Action:** Go to `Asset Depreciations` -> Execute Depreciation Run for FY 2026-2027.
- **Expected Result 1 (Book Value Update):** Asset Net Book Value reduced by `6,000 INR` to `54,000 INR`.
- **Expected Result 2 (Accounting Journal):** Automated financial journal posted:
  - Debit: `Depreciation Expense A/c` = `6,000.00 INR`
  - Credit: `Accumulated Depreciation A/c` = `6,000.00 INR`.

#### Test Case 2.2: Asset Scrap Disposal & Profit/Loss Calculation
- **Objective:** Dispose scrap asset and record gain/loss on sale.
- **Action:** Go to `Asset Disposals` -> Dispose old computer asset (Book Value: `5,000 INR`) for Sale Price `2,000 INR Cash`.
- **Expected Result:** Asset marked `Disposed`. Journal entry debits Cash `2,000`, debits `Loss on Asset Disposal` `3,000`, credits Asset `5,000`.

---

### 3.3 Customer Service & Warranty Management

#### Test Case 3.1: Service Request & Field Technician Assignment Lifecycle
- **Objective:** Track customer garment alteration / repair requests.
- **Action:** Go to `Service Requests` -> Create request for Customer `Priya`. Description `Suit Alteration`. Go to `Service Assignments` -> Assign to Technician `Ramesh`. Update Status: `In Progress` -> `Completed`.
- **Expected Result:** Service job completed, turn-around time logged, customer notified.

#### Test Case 3.2: Vendor OEM Warranty Claim Tracking
- **Objective:** Process broken asset under supplier warranty.
- **Action:** Go to `Warranty Claims` -> Select broken machine asset under warranty. Log claim to OEM Supplier. Status = `Sent to Vendor`.
- **Expected Result:** Asset operational status updated to `In Maintenance / Under Warranty Claim`. Prevents re-allocation until claim resolution.
