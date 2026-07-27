<?php

namespace App\Filament\Branch\Resources\InventoryReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;


class InventoryReportForm
{
    public static function configure(Schema $schema): Schema
    {
        $updateTotalInventoryValue = function ($get, $set) {
            $fromDate = $get('from_date');
            $toDate = $get('to_date');
            $date = $get('report_date');
            
            $branchId = null;
            try {
                $branchId = $get('branch_id');
            } catch (\Exception $e) {
                $branchId = null;
            }

            if (!$branchId && auth()->check() && auth()->user()?->branch_id) {
                $branchId = auth()->user()->branch_id;
            }

            if (!$branchId) {
                $branchId = \App\Models\Branch::query()->first()?->id;
            }

            if ($branchId && ($fromDate || $toDate || $date)) {
                // 1. Check StockValuation for date range
                $query = \App\Models\StockValuation::where('branch_id', $branchId);
                if ($fromDate && $toDate) {
                    $query->whereBetween('valuation_date', [$fromDate, $toDate]);
                } elseif ($fromDate) {
                    $query->whereDate('valuation_date', '>=', $fromDate);
                } elseif ($toDate) {
                    $query->whereDate('valuation_date', '<=', $toDate);
                } elseif ($date) {
                    if (strlen($date) === 7) {
                        $query->whereYear('valuation_date', substr($date, 0, 4))
                            ->whereMonth('valuation_date', substr($date, 5, 2));
                    } else {
                        $query->whereDate('valuation_date', $date);
                    }
                }
                $valSum = $query->sum('total_value');

                if ($valSum > 0) {
                    $set('total_inventory_value', (float) $valSum);
                    return;
                }

                // 2. Check StockLedger for date range
                $ledgerQuery = \App\Models\StockLedger::where('branch_id', $branchId)->with(['product', 'productVariant']);
                if ($fromDate && $toDate) {
                    $ledgerQuery->whereBetween('transaction_date', [$fromDate, $toDate]);
                } elseif ($fromDate) {
                    $ledgerQuery->whereDate('transaction_date', '>=', $fromDate);
                } elseif ($toDate) {
                    $ledgerQuery->whereDate('transaction_date', '<=', $toDate);
                } elseif ($date) {
                    $ledgerQuery->whereDate('transaction_date', $date);
                }

                $ledgers = $ledgerQuery->get();
                if ($ledgers->isNotEmpty()) {
                    $grouped = $ledgers->groupBy(fn($item) => $item->product_variant_id ? 'v_'.$item->product_variant_id : 'p_'.$item->product_id);
                    $totalVal = 0;
                    foreach ($grouped as $group) {
                        $latestItem = $group->sortByDesc('id')->first();
                        $variant = $latestItem->productVariant ?: \App\Models\ProductVariant::where('product_id', $latestItem->product_id)->first();
                        $cost = (float) ($variant?->cost_price ?: ($variant?->selling_price ?: 0));
                        $totalVal += (float) $latestItem->closing_qty * $cost;
                    }
                    if ($totalVal > 0) {
                        $set('total_inventory_value', (float) $totalVal);
                        return;
                    }
                }

                // 3. Fallback to BranchInventory
                $branchItems = \App\Models\BranchInventory::where('branch_id', $branchId)
                    ->with(['product', 'productVariant'])
                    ->get();

                if ($branchItems->isNotEmpty()) {
                    $totalVal = 0;
                    foreach ($branchItems as $item) {
                        $variant = $item->productVariant ?: \App\Models\ProductVariant::where('product_id', $item->product_id)->first();
                        $cost = (float) ($variant?->cost_price ?: ($variant?->selling_price ?: 0));
                        $totalVal += (float) $item->quantity * $cost;
                    }
                    if ($totalVal > 0) {
                        $set('total_inventory_value', (float) $totalVal);
                        return;
                    }
                }

                // 4. Fallback to Stock records
                $stockItems = \App\Models\Stock::where('branch_id', $branchId)
                    ->with(['product', 'productVariant'])
                    ->get();

                if ($stockItems->isNotEmpty()) {
                    $totalVal = 0;
                    foreach ($stockItems as $item) {
                        $variant = $item->productVariant ?: \App\Models\ProductVariant::where('product_id', $item->product_id)->first();
                        $cost = (float) ($variant?->cost_price ?: ($variant?->selling_price ?: ($item->avg_cost ?? 0)));
                        $totalVal += (float) $item->quantity * $cost;
                    }
                    $set('total_inventory_value', (float) $totalVal);
                    return;
                }

                $set('total_inventory_value', 0.0);
            }
        };

        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required()
                    ->live()
                    ->afterStateUpdated($updateTotalInventoryValue),
                DatePicker::make('from_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required()
                    ->live()
                    ->afterStateUpdated($updateTotalInventoryValue),
                DatePicker::make('to_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required()
                    ->live()
                    ->afterStateUpdated($updateTotalInventoryValue),
                DatePicker::make('report_date')
                    ->label('Report Date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->nullable()
                    ->live()
                    ->afterStateUpdated($updateTotalInventoryValue),
                TextInput::make('total_inventory_value')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
            
                Placeholder::make('report_details')
                    ->label('Report Details')
                    ->content(function ($get) {
                        $fromDate = $get('from_date');
                        $toDate = $get('to_date');
                        $date = $get('report_date');
                        
                        $branchId = null;
                        try {
                            $branchId = $get('branch_id');
                        } catch (\Exception $e) {
                            $branchId = null;
                        }

                        if (!$branchId && auth()->check() && auth()->user()?->branch_id) {
                            $branchId = auth()->user()->branch_id;
                        }

                        if (!$branchId) {
                            $branchId = \App\Models\Branch::query()->first()?->id;
                        }
                        
                        if (!$branchId || (!$fromDate && !$toDate && !$date)) return new HtmlString('<p class="text-gray-500">Please select a branch and date range to view inventory details.</p>');
                        
                        $items = \App\Models\BranchInventory::where('branch_id', $branchId)->with(['product', 'productVariant'])->take(50)->get();
                        if ($items->isEmpty()) {
                            $items = \App\Models\Stock::where('branch_id', $branchId)->with(['product', 'productVariant'])->take(50)->get();
                        }
                        
                        if ($items->isEmpty()) return new HtmlString('<p class="text-gray-500">No inventory items found for this branch.</p>');
                        
                        $html = '<div class="overflow-x-auto"><table class="w-full text-sm text-left border-collapse">';
                        $html .= '<thead><tr class="bg-gray-100">';
                        $html .= '<th class="p-2 border">Product</th>';
                        $html .= '<th class="p-2 border">SKU</th>';
                        $html .= '<th class="p-2 border">Qty</th>';
                        $html .= '<th class="p-2 border">Unit Cost</th>';
                        $html .= '<th class="p-2 border">Total Value</th>';
                        $html .= '</tr></thead><tbody>';
                        $grandTotal = 0;
                        foreach ($records = $items as $record) {
                            $qty = (float) $record->quantity;
                            $variant = $record->productVariant ?: \App\Models\ProductVariant::where('product_id', $record->product_id)->first();
                            $cost = (float) ($variant?->cost_price ?: ($variant?->selling_price ?: ($record->avg_cost ?? 0)));
                            $val = $qty * $cost;
                            $grandTotal += $val;
                            $html .= '<tr>';
                            $html .= '<td class="p-2 border">' . ($record->product ? $record->product->name : 'N/A') . '</td>';
                            $html .= '<td class="p-2 border">' . ($variant ? $variant->sku : '-') . '</td>';
                            $html .= '<td class="p-2 border">' . number_format($qty, 2) . '</td>';
                            $html .= '<td class="p-2 border">₹' . number_format($cost, 2) . '</td>';
                            $html .= '<td class="p-2 border font-bold">₹' . number_format($val, 2) . '</td>';
                            $html .= '</tr>';
                        }
                        $html .= '</tbody><tfoot><tr class="bg-gray-50 font-bold"><td colspan="4" class="p-2 border text-right">Total Inventory Value:</td><td class="p-2 border">₹' . number_format($grandTotal, 2) . '</td></tr></tfoot></table></div>';
                        return new HtmlString($html);
                    })
                    ->columnSpanFull(),
            ]);
    }
}
