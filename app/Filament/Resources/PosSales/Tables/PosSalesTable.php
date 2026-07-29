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
                TextColumn::make('tax_amount')
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
            ])
            ->headerActions([
                \Filament\Actions\Action::make('download_overall_pdf')
                    ->label('Overall PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('primary')
                    ->action(function ($livewire) {
                        $records = $livewire->getFilteredTableQuery()->get();
                        return \App\Helpers\ReportPdfHelper::downloadListPdf('Overall POS Sales', $records, [
                            'Invoice No' => fn($r) => $r->invoice_number ?? '-',
                            'Branch' => fn($r) => $r->branch?->name ?? '-',
                            'Customer' => fn($r) => $r->customer?->name ?? 'Walk-in',
                            'Date' => fn($r) => $r->sale_date ? \Carbon\Carbon::parse($r->sale_date)->format('Y-m-d') : '-',
                            'Amount' => fn($r) => number_format($r->net_amount, 2),
                            'Status' => fn($r) => ucfirst($r->status),
                        ]);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
