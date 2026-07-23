<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::find(2);
$user->load('permissions');
$direct = $user->getDirectPermissions()->pluck('name')->all();
echo json_encode($direct) . PHP_EOL;
