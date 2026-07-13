<?php

namespace App\Filament\Resources\AssetDepreciations\Pages;

use App\Filament\Resources\AssetDepreciations\AssetDepreciationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAssetDepreciation extends EditRecord
{
    protected static string $resource = AssetDepreciationResource::class;

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
