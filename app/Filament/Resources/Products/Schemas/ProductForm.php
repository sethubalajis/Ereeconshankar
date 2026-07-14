<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('brand_id')->relationship('brand', 'name'),
                \Filament\Forms\Components\Select::make('product_category_id')->relationship('productCategory', 'name'),
                \Filament\Forms\Components\Select::make('product_sub_category_id')->relationship('productSubCategory', 'name'),
                \Filament\Forms\Components\Select::make('product_unit_id')->relationship('productUnit', 'name'),
                \Filament\Forms\Components\Select::make('hsn_code_id')->relationship('hsnCode', 'hsn_code'),
                \Filament\Forms\Components\Select::make('tax_group_id')->relationship('taxGroup', 'name'),
                TextInput::make('name')
                    ->live(onBlur: true)
                    ->required(),
                TextInput::make('code')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('base_price')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('₹'),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
