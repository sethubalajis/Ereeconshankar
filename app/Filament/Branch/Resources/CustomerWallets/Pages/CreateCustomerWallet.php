<?php

namespace App\Filament\Branch\Resources\CustomerWallets\Pages;

use App\Filament\Branch\Resources\CustomerWallets\CustomerWalletResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerWallet extends CreateRecord
{
    protected static string $resource = CustomerWalletResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
