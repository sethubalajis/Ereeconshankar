<?php

namespace App\Filament\Branch\Resources\PayrollProcessings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
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
                    ->searchable()
                    ->sortable(),
                TextColumn::make('employee.first_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_working_days')
                    ->label('Working Days')
                    ->numeric(),
                TextColumn::make('effective_present_days')
                    ->label('Effective Present Days')
                    ->numeric(),
                TextColumn::make('missing_attendance_days')
                    ->label('Missing Attendance Days')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('absent_leave_days')
                    ->label('Absent / Leave Days')
                    ->numeric(),
                TextColumn::make('basic_pay')->numeric()->prefix('₹ ')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('hra')->numeric()->prefix('₹ ')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('special_allowance')->numeric()->prefix('₹ ')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('pf')->numeric()->prefix('₹ ')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('esi')->numeric()->prefix('₹ ')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('loss_of_pay')->numeric()->prefix('₹ ')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('extra_deductions')->numeric()->prefix('₹ ')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('bonuses')->numeric()->prefix('₹ ')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('incentives')->numeric()->prefix('₹ ')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('reimbursement')->numeric()->prefix('₹ ')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('professional_taxes')->numeric()->prefix('₹ ')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tds_records')->numeric()->prefix('₹ ')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('gross_pay')
                    ->numeric()
                    ->prefix('₹ ')
                    ->sortable(),
                TextColumn::make('total_deductions')
                    ->numeric()
                    ->prefix('₹ ')
                    ->sortable(),
                TextColumn::make('net_pay')
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
            ->defaultSort('id', 'desc')
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
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
