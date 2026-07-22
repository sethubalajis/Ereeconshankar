<?php

namespace App\Filament\Branch\Resources\AssetTransfers\Pages;

use App\Filament\Branch\Resources\AssetTransfers\AssetTransferResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAssetTransfer extends CreateRecord
{
    protected static string $resource = AssetTransferResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
