<?php

namespace App\Filament\Resources\EmployeeTaxDeclarations\Pages;

use App\Filament\Resources\EmployeeTaxDeclarations\EmployeeTaxDeclarationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeTaxDeclaration extends EditRecord
{
    protected static string $resource = EmployeeTaxDeclarationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
