<?php

namespace App\Filament\Resources\Rfqs\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RfqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('purchase_requisition_id')
                    ->relationship('purchaseRequisition', 'requisition_number')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->requisition_number ?? ($record->subject ?? "Requisition #{$record->id}")),
                TextInput::make('rfq_number')
                    ->default('Auto-generated')
                    ->disabled()
                    ->dehydrated(false),
                DatePicker::make('rfq_date')
                    ->required()
                    ->default(now()->timezone('Asia/Kolkata')),
                DatePicker::make('submission_deadline')
                    ->default(now()->timezone('Asia/Kolkata')),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'open' => 'Open',
                        'closed' => 'Closed',
                        'awarded' => 'Awarded',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('open'),
            ]);
    }
}
