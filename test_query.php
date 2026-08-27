<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sub = \Illuminate\Support\Facades\DB::table(\Illuminate\Support\Facades\DB::raw('DUAL'))
    ->selectRaw("1 as id, 'Revenue' as kpi_name, 'Jan-Feb' as period, 1500.50 as calculated_value");

$results = \App\Models\User::query()->fromSub($sub, 'kpi_data')->get();

echo "Count: " . count($results) . "\n";
echo "KPI Name: " . $results[0]->kpi_name . "\n";
echo "Value: " . $results[0]->calculated_value . "\n";
