<?php

$sourceDir = __DIR__ . '/app/Filament/Resources/InventoryReports';
$targetDir = __DIR__ . '/app/Filament/Resources/StockTransactionReports';

function copyAndReplace($src, $dst) {
    if (!is_dir($dst)) {
        mkdir($dst, 0777, true);
    }

    $dir = opendir($src);
    while (($file = readdir($dir)) !== false) {
        if ($file != '.' && $file != '..') {
            $srcFile = $src . '/' . $file;
            $dstFile = $dst . '/' . str_replace('InventoryReport', 'StockTransactionReport', $file);

            if (is_dir($srcFile)) {
                copyAndReplace($srcFile, $dstFile);
            } else {
                $content = file_get_contents($srcFile);
                $content = str_replace('InventoryReport', 'StockTransactionReport', $content);
                $content = str_replace('Inventory Reports', 'Stock Transaction Reports', $content);
                $content = str_replace('Inventory Report', 'Stock Transaction Report', $content);
                $content = str_replace('inventory-reports', 'stock-transaction-reports', $content);
                $content = str_replace('inventory_reports', 'stock_transaction_reports', $content);
                $content = str_replace('InventoryDataReportTable', 'StockTransactionDataReportTable', $content);
                $content = str_replace('inventory-data-report-table', 'stock-transaction-data-report-table', $content);
                
                file_put_contents($dstFile, $content);
            }
        }
    }
    closedir($dir);
}

copyAndReplace($sourceDir, $targetDir);
echo "Scaffolding completed successfully.\n";
