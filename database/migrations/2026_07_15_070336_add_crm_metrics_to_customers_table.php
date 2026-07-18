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
            $table->date('last_purchase_date')->nullable();
            $table->decimal('total_purchase_amount', 15, 2)->default(0);
            $table->integer('total_invoices')->default(0);
            $table->decimal('average_purchase_value', 15, 2)->default(0);
            $table->decimal('lifetime_purchase_value', 15, 2)->default(0);
            $table->foreignId('last_visited_branch_id')->nullable()->constrained('branches')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['last_visited_branch_id']);
            $table->dropColumn([
                'last_purchase_date',
                'total_purchase_amount',
                'total_invoices',
                'average_purchase_value',
                'lifetime_purchase_value',
                'last_visited_branch_id',
            ]);
        });
    }
};
