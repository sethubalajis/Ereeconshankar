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
        Schema::create('promotion_usages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('promotion_id');
            $table->string('promotion_type');
            $table->foreignId('pos_sale_id')->constrained('pos_sales');
            $table->foreignId('pos_sale_item_id')->nullable()->constrained('pos_sale_items');
            $table->foreignId('customer_id')->nullable()->constrained('customers');
            $table->foreignId('branch_id')->constrained('branches');
            $table->decimal('quantity', 15, 2)->default(1);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_usages');
    }
};
