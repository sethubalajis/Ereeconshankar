<?php

namespace App\Filament\Branch\Resources\PayrollCycles\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PayrollCycleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                DatePicker::make('start_date')
                    ->default(now()->timezone('Asia/Kolkata')->toDateString())
                    ->required(),
                DatePicker::make('end_date')
                    ->default(now()->timezone('Asia/Kolkata')->toDateString())
                    ->required(),
                DatePicker::make('payment_date')
                    ->default(now()->timezone('Asia/Kolkata')->toDateString()),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'processed' => 'Processed',
                        'approved' => 'Approved',
                        'paid' => 'Paid',
                    ])
                    ->required()
                    ->default('draft'),
            ]);
    }
}
