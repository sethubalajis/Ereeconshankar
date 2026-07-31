# Testing Guide: Point of Sale (POS) & Retail Sales Flow

## Overview
Handles the front-end billing and retail operations at branch locations.

## Components to Test
POS Terminals, Counters, POS Sales, Sale Items, Returns, Cash Registers, Cash Openings/Closings, Payments, Receipts.

## Step-by-Step Manual Testing
1. **Infrastructure Setup**
   - Go to `POS Terminals` and `POS Counters` -> Create a terminal for a specific branch.

2. **Shift Management (Cash Register)**
   - Go to `Cash Openings` -> Open a register with a float amount (e.g., 5000 Rs).
   - **Check:** Is the register marked as 'Open'?

3. **Sales Processing**
   - Go to `POS Sales` -> Create a new sale transaction. Add products to `POS Sale Items`.
   - Apply taxes (if applicable) and discounts.
   - Go to `Payments` -> Record the payment for the sale (Cash/Card/UPI).
   - **Check:** Did the stock decrease in the branch inventory? Did the Cash Register total update?

4. **Returns & Closing**
   - Go to `POS Returns` -> Process a return for an item sold.
   - **Check:** Did the stock return to inventory?
   - Go to `Cash Closings` -> Close the register at the end of the day.
   - **Check:** Does the closing amount match the opening + sales - returns?
