<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$item = \App\Models\PosSaleItem::find(1);
if(!$item) { echo "No item 1\n"; exit; }
$sale = $item->posSale;
echo 'Item Product ID: ' . $item->product_id . ' Variant: ' . $item->product_variant_id . ' Branch: ' . $sale->branch_id . "\n";
$stock = \App\Models\Stock::where('product_id', $item->product_id)->get();
echo 'Stocks: ' . json_encode($stock) . "\n";
