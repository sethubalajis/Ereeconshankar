<?php

namespace App\Filament\Branch\Resources\WarrantyClaims\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WarrantyClaimForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('customer_id')->relationship('customer', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('product_id')->relationship('product', 'name')
                    ->required(),
                TextInput::make('claim_number')
                    ->required(),
                DatePicker::make('claim_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required(),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'resolved' => 'Resolved',
                    ])
                    ->required()
                    ->default('pending'),
            ]);
    }
}
