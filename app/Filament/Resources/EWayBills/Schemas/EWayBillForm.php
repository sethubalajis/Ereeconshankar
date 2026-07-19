<?php

namespace App\Filament\Resources\EWayBills\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EWayBillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('tax_invoice_id')->relationship('taxInvoice', 'invoice_number')
                    ->required(),
                TextInput::make('eway_bill_no')
                    ->required(),
                DatePicker::make('valid_until')
                    ->default(now('Asia/Kolkata')),
                \Filament\Forms\Components\TextInput::make('transporter_id')->numeric()
                    ,
                TextInput::make('vehicle_no'),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'expired' => 'Expired',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('active'),
            ]);
    }
}
