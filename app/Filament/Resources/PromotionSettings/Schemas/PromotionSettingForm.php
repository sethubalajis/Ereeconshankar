<?php

namespace App\Filament\Resources\PromotionSettings\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PromotionSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('offer_type')
                    ->options(\App\Models\PromotionType::pluck('name', 'code'))
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
                Select::make('exclusions')
                    ->multiple()
                    ->options(\App\Models\PromotionType::pluck('name', 'code')),
                Select::make('stack_group')
                    ->options(\App\Models\PromotionSetting::whereNotNull('stack_group')->distinct()->pluck('stack_group', 'stack_group')),
                Select::make('eligible_customers')->multiple()->options(\App\Models\Customer::pluck('name', 'id')),
                Select::make('eligible_customer_groups')->multiple()->options(\App\Models\CustomerGroup::pluck('name', 'id')),
                Select::make('eligible_memberships')->multiple()->options(\App\Models\MembershipPlan::pluck('name', 'id')),
                Select::make('eligible_products')->multiple()->options(\App\Models\Product::pluck('name', 'id')),
                Select::make('eligible_categories')->multiple()->options(\App\Models\ProductCategory::pluck('name', 'id')),
                Select::make('eligible_brands')->multiple()->options(\App\Models\Brand::pluck('name', 'id')),
                Select::make('eligible_branches')->multiple()->options(\App\Models\Branch::pluck('name', 'id')),
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
                DateTimePicker::make('start_date')
                    ->timezone('Asia/Kolkata')
                    ->default(now('Asia/Kolkata')),
                DateTimePicker::make('end_date')
                    ->timezone('Asia/Kolkata')
                    ->default(now('Asia/Kolkata')),
            ]);
    }
}
