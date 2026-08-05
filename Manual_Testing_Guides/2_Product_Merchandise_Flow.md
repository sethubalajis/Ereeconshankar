# Testing Guide: Product & Merchandise Flow (PIM)

## Overview
This flow handles the complex product catalog typical for a textile business, including variants, pricing, and attributes.

## Components to Test
Products, Categories, Sub-Categories, Lines, Brands, Variants, Attributes, Colors, Sizes, Styles, Seasons, Designs, Fabric Types, Price Lists.

## Step-by-Step Manual Testing
1. **Catalog Foundation**
   - Go to `Brands`, `Colors`, `Sizes`, `Styles`, `Seasons`, `Designs`, `Fabric Types`, `Pattern Types` and create 1-2 sample records in each.
   - Go to `Product Categories`, `Sub-Categories`, and `Lines` and create a hierarchical structure.

2. **Product Creation**
   - Go to `Products` -> Create a new base product (e.g., 'Cotton T-Shirt'). Assign it to a category, brand, and fabric type.
   - **Check:** Does the form save without SQL errors?

3. **Product Variants & Attributes**
   - Go to `Product Attributes` -> Create an attribute (e.g., 'Fit'). Add `Attribute Values` (e.g., 'Slim', 'Regular').
   - Go to `Product Variants` -> Create a variant for the base product (e.g., 'Cotton T-Shirt - Red - XL').
   - **Check:** Ensure the SKU or Barcode is generated or can be assigned correctly.

4. **Pricing**
   - Go to `Price Lists` -> Create a base retail price list and assign it to the products/variants.
   - **Check:** Verify that the prices reflect correctly in the POS or Sales screens later.
