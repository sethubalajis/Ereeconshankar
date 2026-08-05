import os
from markdown_pdf import Section, MarkdownPdf

output_dir = "Manual_Testing_Guides"
if not os.path.exists(output_dir):
    os.makedirs(output_dir)

flows = {
    "1_Organization_Administration_Flow_Detailed": """# Comprehensive Manual Testing Guide: Organization & Administration Flow

## 1. Overview
This flow forms the foundation of the ERP system. It manages the multi-branch hierarchy, organizational units, user access, and global permissions. Without this flow working perfectly, no other module can function correctly as they all rely on Branches and Users.

## 2. Pre-requisites
- Ensure the database is migrated.
- Ensure the default 'Admin' user exists and has full access.
- Ensure standard Cities, States, and Countries are populated in the database (or create a few manually for testing).

## 3. Test Cases: Organizational Structure

### Test Case 1.1: Create a Company (Positive & Negative)
**Objective:** Verify that the root organizational entity can be created.
- **Action 1 (Negative):** Navigate to `Companies` -> `Create Company`. Leave all fields blank and click `Save`.
- **Expected Result:** The system should throw validation errors for required fields (Name, Email, Phone, Country, State, City).
- **Action 2 (Positive):** Fill in all required fields. Select a Country -> State -> City. Upload a test logo image. Click `Save`.
- **Expected Result:** The company is created successfully. The logo image is stored in the correct storage directory. The list view displays the new company.

### Test Case 1.2: Create a Business Unit
**Objective:** Verify that logical segments of a company can be created.
- **Action:** Navigate to `Business Units` -> `Create`. Select the parent Company. Enter a Name (e.g., 'Textile Manufacturing').
- **Expected Result:** Saved successfully.
- **Validation Check:** Look at the `Code` column in the list view. It MUST automatically generate a sequential code like `BU-0001`.

### Test Case 1.3: Create a Branch (Crucial for Multi-Tenancy)
**Objective:** Verify physical branch creation and its linked address.
- **Action:** Navigate to `Branches` -> `Create`. Select the parent Company. Fill in address details and use the Country -> State -> City cascading dropdowns.
- **Validation Check:** Ensure that when a Country is selected, the State dropdown only shows states for that country.
- **Expected Result:** Saved successfully. The `Code` field must automatically generate a random 6-character alphanumeric string (e.g., `BR-A1B2C3`).

### Test Case 1.4: Departments & Designations
**Objective:** Verify auto-incrementing logic for organizational sub-entities.
- **Action:** Create 3 Departments under the newly created branch. Create 3 Designations.
- **Expected Result:** Department codes must increment strictly (e.g., `DPT-0001`, `DPT-0002`, `DPT-0003`). Designation codes must increment similarly (`DSG-0001`, `DSG-0002`).

## 4. Test Cases: Access Control & Multi-Tenancy

### Test Case 2.1: Role & Permission Assignment
**Objective:** Verify that roles can be customized with granular permissions.
- **Action:** Navigate to `Roles`. Create a new role named `Test Cashier`.
- **Action:** In the permissions checklist, select ONLY the following: `view_products`, `view_any_products`, `create_pos_sales`. Do NOT select any delete or user management permissions.
- **Expected Result:** Role saves successfully with exactly 3 permissions attached in the database pivot table.

### Test Case 2.2: User Creation & Tenancy Linking
**Objective:** Verify that users are restricted by branch (multi-tenancy) and role.
- **Action 1 (Create User):** Navigate to `Users` -> `Create`. Enter Name, unique Email, and Password. Select the `Test Cashier` role.
- **Action 2 (Physical Link):** Navigate to `Branch Users` -> Create a record linking the user to the newly created Branch.
- **Action 3 (Tenancy Link):** Navigate to `User Branch Accesses` -> Create a record linking the user to the Branch. (This is what allows them to log in to the dashboard for that branch).

### Test Case 2.3: Verification of Restrictions (The Final Test)
**Objective:** Ensure the system physically blocks unauthorized access.
- **Action:** Log out of the Admin account. Log in using the `Test Cashier` credentials.
- **Expected Result 1 (Tenancy):** The top navigation bar should display the active Branch. If you click the branch switcher, only the branch assigned in Test Case 2.2 should be visible.
- **Expected Result 2 (Permissions):** Look at the left sidebar menu. It should be virtually empty. The user should ONLY see the menus for `Products` and `POS Sales`. They should NOT be able to see `Users`, `Roles`, `Companies`, or `Settings`.
- **Expected Result 3 (Security Bypass):** Try to manually type the URL for the Users page (e.g., `/admin/users`). The system MUST throw a `403 Forbidden` error.
""",

    "2_Product_Merchandise_Flow_Detailed": """# Comprehensive Manual Testing Guide: Product & Merchandise Flow

## 1. Overview
The PIM (Product Information Management) flow is the heart of a retail/textile ERP. It handles hierarchical categorization, complex variants (size/color), and pricing.

## 2. Pre-requisites
- Organization flow must be tested and a working Branch must exist.
- Standard GST Rates and HSN codes (from Flow 9) should ideally exist, but testing can proceed without them initially.

## 3. Detailed Test Cases

### Test Case 1: Catalog Taxonomy Setup
**Objective:** Ensure product classifications work and hierarchy is maintained.
- **Action:** Navigate through `Product Categories`, `Sub-Categories`, `Product Lines`, and `Brands`.
- **Action:** Create a Category (e.g., 'Men'). Create a Sub-Category (e.g., 'Shirts') linking it to 'Men'. Create a Brand (e.g., 'Polo').
- **Validation:** Attempt to delete the 'Men' category.
- **Expected Result:** The system should either prevent deletion due to the foreign key constraint from the Sub-Category, or cascade the delete (check requirements). Usually, it should prevent it (Error 1451).

### Test Case 2: Textile Attribute Setup
**Objective:** Verify that dynamic attributes specific to textiles can be configured.
- **Action:** Navigate to `Product Attributes`. Create an attribute named 'Color'.
- **Action:** Add `Attribute Values` for 'Color' (e.g., 'Red', 'Blue', 'Green').
- **Action:** Repeat for 'Size' (S, M, L, XL) and 'Fabric Type' (Cotton, Silk).
- **Expected Result:** All attributes and values save correctly and are available globally.

### Test Case 3: Base Product Creation (Positive & Negative)
**Objective:** Verify the main product wrapper is created successfully.
- **Action 1 (Negative):** Go to `Products` -> `Create`. Enter only the product name and hit save.
- **Expected Result:** Validation errors for required fields (Category, UOM, Base Price, etc.).
- **Action 2 (Positive):** Fill all details (Name: 'Classic Polo Shirt', Category: Men, Sub-Category: Shirts, Brand: Polo). Upload a primary product image.
- **Expected Result:** Product is created successfully.

### Test Case 4: Variant Generation & Barcodes
**Objective:** Verify that specific SKUs are generated for the size/color combinations.
- **Action:** Go to `Product Variants` -> `Create`. Select the base product 'Classic Polo Shirt'.
- **Action:** Assign the attributes: Size = 'L', Color = 'Red'. Set the MRP and Selling Price.
- **Validation:** Check the SKU field. If it's auto-generated, ensure it follows a standard pattern (e.g., `POLO-RED-L`).
- **Action:** Go to `Product Barcodes`. Generate or manually assign a barcode string (e.g., `890123456789`) to this variant.
- **Expected Result:** The system prevents assigning the same barcode to two different variants (should throw a unique constraint error if tested).

### Test Case 5: Pricing Strategy
**Objective:** Ensure multiple price lists can be managed.
- **Action:** Go to `Price Lists` -> `Create`. Name it 'Diwali Wholesale Pricing'. Set active dates (valid from/to).
- **Action:** Add price list items for the variant created in Test Case 4, setting a lower wholesale price.
- **Expected Result:** The price list saves. When testing the POS later, this specific price list should activate if the conditions are met.
""",

    "3_Inventory_Warehouse_Flow_Detailed": """# Comprehensive Manual Testing Guide: Inventory & Warehouse Flow (WMS)

## 1. Overview
This flow manages physical stock movement, storage layouts, and stock reconciliation. Precision is critical as this dictates the valuation of the company.

## 2. Detailed Test Cases

### Test Case 1: Physical Infrastructure Configuration
**Objective:** Ensure the exact physical location of a product can be mapped.
- **Action:** Go to `Warehouses` -> Create 'Main Central Warehouse'.
- **Action:** Go to `Warehouse Zones` -> Create 'Zone A' linked to Main Central Warehouse.
- **Action:** Go to `Racks` -> Create 'Rack 1' linked to Zone A.
- **Action:** Go to `Bins` -> Create 'Bin 1A' linked to Rack 1.
- **Validation:** Look at the Bin list view. Ensure the relation chain (Warehouse -> Zone -> Rack -> Bin) displays correctly without N+1 query slowdowns.

### Test Case 2: Goods Receipt Note (GRN) - Inward Stock
**Objective:** Verify stock increments when goods arrive.
- **Action:** Go to `GRNs` -> `Create`.
- **Action:** Select a vendor, destination warehouse, and add items (select the Product Variants created in Flow 2). Enter a quantity of 100.
- **Action:** Save and 'Approve' or 'Submit' the GRN (if there is a status workflow).
- **Expected Result 1 (Stock Ledger):** Navigate to `Stock Ledgers`. You must see a new entry with Transaction Type 'GRN' for +100 quantity.
- **Expected Result 2 (Current Stock):** Navigate to `Stocks`. The current stock for this variant in the selected warehouse must be exactly 100.

### Test Case 3: Stock Adjustments (Loss/Damage)
**Objective:** Verify manual corrections to inventory.
- **Action:** Go to `Stock Adjustments` -> `Create`.
- **Action:** Select the same warehouse and variant. Set Adjustment Type to 'Decrease' (or enter -5 quantity) and Reason to 'Damaged'.
- **Expected Result:** The overall stock in `Stocks` should now be 95. A ledger entry for -5 should be visible.

### Test Case 4: Branch to Branch Transfer (Crucial Workflow)
**Objective:** Verify stock safely moves from a warehouse to a retail branch.
- **Action 1 (Initiate):** Go to `Warehouse Transfers` -> Create a transfer from 'Main Central Warehouse' to 'Retail Branch 1'. Add 20 units of the variant. Status = 'Pending'.
- **Action 2 (Approval):** Go to `Transfer Approvals` -> Approve the request.
- **Action 3 (Dispatch):** Go to `Picking Lists` and `Packing Lists` to simulate packing. Change transfer status to 'In Transit'.
- **Expected Result (Transit):** Stock at Warehouse decreases by 20. Stock at Branch is NOT YET increased. The 20 units must appear in `Transit Inventories`.
- **Action 4 (Receipt):** Go to `Transfer Receipts` (log in as a Branch User). Acknowledge receipt of the 20 units.
- **Expected Result (Final):** Transit inventory clears. Branch inventory increases by +20. Warehouse stock remains at 75.

### Test Case 5: Inventory Reconciliation
**Objective:** Verify physical stock-taking operations.
- **Action:** Go to `Inventory Counts` -> Start a stock take for the warehouse.
- **Action:** System shows expected stock is 75. Enter 'Physical Count' as 74.
- **Expected Result:** Go to `Inventory Reconciliations`. System should flag a discrepancy of -1 and automatically generate an adjustment entry to fix the system stock to 74 upon supervisor approval.
""",

    "4_Procurement_Vendor_Flow_Detailed": """# Comprehensive Manual Testing Guide: Procurement & Vendor Flow

## 1. Overview
Manages the supplier ecosystem and purchasing lifecycle.

## 2. Detailed Test Cases

### Test Case 1: Vendor Onboarding
**Objective:** Ensure supplier details and agreements are captured.
- **Action:** Go to `Vendors` -> Create a new Vendor. Fill GST details, address, and link to a `Vendor Category`.
- **Action:** Go to `Vendor Contacts` -> Add 2 contact persons for this vendor.
- **Validation:** Attempt to create another vendor with the same GST number. The system should throw a unique validation error.

### Test Case 2: The Procure-to-Pay Lifecycle
**Objective:** Test the full strict purchasing flow.
- **Step 1 (Requisition):** Log in as a Branch Manager. Go to `Purchase Requisitions`. Request 50 units of Fabric. Status = 'Submitted'.
- **Step 2 (RFQ):** Log in as Procurement Officer. Go to `RFQs`. Convert the requisition into an RFQ to 2 different vendors.
- **Step 3 (PO):** Vendor A replies with a better rate. Go to `Purchase Orders`. Create a PO linking to Vendor A. Add the 50 units and specific unit prices. 
- **Validation (PO Approval):** Ensure the PO cannot be 'Sent' to the vendor until authorized by a manager (status workflow).
- **Step 4 (GRN linkage):** (Cross-module check) Ensure the warehouse staff can create a GRN against this specific PO number, and the system pulls the items automatically.

### Test Case 3: Vendor Payments
**Objective:** Ensure financial tracking against vendors is accurate.
- **Action:** Go to `Vendor Payments`. Create a payment record for the PO above. Select payment mode (Bank Transfer), enter UTR number.
- **Expected Result:** The vendor's outstanding ledger balance should decrease by the payment amount.

### Test Case 4: Purchase Returns (Debit Notes)
**Objective:** Handle defective goods returned to suppliers.
- **Action:** Go to `Purchase Returns`. Select the Vendor and the specific PO. Return 5 units due to damage.
- **Expected Result 1:** Stock decreases by 5 units.
- **Expected Result 2:** A 'Debit Note' should be generated or the vendor's payable balance should be adjusted to reflect that we owe them less money.
""",

    "5_Point_of_Sale_Retail_Sales_Flow_Detailed": """# Comprehensive Manual Testing Guide: POS & Retail Sales

## 1. Overview
The most critical module for daily branch operations. Speed, accuracy, and offline-capability (if applicable) are key.

## 2. Detailed Test Cases

### Test Case 1: Shift & Register Management
**Objective:** Ensure daily cash control is strictly enforced.
- **Action (Opening):** Go to `Cash Openings`. Enter opening float (e.g., 2000 Rs). Note the timestamp and user.
- **Validation:** Try to perform a POS Sale *without* opening a register. The system MUST block the sale and alert the user to open a register first.
- **Action (Closing):** At the end of the shift, go to `Cash Closings`. Enter the physical cash counted.
- **Expected Result:** System compares physical cash vs (Opening + Cash Sales - Cash Refunds). It should highlight any shortage/excess.

### Test Case 2: The Core Billing Process
**Objective:** Ensure barcode scanning and billing is flawless.
- **Action:** Open `POS Sales`.
- **Action (Item Entry):** Scan a barcode (or manually select a Product Variant). Change quantity to 3.
- **Action (Pricing Check):** Ensure the unit price matches the defined MRP or active Price List.
- **Action (Customer):** Attach a Customer profile to the bill via mobile number search.
- **Action (Payment):** Proceed to checkout. Do a split payment (e.g., Total 1500: 500 Cash, 1000 UPI).
- **Expected Result:** Sale completes. A receipt is generated.
- **Database Verification:** Check `Stock Ledgers` -> Stock for that branch must decrease by 3. Check `CashBooks` -> Cash must increase by 500, Bank by 1000.

### Test Case 3: Sales Returns (Exchange/Refund)
**Objective:** Process customer returns safely.
- **Action:** Go to `POS Returns`. Enter the invoice number from Test Case 2.
- **Action:** Select 1 item to return. Choose refund method (Cash).
- **Expected Result:** Stock increases by 1. Cash register total decreases by the refund amount.

### Test Case 4: Discount & Tax Verifications
**Objective:** Ensure math is 100% accurate.
- **Action:** Create a sale for a 1000 Rs item. Apply a flat 10% line-item discount.
- **Validation:** Check the tax calculation. GST should be calculated on the discounted value (900 Rs), NOT the MRP (1000 Rs). Verify the final grand total is correct down to the decimal.
""",

    "6_CRM_Loyalty_Promotions_Flow_Detailed": """# Comprehensive Manual Testing Guide: CRM & Promotions

## 1. Overview
Manages marketing, retention, and customer satisfaction.

## 2. Detailed Test Cases

### Test Case 1: Customer Wallet & Loyalty Mechanics
**Objective:** Ensure points accrue and can be redeemed accurately.
- **Pre-requisite:** Create a `Loyalty Program` (Rule: Earn 1 point per 100 Rs spent, 1 point = 1 Rs value).
- **Action (Earn):** Perform a POS sale for 550 Rs for "Customer A".
- **Expected Result:** Check `Customer Wallets` or `Loyalty Points` table. Customer A should now have 5 points.
- **Action (Redeem):** Perform another POS sale for 200 Rs for Customer A. During payment, choose to redeem 5 loyalty points.
- **Expected Result:** Customer pays 195 Rs via Cash/Card. The 5 points are deducted from their wallet.

### Test Case 2: Complex Promotions (BOGO)
**Objective:** Ensure promotional engines trigger only when conditions are met.
- **Action:** Create a `Buy-X-Get-Y Offer`. Rule: Buy 2 'Classic Polo Shirts', Get 1 'Socks' Free. Valid only for today.
- **Action (Negative):** Add 1 Polo Shirt and 1 Socks to POS.
- **Expected Result:** No discount applied. Socks charged at full price.
- **Action (Positive):** Add 2 Polo Shirts and 1 Socks to POS.
- **Expected Result:** The price of the Socks line-item should instantly drop to 0.00. The invoice should state "BOGO Discount Applied".

### Test Case 3: Coupon Code Validation
**Objective:** Prevent coupon abuse.
- **Action:** Create a `Promo Code` (Code: 'WELCOME10', 10% off, Usage Limit: 1 per customer).
- **Action:** Customer A uses 'WELCOME10' on an invoice. Works perfectly.
- **Validation:** Customer A attempts to use 'WELCOME10' on a second invoice.
- **Expected Result:** System MUST throw an error: "Coupon usage limit exceeded for this customer".

### Test Case 4: Complaint Ticketing Workflow
**Objective:** Track customer grievances to resolution.
- **Action:** Go to `Complaint Tickets`. Create a ticket regarding 'Defective zipper'. Status = 'Open'.
- **Action:** Assign it to a staff member. Add a resolution note. Change status to 'Resolved'.
- **Expected Result:** Timeline of ticket states should be preserved.
""",

    "7_Finance_Accounting_Flow_Detailed": """# Comprehensive Manual Testing Guide: Finance & Accounting Flow

## 1. Overview
Double-entry bookkeeping validation. If this module fails, the ERP fails audit.

## 2. Detailed Test Cases

### Test Case 1: Chart of Accounts Constraints
**Objective:** Ensure the financial tree is structured logically.
- **Action:** View `Chart of Accounts`. Ensure root types exist (Assets, Liabilities, Equity, Revenue, Expenses).
- **Validation:** Attempt to delete a root account (e.g., 'Revenue').
- **Expected Result:** System completely blocks deletion (hard-coded or restricted).
- **Action:** Create a sub-ledger under Expenses called 'Branch Electricity'.

### Test Case 2: Double-Entry Validation (The Core Test)
**Objective:** Ensure impossible unbalanced journals cannot be saved.
- **Action (Negative):** Go to `Journals` -> `Create Journal Entry`.
  - Line 1: Debit 'Branch Electricity' for 5000.
  - Line 2: Credit 'Cash' for 4000.
  - Click Save.
- **Expected Result:** System throws a hard error: "Journal is unbalanced. Total Debits (5000) must equal Total Credits (4000)".
- **Action (Positive):** Adjust Line 2 Credit to 5000. Click Save.
- **Expected Result:** Entry posts successfully.

### Test Case 3: Automated Ledger Postings
**Objective:** Verify that operational actions trigger financial entries automatically.
- **Action:** Perform a POS Sale for 1000 Rs cash (from Flow 5).
- **Expected Result:** Navigate to `Journal Entries`. You should see an auto-generated entry:
  - Debit: Cash Account (1000)
  - Credit: Sales Revenue Account (1000)
  - (If COGS is tracked perpetually): Debit COGS, Credit Inventory.
- **Validation:** Verify that these auto-generated entries CANNOT be manually edited or deleted by standard accounting staff to prevent fraud.

### Test Case 4: Contra Entries & Cash Flow
**Objective:** Track money moving between internal accounts (Cash to Bank).
- **Action:** Go to `Contra Entries`. Transfer 10,000 from 'Cash in Hand' to 'HDFC Current A/c'.
- **Expected Result:** Verify `Trial Balance` report. Cash decreases by 10k, HDFC increases by 10k.
""",

    "8_HR_Payroll_Flow_Detailed": """# Comprehensive Manual Testing Guide: HR & Payroll Flow (HRMS)

## 1. Overview
Manages human capital, time tracking, and complex salary formulas.

## 2. Detailed Test Cases

### Test Case 1: Employee Data Integrity
**Objective:** Ensure crucial PII and employment data is captured safely.
- **Action:** Go to `Employees` -> Create employee. Check fields: PAN, Aadhar, Bank Account Details, Date of Joining.
- **Validation:** Try to create another employee with the same PAN or Email. System must reject it.
- **Action:** Upload `Employee Documents` (PDF ID proofs). Verify they can be downloaded later.

### Test Case 2: Leave & Attendance Rules
**Objective:** Ensure time tracking strictly dictates payroll.
- **Action:** Go to `Leave Types`. Check 'Annual Leave' (Balance: 12/year).
- **Action:** Employee applies for 3 days leave via `Employee Leaves`. Manager approves.
- **Expected Result:** Employee's remaining leave balance updates to 9.
- **Validation:** Employee attempts to apply for 10 days leave (exceeding balance). System should reject or flag as 'Loss of Pay' (LOP) depending on configuration.

### Test Case 3: Salary Structure Configuration
**Objective:** Test the math behind compensation.
- **Action:** Go to `Salary Components`. 
  - Create 'Basic Pay' (Earning, Flat Amount).
  - Create 'HRA' (Earning, Formula: Basic * 0.4).
  - Create 'PF' (Deduction, Formula: Basic * 0.12).
- **Action:** Create a `Salary Structure` combining these, assigning a base of 20,000 to the Basic Pay.
- **Expected Result:** System should preview the calculations: HRA = 8000, PF = 2400. Gross = 28000. Net = 25600.

### Test Case 4: Payroll Processing Cycle
**Objective:** Process a monthly batch of salaries.
- **Action:** Go to `Payroll Processings`. Select the month (e.g., October) and target Branch. Click 'Run Payroll'.
- **Expected Result 1 (Calculation):** System generates salary slips for all employees in that branch.
- **Expected Result 2 (LOP Handling):** For the employee with 'Loss of Pay' days, verify their Basic Pay is pro-rated correctly based on working days.
- **Expected Result 3 (Finance Link):** Once Payroll is 'Approved/Paid', verify an automated Journal Entry is created in the Finance module crediting the Bank and debiting Salary Expense.
""",

    "9_GST_Taxation_Compliance_Flow_Detailed": """# Comprehensive Manual Testing Guide: GST Taxation & Compliance (India)

## 1. Overview
Ensures legal compliance with Indian tax laws. Miscalculations here result in severe penalties.

## 2. Detailed Test Cases

### Test Case 1: HSN and Rate Integrity
**Objective:** Ensure master tax data cannot be entered incorrectly.
- **Action:** Go to `GST Rates`. Ensure fixed slabs (0, 5, 12, 18, 28) exist.
- **Action:** Go to `HSN/SAC Codes`. Create code '6105' (Men's Shirts) and map to 5% GST rate.
- **Action:** Go to `Products` and assign HSN '6105' to the Classic Polo Shirt.
- **Expected Result:** Changes cascade properly. The product is now officially a 5% tax item.

### Test Case 2: Intra-State vs Inter-State Tax Split (Crucial Test)
**Objective:** Verify the core engine that splits IGST vs CGST/SGST based on addresses.
- **Pre-requisite:** Ensure the Company HQ is set to State: 'Tamil Nadu' (Code 33).
- **Action (Intra-State):** Create a B2B Sale Invoice to a Customer whose address is also in 'Tamil Nadu'. Add the Polo Shirt (1000 Rs).
- **Expected Result:** The invoice MUST display: CGST 2.5% (25 Rs) and SGST 2.5% (25 Rs). Total = 1050 Rs. IGST must be 0.
- **Action (Inter-State):** Change the Customer's address to 'Kerala' (Code 32). Recalculate the invoice.
- **Expected Result:** The invoice MUST display: IGST 5% (50 Rs). CGST and SGST must be 0. Total = 1050 Rs.

### Test Case 3: Tax Invoice Generation (B2B)
**Objective:** Verify the legal document output.
- **Action:** Generate the PDF for the `Tax Invoice` created above.
- **Validation:** Visually inspect the PDF. It MUST contain:
  - Supplier GSTIN
  - Customer GSTIN
  - HSN Codes column for items
  - Distinct tax breakdown table (Taxable Value, CGST amount, SGST amount).
  - "Tax Invoice" written at the top.

### Test Case 4: TDS Calculation (If applicable)
**Objective:** Verify Tax Deducted at Source on vendor services.
- **Action:** Create a Purchase Invoice for 'Consulting Services' from a Vendor for 50,000 Rs.
- **Action:** Apply a 10% TDS rule in `TDS Records` against this transaction.
- **Expected Result:** The payable amount to the vendor reduces to 45,000. 5,000 is moved to a TDS Payable liability ledger automatically.
""",

    "10_Asset_Service_Management_Flow_Detailed": """# Comprehensive Manual Testing Guide: Asset & Service Management

## 1. Overview
Tracks internal machinery (e.g., sewing machines, laptops) and customer service repairs.

## 2. Detailed Test Cases

### Test Case 1: Asset Registration and Allocation
**Objective:** Ensure hardware is tracked to physical locations.
- **Action:** Go to `Assets`. Register a 'Brother Sewing Machine S-7200'. Enter Serial Number, Purchase Price (50,000), Purchase Date.
- **Action:** Go to `Asset Allocations`. Allocate this machine to 'Branch 2'.
- **Validation:** Attempt to allocate the same machine to 'Branch 3' on the same date without returning it. System should prevent double-allocation.

### Test Case 2: Asset Depreciation
**Objective:** Verify automated financial write-downs.
- **Action:** Go to `Asset Depreciations`. Set rule: Straight Line, 10% per year. Run depreciation for the financial year end.
- **Expected Result:** The current book value of the machine drops to 45,000. An automated journal entry debits Depreciation Expense and credits Accumulated Depreciation.

### Test Case 3: Customer Service Request (Ticketing)
**Objective:** Verify repair tracking workflows.
- **Action:** Go to `Service Requests`. Create a ticket for a customer (e.g., 'Custom tailoring alteration'). Status = 'Logged'.
- **Action:** Go to `Service Assignments`. Assign the ticket to Technician A.
- **Action:** Log in as Technician A. Update the ticket status to 'In Progress'. Add notes regarding fabric required.
- **Action:** Once done, technician marks it as 'Completed'.
- **Expected Result:** The customer (if integrated via SMS/Email) is notified. The operations manager can see turnaround times in reports.

### Test Case 4: Warranty Claims
**Objective:** Track replacement processes with vendors.
- **Action:** Go to `Warranty Claims`. Select a broken asset that is still within the warranty date range.
- **Action:** Log the claim sent to the OEM/Vendor. Track status from 'Sent' to 'Replaced/Repaired'.
- **Expected Result:** Asset status updates to 'In Repair' preventing it from being allocated to branches until the claim is resolved.
""",

    "11_Reports_Analytics_Flow_Detailed": """# Comprehensive Manual Testing Guide: Reports & Analytics Flow

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
"""
}

for name, content in flows.items():
    md_file = os.path.join(output_dir, f"{name}.md")
    pdf_file = os.path.join(output_dir, f"{name}.pdf")
    
    with open(md_file, "w", encoding="utf-8") as f:
        f.write(content)
        
    print(f"Generating {pdf_file}...")
    pdf = MarkdownPdf(toc_level=0)
    pdf.add_section(Section(content))
    pdf.save(pdf_file)

print("All DETAILED flow testing guides generated successfully!")
