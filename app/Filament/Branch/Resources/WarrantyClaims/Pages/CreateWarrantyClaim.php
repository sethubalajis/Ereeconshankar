<?php

namespace App\Filament\Branch\Resources\WarrantyClaims\Pages;

use App\Filament\Branch\Resources\WarrantyClaims\WarrantyClaimResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWarrantyClaim extends CreateRecord
{
    protected static string $resource = WarrantyClaimResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
