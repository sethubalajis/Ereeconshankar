<?php

namespace App\Filament\Resources\PurchaseReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PurchaseReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required(),
                DatePicker::make('report_date')
                    ->required(),
                TextInput::make('total_purchases')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
