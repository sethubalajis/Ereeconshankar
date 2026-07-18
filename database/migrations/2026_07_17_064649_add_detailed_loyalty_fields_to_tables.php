<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loyalty_programs', function (Blueprint $table) {
            $table->foreignId('customer_group_id')->nullable()->constrained('customer_groups')->nullOnDelete();
            $table->foreignId('membership_plan_id')->nullable()->constrained('membership_plans')->nullOnDelete();
            $table->decimal('amount_per_point', 10, 2)->default(100)->after('points_per_amount');
            $table->decimal('max_redemption_percentage', 5, 2)->nullable()->after('value_per_point');
            $table->integer('points_expiry_months')->nullable()->after('max_redemption_percentage');
            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();
        });

        Schema::table('loyalty_points', function (Blueprint $table) {
            $table->foreignId('pos_sale_id')->nullable()->constrained('pos_sales')->nullOnDelete();
            $table->foreignId('loyalty_program_id')->nullable()->constrained('loyalty_programs')->nullOnDelete();
            $table->string('invoice_number')->nullable();
            $table->integer('points_earned')->default(0);
            $table->integer('points_redeemed')->default(0);
            $table->integer('opening_balance')->default(0);
            $table->integer('closing_balance')->default(0);
            $table->string('remarks')->nullable();
            // rename description to remarks, drop points ? Actually we can just add these and not drop the old to prevent errors, or replace them.
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->integer('lifetime_points_earned')->default(0);
            $table->integer('lifetime_points_redeemed')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('loyalty_programs', function (Blueprint $table) {
            $table->dropForeign(['customer_group_id']);
            $table->dropForeign(['membership_plan_id']);
            $table->dropColumn([
                'customer_group_id', 'membership_plan_id', 'amount_per_point', 
                'max_redemption_percentage', 'points_expiry_months', 
                'effective_from', 'effective_to'
            ]);
        });

        Schema::table('loyalty_points', function (Blueprint $table) {
            $table->dropForeign(['pos_sale_id']);
            $table->dropForeign(['loyalty_program_id']);
            $table->dropColumn([
                'pos_sale_id', 'loyalty_program_id', 'invoice_number', 
                'points_earned', 'points_redeemed', 'opening_balance', 
                'closing_balance', 'remarks'
            ]);
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['lifetime_points_earned', 'lifetime_points_redeemed']);
        });
    }
};
