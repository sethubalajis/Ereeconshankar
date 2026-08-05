<?php

namespace App\Filament\Resources\StockReservations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StockReservationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reservation_number')
                    ->searchable(),
                TextColumn::make('product.name')
                    ->sortable(),
                TextColumn::make('productVariant.sku')
                    ->sortable(),
                TextColumn::make('branch.name')
                    ->sortable(),
                TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('reservation_type')
                    ->searchable(),
                TextColumn::make('reference_document')
                    ->searchable(),
                TextColumn::make('reservation_date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('expiry_date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('reference_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('company.name')
                    ->sortable(),
                TextColumn::make('warehouse.name')
                    ->sortable(),
                TextColumn::make('creator.name')
                    ->sortable(),
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
