<?php

namespace App\Filament\Branch\Resources\PurchaseReports\Schemas;

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
                        return view('filament.reports.purchase-report-placeholder', [
                            'branchId' => $get('branch_id'),
                            'fromDate' => $get('from_date'),
                            'toDate' => $get('to_date'),
                            'reportDate' => $get('report_date'),
                        ]);
                    })
                    ->columnSpanFull(),
            ]);
    }
}
