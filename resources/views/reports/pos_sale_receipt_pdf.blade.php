<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>POS Sale Receipt - {{ $record->invoice_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 12px;
            color: #1e293b;
            margin: 0;
            padding: 20px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 2px dashed #94a3b8;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .company {
            font-size: 20px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 13px;
            color: #475569;
            margin-top: 3px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 2px 0;
            font-size: 11px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background: #f1f5f9;
            border-bottom: 1px solid #cbd5e1;
            padding: 8px 4px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }
        .items-table td {
            border-bottom: 1px dashed #e2e8f0;
            padding: 8px 4px;
            font-size: 11px;
        }
        .text-right {
            text-align: right;
        }
        .font-bold {
            font-weight: bold;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 4px;
            font-size: 12px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            border-top: 2px dashed #94a3b8;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company">{{ $record->branch->name ?? 'eReckon POS' }}</div>
        <div class="subtitle">Tax Invoice / Receipt</div>
    </div>

    <table class="info-table">
        <tr>
            <td><strong>Invoice No:</strong> {{ $record->invoice_number }}</td>
            <td class="text-right"><strong>Date:</strong> {{ \Carbon\Carbon::parse($record->sale_date)->format('d M Y h:i A') }}</td>
        </tr>
        <tr>
            <td><strong>Terminal:</strong> {{ $record->posTerminal->terminal_name ?? 'N/A' }}</td>
            <td class="text-right"><strong>Status:</strong> {{ ucfirst($record->status) }}</td>
        </tr>
        <tr>
            <td><strong>Customer:</strong> {{ $record->customer->name ?? 'Walk-in Customer' }}</td>
            <td class="text-right"><strong>Phone:</strong> {{ $record->customer->phone ?? 'N/A' }}</td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($record->posSaleItems as $item)
            <tr>
                <td>
                    {{ $item->product->name ?? 'Unknown' }}<br>
                    <small style="color: #64748b;">{{ $item->productVariant->sku ?? '' }}</small>
                </td>
                <td class="text-right">{{ $item->quantity }}</td>
                <td class="text-right">₹{{ number_format($item->unit_price, 2) }}</td>
                <td class="text-right">₹{{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td colspan="3" class="text-right">Subtotal:</td>
            <td width="20%" class="text-right">₹{{ number_format($record->total_amount, 2) }}</td>
        </tr>
        @if($record->discount_amount > 0)
        <tr>
            <td colspan="3" class="text-right">Discount:</td>
            <td class="text-right">-₹{{ number_format($record->discount_amount, 2) }}</td>
        </tr>
        @endif
        @if($record->tax_amount > 0)
        <tr>
            <td colspan="3" class="text-right">Tax:</td>
            <td class="text-right">₹{{ number_format($record->tax_amount, 2) }}</td>
        </tr>
        @endif
        <tr class="font-bold" style="font-size: 14px;">
            <td colspan="3" class="text-right">Net Payable:</td>
            <td class="text-right">₹{{ number_format($record->net_amount, 2) }}</td>
        </tr>
    </table>

    <div class="footer">
        Thank you for your business!<br>
        Generated on {{ now()->format('d M Y h:i A') }}
    </div>
</body>
</html>

