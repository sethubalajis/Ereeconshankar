<?php

namespace App\Filament\Resources\RolePermissions\Pages;

use App\Filament\Resources\RolePermissions\RolePermissionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRolePermissions extends ListRecords
{
    protected static string $resource = RolePermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
