<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Financial Report Detailed Statement</title>
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
            font-size: 14px;
            color: #3b82f6;
            margin-top: 3px;
            font-weight: 600;
        }
        .summary-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            padding: 12px 14px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 5px 0;
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
        .badge-income { background: #dcfce7; color: #166534; }
        .badge-expense { background: #fee2e2; color: #991b1b; }
        .badge-asset { background: #dbeafe; color: #1e40af; }
        .badge-liability { background: #fef3c7; color: #92400e; }
        .badge-equity { background: #f3e8ff; color: #6b21a8; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company">eReckon ERP Financial Statement</div>
        <div class="subtitle">Financial Report Detailed Breakdown ({{ $reportType }})</div>
    </div>

    <div class="summary-box">
        <table class="summary-table">
            <tr>
                <td><strong>Report Type:</strong> {{ $reportType }}</td>
                <td>
                    @if($fromDate && $toDate)
                        <strong>Period:</strong> {{ \Carbon\Carbon::parse($fromDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($toDate)->format('d M Y') }}
                    @elseif($asOfDate)
                        <strong>As Of Date:</strong> {{ \Carbon\Carbon::parse($asOfDate)->format('d M Y') }}
                    @endif
                </td>
                <td class="text-right">
                    @if($reportType === 'Profit & Loss')
                        <strong>Total Revenue:</strong> ₹{{ number_format($summary['total_revenue'] ?? 0, 2) }}
                    @elseif($reportType === 'Balance Sheet')
                        <strong>Total Assets:</strong> ₹{{ number_format($summary['total_assets'] ?? 0, 2) }}
                    @elseif($reportType === 'Cash Flow')
                        <strong>Total Inflow:</strong> ₹{{ number_format($summary['total_inflow'] ?? 0, 2) }}
                    @else
                        <strong>Total Debits:</strong> ₹{{ number_format($summary['total_debit'] ?? 0, 2) }}
                    @endif
                </td>
            </tr>

            @if($reportType === 'Profit & Loss')
                <tr>
                    <td><strong>Cost of Goods / Purchases:</strong> ₹{{ number_format($summary['cogs_purchases'] ?? 0, 2) }}</td>
                    <td><strong>Operating Expenses:</strong> ₹{{ number_format($summary['operating_expenses'] ?? 0, 2) }}</td>
                    <td class="text-right font-bold" style="color: {{ ($summary['net_profit'] ?? 0) >= 0 ? '#166534' : '#991b1b' }}; font-size: 12px;">
                        <strong>Net {{ ($summary['net_profit'] ?? 0) >= 0 ? 'Profit' : 'Loss' }}:</strong> ₹{{ number_format($summary['net_profit'] ?? 0, 2) }}
                    </td>
                </tr>
            @elseif($reportType === 'Balance Sheet')
                <tr>
                    <td><strong>Total Liabilities:</strong> ₹{{ number_format($summary['total_liabilities'] ?? 0, 2) }}</td>
                    <td><strong>Total Equity:</strong> ₹{{ number_format($summary['total_equity'] ?? 0, 2) }}</td>
                    <td class="text-right font-bold" style="color: #1e40af; font-size: 12px;">
                        <strong>Net Asset Balance:</strong> ₹{{ number_format($summary['total_assets'] ?? 0, 2) }}
                    </td>
                </tr>
            @elseif($reportType === 'Cash Flow')
                <tr>
                    <td><strong>Total Cash Outflow:</strong> ₹{{ number_format($summary['total_outflow'] ?? 0, 2) }}</td>
                    <td></td>
                    <td class="text-right font-bold" style="color: {{ ($summary['net_cash_flow'] ?? 0) >= 0 ? '#166534' : '#991b1b' }}; font-size: 12px;">
                        <strong>Net Cash Flow:</strong> ₹{{ number_format($summary['net_cash_flow'] ?? 0, 2) }}
                    </td>
                </tr>
            @else
                <tr>
                    <td><strong>Total Credits:</strong> ₹{{ number_format($summary['total_credit'] ?? 0, 2) }}</td>
                    <td><strong>Variance Difference:</strong> ₹{{ number_format($summary['difference'] ?? 0, 2) }}</td>
                    <td class="text-right font-bold" style="color: {{ ($summary['is_balanced'] ?? false) ? '#166534' : '#991b1b' }};">
                        <strong>Status:</strong> {{ ($summary['is_balanced'] ?? false) ? 'Balanced Statement' : 'Unbalanced Variance' }}
                    </td>
                </tr>
            @endif
        </table>
    </div>

    <div class="section-title">Itemized Line Items & Account Breakdown</div>
    
    <table class="data-table">
        @if($reportType === 'Trial Balance')
            <thead>
                <tr>
                    <th style="width: 25px;">#</th>
                    <th style="width: 90px;">Account Code</th>
                    <th>Account Name</th>
                    <th style="width: 110px;">Account Type</th>
                    <th class="text-right" style="width: 110px;">Debit Balance (₹)</th>
                    <th class="text-right" style="width: 110px;">Credit Balance (₹)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $item['account_code'] ?? '-' }}</strong></td>
                        <td>{{ $item['account_name'] ?? '-' }}</td>
                        <td><span class="badge">{{ $item['account_type'] ?? 'General' }}</span></td>
                        <td class="text-right">₹{{ number_format($item['debit'] ?? 0, 2) }}</td>
                        <td class="text-right">₹{{ number_format($item['credit'] ?? 0, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center" style="color: #64748b; padding: 15px;">No accounts found for the trial balance period.</td>
                    </tr>
                @endforelse
            </tbody>
            @if(count($items) > 0)
                <tfoot>
                    <tr style="background: #f1f5f9;" class="font-bold">
                        <td colspan="4" class="text-right">Total Statement Balances:</td>
                        <td class="text-right">₹{{ number_format($summary['total_debit'] ?? 0, 2) }}</td>
                        <td class="text-right">₹{{ number_format($summary['total_credit'] ?? 0, 2) }}</td>
                    </tr>
                </tfoot>
            @endif
        @else
            <thead>
                <tr>
                    <th style="width: 25px;">#</th>
                    <th style="width: 120px;">Category</th>
                    <th style="width: 75px;">Date</th>
                    <th style="width: 90px;">Ref / Doc No</th>
                    <th>Description</th>
                    <th style="width: 70px;" class="text-center">Type</th>
                    <th class="text-right" style="width: 100px;">Amount (₹)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $index => $item)
                    @php
                        $t = strtolower($item['type'] ?? '');
                        $badgeClass = 'badge';
                        if (str_contains($t, 'revenue') || str_contains($t, 'income') || str_contains($t, 'inflow')) $badgeClass = 'badge-income';
                        elseif (str_contains($t, 'expense') || str_contains($t, 'cogs') || str_contains($t, 'outflow')) $badgeClass = 'badge-expense';
                        elseif (str_contains($t, 'asset')) $badgeClass = 'badge-asset';
                        elseif (str_contains($t, 'liability')) $badgeClass = 'badge-liability';
                        elseif (str_contains($t, 'equity')) $badgeClass = 'badge-equity';
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $item['category'] ?? '-' }}</strong></td>
                        <td>{{ $item['date'] ? \Carbon\Carbon::parse($item['date'])->format('d/m/Y') : '-' }}</td>
                        <td>{{ $item['doc_no'] ?? '-' }}</td>
                        <td>{{ $item['description'] ?? '-' }}</td>
                        <td class="text-center"><span class="badge {{ $badgeClass }}">{{ $item['type'] ?? '-' }}</span></td>
                        <td class="text-right font-bold">₹{{ number_format($item['amount'] ?? 0, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center" style="color: #64748b; padding: 15px;">No financial transactions recorded for this report selection.</td>
                    </tr>
                @endforelse
            </tbody>
        @endif
    </table>

    <div class="footer">
        Generated on {{ now()->format('d M Y, h:i A') }} &bull; eReckon ERP Financial Confidential Statement
    </div>
</body>
</html>
