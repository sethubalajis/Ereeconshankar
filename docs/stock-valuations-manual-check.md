# Stock Valuations Manual Checking Guide

## Objective

Use this checklist to confirm the Stock Valuations feature is calculating and saving correctly.

---

## Prerequisites

1. You can log in to the admin panel.
2. You have database access (phpMyAdmin or MySQL client).
3. At least one branch exists in `branches` table.
4. The `stocks` table has rows with `branch_id`, `quantity`, and `avg_cost`.

---

## Test 1: Verify Calculation Against SQL

### Step A: Pick a branch

Choose one branch id for testing, for example branch id = 1.

### Step B: Get expected value from database

Run:

```sql
SELECT COALESCE(SUM(quantity * avg_cost), 0) AS expected_total
FROM stocks
WHERE branch_id = 1;
```

Note the `expected_total` value.

### Step C: Create valuation from UI

1. Go to Admin -> Stock Valuations.
2. Click Create.
3. Select the same branch.
4. Observe `total_value` auto-fill.

### Expected Result

- `total_value` in the form matches `expected_total` from SQL.

---

## Test 2: Save and Validate Record

### Steps

1. Keep the selected branch and valuation date.
2. Click Save.
3. Return to list page.

### Expected Result

1. New row appears in list.
2. Branch is correct.
3. Valuation date is correct.
4. Total value is correct.

Optional DB verification:

```sql
SELECT id, branch_id, valuation_date, total_value, created_at
FROM stock_valuations
ORDER BY id DESC
LIMIT 5;
```

---

## Test 3: Branch Change Recalculation

### Steps

1. Open Create page again.
2. Select Branch A and note `total_value`.
3. Change to Branch B.

### Expected Result

- `total_value` updates immediately when branch changes.

---

## Test 4: Zero Stock Branch

### Steps

1. Use a branch with no rows in `stocks`.
2. Create valuation for that branch.

### Expected Result

1. `total_value` becomes 0 or 0.00.
2. Save works without error.

---

## Test 5: Edit Existing Record

### Steps

1. Open any existing stock valuation record.
2. Change branch and/or date.
3. Save.

### Expected Result

1. Record updates successfully.
2. Updated values are shown in list.

---

## Test 6: List Page Behavior

### Steps

1. Open Stock Valuations list.
2. Check ordering.
3. Try bulk delete on sample rows (non-production data only).

### Expected Result

1. Records are sorted by latest valuation date first.
2. Bulk delete works for selected rows.

---

## Test 7: Date Default Check

### Steps

1. Open Create page.
2. Observe default valuation date.

### Expected Result

- Date defaults to current date based on Asia/Kolkata timezone behavior configured in form logic.

---

## Known Behavior (Important)

1. Calculation is triggered when branch is selected in the form.
2. There is no automatic daily scheduler by default.
3. Value captured depends on stock data at save time.

---

## Pass Criteria

Feature can be considered manually verified if all below are true:

1. UI value equals SQL value for tested branches.
2. Save and edit work without errors.
3. Zero-stock case returns zero.
4. List sorting and actions behave as expected.
