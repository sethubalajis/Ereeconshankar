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
            $table->json('payments')->nullable();
            $table->dropForeign(['payment_mode_id']);
            $table->dropColumn('payment_mode_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pos_sales', function (Blueprint $table) {
            $table->foreignId('payment_mode_id')->nullable()->constrained('payment_modes');
            $table->dropColumn('payments');
        });
    }
};
