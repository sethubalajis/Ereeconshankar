<?php

namespace App\Filament\Branch\Resources\FinancialReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;


class FinancialReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('report_type')
                    ->options([
                        'Profit & Loss' => 'Profit & Loss',
                        'Balance Sheet' => 'Balance Sheet',
                        'Cash Flow' => 'Cash Flow',
                        'Trial Balance' => 'Trial Balance',
                    ])
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
                DatePicker::make('as_of_date')
                    ->label('As Of Date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->nullable()
                    ->live(),
                Textarea::make('data')
                    ->columnSpanFull(),
            
                Placeholder::make('report_details')
                    ->label('Report Details & Live Financial Breakdown')
                    ->content(function ($get) {
                        return view('filament.reports.financial-report-placeholder', [
                            'reportType' => $get('report_type'),
                            'fromDate' => $get('from_date'),
                            'toDate' => $get('to_date'),
                            'asOfDate' => $get('as_of_date'),
                        ]);
                    })
                    ->columnSpanFull(),
            ]);
    }
}
