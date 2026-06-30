<?php

namespace App\Filament\Resources\Grns\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GrnForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('purchase_order_id')->relationship('purchaseOrder', 'po_number')
                    ->required(),
                \Filament\Forms\Components\Select::make('vendor_id')->relationship('vendor', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required(),
                TextInput::make('grn_number')
                    ->required(),
                DatePicker::make('receive_date')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('received'),
            ]);
    }
}
