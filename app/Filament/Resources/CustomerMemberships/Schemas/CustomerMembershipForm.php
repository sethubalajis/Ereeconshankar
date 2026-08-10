<?php

namespace App\Filament\Resources\CustomerMemberships\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CustomerMembershipForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                \Filament\Forms\Components\Select::make('membership_plan_id')
                    ->relationship('membershipPlan', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('membership_number')
                    ->disabled()
                    ->dehydrated(false)
                    ->placeholder('Auto-generated on save'),
                DatePicker::make('start_date')
                    ->default(now('Asia/Kolkata'))
                    ->required(),
                DatePicker::make('end_date')
                    ->default(now('Asia/Kolkata')->addYear())
                    ->required(),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'Active' => 'Active',
                        'Expired' => 'Expired',
                        'Cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('Active'),
                \Filament\Forms\Components\Select::make('created_by')
                    ->relationship('createdBy', 'name')
                    ->searchable()
                    ->preload(),
                \Filament\Forms\Components\Select::make('updated_by')
                    ->relationship('updatedBy', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }
}
