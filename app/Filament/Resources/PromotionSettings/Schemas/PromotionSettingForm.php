<?php

namespace App\Filament\Resources\PromotionSettings\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PromotionSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('offer_type')
                    ->required(),
                TextInput::make('priority')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_stackable')
                    ->required(),
                Toggle::make('is_exclusive')
                    ->required(),
                Toggle::make('best_price_only')
                    ->required(),
                TextInput::make('exclusions'),
                TextInput::make('stack_group'),
                TextInput::make('eligible_customers'),
                TextInput::make('eligible_customer_groups'),
                TextInput::make('eligible_memberships'),
                TextInput::make('eligible_products'),
                TextInput::make('eligible_categories'),
                TextInput::make('eligible_brands'),
                TextInput::make('eligible_branches'),
                TextInput::make('min_quantity')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('max_quantity')
                    ->numeric(),
                TextInput::make('min_bill_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('max_discount')
                    ->numeric(),
                TextInput::make('usage_limit')
                    ->numeric(),
                TextInput::make('customer_usage_limit')
                    ->numeric(),
                DateTimePicker::make('start_date'),
                DateTimePicker::make('end_date'),
            ]);
    }
}
