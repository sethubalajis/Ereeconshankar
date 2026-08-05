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
            $table->foreignId('stock_reservation_id')->nullable()->constrained('stock_reservations')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pos_sales', function (Blueprint $table) {
            $table->dropForeign(['stock_reservation_id']);
            $table->dropColumn('stock_reservation_id');
        });
    }
};
