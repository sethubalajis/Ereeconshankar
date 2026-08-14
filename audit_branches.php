<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

$modelsToCheck = [
    'PurchaseRequisition', 'Rfq', 'Quotation', 'PurchaseOrder', 'PurchaseOrderItem', 'Grn', 'PurchaseReturn',
    'Stock', 'StockTransaction', 'StockAdjustment', 'StockLedger', 'StockValuation', 'StockReservation', 'InventoryCount', 'InventoryReconciliation',
    'Warehouse', 'WarehouseZone', 'Rack', 'Bin', 'BinStock', 'WarehouseTransfer', 'PickingList', 'PackingList',
    'TransferRequest', 'StockTransfer', 'TransferItem', 'TransferReceipt', 'TransitInventory', 'TransferApproval',
    'PosTerminal', 'PosCounter', 'CashRegister', 'CashOpening', 'CashClosing', 'PosSale', 'PosSaleItem', 'PosReturn',
    'Branch', 'BranchSetting', 'BranchInventory', 'BranchUser', 'BranchExpense', 'BranchTarget',
    'Journal', 'JournalEntry', 'Ledger', 'BankAccount', 'CashBook', 'Payment', 'Receipt', 'ContraEntry', 'CreditNote', 'DebitNote', 'TrialBalance',
    'GstReturn', 'TaxInvoice', 'EInvoice', 'EWayBill', 'TcsRecord',
    'Employee', 'EmployeeDocument', 'Attendance', 'Shift', 'EmployeeLeave', 'EmployeeTransfer', 'EmployeeExit',
    'EmployeeSalaryAssignment', 'PayrollCycle', 'Bonus', 'Incentive', 'Reimbursement', 'Deduction', 'PfContribution', 'EsiContribution', 'ProfessionalTax', 'TdsRecord', 'PayrollProcessing', 'Payslip',
    'Asset', 'AssetAllocation', 'AssetTransfer', 'AssetMaintenance', 'AssetDepreciation', 'AssetDisposal',
    'ServiceRequest', 'ComplaintTicket', 'WarrantyClaim', 'ServiceAssignment', 'TechnicianVisit', 'ServiceReport', 'CustomerFeedback',
    'LoyaltyCard', 'LoyaltyPoint', 'CustomerWallet', 'CustomerMembership',
    'Coupon', 'PromoCode', 'BuyXGetYOffer', 'FestivalOffer', 'ComboOffer', 'PriceList', 'PromotionSetting', 'PromotionAudit'
];

$report = [];

foreach ($modelsToCheck as $modelName) {
    $modelClass = "\\App\\Models\\$modelName";
    $resourceClass = "\\App\\Filament\\Resources\\" . Str::plural($modelName) . "\\" . $modelName . "Resource";
    
    $modelExists = class_exists($modelClass);
    $resourceExists = class_exists($resourceClass);
    
    $hasTable = false;
    $hasBranchId = false;
    $hasBranchRelation = false;
    
    if ($modelExists) {
        $instance = new $modelClass();
        $table = $instance->getTable();
        $hasTable = Schema::hasTable($table);
        if ($hasTable) {
            $hasBranchId = Schema::hasColumn($table, 'branch_id');
            // Check special cases
            if ($modelName === 'StockTransfer') {
                $hasBranchId = Schema::hasColumn($table, 'from_branch_id') && Schema::hasColumn($table, 'to_branch_id');
            }
        }
        $hasBranchRelation = method_exists($instance, 'branch');
        if ($modelName === 'StockTransfer') {
            $hasBranchRelation = method_exists($instance, 'fromBranch') && method_exists($instance, 'toBranch');
        }
    }
    
    $report[$modelName] = [
        'Model Exists' => $modelExists,
        'Table Exists' => $hasTable,
        'Has branch_id' => $hasBranchId,
        'Has branch() relation' => $hasBranchRelation,
        'Resource Exists' => $resourceExists,
    ];
}

echo json_encode($report, JSON_PRETTY_PRINT);
