<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Branch Performance Report</title>
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
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .company {
            font-size: 20px;
            font-weight: bold;
            color: #1e40af;
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 13px;
            color: #475569;
            margin-top: 3px;
            font-weight: 600;
        }
        .summary-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 10px 14px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 4px 8px;
            font-size: 11px;
        }
        .stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 8px;
            text-align: center;
        }
        .stat-title {
            font-size: 9px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
        }
        .stat-val {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
            margin-top: 2px;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
            margin-top: 18px;
            margin-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 15px;
        }
        .data-table th {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            color: #1e293b;
        }
        .data-table td {
            border: 1px solid #e2e8f0;
            padding: 6px 8px;
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
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            background: #e2e8f0;
            color: #334155;
        }
        .badge-success {
            background: #dcfce7;
            color: #166534;
        }
        .badge-info {
            background: #dbeafe;
            color: #1e40af;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company">eReckon ERP System</div>
        <div class="subtitle">Branch Performance Report</div>
    </div>

    <div class="summary-box">
        <table class="summary-table">
            <tr>
                <td><strong>Branch:</strong> {{ $record->branch->name ?? 'N/A' }}</td>
                <td>
                    @if($record->from_date && $record->to_date)
                        <strong>Period:</strong> {{ \Carbon\Carbon::parse($record->from_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($record->to_date)->format('d M Y') }}
                    @elseif($record->from_date)
                        <strong>From Date:</strong> {{ \Carbon\Carbon::parse($record->from_date)->format('d M Y') }}
                    @elseif($record->report_month)
                        <strong>Report Date / Month:</strong> {{ \Carbon\Carbon::parse($record->report_month)->format('d M Y') }}
                    @endif
                </td>
                <td class="text-right">
                    <strong>Performance Score:</strong> {{ number_format((float)$record->score, 2) }} / 100
                </td>
            </tr>
        </table>

        @php
            $totalNet = $sales->sum('net_amount');
            $totalGross = $sales->sum('total_amount');
            $totalDiscount = $sales->sum('discount_amount');
            $completedCount = $sales->where('status', 'completed')->count();
        @endphp

        <table style="width: 100%; margin-top: 10px; border-collapse: separate; border-spacing: 6px;">
            <tr>
                <td style="width: 25%;">
                    <div class="stat-card" style="border-left: 3px solid #2563eb;">
                        <div class="stat-title">Total Net Sales</div>
                        <div class="stat-val">₹{{ number_format($totalNet, 2) }}</div>
                    </div>
                </td>
                <td style="width: 25%;">
                    <div class="stat-card" style="border-left: 3px solid #16a34a;">
                        <div class="stat-title">Total Invoices</div>
                        <div class="stat-val">{{ $sales->count() }}</div>
                    </div>
                </td>
                <td style="width: 25%;">
                    <div class="stat-card" style="border-left: 3px solid #0284c7;">
                        <div class="stat-title">Completed Orders</div>
                        <div class="stat-val">{{ $completedCount }}</div>
                    </div>
                </td>
                <td style="width: 25%;">
                    <div class="stat-card" style="border-left: 3px solid #d97706;">
                        <div class="stat-title">Overall Score</div>
                        <div class="stat-val">{{ number_format((float)$record->score, 2) }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">Branch Sales & Performance Breakdown</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th>Date</th>
                <th>Invoice Number</th>
                <th class="text-right">Gross Amount</th>
                <th class="text-right">Discount</th>
                <th class="text-right">Net Amount</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $index => $sale)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $sale->sale_date ? \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') : 'N/A' }}</td>
                    <td><strong>{{ $sale->invoice_number }}</strong></td>
                    <td class="text-right">₹{{ number_format($sale->total_amount, 2) }}</td>
                    <td class="text-right">₹{{ number_format($sale->discount_amount, 2) }}</td>
                    <td class="text-right font-bold">₹{{ number_format($sale->net_amount, 2) }}</td>
                    <td class="text-center">
                        <span class="badge {{ strtolower($sale->status) == 'completed' ? 'badge-success' : 'badge-info' }}">
                            {{ ucfirst($sale->status) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #64748b; padding: 15px;">
                        No sales transactions recorded for this branch in the selected date range.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($sales->isNotEmpty())
            <tfoot>
                <tr style="background: #f1f5f9;" class="font-bold">
                    <td colspan="3" class="text-right">Total Summary:</td>
                    <td class="text-right">₹{{ number_format($totalGross, 2) }}</td>
                    <td class="text-right">₹{{ number_format($totalDiscount, 2) }}</td>
                    <td class="text-right">₹{{ number_format($totalNet, 2) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        @endif
    </table>

    <div class="footer">
        Generated on {{ now()->format('d M Y, h:i A') }} &bull; eReckon ERP Branch Performance Report
    </div>
</body>
</html>
