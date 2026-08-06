<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$category = App\Models\VendorCategory::create(['name' => 'Test Category']);
echo "Category code: " . $category->code . "\n";
