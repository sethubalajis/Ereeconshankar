<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .meta-info {
            font-size: 11px;
            color: #666;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    
    <div class="meta-info">
        @if(!empty($reportType))
            <strong>Type:</strong> {{ $reportType }}<br>
        @endif
        @if(!empty($fromDate) && !empty($toDate))
            <strong>Period:</strong> {{ \Carbon\Carbon::parse($fromDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($toDate)->format('d/m/Y') }}<br>
        @endif
        <strong>Generated At:</strong> {{ now()->format('d/m/Y H:i:s') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>S.No</th>
                @foreach($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($records as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    @foreach($row as $value)
                        <td>{{ $value }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headers) + 1 }}" style="text-align:center;">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
