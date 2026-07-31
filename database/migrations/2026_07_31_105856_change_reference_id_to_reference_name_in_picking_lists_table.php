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
        Schema::table('picking_lists', function (Blueprint $table) {
            $table->dropColumn('reference_id');
            $table->string('reference_name')->nullable()->after('warehouse_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('picking_lists', function (Blueprint $table) {
            $table->dropColumn('reference_name');
            $table->unsignedBigInteger('reference_id')->nullable()->after('warehouse_id');
        });
    }
};
