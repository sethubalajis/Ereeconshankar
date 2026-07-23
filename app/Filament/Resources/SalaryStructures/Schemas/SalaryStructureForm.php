<?php

namespace App\Filament\Resources\SalaryStructures\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
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
                    ->required()
                    ->default(fn () => 'SAL-' . str_pad((string)(\App\Models\SalaryStructure::max('id') + 1), 4, '0', STR_PAD_LEFT))
                    ->disabled()
                    ->dehydrated()
                    ->unique(ignoreRecord: true),
                TextInput::make('basic_percentage')
                    ->required()
                    ->numeric()
                    ->suffix('%')
                    ->default(40.0)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, Get $get) => self::calculateSpecialAllowance($set, $get)),
                TextInput::make('hra_percentage')
                    ->required()
                    ->numeric()
                    ->suffix('%')
                    ->default(20.0)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, Get $get) => self::calculateSpecialAllowance($set, $get)),
                \Filament\Forms\Components\Repeater::make('other_allowances')
                    ->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('percentage')
                            ->required()
                            ->numeric()
                            ->default(0.0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, Get $get) => self::calculateSpecialAllowance($set, $get)),
                    ])
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, Get $get) => self::calculateSpecialAllowance($set, $get))
                    ->columnSpanFull()
                    ->default([]),
                TextInput::make('special_allowance')
                    ->required()
                    ->numeric()
                    ->suffix('%')
                    ->readOnly()
                    ->default(40.0),
                Toggle::make('status')
                    ->required(),
            ]);
    }

    protected static function calculateSpecialAllowance(Set $set, Get $get): void
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
