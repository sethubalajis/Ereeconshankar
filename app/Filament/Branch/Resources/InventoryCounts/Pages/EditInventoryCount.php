<?php

namespace App\Filament\Branch\Resources\InventoryCounts\Pages;

use App\Filament\Branch\Resources\InventoryCounts\InventoryCountResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInventoryCount extends EditRecord
{
    protected static string $resource = InventoryCountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
