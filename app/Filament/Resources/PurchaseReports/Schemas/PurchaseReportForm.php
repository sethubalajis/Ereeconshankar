<?php

namespace App\Filament\Resources\PurchaseReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;


class PurchaseReportForm
{
    public static function configure(Schema $schema): Schema
    {
        $updateTotalPurchases = function ($get, $set) {
            $fromDate = $get('from_date');
            $toDate = $get('to_date');
            $date = $get('report_date');
            
            $branchId = null;
            try {
                $branchId = $get('branch_id');
            } catch (\Exception $e) {
                $branchId = null;
            }

            if ($branchId && ($fromDate || $toDate || $date)) {
                $query = \App\Models\PurchaseOrder::query();

                if ($fromDate && $toDate) {
                    $query->whereBetween('po_date', [$fromDate, $toDate]);
                } elseif ($fromDate) {
                    $query->whereDate('po_date', '>=', $fromDate);
                } elseif ($toDate) {
                    $query->whereDate('po_date', '<=', $toDate);
                } elseif ($date) {
                    if (strlen($date) === 7) { // Y-m
                        $query->whereYear('po_date', substr($date, 0, 4))
                            ->whereMonth('po_date', substr($date, 5, 2));
                    } else {
                        $query->whereDate('po_date', $date);
                    }
                }

                if (\Illuminate\Support\Facades\Schema::hasColumn((new \App\Models\PurchaseOrder)->getTable(), 'branch_id')) {
                    $query->where('branch_id', $branchId);
                }

                $totalPurchases = $query->sum('total_amount');
                $set('total_purchases', (float) $totalPurchases);
            }
        };

        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required()
                    ->live()
                    ->afterStateUpdated($updateTotalPurchases),
                DatePicker::make('from_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required()
                    ->live()
                    ->afterStateUpdated($updateTotalPurchases),
                DatePicker::make('to_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required()
                    ->live()
                    ->afterStateUpdated($updateTotalPurchases),
                DatePicker::make('report_date')
                    ->label('Report Date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->nullable()
                    ->live()
                    ->afterStateUpdated($updateTotalPurchases),
                TextInput::make('total_purchases')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->readOnly()
                    ->dehydrated()
                    ->default(0.0),
            
                Placeholder::make('report_details')
                    ->label('Report Details')
                    ->content(function ($get) {
                        $fromDate = $get('from_date');
                        $toDate = $get('to_date');
                        $date = $get('report_date');
                        
                        // Handle branch_id safely
                        $branchId = null;
                        try {
                            $branchId = $get('branch_id');
                        } catch (\Exception $e) {
                            $branchId = null;
                        }
                        
                        if (!$branchId || (!$fromDate && !$toDate && !$date)) return new HtmlString('<p class="text-gray-500">Please select a branch and date range to view details.</p>');
                        
                        $query = \App\Models\PurchaseOrder::query();

                        if ($fromDate && $toDate) {
                            $query->whereBetween('po_date', [$fromDate, $toDate]);
                        } elseif ($fromDate) {
                            $query->whereDate('po_date', '>=', $fromDate);
                        } elseif ($toDate) {
                            $query->whereDate('po_date', '<=', $toDate);
                        } elseif ($date) {
                            if (strlen($date) === 7) { // Y-m
                                $query->whereYear('po_date', substr($date, 0, 4))
                                    ->whereMonth('po_date', substr($date, 5, 2));
                            } else {
                                $query->whereDate('po_date', $date);
                            }
                        }
                        
                        if ($branchId) {
                            // Check if branch_id exists on the model
                            if (\Illuminate\Support\Facades\Schema::hasColumn((new \App\Models\PurchaseOrder)->getTable(), 'branch_id')) {
                                $query->where('branch_id', $branchId);
                            }
                        }
                        
                        $records = $query->take(50)->get();
                        
                        if ($records->isEmpty()) return new HtmlString('<p class="text-gray-500">No purchase records found for the selected branch and date range.</p>');
                        
                        $html = '<div class="overflow-x-auto"><table class="w-full text-sm text-left border-collapse">';
                        $html .= '<thead><tr class="bg-gray-100">';
                        $html .= '<th class="p-2 border">PO Number</th>';
                        $html .= '<th class="p-2 border">Vendor</th>';
                        $html .= '<th class="p-2 border">Amount</th>';
                        $html .= '<th class="p-2 border">Status</th>';
                        $html .= '</tr></thead><tbody>';
                        $total = 0;
                        foreach ($records as $record) {
                            $total += (float) $record->total_amount;
                            $html .= '<tr>';
                            $val = $record->po_number;
                            $html .= '<td class="p-2 border">' . $val . '</td>';
                            $val = $record->vendor ? $record->vendor->name : 'N/A';
                            $html .= '<td class="p-2 border">' . $val . '</td>';
                            $html .= '<td class="p-2 border">₹' . number_format((float) $record->total_amount, 2) . '</td>';
                            $val = ucfirst($record->status ?? 'N/A');
                            $html .= '<td class="p-2 border">' . $val . '</td>';
                            $html .= '</tr>';
                        }
                        $html .= '</tbody><tfoot><tr class="bg-gray-50 font-bold"><td colspan="2" class="p-2 border text-right">Total:</td><td class="p-2 border" colspan="2">₹' . number_format($total, 2) . '</td></tr></tfoot></table></div>';
                        return new HtmlString($html);
                    })
                    ->columnSpanFull(),
            ]);
    }
}
