<?php

namespace App\Filament\Resources\TransferRequests\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TransferRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('transfer_request_number')
                    ->label('Transfer Request Number')
                    ->disabled()
                    ->dehydrated(false)
                    ->placeholder('Auto-generated on save'),
                \Filament\Forms\Components\Select::make('from_branch_id')->relationship('fromBranch', 'name')
                    ->required()
                    ->label('Source Branch'),
                \Filament\Forms\Components\Select::make('to_branch_id')->relationship('toBranch', 'name')
                    ->required()
                    ->label('Destination Branch'),
                DatePicker::make('request_date')
                    ->required()
                    ->default(now()->timezone('Asia/Kolkata')),
                TextInput::make('reason')->label('Reason'),
                \Filament\Forms\Components\Textarea::make('remarks')->label('Remarks'),
                \Filament\Forms\Components\Hidden::make('requested_by')->default(fn () => auth()->id()),
                \Filament\Forms\Components\Hidden::make('financial_year_id')->default(1), // Should be dynamic
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'DRAFT' => 'Draft',
                        'PENDING_APPROVAL' => 'Pending Approval',
                        'APPROVED' => 'Approved',
                        'REJECTED' => 'Rejected',
                        'CANCELLED' => 'Cancelled',
                    ])
                    ->required()
                    ->default('DRAFT'),
                    
                \Filament\Forms\Components\Repeater::make('transferItems')
                    ->relationship('transferItems')
                    ->schema([
                        \Filament\Forms\Components\Select::make('product_id')
                            ->relationship('product', 'name')
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn ($set) => $set('product_variant_id', null))
                            ->label('Product'),
                        \Filament\Forms\Components\Select::make('product_variant_id')
                            ->relationship('productVariant', 'sku', fn (\Illuminate\Database\Eloquent\Builder $query, $get) => 
                                $query->where('product_id', $get('product_id'))
                            )
                            ->label('Variant'),
                        TextInput::make('requested_quantity')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->label('Requested Quantity'),
                        TextInput::make('unit_cost')
                            ->numeric()
                            ->label('Unit Cost'),
                        \Filament\Forms\Components\Textarea::make('remarks')->label('Remarks'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
