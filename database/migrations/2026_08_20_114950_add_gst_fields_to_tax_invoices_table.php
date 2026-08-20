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
        Schema::table('tax_invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('tax_invoices', 'company_id')) {
                $table->unsignedBigInteger('company_id')->nullable();
            }
            if (!Schema::hasColumn('tax_invoices', 'customer_id')) {
                $table->unsignedBigInteger('customer_id')->nullable();
            }
            if (!Schema::hasColumn('tax_invoices', 'cgst')) {
                $table->decimal('cgst', 15, 2)->default(0);
                $table->decimal('sgst', 15, 2)->default(0);
                $table->decimal('igst', 15, 2)->default(0);
                $table->string('hsn_sac')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tax_invoices', function (Blueprint $table) {
            $table->dropColumn([
                'branch_id', 'company_id', 'financial_year_id', 'customer_id',
                'cgst', 'sgst', 'igst', 'hsn_sac'
            ]);
        });
    }
};
