<?php

namespace App\Filament\Resources\EmployeeTaxDeclarations\Pages;

use App\Filament\Resources\EmployeeTaxDeclarations\EmployeeTaxDeclarationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeTaxDeclarations extends ListRecords
{
    protected static string $resource = EmployeeTaxDeclarationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
