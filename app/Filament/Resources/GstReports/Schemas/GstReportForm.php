<?php

namespace App\Filament\Resources\GstReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;


class GstReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('report_type')
                    ->options([
                        'GSTR-1' => 'GSTR-1 (Outward Supplies)',
                        'GSTR-2A' => 'GSTR-2A (Inward Supplies Read-Only)',
                        'GSTR-2B' => 'GSTR-2B (Input Tax Credit Statement)',
                        'GSTR-3B' => 'GSTR-3B (Monthly Summary Return)',
                        'GSTR-4' => 'GSTR-4 (Composition Return)',
                        'GSTR-9' => 'GSTR-9 (Annual Return)',
                        'GSTR-9C' => 'GSTR-9C (Reconciliation Statement)',
                    ])
                    ->required()
                    ->searchable()
                    ->live(),
                DatePicker::make('period_start')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required()
                    ->live(),
                DatePicker::make('period_end')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required()
                    ->live(),
            
                Placeholder::make('report_details')
                    ->label('GST Report Details & Tax Breakdown')
                    ->content(function ($get) {
                        $reportType = $get('report_type');
                        $fromDate = $get('period_start');
                        $toDate = $get('period_end');
                        return view('filament.reports.gst-report-placeholder', [
                            'reportType' => $reportType,
                            'periodStart' => $fromDate,
                            'periodEnd' => $toDate,
                        ]);
                    })
                    ->columnSpanFull(),
            ]);
    }
}
