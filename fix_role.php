<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$u = App\Models\User::find(1);
if(!$u->hasRole('Super Admin')) {
    $r = App\Models\Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $u->assignRole($r);
    echo "Assigned role\n";
} else {
    echo "Already has role\n";
}
