<?php

namespace App\Filament\Resources\TransferApprovals\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TransferApprovalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('transfer_request_id')
                    ->label('Transfer Request Number')
                    ->relationship('transferRequest', 'transfer_request_number')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->transfer_request_number ?? $record->transfer_request_code ?? "Transfer Request #{$record->id}")
                    ->searchable()
                    ->preload()
                    ->required(),
                \Filament\Forms\Components\Select::make('approver_id')->relationship('approver', 'name'),
                DatePicker::make('approval_date')
                    ->default(now()->timezone('Asia/Kolkata')),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'PENDING' => 'Pending',
                        'APPROVED' => 'Approved',
                        'REJECTED' => 'Rejected',
                    ])
                    ->required()
                    ->default('PENDING'),
                \Filament\Forms\Components\TextInput::make('approved_quantity')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    // Column is NOT NULL (default 0); a cleared input must not send null
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? $state : 0)
                    ->label('Total Approved Qty'),
                \Filament\Forms\Components\Textarea::make('rejection_reason')->label('Rejection Reason'),
                \Filament\Forms\Components\Textarea::make('remarks')->label('Remarks'),
            ]);
    }
}
