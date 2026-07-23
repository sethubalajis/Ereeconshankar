<?php

namespace App\Filament\Resources\PosSales\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PosSalesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('branch.name')
                    ->sortable(),
                TextColumn::make('posTerminal.terminal_name')
                    ->sortable(),
                TextColumn::make('customer.name')
                    ->sortable(),
                TextColumn::make('invoice_number')
                    ->searchable(),
                TextColumn::make('sale_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->numeric()
                    ->prefix('₹ ')
                    ->sortable(),
                TextColumn::make('tax_amount')
                    ->numeric()
                    ->prefix('₹ ')
                    ->sortable(),
                TextColumn::make('discount_amount')
                    ->numeric()
                    ->prefix('₹ ')
                    ->sortable(),
                TextColumn::make('net_amount')
                    ->numeric()
                    ->prefix('₹ ')
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
            ])
            ->filters([
                //
            ])
            ->defaultSort('created_at', 'desc')
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
