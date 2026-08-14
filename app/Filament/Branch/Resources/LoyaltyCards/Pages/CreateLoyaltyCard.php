<?php

namespace App\Filament\Branch\Resources\LoyaltyCards\Pages;

use App\Filament\Branch\Resources\LoyaltyCards\LoyaltyCardResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLoyaltyCard extends CreateRecord
{
    protected static string $resource = LoyaltyCardResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
