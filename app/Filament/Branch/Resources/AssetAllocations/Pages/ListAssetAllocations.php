<?php

namespace App\Filament\Branch\Resources\AssetAllocations\Pages;

use App\Filament\Branch\Resources\AssetAllocations\AssetAllocationResource;
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
