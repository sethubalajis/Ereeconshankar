<?php

namespace App\Filament\Resources\InventoryReconciliations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InventoryReconciliationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('inventoryCount.count_code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->sortable(),
                TextColumn::make('productVariant.sku')
                    ->sortable(),
                TextColumn::make('system_qty')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('physical_qty')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('variance')
                    ->numeric()
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
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('updated_at', 'desc');
    }
}
