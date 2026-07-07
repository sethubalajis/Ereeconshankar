<?php

namespace App\Filament\Resources\Grns\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;
use App\Models\PurchaseOrder;

class GrnForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('purchase_order_id')
                    ->relationship('purchaseOrder', 'po_number')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $po = PurchaseOrder::with('items')->find($state);
                            if ($po) {
                                $set('vendor_id', $po->vendor_id);
                                $set('branch_id', $po->branch_id);
                                $items = $po->toArray()['items'] ?? [];
                                $formattedItems = [];
                                foreach($items as $item) {
                                    $formattedItems[(string) str()->uuid()] = $item;
                                }
                                $set('received_items', $formattedItems);
                            }
                        }
                    }),
                \Filament\Forms\Components\Select::make('vendor_id')->relationship('vendor', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('warehouse_id')->relationship('warehouse', 'name')
                    ->required(),
                TextInput::make('grn_number')
                    ->default('Auto-generated')
                    ->disabled()
                    ->dehydrated(false),
                DatePicker::make('receive_date')
                    ->required(),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'received' => 'Received',
                        'inspected' => 'Inspected',
                        'partially_received' => 'Partially Received',
                        'rejected' => 'Rejected',
                    ])
                    ->required()
                    ->default('received'),
                    
                Section::make('Received Items')
                    ->schema([
                        \Filament\Forms\Components\Repeater::make('received_items')
                            ->label('Items')
                            ->schema([
                                \Filament\Forms\Components\Select::make('product_id')
                                    ->options(\App\Models\Product::pluck('name', 'id'))
                                    ->required()
                                    ->live(),
                                \Filament\Forms\Components\Select::make('product_variant_id')
                                    ->options(function (callable $get) {
                                        if (!$get('product_id')) return [];
                                        return \App\Models\ProductVariant::where('product_id', $get('product_id'))->pluck('sku', 'id');
                                    })
                                    ->live(onBlur: true),
                                \Filament\Forms\Components\TextInput::make('quantity')
                                    ->required()
                                    ->numeric()
                                    ->default(1)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $unitPrice = (float)($get('unit_price') ?: 0);
                                        $set('total_price', $unitPrice * (float)$state);
                                    }),
                                \Filament\Forms\Components\TextInput::make('unit_price')
                                    ->required()
                                    ->numeric()
                                    ->default(0.0)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $qty = (float)($get('quantity') ?: 1);
                                        $set('total_price', (float)$state * $qty);
                                    }),
                                \Filament\Forms\Components\TextInput::make('total_price')
                                    ->required()
                                    ->numeric()
                                    ->default(0.0),
                            ])
                            ->columns(5)
                            ->columnSpanFull()
                    ])
                    ->columnSpan('full')
                    ->columnSpan('full')
            ]);
    }
}
