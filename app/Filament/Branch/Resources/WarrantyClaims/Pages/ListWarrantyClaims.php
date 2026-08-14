<?php

namespace App\Filament\Branch\Resources\WarrantyClaims\Pages;

use App\Filament\Branch\Resources\WarrantyClaims\WarrantyClaimResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWarrantyClaims extends ListRecords
{
    protected static string $resource = WarrantyClaimResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
