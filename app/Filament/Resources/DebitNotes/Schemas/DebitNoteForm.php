<?php

namespace App\Filament\Resources\DebitNotes\Schemas;

use App\Models\PurchaseReturn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Schemas\Schema;

class DebitNoteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('purchase_return_id')
                    ->relationship('purchaseReturn', 'id')
                    ->label('Purchase Return')
                    ->getOptionLabelFromRecordUsing(fn (PurchaseReturn $record) => "PR-#{$record->id} (" . ($record->vendor?->name ?? 'N/A') . " - ₹" . number_format($record->total_amount, 2) . ")")
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set) {
                        if ($state) {
                            $pr = PurchaseReturn::find($state);
                            if ($pr) {
                                $set('vendor_id', $pr->vendor_id);
                                $set('amount', (float) $pr->total_amount);
                            }
                        }
                    })
                    ->required(),
                Select::make('vendor_id')
                    ->relationship('vendor', 'name')
                    ->label('Vendor')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('note_number')
                    ->label('Debit Note #')
                    ->default(fn () => 'DN-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4)))
                    ->required(),
                DatePicker::make('note_date')
                    ->default(now('Asia/Kolkata'))
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
                Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'issued' => 'Issued',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('issued'),
            ]);
    }
}