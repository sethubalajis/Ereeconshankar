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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->nullable()->constrained('brands');
            $table->foreignId('product_category_id')->nullable()->constrained('product_categories');
            $table->foreignId('product_sub_category_id')->nullable()->constrained('product_sub_categories');
            $table->foreignId('product_unit_id')->nullable()->constrained('product_units');
            $table->foreignId('hsn_code_id')->nullable()->constrained('hsn_codes');
            $table->foreignId('tax_group_id')->nullable()->constrained('tax_groups');
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
