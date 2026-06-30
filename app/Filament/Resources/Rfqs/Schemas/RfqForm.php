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
                \Filament\Forms\Components\Select::make('purchase_requisition_id')->relationship('purchaseRequisition', 'status'),
                DatePicker::make('rfq_date')
                    ->required(),
                DatePicker::make('submission_deadline'),
                TextInput::make('status')
                    ->required()
                    ->default('open'),
            ]);
    }
}
