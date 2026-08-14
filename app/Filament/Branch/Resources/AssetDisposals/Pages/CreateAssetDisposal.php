<?php

namespace App\Filament\Branch\Resources\AssetDisposals\Pages;

use App\Filament\Branch\Resources\AssetDisposals\AssetDisposalResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAssetDisposal extends CreateRecord
{
    protected static string $resource = AssetDisposalResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
