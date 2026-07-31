# Exhaustive Manual Testing Guide: Organization & Administration Flow

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
