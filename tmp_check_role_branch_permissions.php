<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$role = App\Models\Role::find(2);
echo 'Role: ' . $role->name . PHP_EOL;
$branchId = 3;
$rows = App\Models\RolePermission::where('role_id', 2)->where('branch_id', $branchId)->get();
echo 'Rows for branch ' . $branchId . ': ' . $rows->count() . PHP_EOL;
foreach ($rows as $row) {
    echo $row->permission_id . PHP_EOL;
}
