<?php

namespace App\Filament\Resources\DocumentNumberConfigurations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DocumentNumberConfigurationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Configuration')
                    ->columns(2)
                    ->schema([
                        \Filament\Forms\Components\Select::make('company_id')
                            ->relationship('company', 'name')
                            ->required()
                            ->live(),
                        \Filament\Forms\Components\Select::make('document_type')
                            ->options(function () {
                                return [
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
                            })
                            ->searchable()
                            ->createOptionForm([
                                \Filament\Forms\Components\TextInput::make('new_document_type')
                                    ->label('New Document Type')
                                    ->required(),
                            ])
                            ->createOptionUsing(function (array $data) {
                                return $data['new_document_type'];
                            })
                            ->getOptionLabelUsing(function ($value) {
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
                                return $options[$value] ?? $value;
                            })
                            ->required()
                            ->live(),
                        \Filament\Forms\Components\TextInput::make('prefix')
                            ->live(),
                        \Filament\Forms\Components\TextInput::make('suffix')
                            ->live(),
                        \Filament\Forms\Components\Select::make('separator')
                            ->options([
                                '/' => 'Slash (/)',
                                '-' => 'Dash (-)',
                                '_' => 'Underscore (_)',
                                '.' => 'Dot (.)',
                                '' => 'None',
                            ])
                            ->default('/')
                            ->live(),
                        \Filament\Forms\Components\TextInput::make('number_padding')
                            ->required()
                            ->numeric()
                            ->default(6)
                            ->live(),
                        \Filament\Forms\Components\TextInput::make('starting_number')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->live(),
                        \Filament\Forms\Components\Select::make('reset_frequency')
                            ->options([
                                'yearly' => 'Yearly',
                                'monthly' => 'Monthly',
                                'never' => 'Never',
                            ])
                            ->required()
                            ->default('yearly')
                            ->live(),
                        Toggle::make('include_financial_year')
                            ->default(false)
                            ->live(),
                        \Filament\Forms\Components\Select::make('financial_year_format')
                            ->options([
                                'YYYY-YYYY' => 'YYYY-YYYY (e.g. 2026-2027)',
                                'YYYY-YY' => 'YYYY-YY (e.g. 2026-27)',
                                'YY-YY' => 'YY-YY (e.g. 26-27)',
                                'YYYY' => 'YYYY (e.g. 2026)',
                                'YY' => 'YY (e.g. 26)',
                            ])
                            ->visible(fn ($get) => $get('include_financial_year'))
                            ->live(),
                        Toggle::make('branch_wise')
                            ->default(false),
                        Toggle::make('is_active')
                            ->default(true),
                    ]),

                \Filament\Schemas\Components\Section::make('Live Preview')
                    ->schema([
                        \Filament\Forms\Components\Placeholder::make('preview')
                            ->label('Example Document Number')
                            ->content(function ($get) {
                                $prefix = $get('prefix');
                                $suffix = $get('suffix');
                                $includeFy = $get('include_financial_year');
                                $fyFormat = $get('financial_year_format');
                                $separator = $get('separator') ?? '';
                                $padding = (int) ($get('number_padding') ?: 6);
                                $starting = (int) ($get('starting_number') ?: 1);

                                $fyString = '';
                                if ($includeFy) {
                                    $startYear = now()->format('Y');
                                    $endYear = now()->addYear()->format('Y');
                                    if ($fyFormat === 'YYYY-YYYY') {
                                        $fyString = $startYear . '-' . $endYear;
                                    } elseif ($fyFormat === 'YY-YY') {
                                        $fyString = substr($startYear, 2) . '-' . substr($endYear, 2);
                                    } elseif ($fyFormat === 'YYYY') {
                                        $fyString = $startYear;
                                    } elseif ($fyFormat === 'YY') {
                                        $fyString = substr($startYear, 2);
                                    } else {
                                        $fyString = $startYear . '-' . substr($endYear, 2);
                                    }
                                }

                                $numStr = str_pad((string)$starting, $padding, '0', STR_PAD_LEFT);
                                $parts = [];
                                if (!empty($prefix)) $parts[] = $prefix;
                                if (!empty($fyString)) $parts[] = $fyString;
                                $parts[] = $numStr;
                                if (!empty($suffix)) $parts[] = $suffix;

                                return implode($separator, $parts);
                            }),
                    ])
            ]);
    }
}