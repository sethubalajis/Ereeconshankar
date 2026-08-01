# Testing Guide: Procurement & Vendor Flow

## Overview
Manages the purchasing cycle from requesting items to paying suppliers.

## Components to Test
Vendors, Categories, Agreements, Price Lists, Purchase Requisitions, RFQs, Purchase Orders, Returns, Payments.

## Step-by-Step Manual Testing
1. **Vendor Setup**
   - Go to `Vendor Categories` -> Create a category (e.g., 'Fabric Suppliers').
   - Go to `Vendors` -> Create a new vendor and link to the category. Add `Vendor Contacts`.

2. **Procurement Cycle**
   - Go to `Purchase Requisitions` -> Create an internal request for products.
   - Go to `RFQs` (Request for Quotation) -> Convert the requisition into an RFQ for the vendor.
   - Go to `Purchase Orders` (PO) -> Convert the RFQ into a PO. Add `Purchase Order Items`.
   - **Check:** Ensure status transitions smoothly (Draft -> Approved -> Sent).

3. **Receiving & Payment**
   - (Link with Inventory) Create a GRN against this PO.
   - Go to `Vendor Payments` -> Record a payment against the vendor's invoice.
   - Go to `Purchase Returns` -> Log a return for defective items.
   - **Check:** Does the return adjust the vendor balance and stock levels?
