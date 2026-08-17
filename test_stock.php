<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sale = \App\Models\PosSale::where('status', 'completed')->first();
if($sale) {
    echo "Found Sale: {$sale->id}\n";
    foreach($sale->posSaleItems as $item) {
        echo "Processing item {$item->id}\n";
        $item->processStockDeduction();
        echo "Done for item {$item->id}\n";
    }
} else {
    echo "No PosSale found\n";
}
