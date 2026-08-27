<?php

namespace App\Filament\Branch\Resources\BranchPerformanceReports\Schemas;

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
                        return view('filament.reports.branch-performance-report-placeholder', [
                            'branchId' => $get('branch_id'),
                            'fromDate' => $get('from_date'),
                            'toDate' => $get('to_date'),
                            'reportMonth' => $get('report_month'),
                        ]);
                    })
                    ->columnSpanFull(),
            ]);
    }
}
