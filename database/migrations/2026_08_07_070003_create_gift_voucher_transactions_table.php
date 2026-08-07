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
        Schema::create('gift_voucher_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gift_voucher_id')->constrained('gift_vouchers');
            $table->foreignId('pos_sale_id')->nullable()->constrained('pos_sales');
            $table->foreignId('customer_id')->nullable()->constrained('customers');
            $table->foreignId('branch_id')->constrained('branches');
            $table->string('transaction_type'); // REDEEM, REFUND, ISSUE
            $table->decimal('amount', 15, 2);
            $table->decimal('balance_before', 15, 2);
            $table->decimal('balance_after', 15, 2);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_voucher_transactions');
    }
};
