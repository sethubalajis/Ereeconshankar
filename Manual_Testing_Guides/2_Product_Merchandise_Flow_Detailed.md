# Comprehensive Manual Testing Guide: Product & Merchandise Flow

## 1. Overview
The PIM (Product Information Management) flow is the heart of a retail/textile ERP. It handles hierarchical categorization, complex variants (size/color), and pricing.

## 2. Pre-requisites
- Organization flow must be tested and a working Branch must exist.
- Standard GST Rates and HSN codes (from Flow 9) should ideally exist, but testing can proceed without them initially.

## 3. Detailed Test Cases

### Test Case 1: Catalog Taxonomy Setup
**Objective:** Ensure product classifications work and hierarchy is maintained.
- **Action:** Navigate through `Product Categories`, `Sub-Categories`, `Product Lines`, and `Brands`.
- **Action:** Create a Category (e.g., 'Men'). Create a Sub-Category (e.g., 'Shirts') linking it to 'Men'. Create a Brand (e.g., 'Polo').
- **Validation:** Attempt to delete the 'Men' category.
- **Expected Result:** The system should either prevent deletion due to the foreign key constraint from the Sub-Category, or cascade the delete (check requirements). Usually, it should prevent it (Error 1451).

### Test Case 2: Textile Attribute Setup
**Objective:** Verify that dynamic attributes specific to textiles can be configured.
- **Action:** Navigate to `Product Attributes`. Create an attribute named 'Color'.
- **Action:** Add `Attribute Values` for 'Color' (e.g., 'Red', 'Blue', 'Green').
- **Action:** Repeat for 'Size' (S, M, L, XL) and 'Fabric Type' (Cotton, Silk).
- **Expected Result:** All attributes and values save correctly and are available globally.

### Test Case 3: Base Product Creation (Positive & Negative)
**Objective:** Verify the main product wrapper is created successfully.
- **Action 1 (Negative):** Go to `Products` -> `Create`. Enter only the product name and hit save.
- **Expected Result:** Validation errors for required fields (Category, UOM, Base Price, etc.).
- **Action 2 (Positive):** Fill all details (Name: 'Classic Polo Shirt', Category: Men, Sub-Category: Shirts, Brand: Polo). Upload a primary product image.
- **Expected Result:** Product is created successfully.

### Test Case 4: Variant Generation & Barcodes
**Objective:** Verify that specific SKUs are generated for the size/color combinations.
- **Action:** Go to `Product Variants` -> `Create`. Select the base product 'Classic Polo Shirt'.
- **Action:** Assign the attributes: Size = 'L', Color = 'Red'. Set the MRP and Selling Price.
- **Validation:** Check the SKU field. If it's auto-generated, ensure it follows a standard pattern (e.g., `POLO-RED-L`).
- **Action:** Go to `Product Barcodes`. Generate or manually assign a barcode string (e.g., `890123456789`) to this variant.
- **Expected Result:** The system prevents assigning the same barcode to two different variants (should throw a unique constraint error if tested).

### Test Case 5: Pricing Strategy
**Objective:** Ensure multiple price lists can be managed.
- **Action:** Go to `Price Lists` -> `Create`. Name it 'Diwali Wholesale Pricing'. Set active dates (valid from/to).
- **Action:** Add price list items for the variant created in Test Case 4, setting a lower wholesale price.
- **Expected Result:** The price list saves. When testing the POS later, this specific price list should activate if the conditions are met.
