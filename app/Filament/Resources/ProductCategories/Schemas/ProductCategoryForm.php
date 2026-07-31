<?php

namespace App\Filament\Resources\ProductCategories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('parent_id')
                    ->relationship('productCategory', 'name')
                    ->label('Product Category')
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required(),
                        \Filament\Forms\Components\Toggle::make('status')
                            ->default(true),
                    ]),
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->disabled()
                    ->dehydrated(false)
                    ->placeholder('Auto generated on creation'),
                Textarea::make('description')
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image(),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
