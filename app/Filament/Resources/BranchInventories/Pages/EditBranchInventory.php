<?php

namespace App\Filament\Resources\BranchInventories\Pages;

use App\Filament\Resources\BranchInventories\BranchInventoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBranchInventory extends EditRecord
{
    protected static string $resource = BranchInventoryResource::class;

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
