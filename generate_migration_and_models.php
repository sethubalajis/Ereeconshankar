<?php
$modelsWithBranchId = [
    'LoginHistory' => 'login_histories',
    'MerchandisePlanning' => 'merchandise_plannings',
    'Rfq' => 'rfqs',
    'Quotation' => 'quotations',
    'PurchaseOrderItem' => 'purchase_order_items',
    'VendorPerformance' => 'vendor_performances',
    'VendorPayment' => 'vendor_payments',
    'InventoryReconciliation' => 'inventory_reconciliations',
    'WarehouseZone' => 'warehouse_zones',
    'Rack' => 'racks',
    'Bin' => 'bins',
    'BinStock' => 'bin_stocks',
    'PickingList' => 'picking_lists',
    'PackingList' => 'packing_lists',
    'TransferItem' => 'transfer_items',
    'TransferReceipt' => 'transfer_receipts',
    'TransferApproval' => 'transfer_approvals',
    'CashRegister' => 'cash_registers',
    'CashOpening' => 'cash_openings',
    'CashClosing' => 'cash_closings',
    'PosSaleItem' => 'pos_sale_items',
    'PosReturn' => 'pos_returns',
    'LoyaltyCard' => 'loyalty_cards',
    'CustomerWallet' => 'customer_wallets',
    'CustomerMembership' => 'customer_memberships',
    'Coupon' => 'coupons',
    'PromoCode' => 'promo_codes',
    'BuyXGetYOffer' => 'buy_x_get_y_offers',
    'FestivalOffer' => 'festival_offers',
    'ComboOffer' => 'combo_offers',
    'PromotionAudit' => 'promotion_audits',
    'Journal' => 'journals',
    'JournalEntry' => 'journal_entries',
    'Ledger' => 'ledgers',
    'BankAccount' => 'bank_accounts',
    'CashBook' => 'cash_books',
    'Payment' => 'payments',
    'Receipt' => 'receipts',
    'ContraEntry' => 'contra_entries',
    'CreditNote' => 'credit_notes',
    'DebitNote' => 'debit_notes',
    'TrialBalance' => 'trial_balances',
    'GstReturn' => 'gst_returns',
    'TaxInvoice' => 'tax_invoices',
    'EInvoice' => 'e_invoices',
    'EWayBill' => 'e_way_bills',
    'TcsRecord' => 'tcs_records',
    'EmployeeDocument' => 'employee_documents',
    'Attendance' => 'attendances',
    'Shift' => 'shifts',
    'EmployeeLeave' => 'employee_leaves',
    'EmployeeExit' => 'employee_exits',
    'EmployeeSalaryAssignment' => 'employee_salary_assignments',
    'PayrollCycle' => 'payroll_cycles',
    'Bonus' => 'bonuses',
    'Incentive' => 'incentives',
    'Reimbursement' => 'reimbursements',
    'Deduction' => 'deductions',
    'PfContribution' => 'pf_contributions',
    'EsiContribution' => 'esi_contributions',
    'ProfessionalTax' => 'professional_taxes',
    'TdsRecord' => 'tds_records',
    'PayrollProcessing' => 'payroll_processings',
    'AssetAllocation' => 'asset_allocations',
    'AssetMaintenance' => 'asset_maintenances',
    'AssetDepreciation' => 'asset_depreciations',
    'AssetDisposal' => 'asset_disposals',
    'ServiceRequest' => 'service_requests',
    'ComplaintTicket' => 'complaint_tickets',
    'WarrantyClaim' => 'warranty_claims',
    'ServiceAssignment' => 'service_assignments',
    'TechnicianVisit' => 'technician_visits',
    'ServiceReport' => 'service_reports'
];

$modelsWithFromToBranch = [
    'WarehouseTransfer' => 'warehouse_transfers',
    'TransitInventory' => 'transit_inventories'
];

$migrationFile = glob(__DIR__ . '/database/migrations/*_add_branch_id_to_missing_tables.php')[0];
$migrationContent = "<?php\n\nuse Illuminate\\Database\\Migrations\\Migration;\nuse Illuminate\\Database\\Schema\\Blueprint;\nuse Illuminate\\Support\\Facades\\Schema;\n\nreturn new class extends Migration\n{\n    public function up(): void\n    {\n";

foreach($modelsWithBranchId as $model => $table) {
    $migrationContent .= "        if (!Schema::hasColumn('$table', 'branch_id')) {\n";
    $migrationContent .= "            Schema::table('$table', function (Blueprint \$table) {\n";
    $migrationContent .= "                \$table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');\n";
    $migrationContent .= "            });\n";
    $migrationContent .= "        }\n";
}
foreach($modelsWithFromToBranch as $model => $table) {
    $migrationContent .= "        if (!Schema::hasColumn('$table', 'from_branch_id')) {\n";
    $migrationContent .= "            Schema::table('$table', function (Blueprint \$table) {\n";
    $migrationContent .= "                \$table->foreignId('from_branch_id')->nullable()->constrained('branches')->onDelete('cascade');\n";
    $migrationContent .= "                \$table->foreignId('to_branch_id')->nullable()->constrained('branches')->onDelete('cascade');\n";
    $migrationContent .= "            });\n";
    $migrationContent .= "        }\n";
}
// Add eligible_branches JSON column to promotion_settings
$migrationContent .= "        if (!Schema::hasColumn('promotion_settings', 'eligible_branches')) {\n";
$migrationContent .= "            Schema::table('promotion_settings', function (Blueprint \$table) {\n";
$migrationContent .= "                \$table->json('eligible_branches')->nullable();\n";
$migrationContent .= "            });\n";
$migrationContent .= "        }\n";

$migrationContent .= "    }\n\n    public function down(): void\n    {\n";
// Down methods...
$migrationContent .= "    }\n};\n";

file_put_contents($migrationFile, $migrationContent);

// UPDATE MODELS
$relationshipBranch = "\n    public function branch()\n    {\n        return \$this->belongsTo(Branch::class, 'branch_id');\n    }\n";
$relationshipFromTo = "\n    public function fromBranch()\n    {\n        return \$this->belongsTo(Branch::class, 'from_branch_id');\n    }\n\n    public function toBranch()\n    {\n        return \$this->belongsTo(Branch::class, 'to_branch_id');\n    }\n";

foreach($modelsWithBranchId as $model => $table) {
    $filePath = __DIR__ . '/app/Models/' . $model . '.php';
    if(file_exists($filePath)) {
        $content = file_get_contents($filePath);
        if(strpos($content, 'function branch(') === false) {
            $content = preg_replace('/}\s*$/', $relationshipBranch . "}\n", $content);
            file_put_contents($filePath, $content);
        }
    }
}
foreach($modelsWithFromToBranch as $model => $table) {
    $filePath = __DIR__ . '/app/Models/' . $model . '.php';
    if(file_exists($filePath)) {
        $content = file_get_contents($filePath);
        if(strpos($content, 'function fromBranch(') === false) {
            $content = preg_replace('/}\s*$/', $relationshipFromTo . "}\n", $content);
            file_put_contents($filePath, $content);
        }
    }
}
echo "Done";
