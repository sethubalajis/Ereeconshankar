<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::find(2);
$user->syncPermissions([1]);
echo 'Stored direct permissions via syncPermissions' . PHP_EOL;
$user->load('permissions');
echo json_encode($user->getDirectPermissions()->pluck('name')->all()) . PHP_EOL;
