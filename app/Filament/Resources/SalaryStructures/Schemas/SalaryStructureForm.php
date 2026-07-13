<?php

namespace App\Filament\Resources\SalaryStructures\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SalaryStructureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                TextInput::make('basic_percentage')
                    ->required()
                    ->numeric()
                    ->default(40.0)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (\Filament\Forms\Set $set, \Filament\Forms\Get $get) => self::calculateSpecialAllowance($set, $get)),
                TextInput::make('hra_percentage')
                    ->required()
                    ->numeric()
                    ->default(20.0)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (\Filament\Forms\Set $set, \Filament\Forms\Get $get) => self::calculateSpecialAllowance($set, $get)),
                \Filament\Forms\Components\Repeater::make('other_allowances')
                    ->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('percentage')
                            ->required()
                            ->numeric()
                            ->default(0.0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (\Filament\Forms\Set $set, \Filament\Forms\Get $get) => self::calculateSpecialAllowance($set, $get)),
                    ])
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (\Filament\Forms\Set $set, \Filament\Forms\Get $get) => self::calculateSpecialAllowance($set, $get))
                    ->columnSpanFull()
                    ->default([]),
                TextInput::make('special_allowance')
                    ->required()
                    ->numeric()
                    ->readOnly()
                    ->default(40.0),
                Toggle::make('status')
                    ->required(),
            ]);
    }

    protected static function calculateSpecialAllowance(\Filament\Forms\Set $set, \Filament\Forms\Get $get): void
    {
        $basic = (float) $get('basic_percentage') ?? 40.0;
        $hra = (float) $get('hra_percentage') ?? 20.0;
        $otherAllowances = $get('other_allowances') ?? [];
        
        $otherTotal = 0;
        foreach ($otherAllowances as $allowance) {
            $otherTotal += (float) ($allowance['percentage'] ?? 0);
        }

        $specialAllowance = 100 - $basic - $hra - $otherTotal;
        $set('special_allowance', max(0, $specialAllowance));
    }
}
