<?php

namespace App\Filament\Branch\Resources\LoyaltyPoints\Pages;

use App\Filament\Branch\Resources\LoyaltyPoints\LoyaltyPointResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLoyaltyPoint extends EditRecord
{
    protected static string $resource = LoyaltyPointResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
