<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Report Detailed Breakdown</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            margin: 0;
            padding: 20px;
            line-height: 1.4;
        }
        .header {
            border-bottom: 2px solid #059669;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .company {
            font-size: 20px;
            font-weight: bold;
            color: #047857;
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 13px;
            color: #475569;
            margin-top: 3px;
            font-weight: 600;
        }
        .summary-box {
            background: #f0fdf4;
            border: 1px solid #a7f3d0;
            padding: 10px 14px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 3px 0;
            font-size: 11px;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
            margin-top: 15px;
            margin-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .data-table th {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 7px 9px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            color: #1e293b;
        }
        .data-table td {
            border: 1px solid #e2e8f0;
            padding: 7px 9px;
            font-size: 10.5px;
        }
        .data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9.5px;
            font-weight: bold;
            text-transform: uppercase;
            background: #e2e8f0;
            color: #334155;
        }
        .badge-approved, .badge-completed {
            background: #dcfce7;
            color: #166534;
        }
        .badge-pending, .badge-submitted, .badge-draft {
            background: #fef3c7;
            color: #92400e;
        }
        .badge-rejected, .badge-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company">eReckon ERP System</div>
        <div class="subtitle">Detailed Purchase Report</div>
    </div>

    <div class="summary-box">
        <table class="summary-table">
            <tr>
                <td><strong>Branch:</strong> {{ $record->branch->name ?? 'N/A' }}</td>
                <td>
                    @if($record->from_date && $record->to_date)
                        <strong>Period:</strong> {{ \Carbon\Carbon::parse($record->from_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($record->to_date)->format('d M Y') }}
                    @elseif($record->from_date)
                        <strong>From:</strong> {{ \Carbon\Carbon::parse($record->from_date)->format('d M Y') }}
                    @elseif($record->report_date)
                        <strong>Report Date:</strong> {{ \Carbon\Carbon::parse($record->report_date)->format('d M Y') }}
                    @endif
                </td>
                <td class="text-right"><strong>Total Purchases Amount:</strong> ₹{{ number_format($record->total_purchases, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">Purchase Orders Summary</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">#</th>
                <th>PO Number</th>
                <th>Vendor</th>
                <th>PO Date</th>
                <th>Expected Delivery</th>
                <th class="text-right">Total Amount</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalPoAmount = 0; 
            @endphp
            @forelse($purchaseOrders as $index => $po)
                @php
                    $totalPoAmount += $po->total_amount;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $po->po_number }}</strong></td>
                    <td>{{ $po->vendor->name ?? 'N/A' }}</td>
                    <td>{{ $po->po_date ? \Carbon\Carbon::parse($po->po_date)->format('d M Y') : 'N/A' }}</td>
                    <td>{{ $po->expected_delivery_date ? \Carbon\Carbon::parse($po->expected_delivery_date)->format('d M Y') : 'N/A' }}</td>
                    <td class="text-right font-bold">₹{{ number_format($po->total_amount, 2) }}</td>
                    <td class="text-center">
                        <span class="badge badge-{{ strtolower($po->status) }}">
                            {{ ucfirst($po->status) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="color: #64748b; padding: 15px;">
                        No purchase orders found for the selected branch and report date.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if(count($purchaseOrders) > 0)
        <tfoot>
            <tr style="background: #f1f5f9;" class="font-bold">
                <td colspan="5" class="text-right">Total Purchase Orders Amount:</td>
                <td class="text-right">₹{{ number_format($totalPoAmount, 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
        @endif
    </table>

    @php
        $hasItems = false;
        foreach($purchaseOrders as $po) {
            if ($po->items && $po->items->count() > 0) {
                $hasItems = true;
                break;
            }
        }
    @endphp

    @if($hasItems)
    <div class="section-title">Purchased Items Breakdown</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">#</th>
                <th>PO Number</th>
                <th>Product</th>
                <th>Variant / SKU</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Total Price</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $itemIndex = 1;
                $totalItemAmount = 0;
                $totalQuantity = 0;
            @endphp
            @foreach($purchaseOrders as $po)
                @foreach($po->items as $item)
                    @php
                        $totalItemAmount += $item->total_price;
                        $totalQuantity += $item->quantity;
                    @endphp
                    <tr>
                        <td>{{ $itemIndex++ }}</td>
                        <td><strong>{{ $po->po_number }}</strong></td>
                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                        <td>{{ $item->productVariant->sku ?? '-' }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">₹{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right font-bold">₹{{ number_format($item->total_price, 2) }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background: #f1f5f9;" class="font-bold">
                <td colspan="4" class="text-right">Total Items Summary:</td>
                <td class="text-right">{{ $totalQuantity }}</td>
                <td></td>
                <td class="text-right">₹{{ number_format($totalItemAmount, 2) }}</td>
            </tr>
        </tfoot>
    </table>
    @endif

    <div class="footer">
        Generated on {{ now()->format('d M Y, h:i A') }} &bull; eReckon ERP Confidential Report
    </div>
</body>
</html>
