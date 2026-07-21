<?php

namespace App\Filament\Branch\Resources\AssetTransfers\Pages;

use App\Filament\Branch\Resources\AssetTransfers\AssetTransferResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAssetTransfers extends ListRecords
{
    protected static string $resource = AssetTransferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
