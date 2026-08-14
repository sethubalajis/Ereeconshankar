<?php

namespace App\Filament\Branch\Resources\PromotionSettings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PromotionSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('offer_type')
                    ->searchable(),
                TextColumn::make('priority')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_stackable')
                    ->boolean(),
                IconColumn::make('is_exclusive')
                    ->boolean(),
                IconColumn::make('best_price_only')
                    ->boolean(),
                TextColumn::make('stack_group')
                    ->searchable(),
                TextColumn::make('min_quantity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('max_quantity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('min_bill_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('max_discount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('usage_limit')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('customer_usage_limit')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('start_date')
                    ->dateTime()
                    ->timezone('Asia/Kolkata')
                    ->sortable(),
                TextColumn::make('end_date')
                    ->dateTime()
                    ->timezone('Asia/Kolkata')
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
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
