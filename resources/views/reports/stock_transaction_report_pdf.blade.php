<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stock Transaction Report</title>
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
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-info { background: #e0f2fe; color: #075985; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company">eReckon ERP System</div>
        <div class="subtitle">Stock Transaction Report</div>
    </div>

    <div class="summary-box">
        <table class="summary-table">
            <tr>
                <td><strong>Branch:</strong> {{ $record->branch->name ?? 'All Branches' }}</td>
                <td>
                    @if($record->from_date && $record->to_date)
                        <strong>Period:</strong> {{ \Carbon\Carbon::parse($record->from_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($record->to_date)->format('d M Y') }}
                    @elseif($record->from_date)
                        <strong>From:</strong> {{ \Carbon\Carbon::parse($record->from_date)->format('d M Y') }}
                    @elseif($record->to_date)
                        <strong>To:</strong> {{ \Carbon\Carbon::parse($record->to_date)->format('d M Y') }}
                    @endif
                </td>
                <td class="text-right"><strong>Total Transactions:</strong> {{ count($transactions) }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">Transaction History</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">#</th>
                <th>Transaction No / Ref</th>
                <th>Date</th>
                <th>Branch</th>
                <th>Product</th>
                <th class="text-center">Type</th>
                <th class="text-right">Qty In</th>
                <th class="text-right">Qty Out</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalIn = 0;
                $totalOut = 0;
            @endphp
            @forelse($transactions as $index => $txn)
                @php
                    $qty = (float) $txn->quantity;
                    if ($txn->transaction_type === 'in') $totalIn += $qty;
                    if ($txn->transaction_type === 'out') $totalOut += $qty;
                    
                    $badgeClass = match($txn->transaction_type) {
                        'in' => 'badge-success',
                        'out' => 'badge-danger',
                        'adjustment' => 'badge-warning',
                        'transfer' => 'badge-info',
                        default => ''
                    };
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $txn->reference ?? '-' }}</strong></td>
                    <td>{{ \Carbon\Carbon::parse($txn->transaction_date)->format('Y-m-d') }}</td>
                    <td>{{ $txn->branch->name ?? 'N/A' }}</td>
                    <td>{{ $txn->product->name ?? 'N/A' }}</td>
                    <td class="text-center">
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($txn->transaction_type) }}</span>
                    </td>
                    <td class="text-right">{{ $txn->transaction_type === 'in' ? number_format($qty, 2) : '-' }}</td>
                    <td class="text-right">{{ $txn->transaction_type === 'out' ? number_format($qty, 2) : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="color: #64748b; padding: 15px;">
                        No stock transactions found for the selected criteria.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if(count($transactions) > 0)
        <tfoot>
            <tr style="background: #f1f5f9;" class="font-bold">
                <td colspan="6" class="text-right">Total:</td>
                <td class="text-right">{{ number_format($totalIn, 2) }}</td>
                <td class="text-right">{{ number_format($totalOut, 2) }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        Generated on {{ now()->format('d M Y, h:i A') }} &bull; eReckon ERP Confidential Report
    </div>
</body>
</html>
