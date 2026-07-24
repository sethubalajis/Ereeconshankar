# Inventory Count to Reconciliation Functional Flow

## Scope

This document defines the functional behavior for:
- Inventory Count Create page: http://127.0.0.1:8000/admin/inventory-counts/create
- Inventory Reconciliation pages: http://127.0.0.1:8000/admin/inventory-reconciliations

It covers draft and active modes, automatic reconciliation row creation, editable physical quantity, and automatic variance calculation.

## Actors

- Admin user
- Branch user with inventory access

## Master Rule Summary

1. User can create an Inventory Count in Draft or Active status.
2. Draft status does not generate reconciliation lines.
3. When status is changed from Draft to Active:
- System must create Inventory Reconciliation rows for the selected branch and warehouse.
- Rows must be created for each product and product variant available in that branch and warehouse.
- System quantity must be auto-populated from current on-hand stock.
4. User updates physical quantity line by line in Inventory Reconciliation.
5. Variance must be auto-calculated for each line as:

$$
variance = physical\_qty - system\_qty
$$

## Preconditions

1. Branch must exist.
2. Warehouse must exist and belong to selected branch.
3. Product and product variant stock must be available in inventory ledger or stock source for the selected branch and warehouse.
4. User must have permission to create and update Inventory Count and Inventory Reconciliation.

## Functional Flow

### Step 1: Create Inventory Count

User opens Inventory Count Create page and fills:
- Branch
- Warehouse
- Count code
- Count date
- Status (Draft or Active)

Expected behavior:
- If status is Draft, record is saved and process stops.
- If status is Active, system continues with auto-generation of reconciliation rows.

### Step 2: Activate Inventory Count

Trigger condition:
- On create with Active status, or
- On edit when status changes from Draft to Active.

System action:
1. Read all stock entries for selected branch and warehouse.
2. Group by product and product variant.
3. Create one reconciliation row per unique product plus variant combination.
4. Set system quantity from current stock balance.
5. Set physical quantity default equal to system quantity (or 0 if business decision requires counting from zero).
6. Set variance using formula physical minus system.

Idempotency rule:
- If reconciliation rows already exist for this inventory count, system must not create duplicates.

### Step 3: Edit Reconciliation Lines

User opens Inventory Reconciliation list filtered by selected inventory count.

User updates physical quantity for each line.

System behavior on each save:
1. Recalculate variance automatically.
2. Prevent manual override of variance field.
3. Persist updated physical quantity and computed variance.

## Field-Level Rules

### Inventory Count

- branch_id: required
- warehouse_id: required
- count_code: required, unique
- count_date: required
- status: required, allowed values Draft, Active, Completed, Cancelled

### Inventory Reconciliation

- inventory_count_id: required
- product_id: required
- product_variant_id: nullable if product has no variants
- system_qty: required, numeric, non-negative
- physical_qty: required, numeric, non-negative
- variance: computed, numeric (can be negative)

## Status Transition Rules

Allowed transitions:
- Draft to Active
- Active to Completed
- Active to Cancelled

Restricted transitions:
- Completed to Draft is not allowed
- Cancelled to Active requires explicit permission or re-open action

Generation rule:
- Reconciliation auto-generation happens only once at Draft to Active transition (or initial create in Active).

## Calculation Rules

Per line:

$$
variance = physical\_qty - system\_qty
$$

Interpretation:
- Positive variance: extra physical stock found
- Zero variance: no mismatch
- Negative variance: shortage in physical stock

## Validation and Error Handling

1. If no stock records are found for selected branch and warehouse:
- Count can still become Active.
- Reconciliation rows remain empty.
- Show message: No stock found for selected branch and warehouse.

2. If stock read fails due to data issue:
- Keep count in Draft or rollback activation transaction.
- Show error with retry option.

3. On concurrent edits:
- Use optimistic or transactional locking for reconciliation saves.

## Audit Requirements

Track these events:
- Inventory Count created
- Status changed Draft to Active
- Reconciliation rows generated count
- Physical quantity edits by user
- Variance recalculated timestamp

## API and Data Processing Notes

Recommended processing pattern:
1. Wrap activation and row creation in a DB transaction.
2. Use upsert keyed by inventory_count_id plus product_id plus product_variant_id.
3. Store generated_by and generated_at fields if available.

## Functional Test Checkpoints

1. Create Inventory Count in Draft and verify no reconciliation rows are created.
2. Change same count from Draft to Active and verify rows are generated.
3. Verify generated rows include all product and variant combinations with correct system quantity.
4. Edit physical quantity and verify variance auto-updates.
5. Verify duplicate generation does not occur on repeated saves.
6. Verify behavior when warehouse has zero stock.
7. Verify access control for create and edit actions.
8. Verify transition restrictions for Completed and Cancelled states.

## Example Scenario

1. Branch A, Warehouse W1 has:
- Product P1 Variant V1: system stock 120
- Product P2 no variant: system stock 35

2. User creates Inventory Count in Draft.
- No reconciliation rows created.

3. User changes status to Active.
- Row 1: P1, V1, system 120, physical default 120, variance 0
- Row 2: P2, null variant, system 35, physical default 35, variance 0

4. User edits physical:
- Row 1 physical changed to 118, variance becomes -2
- Row 2 physical changed to 40, variance becomes +5

Result:
- Variance is always system-driven and computed automatically.
