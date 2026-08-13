<?php

namespace App\Filament\Branch\Resources\PriceLists\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PriceListItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'priceListItems';
    protected static ?string $recordTitleAttribute = 'id';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'name', fn ($query) => $query->where('product_type', 1))
                    ->required()
                    ->live()
                    ->preload(),
                Forms\Components\Select::make('product_variant_id')
                    ->relationship('productVariant', 'sku', function ($query, $get) {
                        $productId = $get('product_id');
                        if ($productId) {
                            $query->where('product_id', $productId);
                        }
                    })
                    ->preload(),
                Forms\Components\TextInput::make('selling_price')
                    ->required()
                    ->numeric()
                    ->prefix('₹'),
                Forms\Components\TextInput::make('minimum_quantity')
                    ->numeric(),
                Forms\Components\TextInput::make('maximum_quantity')
                    ->numeric(),
                Forms\Components\DatePicker::make('start_date'),
                Forms\Components\DatePicker::make('end_date'),
                Forms\Components\Toggle::make('status')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name'),
                Tables\Columns\TextColumn::make('productVariant.sku'),
                Tables\Columns\TextColumn::make('selling_price')->money('INR'),
                Tables\Columns\ToggleColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make(),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
