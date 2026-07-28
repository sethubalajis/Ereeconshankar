<?php

namespace App\Filament\Resources\SalesReports\Schemas;

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
                        $fromDate = $get('from_date');
                        $toDate = $get('to_date');
                        $date = $get('report_date');
                        $branchId = $get('branch_id');

                        if (!$branchId || (!$fromDate && !$toDate && !$date)) {
                            return new HtmlString('<p class="text-gray-500">Please select a branch and date range to view sales.</p>');
                        }

                        $query = \App\Models\PosSale::where('branch_id', $branchId);

                        if ($fromDate && $toDate) {
                            $query->whereBetween('sale_date', [$fromDate, $toDate]);
                        } elseif ($fromDate) {
                            $query->whereDate('sale_date', '>=', $fromDate);
                        } elseif ($toDate) {
                            $query->whereDate('sale_date', '<=', $toDate);
                        } elseif ($date) {
                            $query->whereDate('sale_date', $date);
                        }

                        $sales = $query->get();

                        if ($sales->isEmpty()) {
                            return new HtmlString('<p class="text-gray-500">No sales found for the selected date range and branch.</p>');
                        }

                        $html = '<div class="overflow-x-auto"><table class="w-full text-sm text-left border-collapse">';
                        $html .= '<thead><tr class="bg-gray-100"><th class="p-2 border">Invoice</th><th class="p-2 border">Amount</th><th class="p-2 border">Discount</th><th class="p-2 border">Net Amount</th><th class="p-2 border">Status</th></tr></thead><tbody>';

                        $totalSales = 0;
                        foreach ($sales as $sale) {
                            $totalSales += $sale->net_amount;
                            $html .= '<tr>';
                            $html .= '<td class="p-2 border">' . $sale->invoice_number . '</td>';
                            $html .= '<td class="p-2 border">₹' . number_format($sale->total_amount, 2) . '</td>';
                            $html .= '<td class="p-2 border">₹' . number_format($sale->discount_amount, 2) . '</td>';
                            $html .= '<td class="p-2 border">₹' . number_format($sale->net_amount, 2) . '</td>';
                            $html .= '<td class="p-2 border">' . ucfirst($sale->status) . '</td>';
                            $html .= '</tr>';
                        }

                        $html .= '</tbody><tfoot><tr class="bg-gray-50 font-bold"><td colspan="3" class="p-2 border text-right">Total:</td><td class="p-2 border" colspan="2">₹' . number_format($totalSales, 2) . '</td></tr></tfoot>';
                        $html .= '</table></div>';

                        return new HtmlString($html);
                    })
                    ->columnSpanFull(),
            ]);
    }
}
