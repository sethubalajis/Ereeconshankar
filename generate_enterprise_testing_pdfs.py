import os
from markdown_pdf import Section, MarkdownPdf

output_dir = "Manual_Testing_Guides"

enterprise_flows = {
    "1_Organization_Administration_Enterprise_TestPlan": """# Enterprise Manual Test Plan: Organization & Administration

## Overview
Test execution matrix for the core multi-tenancy and organizational structure.

## Test Execution Matrix

| TC ID | Module | Test Scenario | Pre-conditions | Test Steps | Expected Result |
|---|---|---|---|---|---|
| ORG-001 | Company | Create Company - Positive | Admin Logged In | 1. Go to Companies. 2. Click Create. 3. Enter Name 'Test Corp'. 4. Select Country 'India', State 'TN', City 'Chennai'. 5. Click Save. | Company 'Test Corp' is created. Flash success message appears. DB table `companies` has 1 new row. |
| ORG-002 | Company | Create Company - Negative (Blank) | Admin Logged In | 1. Go to Companies. 2. Click Create. 3. Leave Name blank. 4. Click Save. | Form submission is blocked. 'Name is required' validation error is displayed in red. |
| ORG-003 | Branch | Create Branch - Code Auto-Gen | Company exists | 1. Go to Branches. 2. Select 'Test Corp'. 3. Fill mandatory address fields. 4. Click Save. | Branch is saved. The `Code` column automatically populates with a format like `BR-X9Y8Z7`. |
| ORG-004 | Dept | Create Department - Linkage | Branch exists | 1. Go to Departments. 2. Click Create. 3. Select the branch from ORG-003. 4. Click Save. | Department saves. Code auto-increments to `DPT-0001`. |
| AUTH-001| Roles | Create Role - Strict Perms | Admin Logged In | 1. Go to Roles. 2. Name 'Viewer'. 3. Select ONLY `view_products` permission. 4. Save. | Role is saved. DB table `role_has_permissions` verifies only 1 permission mapped. |
| AUTH-002| Users | Assign Role & Tenancy | Role 'Viewer' exists | 1. Go to Users. 2. Create User A. Assign 'Viewer' role. 3. Go to User Branch Access. 4. Assign User A to Branch 1. | User A is created and linked to Branch 1. |
| AUTH-003| Auth | Verify Multi-Tenant Block | User A created | 1. Log in as User A. 2. Check Top Nav Bar. 3. Attempt to access `/admin/users` URL directly. | Tenant switcher only shows Branch 1. Menu only shows Products. Accessing `/admin/users` throws 403 Forbidden. |
""",

    "2_Product_Merchandise_Enterprise_TestPlan": """# Enterprise Manual Test Plan: Product & Merchandise

## Test Execution Matrix

| TC ID | Module | Test Scenario | Pre-conditions | Test Steps | Expected Result |
|---|---|---|---|---|---|
| PIM-001 | Taxonomy | Create Category Hierarchy | None | 1. Create Category 'Apparel'. 2. Create Sub-Category 'T-Shirts', link to 'Apparel'. | Hierarchy is maintained. Deleting 'Apparel' is blocked due to FK constraint from 'T-Shirts'. |
| PIM-002 | Attributes | Define Dynamic Attributes | None | 1. Go to Attributes. Create 'Size'. 2. Add values 'S, M, L'. | Attributes save successfully. |
| PIM-003 | Product | Base Product Setup | Cat/SubCat exist | 1. Go to Products. 2. Enter Name 'Basic Tee', map to Category. 3. Save. | Base product wrapper is created. Base ID is generated. |
| PIM-004 | Variants | SKU & Barcode Generation | Base Product exists | 1. Go to Variants. 2. Select 'Basic Tee'. 3. Select Size 'M'. 4. Enter Price 500. 5. Save. | Variant created. SKU auto-generates (e.g., `BAS-TEE-M`). System allows generation of unique Barcode. |
| PIM-005 | Variants | Barcode Duplicate Check | Variant exists | 1. Copy barcode from PIM-004. 2. Create new variant. 3. Paste identical barcode. 4. Save. | System throws validation error: 'The barcode has already been taken.' |
| PIM-006 | Pricing | Multi-Tier Price List | Variants exist | 1. Create Price List 'Festive'. 2. Set active dates (Tomorrow to Next Week). 3. Map 'Basic Tee' to 400 Rs. | Price list saves. POS will NOT apply this price today, but will apply it tomorrow. |
""",

    "3_Inventory_Warehouse_Enterprise_TestPlan": """# Enterprise Manual Test Plan: Inventory & Warehouse

## Test Execution Matrix

| TC ID | Module | Test Scenario | Pre-conditions | Test Steps | Expected Result |
|---|---|---|---|---|---|
| INV-001 | Warehouse| Infrastructure Mapping | None | 1. Create Warehouse 'W1'. 2. Create Zone 'Z1' in W1. 3. Create Rack 'R1' in Z1. 4. Create Bin 'B1' in R1. | Entire physical hierarchy saves without relational errors. |
| INV-002 | GRN | Stock Inwarding via GRN | Product exists | 1. Go to GRNs. 2. Select Vendor and 'W1'. 3. Add 'Basic Tee - M', Qty 100. 4. Approve GRN. | `Stocks` table shows 100 in W1. `Stock Ledgers` shows +100 transaction (Type: GRN). |
| INV-003 | Stock Adj| Manual Deduction (Damage) | Stock > 0 | 1. Go to Stock Adjustments. 2. Select 'Basic Tee - M' in 'W1'. 3. Enter Qty -5, Reason 'Damage'. | `Stocks` drops to 95. Ledger shows -5 (Type: Adjustment). |
| INV-004 | Transfer | Initiate Branch Transfer | Stock > 0 in W1 | 1. Go to Warehouse Transfers. 2. From 'W1' to 'Branch 1'. 3. Add Qty 20. 4. Approve and Dispatch. | 'W1' stock drops to 75. 'Branch 1' stock remains unchanged. `Transit Inventory` shows 20 units. |
| INV-005 | Receipt  | Acknowledge Transfer | Transfer in Transit | 1. Log in as Branch 1 user. 2. Go to Transfer Receipts. 3. Mark transfer from INV-004 as Received. | Transit Inventory drops to 0. 'Branch 1' stock increases by +20. |
| INV-006 | Audit | Physical Reconciliation | Stock exists | 1. Go to Inventory Counts. 2. System shows expected 75. 3. Enter physical count 74. 4. Approve. | Reconciliation generates automatic Stock Adjustment of -1. Final stock is 74. |
""",

    "4_Procurement_Vendor_Enterprise_TestPlan": """# Enterprise Manual Test Plan: Procurement & Vendor

## Test Execution Matrix

| TC ID | Module | Test Scenario | Pre-conditions | Test Steps | Expected Result |
|---|---|---|---|---|---|
| PUR-001 | Vendor | Vendor Duplicate GST | Vendor A has GST X | 1. Create Vendor B. 2. Enter GST X. 3. Click Save. | System throws unique constraint validation error on GST field. |
| PUR-002 | Request | Purchase Requisition Flow | None | 1. Manager logs in. 2. Creates PR for 50x Fabric. 3. Submits for approval. | PR Status changes to 'Submitted'. Awaiting PO conversion. |
| PUR-003 | PO | Convert PR to PO | PR exists | 1. Procurement logs in. 2. Opens PR. 3. Creates PO against Vendor A. 4. Enters rate 100/unit. | PO is generated in 'Draft' state. Total Value calculates to 5000. |
| PUR-004 | Payment| Outward Vendor Payment | PO Approved & Received | 1. Go to Vendor Payments. 2. Select Vendor A. 3. Enter Payment of 5000 via NEFT. | Vendor A's outstanding payable ledger decreases by 5000. |
| PUR-005 | Debit  | Purchase Return (Debit Note) | Stock exists from PO | 1. Go to Purchase Returns. 2. Select PO. 3. Return 5 units (Defective). | Stock decreases by 5. Vendor payable balance adjusts downwards by 500 (Debit Note created). |
""",

    "5_Point_of_Sale_Enterprise_TestPlan": """# Enterprise Manual Test Plan: POS & Retail Sales

## Test Execution Matrix

| TC ID | Module | Test Scenario | Pre-conditions | Test Steps | Expected Result |
|---|---|---|---|---|---|
| POS-001 | Register | Enforce Cash Register Open | Register Closed | 1. Go to POS Sales. 2. Attempt to add item to cart. | UI Blocks action. Alert: "Please open a cash register for this shift first." |
| POS-002 | Register | Open Register Float | Register Closed | 1. Go to Cash Openings. 2. Enter Opening Balance 1000. 3. Save. | Register status changes to 'Open'. Session ID is generated. |
| POS-003 | Sales | Standard Cash Checkout | Register Open, Stock > 0 | 1. Scan item (Price 500). 2. Add Customer. 3. Select Payment Mode: Cash. 4. Click Complete. | Receipt generated. Stock decreases by 1. Cash Register balance increases by 500. |
| POS-004 | Sales | Split Payment Method | Register Open, Stock > 0 | 1. Scan item (Price 1500). 2. Enter Cash: 500, Card: 1000. 3. Click Complete. | Sale successful. Cashbook reflects +500 Cash, +1000 Bank. |
| POS-005 | Tax | Discount Before Tax Calc | Item MRP 1000, GST 5% | 1. Scan item. 2. Apply 10% Flat Discount. 3. Verify Tax Breakdown. | Tax is calculated on 900 (Discounted Value), NOT 1000. |
| POS-006 | Return | Process Sales Return | Previous Sale exists | 1. Go to POS Returns. 2. Select Invoice from POS-003. 3. Return item, Refund Cash 500. | Stock increases by +1. Cash Register balance decreases by 500. |
| POS-007 | Close | EOD Register Closing | Register Open | 1. Go to Cash Closings. 2. Enter Physical Cash count (e.g., 1000 + 500 - 500 = 1000). | Register closes successfully. Discrepancy (Excess/Shortage) = 0. |
"""
}

# Remaining flows...
enterprise_flows.update({
    "6_CRM_Loyalty_Enterprise_TestPlan": """# Enterprise Manual Test Plan: CRM & Promotions

## Test Execution Matrix

| TC ID | Module | Test Scenario | Pre-conditions | Test Steps | Expected Result |
|---|---|---|---|---|---|
| CRM-001 | Loyalty| Earn Loyalty Points | Rule: 1 Pt per 100 Rs | 1. Create POS Sale for 550 Rs. 2. Select Customer X. | Customer X wallet shows 5 accrued points. |
| CRM-002 | Loyalty| Redeem Loyalty Points | Customer has 5 points | 1. Create POS Sale for 200 Rs. 2. Choose Pay with Points (5 pts). | Customer pays 195 Rs. Wallet drops to 0 points. |
| CRM-003 | Promo | Conditional BOGO Rule | Rule: Buy 2 Get 1 Free | 1. Add 1 Qty to Cart. Verify price. 2. Add 2 Qty. Verify price. 3. Add 3 Qty. Verify price. | Qty 1: Full price. Qty 2: Full price. Qty 3: Third item price changes to 0.00 (Discount applied). |
| CRM-004 | Coupon | Usage Limit Restriction | Coupon limit: 1/user | 1. Customer X uses 'FESTIVE10'. 2. Sale completes. 3. Customer X tries 'FESTIVE10' again. | First sale succeeds. Second sale throws error: "Coupon usage limit exceeded". |
""",

    "7_Finance_Accounting_Enterprise_TestPlan": """# Enterprise Manual Test Plan: Finance & Accounting

## Test Execution Matrix

| TC ID | Module | Test Scenario | Pre-conditions | Test Steps | Expected Result |
|---|---|---|---|---|---|
| FIN-001 | Ledger | Protect Root Accounts | Standard COA loaded | 1. Go to Chart of Accounts. 2. Select root 'Assets' account. 3. Click Delete. | Action blocked. Cannot delete root or system-locked ledger accounts. |
| FIN-002 | Journal| Enforce Double-Entry Match | None | 1. Create Journal. 2. Debit A/c X: 5000. 3. Credit A/c Y: 4000. 4. Save. | Save fails. Error: "Debits and Credits must balance". |
| FIN-003 | Journal| Successful Manual Entry | None | 1. Adjust FIN-002 so Credit A/c Y: 5000. 2. Save. | Journal saves. Ledgers update accurately. |
| FIN-004 | System | Verify Auto-Journal Lock | POS Sale executed | 1. Find the automated Journal Entry for the POS Sale. 2. Attempt to edit amounts or delete it. | Action blocked. System-generated journals cannot be modified manually to prevent fraud. |
| FIN-005 | Contra | Cash-to-Bank Transfer | Cash Balance > 10k | 1. Go to Contra Entries. 2. Transfer 10,000 from Cash Ledger to Bank Ledger. | Trial Balance shows Cash -10,000 and Bank +10,000. |
""",

    "8_HR_Payroll_Enterprise_TestPlan": """# Enterprise Manual Test Plan: HR & Payroll

## Test Execution Matrix

| TC ID | Module | Test Scenario | Pre-conditions | Test Steps | Expected Result |
|---|---|---|---|---|---|
| HRM-001 | Data | Unique PAN/Aadhar | Employee A exists with PAN X | 1. Create Employee B. 2. Enter PAN X. 3. Save. | System throws unique constraint error on PAN field. |
| HRM-002 | Leave | Exceeding Leave Balance | Balance: 12 days | 1. Employee requests 15 days Annual Leave. | System either rejects request or marks 3 days as Loss of Pay (LOP) based on policy. |
| HRM-003 | Salary | Component Math Logic | Basic=20k, HRA=40% of Basic | 1. Create Salary Structure. 2. Assign to Employee. | Preview calculates HRA strictly as 8000. |
| HRM-004 | Payroll| Process Monthly Batch | Month ended, LOP logged | 1. Go to Payroll Processings. 2. Run for current month. | Payslips generated. Basic pay for employees with LOP is pro-rated down (e.g. 20k / 30 * 27 days). |
| HRM-005 | Finance| Automated Salary Journal | Payroll Approved | 1. Check Finance Journals. | Journal auto-created: Debit Salary Expense, Credit Bank/Salary Payable. |
""",

    "9_GST_Taxation_Enterprise_TestPlan": """# Enterprise Manual Test Plan: GST Taxation (India)

## Test Execution Matrix

| TC ID | Module | Test Scenario | Pre-conditions | Test Steps | Expected Result |
|---|---|---|---|---|---|
| GST-001 | Master | HSN and Rate Mapping | None | 1. Create HSN '1234'. 2. Map to 18% GST Rate. 3. Assign to Product. | Product tax rules automatically lock to 18%. |
| GST-002 | Logic | Intra-State (CGST/SGST) | Branch & Customer in TN | 1. Create B2B Invoice for Product (18%). 2. Verify tax lines. | System splits 18% into 9% CGST and 9% SGST. IGST is 0. |
| GST-003 | Logic | Inter-State (IGST) | Branch TN, Customer KL | 1. Create B2B Invoice for Product (18%). 2. Verify tax lines. | System charges 18% IGST. CGST and SGST are 0. |
| GST-004 | Print | Tax Invoice PDF Output | B2B Sale executed | 1. Generate PDF for sale. | PDF strictly contains: Supplier/Customer GSTINs, HSN column, distinct Taxable Value / Tax Amount table. |
| GST-005 | TDS | Tax Deducted at Source | Vendor Service Invoice 50k | 1. Apply 10% TDS rule. | Payable to vendor reduces to 45k. 5k moves to TDS Liability ledger. |
""",

    "10_Asset_Service_Enterprise_TestPlan": """# Enterprise Manual Test Plan: Asset & Service Management

## Test Execution Matrix

| TC ID | Module | Test Scenario | Pre-conditions | Test Steps | Expected Result |
|---|---|---|---|---|---|
| AST-001 | Tracking| Asset Double Allocation | Asset X allocated to Br 1 | 1. Go to Asset Allocations. 2. Attempt to allocate Asset X to Br 2. | System prevents allocation until Asset X is returned from Br 1. |
| AST-002 | Finance | Depreciation Math | Asset Value 50k, 10% SL | 1. Run yearly depreciation. | Asset Book Value becomes 45k. Journal Entry auto-created for 5k depreciation expense. |
| AST-003 | Ticket | State Machine Workflow | None | 1. Create Service Request (Open). 2. Assign Technician (In Progress). 3. Complete (Resolved). | Status updates cleanly. Timestamps for SLA tracking are recorded. |
| AST-004 | Warranty| Claim Status Lock | Asset broken | 1. Log Warranty Claim with Vendor. | Asset status changes to 'In Repair'. Cannot be allocated to branches. |
""",

    "11_Reports_Analytics_Enterprise_TestPlan": """# Enterprise Manual Test Plan: Reports & Analytics

## Test Execution Matrix

| TC ID | Module | Test Scenario | Pre-conditions | Test Steps | Expected Result |
|---|---|---|---|---|---|
| RPT-001 | Filter | Date Boundary Precision | Sales exist on Oct 1 & Oct 2 | 1. Run Sales Report for Oct 1 (00:00 to 23:59). | ONLY Oct 1 sales sum up. Oct 2 sales are strictly excluded. |
| RPT-002 | Security| Tenancy Data Isolation | User limited to Branch 1 | 1. Log in as User. 2. Run Inventory Report. | User ONLY sees stock for Branch 1. No other branches appear in data or filters. |
| RPT-003 | Math | P&L Roll-up Accuracy | Sub-ledgers have balances | 1. Run P&L Statement. 2. Manually calculate sub-ledger sum. | The parent group (e.g., Total Expenses) exactly matches the sum of its children ledgers. |
| RPT-004 | Export | CSV Data Integrity | Report data visible | 1. Click Export to CSV. 2. Open in Excel. | Columns map correctly. Numbers are formatted as numeric values (not text). No HTML injection. |
"""
})

for name, content in enterprise_flows.items():
    md_file = os.path.join(output_dir, f"{name}.md")
    pdf_file = os.path.join(output_dir, f"{name}.pdf")
    
    with open(md_file, "w", encoding="utf-8") as f:
        f.write(content)
        
    print(f"Generating {pdf_file}...")
    pdf = MarkdownPdf(toc_level=0)
    pdf.add_section(Section(content))
    pdf.save(pdf_file)

print("All ENTERPRISE-GRADE tabular flow testing plans generated successfully!")
