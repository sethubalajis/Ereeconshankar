# Testing Guide: Inventory & Warehouse Flow (WMS)

## Overview
Tracks physical stock movement across multiple branches and warehouses.

## Components to Test
Warehouses, Zones, Racks, Bins, Stocks, Stock Adjustments, Transfers, GRNs, Picking/Packing Lists.

## Step-by-Step Manual Testing
1. **Warehouse Structure Setup**
   - Go to `Warehouses` -> Create a central warehouse linked to a branch.
   - Go to `Warehouse Zones`, `Racks`, and `Bins` -> Create the physical layout hierarchy inside the warehouse.
   - **Check:** Can you successfully link a Bin -> Rack -> Zone -> Warehouse?

2. **Inward Stock (GRN)**
   - Go to `GRNs` (Goods Receipt Notes) -> Create a GRN for a specific product/variant arriving at a warehouse.
   - **Check:** Does creating the GRN automatically increase the stock in the `Stocks` and `Stock Ledgers` tables?

3. **Stock Adjustments & Transfers**
   - Go to `Stock Adjustments` -> Perform a manual positive and negative adjustment.
   - **Check:** Does the overall stock level update correctly?
   - Go to `Warehouse Transfers` or `Stock Transfers` -> Initiate a transfer from Warehouse A to Branch B.
   - Complete the `Transfer Approval`, `Picking List`, `Packing List`, and `Transfer Receipt`.
   - **Check:** Verify the stock decreases in Warehouse A and increases in Branch B after the receipt is complete.
