<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'journal_entries', 'payments', 'receipts', 'contra_entries', 'debit_notes', 'credit_notes',
            'grns', 'stock_transfers', 'warehouse_transfers', 'stock_adjustments', 'inventory_counts',
            'purchase_orders', 'purchase_requisitions', 'vendor_payments', 'purchase_returns',
            'pos_sales', 'pos_returns', 'quotations', 'tax_invoices', 'e_invoices', 'e_way_bills',
            'payroll_processings', 'asset_depreciations', 'asset_disposals', 'asset_transfers', 'asset_maintenances',
            'gst_returns'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->foreignId('financial_year_id')->nullable()->constrained('financial_years')->onDelete('restrict');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'journal_entries', 'payments', 'receipts', 'contra_entries', 'debit_notes', 'credit_notes',
            'grns', 'stock_transfers', 'warehouse_transfers', 'stock_adjustments', 'inventory_counts',
            'purchase_orders', 'purchase_requisitions', 'vendor_payments', 'purchase_returns',
            'pos_sales', 'pos_returns', 'quotations', 'tax_invoices', 'e_invoices', 'e_way_bills',
            'payroll_processings', 'asset_depreciations', 'asset_disposals', 'asset_transfers', 'asset_maintenances',
            'gst_returns'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'financial_year_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropForeign(['financial_year_id']);
                    $table->dropColumn('financial_year_id');
                });
            }
        }
    }
};
