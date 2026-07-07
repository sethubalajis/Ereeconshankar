<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Grn;

class GrnSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('grns')->truncate();
        
        // Truncate stock to rebuild based on GRNs
        DB::table('stocks')->truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $po = DB::table('purchase_orders')->first();
        $vendorId = DB::table('vendors')->first()->id ?? 1;
        $branchId = DB::table('branches')->first()->id ?? 1;
        $warehouseId = DB::table('warehouses')->first()->id ?? 1;
        
        $products = DB::table('products')->take(3)->get();
        if ($products->count() < 3) {
            return;
        }

        $grns = [
            [
                'purchase_order_id' => $po->id ?? 1,
                'vendor_id' => $vendorId,
                'branch_id' => $branchId,
                'warehouse_id' => $warehouseId,
                'receive_date' => now()->subDays(10)->format('Y-m-d'),
                'status' => 'received',
                'received_items' => [
                    [
                        'product_id' => $products[0]->id,
                        'product_variant_id' => null,
                        'quantity' => 200,
                        'unit_price' => 1250.00
                    ],
                    [
                        'product_id' => $products[1]->id,
                        'product_variant_id' => null,
                        'quantity' => 150,
                        'unit_price' => 850.50
                    ]
                ]
            ],
            [
                'purchase_order_id' => $po->id ?? 1,
                'vendor_id' => $vendorId,
                'branch_id' => $branchId,
                'warehouse_id' => $warehouseId,
                'receive_date' => now()->subDays(5)->format('Y-m-d'),
                'status' => 'received',
                'received_items' => [
                    [
                        'product_id' => $products[2]->id,
                        'product_variant_id' => null,
                        'quantity' => 500,
                        'unit_price' => 45.00
                    ],
                    [
                        'product_id' => $products[0]->id,
                        'product_variant_id' => null,
                        'quantity' => 50,
                        'unit_price' => 1300.00
                    ]
                ]
            ]
        ];

        foreach ($grns as $grnData) {
            // Using Eloquent create so the created event triggers and updates stocks table
            Grn::create($grnData);
        }
    }
}
