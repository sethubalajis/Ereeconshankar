<?php

namespace App\Filament\Branch\Resources\EmployeeSalaryAssignments\Pages;

use App\Filament\Branch\Resources\EmployeeSalaryAssignments\EmployeeSalaryAssignmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeSalaryAssignments extends ListRecords
{
    protected static string $resource = EmployeeSalaryAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
