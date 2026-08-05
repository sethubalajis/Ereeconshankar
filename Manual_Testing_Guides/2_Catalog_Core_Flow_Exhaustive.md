# Exhaustive Manual Testing Guide: Catalog Core (Products, Product Categories, Product Sub-Categories, Product Lines, Brands)

## 1. Overview & Architecture Scope
The Catalog Core module serves as the central taxonomy foundation of the Product Information Management (PIM) system in the Textile ERP. It structures product classifications across 5 core entities: Brands, Categories, Sub-Categories, Product Lines, and Base Products. High data integrity here ensures accurate inventory management, procurement line items, POS billing, price list resolutions, and GST tax applications across the entire enterprise.

### Target Components & Resources:
- **Brand Master:** Brands (Name, Code, Logo, Description, Status)
- **Category Taxonomy:** Product Categories (Parent-Child Hierarchy, Code, Image, Status)
- **Sub-Category Taxonomy:** Product Sub-Categories (Category Linkage, Code, Description, Status)
- **Product Lines:** Product Lines (Line Name, Code, Segment Description, Status)
- **Product Master:** Products (Name, Code, Brand, Category, Sub-Category, Unit, HSN Code, Tax Group, Base Price, Active Status)

---

## 2. Pre-requisites & Test Environment Setup
1. Database migrated cleanly (`php artisan migrate:fresh`).
2. Super Admin user created with full PIM catalog permissions.
3. Base reference data seeded:
   - **Product Units:** `Pcs`, `Meters`, `Kg`, `Box`.
   - **HSN Codes:** `6105` (5% GST), `5208` (5% GST), `6203` (12% GST).
   - **Tax Groups:** `GST 5%`, `GST 12%`, `GST 18%`.

---

## 3. Comprehensive Test Cases

### 3.1 Brand Master Management

#### Test Case 1.1: Brand Creation & Logo Upload (Positive & Negative)
- **Objective:** Verify creation of manufacturer brand records with logo asset storage.
- **Pre-conditions:** Logged in as Super Admin.
- **Action 1 (Negative Validation):** Navigate to `Brands` -> `Create`. Click `Save` with empty form fields.
  - **Expected Result:** Validation errors MUST trigger for required fields: `Name` and `Code`.
- **Action 2 (Positive Creation):** Fill Name `Royal Silk Mills`, Code `BRD-RSM-001`, Description `Premium silk & linen fabrics`. Upload brand logo (`royal_silk_logo.png`, 300x300px). Click `Save`.
  - **Expected Result:** Brand record created successfully. Logo image stored in public storage path. Brand code unique index confirmed.

#### Test Case 1.2: Brand Code Uniqueness Enforcement
- **Objective:** Prevent duplicate brand identification codes in the database.
- **Action:** Attempt to create another Brand with Name `Royal Silk Heritage` and exact same Code `BRD-RSM-001`. Click `Save`.
- **Expected Result:** System rejects form submission with error: "Brand code BRD-RSM-001 already exists in the system."

#### Test Case 1.3: Brand Active/Inactive Status Toggle
- **Objective:** Deactivate brands and ensure inactive brands are restricted during product creation.
- **Action 1:** Edit Brand `Royal Silk Mills`. Toggle `status` = `false` (Inactive). Save.
- **Action 2:** Open `Products` -> `Create Product`. Click the `Brand` selection dropdown.
- **Expected Result:** Inactive Brand `Royal Silk Mills` is either hidden or marked disabled in dropdown, preventing new product assignment.

#### Test Case 1.4: Foreign Key Protection on Brand Deletion
- **Objective:** Block deletion of Brands that have linked active products.
- **Pre-condition:** Brand `Royal Silk Mills` assigned to active product `RCS-001`.
- **Action:** Go to `Brands` -> Select `Royal Silk Mills` -> Click `Delete`.
- **Expected Result:** System intercepts deletion request and returns error: "Cannot delete brand because active products are assigned to it (FK Constraint 1451)."

---

### 3.2 Product Categories (Parent-Child Hierarchy)

#### Test Case 2.1: Root Product Category Creation
- **Objective:** Create top-level parent product classification categories.
- **Action:** Navigate to `Product Categories` -> `Create`. Fill:
  - Name: `Apparel & Garments`
  - Code: `CAT-APP-001`
  - Parent Category: `None (Root Category)`
  - Description: `All finished wearable textiles`
  - Status: `Active (true)`
  - Save.
- **Expected Result:** Root category created cleanly. `parent_id` stored as `null` in DB.

#### Test Case 2.2: Nested Sub-Parent Category Tree (`parent_id`)
- **Objective:** Build multi-tier category tree structure.
- **Action:** Navigate to `Product Categories` -> `Create`. Fill:
  - Name: `Men's Collection`
  - Code: `CAT-APP-MEN`
  - Parent Category: Select `Apparel & Garments`
  - Status: `Active (true)`
  - Save.
- **Expected Result:** Category created with `parent_id` pointing to `Apparel & Garments` record ID.

#### Test Case 2.3: Circular Parent Dependency Prevention
- **Objective:** Prevent setting a category as its own parent or child loop.
- **Action:** Edit Category `Apparel & Garments`. Attempt to set its `Parent Category` to `Men's Collection`.
- **Expected Result:** System validation blocks operation: "Invalid category hierarchy. A parent category cannot be a child of its own sub-category."

#### Test Case 2.4: Category Image Upload & Storage Verification
- **Objective:** Attach banner images for category display on POS/E-commerce portals.
- **Action:** Upload category banner image `apparel_banner.jpg` (JPG, 2MB). Save.
- **Expected Result:** Image uploaded cleanly, stored in `storage/app/public/product-categories/`, and thumbnail rendered on table list view.

---

### 3.3 Product Sub-Categories

#### Test Case 3.1: Sub-Category Creation & Parent Category Linkage
- **Objective:** Create detailed sub-classifications mapped to main categories.
- **Action:** Navigate to `Product Sub-Categories` -> `Create`. Fill:
  - Parent Category: Select `Apparel & Garments`
  - Name: `Formal Shirts`
  - Code: `SUBCAT-SHT-001`
  - Description: `Men's formal woven button-down shirts`
  - Status: `Active (true)`
  - Save.
- **Expected Result:** Sub-category saved cleanly with `product_category_id` foreign key properly set.

#### Test Case 3.2: Duplicate Sub-Category Code Rejection
- **Objective:** Enforce unique codes for sub-categories.
- **Action:** Attempt to create another sub-category with Code `SUBCAT-SHT-001`.
- **Expected Result:** System blocks save with error: "Product sub-category code must be unique."

#### Test Case 3.3: Cascading Category -> Sub-Category Selection in UI
- **Objective:** Verify dynamic filtering of sub-categories based on selected parent category.
- **Action:** Open `Products` -> `Create`. Select Category `Apparel & Garments`. Observe `Sub-Category` dropdown.
- **Expected Result:** Sub-Category dropdown lists ONLY sub-categories linked to `Apparel & Garments` (`Formal Shirts`). Sub-categories belonging to `Home Textiles` are excluded.

---

### 3.4 Product Lines

#### Test Case 4.1: Product Line Registration & Segment Mapping
- **Objective:** Configure market/style product lines.
- **Action:** Navigate to `Product Lines` -> `Create`. Fill:
  - Name: `Executive Linen Series`
  - Code: `PL-EXEC-LIN`
  - Description: `100% Pure Egyptian linen formal wear line`
  - Status: `Active (true)`
  - Save.
- **Expected Result:** Product Line logged and available for product tag assignment.

#### Test Case 4.2: Product Line Active Toggle & Filtering
- **Objective:** Disable obsolete product lines.
- **Action:** Set Product Line `PL-EXEC-LIN` status to `Inactive`.
- **Expected Result:** Product Line is flagged inactive and excluded from active merchandise planning filters.

---

### 3.5 Base Product Master & Taxonomy Assembly

#### Test Case 5.1: Master Product Creation (Positive Scenario)
- **Objective:** Create complete base product record assembling all catalog core entities.
- **Action:** Navigate to `Products` -> `Create`. Fill:
  - Name: `Royal Cotton Executive Shirt`
  - Code (SKU/Master Code): `PRD-RCS-001`
  - Brand: Select `Royal Silk Mills`
  - Product Category: Select `Apparel & Garments`
  - Product Sub-Category: Select `Formal Shirts`
  - Product Unit: Select `Pcs`
  - HSN Code: Select `6105`
  - Tax Group: Select `GST 5%`
  - Base Price: `1499.00`
  - Description: `Premium 100% combed cotton formal shirt`
  - Is Active: `true`
  - Click `Save`.
- **Expected Result:** Master product record created successfully. All foreign keys (`brand_id`, `product_category_id`, `product_sub_category_id`, `product_unit_id`, `hsn_code_id`, `tax_group_id`) accurately stored in DB.

#### Test Case 5.2: Product Form Validation Rules (Negative Scenarios)
- **Objective:** Verify mandatory field validations on product form.
- **Action 1:** Leave `Name` blank -> Click `Save`.
  - **Expected Result:** Error: "Product name is required."
- **Action 2:** Enter negative `Base Price` (`-500.00`) -> Click `Save`.
  - **Expected Result:** Error: "Base price must be a positive number greater than or equal to 0."

#### Test Case 5.3: Duplicate Product Code Enforcement
- **Objective:** Prevent duplicate product SKU master codes.
- **Action:** Attempt to create another Product with Code `PRD-RCS-001`.
- **Expected Result:** System blocks save: "Product code PRD-RCS-001 already exists."

#### Test Case 5.4: Base Price & Tax Calculation Consistency
- **Objective:** Verify decimal precision and tax rate attachment.
- **Action:** View created product details table for `PRD-RCS-001`.
- **Expected Result:** Base price stored as `1499.00` (decimal 10,2). Linked HSN `6105` displays statutory 5% tax bracket.

#### Test Case 5.5: Multi-Module Availability & Lifecycle Integration
- **Objective:** Ensure created product is instantly available in dependent operational modules.
- **Validation Checklist:**
  1. **Variant Generator (PIM):** Product `PRD-RCS-001` appears in parent product dropdown for SKU size/color variant generation.
  2. **Procurement (PO/RFQ):** Product selectable as line item in Purchase Requisitions and POs.
  3. **Warehouse (WMS):** Product available for Goods Receipt Notes (GRN) inward stocking.
  4. **POS Billing (POS):** Product available in POS billing search (if variants created).

#### Test Case 5.6: Inactive Product Lockout Check
- **Objective:** Verify setting `is_active = false` locks product out of operations.
- **Action 1:** Edit Product `PRD-RCS-001`. Set `is_active` = `false`. Save.
- **Action 2:** Open POS Billing Interface / Purchase Order line item selector.
- **Expected Result:** Inactive product `PRD-RCS-001` is HIDDEN and cannot be selected for new billing invoices or PO receipts.

---

## 4. Pass / Fail Verification Checklist Matrix
| Module Component | Test Scenario | Expected Result | Status |
| :--- | :--- | :--- | :--- |
| **Brands** | Logo upload & Code Uniqueness | Unique code enforced; Logo stored in public disk | Pass |
| **Product Categories** | Parent-Child Tree (`parent_id`) | Category tree maps correctly; Circular loops blocked | Pass |
| **Sub-Categories** | Category Linkage & Filter | Dynamic UI filtering by selected category works | Pass |
| **Product Lines** | Line Code & Segment Tag | Product lines register and toggle active/inactive | Pass |
| **Base Products** | Assembly & Cross-Module Availability | All 6 FKs stored cleanly; Inactive lockout enforced | Pass |
