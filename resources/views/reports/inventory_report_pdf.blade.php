<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inventory Report Detailed Breakdown</title>
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
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .company {
            font-size: 20px;
            font-weight: bold;
            color: #3730a3;
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 13px;
            color: #475569;
            margin-top: 3px;
            font-weight: 600;
        }
        .summary-box {
            background: #eef2ff;
            border: 1px solid #c7d2fe;
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
        .badge-active, .badge-completed {
            background: #dcfce7;
            color: #166534;
        }
        .badge-draft {
            background: #fef3c7;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company">eReckon ERP System</div>
        <div class="subtitle">Detailed Inventory Report</div>
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
                <td class="text-right"><strong>Total Inventory Value:</strong> ₹{{ number_format($record->total_inventory_value, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">Branch Inventory & Stock Breakdown</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">#</th>
                <th>Product</th>
                <th>SKU / Variant</th>
                <th class="text-right">Quantity</th>
                <th class="text-right">Unit Cost</th>
                <th class="text-right">Total Valuation</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalQty = 0;
                $totalValue = 0;
                $items = $branchInventories->isNotEmpty() ? $branchInventories : $stocks;
            @endphp
            @forelse($items as $index => $item)
                @php
                    $qty = (float) $item->quantity;
                    $cost = (float) ($item->productVariant?->cost_price ?: ($item->productVariant?->selling_price ?: 0));
                    $val = $qty * $cost;
                    $totalQty += $qty;
                    $totalValue += $val;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $item->product->name ?? 'N/A' }}</strong></td>
                    <td>{{ $item->productVariant->sku ?? '-' }}</td>
                    <td class="text-right">{{ number_format($qty, 2) }}</td>
                    <td class="text-right">₹{{ number_format($cost, 2) }}</td>
                    <td class="text-right font-bold">₹{{ number_format($val, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="color: #64748b; padding: 15px;">
                        No inventory records found for the selected branch.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if(count($items) > 0)
        <tfoot>
            <tr style="background: #f1f5f9;" class="font-bold">
                <td colspan="3" class="text-right">Total Inventory Summary:</td>
                <td class="text-right">{{ number_format($totalQty, 2) }}</td>
                <td></td>
                <td class="text-right">₹{{ number_format($totalValue, 2) }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    @if(isset($inventoryCounts) && $inventoryCounts->count() > 0)
    <div class="section-title">Inventory Counts & Reconciliations</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">#</th>
                <th>Count Code / ID</th>
                <th>Warehouse</th>
                <th>Count Date</th>
                <th class="text-center">Status</th>
                <th class="text-right">Reconciled Items</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventoryCounts as $index => $count)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $count->count_code ?? ('IC-' . $count->id) }}</strong></td>
                    <td>{{ $count->warehouse->name ?? 'N/A' }}</td>
                    <td>{{ $count->count_date ? \Carbon\Carbon::parse($count->count_date)->format('d M Y') : \Carbon\Carbon::parse($count->created_at)->format('d M Y') }}</td>
                    <td class="text-center">
                        <span class="badge badge-{{ strtolower($count->status) }}">
                            {{ ucfirst($count->status) }}
                        </span>
                    </td>
                    <td class="text-right">{{ $count->inventoryReconciliations ? $count->inventoryReconciliations->count() : 0 }} items</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        Generated on {{ now()->format('d M Y, h:i A') }} &bull; eReckon ERP Confidential Report
    </div>
</body>
</html>
