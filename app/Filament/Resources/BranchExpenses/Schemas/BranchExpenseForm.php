<?php

namespace App\Filament\Resources\BranchExpenses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BranchExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required(),
                DatePicker::make('expense_date')
                    ->required()
                    ->default(now()->timezone('Asia/Kolkata')),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
                Textarea::make('description')
                    ->columnSpanFull(),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'paid' => 'Paid',
                    ])
                    ->required()
                    ->default('pending'),
            ]);
    }
}
