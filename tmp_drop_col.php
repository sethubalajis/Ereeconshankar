<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

Illuminate\Support\Facades\Schema::table('stock_reservations', function ($table) {
    if (Illuminate\Support\Facades\Schema::hasColumn('stock_reservations', 'reservation_number')) {
        $table->dropColumn('reservation_number');
    }
});
echo "Column dropped.\n";

