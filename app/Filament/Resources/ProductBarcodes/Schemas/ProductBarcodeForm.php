<?php

namespace App\Filament\Resources\ProductBarcodes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductBarcodeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')->relationship('product', 'name')
                    ->required(),
                Select::make('product_variant_id')->relationship('productVariant', 'sku'),
                TextInput::make('barcode')
                    ->required(),
                Select::make('barcode_type')
                    ->options([
                        'EAN13' => 'EAN-13',
                        'EAN8' => 'EAN-8',
                        'UPC' => 'UPC-A',
                        'CODE128' => 'Code 128',
                        'CODE39' => 'Code 39',
                        'ITF' => 'ITF',
                    ])
                    ->searchable()
                    ->native(false),
            ]);
    }
}
