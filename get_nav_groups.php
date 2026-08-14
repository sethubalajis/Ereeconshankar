<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$groups = [];
foreach (glob('app/Filament/Branch/Resources/*/*.php') as $file) {
    $content = file_get_contents($file);
    if (preg_match('/\$navigationGroup\s*=\s*[\'"]([^\'"]+)[\'"]/', $content, $matches)) {
        $groups[] = $matches[1];
    }
}
$groups = array_unique($groups);
natsort($groups);
echo json_encode(array_values($groups), JSON_PRETTY_PRINT);
