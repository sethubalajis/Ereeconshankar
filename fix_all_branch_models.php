<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

$models = glob(__DIR__ . '/app/Models/*.php');
$appliedBranch = [];
$appliedTransfer = [];

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
            if ($hasBranchId && strpos($content, 'use HasBranch;') === false) {
                if(strpos($content, 'use App\Traits\HasBranch;') === false) {
                    $content = preg_replace('/namespace App\\\\Models;/', "namespace App\Models;\n\nuse App\Traits\HasBranch;", $content);
                }
                $content = preg_replace('/class ' . $modelName . '[^{]*\{/', "$0\n    use HasBranch;\n", $content);
                file_put_contents($file, $content);
                $appliedBranch[] = $modelName;
            }

            $hasFromBranchId = Schema::hasColumn($table, 'from_branch_id');
            if ($hasFromBranchId && strpos($content, 'use HasTransferBranch;') === false) {
                if(strpos($content, 'use App\Traits\HasTransferBranch;') === false) {
                    $content = preg_replace('/namespace App\\\\Models;/', "namespace App\Models;\n\nuse App\Traits\HasTransferBranch;", $content);
                }
                $content = preg_replace('/class ' . $modelName . '[^{]*\{/', "$0\n    use HasTransferBranch;\n", $content);
                file_put_contents($file, $content);
                $appliedTransfer[] = $modelName;
            }

        } catch (\Exception $e) {
            // Ignore abstract classes or interfaces
        }
    }
}

echo "Applied HasBranch to: " . implode(', ', $appliedBranch) . "\n";
echo "Applied HasTransferBranch to: " . implode(', ', $appliedTransfer) . "\n";
