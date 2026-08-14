<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

$models = glob(__DIR__ . '/app/Models/*.php');
$relationshipBranch = "\n    public function branch()\n    {\n        return \$this->belongsTo(Branch::class, 'branch_id');\n    }\n";

$appliedRel = [];

foreach ($models as $file) {
    $modelName = basename($file, '.php');
    $class = '\\App\\Models\\' . $modelName;

    if (class_exists($class)) {
        try {
            $model = new $class;
            if (!method_exists($model, 'getTable')) continue;
            
            $table = $model->getTable();
            $content = file_get_contents($file);
            
            $hasBranchId = Schema::hasColumn($table, 'branch_id');
            if ($hasBranchId && strpos($content, 'function branch(') === false) {
                $content = preg_replace('/}\s*$/', $relationshipBranch . "}\n", $content);
                file_put_contents($file, $content);
                $appliedRel[] = $modelName;
            }
        } catch (\Exception $e) {
            // Ignore abstract classes or interfaces
        }
    }
}

echo "Applied branch() rel to: " . implode(', ', $appliedRel) . "\n";
