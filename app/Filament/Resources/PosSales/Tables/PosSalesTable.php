<?php

namespace App\Filament\Resources\PosSales\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PosSalesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('branch.name')
                    ->sortable(),
                TextColumn::make('posTerminal.terminal_name')
                    ->sortable(),
                TextColumn::make('customer.name')
                    ->sortable(),
                TextColumn::make('invoice_number')
                    ->searchable(),
                TextColumn::make('sale_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->numeric()
                    ->prefix('₹ ')
                    ->sortable(),
                TextColumn::make('taxable_amount')
                    ->numeric()
                    ->prefix('₹ ')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('cgst_amount')
                    ->numeric()
                    ->prefix('₹ ')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sgst_amount')
                    ->numeric()
                    ->prefix('₹ ')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('igst_amount')
                    ->numeric()
                    ->prefix('₹ ')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tax_amount')
                    ->label('Total Tax')
                    ->numeric()
                    ->prefix('₹ ')
                    ->sortable(),
                TextColumn::make('discount_amount')
                    ->numeric()
                    ->prefix('₹ ')
                    ->sortable(),
                TextColumn::make('net_amount')
                    ->numeric()
                    ->prefix('₹ ')
                    ->sortable(),

                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('email_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'sent', 'delivered' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    })
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
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),
                \Filament\Actions\Action::make('download_receipt')
                    ->label('Receipt')
                    ->icon('heroicon-o-document-text')
                    ->color('success')
                    ->action(function ($record) {
                        return \App\Helpers\ReportPdfHelper::downloadPosSaleReceiptPdf($record);
                    }),
                \Filament\Actions\Action::make('resend_email')
                    ->label('Resend Email')
                    ->icon('heroicon-o-envelope')
                    ->color('info')
                    ->visible(fn ($record) => $record->status === 'completed' && $record->customer_id)
                    ->action(function ($record) {
                        try {
                            \Illuminate\Support\Facades\Mail::to($record->customer->email)
                                ->send(new \App\Mail\PosInvoiceMail($record));
                            $record->update(['email_status' => 'sent']);
                            \Filament\Notifications\Notification::make()
                                ->title('Email sent successfully')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            $record->update(['email_status' => 'failed']);
                            \Filament\Notifications\Notification::make()
                                ->title('Failed to send email')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->headerActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
