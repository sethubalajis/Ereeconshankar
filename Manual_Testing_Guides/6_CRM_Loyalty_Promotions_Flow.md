# Testing Guide: CRM, Loyalty & Promotions Flow

## Overview
Manages customer relationships, retention, and marketing offers.

## Components to Test
Customers, Groups, Wallets, Feedback, Loyalty Programs, Points, Cards, Promo Codes, Coupons, Combo Offers, Complaint Tickets.

## Step-by-Step Manual Testing
1. **Customer & Loyalty Setup**
   - Go to `Customer Groups` -> Create a group (e.g., 'VIP').
   - Go to `Customers` -> Create a customer.
   - Go to `Loyalty Programs` and `Membership Plans` -> Set up a point-earning rule (e.g., 1 point per 100 Rs).

2. **Promotions & Offers**
   - Go to `Promo Codes` or `Coupons` -> Create a 10% off coupon valid for a specific date range.
   - Go to `Buy-X-Get-Y Offers` or `Combo Offers` -> Create a promotional offer.

3. **Verification in Sales**
   - Process a `POS Sale` (from Flow 5) for this customer.
   - **Check:** Apply the Promo Code. Did the total decrease by 10%?
   - **Check:** After the sale, go to `Loyalty Points` or `Customer Wallets`. Did the customer earn the correct amount of points based on the loyalty program rules?

4. **Customer Support**
   - Go to `Complaint Tickets` -> Log a ticket for the customer.
   - **Check:** Update the status from 'Open' to 'Resolved'.
