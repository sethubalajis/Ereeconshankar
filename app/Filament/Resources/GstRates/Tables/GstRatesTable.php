<?php

namespace App\Filament\Resources\GstRates\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GstRatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cgst_percentage')
                    ->numeric()
                    ->suffix('%')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sgst_percentage')
                    ->numeric()
                    ->suffix('%')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('igst_percentage')
                    ->numeric()
                    ->suffix('%')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cess_percentage')
                    ->numeric()
                    ->suffix('%')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('status')
                    ->boolean(),
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
