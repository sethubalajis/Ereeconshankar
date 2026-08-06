<?php

namespace App\Filament\Resources\Stocks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StocksTable
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
                TextColumn::make('quantity')
                    ->label('Total Qty')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('reserved_qty')
                    ->label('Reserved Qty')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('available_qty')
                    ->label('Available Qty')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('avg_cost')
                    ->prefix('₹')
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
