# Stock Valuations - How It Works

## Purpose

The Stock Valuations module stores a snapshot of total inventory value per branch for a selected date.

Business use cases:

1. Track branch-wise inventory value over time.
2. Support month-end and period-end stock value checks.
3. Provide a historical valuation log for audit and reporting.

---

## Where It Is In Admin

- Menu Group: Inventory Management
- Screen: Admin -> Stock Valuations
- URL: `/admin/stock-valuations`

This page is protected by authentication.

---

## Data Stored

Each valuation record stores:

1. `branch_id` - Which branch the valuation belongs to.
2. `valuation_date` - Date of valuation snapshot.
3. `total_value` - Total inventory value for that branch at calculation time.
4. Timestamps - `created_at`, `updated_at`.

Database table: `stock_valuations`.

---

## Calculation Logic

When a branch is selected in the Stock Valuation form, the system calculates total inventory value from the `stocks` table as:

$$
\text{total\_value} = \sum(\text{quantity} \times \text{avg\_cost})
$$

Applied filter:

- Only rows where `stocks.branch_id = selected branch` are included.

The computed value is then written into the form field `total_value`.

---

## When Calculation Happens

Current behavior is manual-triggered, not scheduled.

It happens when a user interacts with the form:

1. Open Create/Edit Stock Valuation form.
2. Select branch.
3. System auto-fills `total_value` using current `stocks` data.
4. User saves the record.

Important:

- There is no automatic daily background job in the current code.
- No fixed clock time (like 11:59 PM) exists by default.
- The captured value depends on the exact time user saves the record.

---

## Date and Timezone Behavior

- `valuation_date` defaults to current date/time in `Asia/Kolkata` timezone at form load.
- The valuation is a snapshot at save-time, based on then-current stock data.

---

## List Page Behavior

The listing shows:

1. Branch name
2. Valuation date
3. Total value
4. Created/updated timestamps (toggleable)

Default sort: latest `valuation_date` first.

Supported actions:

1. Create
2. Edit
3. Bulk delete

---

## Operational Recommendation

For consistent reporting, define a process:

1. Manual daily close at fixed time (example: 11:59 PM IST), or
2. Add a scheduled Laravel job to auto-create one valuation per branch daily.

Until automation is added, valuation timing is user-driven.

---

## Source Implementation (Code References)

1. Resource and routes: `app/Filament/Resources/StockValuations/StockValuationResource.php`
2. Form and calculation trigger: `app/Filament/Resources/StockValuations/Schemas/StockValuationForm.php`
3. Table listing config: `app/Filament/Resources/StockValuations/Tables/StockValuationsTable.php`
4. Model relation: `app/Models/StockValuation.php`
5. Migration schema: `database/migrations/2026_06_29_070308_create_stock_valuations_table.php`
