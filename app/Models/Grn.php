<?php

namespace App\Models;

use App\Traits\HasBranch;

use App\Traits\BelongsToFinancialYear;

use Illuminate\Database\Eloquent\Model;

class Grn extends Model
{
    use HasBranch;

    use BelongsToFinancialYear;
    protected $guarded = [];

    protected $casts = [
        'received_items' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->grn_number)) {
                $lastGrn = self::orderBy('id', 'desc')->first();
                $nextId = $lastGrn ? $lastGrn->id + 1 : 1;
                $model->grn_number = 'GRN-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });

        static::created(function ($model) {
            if ($model->status === 'received' && is_array($model->received_items) && count($model->received_items) > 0) {
                self::processReceivedGrn($model);
            }
        });

        static::updated(function ($model) {
            if ($model->isDirty('status') && $model->status === 'received' && is_array($model->received_items) && count($model->received_items) > 0) {
                self::processReceivedGrn($model);
            }
        });
    }

    protected static function processReceivedGrn($model)
    {
        $inventoryService = new \App\Services\InventoryService();
        $accountingService = app(\App\Services\AccountingService::class);
        $gstService = app(\App\Services\GstService::class);
        
        \Log::info('GRN Created/Updated, processing items...', ['grn_id' => $model->id, 'items_count' => count($model->received_items)]);

        $totalTaxable = 0;
        $totalGst = 0;

        foreach ($model->received_items as $item) {
            $productId = $item['product_id'] ?? null;
            if (!$productId) continue;
            
            $product = \App\Models\Product::find($productId);
            if (!$product) continue;

            $variantId = $item['product_variant_id'] ?? null;
            $quantity = (float)($item['quantity'] ?? 0);
            $unitPrice = (float)($item['unit_price'] ?? 0);
            
            $itemTotal = $quantity * $unitPrice;
            $totalTaxable += $itemTotal;

            \Log::info('Processing GRN item', ['product_id' => $productId, 'type' => $product->product_type, 'qty' => $quantity]);

            if (strtolower(trim($product->product_type)) === 'asset') {
                $assetCategory = \App\Models\AssetCategory::firstOrCreate(
                    ['name' => 'Uncategorized Hardware'],
                    ['code' => 'UNCAT-HW']
                );

                for ($i = 0; $i < $quantity; $i++) {
                    $asset = \App\Models\Asset::create([
                        'asset_category_id' => $assetCategory->id,
                        'branch_id' => $model->branch_id,
                        'name' => $product->name,
                        'asset_code' => 'AST-' . date('Ymd') . '-' . $product->id . '-' . uniqid('', true),
                        'purchase_date' => $model->created_at,
                        'purchase_cost' => $unitPrice,
                        'status' => 'active',
                    ]);
                    \Log::info('Asset Created from GRN', ['asset_id' => $asset->id, 'asset_code' => $asset->asset_code]);
                }
            } else {
                $inventoryService->updateStock(
                    $productId,
                    $variantId,
                    $model->branch_id,
                    null, // warehouse_id removed
                    'in',
                    $quantity,
                    $unitPrice,
                    $model->grn_number ?? 'GRN-' . $model->id,
                    'App\\Models\\Grn'
                );
            }
        }
        
        // Accounting & GST
        $purchaseAcc = \App\Models\ChartOfAccount::where('account_code', '5001')->first(); // Purchase
        $vendorAcc = \App\Models\ChartOfAccount::where('account_code', '3001')->first(); // Accounts Payable
        $gstInAcc = \App\Models\ChartOfAccount::where('account_code', '1002')->first(); // Input GST

        if ($purchaseAcc && $vendorAcc && $gstInAcc && $totalTaxable > 0) {
            $gstData = $gstService->calculateGst((float)$totalTaxable, 18.0); // Assuming 18% for purchases
            $totalGst = $gstData['total_gst'];
            $totalAmount = $totalTaxable + $totalGst;

            // Note: In real life, vendor invoices might be captured manually, but we generate a dummy one here for testing
            // or we track it for GST returns.
            $gstService->generateTaxInvoice($model, [
                'invoice_number' => 'INV-GRN-' . $model->id,
                'invoice_date' => now(),
                'taxable_amount' => $totalTaxable,
                'tax_amount' => $totalGst,
                'total_amount' => $totalAmount,
                'branch_id' => $model->branch_id,
                'company_id' => clone $model->company_id ?? null,
                'financial_year_id' => $model->financial_year_id ?? null,
                'cgst' => $gstData['cgst'],
                'sgst' => $gstData['sgst'],
                'igst' => $gstData['igst'],
            ]);

            $accountingService->postJournalEntry([
                'journal_number' => 'JRN-GRN-' . $model->id,
                'journal_date' => now(),
                'reference' => 'GRN ' . $model->id,
                'branch_id' => $model->branch_id,
                'financial_year_id' => $model->financial_year_id ?? null,
                'total_debit' => $totalAmount,
                'total_credit' => $totalAmount,
            ], [
                [
                    'chart_of_account_id' => $purchaseAcc->id,
                    'debit' => $totalTaxable,
                    'credit' => 0,
                    'branch_id' => $model->branch_id,
                    'financial_year_id' => $model->financial_year_id ?? null,
                ],
                [
                    'chart_of_account_id' => $gstInAcc->id,
                    'debit' => $totalGst,
                    'credit' => 0,
                    'branch_id' => $model->branch_id,
                    'financial_year_id' => $model->financial_year_id ?? null,
                ],
                [
                    'chart_of_account_id' => $vendorAcc->id,
                    'debit' => 0,
                    'credit' => $totalAmount,
                    'branch_id' => $model->branch_id,
                    'financial_year_id' => $model->financial_year_id ?? null,
                ]
            ]);
        }
    }

    //
    public function purchaseOrder() {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }
    public function vendor() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
