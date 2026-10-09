<?php

namespace App\Filament\Resources\MerchandisePlannings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MerchandisePlanningForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('financial_year_id')
                    ->relationship('financialYear', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('season_id')
                    ->relationship('season', 'name')
                    ->required(),
                TextInput::make('target_sales')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
                TextInput::make('planned_budget')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
                \Filament\Forms\Components\Repeater::make('items')
                    ->relationship()
                    ->itemLabel(fn (array $state): ?string => 
                        isset($state['product_id']) && $state['product_id'] 
                            ? 'Item: ' . (\App\Models\Product::find($state['product_id'])?->name ?? 'Product #' . $state['product_id']) 
                            : 'New Item'
                    )
                    ->collapsible()
                    ->cloneable()
                    ->addActionLabel('+ Add Merchandise Item')
                    ->schema([
                        \Filament\Forms\Components\Select::make('product_id')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live(),
                        \Filament\Forms\Components\Select::make('product_variant_id')
                            ->relationship('productVariant', 'id')
                            ->searchable(),
                        \Filament\Forms\Components\Select::make('style_id')
                            ->relationship('style', 'name')
                            ->searchable(),
                        \Filament\Forms\Components\Select::make('design_id')
                            ->relationship('design', 'name')
                            ->searchable(),
                        \Filament\Forms\Components\Select::make('color_id')
                            ->relationship('color', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->hex_code ? "{$record->name} ({$record->hex_code})" : $record->name)
                            ->searchable(),
                        \Filament\Forms\Components\Select::make('size_id')
                            ->relationship('size', 'name')
                            ->searchable(),
                        \Filament\Forms\Components\TextInput::make('planned_quantity')
                            ->numeric()
                            ->required()
                            ->default(0),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}