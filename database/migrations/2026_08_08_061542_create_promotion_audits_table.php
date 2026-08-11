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
        Schema::create('promotion_audits', function (Blueprint $table) {
            $table->id();
            // A reference to the pos_sale or cart session if we have one. We can just use string for session or nullable foreign key for pos_sale.
            $table->foreignId('pos_sale_id')->nullable();
            
            $table->string('promotion_name');
            $table->string('promotion_type')->nullable(); // e.g., B1G1, DISCOUNT_RULE
            $table->integer('priority')->default(0);
            $table->boolean('is_stackable')->default(false);
            
            $table->string('status'); // ELIGIBLE, APPLIED, REJECTED
            $table->string('rejection_reason')->nullable();
            
            $table->decimal('discount_amount', 15, 2)->default(0);
            
            $table->unsignedBigInteger('product_id')->nullable(); // If it applied to a specific product

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_audits');
    }
};
