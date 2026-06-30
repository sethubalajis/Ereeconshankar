<?php

namespace App\Filament\Resources\PosSales\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PosSaleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('pos_terminal_id')->relationship('posTerminal', 'terminal_name')
                    ->required(),
                \Filament\Forms\Components\Select::make('customer_id')->relationship('customer', 'name'),
                TextInput::make('invoice_number')
                    ->required(),
                DatePicker::make('sale_date')
                    ->required(),
                TextInput::make('total_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('tax_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('discount_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('net_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                \Filament\Forms\Components\Select::make('payment_mode_id')->relationship('paymentMode', 'name'),
                TextInput::make('status')
                    ->required()
                    ->default('completed'),
            ]);
    }
}
