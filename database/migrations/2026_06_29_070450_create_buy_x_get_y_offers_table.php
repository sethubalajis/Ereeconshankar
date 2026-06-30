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
        Schema::create('buy_x_get_y_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buy_product_id')->constrained('products');
            $table->foreignId('get_product_id')->constrained('products');
            $table->integer('buy_quantity')->default(1);
            $table->integer('get_quantity')->default(1);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buy_x_get_y_offers');
    }
};
