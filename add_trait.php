<?php

$models = [
    'JournalEntry', 'Payment', 'Receipt', 'ContraEntry', 'DebitNote', 'CreditNote',
    'Grn', 'StockTransfer', 'WarehouseTransfer', 'StockAdjustment', 'InventoryCount',
    'PurchaseOrder', 'PurchaseRequisition', 'VendorPayment', 'PurchaseReturn',
    'PosSale', 'PosReturn', 'Quotation', 'TaxInvoice', 'EInvoice', 'EWayBill',
    'PayrollProcessing', 'AssetDepreciation', 'AssetDisposal', 'AssetTransfer', 'AssetMaintenance',
    'GstReturn'
];

$model_dir = "app/Models";

foreach ($models as $model_name) {
    $file_path = $model_dir . '/' . $model_name . '.php';
    if (file_exists($file_path)) {
        $content = file_get_contents($file_path);

        if (strpos($content, 'use App\Traits\BelongsToFinancialYear;') === false) {
            $content = str_replace("namespace App\Models;", "namespace App\Models;\n\nuse App\Traits\BelongsToFinancialYear;", $content);
            
            $content = preg_replace('/(class\s+' . $model_name . '\s+extends\s+[^{]+\{)/', "$1\n    use BelongsToFinancialYear;", $content);
            
            file_put_contents($file_path, $content);
            echo "Updated {$model_name}.php\n";
        }
    } else {
        echo "Model {$model_name}.php not found\n";
    }
}

