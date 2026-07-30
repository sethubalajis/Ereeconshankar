<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('inventory_counts', 'count_code')) {
            Schema::table('inventory_counts', function (Blueprint $table) {
                $table->string('count_code')->nullable()->after('warehouse_id');
            });
            
            $rows = DB::table('inventory_counts')->orderBy('id')->get(['id', 'created_at']);
    
            foreach ($rows as $row) {
                $datePart = $row->created_at ? date('Ymd', strtotime((string) $row->created_at)) : date('Ymd');
                DB::table('inventory_counts')
                    ->where('id', $row->id)
                    ->update([
                        'count_code' => 'IC-' . $datePart . '-' . str_pad((string) $row->id, 4, '0', STR_PAD_LEFT),
                    ]);
            }
    
            Schema::table('inventory_counts', function (Blueprint $table) {
                $table->unique('count_code');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_counts', function (Blueprint $table) {
            $table->dropUnique(['count_code']);
            $table->dropColumn('count_code');
        });
    }
};
