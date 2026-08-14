<?php

namespace App\Filament\Branch\Resources\TransitInventories\Pages;

use App\Filament\Branch\Resources\TransitInventories\TransitInventoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTransitInventory extends EditRecord
{
    protected static string $resource = TransitInventoryResource::class;

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
