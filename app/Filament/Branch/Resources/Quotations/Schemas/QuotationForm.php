<?php

namespace App\Filament\Branch\Resources\Quotations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class QuotationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('vendor_id')->relationship('vendor', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('rfq_id')
                    ->relationship('rfq', 'rfq_number')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->rfq_number ?? "RFQ #{$record->id}"),
                DatePicker::make('quote_date')
                    ->required()
                    ->default(now()->timezone('Asia/Kolkata')),
                DatePicker::make('validity_date')
                    ->default(now()->timezone('Asia/Kolkata')),
                TextInput::make('total_amount')
                    ->required()
                    ->prefix('₹')
                    ->numeric()
                    ->default(0.0),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'received' => 'Received',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                    ])
                    ->required()
                    ->default('received'),
            ]);
    }
}
