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
                \Filament\Forms\Components\Select::make('transfer_request_id')->relationship('transferRequest', 'status')
                    ->required(),
                \Filament\Forms\Components\Select::make('approver_id')->relationship('approver', 'name'),
                DatePicker::make('approval_date'),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
            ]);
    }
}
