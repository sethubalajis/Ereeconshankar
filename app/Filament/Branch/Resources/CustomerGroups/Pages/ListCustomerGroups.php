<?php

namespace App\Filament\Branch\Resources\CustomerGroups\Pages;

use App\Filament\Branch\Resources\CustomerGroups\CustomerGroupResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomerGroups extends ListRecords
{
    protected static string $resource = CustomerGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
