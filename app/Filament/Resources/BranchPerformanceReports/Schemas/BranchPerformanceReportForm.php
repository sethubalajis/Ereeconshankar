<?php

namespace App\Filament\Resources\BranchPerformanceReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;


class BranchPerformanceReportForm
{
    public static function configure(Schema $schema): Schema
    {
        $updatePerformanceScore = function ($get, $set) {
            $fromDate = $get('from_date');
            $toDate = $get('to_date');
            $date = $get('report_month');

            $branchId = null;
            try {
                $branchId = $get('branch_id');
            } catch (\Exception $e) {
                $branchId = null;
            }

            if ($branchId && ($fromDate || $toDate || $date)) {
                $salesQuery = \App\Models\PosSale::where('branch_id', $branchId);
                if ($fromDate && $toDate) {
                    $salesQuery->whereBetween('sale_date', [$fromDate, $toDate]);
                } elseif ($fromDate) {
                    $salesQuery->whereDate('sale_date', '>=', $fromDate);
                } elseif ($toDate) {
                    $salesQuery->whereDate('sale_date', '<=', $toDate);
                } elseif ($date) {
                    if (strlen($date) === 7) {
                        $salesQuery->whereYear('sale_date', substr($date, 0, 4))
                            ->whereMonth('sale_date', substr($date, 5, 2));
                    } else {
                        $salesQuery->whereDate('sale_date', $date);
                    }
                }

                $totalSales = (float) $salesQuery->sum('net_amount');
                $totalInvoices = $salesQuery->count();
                $completedInvoices = (clone $salesQuery)->where('status', 'completed')->count();

                if ($totalInvoices > 0) {
                    $completionRate = ($completedInvoices / $totalInvoices) * 70;
                    $salesBonus = min(30, ($totalSales / 50000) * 30);
                    $score = round($completionRate + $salesBonus, 2);
                } else {
                    $score = 0.0;
                }

                $set('score', (float) $score);
            }
        };

        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required()
                    ->live()
                    ->afterStateUpdated($updatePerformanceScore),
                DatePicker::make('from_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required()
                    ->live()
                    ->afterStateUpdated($updatePerformanceScore),
                DatePicker::make('to_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required()
                    ->live()
                    ->afterStateUpdated($updatePerformanceScore),
                DatePicker::make('report_month')
                    ->label('Report Month / Date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->nullable()
                    ->live()
                    ->afterStateUpdated($updatePerformanceScore),
                TextInput::make('score')
                    ->label('Performance Score (0-100)')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            
                Placeholder::make('report_details')
                    ->label('Report Details')
                    ->content(function ($get) {
                        $fromDate = $get('from_date');
                        $toDate = $get('to_date');
                        $date = $get('report_month');
                        
                        $branchId = null;
                        try {
                            $branchId = $get('branch_id');
                        } catch (\Exception $e) {
                            $branchId = null;
                        }
                        
                        if (!$branchId || (!$fromDate && !$toDate && !$date)) {
                            return new HtmlString('<p class="text-gray-500">Please select a branch and date range to view performance details.</p>');
                        }
                        
                        $query = \App\Models\PosSale::where('branch_id', $branchId);

                        if ($fromDate && $toDate) {
                            $query->whereBetween('sale_date', [$fromDate, $toDate]);
                        } elseif ($fromDate) {
                            $query->whereDate('sale_date', '>=', $fromDate);
                        } elseif ($toDate) {
                            $query->whereDate('sale_date', '<=', $toDate);
                        } elseif ($date) {
                            if (strlen($date) === 7) {
                                $query->whereYear('sale_date', substr($date, 0, 4))
                                    ->whereMonth('sale_date', substr($date, 5, 2));
                            } else {
                                $query->whereDate('sale_date', $date);
                            }
                        }
                        
                        $sales = $query->orderBy('sale_date', 'desc')->get();
                        
                        $totalNet = $sales->sum('net_amount');
                        $totalGross = $sales->sum('total_amount');
                        $totalDiscount = $sales->sum('discount_amount');
                        $completedCount = $sales->where('status', 'completed')->count();

                        $html = '<div class="space-y-6">';

                        // Summary Statistics Cards
                        $html .= '<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">';
                        $html .= '<div class="p-3 bg-blue-50 border border-blue-200 rounded-lg text-center"><div class="text-xs font-semibold text-blue-600 uppercase">Total Net Sales</div><div class="text-xl font-bold text-blue-900">₹' . number_format($totalNet, 2) . '</div></div>';
                        $html .= '<div class="p-3 bg-emerald-50 border border-emerald-200 rounded-lg text-center"><div class="text-xs font-semibold text-emerald-600 uppercase">Total Invoices</div><div class="text-xl font-bold text-emerald-900">' . $sales->count() . '</div></div>';
                        $html .= '<div class="p-3 bg-indigo-50 border border-indigo-200 rounded-lg text-center"><div class="text-xs font-semibold text-indigo-600 uppercase">Completed Sales</div><div class="text-xl font-bold text-indigo-900">' . $completedCount . '</div></div>';
                        $html .= '<div class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-center"><div class="text-xs font-semibold text-amber-600 uppercase">Total Discounts</div><div class="text-xl font-bold text-amber-900">₹' . number_format($totalDiscount, 2) . '</div></div>';
                        $html .= '</div>';

                        if ($sales->isEmpty()) {
                            $html .= '<p class="text-gray-500">No POS sales records found for this branch and date range.</p>';
                        } else {
                            $html .= '<div class="overflow-x-auto"><table class="w-full text-sm text-left border-collapse border">';
                            $html .= '<thead><tr class="bg-gray-100"><th class="p-2 border">Date</th><th class="p-2 border">Invoice</th><th class="p-2 border">Gross Amount</th><th class="p-2 border">Discount</th><th class="p-2 border">Net Amount</th><th class="p-2 border">Status</th></tr></thead><tbody>';
                            foreach ($sales as $record) {
                                $html .= '<tr>';
                                $html .= '<td class="p-2 border">' . ($record->sale_date ? \Carbon\Carbon::parse($record->sale_date)->format('Y-m-d') : 'N/A') . '</td>';
                                $html .= '<td class="p-2 border font-medium">' . ($record->invoice_number ?? 'N/A') . '</td>';
                                $html .= '<td class="p-2 border">₹' . number_format((float) $record->total_amount, 2) . '</td>';
                                $html .= '<td class="p-2 border">₹' . number_format((float) $record->discount_amount, 2) . '</td>';
                                $html .= '<td class="p-2 border font-bold">₹' . number_format((float) $record->net_amount, 2) . '</td>';
                                $html .= '<td class="p-2 border">' . ucfirst($record->status ?? 'N/A') . '</td>';
                                $html .= '</tr>';
                            }
                            $html .= '</tbody><tfoot><tr class="bg-gray-50 font-bold"><td colspan="4" class="p-2 border text-right">Total Net Sales:</td><td class="p-2 border" colspan="2">₹' . number_format($totalNet, 2) . '</td></tr></tfoot></table></div>';
                        }

                        $html .= '</div>';
                        return new HtmlString($html);
                    })
                    ->columnSpanFull(),
            ]);
    }
}
