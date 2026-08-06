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
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('financial_year_id')->nullable()->constrained('financial_years')->nullOnDelete();
            
            $table->decimal('on_hand_before', 15, 2)->default(0);
            $table->decimal('on_hand_after', 15, 2)->default(0);
            $table->decimal('reserved_before', 15, 2)->default(0);
            $table->decimal('reserved_after', 15, 2)->default(0);
            $table->decimal('available_before', 15, 2)->default(0);
            $table->decimal('available_after', 15, 2)->default(0);
            
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('transaction_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['warehouse_id']);
            $table->dropForeign(['product_id']);
            $table->dropForeign(['financial_year_id']);
            $table->dropForeign(['user_id']);
            
            $table->dropColumn([
                'company_id', 'branch_id', 'warehouse_id', 'product_id', 'financial_year_id',
                'on_hand_before', 'on_hand_after', 'reserved_before', 'reserved_after',
                'available_before', 'available_after', 'user_id', 'transaction_date'
            ]);
        });
    }
};
