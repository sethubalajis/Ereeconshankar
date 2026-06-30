<?php

namespace App\Filament\Resources\CustomerWallets\Pages;

use App\Filament\Resources\CustomerWallets\CustomerWalletResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerWallet extends CreateRecord
{
    protected static string $resource = CustomerWalletResource::class;
}
