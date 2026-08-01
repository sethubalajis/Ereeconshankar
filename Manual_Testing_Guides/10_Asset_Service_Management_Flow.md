# Testing Guide: Asset & Service Management Flow

## Overview
Tracks company-owned assets and handles maintenance or external servicing.

## Components to Test
Assets, Categories, Allocations, Depreciations, Maintenances, Disposals, Service Requests, Technician Visits.

## Step-by-Step Manual Testing
1. **Asset Lifecycle**
   - Go to `Asset Categories` -> Create a category (e.g., 'Sewing Machines').
   - Go to `Assets` -> Register a new machine with its purchase value and date.
   - Go to `Asset Allocations` -> Allocate the machine to a specific Branch or Employee.
   - Go to `Asset Depreciations` -> Run a depreciation cycle.
   - **Check:** Verify the asset's current book value decreases correctly based on the depreciation method.

2. **Maintenance & Servicing**
   - Go to `Asset Maintenances` -> Schedule a routine maintenance for the machine.
   - Go to `Service Requests` -> Log a breakdown ticket.
   - Go to `Technician Visits` -> Assign a technician and log their visit details and resolution.
   - **Check:** Ensure the service request status changes to 'Resolved'.
