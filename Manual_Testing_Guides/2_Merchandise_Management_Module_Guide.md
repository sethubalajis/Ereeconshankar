# Merchandise Management Module Guide

## Purpose

In this codebase, the Merchandise Management navigation group is a mix of:

1. Seasonal planning data used for budget and sales target entry.
2. Lookup masters used by the product catalog and variant definitions.

The important implementation detail is that this is not a single deeply connected subdomain yet. Only a small subset of the resources under this group are tied together by foreign keys.

## Resources Under `4. Merchandise Management`

| Resource | Model | Current Role | Actual Relationships in Code |
| :--- | :--- | :--- | :--- |
| `Collections` | `Collection` | Standalone lookup/master | None backed by current schema |
| `Colors` | `Color` | Variant option lookup | `hasMany(ProductVariant)` |
| `Designs` | `Design` | Standalone lookup/master | None backed by current schema |
| `Fabric Types` | `FabricType` | Standalone lookup/master | None backed by current schema |
| `Merchandise Plannings` | `MerchandisePlanning` | Seasonal sales and budget planning | `belongsTo(FinancialYear)`, `belongsTo(Season)` |
| `Pattern Types` | `PatternType` | Standalone lookup/master | None backed by current schema |
| `Product Lines` | `ProductLine` | Standalone lookup/master | None backed by current schema |
| `Seasons` | `Season` | Seasonal master | `hasMany(MerchandisePlanning)` |
| `Sizes` | `Size` | Variant option lookup | `hasMany(ProductVariant)` |
| `Styles` | `Style` | Standalone lookup/master | None backed by current schema |

## Actual Relationship Graph

### 1. Planning Layer

```text
FinancialYear (1) ----< MerchandisePlanning >---- (1) Season
```

- `MerchandisePlanning.financial_year_id` references `financial_years.id`
- `MerchandisePlanning.season_id` references `seasons.id`

This means the `/admin/merchandise-plannings` screen is currently a high-level planning table for:

- target sales by season
- planned budget by season
- grouped within a financial year

It is not currently linked to categories, brands, products, branches, or vendors.

### 2. Product Catalog Layer Used By Merchandise Operations

```text
Brand (1) ----< Product >---- (1) ProductCategory
                         \
                          \--- (1) ProductSubCategory

ProductCategory (1) ----< ProductSubCategory
ProductCategory (1) ----< ProductCategory (child categories)

Product (1) ----< ProductVariant >---- (1) Color
                                 \
                                  \--- (1) Size

Product (1) ----< ProductImage
Product (1) ----< ProductBarcode

ProductVariant (1) ----< ProductImage
ProductVariant (1) ----< ProductBarcode

ProductAttribute (1) ----< AttributeValue >----< ProductVariant
```

These relationships are real in the schema and code, but they belong mostly to the product catalog, not directly to `MerchandisePlanning`.

## What Is Connected And What Is Not

### Directly connected to `MerchandisePlanning`

- `FinancialYear`
- `Season`

### Connected to product and variant setup

- `Color`
- `Size`
- `Brand`
- `ProductCategory`
- `ProductSubCategory`
- `Product`
- `ProductVariant`
- `ProductAttribute`
- `AttributeValue`
- `ProductImage`
- `ProductBarcode`

### Present in the navigation group but currently standalone

- `Collection`
- `Design`
- `FabricType`
- `PatternType`
- `ProductLine`
- `Style`

Those models exist as masters, but the current migrations do not attach them to `products`, `product_variants`, or `merchandise_plannings`.

## Current Architecture Summary

The module is best understood as two adjacent layers:

1. `MerchandisePlanning` is the planning layer.
2. The lookup resources are supporting catalog masters.

Because the schema does not currently contain columns like `collection_id`, `design_id`, `fabric_type_id`, `pattern_type_id`, `product_line_id`, `style_id`, `branch_id`, or `product_category_id` on `merchandise_plannings`, the planning screen cannot yet drive assortment planning at category, style, or branch level.

## Implemented In Code

The codebase now defines the missing Eloquent relations that were already implied by existing foreign keys:

- `FinancialYear -> merchandisePlannings()`
- `Season -> merchandisePlannings()`
- `Color -> productVariants()`
- `Size -> productVariants()`
- `Brand -> products()`
- `ProductCategory -> childCategories(), productSubCategories(), products()`
- `ProductSubCategory -> products()`
- `Product -> productVariants(), productImages(), productBarcodes()`
- `ProductVariant -> productImages(), productBarcodes(), attributeValues()`
- `ProductAttribute -> attributeValues()`

## If You Want A More Complete Merchandise Planning Engine

The next schema step would be to add optional planning dimensions such as:

- `branch_id` on `merchandise_plannings`
- `product_category_id` on `merchandise_plannings`
- `brand_id` on `merchandise_plannings`
- a new detail table such as `merchandise_planning_lines` for category-wise or product-wise allocation

That would turn the current planning screen from seasonal budgeting into actual assortment and allocation planning.
