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
