# Testing Guide: GST Taxation & Compliance Flow (India)

## Overview
Handles Indian statutory requirements and tax filings.

## Components to Test
GST Categories, Rates, HSN/SAC Codes, Tax Groups, Tax Invoices, E-Invoices, E-Way Bills, GST Returns, TDS/TCS.

## Step-by-Step Manual Testing
1. **Tax Configuration**
   - Go to `GST Categories` and `GST Rates` -> Ensure standard rates (e.g., 5%, 12%, 18%) exist.
   - Go to `HSN/SAC Codes` -> Create an HSN code for a textile product and link the GST rate (e.g., 5%).
   - Link this HSN code to a `Product` in the PIM flow.

2. **Tax Application**
   - Create a `Purchase Order` (intra-state) or a B2B `POS Sale`.
   - **Check:** Verify that the system automatically splits the 5% GST into 2.5% CGST and 2.5% SGST.
   - Create an inter-state sale.
   - **Check:** Verify that the system applies 5% IGST instead.

3. **Compliance Documents**
   - Go to `Tax Invoices` -> Generate a formal B2B tax invoice.
   - **Check:** Ensure the GSTIN numbers (company and customer) and HSN codes print on the invoice.
   - Go to `TDS/TCS Records` -> If applicable, verify TDS is deducted on specific vendor payments.
