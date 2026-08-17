<?php

namespace App\Filament\Resources\StockReservations\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Builder;

class StockReservationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('reservation_number')
                    ->default(function () {
                        $prefix = 'RES-';
                        $lastReservation = \App\Models\StockReservation::orderBy('id', 'desc')->first();
                        if (!$lastReservation || !$lastReservation->reservation_number) {
                            return $prefix . '000001';
                        }
                        $number = (int) str_replace($prefix, '', $lastReservation->reservation_number);
                        return $prefix . str_pad($number + 1, 6, '0', STR_PAD_LEFT);
                    })
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(ignoreRecord: true),
                Select::make('product_id')
                    ->label('Product')
                    ->options(\App\Models\Product::pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(fn ($set) => $set('product_variant_id', null)),
                Select::make('product_variant_id')
                    ->label('Product Variant')
                    ->options(function ($get) {
                        $productId = $get('product_id');
                        if (!$productId) {
                            return [];
                        }
                        return \App\Models\ProductVariant::where('product_id', $productId)->pluck('sku', 'id');
                    })
                    ->searchable()
                    ->live(),
                Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->required(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Select::make('reservation_type')
                    ->options([
                        'Sales Order' => 'Sales Order',
                        'POS Hold' => 'POS Hold',
                        'Transfer Request' => 'Transfer Request',
                        'Service Request' => 'Service Request',
                    ])
                    ->required(),
                TextInput::make('reference_document'),
                DateTimePicker::make('reservation_date')
                    ->default(fn () => now()->timezone('Asia/Kolkata')->format('Y-m-d H:i:s')),
                DateTimePicker::make('expiry_date')
                    ->default(fn () => now()->timezone('Asia/Kolkata')->format('Y-m-d H:i:s')),
                TextInput::make('reference_id')
                    ->numeric(),
                Select::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Reserved' => 'Reserved',
                        'Released' => 'Released',
                        'Expired' => 'Expired',
                        'Completed' => 'Completed',
                    ])
                    ->required()
                    ->default('Pending'),
                Select::make('company_id')
                    ->relationship('company', 'name'),
                Select::make('warehouse_id')
                    ->relationship('warehouse', 'name'),
                Select::make('created_by')
                    ->relationship('creator', 'name'),
            ]);
    }
}
