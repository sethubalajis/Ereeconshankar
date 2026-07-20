# Warehouses - Purpose and Capacity Unit

## Purpose

The Warehouses module is a master data screen for maintaining warehouse records under Warehouse Management.

Main business purpose:

1. Define warehouses branch-wise.
2. Store warehouse identity details (name, code, address).
3. Mark warehouse as active or inactive.
4. Maintain reference data used by other warehouse and inventory processes.

Admin location:

- Admin -> Warehouses
- URL: `/admin/warehouses`

---

## What Data Is Managed

Each warehouse record contains:

1. `branch_id`
2. `name`
3. `code` (unique)
4. `address`
5. `capacity`
6. `status` (active/inactive)
7. `created_at`, `updated_at`

Database table: `warehouses`.

---

## Capacity: How It Works

Current implementation behavior:

1. Capacity is entered manually in the form.
2. Capacity is stored as a decimal number (`decimal(15,2)`).
3. Capacity is displayed as a numeric field in list and form.
4. Capacity is not auto-calculated from stock quantity, volume, weight, or pallet counts.

This means capacity is currently unit-agnostic in code.

---

## Capacity Unit in Current System

There is no separate column for unit such as:

1. Square feet (`sq ft`)
2. Cubic meters (`m3`)
3. Tons (`t`)
4. Pallets

So the system does not enforce a fixed unit. The team must use one agreed business unit consistently across all warehouses.

---

## Recommended Operational Policy

To avoid reporting confusion:

1. Choose one standard capacity unit for the organization (example: `sq ft` or `m3`).
2. Document that standard in your internal SOP.
3. Enter all warehouse capacities only in that same unit.

Optional future improvement:

1. Add a `capacity_unit` field (enum or lookup) to enforce unit selection.
2. Display unit beside capacity in form and table.

---

## Source Implementation References

1. Warehouse resource: `app/Filament/Resources/Warehouses/WarehouseResource.php`
2. Warehouse form: `app/Filament/Resources/Warehouses/Schemas/WarehouseForm.php`
3. Warehouse table/list: `app/Filament/Resources/Warehouses/Tables/WarehousesTable.php`
4. Warehouse model: `app/Models/Warehouse.php`
5. Warehouses migration: `database/migrations/2026_06_29_070316_create_warehouses_table.php`
