<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $branch = \App\Models\Branch::first();
    $cat = \App\Models\ProductCategory::first();
    $prod = \App\Models\Product::where('product_type', 1)->first();

    if (!$prod) {
        $prod = \App\Models\Product::create(['name' => 'Cotton Shirt Test', 'product_type' => 1, 'category_id' => $cat->id]);
    }

    $grn = \App\Models\Grn::create([
        'branch_id' => $branch->id,
        'received_items' => [
            ['product_id' => $prod->id, 'quantity' => 100, 'unit_price' => 500]
        ]
    ]);

    $stock = \App\Models\Stock::where('product_id', $prod->id)->first();
    echo "After GRN: QTY=" . $stock->quantity . ", AVAIL=" . $stock->available_qty . "\n";

    $sale = \App\Models\PosSale::create([
        'branch_id' => $branch->id,
        'status' => 'completed',
        'total_amount' => 1000,
        'net_amount' => 1000,
        'invoice_number' => 'POS-TEST-' . time(),
        'sale_date' => now()
    ]);

    $item = \App\Models\PosSaleItem::create([
        'pos_sale_id' => $sale->id,
        'product_id' => $prod->id,
        'quantity' => 2,
        'unit_price' => 500,
        'total_price' => 1000
    ]);

    $stock->refresh();
    echo "After SALE: QTY=" . $stock->quantity . ", AVAIL=" . $stock->available_qty . "\n";
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
