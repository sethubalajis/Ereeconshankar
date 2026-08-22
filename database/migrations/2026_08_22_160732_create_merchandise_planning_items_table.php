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
        Schema::create('merchandise_planning_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchandise_planning_id')->constrained('merchandise_plannings')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants');
            $table->foreignId('style_id')->nullable()->constrained('styles');
            $table->foreignId('design_id')->nullable()->constrained('designs');
            $table->foreignId('color_id')->nullable()->constrained('colors');
            $table->foreignId('size_id')->nullable()->constrained('sizes');
            $table->foreignId('fabric_type_id')->nullable()->constrained('fabric_types');
            $table->foreignId('pattern_type_id')->nullable()->constrained('pattern_types');
            $table->integer('planned_quantity')->default(0);
            $table->integer('allocated_quantity')->default(0);
            $table->integer('remaining_quantity')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchandise_planning_items');
    }
};
