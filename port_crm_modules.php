<?php
$modules = ['LoyaltyCards', 'LoyaltyPoints', 'CustomerWallets', 'CustomerMemberships'];
$baseSourceDir = __DIR__ . '/app/Filament/Resources';
$baseTargetDir = __DIR__ . '/app/Filament/Branch/Resources';

function copyDirectory($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                copyDirectory($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

function processDirectory($dir, $module) {
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($files as $file) {
        if ($file->isDir()) continue;
        if ($file->getExtension() === 'php') {
            $content = file_get_contents($file->getRealPath());
            
            // Update namespaces and uses
            $content = str_replace(
                "App\\Filament\\Resources\\$module",
                "App\\Filament\\Branch\\Resources\\$module",
                $content
            );

            // If this is the main Resource file
            if (str_ends_with($file->getFilename(), 'Resource.php')) {
                
                // Replace Resource extension with BaseBranchResource
                $content = str_replace('use Filament\Resources\Resource;', "use App\Filament\Branch\Resources\BaseBranchResource;\nuse Filament\Resources\Resource;", $content);
                $content = str_replace('extends Resource', 'extends BaseBranchResource', $content);

                // Ensure navigation group is set to '12. CRM & Loyalty'
                if (!preg_match('/\$navigationGroup/', $content)) {
                    $content = preg_replace(
                        '/(class\s+[A-Za-z0-9_]+\s+extends\s+BaseBranchResource\s*\{)/',
                        "$1\n    protected static \\UnitEnum|string|null \$navigationGroup = '12. CRM & Loyalty';",
                        $content
                    );
                } else {
                    $content = preg_replace(
                        '/protected\s+static\s+\??string\s+\$navigationGroup\s*=\s*[\'"].*?[\'"];/',
                        "protected static \\UnitEnum|string|null \$navigationGroup = '12. CRM & Loyalty';",
                        $content
                    );
                    $content = preg_replace(
                        '/protected\s+static\s+\\\\UnitEnum\|string\|null\s+\$navigationGroup\s*=\s*[\'"].*?[\'"];/',
                        "protected static \\UnitEnum|string|null \$navigationGroup = '12. CRM & Loyalty';",
                        $content
                    );
                }
            }

            file_put_contents($file->getRealPath(), $content);
        }
    }
}

foreach ($modules as $module) {
    $src = $baseSourceDir . '/' . $module;
    $dst = $baseTargetDir . '/' . $module;
    
    if (is_dir($src)) {
        echo "Copying $module...\n";
        copyDirectory($src, $dst);
        echo "Processing $module...\n";
        processDirectory($dst, $module);
    } else {
        echo "Source directory not found for $module: $src\n";
    }
}
echo "Done.\n";
