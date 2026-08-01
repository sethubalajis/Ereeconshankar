import os
from markdown_pdf import Section, MarkdownPdf

# Create output directory
output_dir = "Manual_Testing_Guides"
if not os.path.exists(output_dir):
    os.makedirs(output_dir)

flows = {
    "1_Organization_Administration_Flow": """# Testing Guide: Organization & Administration Flow

## Overview
This flow manages the foundational setup, branch hierarchy, and access control of the ERP.

## Components to Test
Companies, Business Units, Branches, Departments, Designations, Users, Roles, Permissions.

## Step-by-Step Manual Testing
1. **Companies & Branches (Hierarchy)**
   - Go to `Companies` -> Create a new company. Verify location dropdowns (Country, State, City) work.
   - Go to `Business Units` -> Create a BU linked to the company. Verify auto-generated BU code.
   - Go to `Branches` -> Create a branch under the company. Verify auto-generated BR code.
   - Go to `Departments` -> Create a department under the branch. Verify auto-generated DPT code.
   - Go to `Designations` -> Create a designation. Verify auto-generated DSG code.

2. **Access Control (Roles & Users)**
   - Go to `Roles` -> Create a test role (e.g., 'Cashier') with limited permissions (e.g., only 'view_products').
   - Go to `Users` -> Create a user and assign the 'Cashier' role.
   - Go to `User Branch Accesses` -> Grant the new user access to specific branches.
   - Go to `Branch Users` -> Assign the user to a primary branch.

3. **Verification**
   - Log out and log in as the new user.
   - **Check:** Can you only see the branches assigned in `User Branch Accesses` in the tenant switcher?
   - **Check:** Are the navigation menus restricted to only what the 'Cashier' role allows?
""",
    "2_Product_Merchandise_Flow": """# Testing Guide: Product & Merchandise Flow (PIM)

## Overview
This flow handles the complex product catalog typical for a textile business, including variants, pricing, and attributes.

## Components to Test
Products, Categories, Sub-Categories, Lines, Brands, Variants, Attributes, Colors, Sizes, Styles, Seasons, Designs, Fabric Types, Price Lists.

## Step-by-Step Manual Testing
1. **Catalog Foundation**
   - Go to `Brands`, `Colors`, `Sizes`, `Styles`, `Seasons`, `Designs`, `Fabric Types`, `Pattern Types` and create 1-2 sample records in each.
   - Go to `Product Categories`, `Sub-Categories`, and `Lines` and create a hierarchical structure.

2. **Product Creation**
   - Go to `Products` -> Create a new base product (e.g., 'Cotton T-Shirt'). Assign it to a category, brand, and fabric type.
   - **Check:** Does the form save without SQL errors?

3. **Product Variants & Attributes**
   - Go to `Product Attributes` -> Create an attribute (e.g., 'Fit'). Add `Attribute Values` (e.g., 'Slim', 'Regular').
   - Go to `Product Variants` -> Create a variant for the base product (e.g., 'Cotton T-Shirt - Red - XL').
   - **Check:** Ensure the SKU or Barcode is generated or can be assigned correctly.

4. **Pricing**
   - Go to `Price Lists` -> Create a base retail price list and assign it to the products/variants.
   - **Check:** Verify that the prices reflect correctly in the POS or Sales screens later.
""",
    "3_Inventory_Warehouse_Flow": """# Testing Guide: Inventory & Warehouse Flow (WMS)

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
""",
    "4_Procurement_Vendor_Flow": """# Testing Guide: Procurement & Vendor Flow

## Overview
Manages the purchasing cycle from requesting items to paying suppliers.

## Components to Test
Vendors, Categories, Agreements, Price Lists, Purchase Requisitions, RFQs, Purchase Orders, Returns, Payments.

## Step-by-Step Manual Testing
1. **Vendor Setup**
   - Go to `Vendor Categories` -> Create a category (e.g., 'Fabric Suppliers').
   - Go to `Vendors` -> Create a new vendor and link to the category. Add `Vendor Contacts`.

2. **Procurement Cycle**
   - Go to `Purchase Requisitions` -> Create an internal request for products.
   - Go to `RFQs` (Request for Quotation) -> Convert the requisition into an RFQ for the vendor.
   - Go to `Purchase Orders` (PO) -> Convert the RFQ into a PO. Add `Purchase Order Items`.
   - **Check:** Ensure status transitions smoothly (Draft -> Approved -> Sent).

3. **Receiving & Payment**
   - (Link with Inventory) Create a GRN against this PO.
   - Go to `Vendor Payments` -> Record a payment against the vendor's invoice.
   - Go to `Purchase Returns` -> Log a return for defective items.
   - **Check:** Does the return adjust the vendor balance and stock levels?
""",
    "5_Point_of_Sale_Retail_Sales_Flow": """# Testing Guide: Point of Sale (POS) & Retail Sales Flow

## Overview
Handles the front-end billing and retail operations at branch locations.

## Components to Test
POS Terminals, Counters, POS Sales, Sale Items, Returns, Cash Registers, Cash Openings/Closings, Payments, Receipts.

## Step-by-Step Manual Testing
1. **Infrastructure Setup**
   - Go to `POS Terminals` and `POS Counters` -> Create a terminal for a specific branch.

2. **Shift Management (Cash Register)**
   - Go to `Cash Openings` -> Open a register with a float amount (e.g., 5000 Rs).
   - **Check:** Is the register marked as 'Open'?

3. **Sales Processing**
   - Go to `POS Sales` -> Create a new sale transaction. Add products to `POS Sale Items`.
   - Apply taxes (if applicable) and discounts.
   - Go to `Payments` -> Record the payment for the sale (Cash/Card/UPI).
   - **Check:** Did the stock decrease in the branch inventory? Did the Cash Register total update?

4. **Returns & Closing**
   - Go to `POS Returns` -> Process a return for an item sold.
   - **Check:** Did the stock return to inventory?
   - Go to `Cash Closings` -> Close the register at the end of the day.
   - **Check:** Does the closing amount match the opening + sales - returns?
""",
    "6_CRM_Loyalty_Promotions_Flow": """# Testing Guide: CRM, Loyalty & Promotions Flow

## Overview
Manages customer relationships, retention, and marketing offers.

## Components to Test
Customers, Groups, Wallets, Feedback, Loyalty Programs, Points, Cards, Promo Codes, Coupons, Combo Offers, Complaint Tickets.

## Step-by-Step Manual Testing
1. **Customer & Loyalty Setup**
   - Go to `Customer Groups` -> Create a group (e.g., 'VIP').
   - Go to `Customers` -> Create a customer.
   - Go to `Loyalty Programs` and `Membership Plans` -> Set up a point-earning rule (e.g., 1 point per 100 Rs).

2. **Promotions & Offers**
   - Go to `Promo Codes` or `Coupons` -> Create a 10% off coupon valid for a specific date range.
   - Go to `Buy-X-Get-Y Offers` or `Combo Offers` -> Create a promotional offer.

3. **Verification in Sales**
   - Process a `POS Sale` (from Flow 5) for this customer.
   - **Check:** Apply the Promo Code. Did the total decrease by 10%?
   - **Check:** After the sale, go to `Loyalty Points` or `Customer Wallets`. Did the customer earn the correct amount of points based on the loyalty program rules?

4. **Customer Support**
   - Go to `Complaint Tickets` -> Log a ticket for the customer.
   - **Check:** Update the status from 'Open' to 'Resolved'.
""",
    "7_Finance_Accounting_Flow": """# Testing Guide: Finance & Accounting Flow

## Overview
Handles the core financial ledgers and accounting entries.

## Components to Test
Chart of Accounts, Account Groups, Ledgers, Cost Centers, Bank Accounts, Journals, Journal Entries, Contra Entries, Trial Balances.

## Step-by-Step Manual Testing
1. **Chart of Accounts Setup**
   - Go to `Account Groups` -> Create a group (e.g., 'Current Assets').
   - Go to `Chart of Accounts` / `Ledgers` -> Create a ledger (e.g., 'HDFC Bank Account') under the group.
   - Go to `Cost Centers` -> Create a cost center for a specific branch.

2. **Manual Journal Entries**
   - Go to `Journals` -> Create a general journal.
   - Go to `Journal Entries` -> Add debit and credit lines. (e.g., Debit Rent Expense, Credit Cash).
   - **Check:** Ensure the system enforces that Total Debits = Total Credits before saving.

3. **Contra Entries & Verification**
   - Go to `Contra Entries` -> Record a cash deposit into the bank.
   - **Check:** Go to the `Trial Balances` or `Ledgers` report. Verify that the balances for Cash and Bank updated correctly reflecting the double-entry bookkeeping rules.
""",
    "8_HR_Payroll_Flow": """# Testing Guide: HR & Payroll Flow (HRMS)

## Overview
Manages employee lifecycles and salary processing.

## Components to Test
Employees, Attendance, Leaves, Shifts, Salary Structures, Payroll Cycles, Payroll Processing, Deductions, Allowances.

## Step-by-Step Manual Testing
1. **Employee Onboarding**
   - Go to `Employees` -> Create an employee. Link them to a Designation and Branch.
   - Go to `Shifts` -> Create a work shift and assign it to the employee.

2. **Time & Leave Management**
   - Go to `Leave Types` -> Create 'Sick Leave'.
   - Go to `Employee Leaves` -> Apply for leave for the employee and approve it.
   - Go to `Attendances` -> Log attendance for a few days.

3. **Payroll Setup & Processing**
   - Go to `Salary Components` -> Create Basic Pay (Earnings) and PF (Deductions).
   - Go to `Salary Structures` -> Combine components and assign it to the employee via `Employee Salary Assignments`.
   - Go to `Payroll Cycles` -> Define the current month.
   - Go to `Payroll Processings` -> Run payroll for the branch/employee.
   - **Check:** Verify the generated payslip calculates Basic Pay + Allowances - Deductions (including unpaid leave deductions) correctly.
""",
    "9_GST_Taxation_Compliance_Flow": """# Testing Guide: GST Taxation & Compliance Flow (India)

## Overview
Handles Indian statutory requirements and tax filings.

## Components to Test
GST Categories, Rates, HSN/SAC Codes, Tax Groups, Tax Invoices, E-Invoices, E-Way Bills, GST Returns, TDS/TCS.

## Step-by-Step Manual Testing
1. **Tax Configuration**
   - Go to `GST Categories` and `GST Rates` -> Ensure standard rates (e.g., 5%, 12%, 18%) exist.
   - Go to `HSN/SAC Codes` -> Create an HSN code for a textile product and link the GST rate (e.g., 5%).
   - Link this HSN code to a `Product` in the PIM flow.

2. **Tax Application**
   - Create a `Purchase Order` (intra-state) or a B2B `POS Sale`.
   - **Check:** Verify that the system automatically splits the 5% GST into 2.5% CGST and 2.5% SGST.
   - Create an inter-state sale.
   - **Check:** Verify that the system applies 5% IGST instead.

3. **Compliance Documents**
   - Go to `Tax Invoices` -> Generate a formal B2B tax invoice.
   - **Check:** Ensure the GSTIN numbers (company and customer) and HSN codes print on the invoice.
   - Go to `TDS/TCS Records` -> If applicable, verify TDS is deducted on specific vendor payments.
""",
    "10_Asset_Service_Management_Flow": """# Testing Guide: Asset & Service Management Flow

## Overview
Tracks company-owned assets and handles maintenance or external servicing.

## Components to Test
Assets, Categories, Allocations, Depreciations, Maintenances, Disposals, Service Requests, Technician Visits.

## Step-by-Step Manual Testing
1. **Asset Lifecycle**
   - Go to `Asset Categories` -> Create a category (e.g., 'Sewing Machines').
   - Go to `Assets` -> Register a new machine with its purchase value and date.
   - Go to `Asset Allocations` -> Allocate the machine to a specific Branch or Employee.
   - Go to `Asset Depreciations` -> Run a depreciation cycle.
   - **Check:** Verify the asset's current book value decreases correctly based on the depreciation method.

2. **Maintenance & Servicing**
   - Go to `Asset Maintenances` -> Schedule a routine maintenance for the machine.
   - Go to `Service Requests` -> Log a breakdown ticket.
   - Go to `Technician Visits` -> Assign a technician and log their visit details and resolution.
   - **Check:** Ensure the service request status changes to 'Resolved'.
""",
    "11_Reports_Analytics_Flow": """# Testing Guide: Reports & Analytics Flow

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
"""
}

for name, content in flows.items():
    md_file = os.path.join(output_dir, f"{name}.md")
    pdf_file = os.path.join(output_dir, f"{name}.pdf")
    
    # Write Markdown
    with open(md_file, "w", encoding="utf-8") as f:
        f.write(content)
        
    # Convert to PDF
    print(f"Generating {pdf_file}...")
    pdf = MarkdownPdf(toc_level=0)
    pdf.add_section(Section(content))
    pdf.save(pdf_file)

print("All flow testing guides generated successfully!")
