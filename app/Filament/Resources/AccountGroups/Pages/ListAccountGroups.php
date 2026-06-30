<?php

namespace App\Filament\Resources\AccountGroups\Pages;

use App\Filament\Resources\AccountGroups\AccountGroupResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAccountGroups extends ListRecords
{
    protected static string $resource = AccountGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
