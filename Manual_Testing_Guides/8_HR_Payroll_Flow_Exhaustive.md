# Exhaustive Manual Testing Guide: HR & Payroll Flow (HRMS)

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
