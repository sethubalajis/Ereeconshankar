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
                TextColumn::make('basic_pay')->numeric()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('hra')->numeric()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('special_allowance')->numeric()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('pf')->numeric()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('esi')->numeric()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('loss_of_pay')->numeric()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('extra_deductions')->numeric()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('bonuses')->numeric()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('incentives')->numeric()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('reimbursement')->numeric()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('professional_taxes')->numeric()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tds_records')->numeric()->sortable()->toggleable(isToggledHiddenByDefault: true),
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
