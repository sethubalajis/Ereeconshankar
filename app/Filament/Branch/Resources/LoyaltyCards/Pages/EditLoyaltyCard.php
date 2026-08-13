<?php

namespace App\Filament\Branch\Resources\LoyaltyCards\Pages;

use App\Filament\Branch\Resources\LoyaltyCards\LoyaltyCardResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLoyaltyCard extends EditRecord
{
    protected static string $resource = LoyaltyCardResource::class;

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
