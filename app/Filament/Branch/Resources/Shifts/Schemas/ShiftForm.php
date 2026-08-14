<?php

namespace App\Filament\Branch\Resources\Shifts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ShiftForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TimePicker::make('start_time')
                    ->required()
                    ->seconds(false)
                    ->native(false),
                TimePicker::make('end_time')
                    ->required()
                    ->seconds(false)
                    ->native(false),
                TextInput::make('break_time')
                    ->label('Break Time (HH:MM)')
                    ->placeholder('00:30')
                    ->formatStateUsing(fn (?string $state): ?string => filled($state) ? substr($state, 0, 5) : null)
                    ->dehydrateStateUsing(function (?string $state): ?string {
                        if (blank($state) || ! str_contains($state, ':')) {
                            return null;
                        }

                        [$hours, $minutes] = explode(':', $state, 2);

                        return sprintf('%02d:%02d:00', (int) $hours, (int) $minutes);
                    })
                    ->rule('regex:/^([0-1]?\\d|2[0-3]):[0-5]\\d$/')
                    ->validationMessages([
                        'regex' => 'Break time must be in HH:MM format (example: 00:10, 01:15).',
                    ])
                    ->nullable(),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
