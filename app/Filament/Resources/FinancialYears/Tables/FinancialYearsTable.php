<?php

namespace App\Filament\Resources\FinancialYears\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FinancialYearsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean(),
                IconColumn::make('is_closed')
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
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\Action::make('closeYear')
                    ->label('Close Year')
                    ->icon('heroicon-o-lock-closed')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->hidden(fn ($record) => $record->is_closed)
                    ->form([
                        \Filament\Forms\Components\Select::make('retained_earnings_account_id')
                            ->label('Retained Earnings Account')
                            ->options(\App\Models\ChartOfAccount::pluck('account_name', 'id'))
                            ->searchable()
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'is_closed' => true,
                            'is_active' => false,
                        ]);

                        $nextStartDate = \Illuminate\Support\Carbon::parse($record->end_date)->addDay();
                        $nextEndDate = $nextStartDate->copy()->addYear()->subDay();
                        $nextYearName = $nextStartDate->format('Y') . '-' . $nextEndDate->format('y');
                        
                        \App\Models\FinancialYear::create([
                            'company_id' => $record->company_id,
                            'name' => $nextYearName,
                            'start_date' => $nextStartDate,
                            'end_date' => $nextEndDate,
                            'is_active' => true,
                            'is_closed' => false,
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Year Closed')
                            ->body('Financial year closed and next year initialized.')
                            ->success()
                            ->send();
                    }),
                \Filament\Actions\Action::make('reopenYear')
                    ->label('Reopen Year')
                    ->icon('heroicon-o-lock-open')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->is_closed) // Ideally restricted by a policy or role
                    ->action(function ($record) {
                        $record->update([
                            'is_closed' => false,
                        ]);
                        \Filament\Notifications\Notification::make()
                            ->title('Year Reopened')
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
