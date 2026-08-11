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
        Schema::table('price_lists', function (Blueprint $table) {
            if (!Schema::hasColumn('price_lists', 'company_id')) {
                $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            }
            if (!Schema::hasColumn('price_lists', 'price_type')) {
                $table->string('price_type')->nullable()->after('code');
            }
            if (!Schema::hasColumn('price_lists', 'customer_id')) {
                $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            }
            if (!Schema::hasColumn('price_lists', 'customer_group_id')) {
                $table->foreignId('customer_group_id')->nullable()->constrained('customer_groups')->nullOnDelete();
            }
            if (!Schema::hasColumn('price_lists', 'membership_plan_id')) {
                $table->foreignId('membership_plan_id')->nullable()->constrained('membership_plans')->nullOnDelete();
            }
            if (!Schema::hasColumn('price_lists', 'branch_id')) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            }
            if (!Schema::hasColumn('price_lists', 'currency_id')) {
                $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete();
            }
            if (!Schema::hasColumn('price_lists', 'priority')) {
                $table->integer('priority')->default(1);
            }
            if (!Schema::hasColumn('price_lists', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('price_lists', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('price_lists', 'updated_by')) {
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            }
            
            if (Schema::hasColumn('price_lists', 'valid_from')) {
                $table->renameColumn('valid_from', 'start_date');
            }
            if (Schema::hasColumn('price_lists', 'valid_to')) {
                $table->renameColumn('valid_to', 'end_date');
            }
            
            if (Schema::hasColumn('price_lists', 'discount_percentage')) {
                $table->dropColumn('discount_percentage');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('price_lists', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['customer_group_id']);
            $table->dropForeign(['membership_plan_id']);
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['currency_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            
            $table->dropColumn([
                'company_id',
                'price_type',
                'customer_id',
                'customer_group_id',
                'membership_plan_id',
                'branch_id',
                'currency_id',
                'priority',
                'description',
                'created_by',
                'updated_by',
            ]);
            
            if (Schema::hasColumn('price_lists', 'start_date')) {
                $table->renameColumn('start_date', 'valid_from');
            }
            if (Schema::hasColumn('price_lists', 'end_date')) {
                $table->renameColumn('end_date', 'valid_to');
            }
            
            if (!Schema::hasColumn('price_lists', 'discount_percentage')) {
                $table->decimal('discount_percentage', 5, 2)->default(0)->after('code');
            }
        });
    }
};
