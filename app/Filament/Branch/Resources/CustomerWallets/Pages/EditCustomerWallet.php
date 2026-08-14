<?php

namespace App\Filament\Branch\Resources\CustomerWallets\Pages;

use App\Filament\Branch\Resources\CustomerWallets\CustomerWalletResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCustomerWallet extends EditRecord
{
    protected static string $resource = CustomerWalletResource::class;

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
