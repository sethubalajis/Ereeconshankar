<?php

namespace App\Filament\Resources\CustomerWallets\Pages;

use App\Filament\Resources\CustomerWallets\CustomerWalletResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomerWallets extends ListRecords
{
    protected static string $resource = CustomerWalletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
