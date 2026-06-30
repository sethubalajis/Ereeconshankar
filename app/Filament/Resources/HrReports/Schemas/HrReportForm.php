<?php

namespace App\Filament\Resources\HrReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class HrReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('report_month')
                    ->required(),
                TextInput::make('total_employees')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('new_hires')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('exits')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
