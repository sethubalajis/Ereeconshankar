<?php

namespace App\Filament\Resources\AssetAllocations\Pages;

use App\Filament\Resources\AssetAllocations\AssetAllocationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAssetAllocation extends EditRecord
{
    protected static string $resource = AssetAllocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
