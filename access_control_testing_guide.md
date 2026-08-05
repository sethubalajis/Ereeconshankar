# Testing Guide: Access Control & Multi-Tenancy

To ensure the **Access Control & Multi-Tenancy** flow is working correctly, you need to verify that a user can only access the specific branches they are assigned to, and can only perform the actions allowed by their role. 

Because your ERP uses Filament's multi-tenancy feature alongside the Spatie Permission package, follow this exact testing flow to verify everything is functioning perfectly:

### Step 1: Verify Permissions (The Rules)
Permissions are the lowest level of access control (e.g., `view_products`, `create_users`).
1. Navigate to **Permissions** in the admin dashboard.
2. **Crucial Check:** Verify that permissions exist. Usually, the system automatically generates these for every resource (view, view_any, create, update, delete, etc.). If none exist, you may need to run your permission seeder.

### Step 2: Create a Role (The Grouping)
Roles group multiple permissions together (e.g., "Branch Manager", "Cashier").
1. Navigate to **Roles** and click **Create Role**.
2. Name the role (e.g., `Test Cashier`).
3. Select a limited set of permissions for this role (e.g., only `view_products` and `create_pos_sales`). Do *not* give them admin permissions.
4. Save the role.

### Step 3: Create a User & Assign the Role
1. Navigate to **Users** and click **Create User**.
2. Fill in the user's details (Name, Email, Password).
3. **Crucial Check:** Look for the Roles section on the user creation form and assign the `Test Cashier` role you just created.
4. Save the user.

### Step 4: Grant User Branch Access (Tenancy Access)
*This is the most critical step for multi-branch ERPs.* Because your `User` model implements multi-tenancy, a user **cannot access the dashboard at all** unless they are tied to a branch.
1. Navigate to **User Branch Accesses** (or find the Branch Access relation manager on the User's edit page).
2. Create a new access record linking the **Test User** to a specific **Branch** (e.g., "Main Branch").
3. (Optional) Link them to a second branch to test switching between branches.

### Step 5: Assign Branch User (Physical Assignment)
While `User Branch Access` dictates what they can see in the software, `Branch User` usually dictates where they are physically employed or their primary operating location.
1. Navigate to **Branch Users**.
2. Create a record linking the **Test User** to their primary **Branch**.

### Step 6: The Final Verification (Logging In)
To truly ensure the flow works, you must test it from the perspective of the new user.
1. Log out of your Admin account (or open an Incognito/Private browsing window).
2. Log in using the **Test User's** email and password.
3. **Crucial Check 1 (Tenancy):** Look at the top navigation bar. You should see a dropdown or indicator showing the active Branch. If you gave them access to 2 branches in Step 4, they should be able to toggle between them. They should *not* see branches they weren't granted access to.
4. **Crucial Check 2 (Permissions):** Look at the sidebar menu. Since you only gave this user the `view_products` and `create_pos_sales` permissions, **most of the menu items should be completely hidden from them.** If they can see the "Users" or "Settings" menus, the role permissions are not restricting access properly.
