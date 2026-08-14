<?php

namespace App\Filament\Branch\Resources\EmployeeSalaryAssignments\Pages;

use App\Filament\Branch\Resources\EmployeeSalaryAssignments\EmployeeSalaryAssignmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployeeSalaryAssignment extends CreateRecord
{
    protected static string $resource = EmployeeSalaryAssignmentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
