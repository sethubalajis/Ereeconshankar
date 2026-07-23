<?php

namespace App\Filament\Resources\TdsRecords\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TdsRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('payrollProcessing.payrollCycle.name')
                    ->label('Payroll Processing')
                    ->sortable(),
                TextColumn::make('employee.first_name')
                    ->sortable(),
                TextColumn::make('financialYear.name')
                    ->sortable(),
                TextColumn::make('tax_regime'),
                TextColumn::make('annual_gross_salary')
                    ->numeric()
                    ->prefix('₹ ')
                    ->sortable(),
                TextColumn::make('approved_tax_deductions')
                    ->numeric()
                    ->prefix('₹ ')
                    ->sortable(),
                TextColumn::make('taxable_income')
                    ->numeric()
                    ->prefix('₹ ')
                    ->sortable(),
                TextColumn::make('annual_income_tax')
                    ->numeric()
                    ->prefix('₹ ')
                    ->sortable(),
                TextColumn::make('monthly_tds')
                    ->numeric()
                    ->prefix('₹ ')
                    ->sortable(),
                TextColumn::make('remaining_tds')
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
