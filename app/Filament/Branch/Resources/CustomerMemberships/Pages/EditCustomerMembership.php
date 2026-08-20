<?php

namespace App\Filament\Branch\Resources\CustomerMemberships\Pages;

use App\Filament\Branch\Resources\CustomerMemberships\CustomerMembershipResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCustomerMembership extends EditRecord
{
    protected static string $resource = CustomerMembershipResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
