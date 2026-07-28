<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Report Detailed Breakdown</title>
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
            padding: 3px 0;
            font-size: 11px;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
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
        .badge-completed {
            background: #dcfce7;
            color: #166534;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company">eReckon ERP System</div>
        <div class="subtitle">Detailed Sales Report</div>
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
                <td class="text-right"><strong>Total Sales Amount:</strong> ₹{{ number_format($record->total_sales, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">Invoice Sales Breakdown</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th>Invoice Number</th>
                <th class="text-right">Gross Amount</th>
                <th class="text-right">Discount Total</th>
                <th class="text-right">Net Amount</th>
                <th style="text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalGross = 0; 
                $totalDiscount = 0; 
                $totalNet = 0; 
            @endphp
            @forelse($sales as $index => $sale)
                @php
                    $totalGross += $sale->total_amount;
                    $totalDiscount += $sale->discount_amount;
                    $totalNet += $sale->net_amount;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $sale->invoice_number }}</strong></td>
                    <td class="text-right">₹{{ number_format($sale->total_amount, 2) }}</td>
                    <td class="text-right">₹{{ number_format($sale->discount_amount, 2) }}</td>
                    <td class="text-right font-bold">₹{{ number_format($sale->net_amount, 2) }}</td>
                    <td style="text-align: center;">
                        <span class="badge {{ strtolower($sale->status) == 'completed' ? 'badge-completed' : '' }}">
                            {{ ucfirst($sale->status) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #64748b; padding: 15px;">
                        No POS sales invoices found for the selected branch and report date.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if(count($sales) > 0)
        <tfoot>
            <tr style="background: #f1f5f9;" class="font-bold">
                <td colspan="2" class="text-right">Total Summary:</td>
                <td class="text-right">₹{{ number_format($totalGross, 2) }}</td>
                <td class="text-right">₹{{ number_format($totalDiscount, 2) }}</td>
                <td class="text-right">₹{{ number_format($totalNet, 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        Generated on {{ now()->format('d M Y, h:i A') }} &bull; eReckon ERP Confidential Report
    </div>
</body>
</html>
