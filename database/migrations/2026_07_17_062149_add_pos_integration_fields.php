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
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('membership_plan_id')->nullable()->constrained('membership_plans')->nullOnDelete();
        });

        Schema::table('membership_plans', function (Blueprint $table) {
            $table->decimal('upgrade_threshold', 15, 2)->nullable()->after('duration_months');
        });

        Schema::table('pos_sales', function (Blueprint $table) {
            $table->foreignId('gift_voucher_id')->nullable()->constrained('gift_vouchers')->nullOnDelete();
            $table->decimal('gift_voucher_amount', 15, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['membership_plan_id']);
            $table->dropColumn('membership_plan_id');
        });

        Schema::table('membership_plans', function (Blueprint $table) {
            $table->dropColumn('upgrade_threshold');
        });

        Schema::table('pos_sales', function (Blueprint $table) {
            $table->dropForeign(['gift_voucher_id']);
            $table->dropColumn(['gift_voucher_id', 'gift_voucher_amount']);
        });
    }
};
