<?php

namespace App\Filament\Branch\Resources\AssetCategories\Pages;

use App\Filament\Branch\Resources\AssetCategories\AssetCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAssetCategory extends CreateRecord
{
    protected static string $resource = AssetCategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
