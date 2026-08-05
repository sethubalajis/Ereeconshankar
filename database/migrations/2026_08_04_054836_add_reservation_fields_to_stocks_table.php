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
        Schema::table('stocks', function (Blueprint $table) {
            $table->decimal('reserved_qty', 15, 2)->default(0)->after('quantity');
            $table->decimal('damaged_qty', 15, 2)->default(0)->after('reserved_qty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropColumn(['reserved_qty', 'damaged_qty']);
        });
    }
};
