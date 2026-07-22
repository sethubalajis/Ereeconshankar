<?php

namespace App\Filament\Branch\Resources\EmployeeTaxDeclarations\Pages;

use App\Filament\Branch\Resources\EmployeeTaxDeclarations\EmployeeTaxDeclarationResource;
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
