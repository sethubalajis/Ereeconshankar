<?php

namespace App\Filament\Resources\Payslips\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PayslipsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('payrollProcessing.status')
                    ->sortable(),
                TextColumn::make('payslip_number')
                    ->searchable(),
                TextColumn::make('document_path')
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
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function ($record) {
                        if (! \Illuminate\Support\Facades\Storage::disk('public')->exists($record->document_path)) {
                            \Filament\Notifications\Notification::make()
                                ->title('File not found')
                                ->body('The payslip document is missing from the server.')
                                ->danger()
                                ->send();
                            return;
                        }
                        return response()->download(storage_path('app/public/' . $record->document_path));
                    })
                    ->visible(fn ($record) => $record->document_path !== null),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
