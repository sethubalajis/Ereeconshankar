<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$grn = App\Models\Grn::first();
if ($grn) {
    $grn->received_items = [
        ["product_id" => 1, "quantity" => 10, "unit_price" => 100, "total_price" => 1000]
    ];
    $grn->save();
    echo "Updated GRN 1\n";
} else {
    echo "No GRN found\n";
}

