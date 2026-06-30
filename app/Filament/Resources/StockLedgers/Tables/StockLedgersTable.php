<?php

namespace App\Filament\Resources\StockLedgers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StockLedgersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->sortable(),
                TextColumn::make('productVariant.sku')
                    ->sortable(),
                TextColumn::make('branch.name')
                    ->sortable(),
                TextColumn::make('warehouse.name')
                    ->sortable(),
                TextColumn::make('transaction_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('opening_qty')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('inward_qty')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('outward_qty')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('closing_qty')
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
            ]);
    }
}
