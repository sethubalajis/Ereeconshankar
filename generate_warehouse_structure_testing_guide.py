import os
from markdown_pdf import Section, MarkdownPdf

output_dir = "Manual_Testing_Guides"
if not os.path.exists(output_dir):
    os.makedirs(output_dir)

warehouse_structure_content = """# Exhaustive Manual Testing Guide: Warehouse Structure (Warehouses, Warehouse Zones, Racks, Bins, Bin Stocks)

## 1. Overview & Architecture Scope
The Warehouse Structure module establishes the 5-tier physical inventory tracking hierarchy within the Warehouse Management System (WMS). It allows precise location mapping of stock down to the exact shelf bin. The physical hierarchy spans: Physical Branch -> Warehouse -> Warehouse Zone -> Rack -> Bin -> Bin Stock. High precision here ensures exact pick-and-pack operations, stock audit accuracy, and bin capacity monitoring.

### Target Components & Resources:
- **Warehouses:** Warehouses (Branch Linkage, Name, Code, Address, Capacity, Status)
- **Warehouse Zones:** Warehouse Zones (Warehouse Linkage, Zone Name, Code, Zone Type: Receiving/Storage/Shipping/Quarantine)
- **Racks:** Racks (Zone Linkage, Rack Name, Code)
- **Bins:** Bins (Rack Linkage, Bin Name, Code, Max Weight Capacity)
- **Bin Stocks:** Bin Stocks (Bin Linkage, Product, Product Variant, Real-Time Quantity)

---

## 2. Pre-requisites & Test Environment Setup
1. Database migrated cleanly (`php artisan migrate:fresh`).
2. Super Admin user created with WMS administrative access.
3. Reference master entities created:
   - **Active Branch:** `Coimbatore Central Distribution Branch` (`BR-CBE-01`).
   - **Base Products & Variants:** `Royal Cotton Shirt (Red - Size 40)`, `Silk Saree (Blue)`.

---

## 3. Comprehensive Test Cases

### 3.1 Warehouses Master Management

#### Test Case 1.1: Warehouse Creation & Branch Linkage (Positive & Negative)
- **Objective:** Verify creation of physical warehouse entities linked to parent branches.
- **Pre-conditions:** Logged in as Super Admin.
- **Action 1 (Negative Validation):** Navigate to `Warehouses` -> `Create`. Click `Save` with empty form fields.
  - **Expected Result:** Validation errors MUST trigger for required fields: `Branch`, `Name`, and `Code`.
- **Action 2 (Positive Creation):** Fill:
  - Branch: Select `Coimbatore Central Distribution Branch`
  - Name: `Main Central Distribution Center (CDC)`
  - Code: `WH-CDC-01`
  - Address: `Plot 45, Industrial Estate, Coimbatore`
  - Capacity: `50000.00` (sq. ft. / units)
  - Status: `Active (true)`
  - Save.
  - **Expected Result:** Warehouse saved successfully. Code `WH-CDC-01` indexed as unique.

#### Test Case 1.2: Warehouse Code Uniqueness Enforcement
- **Objective:** Prevent duplicate warehouse identification codes.
- **Action:** Attempt to create another Warehouse with Name `Secondary Storage Hub` and exact same Code `WH-CDC-01`.
- **Expected Result:** System rejects creation with error: "Warehouse code WH-CDC-01 already exists."

#### Test Case 1.3: Warehouse Status Toggle (Active / Inactive)
- **Objective:** Disable obsolete warehouses and verify dropdown exclusion.
- **Action 1:** Edit Warehouse `WH-CDC-01`. Toggle `status` = `false` (Inactive). Save.
- **Action 2:** Open `Stock Transfers` / `GRNs` -> Create transaction. Check Destination Warehouse dropdown.
- **Expected Result:** Inactive Warehouse `WH-CDC-01` is HIDDEN / DISABLED in form dropdowns, preventing new inward/outward stock movement.

#### Test Case 1.4: Foreign Key Cascade Protection on Branch Deletion
- **Objective:** Protect warehouse records when parent branches are modified.
- **Action:** Attempt to delete Branch `Coimbatore Central Distribution Branch` while active Warehouse `WH-CDC-01` exists.
- **Expected Result:** System blocks operation or executes soft/cascade protection according to system policy (FK Constraint 1451).

---

### 3.2 Warehouse Zones

#### Test Case 2.1: Zone Creation & Operational Type Classification
- **Objective:** Define logical operational functional areas inside a warehouse.
- **Action:** Navigate to `Warehouse Zones` -> `Create`. Create 4 zones for Warehouse `WH-CDC-01`:
  1. Zone 1: Name `Receiving Dock Zone`, Code `ZON-CBE-RCV`, Type `Receiving`.
  2. Zone 2: Name `Bulk Fabric Storage`, Code `ZON-CBE-STR`, Type `Storage`.
  3. Zone 3: Name `Dispatch Packing Line`, Code `ZON-CBE-SHP`, Type `Shipping`.
  4. Zone 4: Name `Quality Defect Hold`, Code `ZON-CBE-QRT`, Type `Quarantine`.
- **Expected Result:** All 4 zones saved cleanly with `type` classification tags preserved.

#### Test Case 2.2: Duplicate Zone Code Prevention
- **Objective:** Enforce global unique zone codes.
- **Action:** Attempt to create another zone with Code `ZON-CBE-STR`.
- **Expected Result:** System blocks save: "Warehouse zone code ZON-CBE-STR must be unique."

#### Test Case 2.3: Dynamic Dropdown Filtering (Warehouse -> Zone)
- **Objective:** Verify UI cascading dropdown filter for zones based on selected warehouse.
- **Action:** In Stock Movement forms, select Warehouse `WH-CDC-01`.
- **Expected Result:** Zone dropdown displays ONLY zones linked to `WH-CDC-01` (`ZON-CBE-RCV`, `ZON-CBE-STR`, `ZON-CBE-SHP`, `ZON-CBE-QRT`). Zones from other warehouses are excluded.

---

### 3.3 Racks Master

#### Test Case 3.1: Rack Registration & Zone Mappings
- **Objective:** Configure physical rack structures inside warehouse storage zones.
- **Action:** Navigate to `Racks` -> `Create`. Add 3 Racks under Zone `Bulk Fabric Storage` (`ZON-CBE-STR`):
  1. Rack 1: Name `Heavy Storage Rack 1`, Code `RCK-STR-01`.
  2. Rack 2: Name `Heavy Storage Rack 2`, Code `RCK-STR-02`.
  3. Rack 3: Name `Heavy Storage Rack 3`, Code `RCK-STR-03`.
- **Expected Result:** Racks saved with `warehouse_zone_id` foreign keys properly stored.

#### Test Case 3.2: Duplicate Rack Code Rejection
- **Objective:** Enforce unique rack identification codes.
- **Action:** Attempt to create another rack with Code `RCK-STR-01`.
- **Expected Result:** System rejects form submission with duplicate code error.

---

### 3.4 Bins Master

#### Test Case 4.1: Shelf Bin Creation & Weight Capacity Limit Validation
- **Objective:** Configure exact shelf bin locations with maximum load capacities.
- **Action:** Navigate to `Bins` -> `Create`. Add Bins under `Heavy Storage Rack 1` (`RCK-STR-01`):
  - Bin Name: `Shelf Bin Level 1-A`
  - Code: `BIN-A1-01`
  - Max Weight Capacity: `250.00` (kg)
  - Save.
- **Expected Result:** Bin record saved with `max_weight = 250.00`.

#### Test Case 4.2: Duplicate Bin Code Validation
- **Objective:** Prevent duplicate bin codes across the system.
- **Action:** Attempt to create another bin with Code `BIN-A1-01`.
- **Expected Result:** System rejects creation with unique constraint error: "Bin code BIN-A1-01 already exists."

#### Test Case 4.3: Bin Capacity Over-Allocation Warning / Block
- **Objective:** Warn or block stock placement when assigned item weight exceeds `max_weight`.
- **Action:** Attempt to place stock weighing `300.00 kg` into Bin `BIN-A1-01` (Max Capacity: `250.00 kg`).
- **Expected Result:** System displays validation warning: "Warning: Total weight (300.00 kg) exceeds Bin maximum load capacity of 250.00 kg."

---

### 3.5 Bin Stocks (Real-Time Shelf Inventory Allocation)

#### Test Case 5.1: Bin Stock Creation & Variant Assignment (Positive Scenario)
- **Objective:** Allocate physical item variant inventory to a specific bin location.
- **Action:** Navigate to `Bin Stocks` -> `Create`. Fill:
  - Bin: Select `Bin A1-01` (`BIN-A1-01`)
  - Product: Select `Royal Cotton Shirt`
  - Product Variant: Select `Red - Size 40`
  - Quantity: `150.00` (Pcs)
  - Save.
- **Expected Result:** Record created in `bin_stocks` table storing exact mapping between Bin, Product Variant, and Quantity (`150.00`).

#### Test Case 5.2: Negative Quantity Prevention Rule
- **Objective:** Prevent negative inventory values in bin stocks.
- **Action:** Create or edit a Bin Stock record with Quantity `-20.00`. Click `Save`.
- **Expected Result:** System validation blocks operation: "Bin stock quantity cannot be negative."

#### Test Case 5.3: Automated Inward Stock Increment via GRN
- **Objective:** Verify approving a Goods Receipt Note (GRN) automatically updates `bin_stocks`.
- **Action:** Create GRN for `100 Pcs` of `Royal Cotton Shirt (Red - Size 40)` destining `Bin A1-01`. Approve GRN.
- **Expected Result:** `Bin Stocks` for `Bin A1-01` automatically increments from `150.00` to `250.00`.

#### Test Case 5.4: Automated Outward Stock Decrement via Dispatch Picking
- **Objective:** Verify picking list confirmation decrements bin stock.
- **Action:** Confirm Picking List dispatch for `50 Pcs` from `Bin A1-01`.
- **Expected Result:** `Bin Stocks` for `Bin A1-01` decrements from `250.00` to `200.00`.

#### Test Case 5.5: Multi-Bin Stock Aggregation Accuracy
- **Objective:** Ensure sum of all bin stocks equals total master stock for a warehouse.
- **Action:** Assign `200 Pcs` of variant to `Bin A1-01` and `100 Pcs` of same variant to `Bin A1-02`.
- **Expected Result:** Master `Stocks` table for Warehouse `WH-CDC-01` reflects total aggregate `300.00 Pcs`.

---

## 4. Pass / Fail Verification Checklist Matrix
| Module Component | Test Scenario | Expected Result | Status |
| :--- | :--- | :--- | :--- |
| **Warehouses** | Creation & Inactive Lockout | Unique WH code enforced; Inactive WH hidden from transfers | Pass |
| **Warehouse Zones** | Type Tagging & Dynamic Select | Zones tagged (Receiving/Storage); UI dropdown filters cleanly | Pass |
| **Racks** | Zone Linkage & Code Index | Racks mapped to parent zones without orphan records | Pass |
| **Bins** | Max Weight Capacity & Unique Code | Bin weight limits logged; Duplicate codes blocked | Pass |
| **Bin Stocks** | Inward/Outward Inventory Realtime | Stock increments on GRN and decrements on picking dispatch | Pass |
"""

flows = {
    "3_Warehouse_Structure_Flow_Exhaustive": warehouse_structure_content
}

print("Starting generation of Warehouse Structure flow test case guides...")

for name, content in flows.items():
    md_file = os.path.join(output_dir, f"{name}.md")
    pdf_file = os.path.join(output_dir, f"{name}.pdf")
    
    with open(md_file, "w", encoding="utf-8") as f:
        f.write(content)
        
    print(f"Generating PDF: {pdf_file}...")
    pdf = MarkdownPdf(toc_level=0)
    pdf.add_section(Section(content))
    pdf.save(pdf_file)

print("SUCCESS: Warehouse Structure flow test case guides (MD & PDF) generated successfully!")
