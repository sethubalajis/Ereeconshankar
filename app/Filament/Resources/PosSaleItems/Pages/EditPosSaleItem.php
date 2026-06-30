<?php

namespace App\Filament\Resources\PosSaleItems\Pages;

use App\Filament\Resources\PosSaleItems\PosSaleItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPosSaleItem extends EditRecord
{
    protected static string $resource = PosSaleItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
