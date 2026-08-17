<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $stocks = DB::table('stocks')->get();
        $grouped = $stocks->groupBy(function($item) {
            return $item->branch_id . '-' . $item->product_id . '-' . ($item->product_variant_id ?? 'null');
        });

        foreach ($grouped as $key => $group) {
            if ($group->count() > 1) {
                $first = $group->first();
                
                $totalQty = $group->sum('quantity');
                $totalReserved = $group->sum('reserved_qty');
                $totalAvailable = $group->sum('available_qty');
                
                $totalCost = $group->sum(function($s) { return $s->quantity * $s->avg_cost; });
                $avgCost = $totalQty > 0 ? ($totalCost / $totalQty) : 0;
                
                DB::table('stocks')->where('id', $first->id)->update([
                    'quantity' => $totalQty,
                    'reserved_qty' => $totalReserved,
                    'available_qty' => $totalAvailable,
                    'avg_cost' => $avgCost
                ]);
                
                // Delete the duplicates
                $duplicates = $group->skip(1)->pluck('id');
                
                // Also update stock_transactions and stock_ledgers to point to the consolidated ID
                DB::table('stock_transactions')->whereIn('stock_id', $duplicates)->update(['stock_id' => $first->id]);
                
                DB::table('stocks')->whereIn('id', $duplicates)->delete();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Consolidation cannot be safely reversed
    }
};
