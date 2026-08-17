<?php

namespace App\Filament\Branch\Resources\Quotations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class QuotationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('quotation_number')
                    ->default('Auto-generated')
                    ->disabled()
                    ->dehydrated(false),
                \Filament\Forms\Components\Select::make('vendor_id')->relationship('vendor', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('rfq_id')
                    ->relationship('rfq', 'rfq_number')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->rfq_number ?? "RFQ #{$record->id}"),
                DatePicker::make('quote_date')
                    ->required()
                    ->default(now()->timezone('Asia/Kolkata')),
                DatePicker::make('validity_date')
                    ->default(now()->timezone('Asia/Kolkata')),
                TextInput::make('total_amount')
                    ->required()
                    ->prefix('₹')
                    ->numeric()
                    ->default(0.0),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'received' => 'Received',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                    ])
                    ->required()
                    ->default('received'),
                \Filament\Forms\Components\Repeater::make('items')
                    ->relationship('items')
                    ->live()
                    ->afterStateUpdated(function (callable $get, callable $set) {
                        $items = $get('items') ?? [];
                        $total = collect($items)->sum('total_price');
                        $set('total_amount', $total);
                    })
                    ->schema([
                        \Filament\Forms\Components\Select::make('product_id')->relationship('product', 'name')
                            ->required()
                            ->live(),
                        \Filament\Forms\Components\Select::make('product_variant_id')
                            ->relationship('productVariant', 'sku')
                            ->options(function (callable $get) {
                                if (!$get('product_id')) return [];
                                return \App\Models\ProductVariant::where('product_id', $get('product_id'))->pluck('sku', 'id');
                            })
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                if ($state) {
                                    $variant = \App\Models\ProductVariant::find($state);
                                    if ($variant) {
                                        $set('unit_price', $variant->cost_price);
                                        $set('total_price', $variant->cost_price * (float)($get('quantity') ?: 1));
                                    }
                                }
                                $items = $get('../../items') ?? [];
                                $total = collect($items)->sum('total_price');
                                $set('../../total_amount', $total);
                            }),
                        \Filament\Forms\Components\TextInput::make('quantity')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->live(debounce: 500)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $unitPrice = (float)($get('unit_price') ?: 0);
                                $set('total_price', $unitPrice * (float)$state);
                                $items = $get('../../items') ?? [];
                                $total = collect($items)->sum('total_price');
                                $set('../../total_amount', $total);
                            }),
                        \Filament\Forms\Components\TextInput::make('unit_price')
                            ->required()
                            ->prefix('₹')
                            ->numeric()
                            ->default(0.0)
                            ->live(debounce: 500)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $qty = (float)($get('quantity') ?: 1);
                                $set('total_price', (float)$state * $qty);
                                $items = $get('../../items') ?? [];
                                $total = collect($items)->sum('total_price');
                                $set('../../total_amount', $total);
                            }),
                        \Filament\Forms\Components\TextInput::make('total_price')
                            ->required()
                            ->prefix('₹')
                            ->numeric()
                            ->default(0.0)
                            ->readOnly(),
                    ])
                    ->columns(5)
                    ->columnSpanFull()
                    ->defaultItems(1),
            ]);
    }
}
