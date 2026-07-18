<?php

namespace App\Filament\Resources\TaxInvoices\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TaxInvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('invoiceable_id')->numeric()
                    ->required(),
                TextInput::make('invoiceable_type')
                    ->required(),
                TextInput::make('invoice_number')
                    ->required(),
                DatePicker::make('invoice_date')
                    ->default(now('Asia/Kolkata'))
                    ->required(),
                TextInput::make('taxable_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('tax_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
