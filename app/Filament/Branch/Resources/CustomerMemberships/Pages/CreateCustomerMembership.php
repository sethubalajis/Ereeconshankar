<?php

namespace App\Filament\Branch\Resources\CustomerMemberships\Pages;

use App\Filament\Branch\Resources\CustomerMemberships\CustomerMembershipResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerMembership extends CreateRecord
{
    protected static string $resource = CustomerMembershipResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
