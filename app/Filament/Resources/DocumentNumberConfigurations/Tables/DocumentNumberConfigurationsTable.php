<?php

namespace App\Filament\Resources\DocumentNumberConfigurations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentNumberConfigurationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company.name')
                    ->label('Company')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('document_type')
                    ->label('Document Type')
                    ->formatStateUsing(function ($state) {
                        $options = [
                            'App\Models\PosSale' => 'POS Sale',
                            'App\Models\PurchaseOrder' => 'Purchase Order',
                            'App\Models\PurchaseRequisition' => 'Purchase Requisition',
                            'App\Models\InventoryCount' => 'Inventory Count',
                            'App\Models\StockTransfer' => 'Stock Transfer',
                            'App\Models\TransferReceipt' => 'Transfer Receipt',
                            'App\Models\TaxInvoice' => 'Tax Invoice',
                            'App\Models\Receipt' => 'Receipt',
                            'App\Models\Grn' => 'GRN',
                            'App\Models\EmployeeDocument' => 'Employee Document',
                            'App\Models\Quotation' => 'Quotation',
                            'App\Models\CreditNote' => 'Credit Note',
                            'App\Models\DebitNote' => 'Debit Note',
                            'App\Models\JournalEntry' => 'Journal Entry',
                            'App\Models\Payment' => 'Payment',
                        ];
                        return $options[$state] ?? class_basename($state);
                    })
                    ->searchable(),
                TextColumn::make('prefix')
                    ->searchable(),
                TextColumn::make('suffix')
                    ->searchable(),
                IconColumn::make('include_financial_year')
                    ->boolean(),
                TextColumn::make('financial_year_format')
                    ->searchable(),
                TextColumn::make('separator')
                    ->searchable(),
                TextColumn::make('number_padding')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('starting_number')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('reset_frequency')
                    ->searchable(),
                IconColumn::make('branch_wise')
                    ->boolean(),
                IconColumn::make('is_active')
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