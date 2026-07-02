<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockTransaction;
use App\Models\Stock;
use App\Models\Grn;
use App\Models\PurchaseOrder;

class StockTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stocks = Stock::all();

        if ($stocks->isEmpty()) {
            $this->command->warn('No stocks found. Please seed stocks before running StockTransactionSeeder.');
            return;
        }

        $grns = Grn::all();
        $purchaseOrders = PurchaseOrder::all();

        $transactionTypes = ['in', 'out', 'adjustment', 'transfer'];

        foreach ($stocks as $stock) {
            // Create 1 to 5 random transactions for each stock
            $numTransactions = rand(1, 5);

            for ($i = 0; $i < $numTransactions; $i++) {
                $type = $transactionTypes[array_rand($transactionTypes)];
                $quantity = rand(1, 50);

                // Assign random references based on type
                $referenceType = null;
                $referenceId = null;

                if ($type === 'in' && $grns->isNotEmpty()) {
                    $referenceType = Grn::class;
                    $referenceId = $grns->random()->id;
                } elseif ($type === 'out' && $purchaseOrders->isNotEmpty()) {
                    $referenceType = PurchaseOrder::class;
                    $referenceId = $purchaseOrders->random()->id;
                }

                // If 'out', quantity could be represented as negative or positive depending on your logic,
                // Assuming it stores positive quantities and transaction_type defines the operation.

                StockTransaction::create([
                    'stock_id' => $stock->id,
                    'transaction_type' => $type,
                    'quantity' => $quantity,
                    'reference' => $referenceId,
                    'reference_type' => $referenceType,
                ]);
            }
        }

        $this->command->info('StockTransactionSeeder completed successfully. Seeded ' . StockTransaction::count() . ' records.');
    }
}
