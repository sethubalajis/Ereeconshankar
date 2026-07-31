# Comprehensive Manual Testing Guide: Organization & Administration Flow

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
