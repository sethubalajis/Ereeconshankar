import os
from markdown_pdf import Section, MarkdownPdf

output_dir = "Manual_Testing_Guides"
if not os.path.exists(output_dir):
    os.makedirs(output_dir)

settings_flow_content = """# Exhaustive Manual Testing Guide: Settings Sub-Flow (Branch Settings, Financial Years, Dashboard Widgets, States, Cities, Countries)

## 1. Overview & Architecture Scope
The Settings Sub-Flow provides administrative control over system parameters, global geographic taxonomies (Countries, States, Cities), financial calendar years, branch-level operational configurations, and executive dashboard widget layouts. 

### Target Components & Resources:
- **Geographic Setup:** Countries, States, Cities
- **Financial Calendar:** Financial Years
- **Operational Configuration:** Branch Settings
- **Dashboard Layout:** Dashboard Widgets

---

## 2. Pre-requisites & Test Environment Setup
1. Laravel/Filament application database migrated cleanly (`php artisan migrate:fresh`).
2. Super Admin user created with full access privileges.
3. At least 2 active physical branches created (`Coimbatore Main Branch`, `Chennai Retail Outlet`).

---

## 3. Comprehensive Test Cases

### 3.1 Geographic Master Data (Countries, States, Cities)

#### Test Case 1.1: Country Onboarding & ISO Code Uniqueness (Positive & Negative)
- **Objective:** Verify creation of master country records with strict ISO code validation.
- **Pre-conditions:** Logged in as Super Admin.
- **Action 1 (Negative Validation):** Navigate to `Countries` -> `Create`. Leave Name empty and enter duplicate ISO code `IN`. Click `Save`.
  - **Expected Result:** System throws validation errors for missing Country Name and unique ISO code violation.
- **Action 2 (Positive Creation):** Enter Country Name `India`, ISO Code `IN`, Phone Code `+91`, Currency Code `INR`. Click `Save`.
  - **Expected Result:** Country created cleanly.

#### Test Case 1.2: State Management & Statutory Tax Code Linkage
- **Objective:** Verify state entity creation and GST state code mapping.
- **Action:** Navigate to `States` -> `Create`. Select Country `India`. Enter State Name `Tamil Nadu`, State Code `33`. Create another state `Kerala`, State Code `32`.
- **Expected Result:** States saved. State Code `33` mapped for intra-state tax split validation.

#### Test Case 1.3: City Creation & Parent State Linkage
- **Objective:** Link cities to parent states.
- **Action:** Navigate to `Cities` -> `Create`. Select State `Tamil Nadu`. Add Cities `Chennai`, `Coimbatore`, `Madurai`. Select State `Kerala`. Add City `Kochi`.
- **Expected Result:** Cities saved with foreign keys pointing to correct state records.

#### Test Case 1.4: Cascading Geographic Dropdown Verification in Forms
- **Objective:** Ensure selecting a Country filters States, and selecting a State filters Cities dynamically.
- **Action:** In Branch/Customer/Vendor forms, select Country `India`.
  - **Validation 1:** State dropdown MUST display only Indian states (`Tamil Nadu`, `Kerala`).
- **Action:** Select State `Tamil Nadu`.
  - **Validation 2:** City dropdown MUST display only Tamil Nadu cities (`Chennai`, `Coimbatore`, `Madurai`). Cities from `Kerala` (`Kochi`) MUST be excluded.
- **Expected Result:** Cascading dropdown logic functions without lag or unhandled options.

#### Test Case 1.5: Foreign Key Deletion Restriction on Geography
- **Objective:** Protect child records from orphan state deletion.
- **Action:** Attempt to delete State `Tamil Nadu` while active Cities (`Chennai`, `Coimbatore`) are linked to it.
- **Expected Result:** System blocks deletion: "Cannot delete state with active linked cities (FK constraint)."

---

### 3.2 Financial Years & Accounting Calendar

#### Test Case 2.1: Financial Year Creation & Valid Date Window
- **Objective:** Define fiscal year calendar windows.
- **Action:** Navigate to `Financial Years` -> `Create`.
  - Name: `FY 2026-2027`
  - Start Date: `2026-04-01`
  - End Date: `2027-03-31`
  - Status `is_active`: `true`
  - Status `is_closed`: `false`
  - Save.
- **Expected Result:** Saved successfully as active financial year.

#### Test Case 2.2: Single Active Financial Year Enforcement Rule
- **Objective:** Ensure ONLY ONE financial year can be marked `is_active = true` at any given time.
- **Action:** Create `FY 2027-2028` (Start: `2027-04-01`, End: `2028-03-31`). Set `is_active = true`. Save.
- **Expected Result:** System automatically sets `is_active = false` on `FY 2026-2027` so only `FY 2027-2028` remains active.

#### Test Case 2.3: Date Overlap Prevention & Validation
- **Objective:** Block creation of overlapping fiscal periods.
- **Action:** Create a financial year with Start Date `2026-10-01` and End Date `2027-09-30`.
- **Expected Result:** System rejects creation with error: "Financial year dates overlap with an existing financial calendar."

#### Test Case 2.4: Financial Year Closing Workflow (`is_closed = true`) & Transaction Lockout
- **Objective:** Lock past financial years to prevent backdated posting fraud.
- **Action 1 (Close FY):** Select `FY 2025-2026`. Toggle `is_closed = true`. Click `Save`.
- **Action 2 (Lockout Check):** Try to post a POS sale, GRN, or Journal Entry dated `2025-06-15` (within closed FY).
- **Expected Result:** System blocks entry: "Posting rejected. Financial Year FY 2025-2026 is CLOSED."

#### Test Case 2.5: Cross-Module Financial Year Reference
- **Objective:** Verify Merchandise Planning, Payroll, and TDS modules resolve active FY.
- **Action:** Go to `Merchandise Plannings` / `Employee Tax Declarations`. Click `Create`.
- **Expected Result:** Financial Year field auto-selects the currently active FY by default.

---

### 3.3 Branch Settings & Operational Configuration

#### Test Case 3.1: Branch Setting Initialization & Tenant Linkage
- **Objective:** Configure branch-specific operational parameters.
- **Action:** Navigate to `Branch Settings` -> Select `Coimbatore Main Branch`. Fill:
  - Default Receipt Header: `Apex Textiles - Coimbatore Branch`
  - Receipt Footer: `Thank you for shopping! Exchange within 7 days.`
  - Invoice Prefix: `INV-CBE-`
  - Default Cashier Discount Limit: `15%`
  - Allow Negative Stock: `false`
  - Save.
- **Expected Result:** Branch settings stored per `branch_id`.

#### Test Case 3.2: Receipt Footer & Header Rendering Verification
- **Objective:** Verify branch settings render on POS receipts.
- **Action:** Complete a POS sale at `Coimbatore Main Branch`. Generate receipt preview.
- **Expected Result:** Receipt header reads `Apex Textiles - Coimbatore Branch` and footer reads `Thank you for shopping! Exchange within 7 days.`

#### Test Case 3.3: Invoice Prefix & Sequential Counter Formatting
- **Objective:** Ensure invoices follow configured branch prefix.
- **Action:** Generate new B2B Sales Invoice at `Coimbatore Main Branch`.
- **Expected Result:** Invoice Number auto-formats as `INV-CBE-00001`.

#### Test Case 3.4: Discount Limit & Negative Stock Guardrails
- **Objective:** Enforce branch operational limits.
- **Action 1 (Discount Check):** In POS, cashier attempts 20% discount (limit is 15%).
  - **Expected Result:** Blocked: "Discount exceeds branch limit of 15%."
- **Action 2 (Negative Stock Check):** Attempt sale of out-of-stock item.
  - **Expected Result:** Blocked: "Branch settings prohibit negative stock transactions."

#### Test Case 3.5: Multi-Branch Setting Isolation
- **Objective:** Ensure Branch A settings do not impact Branch B.
- **Action:** Change `Chennai Retail Outlet` settings (Invoice Prefix: `INV-MAA-`, Discount Limit: `20%`).
- **Expected Result:** `Coimbatore Main Branch` retains `INV-CBE-` and `15%` limit without alteration.

---

### 3.4 Dashboard Widgets & Customization

#### Test Case 4.1: Dashboard Widget Registration & Active Toggle
- **Objective:** Manage dashboard analytics widgets.
- **Action:** Navigate to `Dashboard Widgets` -> Toggle visibility for `Daily Sales Summary`, `Low Stock Alerts`, `Pending Approvals`.
- **Expected Result:** Widget states update in database.

#### Test Case 4.2: Role-Based Widget Visibility Matrix
- **Objective:** Restrict sensitive widgets based on user role permissions.
- **Action 1 (Admin Login):** Log in as Super Admin.
  - **Expected Result:** Admin dashboard displays ALL widgets (`Daily Sales`, `Gross Profit`, `Payroll Expense`, `Stock Value`).
- **Action 2 (Cashier Login):** Log in as `Branch Cashier`.
  - **Expected Result:** Cashier dashboard displays ONLY `Daily Shift Sales` widget. Financial profit and HR payroll widgets are HIDDEN.

#### Test Case 4.3: Real-Time Widget Data Calculation Integrity
- **Objective:** Verify math on dashboard summary cards.
- **Action:** Check `Daily Sales Widget` total. Perform new POS sale of `2,500 INR`. Refresh dashboard.
- **Expected Result:** Widget aggregate count increases by exactly `2,500.00 INR`.

#### Test Case 4.4: Widget Position Drag & Drop Reordering
- **Objective:** Persist user dashboard layout customizations.
- **Action:** Reorder widgets on dashboard (Move `Low Stock Alerts` to position 1). Save Layout. Refresh page.
- **Expected Result:** Layout position preserved after page refresh.

---

## 4. Summary & Pass Criteria
| Component | Key Validation | Expected Result | Pass Criteria |
| :--- | :--- | :--- | :--- |
| **Geographic Master** | Cascading Selects & ISO Unique | Country -> State -> City cascading works | Pass |
| **Financial Years** | Date Overlap & Single Active | Only 1 active FY; Closed FY blocks posting | Pass |
| **Branch Settings** | Receipt/Invoice & Guardrails | Branch prefix & discount limits enforced | Pass |
| **Dashboard Widgets** | RBAC Visibility & Aggregates | Widgets reflect role permissions & real-time DB math | Pass |
"""

flows = {
    "1_Settings_Flow_Exhaustive": settings_flow_content,
    "1_Organization_Administration_Flow_Exhaustive": """# Exhaustive Manual Testing Guide: Organization & Administration Flow

## 1. Overview & Architecture Scope
The Organization & Administration Flow is the foundational pillar of the Textile Multi-Branch ERP system. It establishes the enterprise corporate structure, physical branch locations, departmental divisions, staff designations, multi-tenant branch isolation, financial calendar periods, settings management, user authentication, and granular role-based permissions (RBAC).

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

### 3.2 Settings & System Configuration Module

#### Test Case 2.1: Geographic Master Data & Cascading Validation
- **Objective:** Verify Country, State, and City setup with cascading dependencies.
- **Action:** Navigate to `Countries` (Create `India`), `States` (Create `Tamil Nadu` Code 33), `Cities` (Create `Chennai`, `Coimbatore`).
- **Validation:** Ensure form selects enforce Country -> State -> City constraints. Attempting to delete a State with linked active Cities must fail with a foreign key constraint error.

#### Test Case 2.2: Financial Year Management & Single Active Rule
- **Objective:** Manage fiscal calendars and enforce single active FY rule.
- **Action:** Create `FY 2026-2027` (`2026-04-01` to `2027-03-31`, `is_active = true`). Create `FY 2027-2028` (`2027-04-01` to `2028-03-31`, `is_active = true`).
- **Expected Result:** System automatically sets `is_active = false` on previous FY. Closing an FY (`is_closed = true`) blocks all backdated posting.

#### Test Case 2.3: Branch Settings Customization & POS Receipt Header/Footer
- **Objective:** Configure branch-specific invoice prefixes, receipt footers, and discount limits.
- **Action:** Open `Branch Settings` for `Coimbatore Main Branch`. Set Receipt Footer text "Thank you for shopping!", Invoice Prefix `INV-CBE-`, Max Cashier Discount `15%`.
- **Expected Result:** POS invoices automatically format as `INV-CBE-00001` and cashier discounts above 15% are blocked.

#### Test Case 2.4: Dashboard Widget Authorization & Visibility Matrix
- **Objective:** Control widget visibility based on user roles.
- **Action:** Open `Dashboard Widgets`. Configure visibility settings. Log in as Cashier vs Super Admin.
- **Expected Result:** Cashiers see only `Daily Sales` widget; financial profit/payroll widgets are hidden.

---

### 3.3 Access Control & Multi-Tenancy Module

#### Test Case 3.1: Granular Role & Permission Matrix Assignment
- **Objective:** Create custom roles with strict permission limits.
- **Action:** Navigate to `Roles` -> `Create`. Name role `Branch Cashier`.
- **Action:** In permissions grid, enable ONLY: `view_pos_sales`, `create_pos_sales`, `view_products`, `view_any_products`. Leave all user management, company, and financial ledger permissions unchecked.
- **Expected Result:** Saved. Database pivot table `role_has_permissions` stores exactly 4 permissions.

#### Test Case 3.2: User Onboarding & Multi-Branch Access Linking
- **Objective:** Link a user to roles and physical branch tenants.
- **Action 1:** Navigate to `Users` -> `Create`. Enter Name `Ravi Kumar`, Email `cashier.cbe@apex.com`, Password `SecretPassword123!`. Assign Role `Branch Cashier`.
- **Action 2:** Navigate to `Branch Users` -> Create link between `Ravi Kumar` and `Coimbatore Main Branch`.
- **Action 3:** Navigate to `User Branch Accesses` -> Grant access for `Coimbatore Main Branch`.
- **Expected Result:** User created successfully with encrypted password in DB.

#### Test Case 3.3: Security Isolation & Direct URL Hardening (Security Pass)
- **Objective:** Ensure multi-tenancy and permissions strictly block unauthorized access.
- **Action 1 (Login):** Log out Admin. Log in as `cashier.cbe@apex.com`.
  - **Expected Result:** Navigation header displays active tenant `Coimbatore Main Branch`. Sidebar ONLY displays `POS Sales` and `Products`.
- **Action 2 (Direct URL Bypass Attack):** Manually type `/admin/users` or `/admin/companies` in browser URL bar.
  - **Expected Result:** System intercepts request and returns `403 Forbidden` / `Unauthorized Access` page.
"""
}

print("Starting generation of Settings flow test case guides...")

for name, content in flows.items():
    md_file = os.path.join(output_dir, f"{name}.md")
    pdf_file = os.path.join(output_dir, f"{name}.pdf")
    
    with open(md_file, "w", encoding="utf-8") as f:
        f.write(content)
        
    print(f"Generating PDF: {pdf_file}...")
    pdf = MarkdownPdf(toc_level=0)
    pdf.add_section(Section(content))
    pdf.save(pdf_file)

print("SUCCESS: Settings flow test case guides generated successfully!")
