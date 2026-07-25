<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->boolean('is_default')->default(false)->after('status');
        });

        $branchIds = DB::table('warehouses')->select('branch_id')->distinct()->pluck('branch_id');

        foreach ($branchIds as $branchId) {
            $defaultWarehouseId = DB::table('warehouses')
                ->where('branch_id', $branchId)
                ->orderByDesc('status')
                ->orderBy('id')
                ->value('id');

            if ($defaultWarehouseId) {
                DB::table('warehouses')
                    ->where('id', $defaultWarehouseId)
                    ->update(['is_default' => true]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropColumn('is_default');
        });
    }
};
