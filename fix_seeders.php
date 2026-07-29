<?php
$files = glob('database/seeders/*.php');
$count = 0;
foreach ($files as $file) {
    $content = file_get_contents($file);
    $new = preg_replace("/DB::statement\(['\"]SET FOREIGN_KEY_CHECKS\s*=\s*0;?['\"]\);/i", "\\Schema::disableForeignKeyConstraints();", $content);
    $new = preg_replace("/DB::statement\(['\"]SET FOREIGN_KEY_CHECKS\s*=\s*1;?['\"]\);/i", "\\Schema::enableForeignKeyConstraints();", $new);
    if ($new !== $content) {
        file_put_contents($file, $new);
        $count++;
    }
}
echo "Updated $count seeders\n";
