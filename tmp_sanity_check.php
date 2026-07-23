<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::find(2);
$user->syncPermissions(['companies.viewAny']);
echo 'stored' . PHP_EOL;
