<?php

namespace App\Filament\Resources\EInvoices\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EInvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('tax_invoice_id')->relationship('taxInvoice', 'invoice_number')
                    ->required(),
                TextInput::make('irn')
                    ->required(),
                TextInput::make('ack_no'),
                DatePicker::make('ack_date'),
                TextInput::make('status')
                    ->required()
                    ->default('generated'),
            ]);
    }
}
