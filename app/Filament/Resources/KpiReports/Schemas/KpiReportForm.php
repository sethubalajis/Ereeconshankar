<?php

namespace App\Filament\Resources\KpiReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KpiReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kpi_name')
                    ->required(),
                DatePicker::make('report_date')
                    ->required(),
                TextInput::make('value')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
