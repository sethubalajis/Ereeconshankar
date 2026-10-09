<?php

namespace App\Filament\Resources\TaxInvoices\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Schemas\Schema;

class TaxInvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('invoiceable_type')
                    ->label('Invoice Category / Module')
                    ->options([
                        'App\Models\PosSale' => 'POS Sale',
                        'App\Models\PurchaseOrder' => 'Purchase Order',
                        'App\Models\DirectInvoice' => 'Direct Invoice',
                        'Other' => 'General / Other',
                    ])
                    ->default('App\Models\PosSale')
                    ->required(),
                TextInput::make('invoiceable_id')
                    ->label('Source Reference ID')
                    ->numeric()
                    ->required(),
                TextInput::make('invoice_number')
                    ->label('Invoice Number')
                    ->default(fn () => 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4)))
                    ->required(),
                DatePicker::make('invoice_date')
                    ->default(now('Asia/Kolkata'))
                    ->required(),
                TextInput::make('taxable_amount')
                    ->label('Taxable Amount')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $taxable = (float) ($get('taxable_amount') ?? 0);
                        $tax = (float) ($get('tax_amount') ?? 0);
                        $set('total_amount', round($taxable + $tax, 2));
                    }),
                TextInput::make('tax_amount')
                    ->label('Tax Amount (GST)')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $taxable = (float) ($get('taxable_amount') ?? 0);
                        $tax = (float) ($get('tax_amount') ?? 0);
                        $set('total_amount', round($taxable + $tax, 2));
                    }),
                TextInput::make('total_amount')
                    ->label('Total Amount (Taxable + Tax)')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0)
                    ->extraInputAttributes(['class' => 'font-bold']),
            ]);
    }
}