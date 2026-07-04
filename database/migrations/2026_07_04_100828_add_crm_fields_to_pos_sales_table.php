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
            $table->decimal('redeem_points_amount', 15, 2)->default(0)->after('net_amount');
            $table->decimal('use_wallet_balance', 15, 2)->default(0)->after('redeem_points_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pos_sales', function (Blueprint $table) {
            $table->dropColumn(['redeem_points_amount', 'use_wallet_balance']);
        });
    }
};
