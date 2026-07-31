# Testing Guide: Core Organizational Structure

To ensure that the core organizational structure flow is working correctly, test the creation and linking of these records step-by-step through the admin dashboard. 

Follow this exact testing flow to verify everything is functioning perfectly:

### Step 1: Create a Company (The Top Level)
Since all other entities branch out from the company, you must create it first.
1. Navigate to **Companies** in the menu.
2. Click **Create Company**.
3. Fill in the name and details.
4. **Crucial Check:** Ensure that the cascading dropdowns for `Country` -> `State` -> `City` work correctly and save to the database.

### Step 2: Create a Business Unit (Linked to Company)
Business units segment a company (e.g., "Retail", "Manufacturing").
1. Navigate to **Business Units**.
2. Click **Create Business Unit**.
3. **Crucial Check:** Ensure there is a dropdown to select the `Company` you just created.
4. **Crucial Check:** After saving, verify that the system automatically generated the custom code (e.g., `BU-0001`). The `code` field is set to auto-generate in the background.

### Step 3: Create a Branch (Linked to Company)
Branches represent physical locations under a company.
1. Navigate to **Branches**.
2. Click **Create Branch**.
3. **Crucial Check:** Ensure you can select the parent `Company` from the dropdown.
4. **Crucial Check:** Test the `Country` -> `State` -> `City` selectors again here.
5. **Crucial Check:** After saving, verify that a random 6-character branch code was automatically generated (e.g., `BR-X9A2BZ`).

### Step 4: Create a Department (Linked to Branch)
Departments exist within specific branches.
1. Navigate to **Departments**.
2. Click **Create Department** (e.g., "Sales", "HR").
3. **Crucial Check:** Ensure you are prompted to select the `Branch` this department belongs to.
4. **Crucial Check:** After saving, check that the department code auto-incremented correctly (e.g., `DPT-0001`).

### Step 5: Create a Designation (Master List)
Designations are job titles. These act as a standalone master list rather than being strictly tied to a single branch.
1. Navigate to **Designations**.
2. Click **Create Designation** (e.g., "Store Manager", "Cashier").
3. **Crucial Check:** After saving, ensure the designation code auto-incremented properly (e.g., `DSG-0001`).

---
### Summary of Expected Behavior
If the flow is working correctly:
* You will not encounter any SQL constraint errors when saving.
* All dropdowns will successfully load data from their parent tables.
* All auto-generated codes (**BU-**, **BR-**, **DPT-**, **DSG-**) must populate instantly in the table view after hitting save.
