<?php

namespace App\Filament\Resources\AssetAllocations\Pages;

use App\Filament\Resources\AssetAllocations\AssetAllocationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAssetAllocation extends CreateRecord
{
    protected static string $resource = AssetAllocationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
