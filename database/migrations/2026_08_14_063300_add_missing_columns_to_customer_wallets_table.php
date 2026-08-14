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
        Schema::table('customer_wallets', function (Blueprint $table) {
            if (!Schema::hasColumn('customer_wallets', 'pos_sale_id')) {
                $table->foreignId('pos_sale_id')->nullable()->constrained('pos_sales')->nullOnDelete();
            }
            if (!Schema::hasColumn('customer_wallets', 'reference')) {
                $table->string('reference')->nullable();
            }
            if (!Schema::hasColumn('customer_wallets', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_wallets', function (Blueprint $table) {
            $table->dropForeign(['pos_sale_id']);
            $table->dropColumn('pos_sale_id');
            $table->dropColumn('reference');
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
        });
    }
};
