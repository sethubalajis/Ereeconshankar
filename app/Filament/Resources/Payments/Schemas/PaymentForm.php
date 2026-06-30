<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('vendor_id')->relationship('vendor', 'name'),
                \Filament\Forms\Components\Select::make('customer_id')->relationship('customer', 'name'),
                \Filament\Forms\Components\Select::make('chart_of_account_id')->relationship('chartOfAccount', 'account_code'),
                DatePicker::make('payment_date')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                \Filament\Forms\Components\Select::make('payment_mode_id')->relationship('paymentMode', 'name'),
                TextInput::make('reference_no'),
                TextInput::make('status')
                    ->required()
                    ->default('completed'),
            ]);
    }
}
