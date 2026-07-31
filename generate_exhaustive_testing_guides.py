import os
from markdown_pdf import Section, MarkdownPdf

output_dir = "Manual_Testing_Guides"
if not os.path.exists(output_dir):
    os.makedirs(output_dir)

flows = {
    "1_Organization_Administration_Flow_Exhaustive": """# Exhaustive Manual Testing Guide: Organization & Administration Flow

## 1. Overview & Architecture Scope
The Organization & Administration Flow is the foundational pillar of the Textile Multi-Branch ERP system. It establishes the enterprise corporate structure, physical branch locations, departmental divisions, staff designations, multi-tenant branch isolation, financial calendar periods, user authentication, and granular role-based permissions (RBAC).

### Target Components & Resources:
- **Structure:** Companies, Business Units, Branches, Departments, Designations
- **Access Control:** Users, Roles, Permissions, Branch Users, User Branch Accesses
- **Settings:** Branch Settings, Financial Years, Dashboard Widgets, States, Cities, Countries

---

## 2. Pre-requisites & Test Environment Setup
1. Laravel/Filament application database migrated cleanly (`php artisan migrate:fresh`).
2. Super Admin user created with unrestricted global permissions.
3. Master geographic data seeded (Countries: India; States: Tamil Nadu, Kerala, Karnataka; Cities: Chennai, Coimbatore, Madurai, Bengaluru).

---

## 3. Comprehensive Test Cases

### 3.1 Organizational Structure Module

#### Test Case 1.1: Company Creation (Positive & Negative Validation)
- **Objective:** Verify root enterprise entity configuration and validation rules.
- **Pre-conditions:** Logged in as Super Admin.
- **Action 1 (Negative Validation):** Navigate to `Companies` -> `Create Company`. Click `Save` with all fields empty.
  - **Expected Result:** Validation errors must trigger for mandatory fields: Company Name, Email, Registration Number, Phone, Address, Country, State, City.
- **Action 2 (Positive Creation):** Enter `Apex Textiles Ltd`, Email `info@apextextiles.com`, GSTIN `33AAAAA0000A1Z5`, select Country `India` -> State `Tamil Nadu` -> City `Chennai`. Upload valid company logo PNG. Click `Save`.
  - **Expected Result:** Record saved. Logo stored in public/storage directory. Company code auto-generated.

#### Test Case 1.2: Business Unit Mapping & Code Generation
- **Objective:** Verify logical divisions under a company.
- **Action:** Navigate to `Business Units` -> `Create`. Select Parent Company `Apex Textiles Ltd`. Enter Name `Spinning & Weaving Division`.
- **Expected Result:** Saved successfully.
- **Database / UI Audit:** Verify `Code` field is automatically formatted sequentially (e.g., `BU-0001`).

#### Test Case 1.3: Multi-Branch Creation & Cascading Dropdowns
- **Objective:** Map physical branches with cascading geographic selects.
- **Action 1 (Cascading Check):** Navigate to `Branches` -> `Create`. Select Country `India`. Observe State dropdown. Select `Tamil Nadu`. Observe City dropdown.
  - **Expected Result:** State dropdown lists ONLY Indian states. City dropdown lists ONLY Tamil Nadu cities.
- **Action 2 (Branch Save):** Set Branch Name `Coimbatore Main Branch`, Type `Retail & Warehouse`. Save.
  - **Expected Result:** Record saved. Branch Code auto-generated as a 6-character random uppercase alphanumeric string (e.g., `BR-X79K2P`).

#### Test Case 1.4: Departments & Designations Code Incrementing
- **Objective:** Verify sub-entity creation under branches.
- **Action:** Navigate to `Departments`. Create 3 departments (`Sales`, `Inventory`, `Finance`) for `Coimbatore Main Branch`. Navigate to `Designations` and create `Floor Manager`, `Cashier`, `Storekeeper`.
- **Expected Result:** Codes increment sequentially (`DPT-0001`, `DPT-0002`, `DPT-0003` and `DSG-0001`, `DSG-0002`, `DSG-0003`).

---

### 3.2 Access Control & Multi-Tenancy Module

#### Test Case 2.1: Granular Role & Permission Matrix Assignment
- **Objective:** Create custom roles with strict permission limits.
- **Action:** Navigate to `Roles` -> `Create`. Name role `Branch Cashier`.
- **Action:** In permissions grid, enable ONLY: `view_pos_sales`, `create_pos_sales`, `view_products`, `view_any_products`. Leave all user management, company, and financial ledger permissions unchecked.
- **Expected Result:** Saved. Database pivot table `role_has_permissions` stores exactly 4 permissions.

#### Test Case 2.2: User Onboarding & Multi-Branch Access Linking
- **Objective:** Link a user to roles and physical branch tenants.
- **Action 1:** Navigate to `Users` -> `Create`. Enter Name `Ravi Kumar`, Email `cashier.cbe@apex.com`, Password `SecretPassword123!`. Assign Role `Branch Cashier`.
- **Action 2:** Navigate to `Branch Users` -> Create link between `Ravi Kumar` and `Coimbatore Main Branch`.
- **Action 3:** Navigate to `User Branch Accesses` -> Grant access for `Coimbatore Main Branch`.
- **Expected Result:** User created successfully with encrypted password in DB.

#### Test Case 2.3: Security Isolation & Direct URL Hardening (Security Pass)
- **Objective:** Ensure multi-tenancy and permissions strictly block unauthorized access.
- **Action 1 (Login):** Log out Admin. Log in as `cashier.cbe@apex.com`.
  - **Expected Result:** Navigation header displays active tenant `Coimbatore Main Branch`. Sidebar ONLY displays `POS Sales` and `Products`.
- **Action 2 (Direct URL Bypass Attack):** Manually type `/admin/users` or `/admin/companies` in browser URL bar.
  - **Expected Result:** System intercepts request and returns `403 Forbidden` / `Unauthorized Access` page.

---

### 3.3 System Settings & Administration Module

#### Test Case 3.1: Financial Year Date Range Constraints
- **Objective:** Ensure valid non-overlapping financial calendars.
- **Action 1 (Positive):** Navigate to `Financial Years` -> `Create`. Set Title `FY 2026-2027`, Start Date `2026-04-01`, End Date `2027-03-31`, Status `Active`. Save.
  - **Expected Result:** Saved successfully.
- **Action 2 (Negative Overlap Check):** Create another FY with Start Date `2026-10-01`.
  - **Expected Result:** System flags validation error: "Financial year dates overlap with existing active financial year."

#### Test Case 3.2: Branch Settings & Receipt Header Customization
- **Objective:** Configure branch-specific invoice/receipt header metadata.
- **Action:** Navigate to `Branch Settings`. Select `Coimbatore Main Branch`. Set POS Receipt Footer text to "Thank you for shopping at Apex Textiles! No Cash Refunds."
- **Expected Result:** Settings saved and reflected on printed POS receipts for this branch.

---

## 4. Summary & Verification Checklist
| Module Component | Expected Result | Pass Criteria |
| :--- | :--- | :--- |
| Company / Branch Setup | Hierarchy linked cleanly | Branch code auto-generated |
| RBAC / Roles | Permissions enforced | Unauthorized menus hidden |
| Multi-Tenancy | Tenant isolation active | URL bypass throws 403 Forbidden |
""",

    "2_Product_Merchandise_Flow_Exhaustive": """# Exhaustive Manual Testing Guide: Product & Merchandise Flow (PIM)

## 1. Overview & Architecture Scope
The Product Information Management (PIM) flow handles the multi-tier product catalog hierarchy required for textile and garment retail operations. It manages product categories, sub-categories, lines, brands, dynamic textile attributes (fabric types, weave, pattern, size, color), barcode generations, multi-tier price lists, and seasonal merchandise plans.

### Target Components & Resources:
- **Catalog Core:** Products, Product Categories, Product Sub-Categories, Product Lines, Brands
- **Textile Specifics:** Product Variants, Attributes, Attribute Values, Colors, Sizes, Styles, Seasons, Designs, Fabric Types, Pattern Types
- **Pricing & Media:** Price Lists, Product Images, Product Barcodes, Merchandise Plannings

---

## 2. Pre-requisites
1. Organization & Branch setup completed (Flow 1).
2. Base GST rates and HSN codes seeded (Flow 9).

---

## 3. Comprehensive Test Cases

### 3.1 Catalog Core Taxonomy

#### Test Case 1.1: 4-Tier Taxonomy Creation
- **Objective:** Verify parent-child catalog classification setup.
- **Action 1:** Navigate to `Product Categories` -> Create Category `Apparel`.
- **Action 2:** Navigate to `Product Sub-Categories` -> Create Sub-Category `Men's Wear` linked to `Apparel`.
- **Action 3:** Navigate to `Product Lines` -> Create Product Line `Formal Shirts` linked to `Men's Wear`.
- **Action 4:** Navigate to `Brands` -> Create Brand `Royal Silk`.
- **Expected Result:** All 4 entities save cleanly with relations intact.

#### Test Case 1.2: Foreign Key Deletion Restriction
- **Objective:** Prevent orphan categories and data corruption.
- **Action:** Attempt to delete Category `Apparel` while `Men's Wear` is assigned to it.
- **Expected Result:** System blocks deletion and displays error: "Cannot delete category containing linked sub-categories."

---

### 3.2 Textile Attribute Matrix

#### Test Case 2.1: Dynamic Textile Attributes Setup
- **Objective:** Configure textile-specific attributes and values.
- **Action 1:** Go to `Fabric Types` -> Add `100% Egyptian Cotton`, `Pure Silk`, `Linen Blend`.
- **Action 2:** Go to `Pattern Types` -> Add `Checked`, `Striped`, `Solid`.
- **Action 3:** Go to `Colors` -> Add `Royal Blue` (Hex: `#4169E1`), `Crimson Red` (Hex: `#DC143C`).
- **Action 4:** Go to `Sizes` -> Add `39`, `40`, `42`, `44`.
- **Expected Result:** Attributes created with hex codes and sort orders preserved.

---

### 3.3 Product Core & Variant Generation

#### Test Case 3.1: Base Product Creation
- **Objective:** Verify creation of base master product record.
- **Action:** Go to `Products` -> `Create`. Fill:
  - Name: `Royal Cotton Formal Shirt`
  - Category: `Apparel` -> `Men's Wear` -> `Formal Shirts`
  - Brand: `Royal Silk`
  - Base UOM: `Pcs`
  - Base Price: `1500.00`
  - HSN Code: `6105`
- **Expected Result:** Product record created in draft/active status.

#### Test Case 3.2: Variant Generation & SKU Naming Formula
- **Objective:** Generate SKU combinations for size and color.
- **Action:** Go to `Product Variants` -> `Create`. Select Base Product `Royal Cotton Formal Shirt`. Select Color `Royal Blue`, Size `40`, Fabric `100% Egyptian Cotton`. Set MRP `2499.00`, Selling Price `1999.00`.
- **Expected Result:** Variant saved. Auto-generated SKU format verified as `RCS-RBLU-40`.

#### Test Case 3.3: Barcode Generation & Duplicate Prevention
- **Objective:** Ensure unique barcode strings per variant SKU.
- **Action 1 (Generate):** Go to `Product Barcodes` -> Assign Barcode `8901234567890` to variant `RCS-RBLU-40`.
  - **Expected Result:** Barcode saved. Printable EAN-13 barcode preview generated.
- **Action 2 (Duplicate Check):** Create another barcode record with the exact same barcode string `8901234567890` for a different variant.
  - **Expected Result:** System rejects creation with unique constraint error: "Barcode string already exists."

---

### 3.4 Pricing & Merchandise Planning

#### Test Case 4.1: Multi-Tier Price List Configuration
- **Objective:** Support wholesale, retail, and festival pricing structures.
- **Action:** Go to `Price Lists` -> `Create`. Name: `Diwali Wholesale Special`. Type: `Wholesale`. Start: `2026-10-01`, End: `2026-10-31`. Add item `RCS-RBLU-40` with special price `1450.00` (min qty 10).
- **Expected Result:** Price list active and restricted within date window.

#### Test Case 4.2: Merchandise Planning Targets
- **Objective:** Set quarterly inventory sales targets.
- **Action:** Go to `Merchandise Plannings` -> Create Q3 plan for `Men's Wear`. Target Sales Amount: `50,00,000.00`. Target Units: `2500`.
- **Expected Result:** Target logged and accessible in reports.
""",

    "3_Inventory_Warehouse_Flow_Exhaustive": """# Exhaustive Manual Testing Guide: Inventory & Warehouse Flow (WMS)

## 1. Overview & Architecture Scope
The Warehouse Management System (WMS) tracks physical inventory across warehouses and retail branches. It handles multi-tier bin storage mapping, inward stock receipts (GRN), stock adjustments, real-time stock ledger entries, branch transfers, transit stock tracking, picking/packing dispatches, inventory counting, and reconciliation.

### Target Components & Resources:
- **Warehouse Structure:** Warehouses, Warehouse Zones, Racks, Bins, Bin Stocks
- **Stock Management:** Stocks, Stock Ledgers, Stock Adjustments, Stock Transactions, Stock Valuations, Stock Reservations
- **Transfers & Transit:** Branch Inventories, Transit Inventories, Stock Transfers, Warehouse Transfers, Transfer Requests/Approvals/Receipts/Items
- **Operations:** Goods Receipt Notes (GRNs), Picking Lists, Packing Lists, Inventory Counts, Inventory Reconciliations

---

## 2. Pre-requisites
1. Product Variants and Barcodes created (Flow 2).
2. Vendors configured (Flow 4).

---

## 3. Comprehensive Test Cases

### 3.1 Physical Storage Infrastructure

#### Test Case 1.1: 4-Level Storage Hierarchy Configuration
- **Objective:** Map physical location down to exact shelf bin.
- **Action 1:** Go to `Warehouses` -> Create `Central Distribution Center (CDC)`.
- **Action 2:** Go to `Warehouse Zones` -> Create `Zone A (Apparel Storage)`.
- **Action 3:** Go to `Racks` -> Create `Rack A1` in `Zone A`.
- **Action 4:** Go to `Bins` -> Create `Bin A1-01` in `Rack A1`.
- **Expected Result:** Full hierarchy created cleanly: CDC -> Zone A -> Rack A1 -> Bin A1-01.

---

### 3.2 Inward Stock & Stock Ledger

#### Test Case 2.1: Goods Receipt Note (GRN) Inward Stock Processing
- **Objective:** Receive inward supplier shipment and verify stock ledger increment.
- **Action:** Go to `Goods Receipt Notes (GRNs)` -> `Create`. Select Vendor `V001`, Destination `Central Distribution Center`. Add Item `RCS-RBLU-40`, Received Quantity `500 Pcs`, Unit Cost `1200.00`. Set Status `Approved`.
- **Expected Result 1 (Stock Ledger):** Navigate to `Stock Ledgers`. A new immutable entry is posted: Type `GRN`, Qty `+500`, Balance `500`.
- **Expected Result 2 (Bin Stock):** Navigate to `Bin Stocks`. `Bin A1-01` shows current stock `500`.

#### Test Case 2.2: Stock Adjustment (Damage / Defect Write-off)
- **Objective:** Manually adjust physical inventory with audit reason.
- **Action:** Go to `Stock Adjustments` -> `Create`. Select Warehouse `CDC`, Product `RCS-RBLU-40`. Adjustment Type `Decrease`, Qty `-10`, Reason `Water Damage during transit`. Save & Approve.
- **Expected Result:** Total stock for SKU reduces to `490`. Stock Ledger records entry `-10`.

---

### 3.3 Inter-Branch Transfer & Transit Lifecycle

#### Test Case 3.1: Transfer Workflow (Request -> Approval -> Dispatch -> Receipt)
- **Objective:** Safely transfer inventory from Central Warehouse to Retail Branch.
- **Step 1 (Request):** Log in as Branch Manager `Coimbatore`. Go to `Transfer Requests` -> Request `50 Pcs` of `RCS-RBLU-40` from `CDC`. Status = `Submitted`.
- **Step 2 (Approval):** Log in as Warehouse Supervisor. Go to `Transfer Approvals` -> Approve Request `TR-00045`.
- **Step 3 (Picking & Packing):** Go to `Picking Lists` -> Generate picking sheet. Go to `Packing Lists` -> Mark Packed & Dispatched. Status = `In Transit`.
  - **Expected Result (Transit Check):** Stock at `CDC` drops from `490` to `440`. Stock at `Coimbatore Branch` is NOT YET increased. `Transit Inventories` table displays `50 Pcs`.
- **Step 4 (Receipt):** Log in as Branch Storekeeper `Coimbatore`. Go to `Transfer Receipts` -> Receive shipment. Acknowledge `50 Pcs`.
  - **Expected Result (Final Realization):** Transit Inventory clears (`0 Pcs`). Branch Inventory at `Coimbatore Branch` increases by `+50 Pcs`.

---

### 3.4 Physical Inventory Audit & Reconciliation

#### Test Case 4.1: Inventory Count & Discrepancy Reconciliation
- **Objective:** Perform physical stock-taking and auto-adjust system variance.
- **Action 1 (Count):** Go to `Inventory Counts` -> Start physical audit for `Coimbatore Branch`. System expects `50 Pcs`. Physical Count entered as `48 Pcs` (2 units missing).
- **Action 2 (Reconcile):** Go to `Inventory Reconciliations` -> Review variance `-2 Pcs`. Supervisor approves reconciliation.
- **Expected Result:** System generates automatic stock ledger adjustment for `-2 Pcs`. Final verified branch stock updated to `48 Pcs`.
""",

    "4_Procurement_Vendor_Flow_Exhaustive": """# Exhaustive Manual Testing Guide: Procurement & Vendor Flow

## 1. Overview & Architecture Scope
The Procurement Flow manages the purchase lifecycle from vendor onboarding to bill settlement. It handles supplier ratings, contact roles, purchase requisitions, Request for Quotations (RFQs), purchase orders, GRN verification, purchase returns (debit notes), vendor payments, and performance scorecards.

### Target Components & Resources:
- **Suppliers:** Vendors, Vendor Categories, Vendor Contacts, Vendor Agreements, Vendor Price Lists
- **Purchasing Cycle:** Purchase Requisitions, Request for Quotations (RFQs), Purchase Orders, Purchase Order Items, Purchase Returns
- **Vendor Management:** Vendor Payments, Vendor Performances

---

## 2. Pre-requisites
1. Organization setup complete (Flow 1).
2. Financial Chart of Accounts configured (Flow 7).

---

## 3. Comprehensive Test Cases

### 3.1 Supplier Master & Onboarding

#### Test Case 1.1: Vendor Creation & Duplicate GSTIN Validation
- **Objective:** Verify onboarding of suppliers and unique GSTIN constraints.
- **Action 1 (Positive):** Go to `Vendors` -> `Create`. Name: `Southern Cotton Mills Ltd`. Category: `Fabric Manufacturers`. GSTIN: `33AAACS1234F1Z2`. PAN: `AAACS1234F`. Payment Terms: `Net 30 Days`. Save.
  - **Expected Result:** Vendor saved cleanly.
- **Action 2 (Duplicate Check):** Create another vendor with duplicate GSTIN `33AAACS1234F1Z2`.
  - **Expected Result:** System blocks save: "GSTIN already registered with Southern Cotton Mills Ltd."

#### Test Case 1.2: Vendor Contacts & Agreement Attachments
- **Objective:** Link key personnel and legal agreement contracts.
- **Action:** Go to `Vendor Contacts` -> Add Sales Manager `Mr. S. Raman`, Phone `9876543210`. Go to `Vendor Agreements` -> Upload signed NDA & Supply Agreement PDF.
- **Expected Result:** Contact and documents stored and accessible from vendor profile.

---

### 3.2 Procure-to-Pay (P2P) Lifecycle

#### Test Case 2.1: Purchase Requisition (PR) Creation
- **Objective:** Initiate internal purchasing demand.
- **Action:** Go to `Purchase Requisitions` -> `Create`. Requested By: `Production Head`. Add Item `Egyptian Cotton Yarn`, Quantity `1000 Kg`. Priority: `High`. Status: `Submitted`.
- **Expected Result:** Requisition logged and routed for procurement review.

#### Test Case 2.2: RFQ Bidding & Quote Comparison
- **Objective:** Solicit quotes from multiple vendors.
- **Action:** Go to `Request for Quotations (RFQs)` -> Convert PR to RFQ. Send to `Southern Cotton Mills` and `Western Yarns Ltd`. Log responses:
  - Vendor A Quote: `350.00 / Kg`
  - Vendor B Quote: `340.00 / Kg`
- **Expected Result:** System highlights Vendor B as lowest bidder (L1).

#### Test Case 2.3: Purchase Order (PO) Approval Workflow
- **Objective:** Generate binding PO and enforce authorization limits.
- **Action 1 (Create PO):** Go to `Purchase Orders` -> Create PO for Vendor B (`Western Yarns Ltd`). Total Amount: `3,40,000.00`. Status = `Draft`.
- **Action 2 (Approval Guardrail):** Try to send PO to vendor without manager signature.
  - **Expected Result:** System blocks action: "Purchase Orders above Rs. 1,000,000 require Purchase Manager approval."
- **Action 3 (Manager Approve):** Log in as Purchase Manager -> Click `Approve`. Status updates to `Issued`.

---

### 3.3 Goods Receipt Linkage & Vendor Settlement

#### Test Case 3.1: GRN Verification against PO
- **Objective:** Ensure incoming warehouse GRN matches PO quantities.
- **Action:** Go to `GRNs` -> Create GRN referencing PO `PO-2026-0089`. Auto-fill line items.
- **Expected Result:** Items and quantities pre-populated automatically from PO.

#### Test Case 3.2: Purchase Return & Debit Note Generation
- **Objective:** Handle rejected supplier materials.
- **Action:** Go to `Purchase Returns` -> Select PO `PO-2026-0089`. Return `50 Kg` defective yarn.
- **Expected Result 1:** Stock reduced by `50 Kg`.
- **Expected Result 2:** Automated `Debit Note` generated in Accounts Payable reducing balance by `17,000.00`.

#### Test Case 3.3: Vendor Payment Settlement
- **Objective:** Process bank payment against supplier invoice.
- **Action:** Go to `Vendor Payments` -> Select Vendor `Western Yarns Ltd`. Payment Amount: `3,23,000.00`. Mode: `NEFT/Bank Transfer`. Enter UTR `HDFCN26109988`.
- **Expected Result:** Vendor outstanding balance updated to `0.00`. Payment voucher posted to general ledger.
""",

    "5_Point_of_Sale_Retail_Sales_Flow_Exhaustive": """# Exhaustive Manual Testing Guide: POS & Retail Sales Flow

## 1. Overview & Architecture Scope
The Point of Sale (POS) flow governs front-end retail billing operations at branch locations. High performance, cashier shift integrity, barcode scanning speed, payment gateways, tax calculation accuracy, split payments, and shift cash reconciliation are critical.

### Target Components & Resources:
- **POS Infrastructure:** POS Terminals, POS Counters
- **Sales Cycle:** POS Sales, POS Sale Items, POS Returns
- **Cash Management:** Cash Registers, Cash Openings, Cash Closings, Cash Books
- **Transactions:** Payments, Receipts, Payment Modes, Quotations

---

## 2. Pre-requisites
1. Active Retail Branch with Product Variants and Barcodes (Flow 2).
2. Cashier user assigned to active branch (Flow 1).

---

## 3. Comprehensive Test Cases

### 3.1 Terminal Infrastructure & Shift Cash Control

#### Test Case 1.1: POS Terminal & Counter Mapping
- **Objective:** Set up physical hardware billing terminals.
- **Action:** Go to `POS Terminals` -> Create Terminal `POS-CBE-01`, Mac Address `00:1A:2B:3C:4D:5E`. Map to `Counter 1` at `Coimbatore Branch`.
- **Expected Result:** Terminal active and linked to branch.

#### Test Case 1.2: Shift Cash Opening & Register Guardrail
- **Objective:** Block sales until cashier explicitly opens register float.
- **Action 1 (Negative Checkout Block):** Log in as Cashier `Ravi`. Open POS Interface. Attempt to scan item and click Checkout WITHOUT opening cash register.
  - **Expected Result:** System blocks operation with modal alert: "Cash Register is CLOSED. Perform Cash Opening first."
- **Action 2 (Cash Opening):** Go to `Cash Openings` -> Enter Opening Float `2,000.00 INR`. Confirm.
  - **Expected Result:** Register status changes to `OPEN`. Timestamp and Cashier ID logged.

---

### 3.2 Core Billing & Multi-Payment Checkout

#### Test Case 2.1: High-Speed Barcode Scanning & Item Lookup
- **Objective:** Verify instant cart loading via barcode input.
- **Action:** In POS interface, input barcode `8901234567890`. Hit Enter.
- **Expected Result:** Product `Royal Cotton Formal Shirt (Size 40, Royal Blue)` added to cart instantly. Unit Price `1,999.00` fetched from price list.

#### Test Case 2.2: Customer Profile Attachment & Loyalty Lookup
- **Objective:** Link customer via mobile number search.
- **Action:** Search customer mobile `9840012345`. Select `Karthik Raja`.
- **Expected Result:** Customer linked to bill. Current loyalty point balance (`120 Points`) displayed on POS screen.

#### Test Case 2.3: Discount Floor Guardrail Enforcement
- **Objective:** Prevent cashiers from giving unauthorized excessive discounts.
- **Action:** Apply line discount of `50%` to item.
- **Expected Result:** System blocks discount: "Line discount exceeds maximum cashier limit (Max 20%). Manager override PIN required."

#### Test Case 2.4: Split Payment Processing (Cash + UPI + Credit Card)
- **Objective:** Accept multiple payment modes for a single invoice.
- **Action:** Grand Total = `3,500.00 INR`. Click `Split Payment`:
  - Payment Mode 1: Cash = `1,000.00`
  - Payment Mode 2: UPI / QR Code = `1,500.00` (Enter Ref: `UPI998877`)
  - Payment Mode 3: Credit Card = `1,000.00` (Enter Approval Code: `AUTH4455`)
  - Click `Complete Sale`.
- **Expected Result 1:** Invoice saved. POS receipt printed.
- **Expected Result 2 (Stock Ledger):** Branch stock reduced by cart items.
- **Expected Result 3 (Cash Book):** Cash Account credited `+1,000`, Bank Account credited `+2,500`.

---

### 3.3 Returns & End-of-Day (EOD) Reconciliation

#### Test Case 3.1: POS Return & Cash Refund Adjustment
- **Objective:** Process customer item exchange/return against original invoice.
- **Action:** Go to `POS Returns` -> Enter Invoice No `POS-2026-00412`. Select returned shirt (`1,999.00`). Reason: `Size Fit Issue`. Select Refund Mode `Cash`.
- **Expected Result:** Return slip issued. Branch stock incremented by `+1`. Cash drawer expected float reduced by `1,999.00`.

#### Test Case 3.2: Shift Cash Closing & Shortage Discrepancy Audit
- **Objective:** Audit physical cash vs system ledger totals at shift end.
- **Action:** At shift end, go to `Cash Closings`.
  - System Calculation: Opening (`2,000`) + Cash Sales (`1,000`) - Cash Refund (`1,999`) = Expected Cash `1,001.00`.
  - Enter Physical Cash Counted: `990.00` (Shortage of 11 INR).
- **Expected Result:** Shift closed. System logs variance `-11.00 INR` and posts discrepancy alert to Branch Manager dashboard.
""",

    "6_CRM_Loyalty_Promotions_Flow_Exhaustive": """# Exhaustive Manual Testing Guide: CRM, Loyalty & Promotions Flow

## 1. Overview & Architecture Scope
This module drives customer retention, loyalty rewards, promotional campaigns, discount coupon rules, combo/BOGO offers, and customer grievance management.

### Target Components & Resources:
- **Customers:** Customers, Customer Groups, Customer Wallets, Customer Feedback
- **Loyalty System:** Loyalty Programs, Loyalty Points, Loyalty Cards, Membership Plans
- **Promotions:** Promo Codes, Coupons, Gift Vouchers, Discount Rules, Promotion Types
- **Special Offers:** Buy-X-Get-Y Offers, Combo Offers, Festival Offers
- **Support:** Complaint Tickets, Complaint Categories

---

## 2. Pre-requisites
1. Active Customers and POS Sales module configured (Flow 5).

---

## 3. Comprehensive Test Cases

### 3.1 Customer Profile & Grouping

#### Test Case 1.1: Customer Onboarding & Credit Limit Enforcement
- **Objective:** Manage customer profiles and wholesale credit boundaries.
- **Action 1:** Go to `Customers` -> Create Customer `Lakshmi Silks`, Category `Wholesale Customer`, Credit Limit `1,00,000.00 INR`.
- **Action 2 (Credit Violation):** Attempt to process credit sale of `1,20,000.00 INR` for `Lakshmi Silks`.
  - **Expected Result:** System blocks transaction: "Credit Limit Exceeded. Max credit allowed: Rs. 100,000."

---

### 3.2 Loyalty Points & Wallet Engine

#### Test Case 2.1: Automated Loyalty Point Earning Formula
- **Objective:** Verify points accrue based on purchase threshold rules.
- **Pre-condition:** Loyalty Program set: "Earn 1 Point per Rs 100 spent".
- **Action:** Perform POS sale of `4,500.00 INR` for Customer `Karthik`.
- **Expected Result:** Check `Loyalty Points` / `Customer Wallets`. Customer earns `45 Points` (`45.00 INR` value). Total wallet balance updated.

#### Test Case 2.2: Loyalty Point Redemption at POS Checkout
- **Objective:** Redeem accumulated wallet points to pay bill portion.
- **Action:** Perform POS sale of `1,000.00 INR` for `Karthik`. Select `Redeem 45 Points` (`45 INR` discount).
- **Expected Result:** Net Bill Payable = `955.00 INR`. Customer's remaining wallet point balance becomes `0`.

---

### 3.3 Promotion & Coupon Engine

#### Test Case 3.1: Buy-1-Get-1 (BOGO) Offer Automation
- **Objective:** Apply automatic discount when promo condition is met.
- **Pre-condition:** Offer Rule: "Buy 2 Polo Shirts, Get 1 Leather Belt FREE".
- **Action 1 (Condition Not Met):** Add 1 Polo Shirt and 1 Leather Belt to POS cart.
  - **Expected Result:** Belt charged at full MRP (`999.00`). No BOGO discount applied.
- **Action 2 (Condition Met):** Add 2 Polo Shirts and 1 Leather Belt to POS cart.
  - **Expected Result:** Belt price automatically discounted to `0.00 INR`. Receipt displays "BOGO Offer Applied".

#### Test Case 3.2: Single-Use Coupon Limit Verification
- **Objective:** Prevent customers from reusing single-use promo codes.
- **Action 1 (First Use):** Apply Promo Code `WELCOME200` (Rs 200 off) on Customer `Anitha` invoice.
  - **Expected Result:** Discount applied successfully.
- **Action 2 (Second Use Check):** Apply `WELCOME200` again on `Anitha` second invoice.
  - **Expected Result:** System rejects coupon: "Promo code WELCOME200 has already been used by this customer."

---

### 3.4 Complaint Support Lifecycle

#### Test Case 4.1: Customer Complaint Ticket Workflow
- **Objective:** Track customer service tickets from logging to resolution.
- **Action:** Go to `Complaint Tickets` -> Create Ticket for `Anitha`. Category: `Fabric Color Fading`. Priority: `Medium`. Assign to `QA Officer`. Update Status: `Open` -> `In Progress` -> `Resolved` (Replacement Issued).
- **Expected Result:** Full resolution timeline and audit logs preserved.
""",

    "7_Finance_Accounting_Flow_Exhaustive": """# Exhaustive Manual Testing Guide: Finance & Accounting Flow

## 1. Overview & Architecture Scope
The Finance & Accounting flow manages double-entry bookkeeping, chart of accounts tree hierarchy, journal vouchers, contra entries, cost centers, credit/debit notes, trial balance generation, and fiscal year financial statements.

### Target Components & Resources:
- **Accounts Setup:** Chart of Accounts, Account Groups, Ledgers, Cost Centers, Bank Accounts
- **Entries:** Journals, Journal Entries, Contra Entries, Credit Notes, Debit Notes
- **Financials:** Trial Balances, Currencies

---

## 2. Pre-requisites
1. Financial Year calendar active (Flow 1).

---

## 3. Comprehensive Test Cases

### 3.1 Chart of Accounts & Structural Hierarchy

#### Test Case 1.1: Standard Chart of Accounts Setup & Protection
- **Objective:** Verify root account groups (Assets, Liabilities, Equity, Income, Expenses).
- **Action 1:** Go to `Chart of Accounts`. Ensure root types exist.
- **Action 2 (System Protection Check):** Attempt to delete system root group `1000 - Current Assets`.
  - **Expected Result:** System blocks deletion: "System core account groups cannot be deleted."

---

### 3.2 Double-Entry Journal Engine

#### Test Case 2.1: Double-Entry Equilibrium Check (Debits Must Equal Credits)
- **Objective:** Enforce fundamental accounting equality before posting.
- **Action 1 (Unbalanced Negative Check):** Go to `Journals` -> `Create Journal Entry`.
  - Line 1: Debit `Electricity Expense` = `12,500.00`
  - Line 2: Credit `HDFC Bank A/c` = `10,000.00`
  - Click `Post Journal`.
  - **Expected Result:** System throws hard error: "Unbalanced Journal Entry! Total Debits (12,500.00) != Total Credits (10,000.00). Difference: 2,500.00."
- **Action 2 (Balanced Positive Post):** Correct Line 2 Credit to `12,500.00`. Click `Post Journal`.
  - **Expected Result:** Journal posted. Status updated to `Posted`. Immutable Journal Voucher number assigned (`JV-2026-0012`).

---

### 3.3 Automated Ledger Postings & Sub-Ledger Integration

#### Test Case 3.1: Automated Operational Postings (POS & Procurement Integration)
- **Objective:** Verify operational transactions generate correct background financial journals.
- **Action:** Perform POS sale of `1,000.00 Cash` (Flow 5).
- **Expected Result:** Go to `Journal Entries`. Verify auto-posted journal entry:
  - Debit: `Cash in Hand A/c` = `1,000.00`
  - Credit: `Sales Revenue A/c` = `1,000.00`
- **Security Check:** Attempt to edit or delete this auto-generated journal entry.
  - **Expected Result:** Edit/Delete buttons disabled: "Automated operational journals cannot be manually modified."

#### Test Case 3.2: Contra Entries (Internal Cash to Bank Transfer)
- **Objective:** Record internal movement of funds between cash and bank accounts.
- **Action:** Go to `Contra Entries` -> Create entry: Transfer `25,000.00 INR` from `Main Cash Register` to `SBI Current Account`. Save & Post.
- **Expected Result:** `Main Cash Register` ledger debits/credits balance reduced by `25,000`, `SBI Current Account` balance increased by `25,000`.

---

### 3.4 Trial Balance Equilibrium & Verification

#### Test Case 4.1: Real-Time Trial Balance Equilibrium
- **Objective:** Verify real-time balance sheet ledger aggregate equilibrium.
- **Action:** Go to `Trial Balances` -> Generate for active Financial Year.
- **Expected Result:** Sum of all Debit balances MUST EXACTLY EQUAL sum of all Credit balances. Net Difference must be `0.00 INR`.
""",

    "8_HR_Payroll_Flow_Exhaustive": """# Exhaustive Manual Testing Guide: HR & Payroll Flow (HRMS)

## 1. Overview & Architecture Scope
The Human Resource Management System (HRMS) handles employee onboarding, digital document vaults, attendance tracking, shift scheduling, leave management, complex salary component formulas (Basic, HRA, Statutory PF, ESI, Professional Tax), monthly payroll processing cycles, salary slip generation, and statutory compliance.

### Target Components & Resources:
- **Employee Core:** Employees, Employee Documents, Employee Transfers, Employee Exits
- **Time & Leave:** Attendances, Shifts, Holidays, Employee Leaves, Leave Types
- **Compensation:** Salary Structures, Salary Components, Deductions, Incentives, Bonuses, Reimbursements, Employee Salary Assignments
- **Payroll & Compliance:** Payroll Cycles, Payroll Processings, Professional Taxes, ESI Contributions, PF Contributions, Employee Tax Declarations, Investment Types

---

## 2. Pre-requisites
1. Active Branch and Department infrastructure (Flow 1).
2. Financial accounts configured for Payroll Expenses and Statutory Liabilities (Flow 7).

---

## 3. Comprehensive Test Cases

### 3.1 Employee Master & Document Repository

#### Test Case 1.1: Employee Creation & Duplicate PAN/Aadhar Enforcement
- **Objective:** Onboard employee with strict PII validation.
- **Action 1:** Go to `Employees` -> `Create`. Fill Name `Suresh Raina`, Email `suresh@apex.com`, PAN `ABCDE1234F`, Aadhar `1234-5678-9012`, DOJ `2026-01-15`, Department `Sales`, Designation `Floor Executive`. Save.
  - **Expected Result:** Saved. Employee Code auto-generated (`EMP-0042`).
- **Action 2 (Duplicate PAN Check):** Attempt to create another employee with same PAN `ABCDE1234F`.
  - **Expected Result:** System blocks save: "PAN ABCDE1234F is already registered with employee Suresh Raina."

---

### 3.2 Time, Attendance & Leave Rules

#### Test Case 2.1: Leave Quota & Application Workflow
- **Objective:** Manage leave requests and balance deductions.
- **Action 1:** Go to `Leave Types` -> Configure `Casual Leave (CL)` = `12 Days/Year`.
- **Action 2:** Log in as Employee `Suresh`. Go to `Employee Leaves` -> Apply for `3 Days CL` (`2026-08-10` to `2026-08-12`). Status = `Pending`.
- **Action 3:** Log in as HR Manager -> Click `Approve`.
- **Expected Result:** Remaining CL balance for `Suresh` updates from `12` to `9 Days`.

#### Test Case 2.2: Loss of Pay (LOP) Unexcused Absence Calculation
- **Objective:** Ensure unexcused absences trigger LOP deductions during payroll.
- **Action:** Mark Employee `Suresh` absent for `2 Days` in `Attendances` without approved leave application.
- **Expected Result:** Payroll engine logs `LOP Days = 2` for current month processing cycle.

---

### 3.3 Salary Structure & Statutory Formulas

#### Test Case 3.1: Complex Indian Statutory Formula Setup
- **Objective:** Configure dynamic salary calculation rules.
- **Action:** Go to `Salary Components`:
  - `Basic Pay` (Earning, Base)
  - `HRA` (Earning, Formula: `Basic * 0.40`)
  - `Provident Fund (PF)` (Statutory Deduction, Formula: `Basic * 0.12`, max basic cap `15000`)
  - `ESI` (Statutory Deduction, Formula: `Gross * 0.0075`, applicable if Gross <= 21000)
  - `Professional Tax (PT)` (Slab-based deduction)
- **Expected Result:** Formulas saved and validated without syntax errors.

#### Test Case 3.2: Employee Salary Assignment & Net Calculation
- **Objective:** Assign structure to employee with monthly base salary.
- **Action:** Go to `Employee Salary Assignments` -> Assign structure to `Suresh Raina` with `Basic = 20,000.00 INR`.
- **Expected Result System Computation:**
  - Basic = `20,000.00`
  - HRA (40%) = `8,000.00`
  - Gross Salary = `28,000.00`
  - Employee PF (12% of 15k cap) = `1,800.00`
  - Professional Tax = `208.00`
  - Expected Net Salary = `25,992.00 INR`.

---

### 3.4 Monthly Payroll Batch Processing & Disbursement

#### Test Case 4.1: Monthly Payroll Run & Pro-rated LOP Adjustment
- **Objective:** Execute monthly batch payroll for a branch.
- **Action:** Go to `Payroll Processings` -> Select Month `August 2026`, Branch `Coimbatore`. Click `Execute Payroll Processing`.
- **Expected Result 1 (Pro-rated Math):** For employee with 2 LOP days in a 31-day month:
  - Payable Days = `29 / 31`.
  - Basic Pay pro-rated = `20,000 * (29/31) = 18,709.68 INR`.
  - HRA, PF, PT recalculated automatically on pro-rated values.
- **Expected Result 2 (Slip Generation):** Individual PDF Payslips generated for all processed employees.
- **Expected Result 3 (Financial Journal Link):** Status marked `Approved`. System posts automatic journal voucher debiting `Salary Expense` and crediting `Salary Payable` & `Statutory Payables`.
""",

    "9_GST_Taxation_Compliance_Flow_Exhaustive": """# Exhaustive Manual Testing Guide: GST Taxation & Compliance Flow (India)

## 1. Overview & Architecture Scope
The GST Taxation & Compliance module enforces compliance with Indian Statutory Tax laws. It governs HSN/SAC code mapping, GST tax slab masters (0%, 5%, 12%, 18%, 28%), intra-state (CGST + SGST split) vs inter-state (IGST) tax calculation engines, B2B Tax Invoices, E-Invoicing (IRN), E-Way Bill thresholds, TDS under GST/Income Tax, and statutory return filing summaries (GSTR-1, GSTR-3B).

### Target Components & Resources:
- **GST Core:** GST Categories, GST Rates, HSN Codes, HSN/SAC Codes
- **Filing & Docs:** GST Returns, Tax Invoices, Tax Groups, E-Invoices, E-Way Bills
- **Deductions:** TDS Records, TCS Records

---

## 2. Pre-requisites
1. Company state location and customer/vendor GSTINs registered (Flow 1, Flow 4, Flow 6).

---

## 3. Comprehensive Test Cases

### 3.1 GST Master & HSN Mapping

#### Test Case 1.1: HSN Code & Tax Slab Linkage
- **Objective:** Map textile goods to legal statutory tax rates.
- **Action:** Go to `HSN/SAC Codes` -> Create HSN `6105` (Men's Woven Shirts). Map to `GST Rate = 5%` (CGST 2.5%, SGST 2.5%, IGST 5%). Assign HSN `6105` to product `Royal Cotton Formal Shirt`.
- **Expected Result:** Product linked to 5% tax bracket.

---

### 3.2 Tax Engine: Intra-State vs Inter-State Calculation

#### Test Case 2.1: Intra-State Tax Calculation (CGST + SGST Equal Split)
- **Objective:** Verify equal tax split when supplier and buyer are in the SAME state.
- **Pre-condition:** Company Branch located in `Tamil Nadu (State Code: 33)`.
- **Action:** Create B2B Sales Invoice for Customer located in `Tamil Nadu (State Code: 33)`. Add item `1,000.00 INR` (5% GST rate).
- **Expected Result Tax Engine Computation:**
  - Taxable Value = `1,000.00 INR`
  - CGST @ 2.5% = `25.00 INR`
  - SGST @ 2.5% = `25.00 INR`
  - IGST = `0.00 INR`
  - Total Invoice Grand Total = `1,050.00 INR`.

#### Test Case 2.2: Inter-State Tax Calculation (IGST Single Rate)
- **Objective:** Verify single IGST calculation when buyer is in a DIFFERENT state.
- **Action:** Create B2B Sales Invoice for Customer located in `Kerala (State Code: 32)`. Add item `1,000.00 INR` (5% GST rate).
- **Expected Result Tax Engine Computation:**
  - Taxable Value = `1,000.00 INR`
  - CGST = `0.00 INR`
  - SGST = `0.00 INR`
  - IGST @ 5.0% = `50.00 INR`
  - Total Invoice Grand Total = `1,050.00 INR`.

---

### 3.3 Statutory Documents & E-Invoicing / E-Way Bills

#### Test Case 3.1: Tax Invoice PDF Legal Compliance Format
- **Objective:** Verify mandatory statutory fields print on generated Tax Invoice PDFs.
- **Action:** Generate Tax Invoice PDF for B2B Sale.
- **Validation Checklist:** PDF MUST visually include:
  1. Title clearly stating "TAX INVOICE".
  2. Supplier Name, Address, State Code `33`, and GSTIN.
  3. Buyer Name, Address, State Code, and GSTIN.
  4. HSN Code column for each line item.
  5. Taxable value, CGST amount, SGST amount, IGST amount breakdown table.
  6. Amount in words.

#### Test Case 3.2: E-Way Bill Threshold Trigger Check
- **Objective:** Automatically flag transport invoices exceeding legal threshold.
- **Action 1 (Below Threshold):** Create out-of-state invoice of value `40,000.00 INR`.
  - **Expected Result:** E-Way Bill requirement status = `Not Required`.
- **Action 2 (Above Threshold):** Create out-of-state invoice of value `75,000.00 INR` (> Rs 50,000 threshold).
  - **Expected Result:** System flags `E-Way Bill Required`. Prompts for Transporter ID and Vehicle Number before finalizing dispatch.

---

### 3.4 Statutory Deductions (TDS / TCS)

#### Test Case 4.1: Vendor Service TDS Deduction (Section 194C / 194J)
- **Objective:** Deduct tax at source on vendor service bills.
- **Action:** Go to `Purchase Invoices` -> Enter Vendor Invoice for `Security Services` = `50,000.00 INR`. Apply `TDS Section 194C @ 2%`.
- **Expected Result:**
  - Gross Payable = `50,000.00 INR`
  - TDS Deducted (2%) = `1,000.00 INR`
  - Net Payable to Vendor = `49,000.00 INR`
  - `TDS Payable Ledger` credited `1,000.00 INR`.
""",

    "10_Asset_Service_Management_Flow_Exhaustive": """# Exhaustive Manual Testing Guide: Asset & Service Management Flow

## 1. Overview & Architecture Scope
The Asset & Service Management flow tracks internal physical assets (weaving machines, computers, generators, vehicles), asset allocation to branches, inter-branch asset transfers, periodic depreciation accounting write-downs, maintenance logs, asset disposals, customer service tickets, technician assignments, and OEM warranty claims.

### Target Components & Resources:
- **Assets:** Assets, Asset Categories, Asset Allocations, Asset Transfers
- **Asset Lifecycle:** Asset Depreciations, Asset Maintenances, Asset Disposals
- **Services:** Service Requests, Service Assignments, Technician Visits, Warranty Claims

---

## 2. Pre-requisites
1. Organization branches active (Flow 1).
2. Depreciation expense accounts set up in Chart of Accounts (Flow 7).

---

## 3. Comprehensive Test Cases

### 3.1 Asset Master & Branch Allocation

#### Test Case 1.1: Asset Registration & Serial Uniqueness
- **Objective:** Onboard physical machinery with asset Tag tracking.
- **Action 1:** Go to `Assets` -> `Create`. Name `Juki Industrial Sewing Machine Model X`, Serial No `JK-998877`, Purchase Price `65,000.00 INR`, Purchase Date `2026-04-01`. Save.
  - **Expected Result:** Asset logged with unique Asset Tag `AST-00109`.
- **Action 2 (Duplicate Serial Check):** Create another asset with duplicate serial `JK-998877`.
  - **Expected Result:** System blocks creation: "Serial number JK-998877 is already registered."

#### Test Case 1.2: Asset Branch Allocation & Double-Allocation Prevention
- **Objective:** Allocate hardware to a specific physical location.
- **Action 1:** Go to `Asset Allocations` -> Allocate `AST-00109` to `Coimbatore Branch`.
  - **Expected Result:** Asset status changes to `Allocated`.
- **Action 2 (Double Allocation Check):** Attempt to create another active allocation for `AST-00109` to `Chennai Branch` without returning it first.
  - **Expected Result:** System blocks operation: "Asset AST-00109 is currently allocated to Coimbatore Branch."

---

### 3.2 Asset Depreciation Engine & Disposal

#### Test Case 2.1: Automated Straight-Line Depreciation Engine
- **Objective:** Execute periodic financial asset value write-down.
- **Pre-condition:** Asset `AST-00109` value `60,000 INR`, Depreciation Rule: `Straight Line @ 10% per year`.
- **Action:** Go to `Asset Depreciations` -> Execute Depreciation Run for FY 2026-2027.
- **Expected Result 1 (Book Value Update):** Asset Net Book Value reduced by `6,000 INR` to `54,000 INR`.
- **Expected Result 2 (Accounting Journal):** Automated financial journal posted:
  - Debit: `Depreciation Expense A/c` = `6,000.00 INR`
  - Credit: `Accumulated Depreciation A/c` = `6,000.00 INR`.

#### Test Case 2.2: Asset Scrap Disposal & Profit/Loss Calculation
- **Objective:** Dispose scrap asset and record gain/loss on sale.
- **Action:** Go to `Asset Disposals` -> Dispose old computer asset (Book Value: `5,000 INR`) for Sale Price `2,000 INR Cash`.
- **Expected Result:** Asset marked `Disposed`. Journal entry debits Cash `2,000`, debits `Loss on Asset Disposal` `3,000`, credits Asset `5,000`.

---

### 3.3 Customer Service & Warranty Management

#### Test Case 3.1: Service Request & Field Technician Assignment Lifecycle
- **Objective:** Track customer garment alteration / repair requests.
- **Action:** Go to `Service Requests` -> Create request for Customer `Priya`. Description `Suit Alteration`. Go to `Service Assignments` -> Assign to Technician `Ramesh`. Update Status: `In Progress` -> `Completed`.
- **Expected Result:** Service job completed, turn-around time logged, customer notified.

#### Test Case 3.2: Vendor OEM Warranty Claim Tracking
- **Objective:** Process broken asset under supplier warranty.
- **Action:** Go to `Warranty Claims` -> Select broken machine asset under warranty. Log claim to OEM Supplier. Status = `Sent to Vendor`.
- **Expected Result:** Asset operational status updated to `In Maintenance / Under Warranty Claim`. Prevents re-allocation until claim resolution.
""",

    "11_Reports_Analytics_Flow_Exhaustive": """# Exhaustive Manual Testing Guide: Reports & Analytics Flow

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
"""
}

print("Starting generation of exhaustive Markdown and PDF testing guides...")

for name, content in flows.items():
    md_file = os.path.join(output_dir, f"{name}.md")
    pdf_file = os.path.join(output_dir, f"{name}.pdf")
    
    with open(md_file, "w", encoding="utf-8") as f:
        f.write(content)
        
    print(f"Generating PDF: {pdf_file}...")
    pdf = MarkdownPdf(toc_level=0)
    pdf.add_section(Section(content))
    pdf.save(pdf_file)

print("SUCCESS: All 11 Exhaustive Flow Testing Guides (MD & PDF) generated successfully!")
