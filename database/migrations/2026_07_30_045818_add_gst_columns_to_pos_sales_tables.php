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
        Schema::table('pos_sales', function (Blueprint $table) {
            $table->decimal('taxable_amount', 15, 2)->default(0)->after('total_amount');
            $table->decimal('cgst_amount', 15, 2)->default(0)->after('tax_amount');
            $table->decimal('sgst_amount', 15, 2)->default(0)->after('cgst_amount');
            $table->decimal('igst_amount', 15, 2)->default(0)->after('sgst_amount');
        });

        Schema::table('pos_sale_items', function (Blueprint $table) {
            $table->decimal('tax_percentage', 5, 2)->default(0)->after('total_price');
            $table->decimal('taxable_amount', 15, 2)->default(0)->after('tax_percentage');
            $table->decimal('cgst_amount', 15, 2)->default(0)->after('taxable_amount');
            $table->decimal('sgst_amount', 15, 2)->default(0)->after('cgst_amount');
            $table->decimal('igst_amount', 15, 2)->default(0)->after('sgst_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pos_sales', function (Blueprint $table) {
            $table->dropColumn(['taxable_amount', 'cgst_amount', 'sgst_amount', 'igst_amount']);
        });

        Schema::table('pos_sale_items', function (Blueprint $table) {
            $table->dropColumn(['tax_percentage', 'taxable_amount', 'cgst_amount', 'sgst_amount', 'igst_amount']);
        });
    }
};
