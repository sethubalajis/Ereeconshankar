<?php

namespace App\Filament\Resources\BranchPerformanceReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BranchPerformanceReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required(),
                DatePicker::make('report_month')
                    ->required(),
                TextInput::make('score')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
