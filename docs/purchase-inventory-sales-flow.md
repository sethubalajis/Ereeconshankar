# Purchase Inventory Sales Flow

## End-to-End Functional Flow

```mermaid
flowchart LR
    A[Purchase Requisition] --> B[RFQ / Vendor Quote]
    B --> C[Purchase Order]
    C --> D[Goods Receipt Note (GRN)]
    D --> E[Quality Check]
    E --> F[Putaway to Warehouse/Bin]
    F --> G[Inventory On-Hand Increased]
    G --> H[Inventory Available for Sale]

    H --> I[Sales Order / POS Sale]
    I --> J[Pick List]
    J --> K[Pack]
    K --> L[Dispatch / Invoice]
    L --> M[Inventory On-Hand Decreased]
    L --> N[Payment Collection]
    N --> O[Accounting Entries]

    D --> P[Purchase Accounting Entry]
    C --> Q[PO Status Tracking]
    I --> R[Sales Status Tracking]
```

## Negative/Exception Paths

```mermaid
flowchart TD
    A[Purchase Order Created] --> B{Vendor Delivers?}
    B -- No --> B1[PO Delayed / Follow-up] --> B2[No Stock Increase]
    B -- Yes --> C[GRN Entry]

    C --> D{Qty Matches PO?}
    D -- Full Match --> E[GRN Complete]
    D -- Partial --> E1[Partial GRN]
    E1 --> E2[Backorder Open]
    D -- Excess/Invalid --> E3[Reject Extra Qty]

    E --> F{QC Pass?}
    E1 --> F
    F -- Pass --> G[Putaway to Bin]
    F -- Fail --> F1[Rejected Stock Area]
    F1 --> F2[Vendor Return]
    F2 --> F3[Purchase Return Note]

    G --> H[Inventory On-Hand Increased]
    H --> I{Sales Request/POS}
    I --> J{Stock Available?}
    J -- No --> J1[Block Sale or Allow Backorder]
    J -- Yes --> K[Pick + Pack + Dispatch]

    K --> L{Dispatch Complete?}
    L -- No --> L1[Pending Shipment]
    L -- Yes --> M[Invoice Generated]

    M --> N{Payment Received?}
    N -- Full --> O[Close Sale]
    N -- Partial --> O1[Open Receivable]
    N -- No --> O2[Credit Control Follow-up]

    K --> P{Customer Return?}
    P -- Yes --> Q[Sales Return]
    Q --> R[Restock or Scrap]
    P -- No --> S[Flow Complete]

    H --> T[Inventory Ledger Check]
    M --> U[Accounting Entry Check]
    F2 --> U
```

## Functional Test Checkpoints

1. Purchase side: requisition, PO approval, GRN posting, and inventory increase.
2. Inventory side: putaway location updates, available quantity, and status transitions.
3. Sales side: sale creation, pick/pack/dispatch, inventory deduction, invoice/payment.
4. Side effects: inventory ledger rows and accounting entries at each branch.
5. Negative flows: partial GRN, rejected QC, insufficient stock, partial/no payment, sales return.

## Inventory Count to Reconciliation Flow

```mermaid
flowchart TD
    A[Create Inventory Count] --> B{Status Selected}
    B -- Draft --> C[Save Count Only]
    C --> C1[No Reconciliation Rows Created]

    B -- Active --> D[Save Count]
    D --> E[Read Stock by Branch and Warehouse]
    E --> F[Create Reconciliation Rows for Product and Variant]
    F --> G[Set System Qty from On-Hand]
    G --> H[Set Default Physical Qty]
    H --> I[Variance Auto-Calculated]

    J[User Edits Physical Qty] --> K[Recalculate Variance]
    K --> L[Save Reconciliation Line]
```

### Functional Rules

1. On Inventory Count create page, user can choose Draft or Active.
2. If Draft is chosen, only inventory count is saved and reconciliation rows are not generated.
3. If status changes from Draft to Active, system must generate inventory reconciliation rows for selected branch and warehouse.
4. Reconciliation rows must include product and variant combinations available in that branch and warehouse.
5. `system_qty` must be auto-filled from current stock.
6. User edits `physical_qty` line by line in Inventory Reconciliation.
7. `variance` must always be calculated automatically using:

$$
variance = physical\_qty - system\_qty
$$

8. Reconciliation generation must be idempotent and should not create duplicate rows for the same count.

### Inventory Count/Reconciliation Test Checkpoints

1. Create in Draft and verify no reconciliation rows are created.
2. Update same count to Active and verify reconciliation rows are generated.
3. Verify generated rows contain expected product and variant combinations for the selected branch and warehouse.
4. Verify `system_qty` is populated from stock source.
5. Edit `physical_qty` and verify `variance` is auto-recalculated.
6. Save repeatedly and verify duplicate reconciliation rows are not created.

### Inventory Count/Reconciliation Negative Paths

```mermaid
flowchart TD
    A[Inventory Count in Draft] --> B{Changed to Active?}
    B -- No --> C[Keep Draft]
    C --> C1[No Reconciliation Generation]

    B -- Yes --> D[Start Generation]
    D --> E{Stock Found for Branch/Warehouse?}
    E -- No --> F[Set Active with Empty Reconciliation]
    F --> F1[Show: No stock found]

    E -- Yes --> G[Prepare Product/Variant Rows]
    G --> H{Rows Already Exist for Same Count?}
    H -- Yes --> I[Skip Duplicates]
    H -- No --> J[Insert Reconciliation Rows]

    J --> K[User Enters Physical Qty]
    K --> L[Auto Calculate Variance]
    L --> M{Invalid Qty?}
    M -- Yes --> N[Block Save with Validation]
    M -- No --> O[Save Line]
```

### Inventory Count/Reconciliation Exception Checkpoints

1. Activate a count with no stock and verify active status is saved with zero reconciliation rows.
2. Re-open and save active count again and verify no duplicate rows are created.
3. Enter invalid physical quantity and verify save is blocked by validation.
4. Edit valid physical quantity and verify variance is recalculated and stored.
