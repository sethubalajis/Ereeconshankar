<?php

namespace App\Filament\Branch\Resources\PosTerminals\Pages;

use App\Filament\Branch\Resources\PosTerminals\PosTerminalResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePosTerminal extends CreateRecord
{
    protected static string $resource = PosTerminalResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
