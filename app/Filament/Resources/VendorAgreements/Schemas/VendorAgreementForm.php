<?php

namespace App\Filament\Resources\VendorAgreements\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VendorAgreementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('vendor_id')->relationship('vendor', 'name')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                DatePicker::make('start_date'),
                DatePicker::make('end_date'),
                \Filament\Forms\Components\FileUpload::make('document_path')
                    ->directory('vendor-agreements')
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png'])
                    ->maxSize(10240)
                    ->downloadable()
                    ->openable(),
                TextInput::make('status')
                    ->required()
                    ->default('active'),
            ]);
    }
}
