<?php

namespace App\Filament\Resources\DamagedProducts\Pages;

use App\Filament\Resources\DamagedProducts\DamagedProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDamagedProduct extends CreateRecord
{
    protected static string $resource = DamagedProductResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
