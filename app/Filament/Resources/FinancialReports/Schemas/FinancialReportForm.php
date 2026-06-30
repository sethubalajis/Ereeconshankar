<?php

namespace App\Filament\Resources\FinancialReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class FinancialReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('report_type')
                    ->required(),
                DatePicker::make('as_of_date')
                    ->required(),
                Textarea::make('data')
                    ->columnSpanFull(),
            ]);
    }
}
