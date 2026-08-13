<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('login_histories', 'branch_id')) {
            Schema::table('login_histories', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('merchandise_plannings', 'branch_id')) {
            Schema::table('merchandise_plannings', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('rfqs', 'branch_id')) {
            Schema::table('rfqs', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('quotations', 'branch_id')) {
            Schema::table('quotations', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('purchase_order_items', 'branch_id')) {
            Schema::table('purchase_order_items', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('vendor_performances', 'branch_id')) {
            Schema::table('vendor_performances', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('vendor_payments', 'branch_id')) {
            Schema::table('vendor_payments', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('inventory_reconciliations', 'branch_id')) {
            Schema::table('inventory_reconciliations', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('warehouse_zones', 'branch_id')) {
            Schema::table('warehouse_zones', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('racks', 'branch_id')) {
            Schema::table('racks', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('bins', 'branch_id')) {
            Schema::table('bins', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('bin_stocks', 'branch_id')) {
            Schema::table('bin_stocks', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('picking_lists', 'branch_id')) {
            Schema::table('picking_lists', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('packing_lists', 'branch_id')) {
            Schema::table('packing_lists', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('transfer_items', 'branch_id')) {
            Schema::table('transfer_items', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('transfer_receipts', 'branch_id')) {
            Schema::table('transfer_receipts', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('transfer_approvals', 'branch_id')) {
            Schema::table('transfer_approvals', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('cash_registers', 'branch_id')) {
            Schema::table('cash_registers', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('cash_openings', 'branch_id')) {
            Schema::table('cash_openings', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('cash_closings', 'branch_id')) {
            Schema::table('cash_closings', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('pos_sale_items', 'branch_id')) {
            Schema::table('pos_sale_items', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('pos_returns', 'branch_id')) {
            Schema::table('pos_returns', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('loyalty_cards', 'branch_id')) {
            Schema::table('loyalty_cards', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('customer_wallets', 'branch_id')) {
            Schema::table('customer_wallets', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('customer_memberships', 'branch_id')) {
            Schema::table('customer_memberships', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('coupons', 'branch_id')) {
            Schema::table('coupons', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('promo_codes', 'branch_id')) {
            Schema::table('promo_codes', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('buy_x_get_y_offers', 'branch_id')) {
            Schema::table('buy_x_get_y_offers', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('festival_offers', 'branch_id')) {
            Schema::table('festival_offers', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('combo_offers', 'branch_id')) {
            Schema::table('combo_offers', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('promotion_audits', 'branch_id')) {
            Schema::table('promotion_audits', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('journals', 'branch_id')) {
            Schema::table('journals', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('journal_entries', 'branch_id')) {
            Schema::table('journal_entries', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('ledgers', 'branch_id')) {
            Schema::table('ledgers', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('bank_accounts', 'branch_id')) {
            Schema::table('bank_accounts', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('cash_books', 'branch_id')) {
            Schema::table('cash_books', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('payments', 'branch_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('receipts', 'branch_id')) {
            Schema::table('receipts', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('contra_entries', 'branch_id')) {
            Schema::table('contra_entries', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('credit_notes', 'branch_id')) {
            Schema::table('credit_notes', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('debit_notes', 'branch_id')) {
            Schema::table('debit_notes', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('trial_balances', 'branch_id')) {
            Schema::table('trial_balances', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('gst_returns', 'branch_id')) {
            Schema::table('gst_returns', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('tax_invoices', 'branch_id')) {
            Schema::table('tax_invoices', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('e_invoices', 'branch_id')) {
            Schema::table('e_invoices', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('e_way_bills', 'branch_id')) {
            Schema::table('e_way_bills', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('tcs_records', 'branch_id')) {
            Schema::table('tcs_records', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('employee_documents', 'branch_id')) {
            Schema::table('employee_documents', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('attendances', 'branch_id')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('shifts', 'branch_id')) {
            Schema::table('shifts', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('employee_leaves', 'branch_id')) {
            Schema::table('employee_leaves', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('employee_exits', 'branch_id')) {
            Schema::table('employee_exits', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('employee_salary_assignments', 'branch_id')) {
            Schema::table('employee_salary_assignments', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('payroll_cycles', 'branch_id')) {
            Schema::table('payroll_cycles', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('bonuses', 'branch_id')) {
            Schema::table('bonuses', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('incentives', 'branch_id')) {
            Schema::table('incentives', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('reimbursements', 'branch_id')) {
            Schema::table('reimbursements', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('deductions', 'branch_id')) {
            Schema::table('deductions', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('pf_contributions', 'branch_id')) {
            Schema::table('pf_contributions', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('esi_contributions', 'branch_id')) {
            Schema::table('esi_contributions', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('professional_taxes', 'branch_id')) {
            Schema::table('professional_taxes', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('tds_records', 'branch_id')) {
            Schema::table('tds_records', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('payroll_processings', 'branch_id')) {
            Schema::table('payroll_processings', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('asset_allocations', 'branch_id')) {
            Schema::table('asset_allocations', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('asset_maintenances', 'branch_id')) {
            Schema::table('asset_maintenances', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('asset_depreciations', 'branch_id')) {
            Schema::table('asset_depreciations', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('asset_disposals', 'branch_id')) {
            Schema::table('asset_disposals', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('service_requests', 'branch_id')) {
            Schema::table('service_requests', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('complaint_tickets', 'branch_id')) {
            Schema::table('complaint_tickets', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('warranty_claims', 'branch_id')) {
            Schema::table('warranty_claims', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('service_assignments', 'branch_id')) {
            Schema::table('service_assignments', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('technician_visits', 'branch_id')) {
            Schema::table('technician_visits', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('service_reports', 'branch_id')) {
            Schema::table('service_reports', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('warehouse_transfers', 'from_branch_id')) {
            Schema::table('warehouse_transfers', function (Blueprint $table) {
                $table->foreignId('from_branch_id')->nullable()->constrained('branches')->onDelete('cascade');
                $table->foreignId('to_branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('transit_inventories', 'from_branch_id')) {
            Schema::table('transit_inventories', function (Blueprint $table) {
                $table->foreignId('from_branch_id')->nullable()->constrained('branches')->onDelete('cascade');
                $table->foreignId('to_branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('promotion_settings', 'eligible_branches')) {
            Schema::table('promotion_settings', function (Blueprint $table) {
                $table->json('eligible_branches')->nullable();
            });
        }
    }

    public function down(): void
    {
    }
};
