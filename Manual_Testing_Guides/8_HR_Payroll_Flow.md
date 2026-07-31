# Testing Guide: HR & Payroll Flow (HRMS)

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
