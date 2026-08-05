# Testing Guide: Organization & Administration Flow

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
