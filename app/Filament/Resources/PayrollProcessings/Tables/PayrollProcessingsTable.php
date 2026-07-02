<?php

namespace App\Filament\Resources\PayrollProcessings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PayrollProcessingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('payrollCycle.name')
                    ->sortable(),
                TextColumn::make('employee.first_name')
                    ->sortable(),
                TextColumn::make('gross_pay')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_deductions')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('net_pay')
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
            ])
            ->filters([
                //
            ])
            ->recordActions([
                \Filament\Actions\Action::make('download')
                    ->label('Download Payslip')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function ($record) {
                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.payslip', ['record' => $record]);
                        return response()->streamDownload(fn () => print($pdf->output()), 'payslip-' . ($record->employee->employee_code ?? 'EMP') . '-' . ($record->payrollCycle->name ?? 'cycle') . '.pdf');
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
