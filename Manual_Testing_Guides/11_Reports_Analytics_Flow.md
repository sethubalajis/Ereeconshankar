# Testing Guide: Reports & Analytics Flow

## Overview
Provides intelligence and data exports across all the modules.

## Components to Test
Financial Reports, HR Reports, Inventory Reports, Purchase Reports, Sales Reports, Service Reports, GST Reports, KPI Reports.

## Step-by-Step Manual Testing
1. **Data Generation**
   - *Prerequisite:* Ensure you have completed testing in the Sales, Purchase, and Inventory flows so there is dummy data in the system.

2. **Testing Core Reports**
   - Go to `Sales Reports` -> Generate a 'Sales by Branch' or 'Sales by Date' report.
   - **Check:** Does the total match the POS Sales you created in Flow 5?
   - Go to `Inventory Reports` -> Generate a 'Stock Valuation' or 'Current Stock' report.
   - **Check:** Does the stock level match the stock ledgers in Flow 3?
   - Go to `Financial Reports` -> Generate a 'Profit & Loss' or 'Balance Sheet'.
   - **Check:** Do the manual journal entries and sales/purchase invoices reflect correctly?

3. **Export Functionality**
   - **Check:** Test the Export features (CSV, Excel, PDF) on these reports. Ensure the exported files open without corruption and formatting is maintained.
