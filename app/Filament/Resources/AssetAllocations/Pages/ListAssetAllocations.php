<?php

namespace App\Filament\Resources\AssetAllocations\Pages;

use App\Filament\Resources\AssetAllocations\AssetAllocationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAssetAllocations extends ListRecords
{
    protected static string $resource = AssetAllocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
