<?php

namespace App\Filament\Resources\Holidays\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class HolidayForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                DatePicker::make('date')
                    ->default(now()->timezone('Asia/Kolkata')->toDateString())
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Toggle::make('is_restricted')
                    ->required(),
            ]);
    }
}
