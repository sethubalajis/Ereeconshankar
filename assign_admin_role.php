<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Spatie\Permission\Models\Role;
use App\Models\User;

$role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
$user = User::where('email', 'admin@example.com')->first();

if ($user) {
    $user->assignRole($role);
    echo "Super Admin role successfully assigned to admin@example.com\n";
} else {
    echo "User admin@example.com not found\n";
}
