<?php

namespace App\Filament\Resources\ContraEntries\Pages;

use App\Filament\Resources\ContraEntries\ContraEntryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContraEntry extends EditRecord
{
    protected static string $resource = ContraEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
