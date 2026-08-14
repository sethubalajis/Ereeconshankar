<?php

namespace App\Filament\Branch\Resources\AssetAllocations\Pages;

use App\Filament\Branch\Resources\AssetAllocations\AssetAllocationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAssetAllocation extends CreateRecord
{
    protected static string $resource = AssetAllocationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
