<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::find(2);
echo 'User: ' . $user->name . PHP_EOL;
$direct = $user->getDirectPermissions()->pluck('name')->all();
echo 'Direct permissions: ' . json_encode($direct) . PHP_EOL;
$rolePerms = $user->getPermissionsViaRoles()->pluck('name')->all();
echo 'Permissions via roles: ' . json_encode($rolePerms) . PHP_EOL;
