<?php

namespace App\Filament\Resources\StockValuations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StockValuationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $totalValue = \Illuminate\Support\Facades\DB::table('stocks')
                                ->where('branch_id', $state)
                                ->select(\Illuminate\Support\Facades\DB::raw('SUM(quantity * avg_cost) as total'))
                                ->value('total');
                            $set('total_value', (float) $totalValue);
                        }
                    }),
                DatePicker::make('valuation_date')
                    ->required()
                    ->default(now()->timezone('Asia/Kolkata')),
                TextInput::make('total_value')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
