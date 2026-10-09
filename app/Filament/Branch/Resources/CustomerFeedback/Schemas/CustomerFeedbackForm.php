<?php

namespace App\Filament\Branch\Resources\CustomerFeedback\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CustomerFeedbackForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->label('Customer')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('rating')
                    ->label('Rating (1 - 5 Stars)')
                    ->options([
                        5 => '★★★★★ (5/5 - Excellent)',
                        4 => '★★★★☆ (4/5 - Good)',
                        3 => '★★★☆☆ (3/5 - Average)',
                        2 => '★★☆☆☆ (2/5 - Poor)',
                        1 => '★☆☆☆☆ (1/5 - Very Poor)',
                    ])
                    ->default(5)
                    ->required(),
                Textarea::make('comments')
                    ->label('Feedback / Comments')
                    ->columnSpanFull(),
                DatePicker::make('feedback_date')
                    ->label('Feedback Date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required(),
            ]);
    }
}