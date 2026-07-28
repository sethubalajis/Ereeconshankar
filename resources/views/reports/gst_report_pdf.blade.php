<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>GST Report Detailed Breakdown</title>
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
            padding: 12px 14px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 4px 0;
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
            padding: 7px 8px;
            text-align: left;
            font-size: 9.5px;
            text-transform: uppercase;
            color: #1e293b;
        }
        .data-table td {
            border: 1px solid #e2e8f0;
            padding: 6px 8px;
            font-size: 10px;
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
        .badge-sales {
            background: #dbeafe;
            color: #1e40af;
        }
        .badge-purchase {
            background: #fef3c7;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company">eReckon ERP System</div>
        <div class="subtitle">GST Report & Tax Return Breakdown ({{ $record->report_type }})</div>
    </div>

    <div class="summary-box">
        <table class="summary-table">
            <tr>
                <td><strong>Report Type:</strong> {{ $record->report_type }}</td>
                <td>
                    @if($record->period_start && $record->period_end)
                        <strong>Period:</strong> {{ \Carbon\Carbon::parse($record->period_start)->format('d M Y') }} - {{ \Carbon\Carbon::parse($record->period_end)->format('d M Y') }}
                    @elseif($record->period_start)
                        <strong>From Date:</strong> {{ \Carbon\Carbon::parse($record->period_start)->format('d M Y') }}
                    @endif
                </td>
                <td class="text-right"><strong>Total Taxable Value:</strong> ₹{{ number_format($summary['taxable_amount'], 2) }}</td>
            </tr>
            <tr>
                <td><strong>Total Invoices:</strong> {{ $summary['invoice_count'] }}</td>
                <td><strong>CGST / SGST:</strong> ₹{{ number_format($summary['cgst'], 2) }} / ₹{{ number_format($summary['sgst'], 2) }}</td>
                <td class="text-right"><strong>Total Tax Amount:</strong> ₹{{ number_format($summary['tax_amount'], 2) }}</td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td class="text-right font-bold" style="color: #047857;"><strong>Gross Amount:</strong> ₹{{ number_format($summary['total_amount'], 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">Detailed Tax Breakdown & Invoice Listing</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">#</th>
                <th>Invoice / Doc No</th>
                <th style="width: 65px;">Date</th>
                <th style="width: 55px;" class="text-center">Type</th>
                <th class="text-right">Taxable Value</th>
                <th class="text-right">CGST</th>
                <th class="text-right">SGST</th>
                <th class="text-right">IGST</th>
                <th class="text-right">Tax Amount</th>
                <th class="text-right">Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $item['doc_no'] }}</strong></td>
                    <td>{{ \Carbon\Carbon::parse($item['date'])->format('d/m/Y') }}</td>
                    <td class="text-center">
                        <span class="badge {{ $item['type'] == 'Sales' ? 'badge-sales' : 'badge-purchase' }}">
                            {{ $item['type'] }}
                        </span>
                    </td>
                    <td class="text-right">₹{{ number_format($item['taxable'], 2) }}</td>
                    <td class="text-right">₹{{ number_format($item['cgst'], 2) }}</td>
                    <td class="text-right">₹{{ number_format($item['sgst'], 2) }}</td>
                    <td class="text-right">₹{{ number_format($item['igst'], 2) }}</td>
                    <td class="text-right font-bold">₹{{ number_format($item['tax_amount'], 2) }}</td>
                    <td class="text-right font-bold">₹{{ number_format($item['total_amount'], 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center; color: #64748b; padding: 15px;">
                        No GST tax records found for the selected report type and date period.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if(count($items) > 0)
        <tfoot>
            <tr style="background: #f1f5f9;" class="font-bold">
                <td colspan="4" class="text-right">Total Summary:</td>
                <td class="text-right">₹{{ number_format($summary['taxable_amount'], 2) }}</td>
                <td class="text-right">₹{{ number_format($summary['cgst'], 2) }}</td>
                <td class="text-right">₹{{ number_format($summary['sgst'], 2) }}</td>
                <td class="text-right">₹{{ number_format($summary['igst'], 2) }}</td>
                <td class="text-right">₹{{ number_format($summary['tax_amount'], 2) }}</td>
                <td class="text-right">₹{{ number_format($summary['total_amount'], 2) }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        Generated on {{ now()->format('d M Y, h:i A') }} &bull; eReckon ERP GST Confidential Report
    </div>
</body>
</html>
