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
        Schema::table('purchase_requisitions', function (Blueprint $table) {
            $table->string('requisition_number')->nullable()->after('department_id');
        });

        $rows = DB::table('purchase_requisitions')->orderBy('id')->get(['id', 'created_at']);

        foreach ($rows as $row) {
            $year = $row->created_at ? date('Y', strtotime((string) $row->created_at)) : date('Y');
            DB::table('purchase_requisitions')
                ->where('id', $row->id)
                ->update([
                    'requisition_number' => 'PR-' . $year . '-' . str_pad((string) $row->id, 4, '0', STR_PAD_LEFT),
                ]);
        }

        Schema::table('purchase_requisitions', function (Blueprint $table) {
            $table->unique('requisition_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_requisitions', function (Blueprint $table) {
            $table->dropUnique(['requisition_number']);
            $table->dropColumn('requisition_number');
        });
    }
};
