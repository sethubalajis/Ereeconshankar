<?php

namespace App\Filament\Resources\InventoryReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InventoryReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required(),
                DatePicker::make('report_date')
                    ->required(),
                TextInput::make('total_inventory_value')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
