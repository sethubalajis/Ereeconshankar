<?php

// 1. Create Scopes directory if not exists
@mkdir(__DIR__ . '/app/Scopes', 0755, true);
@mkdir(__DIR__ . '/app/Traits', 0755, true);

// 2. Write BranchScope.php
$branchScope = <<<PHP
<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BranchScope implements Scope
{
    public function apply(Builder \$builder, Model \$model)
    {
        if (auth()->check() && !auth()->user()->hasRole(['Super Admin', 'super admin'])) {
            \$branchIds = auth()->user()->branches->pluck('id')->toArray();
            if (!empty(\$branchIds)) {
                \$builder->whereIn(\$model->getTable() . '.branch_id', \$branchIds);
            } else {
                \$builder->whereRaw('1 = 0'); // Deny access if no branch assigned
            }
        }
    }
}
PHP;
file_put_contents(__DIR__ . '/app/Scopes/BranchScope.php', $branchScope);

// 3. Write TransferBranchScope.php
$transferBranchScope = <<<PHP
<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TransferBranchScope implements Scope
{
    public function apply(Builder \$builder, Model \$model)
    {
        if (auth()->check() && !auth()->user()->hasRole(['Super Admin', 'super admin'])) {
            \$branchIds = auth()->user()->branches->pluck('id')->toArray();
            if (!empty(\$branchIds)) {
                \$builder->where(function(\$q) use (\$model, \$branchIds) {
                    \$q->whereIn(\$model->getTable() . '.from_branch_id', \$branchIds)
                      ->orWhereIn(\$model->getTable() . '.to_branch_id', \$branchIds);
                });
            } else {
                \$builder->whereRaw('1 = 0'); // Deny access if no branch assigned
            }
        }
    }
}
PHP;
file_put_contents(__DIR__ . '/app/Scopes/TransferBranchScope.php', $transferBranchScope);

// 4. Write Traits
$hasBranchTrait = <<<PHP
<?php

namespace App\Traits;

use App\Scopes\BranchScope;

trait HasBranch
{
    protected static function bootHasBranch()
    {
        static::addGlobalScope(new BranchScope);
    }
}
PHP;
file_put_contents(__DIR__ . '/app/Traits/HasBranch.php', $hasBranchTrait);

$hasTransferBranchTrait = <<<PHP
<?php

namespace App\Traits;

use App\Scopes\TransferBranchScope;

trait HasTransferBranch
{
    protected static function bootHasTransferBranch()
    {
        static::addGlobalScope(new TransferBranchScope);
    }
}
PHP;
file_put_contents(__DIR__ . '/app/Traits/HasTransferBranch.php', $hasTransferBranchTrait);

// 5. Apply to models
$branchModels = [
    'LoginHistory', 'MerchandisePlanning', 'Rfq', 'Quotation', 'PurchaseOrderItem', 
    'VendorPerformance', 'VendorPayment', 'InventoryReconciliation', 'WarehouseZone', 
    'Rack', 'Bin', 'BinStock', 'PickingList', 'PackingList', 'TransferItem', 
    'TransferReceipt', 'TransferApproval', 'CashRegister', 'CashOpening', 'CashClosing', 
    'PosSaleItem', 'PosReturn', 'LoyaltyCard', 'CustomerWallet', 'CustomerMembership', 
    'Coupon', 'PromoCode', 'BuyXGetYOffer', 'FestivalOffer', 'ComboOffer', 'PromotionAudit', 
    'Journal', 'JournalEntry', 'Ledger', 'BankAccount', 'CashBook', 'Payment', 'Receipt', 
    'ContraEntry', 'CreditNote', 'DebitNote', 'TrialBalance', 'GstReturn', 'TaxInvoice', 
    'EInvoice', 'EWayBill', 'TcsRecord', 'EmployeeDocument', 'Attendance', 'Shift', 
    'EmployeeLeave', 'EmployeeExit', 'EmployeeSalaryAssignment', 'PayrollCycle', 'Bonus', 
    'Incentive', 'Reimbursement', 'Deduction', 'PfContribution', 'EsiContribution', 
    'ProfessionalTax', 'TdsRecord', 'PayrollProcessing', 'AssetAllocation', 'AssetMaintenance', 
    'AssetDepreciation', 'AssetDisposal', 'ServiceRequest', 'ComplaintTicket', 'WarrantyClaim', 
    'ServiceAssignment', 'TechnicianVisit', 'ServiceReport',
    // Existing models
    'CostCenter', 'BranchSetting', 'UserBranchAccess', 'BranchUser', 'PurchaseRequisition', 
    'PurchaseOrder', 'Grn', 'PurchaseReturn', 'Stock', 'StockTransaction', 'StockAdjustment', 
    'StockLedger', 'StockValuation', 'StockReservation', 'InventoryCount', 'Warehouse', 
    'BranchInventory', 'BranchExpense', 'BranchTarget', 'PosTerminal', 'PosCounter', 'PosSale', 
    'LoyaltyPoint', 'PriceList', 'Employee', 'Asset', 'CustomerFeedback'
];

$transferModels = [
    'WarehouseTransfer', 'TransitInventory', 'TransferRequest', 'StockTransfer', 
    'EmployeeTransfer', 'AssetTransfer'
];

foreach($branchModels as $m) {
    $path = __DIR__ . '/app/Models/' . $m . '.php';
    if(file_exists($path)) {
        $content = file_get_contents($path);
        if(strpos($content, 'use HasBranch;') === false && strpos($content, 'class ' . $m) !== false) {
            // Add use App\Traits\HasBranch; at the top
            if(strpos($content, 'use App\Traits\HasBranch;') === false) {
                $content = preg_replace('/namespace App\\\\Models;/', "namespace App\Models;\n\nuse App\Traits\HasBranch;", $content);
            }
            // Add use HasBranch; inside class
            $content = preg_replace('/class ' . $m . '[^{]*\{/', "$0\n    use HasBranch;\n", $content);
            file_put_contents($path, $content);
        }
    }
}

foreach($transferModels as $m) {
    $path = __DIR__ . '/app/Models/' . $m . '.php';
    if(file_exists($path)) {
        $content = file_get_contents($path);
        if(strpos($content, 'use HasTransferBranch;') === false && strpos($content, 'class ' . $m) !== false) {
            // Add use App\Traits\HasTransferBranch; at the top
            if(strpos($content, 'use App\Traits\HasTransferBranch;') === false) {
                $content = preg_replace('/namespace App\\\\Models;/', "namespace App\Models;\n\nuse App\Traits\HasTransferBranch;", $content);
            }
            // Add use HasTransferBranch; inside class
            $content = preg_replace('/class ' . $m . '[^{]*\{/', "$0\n    use HasTransferBranch;\n", $content);
            file_put_contents($path, $content);
        }
    }
}
echo "Applied scopes to models";
