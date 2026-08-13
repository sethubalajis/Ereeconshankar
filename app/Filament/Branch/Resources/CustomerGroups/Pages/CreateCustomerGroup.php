<?php

namespace App\Filament\Branch\Resources\CustomerGroups\Pages;

use App\Filament\Branch\Resources\CustomerGroups\CustomerGroupResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerGroup extends CreateRecord
{
    protected static string $resource = CustomerGroupResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
