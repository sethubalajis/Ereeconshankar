<?php

namespace App\Filament\Resources\StockTransactionReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;

class StockTransactionReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('branch_id')->relationship('branch', 'name')
                    ->required()
                    ->live(),
                DatePicker::make('from_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required()
                    ->live(),
                DatePicker::make('to_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required()
                    ->live(),
            
                Placeholder::make('report_details')
                    ->label('Report Details')
                    ->content(function ($get) {
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
                        
                        return view('filament.reports.stock-transaction-report-placeholder', [
                            'branchId' => $branchId,
                            'fromDate' => $get('from_date'),
                            'toDate' => $get('to_date'),
                        ]);
                    })
                    ->columnSpanFull(),
            ]);
    }
}
