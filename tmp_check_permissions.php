<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::find(2);
echo 'User: ' . $user->name . PHP_EOL;
echo 'Roles: ';
foreach ($user->roles as $role) {
    echo $role->name . ' ';
}
echo PHP_EOL;
$roleIds = $user->roles()->pluck('roles.id')->all();
echo 'Role IDs: ' . json_encode($roleIds) . PHP_EOL;
$perms = DB::table('role_permissions')->whereIn('role_id', $roleIds)->get(['role_id', 'permission_id', 'branch_id']);
echo 'Role permissions count: ' . $perms->count() . PHP_EOL;
foreach ($perms as $p) {
    echo $p->role_id . '|' . $p->permission_id . '|' . $p->branch_id . PHP_EOL;
}
