<?php

namespace App\Filament\Resources\TechnicianVisits\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TechnicianVisitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('service_assignment_id')->relationship('serviceAssignment', 'status')
                    ->required(),
                DatePicker::make('visit_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required(),
                Textarea::make('remarks')
                    ->columnSpanFull(),
            ]);
    }
}
