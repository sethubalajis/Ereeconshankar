<?php

namespace App\Filament\Branch\Resources\PosReturns\Schemas;

use App\Models\PosSale;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PosReturnForm
{
    public static function configure(Schema $schema): Schema
    {
        $updateReturnAmount = function (callable $set, array $items): void {
            $total = collect($items)->sum(function ($item) {
                return (float) ($item['line_total'] ?? 0);
            });

            $set('return_amount', round($total, 2));
        };

        return $schema
            ->components([
                Select::make('pos_sale_id')
                    ->label('POS Sale')
                    ->relationship('posSale', 'invoice_number')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) use ($updateReturnAmount): void {
                        if (! $state) {
                            $set('return_items', []);
                            $set('return_amount', 0);
                            return;
                        }

                        $sale = PosSale::query()
                            ->with(['posSaleItems.product', 'posSaleItems.productVariant'])
                            ->find($state);

                        $items = $sale?->posSaleItems->map(function ($item) {
                            $soldQty = (float) ($item->quantity ?? 0);
                            $unitPrice = (float) ($item->unit_price ?? 0);

                            return [
                                'pos_sale_item_id' => $item->id,
                                'product_name' => $item->product?->name,
                                'variant_sku' => $item->productVariant?->sku,
                                'sold_quantity' => $soldQty,
                                'return_quantity' => $soldQty,
                                'unit_price' => $unitPrice,
                                'line_total' => round($soldQty * $unitPrice, 2),
                                'return_reason' => 'Exchange Request',
                                'condition' => 'Good',
                            ];
                        })->values()->all() ?? [];

                        $set('return_items', $items);
                        $updateReturnAmount($set, $items);
                    })
                    ->required(),
                DatePicker::make('return_date')
                    ->required()
                    ->default(now()->timezone('Asia/Kolkata')),
                TextInput::make('return_amount')
                    ->numeric()
                    ->prefix('₹')
                    ->readOnly()
                    ->default(0.0),
                Repeater::make('return_items')
                    ->label('Return Items')
                    ->schema([
                        Hidden::make('pos_sale_item_id'),
                        TextInput::make('product_name')
                            ->label('Product')
                            ->readOnly(),
                        TextInput::make('variant_sku')
                            ->label('Variant SKU')
                            ->readOnly(),
                        TextInput::make('sold_quantity')
                            ->label('Sold Qty')
                            ->numeric()
                            ->readOnly(),
                        TextInput::make('return_quantity')
                            ->label('Return Qty')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(fn (callable $get) => (float) ($get('sold_quantity') ?? 0))
                            ->live()
                            ->afterStateUpdated(function ($state, callable $get, callable $set): void {
                                $soldQty = (float) ($get('sold_quantity') ?? 0);
                                $qty = max(0, min((float) ($state ?? 0), $soldQty));
                                $unitPrice = (float) ($get('unit_price') ?? 0);
                                $set('return_quantity', $qty);
                                $set('line_total', round($qty * $unitPrice, 2));
                            })
                            ->required(),
                        Select::make('return_reason')
                            ->label('Return Reason')
                            ->options([
                                'Wrong Color' => 'Wrong Color',
                                'Wrong Size' => 'Wrong Size',
                                'Wrong Design' => 'Wrong Design',
                                'Wrong Model' => 'Wrong Model',
                                'Customer Preference' => 'Customer Preference',
                                'Exchange Request' => 'Exchange Request',
                                'Damaged' => 'Damaged',
                                'Torn' => 'Torn',
                                'Broken' => 'Broken',
                                'Defective' => 'Defective',
                                'Used' => 'Used',
                                'Expired' => 'Expired',
                                'Manufacturing Defect' => 'Manufacturing Defect',
                            ])
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $saleableReasons = ['Wrong Color', 'Wrong Size', 'Wrong Design', 'Wrong Model', 'Customer Preference', 'Exchange Request'];
                                if (in_array($state, $saleableReasons)) {
                                    $set('condition', 'Good');
                                } else {
                                    $set('condition', 'Damaged');
                                }
                            })
                            ->required(),
                        Select::make('condition')
                            ->label('Condition')
                            ->options([
                                'Good' => 'Good (Saleable)',
                                'Damaged' => 'Damaged (Unsaleable)',
                            ])
                            ->required(),
                        TextInput::make('unit_price')
                            ->label('Unit Price')
                            ->numeric()
                            ->prefix('₹')
                            ->readOnly(),
                        TextInput::make('line_total')
                            ->label('Line Return Amount')
                            ->numeric()
                            ->prefix('₹')
                            ->readOnly(),
                    ])
                    ->columns(4)
                    ->addable(false)
                    ->deletable(false)
                    ->reorderable(false)
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) use ($updateReturnAmount): void {
                        $items = is_array($state) ? $state : [];
                        $updateReturnAmount($set, $items);
                    })
                    ->default([])
                    ->columnSpanFull(),
                Textarea::make('reason')
                    ->columnSpanFull(),
                Select::make('status')
                    ->options([
                        'completed' => 'Completed',
                        'pending' => 'Pending',
                        'cancelled' => 'Cancelled',
                        'refunded' => 'Refunded',
                    ])
                    ->required()
                    ->default('completed'),
            ]);
    }
}
