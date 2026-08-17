<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quotation Receipt - {{ $record->quotation_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 10px;
            color: #1e293b;
            margin: 0;
            padding: 5px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 2px dashed #94a3b8;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .company {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 11px;
            color: #475569;
            margin-top: 3px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 2px 0;
            font-size: 9px;
        }
        .text-right {
            text-align: right;
        }
        .font-bold {
            font-weight: bold;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background: #f1f5f9;
            border-bottom: 1px solid #cbd5e1;
            padding: 4px 2px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
        }
        .items-table td {
            border-bottom: 1px dashed #e2e8f0;
            padding: 4px 2px;
            font-size: 10px;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .totals-table td {
            padding: 4px 2px;
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
        @php
            $company = $record->branch->company ?? null;
            $branch = $record->branch ?? null;
        @endphp
        
        @if($company && $company->logo)
            <div style="margin-bottom: 10px;">
                @php
                    $logoPath = public_path('storage/' . $company->logo);
                    if (!file_exists($logoPath)) {
                        $logoPath = storage_path('app/public/' . $company->logo);
                    }
                @endphp
                @if(file_exists($logoPath))
                    <img src="{{ $logoPath }}" alt="{{ $company->name }} Logo" style="max-height: 60px; max-width: 100%; object-fit: contain;">
                @endif
            </div>
        @endif

        <div class="company">{{ $company->name ?? ($branch->name ?? 'eReckon POS') }}</div>
        
        @if($company)
            @if($company->address)
                <div style="font-size: 11px; color: #475569; margin-top: 3px;">
                    {{ $company->address }}
                    @if($company->city)
                        , {{ $company->city->name ?? '' }}
                    @endif
                    @if($company->pin_code)
                        - {{ $company->pin_code }}
                    @endif
                </div>
            @endif
            @if($company->phone || $company->email)
                <div style="font-size: 11px; color: #475569;">
                    @if($company->phone) Ph: {{ $company->phone }} @endif
                    @if($company->phone && $company->email) | @endif
                    @if($company->email) Email: {{ $company->email }} @endif
                </div>
            @endif
        @elseif($branch)
            {{-- Fallback to branch details if company doesn't exist --}}
            @if($branch->address)
                <div style="font-size: 11px; color: #475569; margin-top: 3px;">{{ $branch->address }}</div>
            @endif
            @if($branch->phone)
                <div style="font-size: 11px; color: #475569;">Ph: {{ $branch->phone }}</div>
            @endif
        @endif

        <div class="subtitle" style="margin-top: 8px; font-weight: bold;">Quotation Receipt</div>
    </div>

    <table class="info-table">
        <tr>
            <td><strong>Quotation No:</strong> {{ $record->quotation_number ?? 'N/A' }}</td>
            <td class="text-right"><strong>Date:</strong> {{ $record->quote_date ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Vendor:</strong> {{ $record->vendor->name ?? 'N/A' }}</td>
            <td class="text-right"><strong>Status:</strong> {{ ucfirst($record->status ?? 'N/A') }}</td>
        </tr>
        <tr>
            <td><strong>RFQ No:</strong> {{ $record->rfq->rfq_number ?? 'N/A' }}</td>
            <td class="text-right"><strong>Validity:</strong> {{ $record->validity_date ?? 'N/A' }}</td>
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
            @if($record->items && $record->items->count() > 0)
                @foreach($record->items as $item)
                <tr>
                    <td>
                        {{ $item->product->name ?? 'Unknown' }}<br>
                        <small style="color: #64748b;">{{ $item->productVariant->sku ?? '' }}</small>
                    </td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">Rs.{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">Rs.{{ number_format($item->total_price, 2) }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="text-center" style="text-align: center; padding: 10px;">No items found</td>
                </tr>
            @endif
        </tbody>
    </table>

    <table class="totals-table">
        <tr class="font-bold">
            <td>Total Amount:</td>
            <td class="text-right">Rs.{{ number_format($record->total_amount ?? 0, 2) }}</td>
        </tr>
    </table>

    <div class="footer">
        Thank you for your business!<br>
        Generated on {{ now()->timezone('Asia/Kolkata')->format('d M Y h:i A') }}
    </div>
</body>
</html>
