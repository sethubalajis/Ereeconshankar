<!DOCTYPE html>
<html>
<head>
    <title>Your Invoice</title>
</head>
<body>
    <h2>Hello {{ $posSale->customer->name ?? 'Customer' }},</h2>
    
    <p>Thank you for your recent purchase at <strong>{{ $posSale->branch->name ?? config('app.name') }}</strong>.</p>
    
    <p>Please find your invoice <strong>#{{ $posSale->invoice_number }}</strong> attached to this email.</p>

    <p><strong>Total Amount:</strong> ₹ {{ number_format($posSale->net_amount, 2) }}</p>

    <p>We hope to see you again soon!</p>
    
    <br>
    <p>Best regards,<br>
    {{ $posSale->branch->company->name ?? config('app.name') }}</p>
</body>
</html>
