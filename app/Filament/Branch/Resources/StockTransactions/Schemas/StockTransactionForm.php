<?php

namespace App\Filament\Branch\Resources\StockTransactions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StockTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('stock_id')
                    ->relationship('stock', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->product->name ?? 'Stock #'.$record->id)
                    ->preload()
                    ->required(),
                \Filament\Forms\Components\Select::make('transaction_type')
                    ->options([
                        'in' => 'Inward (In)',
                        'out' => 'Outward (Out)',
                    ])
                    ->required(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                \Filament\Forms\Components\TextInput::make('reference')
                    ->required(),
                \Filament\Forms\Components\Select::make('reference_type')
                    ->options([
                        'App\Models\PosSale' => 'POS Sale',
                        'App\Models\PosReturn' => 'POS Return',
                        'App\Models\PurchaseOrder' => 'Purchase Order',
                        'App\Models\PurchaseReturn' => 'Purchase Return',
                        'App\Models\StockAdjustment' => 'Stock Adjustment',
                        'App\Models\StockTransfer' => 'Stock Transfer',
                        'App\Models\WarehouseTransfer' => 'Warehouse Transfer',
                        'App\Models\Grn' => 'GRN',
                    ])
                    ->searchable(),
            ]);
    }
}
