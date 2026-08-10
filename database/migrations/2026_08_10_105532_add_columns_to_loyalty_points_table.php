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
        Schema::table('loyalty_points', function (Blueprint $table) {
            if (!Schema::hasColumn('loyalty_points', 'pos_sale_id')) {
                $table->foreignId('pos_sale_id')->nullable()->constrained('pos_sales')->nullOnDelete();
            }
            if (!Schema::hasColumn('loyalty_points', 'branch_id')) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            }
            if (!Schema::hasColumn('loyalty_points', 'reference')) {
                $table->string('reference')->nullable();
            }
            if (!Schema::hasColumn('loyalty_points', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('loyalty_points', 'financial_year_id')) {
                $table->foreignId('financial_year_id')->nullable()->constrained('financial_years')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loyalty_points', function (Blueprint $table) {
            $table->dropForeign(['pos_sale_id']);
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['financial_year_id']);
            $table->dropColumn(['pos_sale_id', 'branch_id', 'reference', 'created_by', 'financial_year_id']);
        });
    }
};
