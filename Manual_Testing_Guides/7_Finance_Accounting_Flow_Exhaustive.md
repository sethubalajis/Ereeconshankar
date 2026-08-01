# Exhaustive Manual Testing Guide: Finance & Accounting Flow

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
