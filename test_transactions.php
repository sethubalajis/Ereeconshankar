<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$transactions = \App\Models\StockTransaction::orderBy('id', 'desc')->take(5)->get();
echo "Recent Transactions:\n";
foreach($transactions as $t) {
    echo "ID: {$t->id}, Ref: {$t->reference}, Type: {$t->reference_type}, Qty: {$t->quantity}\n";
}
