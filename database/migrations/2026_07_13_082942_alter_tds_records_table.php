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
        Schema::table('tds_records', function (Blueprint $table) {
            $table->dropColumn(['section_code', 'tds_percentage', 'deductee_id', 'deductee_type', 'amount']);
            
            $table->foreignId('payroll_processing_id')->nullable()->constrained('payroll_processings')->onDelete('cascade');
            $table->foreignId('employee_id')->nullable()->constrained('employees')->onDelete('cascade');
            $table->foreignId('financial_year_id')->nullable()->constrained('financial_years')->onDelete('cascade');
            $table->enum('tax_regime', ['old', 'new'])->default('old');
            $table->decimal('annual_gross_salary', 15, 2)->default(0);
            $table->decimal('approved_tax_deductions', 15, 2)->default(0);
            $table->decimal('taxable_income', 15, 2)->default(0);
            $table->decimal('annual_income_tax', 15, 2)->default(0);
            $table->decimal('monthly_tds', 15, 2)->default(0);
            $table->decimal('remaining_tds', 15, 2)->default(0);
            $table->string('status')->default('processed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tds_records', function (Blueprint $table) {
            $table->string('section_code')->nullable();
            $table->decimal('tds_percentage', 5, 2)->default(0);
            $table->unsignedBigInteger('deductee_id')->nullable();
            $table->string('deductee_type')->nullable();
            $table->decimal('amount', 15, 2)->default(0);

            $table->dropForeign(['payroll_processing_id']);
            $table->dropForeign(['employee_id']);
            $table->dropForeign(['financial_year_id']);
            $table->dropColumn([
                'payroll_processing_id', 'employee_id', 'financial_year_id',
                'tax_regime', 'annual_gross_salary', 'approved_tax_deductions',
                'taxable_income', 'annual_income_tax', 'monthly_tds', 'remaining_tds', 'status'
            ]);
        });
    }
};
