<?php

namespace App\Filament\Resources\EmployeeDocuments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmployeeDocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.first_name')
                    ->label('Employee')
                    ->formatStateUsing(fn ($state, $record) => $record->employee ? "{$record->employee->first_name} {$record->employee->last_name} ({$record->employee->employee_code})" : '-')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('document_type')
                    ->label('Document Type')
                    ->badge()
                    ->searchable(),
                TextColumn::make('document_path')
                    ->label('File')
                    ->formatStateUsing(fn ($state) => basename($state))
                    ->openUrlInNewTab(fn ($record) => asset('storage/' . $record->document_path)),
                IconColumn::make('is_verified')
                    ->label('Verified')
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