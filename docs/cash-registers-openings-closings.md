# Cash Registers, Cash Openings, and Cash Closings

## Purpose

These three modules work together to track the daily cash cycle for a POS terminal and cashier.

Business flow:

1. Create a cash register for a POS terminal, user, and date.
2. Record the opening cash amount.
3. Record the closing cash amount at the end of the shift or day.
4. Track any closing difference as variance.

This setup supports cashier accountability, shift closing, and daily cash reconciliation.

---

## 1. Cash Registers

### Purpose

Cash Registers is the master transaction record for a cashier session.

Each record links:

1. POS terminal
2. User
3. Date
4. Opening balance
5. Closing balance
6. Status (`open` or `closed`)

Admin location:

- Admin -> Cash Registers

### How it works

When terminal, user, and date are selected, the form checks whether a register already exists for that combination.

If found:

1. `opening_balance` is filled from the sum of related cash openings.
2. `closing_balance` is filled from the sum of related cash closings.

If not found:

1. `opening_balance` defaults to `0.00`
2. `closing_balance` defaults to `0.00`

### Stored data

Table: `cash_registers`

Fields:

1. `pos_terminal_id`
2. `user_id`
3. `date`
4. `opening_balance`
5. `closing_balance`
6. `status`

---

## 2. Cash Openings

### Purpose

Cash Openings records the starting cash amount for a selected cash register.

This is usually the opening float or carry-forward cash for the day/shift.

Admin location:

- Admin -> Cash Openings

### How opening amount is populated

When a cash register is selected, the form tries to find the previous register for the same POS terminal.

Logic:

1. Find previous register for the same `pos_terminal_id`.
2. Find the latest cash closing for that previous register.
3. If found, use previous `closing_amount` as current `opening_amount`.
4. If no previous closing exists, use previous register `closing_balance`.

This means opening cash is intended to continue from the previous session.

### Stored data

Table: `cash_openings`

Fields:

1. `cash_register_id`
2. `opening_time`
3. `opening_amount`

---

## 3. Cash Closings

### Purpose

Cash Closings records the expected closing cash and the closing-time reconciliation result.

Admin location:

- Admin -> Cash Closings

### How closing amount is calculated

When a cash register is selected, the system calculates:

$$
\text{closing\_amount} = \text{opening\_amount total} + \text{POS sales total}
$$

Actual implementation:

1. Sum all `opening_amount` for the selected `cash_register_id`.
2. Sum `net_amount` from `pos_sales` where:
   - `pos_terminal_id` matches the register terminal
   - `sale_date` matches the register date
3. Set `closing_amount = opening total + sales total`

### What Variance means

`variance` is a manual reconciliation field.

Suggested business meaning:

$$
\text{variance} = \text{physically counted cash} - \text{expected closing amount}
$$

Examples:

1. Expected = 10,000 and counted = 9,800 -> variance = -200
2. Expected = 10,000 and counted = 10,150 -> variance = 150

Important current behavior:

1. `closing_amount` is auto-filled by the system.
2. `variance` is not auto-calculated.
3. User must enter `variance` manually.

### Stored data

Table: `cash_closings`

Fields:

1. `cash_register_id`
2. `closing_time`
3. `closing_amount`
4. `variance`

---

## End-to-End Workflow

Typical daily process:

1. Create Cash Register for terminal, user, and date.
2. Create Cash Opening for that register.
3. System carries forward previous closing amount when possible.
4. During business, POS sales are recorded in `pos_sales`.
5. Create Cash Closing for the same register.
6. System calculates expected closing amount.
7. Cashier counts physical cash and enters variance manually.

---

## Important Notes About Current Implementation

1. Opening and closing balances depend on related opening and closing records.
2. Closing amount uses POS sales total plus opening total.
3. Variance is manual, not automated.
4. Closing logic does not currently subtract expenses, cash payouts, refunds, or bank deposits unless those are already reflected elsewhere in sales/cash handling.

---

## Source Code References

1. Cash Register form: `app/Filament/Resources/CashRegisters/Schemas/CashRegisterForm.php`
2. Cash Register table: `app/Filament/Resources/CashRegisters/Tables/CashRegistersTable.php`
3. Cash Opening form: `app/Filament/Resources/CashOpenings/Schemas/CashOpeningForm.php`
4. Cash Opening table: `app/Filament/Resources/CashOpenings/Tables/CashOpeningsTable.php`
5. Cash Closing form: `app/Filament/Resources/CashClosings/Schemas/CashClosingForm.php`
6. Cash Closing table: `app/Filament/Resources/CashClosings/Tables/CashClosingsTable.php`
7. Cash Registers migration: `database/migrations/2026_06_29_070400_create_cash_registers_table.php`
8. Cash Registers date migration: `database/migrations/2026_07_10_085940_add_date_to_cash_registers_table.php`
9. Cash Openings migration: `database/migrations/2026_06_29_070402_create_cash_openings_table.php`
10. Cash Closings migration: `database/migrations/2026_06_29_070405_create_cash_closings_table.php`
