<?php

namespace App\Filament\Branch\Resources\EmployeeSalaryAssignments\Pages;

use App\Filament\Branch\Resources\EmployeeSalaryAssignments\EmployeeSalaryAssignmentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeSalaryAssignment extends EditRecord
{
    protected static string $resource = EmployeeSalaryAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
