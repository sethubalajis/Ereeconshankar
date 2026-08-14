<?php

namespace App\Filament\Branch\Resources\AssetDepreciations\Pages;

use App\Filament\Branch\Resources\AssetDepreciations\AssetDepreciationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAssetDepreciations extends ListRecords
{
    protected static string $resource = AssetDepreciationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
