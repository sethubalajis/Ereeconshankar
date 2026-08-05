# Office Stock vs POS Sales Guide

## Your Doubt
If stationery is kept in office stock, will it appear in sales and be sold by mistake?

Yes, that can happen if stationery is maintained as normal products and POS is not filtered.

## Current Behavior in Your Codebase
- POS product selection currently uses product relationships without a sellable filter.
- So any product that exists in product master can be selected in POS.

## Correct Operational Approach
1. Use Assets module for fixed office items:
   - Computers
   - Printers
   - Furniture
2. Use Purchase and Stock flow for consumables:
   - Paper
   - Pens
   - Files
   - Other stationery
3. Prevent consumables meant only for office use from appearing in POS.

## Recommended Control
Implement a sellability flag in product master.

Suggested field:
- `is_saleable` (boolean)

Suggested behavior:
- `is_saleable = true`: item can be sold in POS
- `is_saleable = false`: item stays in stock but cannot be selected in POS

## Practical Rule
- Office-use consumables: stock managed, not POS saleable
- Retail merchandise: stock managed and POS saleable

## Suggested Next Step
Update POS product dropdown query to only show products where `is_saleable = true`.

This keeps office stationery available for internal stock control but hidden from billing counters.
