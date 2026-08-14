<?php

namespace App\Filament\Branch\Resources\PromotionAudits\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PromotionAuditForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('pos_sale_id')
                    ->numeric(),
                TextInput::make('promotion_name')
                    ->required(),
                TextInput::make('promotion_type'),
                TextInput::make('priority')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_stackable')
                    ->required(),
                TextInput::make('status')
                    ->required(),
                TextInput::make('rejection_reason'),
                TextInput::make('discount_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('product_id')
                    ->numeric(),
            ]);
    }
}
