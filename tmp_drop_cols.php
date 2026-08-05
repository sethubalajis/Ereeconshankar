<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

Illuminate\Support\Facades\Schema::table('stock_reservations', function ($table) {
    $columns = ['reservation_number', 'company_id', 'warehouse_id', 'reservation_type', 'reference_document', 'reservation_date', 'expiry_date', 'created_by'];
    foreach ($columns as $col) {
        if (Illuminate\Support\Facades\Schema::hasColumn('stock_reservations', $col)) {
            $table->dropColumn($col);
        }
    }
});
echo "Columns dropped.\n";

