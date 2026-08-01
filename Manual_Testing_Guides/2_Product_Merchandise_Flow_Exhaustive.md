# Exhaustive Manual Testing Guide: Product & Merchandise Flow (PIM)

## 1. Overview & Architecture Scope
The Product Information Management (PIM) flow handles the multi-tier product catalog hierarchy required for textile and garment retail operations. It manages product categories, sub-categories, lines, brands, dynamic textile attributes (fabric types, weave, pattern, size, color), barcode generations, multi-tier price lists, and seasonal merchandise plans.

### Target Components & Resources:
- **Catalog Core:** Products, Product Categories, Product Sub-Categories, Product Lines, Brands
- **Textile Specifics:** Product Variants, Attributes, Attribute Values, Colors, Sizes, Styles, Seasons, Designs, Fabric Types, Pattern Types
- **Pricing & Media:** Price Lists, Product Images, Product Barcodes, Merchandise Plannings

---

## 2. Pre-requisites
1. Organization & Branch setup completed (Flow 1).
2. Base GST rates and HSN codes seeded (Flow 9).

---

## 3. Comprehensive Test Cases

### 3.1 Catalog Core Taxonomy

#### Test Case 1.1: 4-Tier Taxonomy Creation
- **Objective:** Verify parent-child catalog classification setup.
- **Action 1:** Navigate to `Product Categories` -> Create Category `Apparel`.
- **Action 2:** Navigate to `Product Sub-Categories` -> Create Sub-Category `Men's Wear` linked to `Apparel`.
- **Action 3:** Navigate to `Product Lines` -> Create Product Line `Formal Shirts` linked to `Men's Wear`.
- **Action 4:** Navigate to `Brands` -> Create Brand `Royal Silk`.
- **Expected Result:** All 4 entities save cleanly with relations intact.

#### Test Case 1.2: Foreign Key Deletion Restriction
- **Objective:** Prevent orphan categories and data corruption.
- **Action:** Attempt to delete Category `Apparel` while `Men's Wear` is assigned to it.
- **Expected Result:** System blocks deletion and displays error: "Cannot delete category containing linked sub-categories."

---

### 3.2 Textile Attribute Matrix

#### Test Case 2.1: Dynamic Textile Attributes Setup
- **Objective:** Configure textile-specific attributes and values.
- **Action 1:** Go to `Fabric Types` -> Add `100% Egyptian Cotton`, `Pure Silk`, `Linen Blend`.
- **Action 2:** Go to `Pattern Types` -> Add `Checked`, `Striped`, `Solid`.
- **Action 3:** Go to `Colors` -> Add `Royal Blue` (Hex: `#4169E1`), `Crimson Red` (Hex: `#DC143C`).
- **Action 4:** Go to `Sizes` -> Add `39`, `40`, `42`, `44`.
- **Expected Result:** Attributes created with hex codes and sort orders preserved.

---

### 3.3 Product Core & Variant Generation

#### Test Case 3.1: Base Product Creation
- **Objective:** Verify creation of base master product record.
- **Action:** Go to `Products` -> `Create`. Fill:
  - Name: `Royal Cotton Formal Shirt`
  - Category: `Apparel` -> `Men's Wear` -> `Formal Shirts`
  - Brand: `Royal Silk`
  - Base UOM: `Pcs`
  - Base Price: `1500.00`
  - HSN Code: `6105`
- **Expected Result:** Product record created in draft/active status.

#### Test Case 3.2: Variant Generation & SKU Naming Formula
- **Objective:** Generate SKU combinations for size and color.
- **Action:** Go to `Product Variants` -> `Create`. Select Base Product `Royal Cotton Formal Shirt`. Select Color `Royal Blue`, Size `40`, Fabric `100% Egyptian Cotton`. Set MRP `2499.00`, Selling Price `1999.00`.
- **Expected Result:** Variant saved. Auto-generated SKU format verified as `RCS-RBLU-40`.

#### Test Case 3.3: Barcode Generation & Duplicate Prevention
- **Objective:** Ensure unique barcode strings per variant SKU.
- **Action 1 (Generate):** Go to `Product Barcodes` -> Assign Barcode `8901234567890` to variant `RCS-RBLU-40`.
  - **Expected Result:** Barcode saved. Printable EAN-13 barcode preview generated.
- **Action 2 (Duplicate Check):** Create another barcode record with the exact same barcode string `8901234567890` for a different variant.
  - **Expected Result:** System rejects creation with unique constraint error: "Barcode string already exists."

---

### 3.4 Pricing & Merchandise Planning

#### Test Case 4.1: Multi-Tier Price List Configuration
- **Objective:** Support wholesale, retail, and festival pricing structures.
- **Action:** Go to `Price Lists` -> `Create`. Name: `Diwali Wholesale Special`. Type: `Wholesale`. Start: `2026-10-01`, End: `2026-10-31`. Add item `RCS-RBLU-40` with special price `1450.00` (min qty 10).
- **Expected Result:** Price list active and restricted within date window.

#### Test Case 4.2: Merchandise Planning Targets
- **Objective:** Set quarterly inventory sales targets.
- **Action:** Go to `Merchandise Plannings` -> Create Q3 plan for `Men's Wear`. Target Sales Amount: `50,00,000.00`. Target Units: `2500`.
- **Expected Result:** Target logged and accessible in reports.
