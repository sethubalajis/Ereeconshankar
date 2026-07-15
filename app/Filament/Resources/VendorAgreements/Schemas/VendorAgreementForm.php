<?php

namespace App\Filament\Resources\VendorAgreements\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class VendorAgreementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('vendor_id')->relationship('vendor', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('title')
                    ->options([
                        'Non-Disclosure Agreement (NDA)' => 'Non-Disclosure Agreement (NDA)',
                        'Master Service Agreement (MSA)' => 'Master Service Agreement (MSA)',
                        'Service Level Agreement (SLA)' => 'Service Level Agreement (SLA)',
                        'Software License Agreement' => 'Software License Agreement',
                        'Consulting Agreement' => 'Consulting Agreement',
                        'General Vendor Agreement' => 'General Vendor Agreement',
                        'Other' => 'Other',
                    ])
                    ->searchable()
                    ->required(),
                DatePicker::make('start_date')
<<<<<<< HEAD
                    ->default(now()->timezone('Asia/Kolkata')),
                DatePicker::make('end_date')
                    ->default(now()->timezone('Asia/Kolkata')),
                \Filament\Forms\Components\FileUpload::make('document_path')
=======
                    ->default(now()),
                DatePicker::make('end_date')
                    ->default(now()),
                FileUpload::make('document_path')
>>>>>>> origin/main
                    ->label('Document')
                    ->directory('vendor-agreements')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'image/jpeg',
                        'image/png',
<<<<<<< HEAD
                    ])
                    ->maxSize(10240)
                    ->downloadable()
                    ->openable(),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'expired' => 'Expired',
                        'terminated' => 'Terminated',
                        'pending' => 'Pending',
                    ])
=======
                    ]),
                Toggle::make('status')
                    ->label('Active')
                    ->formatStateUsing(fn (mixed $state): bool => in_array($state, ['active', 1, true, '1'], true))
                    ->dehydrateStateUsing(fn (bool $state): string => $state ? 'active' : 'inactive')
>>>>>>> origin/main
                    ->required()
                    ->default(true),
            ]);
    }
}
