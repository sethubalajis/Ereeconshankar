# Testing Guide: Finance & Accounting Flow

## Overview
Handles the core financial ledgers and accounting entries.

## Components to Test
Chart of Accounts, Account Groups, Ledgers, Cost Centers, Bank Accounts, Journals, Journal Entries, Contra Entries, Trial Balances.

## Step-by-Step Manual Testing
1. **Chart of Accounts Setup**
   - Go to `Account Groups` -> Create a group (e.g., 'Current Assets').
   - Go to `Chart of Accounts` / `Ledgers` -> Create a ledger (e.g., 'HDFC Bank Account') under the group.
   - Go to `Cost Centers` -> Create a cost center for a specific branch.

2. **Manual Journal Entries**
   - Go to `Journals` -> Create a general journal.
   - Go to `Journal Entries` -> Add debit and credit lines. (e.g., Debit Rent Expense, Credit Cash).
   - **Check:** Ensure the system enforces that Total Debits = Total Credits before saving.

3. **Contra Entries & Verification**
   - Go to `Contra Entries` -> Record a cash deposit into the bank.
   - **Check:** Go to the `Trial Balances` or `Ledgers` report. Verify that the balances for Cash and Bank updated correctly reflecting the double-entry bookkeeping rules.
