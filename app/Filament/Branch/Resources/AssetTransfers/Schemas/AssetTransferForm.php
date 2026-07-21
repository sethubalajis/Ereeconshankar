<?php

namespace App\Filament\Branch\Resources\AssetTransfers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AssetTransferForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('asset_id')->relationship('asset', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('from_branch_id')->relationship('fromBranch', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('to_branch_id')->relationship('toBranch', 'name')
                    ->required(),
                DatePicker::make('transfer_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required(),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'in_transit' => 'In Transit',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('pending'),
            ]);
    }
}
