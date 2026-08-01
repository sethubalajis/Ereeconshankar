# Comprehensive Manual Testing Guide: Reports & Analytics Flow

## 1. Overview
Validates the data output that management relies on. A report is useless if the filters don't work or the math is wrong.

## 2. Detailed Test Cases

### Test Case 1: Date Range Filters (Crucial Validation)
**Objective:** Ensure reports accurately scope data by time.
- **Action:** Open the `Sales Reports` -> 'Daily Sales Register'.
- **Validation:** Select a date range from 'October 1' to 'October 5'. 
- **Expected Result:** Verify against the raw `POS Sales` table that ONLY sales created within those exact dates (including time boundaries 00:00:00 to 23:59:59) are included in the sum.

### Test Case 2: Multi-Tenancy Data Segregation
**Objective:** Prevent Branch A from seeing Branch B's data.
- **Action:** Log in as a Manager who only has access to 'Branch A'.
- **Action:** Generate the 'Inventory Current Stock' report.
- **Expected Result:** The report MUST only display stock data for Branch A. There should not even be a dropdown option to select Branch B.
- **Action:** Log in as Super Admin. Generate the same report.
- **Expected Result:** Admin should see data for ALL branches, and the report should group totals appropriately by branch.

### Test Case 3: Financial Report Roll-ups
**Objective:** Verify accounting math aggregates correctly up the chart of accounts tree.
- **Action:** Open `Financial Reports` -> 'Profit & Loss Statement'.
- **Expected Result:** Ensure all sub-ledger expenses (e.g., 'Branch Electricity', 'Water Bill') roll up accurately into their parent group (e.g., 'Utilities Expense').
- **Validation:** Manually add up the sub-ledgers. The group total must match exactly. Net Profit must equal Total Revenue - Total Expenses.

### Test Case 4: Export Format Integrity
**Objective:** Ensure data is usable outside the ERP.
- **Action:** Generate the 'GST Returns' or 'GSTR-3B' report.
- **Action:** Click 'Export to Excel/CSV'.
- **Validation:** Open the downloaded file in Excel.
- **Expected Result:** 
  1. Data columns align properly with headers.
  2. Numbers are formatted as numbers (not text strings) so formulas can be applied.
  3. No HTML tags or raw code leak into the CSV cells.
