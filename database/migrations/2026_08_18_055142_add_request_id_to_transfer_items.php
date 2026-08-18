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
        Schema::table('transfer_items', function (Blueprint $table) {
            $table->unsignedBigInteger('transfer_request_id')->nullable()->after('id');
        });
        
        // Make stock_transfer_id nullable
        Schema::table('transfer_items', function (Blueprint $table) {
            $table->unsignedBigInteger('stock_transfer_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transfer_items', function (Blueprint $table) {
            $table->dropColumn('transfer_request_id');
        });
    }
};
