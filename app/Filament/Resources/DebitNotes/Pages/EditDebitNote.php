<?php

namespace App\Filament\Resources\DebitNotes\Pages;

use App\Filament\Resources\DebitNotes\DebitNoteResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDebitNote extends EditRecord
{
    protected static string $resource = DebitNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
