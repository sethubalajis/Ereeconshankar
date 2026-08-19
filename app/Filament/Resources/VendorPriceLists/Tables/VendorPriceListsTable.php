<?php

namespace App\Filament\Resources\VendorPriceLists\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VendorPriceListsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vendor.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('productVariant.sku')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('agreed_price')
                    ->prefix('₹')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('valid_from')
                    ->date()
                    ->sortable(),
                TextColumn::make('valid_to')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
