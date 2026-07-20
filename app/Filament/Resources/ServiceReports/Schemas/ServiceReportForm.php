<?php

namespace App\Filament\Resources\ServiceReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ServiceReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('service_request_id')->relationship('serviceRequest', 'request_number')
                    ->required(),
                Textarea::make('resolution_details')
                    ->columnSpanFull(),
                DatePicker::make('report_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required(),
            ]);
    }
}
