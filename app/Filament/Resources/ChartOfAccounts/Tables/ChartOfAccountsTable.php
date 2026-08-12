<?php

namespace App\Filament\Resources\ChartOfAccounts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ChartOfAccountsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('accountGroup.name')
                    ->sortable(),
                TextColumn::make('account_code')
                    ->searchable(),
                TextColumn::make('account_name')
                    ->searchable(),
                TextColumn::make('account_type')
                    ->searchable(),
                TextColumn::make('opening_balance')
                    ->numeric()
                    ->prefix('₹ ')
                    ->sortable(),
                TextColumn::make('current_balance')
                    ->numeric()
                    ->prefix('₹ ')
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
