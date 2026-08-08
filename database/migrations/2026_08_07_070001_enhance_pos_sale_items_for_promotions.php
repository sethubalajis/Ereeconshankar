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
        Schema::table('pos_sale_items', function (Blueprint $table) {
            if (!Schema::hasColumn('pos_sale_items', 'mrp')) {
                $table->decimal('mrp', 15, 2)->default(0)->after('unit_price');
            }
            if (!Schema::hasColumn('pos_sale_items', 'selling_price')) {
                $table->decimal('selling_price', 15, 2)->default(0)->after('mrp');
            }
            if (!Schema::hasColumn('pos_sale_items', 'gross_amount')) {
                $table->decimal('gross_amount', 15, 2)->default(0)->after('total_price');
            }
            if (!Schema::hasColumn('pos_sale_items', 'discount_type')) {
                $table->string('discount_type')->nullable()->after('gross_amount');
            }
            if (!Schema::hasColumn('pos_sale_items', 'discount_percentage')) {
                $table->decimal('discount_percentage', 5, 2)->nullable()->after('discount_type');
            }
            if (!Schema::hasColumn('pos_sale_items', 'discount_amount')) {
                $table->decimal('discount_amount', 15, 2)->default(0)->after('discount_percentage');
            }
            if (!Schema::hasColumn('pos_sale_items', 'promotion_id')) {
                $table->unsignedBigInteger('promotion_id')->nullable()->after('discount_amount');
            }
            if (!Schema::hasColumn('pos_sale_items', 'promotion_type')) {
                $table->string('promotion_type')->nullable()->after('promotion_id');
            }
            if (!Schema::hasColumn('pos_sale_items', 'coupon_id')) {
                $table->unsignedBigInteger('coupon_id')->nullable()->after('promotion_type');
            }
            if (!Schema::hasColumn('pos_sale_items', 'promo_code_id')) {
                $table->unsignedBigInteger('promo_code_id')->nullable()->after('coupon_id');
            }
            if (!Schema::hasColumn('pos_sale_items', 'loyalty_discount')) {
                $table->decimal('loyalty_discount', 15, 2)->default(0)->after('promo_code_id');
            }
            if (!Schema::hasColumn('pos_sale_items', 'is_free_item')) {
                $table->boolean('is_free_item')->default(false)->after('loyalty_discount');
            }
            if (!Schema::hasColumn('pos_sale_items', 'free_item_reason')) {
                $table->string('free_item_reason')->nullable()->after('is_free_item');
            }
            if (!Schema::hasColumn('pos_sale_items', 'taxable_amount')) {
                $table->decimal('taxable_amount', 15, 2)->default(0)->after('free_item_reason');
            }
            if (!Schema::hasColumn('pos_sale_items', 'cgst')) {
                $table->decimal('cgst', 15, 2)->default(0)->after('taxable_amount');
            }
            if (!Schema::hasColumn('pos_sale_items', 'sgst')) {
                $table->decimal('sgst', 15, 2)->default(0)->after('cgst');
            }
            if (!Schema::hasColumn('pos_sale_items', 'igst')) {
                $table->decimal('igst', 15, 2)->default(0)->after('sgst');
            }
            if (!Schema::hasColumn('pos_sale_items', 'gst_amount')) {
                $table->decimal('gst_amount', 15, 2)->default(0)->after('igst');
            }
            if (!Schema::hasColumn('pos_sale_items', 'net_amount')) {
                $table->decimal('net_amount', 15, 2)->default(0)->after('gst_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pos_sale_items', function (Blueprint $table) {
            // Drop columns if needed
        });
    }
};
