<?php

namespace App\Filament\Resources\PosReturns\Pages;

use App\Filament\Resources\PosReturns\PosReturnResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePosReturn extends CreateRecord
{
    protected static string $resource = PosReturnResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
