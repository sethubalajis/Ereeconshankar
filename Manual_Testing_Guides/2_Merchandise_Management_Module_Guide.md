# Architecture & Model Relationships Guide: Merchandise Management Module

## 1. Executive Summary & Purpose of Merchandise Management
In a Multi-Branch Textile & Retail ERP System, the **Merchandise Management (Merchandise Planning)** module serves as the strategic & financial forecasting layer for product inventory acquisition and revenue target tracking.

### Core Business Objectives:
1. **Seasonal Budget Allocation:** Establishing planned procurement budgets (`planned_budget`) for specific retail seasons (e.g., *Diwali Festival*, *Summer Casuals*, *Pongal Special*, *Wedding Silk*) to prevent over-purchasing by procurement managers.
2. **Sales Revenue Targeting:** Setting macro-level revenue targets (`target_sales`) per financial calendar year and season to evaluate store performance.
3. **Inventory & Assortment Control:** Aligning product catalog taxonomies (Categories, Sub-Categories, Brands, Textile Attributes, and Price Lists) with seasonal consumer demand.

---

## 2. Model Relationship Architecture

### 2.1 Direct Relationships in `MerchandisePlanning` Model
The `MerchandisePlanning` model connects macro-level financial goals to specific time periods and retail seasons:

```
[ FinancialYear ]  <--- (financial_year_id) ---  [ MerchandisePlanning ]  --- (season_id) --->  [ Season ]
```

1. **`MerchandisePlanning` -> `FinancialYear` (`belongsTo`):**
   - **Foreign Key:** `financial_year_id`
   - **Purpose:** Maps the merchandise plan to a specific active financial year (e.g., `FY 2026-2027`).
2. **`MerchandisePlanning` -> `Season` (`belongsTo`):**
   - **Foreign Key:** `season_id`
   - **Purpose:** Associates the sales targets and procurement budgets with a specific retail season (e.g., *Diwali*, *Winter Collection*).

---

### 2.2 Extended Product Information Management (PIM) Model Mapping
The `MerchandisePlanning` module interacts with the broader Product Information Management (PIM) catalog ecosystem:

```
                  +-----------------------+
                  |    FinancialYear      |
                  +-----------------------+
                              | 1
                              |
                              | *
                  +-----------------------+
                  |  MerchandisePlanning  |
                  +-----------------------+
                              | *
                              |
                              | 1
                  +-----------------------+
                  |        Season         |
                  +-----------------------+
                              | 1
                              |
                              | *
+-----------------+   *     * |     *   +-----------------+
| ProductCategory |<----------+-------->| ProductVariant  |
+-----------------+                 +-----------------+
        | 1                                  | *
        |                                    |
        | *                                  | 1
+-----------------+   1             *   +-----------------+
|ProductSubCategory|<-------------------|     Product     |
+-----------------+                     +-----------------+
                                             | *
                                             |
                                             | 1
                                        +-----------------+
                                        |      Brand      |
                                        +-----------------+
```

#### Detailed Model Interconnections:
- **`ProductCategory` & `ProductSubCategory`:** Organize items into hierarchical merchandise divisions (e.g., `Apparel` -> `Men's Wear` -> `Formal Shirts`).
- **`Brand`:** Links master product records to vendor manufacturers (`Royal Silk Mills`).
- **`Product`:** Base product master containing default units, HSN codes, and base prices.
- **`ProductVariant`:** Connects `Season`, `Color`, `Size`, `FabricType`, `Design`, and `Style` to specific SKU items.
- **`PriceList`:** Controls wholesale and retail pricing tiers for variants during planned seasonal dates.

---

## 3. Database Schema Overview

### Table: `merchandise_plannings`
| Column Name | Data Type | Key Type | Description |
| :--- | :--- | :--- | :--- |
| `id` | BigInteger (Auto-Increment) | Primary Key | Unique record identifier |
| `financial_year_id` | ForeignId | Foreign Key | References `id` on `financial_years` |
| `season_id` | ForeignId | Foreign Key | References `id` on `seasons` |
| `target_sales` | Decimal(15, 2) | Attribute | Targeted sales revenue in INR |
| `planned_budget` | Decimal(15, 2) | Attribute | Allocated inventory purchase budget |
| `created_at` | Timestamp | Standard | Record creation timestamp |
| `updated_at` | Timestamp | Standard | Record last modification timestamp |

---

## 4. Current Implementation vs Recommended Enhancements

### Current Architecture State:
The current `MerchandisePlanning` model functions as a **macro-level financial forecasting tool**. It tracks total target sales and overall planned budgets per season.

### Recommended Architectural Enhancements:
For enterprise ERP deployments requiring granular category-level or branch-level planning, the schema can be extended as follows:

1. **Branch-Level Allocation (`branch_id`):**
   - Add `foreignId('branch_id')->nullable()->constrained('branches')` to `merchandise_plannings`.
   - **Benefit:** Allows branch managers to set distinct seasonal sales targets for `Coimbatore Branch` vs `Chennai Branch`.

2. **Category-Level Budgeting (`product_category_id`):**
   - Add `foreignId('product_category_id')->nullable()->constrained('product_categories')`.
   - **Benefit:** Allows merchandise planners to allocate specific procurement budgets per category (e.g., allocating ₹ 25,00,000 for *Silk Sarees* and ₹ 15,00,000 for *Men's Wear*).
