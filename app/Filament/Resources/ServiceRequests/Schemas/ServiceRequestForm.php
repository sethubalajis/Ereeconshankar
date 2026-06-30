<?php

namespace App\Filament\Resources\ServiceRequests\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ServiceRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('customer_id')->relationship('customer', 'name')
                    ->required(),
                TextInput::make('request_number')
                    ->required(),
                DatePicker::make('request_date')
                    ->required(),
                Textarea::make('issue_description')
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->required()
                    ->default('open'),
            ]);
    }
}
