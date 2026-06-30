<?php

namespace App\Filament\Resources\ComplaintTickets\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ComplaintTicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('customer_id')->relationship('customer', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('complaint_category_id')->relationship('complaintCategory', 'name')
                    ->required(),
                TextInput::make('ticket_number')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->required()
                    ->default('open'),
            ]);
    }
}
