<?php

namespace App\Filament\Resources\DocumentNumberConfigurations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentNumberConfigurationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('document_type')
                    ->searchable(),
                TextColumn::make('prefix')
                    ->searchable(),
                TextColumn::make('suffix')
                    ->searchable(),
                IconColumn::make('include_financial_year')
                    ->boolean(),
                TextColumn::make('financial_year_format')
                    ->searchable(),
                TextColumn::make('separator')
                    ->searchable(),
                TextColumn::make('number_padding')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('starting_number')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('reset_frequency')
                    ->searchable(),
                IconColumn::make('branch_wise')
                    ->boolean(),
                IconColumn::make('is_active')
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
