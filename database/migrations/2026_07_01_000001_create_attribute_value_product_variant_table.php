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
        if (! Schema::hasTable('attribute_value_product_variant')) {
            Schema::create('attribute_value_product_variant', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_variant_id');
                $table->unsignedBigInteger('attribute_value_id');
                $table->timestamps();

                $table->index('product_variant_id');
                $table->index('attribute_value_id');
                $table->unique(['product_variant_id', 'attribute_value_id'], 'av_pv_unique');
            });

            return;
        }

        Schema::table('attribute_value_product_variant', function (Blueprint $table) {
            $table->index('product_variant_id');
            $table->index('attribute_value_id');
            $table->unique(['product_variant_id', 'attribute_value_id'], 'av_pv_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_value_product_variant');
    }
};
