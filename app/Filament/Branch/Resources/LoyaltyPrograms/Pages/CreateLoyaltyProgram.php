<?php

namespace App\Filament\Branch\Resources\LoyaltyPrograms\Pages;

use App\Filament\Branch\Resources\LoyaltyPrograms\LoyaltyProgramResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLoyaltyProgram extends CreateRecord
{
    protected static string $resource = LoyaltyProgramResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
