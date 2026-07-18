<?php

namespace App\Filament\Resources\GiftVouchers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class GiftVoucherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('voucher_code')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                DatePicker::make('expiry_date')
                    ->default(now()->timezone('Asia/Kolkata')),
                Toggle::make('is_redeemed')
                    ->required(),
            ]);
    }
}
