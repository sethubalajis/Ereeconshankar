<?php

namespace App\Filament\Resources\LoyaltyPrograms\Pages;

use App\Filament\Resources\LoyaltyPrograms\LoyaltyProgramResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLoyaltyProgram extends CreateRecord
{
    protected static string $resource = LoyaltyProgramResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
