<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$product = \App\Models\Product::where('product_type', 'Asset')->first();
if (!$product) {
    $product = \App\Models\Product::create([
        'name' => 'Test Asset',
        'code' => 'TEST-ASSET',
        'product_type' => 'Asset',
        'base_price' => 100,
        'is_active' => true,
    ]);
}

$po = \App\Models\PurchaseOrder::first();
$vendor = \App\Models\Vendor::first();
$branch = \App\Models\Branch::first();

try {
    $grn = \App\Models\Grn::create([
        'purchase_order_id' => $po->id ?? 1,
        'vendor_id' => $vendor->id ?? 1,
        'branch_id' => $branch->id ?? 1,
        'receive_date' => now(),
        'status' => 'received',
        'received_items' => [
            [
                'product_id' => $product->id,
                'quantity' => 2,
                'unit_price' => 100,
            ]
        ]
    ]);
    echo "GRN Created: " . $grn->id . "\n";
    echo "Assets Count: " . \App\Models\Asset::where('name', $product->name)->count() . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
