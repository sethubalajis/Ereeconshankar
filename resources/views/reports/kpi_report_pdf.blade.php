<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>KPI Report Detailed Breakdown</title>
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
    </style>
</head>
<body>
    <div class="header">
        <div class="company">eReckon ERP System</div>
        <div class="subtitle">KPI Key Performance Indicator Report</div>
    </div>

    <div class="summary-box">
        <table class="summary-table">
            <tr>
                <td><strong>KPI Name:</strong> {{ $record->kpi_name ?? 'N/A' }}</td>
                <td>
                    @if($record->from_date && $record->to_date)
                        <strong>Period:</strong> {{ \Carbon\Carbon::parse($record->from_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($record->to_date)->format('d M Y') }}
                    @elseif($record->from_date)
                        <strong>From Date:</strong> {{ \Carbon\Carbon::parse($record->from_date)->format('d M Y') }}
                    @elseif($record->report_date)
                        <strong>Report Date:</strong> {{ \Carbon\Carbon::parse($record->report_date)->format('d M Y') }}
                    @endif
                </td>
                <td class="text-right">
                    <strong>KPI Value:</strong> <span style="font-size: 14px; color: #1e40af; font-weight: bold;">{{ number_format((float)$record->value, 2) }}</span>
                </td>
            </tr>
        </table>

        <table style="width: 100%; margin-top: 10px; border-collapse: separate; border-spacing: 6px;">
            <tr>
                <td style="width: 25%;">
                    <div class="stat-card" style="border-left: 3px solid #2563eb;">
                        <div class="stat-title">KPI Metric Value</div>
                        <div class="stat-val" style="color: #2563eb;">{{ number_format((float)$record->value, 2) }}</div>
                    </div>
                </td>
                <td style="width: 25%;">
                    <div class="stat-card" style="border-left: 3px solid #16a34a;">
                        <div class="stat-title">Active Employees</div>
                        <div class="stat-val">{{ $empCount }}</div>
                    </div>
                </td>
                <td style="width: 25%;">
                    <div class="stat-card" style="border-left: 3px solid #0284c7;">
                        <div class="stat-title">Total Sales Revenue</div>
                        <div class="stat-val">₹{{ number_format($totalSales, 2) }}</div>
                    </div>
                </td>
                <td style="width: 25%;">
                    <div class="stat-card" style="border-left: 3px solid #d97706;">
                        <div class="stat-title">Attendance Logs</div>
                        <div class="stat-val">{{ $totalAttendance }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">KPI Analysis & Underlying Data Metrics</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th>Metric Category</th>
                <th>Period / Reference</th>
                <th class="text-right">Count / Value</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td><strong>Selected KPI Metric</strong> ({{ $record->kpi_name }})</td>
                <td>
                    @if($record->from_date && $record->to_date)
                        {{ \Carbon\Carbon::parse($record->from_date)->format('d M Y') }} to {{ \Carbon\Carbon::parse($record->to_date)->format('d M Y') }}
                    @else
                        {{ $record->report_date ? \Carbon\Carbon::parse($record->report_date)->format('d M Y') : 'N/A' }}
                    @endif
                </td>
                <td class="text-right font-bold" style="color: #1e40af;">{{ number_format((float)$record->value, 2) }}</td>
                <td class="text-center"><span class="badge badge-success">Evaluated</span></td>
            </tr>
            <tr>
                <td>2</td>
                <td>Total Active Workforce (Employees)</td>
                <td>System Record Count</td>
                <td class="text-right font-bold">{{ $empCount }}</td>
                <td class="text-center"><span class="badge badge-success">Active</span></td>
            </tr>
            <tr>
                <td>3</td>
                <td>Total Gross POS Sales Revenue</td>
                <td>Selected Period Total</td>
                <td class="text-right font-bold">₹{{ number_format($totalSales, 2) }}</td>
                <td class="text-center"><span class="badge badge-success">Recorded</span></td>
            </tr>
            <tr>
                <td>4</td>
                <td>Total Attendance Logs</td>
                <td>Selected Period Logs</td>
                <td class="text-right font-bold">{{ $totalAttendance }}</td>
                <td class="text-center"><span class="badge badge-success">Recorded</span></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ now()->format('d M Y, h:i A') }} &bull; eReckon ERP Confidential KPI Report
    </div>
</body>
</html>
