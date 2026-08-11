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
        Schema::create('promotion_settings', function (Blueprint $table) {
            $table->id();
            $table->string('offer_type')->unique()->comment('e.g., B1G1, COMBO, FESTIVAL, CUSTOMER_GROUP, MEMBERSHIP, COUPON, PROMO_CODE, LOYALTY, MANUAL');
            $table->integer('priority')->default(0);
            $table->boolean('is_stackable')->default(false);
            $table->boolean('is_exclusive')->default(false);
            $table->boolean('best_price_only')->default(false);
            $table->json('exclusions')->nullable()->comment('Array of offer_types this cannot stack with');
            $table->string('stack_group')->nullable();
            
            // Eligibilities
            $table->json('eligible_customers')->nullable();
            $table->json('eligible_customer_groups')->nullable();
            $table->json('eligible_memberships')->nullable();
            $table->json('eligible_products')->nullable();
            $table->json('eligible_categories')->nullable();
            $table->json('eligible_brands')->nullable();
            $table->json('eligible_branches')->nullable();
            
            // Limits
            $table->integer('min_quantity')->default(0);
            $table->integer('max_quantity')->nullable();
            $table->decimal('min_bill_amount', 15, 2)->default(0);
            $table->decimal('max_discount', 15, 2)->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('customer_usage_limit')->nullable();
            
            // Dates
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_settings');
    }
};
