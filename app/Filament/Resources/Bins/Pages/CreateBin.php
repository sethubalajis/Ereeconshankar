<?php

namespace App\Filament\Resources\Bins\Pages;

use App\Filament\Resources\Bins\BinResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBin extends CreateRecord
{
    protected static string $resource = BinResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
