# Comprehensive Manual Testing Guide: HR & Payroll Flow (HRMS)

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
