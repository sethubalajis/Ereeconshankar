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
        Schema::create('stock_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants');
            $table->foreignId('branch_id')->constrained('branches');
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->date('transaction_date');
            $table->decimal('opening_qty', 15, 2)->default(0);
            $table->decimal('inward_qty', 15, 2)->default(0);
            $table->decimal('outward_qty', 15, 2)->default(0);
            $table->decimal('closing_qty', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_ledgers');
    }
};
