<?php

namespace App\Filament\Branch\Resources\Bonuses\Pages;

use App\Filament\Branch\Resources\Bonuses\BonusResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBonus extends CreateRecord
{
    protected static string $resource = BonusResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
