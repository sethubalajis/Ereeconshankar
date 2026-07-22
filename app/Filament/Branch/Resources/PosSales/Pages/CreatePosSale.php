<?php

namespace App\Filament\Branch\Resources\PosSales\Pages;

use App\Filament\Branch\Resources\PosSales\PosSaleResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePosSale extends CreateRecord
{
    protected static string $resource = PosSaleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
