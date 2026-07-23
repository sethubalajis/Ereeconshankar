<?php

namespace App\Filament\Resources\PurchaseReturns\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use App\Models\Grn;

class PurchaseReturnForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('purchase_order_id')
                    ->relationship('purchaseOrder', 'po_number')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $po = \App\Models\PurchaseOrder::with('items')->find($state);
                            if ($po) {
                                $set('vendor_id', $po->vendor_id);
                                $set('branch_id', $po->branch_id);
                                
                                $items = $po->toArray()['items'] ?? [];
                                $formattedItems = [];
                                foreach($items as $item) {
                                    $formattedItems[(string) str()->uuid()] = $item;
                                }
                                $set('returned_items', $formattedItems);
                            }
                        }
                    }),
                \Filament\Forms\Components\Select::make('grn_id')
                    ->relationship(
                        name: 'grn',
                        titleAttribute: 'grn_number',
                        modifyQueryUsing: fn (\Illuminate\Database\Eloquent\Builder $query, callable $get) => 
                            $get('purchase_order_id') ? $query->where('purchase_order_id', $get('purchase_order_id')) : $query
                    )
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $grn = Grn::find($state);
                            if ($grn) {
                                $set('vendor_id', $grn->vendor_id);
                                $set('branch_id', $grn->branch_id);
                                $set('purchase_order_id', $grn->purchase_order_id);
                                
                                $items = $grn->received_items ?? [];
                                $formattedItems = [];
                                foreach($items as $item) {
                                    $formattedItems[(string) str()->uuid()] = $item;
                                }
                                $set('returned_items', $formattedItems);
                            }
                        }
                    }),
                \Filament\Forms\Components\Select::make('vendor_id')->relationship('vendor', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required(),
                DatePicker::make('return_date')
                    ->required()
                    ->default(now()->timezone('Asia/Kolkata')),
                TextInput::make('total_amount')
                    ->required()
                    ->prefix('₹')
                    ->numeric()
                    ->default(0.0),
                Textarea::make('reason')
                    ->columnSpanFull(),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'processed' => 'Processed',
                        'rejected' => 'Rejected',
                    ])
                    ->required()
                    ->default('pending'),
                    
                Section::make('Received Items from GRN')
                    ->schema([
                        \Filament\Forms\Components\Repeater::make('returned_items')
                            ->label('Items')
                            ->schema([
                                \Filament\Forms\Components\Select::make('product_id')
                                    ->options(\App\Models\Product::pluck('name', 'id'))
                                    ->live(),
                                \Filament\Forms\Components\Select::make('product_variant_id')
                                    ->options(function (callable $get) {
                                        if (!$get('product_id')) return [];
                                        return \App\Models\ProductVariant::where('product_id', $get('product_id'))->pluck('sku', 'id');
                                    }),
                                \Filament\Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->live(debounce: 500)
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $unitPrice = (float)($get('unit_price') ?: 0);
                                        $set('total_price', $unitPrice * (float)$state);
                                        $items = $get('../../returned_items') ?? [];
                                        $total = collect($items)->sum('total_price');
                                        $set('../../total_amount', $total);
                                    }),
                                \Filament\Forms\Components\TextInput::make('unit_price')
                                    ->numeric()
                                    ->prefix('₹')
                                    ->live(debounce: 500)
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $qty = (float)($get('quantity') ?: 1);
                                        $set('total_price', (float)$state * $qty);
                                        $items = $get('../../returned_items') ?? [];
                                        $total = collect($items)->sum('total_price');
                                        $set('../../total_amount', $total);
                                    }),
                                \Filament\Forms\Components\TextInput::make('total_price')
                                    ->numeric()
                                    ->prefix('₹'),
                            ])
                            ->columns(5)
                    ])
                    ->columnSpan('full')
                    ->visible(fn (callable $get) => filled($get('grn_id')) || filled($get('purchase_order_id'))),
            ]);
    }
}
