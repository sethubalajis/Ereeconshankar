<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Report PDF' }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1a202c;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.5;
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #1e40af;
            margin: 0;
            text-transform: uppercase;
        }
        .report-title {
            font-size: 16px;
            font-weight: 600;
            color: #334155;
            margin-top: 5px;
        }
        .meta-text {
            text-align: right;
            font-size: 11px;
            color: #64748b;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .data-table th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-weight: bold;
            text-align: left;
            padding: 8px 12px;
            border: 1px solid #cbd5e1;
            font-size: 11px;
            text-transform: uppercase;
        }
        .data-table td {
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            font-size: 11px;
        }
        .data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .footer {
            margin-top: 40px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td>
                    <div class="company-name">eReckon ERP System</div>
                    <div class="report-title">{{ $title }}</div>
                </td>
                <td class="meta-text">
                    <strong>Generated Date:</strong> {{ now()->format('d M Y, h:i A') }}<br>
                    <strong>Total Records:</strong> {{ count($records) }}
                </td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                @foreach($columns as $key => $label)
                    <th>{{ $label }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($records as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    @foreach($columns as $key => $label)
                        <td>
                            @php
                                $val = data_get($row, $key);
                                if ($val instanceof \DateTimeInterface) {
                                    $val = $val->format('Y-m-d H:i');
                                } elseif (is_array($val)) {
                                    $val = json_encode($val);
                                }
                            @endphp
                            {{ $val ?? '-' }}
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) + 1 }}" style="text-align: center;">No records found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Confidential System Generated Report &bull; eReckon ERP System
    </div>
</body>
</html>
