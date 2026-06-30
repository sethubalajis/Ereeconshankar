<?php

namespace App\Filament\Resources\VendorPayments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VendorPaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('vendor_id')->relationship('vendor', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('purchase_order_id')->relationship('purchaseOrder', 'po_number'),
                DatePicker::make('payment_date')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                \Filament\Forms\Components\Select::make('payment_mode_id')->relationship('paymentMode', 'name'),
                TextInput::make('transaction_reference'),
                TextInput::make('status')
                    ->required()
                    ->default('completed'),
            ]);
    }
}
