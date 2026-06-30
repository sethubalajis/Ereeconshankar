<?php

namespace App\Filament\Resources\CustomerFeedback\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CustomerFeedbackForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('customer_id')->relationship('customer', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name'),
                TextInput::make('rating')
                    ->required()
                    ->numeric()
                    ->default(5),
                Textarea::make('comments')
                    ->columnSpanFull(),
                DatePicker::make('feedback_date')
                    ->required(),
            ]);
    }
}
