<?php

namespace App\Filament\Resources\Employees\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->sortable(),
                TextColumn::make('department.name')
                    ->sortable(),
                TextColumn::make('designation.name')
                    ->sortable(),
                TextColumn::make('branch.name')
                    ->sortable(),
                TextColumn::make('employee_code')
                    ->searchable(),
                TextColumn::make('biometric_code')
                    ->searchable(),
                TextColumn::make('first_name')
                    ->searchable(),
                TextColumn::make('last_name')
                    ->searchable(),
                TextColumn::make('dob')
                    ->date()
                    ->sortable(),
                TextColumn::make('joining_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('pan_number')
                    ->searchable(),
                TextColumn::make('pf_number')
                    ->searchable(),
                TextColumn::make('esi_number')
                    ->searchable(),
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
