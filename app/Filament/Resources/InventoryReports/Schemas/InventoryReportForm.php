<?php

namespace App\Filament\Resources\InventoryReports\Schemas;

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
                        
                        return view('filament.reports.inventory-report-placeholder', [
                            'branchId' => $branchId,
                            'fromDate' => $get('from_date'),
                            'toDate' => $get('to_date'),
                            'reportDate' => $get('report_date')
                        ]);
                    })
                    ->columnSpanFull(),
            ]);
    }
}
