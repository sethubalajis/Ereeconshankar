<?php

namespace App\Filament\Resources\AssetDepreciations\Pages;

use App\Filament\Resources\AssetDepreciations\AssetDepreciationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAssetDepreciation extends CreateRecord
{
    protected static string $resource = AssetDepreciationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
