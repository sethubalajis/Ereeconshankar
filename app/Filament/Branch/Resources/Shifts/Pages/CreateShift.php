<?php

namespace App\Filament\Branch\Resources\Shifts\Pages;

use App\Filament\Branch\Resources\Shifts\ShiftResource;
use Filament\Resources\Pages\CreateRecord;

class CreateShift extends CreateRecord
{
    protected static string $resource = ShiftResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
