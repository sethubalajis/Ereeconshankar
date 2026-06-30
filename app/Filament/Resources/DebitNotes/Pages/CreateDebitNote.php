<?php

namespace App\Filament\Resources\DebitNotes\Pages;

use App\Filament\Resources\DebitNotes\DebitNoteResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDebitNote extends CreateRecord
{
    protected static string $resource = DebitNoteResource::class;
}
