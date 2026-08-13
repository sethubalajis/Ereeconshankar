<?php

namespace App\Filament\Branch\Resources\LoyaltyPoints\Pages;

use App\Filament\Branch\Resources\LoyaltyPoints\LoyaltyPointResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLoyaltyPoint extends CreateRecord
{
    protected static string $resource = LoyaltyPointResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
