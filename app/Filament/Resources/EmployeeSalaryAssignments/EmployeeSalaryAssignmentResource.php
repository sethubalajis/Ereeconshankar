<?php

namespace App\Filament\Resources\EmployeeSalaryAssignments;

use App\Filament\Resources\EmployeeSalaryAssignments\Pages\CreateEmployeeSalaryAssignment;
use App\Filament\Resources\EmployeeSalaryAssignments\Pages\EditEmployeeSalaryAssignment;
use App\Filament\Resources\EmployeeSalaryAssignments\Pages\ListEmployeeSalaryAssignments;
use App\Filament\Resources\EmployeeSalaryAssignments\Schemas\EmployeeSalaryAssignmentForm;
use App\Filament\Resources\EmployeeSalaryAssignments\Tables\EmployeeSalaryAssignmentsTable;
use App\Models\EmployeeSalaryAssignment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EmployeeSalaryAssignmentResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '17. Payroll';
    protected static ?int $navigationSort = 3;
    protected static ?string $model = EmployeeSalaryAssignment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    public static function form(Schema $schema): Schema
    {
        return EmployeeSalaryAssignmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeeSalaryAssignmentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmployeeSalaryAssignments::route('/'),
            'create' => CreateEmployeeSalaryAssignment::route('/create'),
            'edit' => EditEmployeeSalaryAssignment::route('/{record}/edit'),
        ];
    }
}
