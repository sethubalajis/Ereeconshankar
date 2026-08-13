<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

$models = [
    'CostCenter', 'Branch', 'BranchSetting', 'UserBranchAccess', 'BranchUser', 'LoginHistory',
    'MerchandisePlanning', 'PurchaseRequisition', 'Rfq', 'Quotation', 'PurchaseOrder', 'PurchaseOrderItem', 'Grn', 'PurchaseReturn',
    'VendorPerformance', 'VendorPayment', 'Stock', 'StockTransaction', 'StockAdjustment', 'StockLedger', 'StockValuation', 'StockReservation', 'InventoryCount', 'InventoryReconciliation',
    'Warehouse', 'WarehouseZone', 'Rack', 'Bin', 'BinStock', 'WarehouseTransfer', 'PickingList', 'PackingList',
    'BranchInventory', 'BranchExpense', 'BranchTarget',
    'TransferRequest', 'StockTransfer', 'TransferItem', 'TransferReceipt', 'TransitInventory', 'TransferApproval',
    'PosTerminal', 'PosCounter', 'CashRegister', 'CashOpening', 'CashClosing', 'PosSale', 'PosSaleItem', 'PosReturn',
    'LoyaltyCard', 'LoyaltyPoint', 'CustomerWallet', 'CustomerMembership',
    'Coupon', 'PromoCode', 'BuyXGetYOffer', 'FestivalOffer', 'ComboOffer', 'PriceList', 'PromotionSetting', 'PromotionAudit',
    'Journal', 'JournalEntry', 'Ledger', 'BankAccount', 'CashBook', 'Payment', 'Receipt', 'ContraEntry', 'CreditNote', 'DebitNote', 'TrialBalance',
    'GstReturn', 'TaxInvoice', 'EInvoice', 'EWayBill', 'TcsRecord',
    'Employee', 'EmployeeDocument', 'Attendance', 'Shift', 'EmployeeLeave', 'EmployeeTransfer', 'EmployeeExit',
    'EmployeeSalaryAssignment', 'PayrollCycle', 'Bonus', 'Incentive', 'Reimbursement', 'Deduction', 'PfContribution', 'EsiContribution', 'ProfessionalTax', 'TdsRecord', 'PayrollProcessing',
    'Asset', 'AssetAllocation', 'AssetTransfer', 'AssetMaintenance', 'AssetDepreciation', 'AssetDisposal',
    'ServiceRequest', 'ComplaintTicket', 'WarrantyClaim', 'ServiceAssignment', 'TechnicianVisit', 'ServiceReport', 'CustomerFeedback',
    // Shared Master Models
    'Product', 'ProductCategory', 'Brand', 'ProductVariant', 'ProductAttribute', 'AttributeValue', 'ProductUnit', 'HsnCode', 'TaxGroup', 'Customer', 'CustomerGroup', 'LoyaltyProgram', 'MembershipPlan', 'Vendor', 'VendorCategory', 'VendorContact', 'PromotionType', 'GstRate', 'GstCategory', 'Country', 'State', 'City', 'Currency', 'Department', 'Designation', 'LeaveType', 'AssetCategory'
];
$output = [];
foreach($models as $m) {
    $class = '\\App\\Models\\' . $m;
    if (class_exists($class)) {
        try {
            $model = new $class;
            $table = $model->getTable();
            $hasBranchId = Schema::hasColumn($table, 'branch_id');
            $hasFromBranchId = Schema::hasColumn($table, 'from_branch_id');
            $hasToBranchId = Schema::hasColumn($table, 'to_branch_id');
            $hasBranchRel = method_exists($model, 'branch');
            $hasFromBranchRel = method_exists($model, 'fromBranch');
            $hasToBranchRel = method_exists($model, 'toBranch');
            $output[$m] = [
                'table' => $table,
                'has_branch_id' => $hasBranchId,
                'has_from_branch_id' => $hasFromBranchId,
                'has_to_branch_id' => $hasToBranchId,
                'has_branch_rel' => $hasBranchRel,
                'has_from_branch_rel' => $hasFromBranchRel,
                'has_to_branch_rel' => $hasToBranchRel
            ];
        } catch(\Exception $e) {
            $output[$m] = ['error' => $e->getMessage()];
        }
    } else {
        $output[$m] = ['error' => 'Class not found'];
    }
}
echo json_encode($output, JSON_PRETTY_PRINT);
