<?php

namespace App\Filament\Branch\Resources\AssetDisposals\Pages;

use App\Filament\Branch\Resources\AssetDisposals\AssetDisposalResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAssetDisposal extends EditRecord
{
    protected static string $resource = AssetDisposalResource::class;

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
