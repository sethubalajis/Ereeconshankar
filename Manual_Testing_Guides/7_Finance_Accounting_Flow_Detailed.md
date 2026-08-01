# Comprehensive Manual Testing Guide: Finance & Accounting Flow

## 1. Overview
Double-entry bookkeeping validation. If this module fails, the ERP fails audit.

## 2. Detailed Test Cases

### Test Case 1: Chart of Accounts Constraints
**Objective:** Ensure the financial tree is structured logically.
- **Action:** View `Chart of Accounts`. Ensure root types exist (Assets, Liabilities, Equity, Revenue, Expenses).
- **Validation:** Attempt to delete a root account (e.g., 'Revenue').
- **Expected Result:** System completely blocks deletion (hard-coded or restricted).
- **Action:** Create a sub-ledger under Expenses called 'Branch Electricity'.

### Test Case 2: Double-Entry Validation (The Core Test)
**Objective:** Ensure impossible unbalanced journals cannot be saved.
- **Action (Negative):** Go to `Journals` -> `Create Journal Entry`.
  - Line 1: Debit 'Branch Electricity' for 5000.
  - Line 2: Credit 'Cash' for 4000.
  - Click Save.
- **Expected Result:** System throws a hard error: "Journal is unbalanced. Total Debits (5000) must equal Total Credits (4000)".
- **Action (Positive):** Adjust Line 2 Credit to 5000. Click Save.
- **Expected Result:** Entry posts successfully.

### Test Case 3: Automated Ledger Postings
**Objective:** Verify that operational actions trigger financial entries automatically.
- **Action:** Perform a POS Sale for 1000 Rs cash (from Flow 5).
- **Expected Result:** Navigate to `Journal Entries`. You should see an auto-generated entry:
  - Debit: Cash Account (1000)
  - Credit: Sales Revenue Account (1000)
  - (If COGS is tracked perpetually): Debit COGS, Credit Inventory.
- **Validation:** Verify that these auto-generated entries CANNOT be manually edited or deleted by standard accounting staff to prevent fraud.

### Test Case 4: Contra Entries & Cash Flow
**Objective:** Track money moving between internal accounts (Cash to Bank).
- **Action:** Go to `Contra Entries`. Transfer 10,000 from 'Cash in Hand' to 'HDFC Current A/c'.
- **Expected Result:** Verify `Trial Balance` report. Cash decreases by 10k, HDFC increases by 10k.
