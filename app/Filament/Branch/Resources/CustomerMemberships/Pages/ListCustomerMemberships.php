<?php

namespace App\Filament\Branch\Resources\CustomerMemberships\Pages;

use App\Filament\Branch\Resources\CustomerMemberships\CustomerMembershipResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomerMemberships extends ListRecords
{
    protected static string $resource = CustomerMembershipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
