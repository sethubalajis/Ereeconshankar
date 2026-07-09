<?php

namespace App\Filament\Resources\PosCounters\Pages;

use App\Filament\Resources\PosCounters\PosCounterResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePosCounter extends CreateRecord
{
    protected static string $resource = PosCounterResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
