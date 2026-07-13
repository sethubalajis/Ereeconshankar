<?php

namespace App\Filament\Resources\PatternTypes\Pages;

use App\Filament\Resources\PatternTypes\PatternTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePatternType extends CreateRecord
{
    protected static string $resource = PatternTypeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
