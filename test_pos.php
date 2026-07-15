<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ledgers = \App\Models\StockLedger::where('transaction_date', now()->toDateString())->get();
foreach ($ledgers as $l) {
    echo "ID: {$l->id}, Prod: {$l->product_id}, In: {$l->inward_qty}, Out: {$l->outward_qty}, Created: {$l->created_at}, Updated: {$l->updated_at}\n";
}
