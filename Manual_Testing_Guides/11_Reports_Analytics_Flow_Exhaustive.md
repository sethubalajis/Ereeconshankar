# Exhaustive Manual Testing Guide: Reports & Analytics Flow

## 1. Overview & Architecture Scope
The Reports & Analytics Flow provides intelligence and decision-making data across all 10 operational modules. It includes KPI executive dashboards, branch performance matrix reports, financial statements (P&L, Balance Sheet, Trial Balance), inventory stock valuation reports, purchase/sales analytics, GST tax return summaries, and export data validation (Excel/CSV/PDF).

### Target Components & Resources:
- **Dashboards:** KPI Reports, Branch Performance Reports
- **Module Reports:** Financial Reports, HR Reports, Inventory Reports, Purchase Reports, Sales Reports, Service Reports, GST Reports

---

## 2. Pre-requisites
1. Operational data populated across all modules (Flows 1 through 10).

---

## 3. Comprehensive Test Cases

### 3.1 Executive KPI Dashboards

#### Test Case 1.1: Real-Time KPI Dashboard Aggregation Accuracy
- **Objective:** Verify executive dashboard widgets reflect accurate aggregate metrics.
- **Action:** Open `KPI Reports` Dashboard as Admin.
- **Validation Checklist:** Cross-check dashboard widgets against raw DB table sums:
  1. `Total Daily Sales Widget` MUST match sum of today's completed POS sales.
  2. `Active Inventory Stock Value Widget` MUST match current stock * valuation rate.
  3. `Total Outstanding Receivables` MUST match customer balance ledger sum.
- **Expected Result:** Dashboard figures match raw DB aggregate queries down to the exact decimal point.

---

### 3.2 Date Filter Boundaries & Multi-Tenant Data Isolation

#### Test Case 2.1: Strict Date Filter Boundary Verification (00:00:00 to 23:59:59)
- **Objective:** Ensure reports accurately filter transactions by exact date/time boundaries.
- **Action:** Open `Sales Reports` -> Select Date Range `2026-08-01` to `2026-08-05`.
- **Validation:** Inspect query results.
- **Expected Result:** Transactions created on `2026-08-01 00:00:01` are INCLUDED. Transactions created on `2026-08-05 23:59:58` are INCLUDED. Transactions created on `2026-08-06 00:00:00` are EXCLUDED.

#### Test Case 2.2: Multi-Tenancy Segregation in Branch Reports (Security Audit)
- **Objective:** Prevent branch managers from accessing other branches' data in reports.
- **Action 1 (Branch User View):** Log in as `Coimbatore Branch Manager`. Open `Inventory Reports` -> `Branch Stock Summary`.
  - **Expected Result:** Report displays ONLY `Coimbatore Branch` stock. Branch filter dropdown is locked/hidden.
- **Action 2 (Super Admin View):** Log in as Super Admin. Open same report.
  - **Expected Result:** Report displays branch dropdown containing ALL branches and option for "All Branches Consolidated".

---

### 3.3 Financial Statement Roll-ups

#### Test Case 3.1: Profit & Loss Statement Group Roll-Up Accuracy
- **Objective:** Verify sub-ledger balances roll up accurately into parent financial groups.
- **Action:** Open `Financial Reports` -> `Profit & Loss Statement`.
- **Validation:**
  - Check `Sub-Ledger: Electricity Expense (12,500)` and `Sub-Ledger: Water Expense (2,500)`.
  - Verify `Parent Group: Utilities Expense` displays total `15,000.00 INR`.
  - Verify `Net Profit` equals `Total Gross Revenue - Total Operating Expenses`.
- **Expected Result:** Mathematical roll-up is 100% accurate.

---

### 3.4 Data Export Integrity

#### Test Case 4.1: Excel / CSV Export Structure & Numeric Type Validation
- **Objective:** Ensure exported data files are clean, formatted, and uncorrupted.
- **Action:** Open `GST Reports` -> `GSTR-1 Outward Summary`. Click `Export to Excel (.xlsx)`. Download file.
- **Validation:** Open exported file in Microsoft Excel:
  1. Column headers align perfectly with data columns.
  2. Amount fields are formatted as Numeric / Currency types (NOT text strings), allowing `=SUM()` formulas to work.
  3. No raw HTML tags (`<br>`, `<div>`), null strings, or unescaped commas exist in data cells.
- **Expected Result:** Exported file passes all data layout integrity checks.
