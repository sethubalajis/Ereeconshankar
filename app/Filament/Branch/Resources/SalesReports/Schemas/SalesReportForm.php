<?php

namespace App\Filament\Branch\Resources\SalesReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class SalesReportForm
{
    public static function configure(Schema $schema): Schema
    {
        $updateTotalSales = function ($get, $set) {
            $fromDate = $get('from_date');
            $toDate = $get('to_date');
            $date = $get('report_date');
            $branchId = $get('branch_id');

            if ($branchId) {
                $query = \App\Models\PosSale::where('branch_id', $branchId);

                if ($fromDate && $toDate) {
                    $query->whereBetween('sale_date', [$fromDate, $toDate]);
                } elseif ($fromDate) {
                    $query->whereDate('sale_date', '>=', $fromDate);
                } elseif ($toDate) {
                    $query->whereDate('sale_date', '<=', $toDate);
                } elseif ($date) {
                    $query->whereDate('sale_date', $date);
                } else {
                    return;
                }

                $totalSales = $query->sum('net_amount');
                $set('total_sales', (float) $totalSales);
            }
        };

        return $schema
            ->components([
                Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->required()
                    ->live()
                    ->afterStateUpdated($updateTotalSales),
                DatePicker::make('from_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required()
                    ->live()
                    ->afterStateUpdated($updateTotalSales),
                DatePicker::make('to_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required()
                    ->live()
                    ->afterStateUpdated($updateTotalSales),
                DatePicker::make('report_date')
                    ->label('Report Date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->nullable()
                    ->live()
                    ->afterStateUpdated($updateTotalSales),
                TextInput::make('total_sales')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
                Placeholder::make('sales_reports_list')
                    ->label('Sales Reports for Selected Date Range')
                    ->content(function ($get) {
                        return view('filament.reports.sales-report-placeholder', [
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
