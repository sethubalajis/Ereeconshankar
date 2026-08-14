<?php

namespace App\Filament\Branch\Resources\AssetMaintenances\Pages;

use App\Filament\Branch\Resources\AssetMaintenances\AssetMaintenanceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAssetMaintenance extends CreateRecord
{
    protected static string $resource = AssetMaintenanceResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
