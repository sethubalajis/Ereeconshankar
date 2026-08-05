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
        Schema::create('damaged_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->onDelete('cascade');
            $table->decimal('quantity', 15, 2)->default(0);
            $table->nullableMorphs('source'); // source_type, source_id
            $table->string('reason')->nullable();
            $table->date('reported_date')->nullable();
            $table->string('status')->default('Quarantined');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('damaged_products');
    }
};
