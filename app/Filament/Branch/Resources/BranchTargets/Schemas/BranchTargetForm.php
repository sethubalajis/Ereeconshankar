<?php

namespace App\Filament\Branch\Resources\BranchTargets\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BranchTargetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required(),
                DatePicker::make('target_month')
                    ->required()
                    ->default(now()->timezone('Asia/Kolkata')),
                TextInput::make('target_amount')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
                TextInput::make('achieved_amount')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
            ]);
    }
}
