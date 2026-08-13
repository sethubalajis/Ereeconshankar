<?php

namespace App\Filament\Branch\Resources\PromotionTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PromotionTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->default(fn () => 'PROMO-' . strtoupper(\Illuminate\Support\Str::random(6)))
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(ignoreRecord: true),
                \Filament\Forms\Components\Select::make('discount_type')
                    ->options([
                        'percentage' => 'Percentage',
                        'value' => 'Value',
                    ])
                    ->default('percentage')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
