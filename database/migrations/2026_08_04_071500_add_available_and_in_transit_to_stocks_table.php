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
            if (!Schema::hasColumn('stocks', 'available_qty')) {
                $table->decimal('available_qty', 15, 2)->default(0)->after('reserved_qty');
            }
            if (!Schema::hasColumn('stocks', 'in_transit_qty')) {
                $table->decimal('in_transit_qty', 15, 2)->default(0)->after('available_qty');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            if (Schema::hasColumn('stocks', 'available_qty')) {
                $table->dropColumn('available_qty');
            }
            if (Schema::hasColumn('stocks', 'in_transit_qty')) {
                $table->dropColumn('in_transit_qty');
            }
        });
    }
};
